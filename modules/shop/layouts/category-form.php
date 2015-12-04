<?php defined('ROOT') or die ('Not allowed!') ?>
<?php require ADMIN_SIDEBAR ?>
<div id="main-contents">
    <form action="<?php echo currentUrl() ?>" id="user-form" method="post" class="form">
        <div class="control-group">
            <label class="label" for="nama">Nama</label>
            <div class="control-input">
                <input type="text" required name="nama" id="nama" <?php echo $data ? 'value="'.$data->nama.'"' : '' ?>>
            </div>
        </div>

        <div class="control-group">
            <label class="label" for="alias">Alias</label>
            <div class="control-input">
                <input type="text" required name="alias" id="alias" <?php echo $data ? 'value="'.$data->alias.'"' : '' ?>>
            </div>
        </div>

        <div class="control-group">
            <label class="label" for="keterangan">Keterangan</label>
            <div class="control-input">
                <textarea name="keterangan" id="keterangan" required><?php echo $data ? $data->keterangan : '' ?></textarea>
            </div>
        </div>

        <div class="form control-action">
            <input type="submit" name="submit" id="submit-btn" class="btn" value="Simpan">
            <input type="reset" name="reset" id="reset-btn" class="btn fright" value="Batal">
        </div>
    </form>
</div>

