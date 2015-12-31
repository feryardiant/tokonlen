<?php defined('ROOT') or die ('Not allowed!') ?>
<?php require __DIR__.'/sidebar.php' ?>
<div id="main-contents">
<?php if (isset($produk) and $produk->count() > 0): foreach ($produk->fetch() as $row): ?>
    <div class="product">
        <img src="<?php echo $row->gambar ?>" width="150" height="150" alt="<?php echo $row->nama ?>">
        <?php echo anchor('shop/product/'.$row->id, $row->nama) ?>
        <span>Rp. <?php echo format_number($row->harga) ?></span>
    </div>
<?php endforeach; endif; ?>
</div>

