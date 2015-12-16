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
        $out .= '<del>Rp. '.formatAngka($harga).'</del>';
        $out .= '<span>Rp. '.formatAngka($diskon).'</span>';
    } else {
        $out .= '<span>Rp. '.formatAngka($harga).'</span>';
    }

    return '<div class="price">'.$out.'</div>';
}
