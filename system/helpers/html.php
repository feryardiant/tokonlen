<?php defined('ROOT') or die ('Not allowed!');

/**
 * Link
 * -------------------------------------------------------------------------- */

/**
 * Setup Anchor tag
 *
 * @param   mixed   $url    Path or Permalink
 * @param   string  $label  Text label
 * @return  string
 */
function anchor($url, $label = '', array $extras = []) {
    if (is_array($url) and $label == '') {
        $attrs = $url;
        $label = $attrs['label'];
        unset($attrs['label']);
    } else {
        $attrs = ['href' => $url];
    }

    $attrs = array_merge($extras, $attrs);
    if ($attrs['href'] == '' || substr($attrs['href'], 0, 2) != '//') {
        $attrs['href'] = siteUrl($attrs['href']);
    } elseif (strpos('?', $attrs['href']) === 1) {
        $attrs['href'] = currentUrl($attrs['href'], true);
    }

    if (!isset($attrs['class']) or (isset($attrs['class']) and strpos($attrs['class'], 'btn') === false)) {
        $attrs['tabindex'] = '-1';
    }

    return '<a '.parseAttrs($attrs).'>'.$label.'</a>';
}


/**
 * Attribute
 * -------------------------------------------------------------------------- */

/**
 * Get Body Attributes
 *
 * @return  string
 */
function bodyAttrs($class = null) {
    $attrs = ['id' => 'home', 'class' => 'halaman'];
    $classes = [];

    if ($segments = app('uri')->segments()) {
        $attrs['id'] = implode('-', $segments);

        foreach ($segments as $segment => $path) {
            $prev = ($tmp = $segment - 1) > 0 ? $tmp : 0;
            if ($segment > $prev) {
                $classes[$segment] = $classes[$prev].'-'.$segments[$segment];
            } else {
                $classes[$segment] = $segments[$segment];
            }
        }
    }

    if ($class !== null) {
        if (is_string($class)) {
            $class = explode(' ', $class);
        }
        $classes += $class;
    }

    $attrs['class'] .= ' '.implode(' ', array_unique($classes));

    echo parseAttrs($attrs);
}

/**
 * Get html attributes from array
 *
 * @param   array   $attrs  HTML Attributes
 * @return  string
 */
function parseAttrs(array $attrs) {
    if (empty($attrs)) return;

    $attr = [];
    foreach ($attrs as $key => $value) {
        // Jika $value bernilai boolean true, maka ambil $key sebagai $value
        if ($value === true) {
            $value = $key;
        }
        // Jika $value bernilai null, maka $value adl string kosong
        if ($value === null) {
            $value = '';
        }
        // Jika $value bertipe array, maka gabungkan $value dengan spasi sebagai pembatas
        if (is_array($value)) {
            $value = implode(' ', $value);
        }
        // Jika $value bertipe string, filter dari karakter yang tidak diinginkan
        if (is_string($value)) {
            $value = escape($value);
        }
        // Jika $value tidak bernilai boolean false, maka tampilkan output
        if ($value !== false) {
            $attr[] = $key.'="'.$value.'"';
        }
    }

    return implode(' ', $attr);
}

/**
 * Pagination
 * -------------------------------------------------------------------------- */

function pagination($total) {
    $output = '';
    $limit = conf('db.limit');

    if (($last_page = ceil($total / $limit)) > 1) {

        $start = $counter = 0;
        $target = rtrim(app('uri')->path(), '/').'?hal=';
        $adjacents = 2;

        /* Setup page vars for display. */
        $num = req('hal') ?: 1;       // if no page var is given, default to 1.
        $prev_page = $num - 1;        // previous page is page - 1
        $next_page = $num + 1;        // next page is page + 1
        $lpm1_page = $last_page - 1;  // last page minus 1

        $dis_btn = '<span class="disabled btn">%s</span>';
        $prev_text = 'Sebelum';
        $next_text = 'Sesudah';
        $btn_class = ['class' => 'btn'];
        $output .= '<p class="btn-group">';
        // previous button
        if ($num > 1) {
            $output .= anchor($target.$prev_page, $prev_text, $btn_class);
        } else {
            $output .= sprintf($dis_btn, $prev_text);
        }

        // pages
        // not enough pages to bother breaking it up
        if ($last_page < 7 + ($adjacents * 2)) {
            for ($counter = 1; $counter <= $last_page; $counter++) {
                if ($counter == $num) {
                    $output .= sprintf($dis_btn, $counter);
                } else {
                    $output .= anchor($target.$counter, $counter, $btn_class);
                }
            }
        }
        // enough pages to hide some
        elseif ($last_page > 5 + ($adjacents * 2)) {
            // close to beginning; only hide later pages
            if ($num < 1 + ($adjacents * 2)) {
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                    if ($counter == $num) {
                        $output .= sprintf($dis_btn, $counter);
                    } else {
                        $output .= anchor($target.$counter, $counter, $btn_class);
                    }
                }

                $output .= sprintf($dis_btn, '...');
                $output .= anchor($target.$lpm1_page, $lpm1_page, $btn_class);
                $output .= anchor($target.$last_page, $last_page, $btn_class);
            }
            // in middle; hide some front and some back
            elseif ($last_page - ($adjacents * 2) > $num && $num > ($adjacents * 2)) {
                $output .= anchor($target.'1', '1', $btn_class);
                $output .= anchor($target.'2', '2', $btn_class);
                $output .= sprintf($dis_btn, '...');

                for ($counter = $num - $adjacents; $counter <= $num + $adjacents; $counter++) {
                    if ($counter == $num) {
                        $output .= sprintf($dis_btn, $counter);
                    } else {
                        $output .= anchor($target.$counter, $counter, $btn_class);
                    }
                }

                $output .= sprintf($dis_btn, '...');
                $output .= anchor($target.$lpm1_page, $lpm1_page, $btn_class);
                $output .= anchor($target.$last_page, $last_page, $btn_class);
            }
            // close to end; only hide early pages
            else {
                $output .= anchor($target.'1', '1', $btn_class);
                $output .= anchor($target.'2', '2', $btn_class);
                $output .= sprintf($dis_btn, '...');

                for ($counter = $last_page - (2 + ($adjacents * 2)); $counter <= $last_page; $counter++) {
                    if ($counter == $num) {
                        $output .= sprintf($dis_btn, $counter);
                    } else {
                        $output .= anchor($target.$counter, $counter, $btn_class);
                    }
                }
            }
        }

        // previous button
        if ($num < $counter - 1) {
            $output .= anchor($target.$next_page, $next_text, $btn_class);
        } else {
            $output .= sprintf($dis_btn, $next_text);
        }

        $output .= "</p>";
    }

    return $output;
}
