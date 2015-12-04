<?php defined('ROOT') or die('Not allowed!');?>
<?php require ADMIN_SIDEBAR;?>
<div id="main-contents">
    <form action="<?php echo currentUrl(); ?>" id="user-form" method="post" class="form" enctype="multipart/form-data">
        <div class="control-group">
            <label class="label" for="tanggal">Tanggal</label>
            <div class="control-input">
                <p class="control-static"><?php echo formatTanggal($data ? $data->tanggal : date('d-m-Y')); ?></p>
            </div>
        </div>

    <?php if ($data): ?>
        <div class="control-group">
            <label class="label" for="status">Status</label>
            <div class="control-input">
            <?php if (User::is('admin')): ?>
                <input type="checkbox" name="status" value="1" <?php echo $data->status == 1 ? 'disabled checked' : ''; ?>>
            <?php else: ?>
                <input type="hidden" name="status" value="<?php echo $data->status; ?>">
                <p class="control-static"><?php echo Order::status($data->status); ?></p>
            <?php endif;?>
            </div>
        </div>
    <?php else: ?>
        <input type="hidden" name="origin" value="<?php echo Order::cityId(); ?>">
    <?php endif;?>

        <fieldset>
            <legend>Pelanggan</legend>

            <input type="hidden" name="id_pelanggan" <?php echo $data ? 'value="' . $data->id_pelanggan . '"' : ''; ?>>

            <div class="control-group">
                <label class="label" for="nama_lengkap">Nama Lengkap</label>
                <div class="control-input">
                    <input type="text" name="nama_lengkap" id="nama_lengkap" <?php echo $data ? 'disabled value="' . $data->nama_lengkap . '"' : 'required class="jqui-autocomplete" data-url="/shop/api/pelanggan/nama_lengkap" data-field="nama_lengkap"'; ?>>
                </div>
            </div>

            <div class="control-group">
                <label class="label" for="alamat">Alamat</label>
                <div class="control-input">
                <?php if ($data): ?>
                    <p class="control-static"><?php echo $data->alamat; ?></p>
                <?php else: ?>
                    <textarea name="alamat" id="alamat" required></textarea>
                <?php endif;?>
                </div>
            </div>

            <div class="control-group">
                <label class="label" for="kota">Kota</label>
                <div class="control-input">
                <?php if ($data): ?>
                    <p class="control-static"><?php echo $data->kota; ?></p>
                <?php else: ?>
                    <input type="hidden" name="kota">
                    <select name="destination" id="destination">
                        <option value="">Pilih</option>
                    <?php foreach (Order::cities() as $id => $city): ?>
                        <option id="dest-<?php echo str_replace([' ', '(', ')'], ['-', '', ''], $city); ?>" value="<?php echo $id; ?>"><?php echo $city; ?></option>
                    <?php endforeach;?>
                    </select>
                <?php endif;?>
                </div>
            </div>

            <div class="control-group">
                <label class="label" for="telp">Telp</label>
                <div class="control-input">
                    <input type="text" name="telp" id="telp" <?php echo $data ? 'disabled value="' . $data->telp . '"' : 'required'; ?>>
                </div>
            </div>
        </fieldset>

    <?php if (!$data): ?>
        <fieldset id="fieldset-akun">
            <legend>Akun Pengguna</legend>

            <div class="control-group">
                <label class="label" for="username">Username</label>
                <div class="control-input">
                    <input type="text" required name="username" id="username">
                </div>
            </div>

            <div class="control-group">
                <label class="label" for="email">Email</label>
                <div class="control-input">
                    <input type="email" required name="email" id="email">
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
    <?php endif;?>

        <fieldset>
            <legend>Pembelian</legend>

        <?php if (!$data): ?>
            <div class="control-group">
                <label class="label" for="produk">Produk</label>
                <div class="control-input">
                    <input class="jqui-autocomplete" type="text" name="produk" id="produk" data-url="shop/api/produk/nama" data-field="nama">
                </div>
            </div>
        <?php endif;?>

            <table class="data">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama</th>
                        <th>Qty</th>
                        <th>Harga Satuan (Rp.)</th>
                        <th>Subtotal (Rp.)</th>
                    </tr>
                </thead>
                <tbody id="tbl-produk">
                <?php
                if ($data) {
                    $products = unserialize($data->produk);
                    $prodId = Product::primary();
                    $ordererProducts = Product::show($prodId.' in (' . implode(',', array_keys($products)) . ')');
                }
                if ($data and $ordererProducts->count() > 0): foreach ($ordererProducts->fetch(false) as $product):
                    $harga = $product->diskon ?: $product->harga;
                    $diskon = $product->diskon ? '<br>(diskon dari: <del>' . formatAngka($product->harga) . '</del>)' : '';
                    $subtotal = $products[$product->$prodId] * $harga; ?>
	                    <tr>
	                        <td><span class="thumb" style="background-image: url(<?php echo siteUrl('asset/uploads/' . $product->gambar); ?>);"></span></td>
	                        <td><?php echo '<strong>' . $product->nama . '</strong><br>' . $product->keterangan; ?></td>
	                        <td class="acenter"><?php echo $products[$product->$prodId]; ?></td>
	                        <td class="aright"><?php echo formatAngka($harga) . $diskon; ?></td>
	                        <td class="aright"><?php echo formatAngka($subtotal); ?></td>
	                    </tr>
	                <?php endforeach; else: ?>
                    <tr class="empty"><td colspan="5" class="acenter">belum ada data</td></tr>
                <?php endif; ?>
                </tbody>
            </table>

            <div class="control-group">
                <label class="label" for="belanja">Total Harga (Rp.)</label>
                <div class="control-input">
                <?php if ($data): ?>
                    <p class="control-static">
                    <?php if ($data->potongan): ?>
                        <s><?php echo formatAngka($data->belanja); ?></s> <?php echo formatAngka($data->belanja - $data->potongan); ?>
                    <?php else: ?>
                        <?php echo formatAngka($data->belanja); ?>
                    <?php endif;?>
                    </p>
                    <input type="hidden" name="belanja" value="<?php echo $data ? $data->belanja : ''; ?>">
                <?php else: ?>
                    <input type="text" name="belanja" id="belanja">
                    <input type="hidden" name="weight">
                <?php endif;?>
                </div>
            </div>
        </fieldset>

        <fieldset <?php echo ($data and $data->status) ? 'disabled' : ''; ?>>
            <legend>Pengiriman</legend>

            <div class="control-group">
                <label class="label" for="courier">Kurir Pengiriman</label>
                <div class="control-input">
                <?php if ($data and $data->kurir): ?>
                    <p class="control-static"><?php echo $data->kurir; ?></p>
                    <input type="hidden" name="kurir" value="<?php echo $data ? $data->kurir : ''; ?>">
                <?php else: ?>
                    <input type="hidden" name="kurir" id="kurir">
                    <select name="courier" id="courier" class="small">
                        <option value="">Pilih kurir</option>
                        <option value="jne">JNE</option>
                        <option value="tiki">TIKI</option>
                    </select>
                    <button type="button" class="btn" id="btn-ongkir">Check ongkir</button>
                <?php endif;?>
                </div>
            </div>

        <?php if ($data): ?>
            <div class="control-group">
                <label class="label" for="ongkir">Ongkos Kirim (Rp.)</label>
                <div class="control-input">
                <?php if ($data->ongkir): ?>
                    <p class="control-static"><?php echo formatAngka($data->ongkir); ?></p>
                    <input type="hidden" name="ongkir" value="<?php echo $data ? $data->ongkir : ''; ?>">
                <?php else: ?>
                    <input type="text" name="ongkir" id="ongkir">
                <?php endif;?>
                </div>
            </div>
        <?php endif; ?>
            <?php include __DIR__.'/ongkir-table.php'; ?>
        </fieldset>

        <fieldset <?php echo ($data and $data->status) ? 'disabled' : ''; ?>>
            <legend>Pembayaran</legend>

            <div class="control-group">
                <label class="label" for="total">Total Biaya (Rp.)</label>
                <div class="control-input">
                    <p class="control-static" id="total-s"><?php echo ($data and $data->total) ? formatAngka($data->total) : '-'; ?></p>
                    <input type="hidden" id="total" name="total" value="<?php echo ($data and $data->total) ? $data->total : ''; ?>">
                </div>
            </div>

            <div class="control-group">
                <label class="label" for="pembayaran">Bukti pembayaran</label>
                <div class="control-input">
                <?php if ($data and $data->pembayaran): ?>
                    <?php if (!User::is('admin') and !$data->status): ?>
                        <p class="control-static">Menunggu konfirmasi dari admin</p>
                    <?php endif;?>
                    <input type="hidden" name="pembayaran" value="<?php echo $data->pembayaran; ?>">
                    <img src="<?php echo siteUrl('asset/uploads/' . $data->pembayaran); ?>" alt="Gambar" class="thumb">
                <?php elseif (!$data or ($data and !$data->pembayaran)): ?>
                    <p class="control-static">Belum ada bukti pembayaran <?php echo ($data and $data->status) ? 'tapi sudah lunas' : ''; ?></p>
                    <input type="file" name="pembayaran">
                <?php endif;?>
                </div>
            </div>

        <?php if (User::is('admin')): ?>
            <div class="control-group">
                <label class="label" for="potongan">Potongan Harga (Rp.)</label>
                <div class="control-input">
                    <input type="text" name="potongan" id="potongan" <?php echo $data ? 'value="' . $data->potongan . '"' : ''; ?>>
                </div>
            </div>

            <div class="control-group">
                <label class="label" for="bayar">Bayar (Rp.)</label>
                <div class="control-input">
                    <input type="text" name="bayar" id="bayar" <?php echo $data ? 'value="' . $data->bayar . '"' : ''; ?>>
                </div>
            </div>

            <div class="control-group">
                <label class="label" for="kembali">Kembalian (Rp.)</label>
                <div class="control-input">
                    <input type="text" name="kembali" id="kembali" <?php echo $data ? 'value="' . $data->kembali . '"' : ''; ?>>
                </div>
            </div>

        <?php endif;?>
        </fieldset>

    <?php if ($data and $data->status): ?>
        <fieldset>
            <legend>Pengiriman</legend>

            <div class="control-group">
                <label class="label" for="resi">Resi Pengiriman</label>
                <div class="control-input">
                    <input type="text" name="resi" id="resi" <?php echo ($data and $data->resi) ? 'disabled value="' . $data->resi . '"' : ''; ?>>
                </div>
            </div>
        </fieldset>
    <?php endif;?>

    <?php if (($data and !$data->resi) or !$data): ?>
        <div class="form control-action">
            <input type="submit" name="submit" id="submit-btn" class="btn" value="<?php echo User::is('admin') ? 'Simpan' : 'Konfirmasi Pembelian'; ?>">
            <input type="reset" name="reset" id="reset-btn" class="btn fright" value="Batal">
        </div>
    <?php endif;?>
    </form>
</div>

<script src="<?php echo siteUrl('modules/shop/script.js'); ?>"></script>
