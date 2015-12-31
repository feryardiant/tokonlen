<?php defined('ROOT') or die ('Not allowed!');

class Shop extends Module
{
    public function __construct()
    {
        parent::__construct();

        $this->data['kategori'] = Category::show()->fetch(false);
    }

    /**
     * {inheritdoc}
     */
    public static function initialize(App $app = null)
    {
        $app->add('routes', function ($c, $name) use ($app) {
            $routes = $app->get($name);
            $routes['admin-shop'] = 'shop/admin';
            return $routes;
        });

        $nav = [];

        if (User::is('admin')) {
            $nav['/admin-shop/categories'] = 'Kategori';
            $nav['/admin-shop/customers']  = 'Pelanggan';
            $nav['/admin-shop/banners']    = 'Banner';
            $nav['/admin-shop/products']   = 'Produk';
            $nav['/admin-shop/reports']    = 'Laporan';
        } else {
            $nav['/admin-shop/account']    = 'Akun saya';
        }

        $nav['/admin-shop/orders'] = 'Order';

        $app->get('admin-menu')->prepend($nav);
    }

    public function index($by = '', $term = '')
    {
        $search = get('search');
        if ($by == 'search') {
            $by = '';
            $search = $term;
        }

        if ($by) {
            $class = ucfirst($by);
            $prop = $class::show(['alias' => $term])->fetchOne();
            $prim = $class::primary();
            $filter = [$prim => $prop->$prim];
            $heading = 'Semua produk '.$prop->nama;
        } elseif ($search) {
            $filter = "nama like '%$search%'";
            $heading = 'Hasil pencarian produk: '.$search;
        } else {
            $filter = [];
            $heading = 'Silahkan pilih produk yang Anda sukai';
        }

        $data = Product::show($filter);

        return $this->render('shop', compact('heading', 'data'));
    }

    public function admin()
    {
        if (!User::loggedin()) redirect('login');

        $admin  = new AdminShop();
        $params = func_get_args();
        $method = array_shift($params);

        if (method_exists($admin, $method)) {
            return call_user_func_array([$admin, $method], $params);
        } else {
            return $this->app->show404();
        }
    }

    public function product($id)
    {
        $data = Product::show($id)->fetchOne();

        if (!$data) {
            return $this->app->show404();
        }

        return $this->render('single', [
            'heading' => $data->nama,
            'data' => $data,
        ]);
    }

    public function cart()
    {
        $items = [];
        if ($_tmp = session('cart-items')) {
            $items = unserialize($_tmp);
        }

        if ($do = get('do')) {
            $id = get('id');
            if ($do != 'clear' and !$id) {
                redirect('shop');
            }

            session('cart-items', serialize(Order::cart($id, $do, $items)));
            return redirect('cart');
        }

        $data = [
            'items' => $items,
            'heading' => 'Keranjang Belanja: '.($items ? count($items).' Produk' : 'Masih kosong :('),
        ];
        $cartItems = array_keys($items);

        if (count($cartItems) > 0) {
            $data['data'] = Product::show(Product::primary().' in ('.implode(',', $cartItems).')');
        } else {
            $data['data'] = false;
        }

        return $this->render('cart', $data);
    }

    public function checkout()
    {
        if (!User::current('id')) redirect('login');

        if ($items = session('cart-items')) {
            $userId = User::current('id');
            $userKey = User::primary();
            $custKey = Customer::primary();
            $pelanggan = Customer::show([$userKey => $userId])->fetchOne();
            $order = [
                $userKey  => $userId,
                $custKey  => $pelanggan->$custKey,
                'tanggal' => date('Y-m-d'),
                'produk'  => $items,
                'belanja' => post('belanja'),
                'kurir'   => post('kurir'),
                'ongkir'  => post('ongkir'),
                'bayar'   => 0,
                'kembali' => 0,
            ];

            if (!$order['ongkir'] && !$order['kurir']) {
                set_alert('error', 'Estimasi ongkos kirim belum ada, pastikan field kurir sudah diisi.');
                return redirect('cart');
            }

            $order['total'] = $order['belanja'] + $order['ongkir'];

            if ($return = Order::add($order)) {
                session('cart-items', '');
                set_alert('success', [
                    'Terima kasih telah berbelanja di '.conf('app.title').'.',
                    'Segeralah melakukan pembayaran agar pesanan anda dapat secepatnya kami proses.'
                ]);
            } else {
                set_alert('error', 'Terjadi kesalahan dalam penghapusan order');
                return redirect('cart');
            }
        }

        if (User::loggedin()) {
            return redirect('admin-shop/orders/form/'.$return);
        }

        return redirect('shop');
    }

    public function api($table, $field)
    {
        $code = 200;
        $result = null;
        $tables = [
            'pelanggan' => 'Customer',
            'produk' => 'Product',
        ];

        $class = $tables[$table];
        $result = $class::show($field.' like \'%'.post('s').'%\'')->fetch();

        if (!$result) {
            $code = 404;
            $result = ['errors' => ['Pelanggan tidak ditemukan']];
        }

        return $this->render($code, (array) $result);
    }

    public function shipment()
    {
        $code = 200;
        // extract data from the post
        extract($_POST);

        // set POST variables
        $fields_string = '';
        $errors = [];
        $result = [];
        $url = conf('rajaongkir.url');
        $fields = [
            'key'         => conf('rajaongkir.key'),
            'origin'      => post('origin'),
            'destination' => post('destination'),
            'weight'      => post('weight'),
            'courier'     => post('courier'),
        ];

        foreach (array_keys($fields) as $fieldName) {
            if (empty($fields[$fieldName])) {
                $errors[] = 'Field '.$fieldName.' is required';
            }
        }

        if (!empty($errors)) {
            $code = 400;
            $result['errors'] = $errors;
        } else {
            // open connection
            $ch = curl_init();

            // set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, count($fields));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));

            // execute post
            if (($exec = curl_exec($ch)) !== false) {
                $exec = json_decode($exec);
                $exec = $exec->rajaongkir;
                if (isset($exec->status->code) && $exec->status->code >= 400) {
                    $code = $exec->status->code;
                    $result['errors'] = ['Request error: '.$exec->status->description];
                } else {
                    $result = (array) $exec->results[0];
                }
            } else {
                $code = 400;
                $result['errors'] = ['Request error: '.curl_error($ch)];
            }

            // close connection
            curl_close($ch);
        }

        return $this->render($code, $result);
    }
}
