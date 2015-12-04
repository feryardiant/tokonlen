<?php defined('ROOT') or die ('Not allowed!') ?>
<?php require __DIR__.'/sidebar.php' ?>
<div id="main-contents">
    <form action="<?php echo currentUrl() ?>" id="user-form" method="post" class="form">
        <div class="control-group">
            <label class="label" for="judul">Judul</label>
            <div class="control-input">
                <input type="text" required name="judul" id="judul" <?php echo $data ? 'value="'.$data->judul.'"' : '' ?>>
            </div>
        </div>

        <div class="control-group">
            <label class="label" for="alias">Alias</label>
            <div class="control-input">
                <input type="text" required name="alias" id="alias" <?php echo $data ? 'value="'.$data->alias.'"' : '' ?>>
            </div>
        </div>

        <div class="control-group">
            <label class="label" for="konten">Konten</label>
            <div class="control-input">
                <textarea name="konten" id="konten" class="full"><?php echo $data ? $data->konten : '' ?></textarea>
            </div>
        </div>

        <div class="form control-action">
            <input type="submit" name="submit" id="submit-btn" class="btn" value="Simpan">
            <input type="reset" name="reset" id="reset-btn" class="btn fright" value="Batal">
        </div>
    </form>
</div>

