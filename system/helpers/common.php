<?php defined('ROOT') or die ('Not allowed!');

/**
 * Base Helper
 * -------------------------------------------------------------------------- */

/**
 * Shortcut untuk mendapatkan semua instance kelas App
 *
 * @return  object
 */
function app($container = '') {
    $app =& App::instance();

    if ($container) {
        return $app->get($container);
    }

    return $app;
}

/**
 * Shortcut untuk mendapatkan instance konfigurasi
 *
 * @param   string  $key    Nama Konfigurasi
 * @param   mixed   $value  Nilai Konfigurasi
 * @return  mixed
 */
function conf($key = null, $value = null) {
    static $conf;

    if ($value !== null) {
        return app()->conf($key, $value);
    }

    if (empty($conf)) {
        $conf = app()->conf();
    }

    return isset($conf[$key]) ? $conf[$key] : null;
}

/**
 * URL
 * -------------------------------------------------------------------------- */

/**
 * Basis URL aplikasi
 *
 * @param   string  Permalink
 * @return  string
 */
function siteUrl($permalink = '') {
    if (in_array(substr($permalink, 0, 1), ['#', '?'])) {
        $permalink = app('uri')->path().$permalink;
    }

    return conf('baseurl').$permalink ;
}

/**
 * Digunakan untuk pengalihan halaman (URL)
 *
 * @param   string  $url  URL Tujuan
 * @return  void
 */
function redirect($url = '', $delay = false) {
    if (PHP_SAPI != 'cli') {
        $url = strpos('?', $url) === 1 ? currentUrl($url) : siteUrl($url);

        if ($delay !== false) {
            header("refresh: {$delay}; url={$url}");
        } else {
            header("Location: ".$url);
        }

        unset($_POST, $_GET, $_REQUEST);
        exit();
    }

    return;
}

/**
 * Digunakan untuk mendapatkan URL saat ini
 *
 * @param   string  $permalink  URL tambahan bila perlu
 * @return  string
 */
function currentUrl($permalink = '', $trim = false) {
    $req = !empty($_GET) ? '?'.http_build_query($_GET) : '';
    $url = siteUrl(app('uri')->path().$req);

    if ($permalink) {
        $permalink = '/'.$permalink;
    }

    if ($trim === true) {
        $url = rtrim($url, '/');
    }

    return $url.$permalink;
}

/**
 * Request Helper
 * -------------------------------------------------------------------------- */

/**
 * Mendapatkan nilai dari $_REQUEST request
 *
 * @param   string  Nama field
 * @return  string
 */
function req($key) {
    if (isset($_REQUEST[$key])) {
        return escape($_REQUEST[$key]);
    }
    return;
}

/**
 * Mendapatkan nilai dari $_GET request
 *
 * @param   string  Nama field
 * @return  string
 */
function get($key) {
    if (isset($_GET[$key])) {
        return escape($_GET[$key]);
    }
    return;
}

/**
 * Mendapatkan nilai dari $_POST request
 *
 * @param   string  Nama field
 * @return  string
 */
function post($key, $escape = true) {
    if (isset($_POST[$key])) {
        if (!is_array($_POST[$key]) and $escape === true) {
            return escape($_POST[$key]);
        } else {
            return $_POST[$key];
        }
    }
    return;
}

/**
 * Page Alert
 * -------------------------------------------------------------------------- */

/**
 * Fungsi untuk menyimpan alert
 *
 * @param  string  $type      Type alert
 * @param  mixed   $messages  Isi alert
 */
function setAlert($type, $messages) {
    // Jika tipe tidak terdaftar, maka $type = 'notice'
    if (!in_array($type, ['warning', 'error', 'notice', 'success'])) {
        $type = 'notice';
    }

    // Jika $message bukanlah array, maka jadikan array
    if (!is_array($messages)) {
        $messages = [$messages];
    }

    $alerts = [$type => $messages];

    // Simpan dalam $_SESSION yang di-serialize()
    session('flash', serialize($alerts));
}

/**
 * Fungsi untuk menampilkan alert
 *
 * @return  string
 */
function showAlert() {
    $out = '';
    $alerts = unserialize(session('flash'));

    if (!empty($alerts)) {
        foreach ($alerts as $type => $messages) {
            $out .= '<ul class="alert '.$type.'">';
            $out .= '<li>'.implode('</li><li>', $messages).'</li>';
            $out .= '</ul>';
        }
    }

    return $out;
}
