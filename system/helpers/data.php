<?php defined('ROOT') or die ('Not allowed!');

/**
 * Array Helper
 * -------------------------------------------------------------------------- */

/**
 * Memastikan bahwa $array adalah asosiatif atau tidak
 *
 * @param   array  $array  Array parameter
 * @return  bool
 */
function isArrayAssoc(array $array) {
    $array = array_keys($array);
    $array = array_filter($array, 'is_string');

    return (bool) count($array);
}

/**
 * Menerapkan nilai default pada array
 *
 * @param   array   $array    Array Parameter
 * @param   array   $default  Nilai Default
 * @return  array
 */
function arraySetDefaults(array $array, array $default) {
    foreach ($default as $key => $val) {
        if (!isset($array[$key])) {
            $array[$key] = $val;
        }
    }

    return $array;
}

/**
 * Menerapkan nilai default pada array
 *
 * @param   array   $array    Array Parameter
 * @param   array   $default  Nilai Default
 * @return  array
 */
function arraySetValues(array $array, array $default) {
    foreach ($array as $key => $value) {
        $array[$key] = isset($default[$key]) ? $default[$key] : $value;
    }

    return $array;
}

/**
 * String Helper
 * -------------------------------------------------------------------------- */

/**
 * Menyaring karakter dari $char
 *
 * @param   string  $char  String yang akan disarung
 * @return  string
 */
function escape($char) {
    if (is_numeric($char) || is_int($char)) {
        return (int) $char;
    } elseif (is_string($char)) {
        return htmlspecialchars($char, ENT_QUOTES, 'UTF-8');
    }
}

/**
 * Formating Helper
 * -------------------------------------------------------------------------- */

/**
 * Get formated number
 *
 * @param   double  $number   Decimal number
 * @param   string  $desimal  Decimal count
 * @param   string  $bts_des  Decimal number separator
 * @param   string  $bts_rbn  Tausans number separator
 * @return  string
 */
function formatAngka($number, $desimal = '', $bts_des = '', $bts_rbn = '') {
    $bts_des || $bts_des = ',';
    $bts_rbn || $bts_rbn = '.';

    if ($desimal !== false) {
        $desimal || $desimal = 2;
    }

    if (is_numeric($number) || is_double($number)) {
        return number_format($number, $desimal, $bts_des, $bts_rbn);
    }

    return $number;
}

/**
 * Get formated date from $fmt_date config
 *
 * @param   string  $string  String that will formated
 * @param   string  $format  Date Format (leave it empty to use default config)
 * @return  string
 */
function formatTanggal($string, $format = '') {
    $format || $format = conf('fmtdate');

    return date($format, strtotime($string));
}

/**
 * Date Helper
 * -------------------------------------------------------------------------- */

/**
 * Mendapatkan daftar bulan
 *
 * @return  array
 */
function getBulan() {
    $output = [];

    for ( $i = 1; $i <= 12; $i++) {
        $month = date('F', mktime(0, 0, 0, $i, 1));
        $output[$i] = $month;
    }

    return $output;
}

/**
 * Mendapatkan daftar tahun
 *
 * @param   int    $interfal  Selisih tahun
 * @return  array
 */
function getTahun($interfal = 10) {
    $output = [];

    for ( $i = 0; $i <= $interfal; $i++) {
        $year = $i === 0 ? date('Y') : date('Y', mktime(0, 0, 0, $i, 1, date('Y')-$i));
        $output[$year] = $year;
    }

    return $output;
}
