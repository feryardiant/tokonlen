<?php defined('ROOT') or die ('Not allowed!') ?>
<?php require ADMIN_SIDEBAR ?>
<div id="main-contents">
    <nav class="data-header toolbar">
        <?php echo anchor('admin-shop/products/form', 'Baru', ['class' => 'btn toolbar-btn btn-edit']) ?>
    </nav>
    <table class="data">
        <thead>
            <tr>
                <th>Gambar</th>
                <th><?php echo sortBy('nama', 'Nama') ?></th>
                <th>Tgl Masuk</th>
                <th>Stok</th>
                <th>Harga (Rp.)</th>
                <th>Pilihan</th>
            </tr>
        </thead>
        <tbody>
        <?php if (($total = $data->count()) > 0) : foreach ($data->fetch() as $row) : ?>
            <tr id="data-<?php echo $row->id_produk ?>">
                <td><span class="thumb" style="background-image: url(<?php echo siteUrl('asset/uploads/'.$row->gambar) ?>);"></span></td>
                <td><?php echo '<strong>'.$row->nama.'</strong><br>'.$row->keterangan ?></td>
                <td class="acenter"><?php echo formatTanggal($row->tgl_masuk) ?></td>
                <td class="acenter"><?php echo $row->stok > 0 ? $row->stok : 'Maaf stok habis' ?></td>
                <td class="aright">
                <?php if ($row->diskon): ?>
                    <del><?php echo formatAngka($row->harga) ?></del><br>
                    <span><?php echo formatAngka($row->diskon) ?></span>
                <?php else: ?>
                    <span><?php echo formatAngka($row->harga) ?></span>
                <?php endif ?>
                </td>
                <td class="action"><div class="btn-group">
                    <?php echo anchor('admin-shop/products/form/'.$row->id_produk, 'Lihat', ['class' => 'btn btn-edit']) ?>
                    <?php echo anchor('admin-shop/products/delete/'.$row->id_produk, 'Hapus', ['class' => 'btn btn-hapus', 'data-confirm-text' => 'Apakah anda yakin ingin menghapus data ini?']) ?>
                </div></td>
            </tr>
        <?php endforeach; else: ?>
            <tr><td colspan="6" class="acenter">Belum ada data.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
    <?php if ($data && $total): ?>
    <footer class="data-info clearfix">
        <p class="data-total">Total data: <?php echo $total ?></p>
        <div class="data-page"><?php echo pagination($total) ?></div>
    </footer>
    <?php endif ?>
</div>

