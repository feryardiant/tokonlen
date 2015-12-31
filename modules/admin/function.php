<?php defined('ROOT') or die ('Not allowed!');

class Admin extends Module
{
    public function __construct()
    {
        parent::__construct();

        if (!User::loggedin()) redirect('login');
    }

    /**
     * {inheritdoc}
     */
    public static function initialize(App $app = null)
    {
        defined('ADMIN_SIDEBAR') or define('ADMIN_SIDEBAR', __DIR__.'/layouts/sidebar.php');
    }

    public function index()
    {
        redirect('admin-shop/orders');

        return $this->render('dashboard', [
            'heading' => 'Administrasi',
            'navigation' => [],
        ]);
    }

    public function pages($do = '', $id = '')
    {
        $data = ['heading' => 'Administrasi: Halaman'];

        switch ($do) {
            case 'form':
                if (post('submit')) {
                    $data = [
                        'id_pengguna' => User::current('id'),
                        'tgl_input' => date('Y-m-d'),
                        'judul' => post('judul'),
                        'alias' => post('alias'),
                        'konten' => post('konten', false),
                    ];

                    $data['konten'] = str_replace(['<br>', '<br/>'], '', $data['konten']);

                    if (Page::save($data, $id)) {
                        if ($id) {
                            set_alert('success', 'Halaman <b>'.$data['judul'].'</b> berhasil diperbarui');
                        } else {
                            set_alert('success', 'Halaman <b>'.$data['judul'].'</b> berhasil dibuat');
                        }

                        return redirect('admin/pages');
                    }

                    set_alert('error', 'Terjadi kesalahan dalam penyimpanan halaman <b>'.$data['judul'].'</b>');
                    return redirect($this->uri->path());
                }

                if ($id) {
                    $data['data'] = Page::show([Page::primary() => $id])->fetchOne();
                }

                return $this->render('page-form', $data);
                break;

            case 'delete':
                if (Page::del([Page::primary() => $id])) {
                    set_alert('success', 'Halaman berhasil terhapus');
                } else {
                    set_alert('error', 'Terjadi kesalahan dalam penghapusan halaman');
                }

                return redirect('admin/pages');
                break;

            default:
                $data['data'] = Page::show();

                return $this->render('page-table', $data);
                break;
        }
    }

    public function users($do = '', $id = '')
    {
        $data = ['heading' => 'Administrasi: Pengguna'];

        switch ($do) {
            case 'form':
                if (post('submit')) {
                    $data = [
                        'username' => post('username'),
                        'email' => post('email'),
                    ];

                    if (User::is('admin')) {
                        $data['level'] = post('level');
                    }

                    if (($password = post('password')) and $password == post('passconf')) {
                        $data['password'] = $password;
                    }

                    if (User::save($data, $id)) {
                        if ($id) {
                            set_alert('success', 'Berhasil memperbarui data pengguna <b>'.$data['username'].'</b>');
                        } else {
                            set_alert('success', 'Berhasil menambahkan <b>'.$data['username'].'</b> sebagai pengguna');
                        }

                        return redirect('admin/users');
                    }

                    set_alert('error', 'Terjadi kesalahan dalam penyimpanan pengguna <b>'.$data['username'].'</b>');
                    return redirect($this->uri->path());
                }

                if ($id) {
                    $data['data'] = User::show([User::primary() => $id])->fetchOne();
                }

                return $this->render('user-form', $data);
                break;

            case 'delete':
                if (User::del([User::primary() => $id])) {
                    set_alert('success', 'Pengguna berhasil terhapus');
                } else {
                    set_alert('error', 'Terjadi kesalahan dalam penghapusan pengguna');
                }

                return redirect('admin/users');
                break;

            default:
                $data['data'] = User::show();

                return $this->render('user-table', $data);
                break;
        }
    }
}
