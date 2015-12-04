<?php defined('ROOT') or die ('Not allowed!') ?>
<div id="main-contents">
<?php if ($data and ($rows = $data->fetch(false))): ?>
    <table class="data">
        <thead>
            <tr>
                <th>ID</th>
                <th>Pelanggan</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Total Harga (Rp.)</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $row): ?>
            <tr id="data-<?php echo $row->id_order ?>">
                <td class="acenter"><?php echo $row->id_order ?></td>
                <td><?php echo $row->nama_lengkap ?></td>
                <td class="acenter"><?php echo formatTanggal($row->tanggal) ?></td>
                <td class="acenter"><?php echo Order::status($row->status) ?></td>
                <td class="aright"><?php echo formatAngka($row->total) ?></td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
<?php endif ?>
    </table>
</div>

