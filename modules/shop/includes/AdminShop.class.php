<?php defined('ROOT') or die ('Not allowed!');

class AdminShop extends Module
{
    public function __construct()
    {
        parent::__construct();

        $this->data['data'] = null;
    }

    public function orders($do = '', $id = '')
    {
        $this->data['heading'] = 'Administrasi: Pembelian';

        switch ($do) {
            case 'form':
                if (post('submit')) {
                    $userKey = User::primary();
                    $customerKey = Customer::primary();
                    $productKey = Product::primary();

                    $order = [
                        $userKey => 1,
                        'status' => post('status') ?: 0,
                    ];

                    if ($tanggal = post('tanggal')) {
                        $order['tanggal'] = formatTanggal($tanggal, 'Y-m-d');
                    } else {
                        $order['tanggal'] = date('Y-m-d');
                    }

                    try {
                        $upload = new Upload('pembayaran');
                        $order['pembayaran'] = $upload->doUpload();
                    } catch (Exception $e) {
                        setAlert('error', $e->getMessage());
                    }

                    if ($id_pelanggan = post($customerKey)) {
                        $order[$customerKey] = $id_pelanggan;
                    } else {
                        $pengguna = [
                            'username' => post('username'),
                            'email'    => post('email'),
                            'level'    => 0,
                            'aktif'    => 1,
                        ];

                        $pelanggan = [
                            'nama_lengkap' => post('nama_lengkap'),
                            'alamat'       => post('alamat'),
                            'kota'         => post('kota'),
                            'telp'         => post('telp'),
                        ];

                        if (($password = post('password')) and $password == post('passconf')) {
                            $pengguna['password'] = $password;
                        }

                        if ($id_pengguna = User::add($pengguna)) {
                            $pelanggan[$userKey] = $id_pengguna;
                        }

                        if ($id_pengguna and ($id_pelanggan = Customer::add($pelanggan))) {
                            $order[$customerKey] = $id_pelanggan;
                        }
                    }

                    if ($produks = post($productKey)) {
                        $produk_qty = post('produk_qty');
                        $produk_arr = [];

                        foreach ($produks as $i => $produk_id) {
                            $produk_arr[$produk_id] =  $produk_qty[$i];
                        }

                        $order['produk'] = serialize($produk_arr);
                    }

                    if (($ongkir = post('ongkir')) and ($kurir = post('kurir'))) {
                        $order['ongkir'] = $ongkir;
                        $order['kurir'] = $kurir;
                    }

                    if (($belanja = post('belanja')) and ($total = post('total'))) {
                        $order['belanja'] = $belanja;
                        $order['total'] = $total;
                    }

                    if ($order['status'] === 0) {
                        $order['potongan'] = post('potongan') ?: 0;
                        $order['bayar']    = post('bayar') ?: 0;
                        $order['kembali']  = post('kembali') ?: 0;

                        if ($order['kembali'] < 0) {
                            $order['kembali'] = 0;
                        }

                        if ($order['bayar'] > 0) {
                            $order['status'] = 1;
                        }
                    }

                    if ($resi = post('resi')) {
                        $order['resi'] = $resi;
                    }

                    if (Order::save($order, $id)) {
                        if ($id) {
                            setAlert('success', 'Berhasil memperbarui data order <b>'.$order['nama'].'</b>');
                        } else {
                            setAlert('success', 'Berhasil menambahkan order <b>'.$order['nama'].'</b>');
                        }

                        return redirect('admin-shop/orders');
                    }

                    setAlert('error', 'Terjadi kesalahan dalam penyimpanan order');
                    return redirect($this->uri->path());
                }

                if ($id) {
                    $order_data = Order::show([Order::primary() => $id])->fetchOne();
                }

                if (
                    !$order_data and
                    (!User::is('admin') or $order_data->id_pelanggan == User::current('id_pelanggan'))
                ) {
                    return redirect('admin-shop/orders');
                }

                $this->data['data'] = $order_data;

                return $this->render('order-form', $this->data);
                break;

            case 'delete':
                if (Order::del([Order::primary() => $id])) {
                    setAlert('success', 'Order berhasil terhapus');
                } else {
                    setAlert('error', 'Terjadi kesalahan dalam penghapusan order');
                }

                return redirect('admin-shop/orders');
                break;

            default:
                $filter = !User::is('admin') ? [Customer::primary() => User::current('id_pelanggan')] : [];
                $this->data['data'] = Order::show($filter, get('sort'));

                return $this->render('order-table', $this->data);
                break;
        }
    }

    public function reports()
    {
        $data['heading'] = 'Administrasi: Laporan';
        $data['data'] = null;

        if (post('submit')) {
            $filtered = false;
            $filter = '';

            if ($status = post('status')) {
                $filtered = true;
                $filter .= 'status = '.($status ? '1' : '0');
            } else {
                $filtered = true;
                $filter .= 'status = 0';
            }

            if ($tgl_mulai = post('tgl_mulai')) {
                if ($filtered) {
                    $filter .= ' AND ';
                }

                $tgl_mulai = date('Y-m-d', strtotime($tgl_mulai));

                if ($tgl_akhir = post('tgl_akhir')) {
                    $tgl_akhir = date('Y-m-d', strtotime($tgl_akhir));
                } else {
                    $tgl_akhir = date('Y-m-d', time());
                }

                $filtered = true;
                $filter .= 'tanggal BETWEEN \''.$tgl_mulai.'\' AND \''.$tgl_akhir.'\'';
            }

            $filter .= $this->normalizeReportOrder('order');

            $data['data'] = Order::show($filter)->fetch(false);
            $data['isSelling'] = false;

            if ($terjual = post('terjual')) {
                $products = [];

                foreach ($data['data'] as $order) {
                    $orderedPrduccts = unserialize($order->produk);

                    foreach ($orderedPrduccts as $prodId => $qty) {
                        $qty = (int) $qty;
                        if (isset($products[$prodId])) {
                            $products[$prodId] += $qty;
                        } else {
                            $products[$prodId] = $qty;
                        }
                    }
                }

                $data['data'] = [];
                $data['isSelling'] = true;

                if ($products) {
                    $prodKeys = array_keys($products);
                    $filter = 'id_produk in ('.implode(',', $prodKeys).')'.$this->normalizeReportOrder('produk');
                    $soldProducts = Product::show($filter)->fetch(false);

                    foreach ($soldProducts as $prodRow) {
                        $data['data'][$prodRow->id_produk] = (object) [
                            'id_produk' => $prodRow->id_produk,
                            'penjualan' => $products[$prodRow->id_produk],
                            'nama'      => $prodRow->nama,
                            'gambar'    => $prodRow->gambar,
                            'harga'     => $prodRow->harga,
                            'diskon'    => $prodRow->diskon,
                        ];
                    }
                }
            }
        }

        return $this->render('report-form', $data);
    }

    private function normalizeReportOrder($table)
    {
        if ($orderby = post('orderby')) {
            list($field, $sort) = explode('_', $orderby);

            if ($field == 'id') {
                $field = 'a.id_'.$table;
            }

            $filter = ' ORDER BY '.$field.' '.$sort;
        } else {
            $filter = ' ORDER BY a.id_'.$table.' ASC';
        }

        $filter = str_replace('a.id_produk', 'id_produk', $filter);
        return $filter;
    }

    public function account()
    {
        $id = User::current('id_pelanggan');

        return $this->customers('form', $id);
    }

    public function customers($do = '', $id = '')
    {
        $this->data['heading'] = 'Administrasi: Pelanggan';

        switch ($do) {
            case 'form':
                if (post('submit')) {
                    $error = [];
                    $pengguna = [];

                    if (!$id) {
                        // Jika ini adalah create submision maka, aktifkan pengguna.
                        $pengguna['aktif'] = 1;
                    } else {
                        // Jika merupakan update submission maka ambil data pengguna yang sudah ada.
                        $user = Customer::show([Customer::primary() => $id])->fetchOne();
                    }

                    // Jika username berbeda dengan yang sebelumnya
                    if ($username = post('username') and $username != $user->username) {
                        $pengguna['username'] = $username;
                    }

                    // Jika email berbeda dengan yang sebelumnya
                    if ($email = post('email') and $email !== $user->email) {
                        $pengguna['email'] = $email;
                    }

                    // Jika password bernilai sama dengan passconf
                    if (($password = post('password')) and $password == post('passconf')) {
                        $pengguna['password'] = md5($password);
                    }

                    $pelanggan = [
                        'nama_lengkap' => post('nama_lengkap'),
                        'alamat' => post('alamat'),
                        'telp' => post('telp'),
                    ];

                    if ($userId = User::save($pengguna, $id)) {
                        $pelanggan[User::primary()] = $id ?: $userId;

                        if (Customer::save($pelanggan, $id)) {
                            if ($id) {
                                setAlert('success', 'Berhasil memperbarui data pelanggan <b>'.$pelanggan['nama'].'</b>');
                            } else {
                                setAlert('success', 'Berhasil menambahkan pelanggan <b>'.$pelanggan['nama'].'</b>');
                            }
                        } else {
                            setAlert('error', 'Data yang anda masukan masih sama, tidak ada update data');
                        }
                    } else {
                        setAlert('notice', 'Data yang anda masukan masih sama, tidak ada update data');
                    }

                    return redirect('admin-shop/customers');
                }

                if ($id) {
                    $this->data['data'] = Customer::show([Customer::primary() => $id])->fetchOne();
                }

                return $this->render('customer-form', $this->data);
                break;

            case 'delete':
                if (Customer::del([Customer::primary() => $id])) {
                    setAlert('success', 'Pelanggan berhasil terhapus');
                } else {
                    setAlert('error', 'Terjadi kesalahan dalam penghapusan pelanggan');
                }

                return redirect('admin-shop/customers');
                break;

            default:
                $this->data['data'] = Customer::show([], get('sort'));

                return $this->render('customer-table', $this->data);
                break;
        }
    }

    public function products($do = '', $id = '')
    {
        $this->data['heading'] = 'Administrasi: Produk';

        switch ($do) {
            case 'form':
                if (post('submit')) {
                    $data = [
                        User::primary() => User::current('id'),
                        'tgl_input'   => date('Y-m-d'),
                        'id_kategori' => post('kategori'),
                        'nama'        => post('nama'),
                        'gambar'      => post('gambar'),
                        'tgl_masuk'   => formatTanggal(post('tgl_masuk'), 'Y-m-d'),
                        'stok'        => post('stok'),
                        'harga'       => post('harga'),
                        'berat'       => post('berat'),
                        'diskon'      => post('diskon') ?: 0,
                        'keterangan'  => post('keterangan', false),
                    ];

                    try {
                        $upload = new Upload('gambar');
                        $data['gambar'] = $upload->doUpload();
                    } catch (Exception $e) {
                        setAlert('error', $e->getMessage());
                        return redirect($this->uri->path());
                    }

                    if (Product::save($data, $id)) {
                        if ($id) {
                            setAlert('success', 'Berhasil memperbarui data produk <b>'.$data['nama'].'</b>');
                        } else {
                            setAlert('success', 'Berhasil menambahkan produk <b>'.$data['nama'].'</b>');
                        }

                        return redirect('admin-shop/products');
                    }

                    setAlert('error', 'Terjadi kesalahan dalam penyimpanan produk <b>'.$data['nama'].'</b>');
                    return redirect($this->uri->path());
                } else {
                    if ($id) {
                        $this->data['data'] = Product::show([Product::primary() => $id])->fetchOne();
                    }

                    return $this->render('product-form', $this->data);
                }
                break;

            case 'delete':
                if (Product::del([Product::primary() => $id])) {
                    setAlert('success', 'Produk berhasil terhapus');
                } else {
                    setAlert('error', 'Terjadi kesalahan dalam penghapusan produk');
                }

                return redirect('admin-shop/products');
                break;

            default:
                $this->data['data'] = Product::show([], get('sort'));

                return $this->render('product-table', $this->data);
                break;
        }
    }

    public function banners($do = '', $id = '')
    {
        $this->data['heading'] = 'Administrasi: Banner';

        switch ($do) {
            case 'form':
                if (post('submit')) {
                    $data = [
                        User::primary() => 1,
                        'tgl_input'   => date('Y-m-d'),
                        'judul'       => post('judul'),
                        'keterangan'  => post('keterangan'),
                        'url'         => post('url'),
                        'gambar'      => post('gambar'),
                        'tgl_mulai'   => formatTanggal(post('tgl_mulai'), 'Y-m-d'),
                        'tgl_akhir'   => formatTanggal(post('tgl_akhir'), 'Y-m-d'),
                        'aktif'       => post('aktif'),
                        'tipe'        => post('tipe'),
                    ];

                    try {
                        $upload = new Upload('gambar');
                        $data['gambar'] = $upload->doUpload();
                    } catch (Exception $e) {
                        setAlert('error', $e->getMessage());
                    }

                    if (Banner::save($data, $id)) {
                        if ($id) {
                            setAlert('success', 'Berhasil memperbarui data banner <b>'.$data['judul'].'</b>');
                        } else {
                            setAlert('success', 'Berhasil menambahkan banner <b>'.$data['judul'].'</b>');
                        }

                        return redirect('admin-shop/banners');
                    }

                    setAlert('error', 'Terjadi kesalahan dalam penyimpanan banner <b>'.$data['judul'].'</b>');
                    return redirect($this->uri->path());
                } else {
                    if ($id) {
                        $this->data['data'] = Banner::show([Banner::primary() => $id])->fetchOne();
                    }

                    return $this->render('banner-form', $this->data);
                }
                break;

            case 'delete':
                if (Banner::del([Banner::primary() => $id])) {
                    setAlert('success', 'Banner berhasil terhapus');
                } else {
                    setAlert('error', 'Terjadi kesalahan dalam penghapusan banner');
                }

                return redirect('admin-shop/banners');
                break;

            default:
                $this->data['data'] = Banner::show();

                return $this->render('banner-table', $this->data);
                break;
        }
    }

    public function categories($do = '', $id = '')
    {
        $this->data['heading'] = 'Administrasi: Kategori';

        switch ($do) {
            case 'form':
                if (post('submit')) {
                    $data = [
                        'nama'       => post('nama'),
                        'alias'      => post('alias'),
                        'keterangan' => post('keterangan'),
                    ];

                    if (Category::save($data, $id)) {
                        if ($id) {
                            setAlert('success', 'Berhasil memperbarui data kategori <b>'.$data['nama'].'</b>');
                        } else {
                            setAlert('success', 'Berhasil menambahkan kategori <b>'.$data['nama'].'</b>');
                        }

                        return redirect('admin-shop/categories');
                    }

                    setAlert('error', 'Terjadi kesalahan dalam penyimpanan kategori <b>'.$data['nama'].'</b>');
                    return redirect($this->uri->path());
                } else {
                    if ($id) {
                        $this->data['data'] = Category::show([Category::primary() => $id])->fetchOne();
                    }

                    return $this->render('category-form', $this->data);
                }
                break;

            case 'delete':
                if (Category::del([Category::primary() => $id])) {
                    setAlert('success', 'Kategori berhasil terhapus');
                } else {
                    setAlert('error', 'Terjadi kesalahan dalam penghapusan kategori');
                }

                return redirect('admin-shop/categories');
                break;

            default:
                $this->data['data'] = Category::show();

                return $this->render('category-table', $this->data);
                break;
        }
    }
}
