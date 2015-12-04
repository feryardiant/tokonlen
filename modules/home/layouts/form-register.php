<?php defined('ROOT') or die ('Not allowed!') ?>
<form action="<?php echo currentUrl() ?>" id="user-form" method="post" class="form">
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
            <input type="password" required name="password" id="password">
        </div>
    </div>

    <div class="control-group">
        <label class="label" for="passconf">Ulangi Password</label>
        <div class="control-input">
            <input type="password" required name="passconf" id="passconf">
        </div>
    </div>

    <fieldset>
        <legend>Biodata</legend>

        <div class="control-group">
            <label class="label" for="nama">Nama Lengkap</label>
            <div class="control-input">
                <input type="text" required name="nama" id="nama">
            </div>
        </div>

        <div class="control-group">
            <label class="label" for="telp">Telp</label>
            <div class="control-input">
                <input type="text" required name="telp" id="telp">
            </div>
        </div>

        <div class="control-group">
            <label class="label" for="alamat">Alamat</label>
            <div class="control-input">
                <textarea name="alamat" required></textarea>
            </div>
        </div>
    </fieldset>

    <div class="form control-action">
        <input type="submit" name="register" id="submit-btn" class="btn" value="Kirim" autofocus>
        <?php echo anchor('home/login', 'Login', array('class' => 'btn fright')) ?>
    </div>
</form>
