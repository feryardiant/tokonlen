<?php defined('ROOT') or die ('Not allowed!');

class Home extends Module
{
    /**
     * {inheritdoc}
     */
    public static function initialize(App $app = null, Config $config = null)
    {
        $config->push('asset.css', [__DIR__.'/asset/style.css']);
        $config->push('asset.js',  [__DIR__.'/asset/script.js']);
    }

    public function index($alias = null)
    {
        $data = ['pages' => Page::show()->fetch(false)];

        if ($alias !== null) {
            $page = Page::show(['alias' => $alias])->fetchOne();
            if (!$page) {
                return $this->app->show404();
            }

            $data['heading'] = $page->judul;
            $data['content'] = $page->konten;

            return $this->render('page', $data);
        }

        $data['heading']  = 'Selamat datang di website resmi '.conf('app.title');
        $data['products'] = Product::show()->fetch(5);
        $data['slides']   = Banner::show([
            'tipe'  => 'slide',
            'aktif' => 1,
        ])->fetch(3);

        return $this->render('home', $data);
    }

    public function login()
    {
        if (post('login')) {
            $login = [
                'username' => post('username'),
                'password' => md5(post('password')),
            ];

            if ($user = User::show($login)->fetchOne()) {
                $login = [
                    'auth'     => 1,
                    'id'       => $user->id_pengguna,
                    'username' => $user->username,
                    'level'    => $user->level,
                ];

                if ($user->level !== 1) {
                    $pelanggan = Customer::show([User::primary() => $user->id_pengguna])->fetchOne();
                    $login[Customer::primary()] = $pelanggan->id_pelanggan;
                }

                session($login);
                return redirect('admin-shop/orders');
            }

            set_alert('error', 'Login gagal');
        }

        return $this->render('form-login', [
            'heading' => 'Silahkan login'
        ]);
    }

    public function register()
    {
        if (post('register')) {
            $pengguna = [
                'username' => post('username'),
                'email' => post('email'),
                'level' => 0,
                'aktif' => 1,
            ];

            $pelanggan = [
                'nama_lengkap' => post('nama'),
                'alamat'       => post('alamat'),
                'kota'         => post('kota'),
                'telp'         => post('telp'),
            ];

            if (post('password') == post('passconf')) {
                $pengguna['password'] = md5(post('password'));
            }

            if ($userId = User::add($pengguna)) {
                $pelanggan['id_pengguna'] = $userId;

                if (Customer::add($pelanggan)) {
                    set_alert('success', 'Registrasi berhasil, silahkan login ke akun yang baru saja anda buat');
                    return redirect('login');
                }

                set_alert('error', 'Maaf registrasi gagal');
                return redirect('register');
            }
        }

        return $this->render('form-register', [
            'heading' => 'Silahkan register'
        ]);
    }

    public function logout()
    {
        session(['auth' => 0, 'id' => '', 'username' => '', 'level' => '']);
        session_clear(['auth', 'id', 'username', 'level']);

        return redirect('');
    }
}
