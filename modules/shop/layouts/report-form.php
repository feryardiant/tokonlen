<?php defined('ROOT') or die ('Not allowed!'); require ADMIN_SIDEBAR; ?>
<div id="main-contents">
    <form action="<?php echo current_url() ?>" id="report-form" method="post" class="form" enctype="multipart/form-data">

        <div class="control-group only-screen">
            <label class="label" for="status">Status Pembayaran</label>
            <div class="control-input">
                <label><input type="radio" name="status" value="0"> Belum dibayar</label>
                <label><input type="radio" name="status" value="1"> Lunas</label>
            </div>
        </div>

        <div class="control-group only-screen">
            <label class="label" for="terjual">Status Pembelian</label>
            <div class="control-input">
                <label><input type="radio" name="terjual" value="0"> Semua</label>
                <label><input type="radio" name="terjual" value="1"> Produk Terjual</label>
            </div>
        </div>

        <div class="control-group only-screen">
            <label class="label" for="tgl_mulai">Tanggal Order</label>
            <div class="control-input">
                <input type="text" name="tgl_mulai" placeholder="Tanggal mulai" id="tgl_mulai" class="small jqui-datepicker">
                <input type="text" name="tgl_akhir" placeholder="Tanggal akhir" id="tgl_akhir" class="small jqui-datepicker">
            </div>
        </div>

        <fieldset class="only-screen">
            <legend>Urutan</legend>

            <div class="control-group">
                <label class="label" for="orderby">ID</label>
                <div class="control-input">
                    <label><input type="radio" name="orderby" value="id_asc"> ASC</label>
                    <label><input type="radio" name="orderby" value="id_desc"> DESC</label>
                </div>
            </div>

            <div class="control-group">
                <label class="label" for="orderby">Tanggal</label>
                <div class="control-input">
                    <label><input type="radio" name="orderby" value="tanggal_asc"> ASC</label>
                    <label><input type="radio" name="orderby" value="tanggal_desc"> DESC</label>
                </div>
            </div>
        </fieldset>

    <?php if ($data): ?>
        <fieldset>
            <legend>Hasil</legend>

            <table class="data">
                <thead>
                    <tr>
                    <?php if ($isSelling): ?>
                        <th>Gambar</th>
                        <th>Nama</th>
                        <th>Penjualan</th>
                        <th>Harga (Rp.)</th>
                        <th>Diskon (Rp.)</th>
                    <?php else: ?>
                        <th>ID</th>
                        <th>Pelanggan</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Total Harga (Rp.)</th>
                    <?php endif ?>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $row): $id = $isSelling ? 'id_produk' : 'id_order'; ?>
                    <tr id="data-<?php echo $row->$id ?>">
                    <?php if ($isSelling): ?>
                        <td><span class="thumb" style="background-image: url(<?php echo site_url('asset/uploads/'.$row->gambar) ?>);"></span></td>
                        <td><?php echo $row->nama ?></td>
                        <td class="acenter"><?php echo $row->penjualan ?></td>
                        <td class="aright"><?php echo format_number($row->harga) ?></td>
                        <td class="aright"><?php echo format_number($row->diskon) ?></td>
                    <?php else: ?>
                        <td class="acenter"><?php echo $row->id_order ?></td>
                        <td><?php echo $row->nama_lengkap ?></td>
                        <td class="acenter"><?php echo format_date($row->tanggal) ?></td>
                        <td class="acenter"><?php echo Order::status($row->status) ?></td>
                        <td class="aright"><?php echo format_number($row->belanja) ?></td>
                    <?php endif ?>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </fieldset>
    <?php endif ?>

        <div class="form control-action only-screen">
            <input type="submit" name="submit" id="submit-btn" class="btn" value="Periksa<?php echo $data ? ' lagi' : '' ?>">
        <?php if ($data) echo anchor('#', 'Cetak', ['class' => 'btn', 'onClick' => 'javascript:window.print()']) ?>
            <input type="reset" name="reset" id="reset-btn" class="btn fright" value="Batal">
        </div>

    </form>
</div>
