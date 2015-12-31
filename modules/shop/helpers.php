<?php

/**
 * Function untuk menampilkan data harga
 *
 * @param  int $harga  Harga asli
 * @param  int $diskon Harga diskon
 * @return string
 */
function shopHarga($harga, $diskon) {
    $out = '';

    if ($diskon) {
        $out .= '<del>Rp. '.format_number($harga).'</del>';
        $out .= '<span>Rp. '.format_number($diskon).'</span>';
    } else {
        $out .= '<span>Rp. '.format_number($harga).'</span>';
    }

    return '<div class="price">'.$out.'</div>';
}
