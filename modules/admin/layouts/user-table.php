<?php defined('ROOT') or die ('Not allowed!') ?>
<?php require __DIR__.'/sidebar.php' ?>
<div id="main-contents">
    <nav class="data-header toolbar">
        <?php echo anchor('admin/users/form', 'Baru', ['class' => 'btn toolbar-btn btn-edit']) ?>
    </nav>
    <table class="data">
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Level</th>
                <th>Aktif</th>
                <th>Pilihan</th>
            </tr>
        </thead>
        <tbody>
        <?php if (($total = $data->count()) > 0) : foreach ($data->fetch() as $user) : ?>
            <tr id="data-<?php echo $user->id_pengguna ?>">
                <td><?php echo $user->username ?></td>
                <td><?php echo $user->email ?></td>
                <td class="acenter"><?php echo User::getAlias($user->level) ?></td>
                <td class="acenter"><?php echo $user->aktif == 1 ? 'Ya' : 'Tidak' ?></td>
                <td class="action"><div class="btn-group">
                    <?php echo anchor('admin/users/form/'.$user->id_pengguna, 'Lihat', ['class' => 'btn btn-edit']) ?>
                    <?php echo anchor('admin/users/delete/'.$user->id_pengguna, 'Hapus', ['class' => 'btn btn-hapus', 'data-confirm-text' => 'Apakah anda yakin ingin menghapus data ini?']) ?>
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

