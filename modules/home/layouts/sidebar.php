<?php defined('ROOT') or die ('Not allowed!') ?>
<div id="main-sidebar" class="fright">
    <div class="widget">
        <h4 class="widget-title">Index Halaman</h4>
        <ul class="widget-content">
        <?php if (count($pages) > 0) : foreach ($pages as $page) : ?>
            <li><?php echo anchor($page->alias, $page->judul) ?></li>
        <?php endforeach; else: ?>
            <li>Kategori kosong.</li>
        <?php endif; ?>
        </ul>
    </div>
</div>
