<?php defined('ROOT') or die ('Not allowed!') ?>
<?php require __DIR__.'/sidebar.php' ?>
<div id="main-contents">
    <nav class="data-header toolbar">
        <?php echo anchor('admin/pages/form', 'Baru', ['class' => 'btn toolbar-btn btn-edit']) ?>
    </nav>
    <table class="data">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Alias</th>
                <th>Dibuat Oleh</th>
                <th>Dibuat Pada</th>
                <th>Pilihan</th>
            </tr>
        </thead>
        <tbody>
        <?php if (($total = $data->count()) > 0) : foreach ($data->fetch() as $row) : ?>
            <tr id="data-<?php echo $row->id_halaman ?>">
                <td><?php echo $row->judul ?></td>
                <td><?php echo $row->alias ?></td>
                <td><?php echo $row->username ?></td>
                <td class="acenter"><?php echo format_date($row->tgl_input) ?></td>
                <td class="action"><div class="btn-group">
                    <?php echo anchor('admin/pages/form/'.$row->id_halaman, 'Lihat', ['class' => 'btn btn-edit']) ?>
                    <?php echo anchor('admin/pages/delete/'.$row->id_halaman, 'Hapus', ['class' => 'btn btn-hapus', 'data-confirm-text' => 'Apakah anda yakin ingin menghapus data ini?']) ?>
                </div></td>
            </tr>
        <?php endforeach; else: ?>
            <tr><td colspan="5" class="acenter">Belum ada data.</td></tr>
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

