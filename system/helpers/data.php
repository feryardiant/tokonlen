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
function array_is_assocc(array $array) {
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
function array_set_defaults(array $array, array $default) {
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
function array_set_value(array $array, array $default) {
    foreach ($array as $key => $value) {
        $array[$key] = isset($default[$key]) ? $default[$key] : $value;
    }

    return $array;
}

/**
 * Data order
 * -------------------------------------------------------------------------- */

function sort_by($field, $label) {
    $uri = app('uri');

    // Mendapatkan nilai untuk ordering data
    if ($sort = get('sort')) {
        if (strpos($sort, ':') !== false) {
            list($by, $order) = explode(':', $sort);
            $order = strtolower($order);
            $order = in_array($order, ['asc', 'desc']) ? $order : 'desc';
        } else {
            $by = $field;
            $order = 'desc';
        }

        $order = $order == 'desc' ? 'asc' : 'desc';

        if ($field !== $by) {
            $by = $field;
            $order = 'desc';
        }

        $sort = 'sort='.$by.':'.$order;
    } else {
        $sort = 'sort='.$field.':desc';
    }

    $query = [];
    if (isset($_SERVER['QUERY_STRING'])) {
        $query = explode('&', $_SERVER['QUERY_STRING']);
        $query = array_filter($query, function ($val) {
            if (strpos($val, 'sort=') === 0) {
                return '';
            }
            return $val;
        });
    }

    if (!empty($query)) {
        $sort = '?'.implode('&', $query).'&'.$sort;
    } else {
        $sort = '?'.$sort;
    }

    return anchor($uri->path().$sort, $label);
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
function format_number($number, $desimal = '', $bts_des = '', $bts_rbn = '') {
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
function format_date($string, $format = '') {
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
function get_months_array() {
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
function get_years_array($interfal = 10) {
    $output = [];

    for ( $i = 0; $i <= $interfal; $i++) {
        $year = $i === 0 ? date('Y') : date('Y', mktime(0, 0, 0, $i, 1, date('Y')-$i));
        $output[$year] = $year;
    }

    return $output;
}
