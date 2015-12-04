<?php defined('ROOT') or die ('Not allowed!') ?>
<?php require ADMIN_SIDEBAR ?>
<div id="main-contents">
    <form action="<?php echo currentUrl() ?>" id="user-form" method="post" class="form">
        <div class="control-group">
            <label class="label" for="nama_lengkap">Nama lengkap</label>
            <div class="control-input">
                <input type="text" required name="nama_lengkap" id="nama_lengkap" <?php echo $data ? 'value="'.$data->nama_lengkap.'"' : '' ?>>
            </div>
        </div>

        <div class="control-group">
            <label class="label" for="alamat">Alamat</label>
            <div class="control-input">
                <textarea name="alamat" id="alamat"><?php echo $data ? $data->alamat : '' ?></textarea>
            </div>
        </div>

        <div class="control-group">
            <label class="label" for="kota">Kota</label>
            <div class="control-input">
                <input type="text" required name="kota" id="kota" <?php echo $data ? 'value="'.$data->kota.'"' : '' ?>>
            </div>
        </div>

        <div class="control-group">
            <label class="label" for="telp">Telp</label>
            <div class="control-input">
                <input type="text" required name="telp" id="telp" <?php echo $data ? 'value="'.$data->telp.'"' : '' ?>>
            </div>
        </div>

        <fieldset>
            <legend>Akun Pengguna</legend>

            <div class="control-group">
                <label class="label" for="username">Username</label>
                <div class="control-input">
                    <input type="text" required name="username" id="username" <?php echo $data ? 'value="'.$data->username.'"' : '' ?>>
                </div>
            </div>

            <div class="control-group">
                <label class="label" for="email">Email</label>
                <div class="control-input">
                    <input type="email" required name="email" id="email" <?php echo $data ? 'value="'.$data->email.'"' : '' ?>>
                </div>
            </div>

            <div class="control-group">
                <label class="label" for="password">Password</label>
                <div class="control-input">
                    <input type="password" required name="password" id="password" class="small">
                    <input type="password" required name="passconf" id="passconf" class="small">
                </div>
            </div>
        </fieldset>

    <?php if ($data and User::is('admin')): ?>
        <fieldset>
            <legend>Belanjaan</legend>
            <?php $orders = Order::show(array('a.id_pelanggan' => $data->id_pelanggan)) ?>
            <table class="data">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Total Harga (Rp.)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($orders->count() > 0): foreach ($orders->fetch(false) as $order): ?>
                    <tr>
                        <td class="acenter"><?php echo $order->id_order ?></td>
                        <td class="acenter"><?php echo formatTanggal($order->tanggal) ?></td>
                        <td class="acenter"><?php echo Order::status($order->status) ?></td>
                        <td class="aright"><?php echo formatAngka($order->total) ?></td>
                        <td class="action"><div class="btn-group">
                            <?php echo anchor('admin-shop/orders/form/'.$order->id_order, 'Lihat', ['class' => 'btn btn-edit']) ?>
                        </div></td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="5" class="acenter">belum ada data</td></tr>
                <?php endif ?>
                </tbody>
            </table>
        </fieldset>
    <?php endif ?>

        <div class="form control-action">
            <input type="submit" name="submit" id="submit-btn" class="btn" value="Simpan">
            <input type="reset" name="reset" id="reset-btn" class="btn fright" value="Batal">
        </div>
    </form>
</div>

