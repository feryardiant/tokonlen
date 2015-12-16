<?php defined('ROOT') or die ('Not allowed!') ?>
<?php require ADMIN_SIDEBAR ?>
<div id="main-contents">
    <nav class="data-header toolbar">
        <?php echo anchor('admin-shop/banners/form', 'Baru', ['class' => 'btn toolbar-btn btn-edit']) ?>
    </nav>
    <table class="data">
        <thead>
            <tr>
                <th>Gambar</th>
                <th><?php echo sortBy('judul', 'Judul') ?></th>
                <th>Penayangan</th>
                <th>Aktif</th>
                <th>Tipe</th>
                <th>Pilihan</th>
            </tr>
        </thead>
        <tbody>
        <?php if (($total = $data->count()) > 0) : foreach ($data->fetch() as $row) : ?>
            <tr id="data-<?php echo $row->id_banner ?>">
                <td><span class="thumb" style="background-image: url(<?php echo siteUrl('asset/uploads/'.$row->gambar) ?>);"></span></td>
                <td><?php echo '<strong>'.$row->judul.'</strong><br>'.$row->keterangan ?></td>
                <td class="acenter"><?php echo formatTanggal($row->tgl_mulai).' - '.formatTanggal($row->tgl_akhir) ?></td>
                <td class="acenter"><?php echo $row->aktif == 1 ? 'Ya' : 'Tidak' ?></td>
                <td class="acenter"><?php echo $row->tipe ?></td>
                <td class="action"><div class="btn-group">
                    <?php echo anchor('admin-shop/banners/form/'.$row->id_banner, 'Lihat', ['class' => 'btn btn-edit']) ?>
                    <?php echo anchor('admin-shop/banners/delete/'.$row->id_banner, 'Hapus', ['class' => 'btn btn-hapus', 'data-confirm-text' => 'Apakah anda yakin ingin menghapus data ini?']) ?>
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

