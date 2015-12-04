<?php defined('ROOT') or die ('Not allowed!');

/**
 * Array Helper
 * -------------------------------------------------------------------------- */

/**
 * Method untuk menerapkan dan mendapatkan Session Aplikasi
 *
 * @param   string  $key    Session Key
 * @param   string  $value  Session Value (kosongkan untuk mendapatkan nilai
 *                          dari $key, dan isi jika ingin mengubah nilai $Key)
 * @return  mixed
 */
function session($key, $value = null) {
    // Jika $value bernilai null
    if (is_null($value)) {
        // Jika $key bertipe array
        if (is_array($key)) {
            // Menerapkan masing2 isi dari array kedalam session
            foreach ($key as $name => $val) {
                session($name, $val);
            }
        } else {
            // Sebaliknya, akan menampilkan isi dari session $key (jika ada)
            return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
        }
    } else {
        // Sebaliknya, akan mengubah nilai dari $key ke $value yg baru
        $_SESSION[$key] = $value;
    }
}

/**
 * Menghapus Semua Session
 *
 * @return  void
 */
function clearSession($key) {
    if (is_array($key)) {
        foreach ($key as $k) {
            clearSession($k);
        }
    } else {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
}

/**
 * Menghapus Semua Session
 *
 * @return  void
 */
function dropSession() {
    unset($_SESSION);
    session_destroy();
}
