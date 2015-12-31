<?php defined('ROOT') or die ('Not allowed!'); require ADMIN_SIDEBAR; ?>
<div id="main-contents">
    <form action="<?php echo current_url() ?>" id="user-form" method="post" class="form" enctype="multipart/form-data">
        <div class="control-group">
            <label class="label" for="gambar">Gambar</label>
            <div class="control-input">
                <input type="file" name="gambar">
            <?php if ($data and $data->gambar): ?>
                <input type="hidden" name="gambar" required value="<?php echo $data->gambar ?>">
                <img src="<?php echo site_url('asset/uploads/'.$data->gambar) ?>" alt="Gambar" class="thumb">
            <?php endif ?>
            </div>
        </div>

        <div class="control-group">
            <label class="label" for="nama">Nama</label>
            <div class="control-input">
                <input type="text" required name="nama" id="nama" <?php echo $data ? 'value="'.$data->nama.'"' : '' ?>>
            </div>
        </div>

        <div class="control-group">
            <label class="label" for="kategori">Kategori</label>
            <div class="control-input">
                <?php $kategori = Category::show()->fetch(false) ?>
                <select name="kategori" required>
                    <option value="">Pilih Kategori</option>
                <?php if (count($kategori) > 0) : $katId = Category::primary(); foreach ($kategori as $row) : ?>
                    <option <?php echo ($data and $data->$katId == $row->$katId) ? 'selected' : '' ?> value="<?php echo $row->$katId ?>"><?php echo $row->nama ?></option>
                <?php endforeach; endif; ?>
                </select>
            </div>
        </div>

        <div class="control-group">
            <label class="label" for="tgl_masuk">Tgl masuk</label>
            <div class="control-input">
                <input type="text" class="jqui-datepicker" required name="tgl_masuk" id="tgl_masuk" <?php echo $data ? 'value="'.format_date($data->tgl_masuk).'"' : '' ?>>
            </div>
        </div>

        <div class="control-group">
            <label class="label" for="stok">Stok &amp; Berat (gram)</label>
            <div class="control-input">
                <input type="number" name="stok" id="stok" <?php echo $data ? 'value="'.$data->stok.'"' : '' ?> required class="small" placeholder="Stok" title="Stok">
                <input type="number" name="berat" id="berat" <?php echo $data ? 'value="'.$data->berat.'"' : '' ?> required class="small" placeholder="Berat (gram)" title="Berat (gram)">
            </div>
        </div>

        <div class="control-group">
            <label class="label" for="stok">Harga &amp; Diskon (Rp.)</label>
            <div class="control-input">
                <input type="text" name="harga" id="harga" <?php echo $data ? 'value="'.$data->harga.'"' : '' ?> required class="small" placeholder="Harga (Rp.)" title="Harga (Rp.)">
                <input type="text" name="diskon" id="diskon" <?php echo $data ? 'value="'.$data->diskon.'"' : '' ?> required class="small" placeholder="Diskon (Rp.)" title="Diskon (Rp.)">
            </div>
        </div>

        <div class="control-group">
            <label class="label" for="keterangan">Keterangan</label>
            <div class="control-input">
                <textarea name="keterangan" id="keterangan" class="full" required><?php echo $data ? $data->keterangan : '' ?></textarea>
            </div>
        </div>

        <div class="form control-action">
            <input type="submit" name="submit" id="submit-btn" class="btn" value="Simpan">
            <input type="reset" name="reset" id="reset-btn" class="btn fright" value="Batal">
        </div>
    </form>
</div>

