<?php defined('ROOT') or die ('Not allowed!') ?>
<div id="slider-home">
<?php if (count($slides) > 0): ?>
    <div class="slider">
    <?php foreach ($slides as $slide): ?>
        <div class="slide">
            <img src="<?php echo siteUrl('asset/uploads/'.$slide->gambar) ?>" alt="<?php echo $slide->judul ?>">
            <span class="slide-text"><?php echo $slide->judul ?></span>
        </div>
    <?php endforeach ?>
    </div>
<?php endif ?>
</div>

<div id="home-contents">
<?php if (($total = count($products)) > 0): $i = 1; ?>
    <div class="product-list clearfix">
    <?php foreach ($products as $product): ?>
        <div class="product-item" style="width: 170px">
            <img src="<?php echo siteUrl('asset/uploads/'.$product->gambar) ?>" style="width: 100%" alt="<?php echo $product->nama ?>">
            <?php echo anchor('shop/product/'.$product->id_produk, $product->nama) ?>
        <?php if ($product->diskon): ?>
            <del>Rp. <?php echo formatAngka($product->harga) ?></del>
            <span>Rp. <?php echo formatAngka($product->diskon) ?></span>
        <?php else: ?>
            <span>Rp. <?php echo formatAngka($product->harga) ?></span>
        <?php endif ?>
        </div>
        <?php if ($i % 5 == 0) echo '<hr>' ?>
    <?php $i++; endforeach; ?>
    </div>
    <?php echo anchor('shop', 'Lihat selengkapnya', array('class' => 'btn')) ?>
<?php else: ?>
    <span class="alert warning no-product">Tidak ada produk.</span>
<?php endif ?>
</div>
