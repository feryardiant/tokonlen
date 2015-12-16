<?php defined('ROOT') or die ('Not allowed!'); require __DIR__.'/sidebar.php'; ?>
<div id="main-contents">
<?php if (($total = $data->count()) > 0): $i = 1; ?>
    <div class="product-list clearfix">
    <?php foreach ($data->fetch(12) as $row): ?>
        <div class="product-item">
            <img src="<?php echo siteUrl('asset/uploads/'.$row->gambar) ?>" alt="<?php echo $row->nama ?>">
            <?php echo anchor('shop/product/'.$row->id_produk, $row->nama) ?>
            <?php echo shopHarga($row->harga, $row->diskon) ?>
        </div>
        <?php if ($i % 4 == 0) echo '<hr>' ?>
    <?php $i++; endforeach; ?>
        <?php if ($total % 4 > 0) echo '<hr>' ?>
    </div>
    <?php if ($data && $total): ?>
    <div class="data-info clearfix">
        <p class="data-total">Total data: <?php echo $total ?></p>
        <div class="data-page"><?php echo pagination($total) ?></div>
    </div>
    <?php endif ?>
<?php else: ?>
    <span class="alert warning no-product">Tidak ada produk.</span>
<?php endif ?>
</div>
