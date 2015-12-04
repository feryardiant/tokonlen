<?php defined('ROOT') or die ('Not allowed!') ?>
<?php require __DIR__.'/sidebar.php' ?>
<div id="main-contents">
    <div class="product-cart clearfix">
    <?php if (count($items) > 0 and $data): $belanja = 0; ?>
    <?php foreach ($data->fetch() as $row): ?>
        <div class="item clearfix">
            <img src="<?php echo siteUrl('asset/uploads/'.$row->gambar) ?>" width="150" height="150" alt="<?php echo $row->nama ?>">
            <div class="detail">
                <?php $harga = $row->diskon ?: $row->harga ?>
                <?php $diskon = $row->diskon ? ' (diskon dari: <del>'.formatAngka($row->harga).'</del>)': '' ?>
                <h4><?php echo anchor('shop/product/'.$row->id_produk, $row->nama) ?></h4>
                <span><?php echo $items[$row->id_produk].' &times; @ Rp. '.formatAngka($harga).$diskon ?></span>
                <?php $subtotal = $items[$row->id_produk] * $harga; $belanja += $subtotal; ?>
                <span class="fright bold"><?php echo 'Subtotal: Rp. '.formatAngka($subtotal) ?></span>
                <input type="hidden" name="weight" value="<?php echo $row->berat ?>">
                <nav class="page-toolbar cart-action">
                    <?php echo anchor('cart/?id='.$row->id_produk.'&do=clear', 'Hapus', ['class' => 'btn toolbar-btn']) ?>
                    <?php if ($row->stok > 1): ?>
                    <nav class="btn-group toolbar-btn">
                        <?php echo anchor('cart/?id='.$row->id_produk.'&do=remove', 'Kurangi', ['class' => 'btn toolbar-btn']) ?>
                        <?php echo anchor('cart/?id='.$row->id_produk.'&do=add', 'Tambah', ['class' => 'btn toolbar-btn']) ?>
                    </nav>
                    <?php endif ?>
                </nav>
            </div>
        </div>
    <?php endforeach; ?>
        <form action="<?php echo siteUrl('shop/checkout/') ?>" method="post" class="form">
            <input type="hidden" name="belanja" value="<?php echo $belanja ?>">
            <h4 class="total-text"><?php echo 'Total: Rp. '.formatAngka($belanja) ?></h4>

            <fieldset>
                <legend>Simulasi Ongkir</legend>
            <?php $origin = conf('app.city'); ?>

                <div class="control-group">
                    <label class="label" for="origin">Pengiriman Dari</label>
                    <div class="control-input">
                        <p class="control-static"><?php echo $origin ?></p>
                        <input type="hidden" name="origin" value="<?php echo Order::cityId($origin) ?>">
                    </div>
                </div>
                <div class="control-group">
                    <label class="label" for="destination">Pengiriman Ke</label>
                    <div class="control-input">
                    <?php if (User::loggedin()): ?>
                        <?php $destination = Customer::show((int) User::current('id'))->fetchOne()->kota; ?>
                        <p class="control-static"><?php echo $destination ?></p>
                        <input type="hidden" name="destination" value="<?php echo Order::cityId($destination) ?>">
                    <?php else: ?>
                        <select name="destination" id="destination">
                            <option value="">Pilih</option>
                        <?php foreach (Order::cities() as $id => $city): ?>
                            <option value="<?php echo $id ?>"><?php echo $city ?></option>
                        <?php endforeach ?>
                        </select>
                    <?php endif ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="label" for="courier">Kurir Pengiriman</label>
                    <div class="control-input">
                        <input type="hidden" name="kurir" id="kurir">
                        <select name="courier" id="courier" class="small">
                            <option value="">Pilih kurir</option>
                            <option value="jne">JNE</option>
                            <option value="tiki">TIKI</option>
                        </select>
                        <button type="button" class="btn" id="btn-ongkir">Check ongkir</button>
                    </div>
                </div>

                <?php include __DIR__.'/ongkir-table.php'; ?>
            </fieldset>
            <hr>
            <?php echo anchor('shop/', 'Mau nambah?', ['class' => 'btn']) ?>
            <input type="submit" name="checkout" value="Proses Sekarang" class="btn">
            <?php echo anchor('cart/?do=clear', 'Nggak jadi belanja', ['class' => 'btn fright']) ?>
        </form>
    <?php else: ?>
        <div class="item clearfix">
            <span class="alert warning">Oops! Troli anda kosong,<br>silahkan pilih produk yang anda suka dilanjutkan dengan menekan tombol "beli"</span>
        </div>
        <?php echo anchor('shop/', 'Lanjutkan belanja', ['class' => 'btn']) ?>
    <?php endif; ?>
    </div>
</div>

<script src="<?php echo siteUrl('modules/shop/script.js') ?>"></script>
