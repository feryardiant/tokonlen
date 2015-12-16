<?php defined('ROOT') or die ('Not allowed!') ?>
<form action="<?php echo currentUrl() ?>" id="user-form" method="post" class="form">
    <div class="control-group">
        <label class="label" for="username">Username</label>
        <div class="control-input">
            <input type="text" required name="username" id="username">
        </div>
    </div>
    <div class="control-group">
        <label class="label" for="password">Password</label>
        <div class="control-input">
            <input type="password" required name="password" id="password">
        </div>
    </div>
    <div class="form control-action">
        <input type="submit" name="login" id="submit-btn" class="btn" value="Login">
        <?php echo anchor('home/register', 'Registrasi', ['class' => 'btn fright']) ?>
    </div>
</form>
