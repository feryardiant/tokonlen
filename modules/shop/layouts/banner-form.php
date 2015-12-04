<?php defined('ROOT') or die ('Not allowed!') ?>
<?php require ADMIN_SIDEBAR ?>
<div id="main-contents">
    <form action="<?php echo currentUrl() ?>" id="user-form" method="post" class="form" enctype="multipart/form-data">
        <div class="control-group">
            <label class="label" for="judul">Judul</label>
            <div class="control-input">
                <input type="text" required name="judul" id="judul" <?php echo $data ? 'value="'.$data->judul.'"' : '' ?>>
            </div>
        </div>

        <div class="control-group">
            <label class="label" for="tipe">Tipe</label>
            <div class="control-input">
                <select name="tipe" required>
                    <option value="">Pilih Tipe</option>
                    <option <?php echo ($data and $data->tipe == 'slide') ? 'selected' : '' ?> value="slide">Slide</option>
                    <option <?php echo ($data and $data->tipe == 'kontent') ? 'selected' : '' ?> value="kontent">Kontent</option>
                    <option <?php echo ($data and $data->tipe == 'samping') ? 'selected' : '' ?> value="samping">Samping</option>
                </select>
            </div>
        </div>

        <div class="control-group">
            <label class="label" for="keterangan">Konten</label>
            <div class="control-input">
                <textarea name="keterangan" id="keterangan" required><?php echo $data ? $data->keterangan : '' ?></textarea>
            </div>
        </div>

        <div class="control-group">
            <label class="label" for="url">Url</label>
            <div class="control-input">
                <input type="text" required name="url" id="url" <?php echo $data ? 'value="'.$data->url.'"' : '' ?>>
            </div>
        </div>

        <div class="control-group">
            <label class="label" for="tgl_mulai">Penayangan</label>
            <div class="control-input">
                <input type="text" required name="tgl_mulai" id="tgl_mulai" class="small jqui-datepicker" <?php echo $data ? 'value="'.$data->tgl_mulai.'"' : '' ?>>
                <input type="text" required name="tgl_akhir" id="tgl_akhir" class="small jqui-datepicker" <?php echo $data ? 'value="'.$data->tgl_akhir.'"' : '' ?>>
            </div>
        </div>

        <div class="control-group">
            <label class="label" for="gambar">Gambar</label>
            <div class="control-input">
                <input type="file" name="gambar">
            <?php if ($data and $data->gambar): ?>
                <input type="hidden" name="gambar" required value="<?php echo $data->gambar ?>">
                <img src="<?php echo siteUrl('asset/uploads/'.$data->gambar) ?>" alt="Gambar" class="thumb">
            <?php endif ?>
            </div>
        </div>

        <div class="control-group">
            <label class="label" for="aktif">Aktif</label>
            <div class="control-input">
                <input type="checkbox" name="aktif" value="1" <?php echo ($data and $data->aktif == 1) ? 'checked' : '' ?>>
            </div>
        </div>

        <div class="form control-action">
            <input type="submit" name="submit" id="submit-btn" class="btn" value="Simpan">
            <input type="reset" name="reset" id="reset-btn" class="btn fright" value="Batal">
        </div>
    </form>
</div>

