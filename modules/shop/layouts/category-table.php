<?php defined('ROOT') or die ('Not allowed!') ?>
<?php require ADMIN_SIDEBAR ?>
<div id="main-contents">
    <nav class="data-header toolbar">
        <?php echo anchor('admin-shop/categories/form', 'Baru', ['class' => 'btn toolbar-btn btn-edit']) ?>
    </nav>
    <table class="data">
        <thead>
            <tr>
                <th><?php echo sortBy('nama', 'Nama') ?></th>
                <th><?php echo sortBy('alias', 'Alias') ?></th>
                <th>Keterangan</th>
                <th>Pilihan</th>
            </tr>
        </thead>
        <tbody>
        <?php if (($total = $data->count()) > 0) : foreach ($data->fetch() as $row) : ?>
            <tr id="data-<?php echo $row->id_kategori ?>">
                <td><?php echo $row->nama ?></td>
                <td><?php echo $row->alias ?></td>
                <td><?php echo $row->keterangan ?></td>
                <td class="action"><div class="btn-group">
                    <?php echo anchor('admin-shop/categories/form/'.$row->id_kategori, 'Lihat', ['class' => 'btn btn-edit']) ?>
                    <?php echo anchor('admin-shop/categories/delete/'.$row->id_kategori, 'Hapus', ['class' => 'btn btn-hapus', 'data-confirm-text' => 'Apakah anda yakin ingin menghapus data ini?']) ?>
                </div></td>
            </tr>
        <?php endforeach; else: ?>
            <tr><td colspan="7" class="acenter">Belum ada data.</td></tr>
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

