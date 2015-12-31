<?php defined('ROOT') or die ('Not allowed!') ?>
<?php require __DIR__.'/sidebar.php' ?>
<div id="main-contents">
    <form action="<?php echo current_url() ?>" id="user-form" method="post" class="form">
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
            <label class="label" for="level">Tipe</label>
            <div class="control-input">
                <select name="level">
                    <option>Pilih Tipe</option>
                <?php foreach (User::levels() as $key => $value): ?>
                    <option <?php echo ($data and $data->level == $key) ? 'selected' : '' ?> value="<?php echo $key ?>"><?php echo ucfirst($value) ?></option>
                <?php endforeach ?>
                </select>
            </div>
        </div>

        <div class="control-group">
            <label class="label" for="password">Password</label>
            <div class="control-input">
                <input type="password" name="password" id="password" class="small">
                <input type="password" name="passconf" id="passconf" class="small">
            </div>
        </div>

        <div class="form control-action">
            <input type="submit" name="submit" id="submit-btn" class="btn" value="Simpan">
            <input type="reset" name="reset" id="reset-btn" class="btn fright" value="Batal">
        </div>
    </form>
</div>

