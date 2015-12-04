<?php defined('ROOT') or die('Not allowed!');

return [
    // URL Aplikasi. (http://localhost/aplikasi)
    'baseurl' => '',
    // Basis nama aplikasi
    'basename' => 'tokonlen',
    // Aktifasi debuging
    'debug' => true,
    // Tentang Aplikasi
    'app' => [
        // Judul Aplikasi
        'title' => 'Tokonlen Sederhana',
        // Keterangan Aplikasi
        'desc'  => 'Kepuasan Konsumen adalah Kebanggaan Kami',
        // Basis lokasi (kota)
        'city'  => 'Pekalongan (kota)',
    ],
    'db' => [
        // Database Host
        'host' => '',
        // Database Username
        'user' => '',
        // Database Password
        'pass' => '',
        // Database Name
        'name' => '',
        // Database Output limit
        'limit' => 10,
    ],
    // Raja Ongkir API Key & URL
    'rajaongkir' => [
        'url' => 'http://rajaongkir.com/api/starter/cost',
        'key' => '1badd4b0e4d5dfe22687148b0d1e34c5',
    ],
];
