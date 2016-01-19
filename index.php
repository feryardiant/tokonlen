<?php

/**
 * Konstanta Aplikasi
 */
define('DS',   DIRECTORY_SEPARATOR);
define('ROOT', __DIR__.DS);

define('EXT', '.php');

/**
 * Memuat Sistem
 */
$app = require 'system/loader.php';

$app->add('routes', function ($c, $name) {
    $routes = [
        'login'    => 'home/login',
        'register' => 'home/register',
        'logout'   => 'home/logout',
        'cart'     => 'shop/cart',
        'account'  => 'admin/account',
    ];

    $c->add('pages', function() {
        return Page::show()->fetch(false);
    });

    foreach ($c->get('pages') as $row) {
        $routes[$row->alias] = 'home/index/'.$row->alias;
    }

    $c->merge($name, $routes);

    return $routes;
});

/**
 * Inisialisasi Menu.
 */
$app->add('main-menu', function ($c) {
    $menu = [
        '/' => 'Home',
        '/shop' => 'Toko',
    ];

    foreach ($c->get('pages') as $row) {
        $menu['/'.$row->alias] = $row->judul;
    }

    return new Menu($menu);
});

$app->add('user-menu', function () {
    $items = [];
    if ($_tmp = session('cart-items')) {
        $items = unserialize($_tmp);
    }

    $menu = [
        '/cart'  => 'Trolli '.count($items),
        '/admin' => 'Akun Saya',
    ];

    if (User::loggedin()) {
        $admin_label = $menu['/admin'];
        $menu['/admin'] = [
            'label' => User::current('username') ?: $admin_label,
            'subs'  => ['/logout' => 'Logout']
        ];
    }

    return new Menu($menu);
});

$app->add('admin-menu', function () {
    $menu = [];
    if (User::is('admin')) {
        $menu['admin/pages'] = 'Halaman';
        $menu['admin/users'] = 'Pengguna';
        $menu[] = '-';
    }

    return new Menu($menu);
});

/**
 * Memulai Sistem
 */
$app->start();
