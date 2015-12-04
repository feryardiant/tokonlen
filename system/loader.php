<?php defined('ROOT') or die ('Not allowed!');

define('SYSPATH', __DIR__.DIRECTORY_SEPARATOR);
define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

/**
 * Memuat Composer autoloader (jika ada)
 */
if (file_exists($composer = 'vendor/autoload.php')) require_once $composer;

spl_autoload_register(function ($class) {
    static $classes = [];

    if (empty($classes)) {
        foreach (glob(__DIR__.'/includes/**.php') as $classPath) {
            $classes[pathinfo($classPath, PATHINFO_FILENAME)] = $classPath;
        }
        foreach (glob(__DIR__.'/../modules/*/includes/**.class.php') as $classPath) {
            $className = str_replace('.class', '', $classPath);
            $classes[pathinfo($className, PATHINFO_FILENAME)] = realpath($classPath);
        }
    }

    if (isset($classes[$class])) require_once $classes[$class];
});

/**
 * Memuat File konfigurasi
 */
$configs = new Config(require 'system/configs.php');

/**
 * Mengaktifkan Mode Debug, ganti 'true' ke 'false' untuk mematikan mode ini.
 * Atau cukup dengan menghapus baris tersebut.
 */
App::debug($configs->get('debug'));

/**
 * Inisialisasi Aplikasi dan menerapkan konfigurasi
 */
$app = new App($configs);

/**
 * Function Loader
 * Memuat semua file yang ada dalam direktory 'helpers'.
 */
foreach (glob(__DIR__.'/helpers/**.php') as $function) {
    require_once $function;
}

/**
 * Inisialisasi Class Database jika terdapat pengaturan Database dalam file Konfigurasi.
 */
$app->add('db', function ($c) {
    return new Db($c->get('db'));
});

return $app;
