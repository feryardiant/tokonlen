<?php defined('ROOT') or die ('Not allowed!') ?>
<div id="main-sidebar" class="fright">
    <div class="widget">
        <h4 class="widget-title">Pencarian</h4>
        <form action="<?php echo siteUrl('shop') ?>" method="get" id="product-search">
            <input type="search" name="search" id="search">
            <input type="submit" id="s-btn" class="btn" value="Cari">
        </form>
    </div>
    <div class="widget">
        <h4 class="widget-title">Kategori</h4>
        <ul class="widget-content">
        <?php if (count($kategori) > 0) : foreach ($kategori as $row) : ?>
            <li><?php echo anchor('shop/index/category/'.$row->alias, $row->nama) ?></li>
        <?php endforeach; else: ?>
            <li>Kategori kosong.</li>
        <?php endif; ?>
        </ul>
    </div>
</div>
