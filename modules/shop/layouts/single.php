<?php defined('ROOT') or die ('Not allowed!'); require __DIR__.'/sidebar.php'; ?>
<div id="main-contents">
    <div class="product-single clearfix">
        <img src="<?php echo siteUrl('asset/uploads/'.$data->gambar) ?>" alt="<?php echo $data->nama ?>">
        <div class="detail">
            <p><?php echo $data->keterangan ?></p>
            <div class="meta">
                <dl>
                    <dt>Stok</dt>
                    <dd><?php echo $data->stok ?: 'Maaf stok habis' ?></dd>
                    <dt>Berat</dt>
                    <dd><?php echo formatAngka($data->berat, false).' Gram' ?></dd>
                </dl>
            </div>
            <hr>
            <?php echo $data->stok ? anchor('cart/?id='.$data->id_produk.'&do=add', 'Beli', ['class' => 'btn fright']) : '' ?>
            <?php echo shopHarga($data->harga, $data->diskon) ?>
        </div>
    </div>
</div>
