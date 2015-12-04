<?php defined('ROOT') or die ('Not allowed!') ?>
<?php require ADMIN_SIDEBAR ?>
<div id="main-contents">
    <nav class="data-header toolbar">
        <?php echo anchor('admin-shop/customers/form', 'Baru', ['class' => 'btn toolbar-btn btn-edit']) ?>
    </nav>
    <table class="data">
        <thead>
            <tr>
                <th>Nama Lengkap</th>
                <th>Alamat</th>
                <th>Telp</th>
                <th>Pilihan</th>
            </tr>
        </thead>
        <tbody>
        <?php if (($total = $data->count()) > 0) : foreach ($data->fetch() as $row) : ?>
            <tr id="data-<?php echo $row->id_pelanggan ?>">
                <td><?php echo $row->nama_lengkap ?></td>
                <td><?php echo $row->alamat ?></td>
                <td><?php echo $row->telp ?></td>
                <td class="action"><div class="btn-group">
                    <?php echo anchor('admin-shop/customers/form/'.$row->id_pelanggan, 'Lihat', ['class' => 'btn btn-edit']) ?>
                    <?php echo anchor('admin-shop/customers/delete/'.$row->id_pelanggan, 'Hapus', ['class' => 'btn btn-hapus', 'data-confirm-text' => 'Apakah anda yakin ingin menghapus data ini?']) ?>
                </div></td>
            </tr>
        <?php endforeach; else: ?>
            <tr><td colspan="4" class="acenter">Belum ada data.</td></tr>
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

