<?php defined('ROOT') or die ('Not allowed!'); require ADMIN_SIDEBAR; ?>
<div id="main-contents">
<?php if (User::is('admin')): ?>
    <nav class="data-header toolbar">
        <?php echo anchor('admin-shop/orders/form', 'Baru', ['class' => 'btn toolbar-btn btn-edit']) ?>
    </nav>
<?php endif ?>
    <table class="data">
        <thead>
            <tr>
                <th><?php echo sortBy('id_order', 'ID') ?></th>
                <th><?php echo sortBy('nama_lengkap', 'Pelanggan') ?></th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Total Harga (Rp.)</th>
                <th>Pilihan</th>
            </tr>
        </thead>
        <tbody>
        <?php if (($total = $data->count()) > 0) : foreach ($data->fetch() as $row) : ?>
            <tr id="data-<?php echo $row->id_order ?>">
                <td class="acenter"><?php echo $row->id_order ?></td>
                <td><?php echo $row->nama_lengkap ?></td>
                <td class="acenter"><?php echo formatTanggal($row->tanggal) ?></td>
                <td class="acenter"><?php echo Order::status($row->status) ?></td>
                <td class="aright"><?php echo formatAngka($row->total) ?></td>
                <td class="action"><div class="btn-group">
                    <?php echo anchor('admin-shop/orders/form/'.$row->id_order, 'Lihat', ['class' => 'btn btn-edit']) ?>
                    <?php echo anchor('admin-shop/orders/delete/'.$row->id_order, 'Hapus', ['class' => 'btn btn-hapus', 'data-confirm-text' => 'Apakah anda yakin ingin menghapus data ini?']) ?>
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

