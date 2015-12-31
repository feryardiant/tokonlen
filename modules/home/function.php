<?php defined('ROOT') or die ('Not allowed!');

class Home extends Module
{
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
        } else {
            $data['heading']  = 'Selamat datang di website resmi '.conf('app.title');
            $data['products'] = Product::show()->fetch(5);
            $data['slides']   = Banner::show([
                'tipe'  => 'slide',
                'aktif' => 1,
            ])->fetch(3);

            return $this->render('home', $data);
        }
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
                    $pelanggan = Customer::show(['id_pengguna' => $user->id_pengguna])->fetchOne();
                    $login['id_pelanggan'] = $pelanggan->id_pelanggan;
                }

                session($login);
                redirect('admin-shop/orders');
            } else {
                set_alert('error', 'Login gagal');
            }
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
                    redirect('login');
                } else {
                    set_alert('error', 'Maaf registrasi gagal');
                    redirect('register');
                }
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
        redirect('');
    }
}
