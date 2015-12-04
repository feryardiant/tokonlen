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

$app->add('routes', function ($c) {
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

$app->add('user-menu', function ($c) {
    $items = [];
    if ($_tmp = session('cart-items')) {
        $items = unserialize($_tmp);
    }

    $menu = [
        '/cart'  => 'Trolli '.count($items),
        '/admin' => 'Akun Saya',
    ];

    if (User::loggedin()) {
        $admin_label    = $menu['/admin'];
        $menu['/admin'] = [
            'label' => User::current('username') ?: 'Akun Saya',
            'subs'  => ['/logout' => 'Logout']
        ];
    }

    return new Menu($menu);
});

$app->add('admin-menu', function ($c) {
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
