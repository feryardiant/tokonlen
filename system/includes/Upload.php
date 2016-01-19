<?php defined('ROOT') or die('Not allowed!');

class Upload
{
    protected $target = '';

    protected $file = '';

    public function __construct($field, $target = '')
    {
        if (!isset($_FILES[$field])) {
            throw new Exception('Invalid upload field');
        }

        $target or $target = ROOT.'asset/uploads/';

        $this->target = $target;
        $this->file = $_FILES[$field];
    }

    public function doUpload()
    {
        if (!is_uploaded_file($this->file['tmp_name'])) {
            throw new Exception('Upload gagal');
        }

        $filename = preg_replace('/\s+/', '_', $this->file['name']);
        $basename = basename($filename);
        $fileext  = pathinfo($filename, PATHINFO_EXTENSION);
        $fileext  = strtolower($fileext);
        $filename = md5($basename.time()).'.'.$fileext;

        $allowed = conf('allowed_exts') ?: ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($fileext, $allowed)) {
            throw new Exception('Pengunggahan hanya mengijinkan berkas ber-ekstensi '.implode(',', $allowed).' saja.');
        }

        if (!copy($this->file['tmp_name'], $this->target.$filename)) {
            if (!move_uploaded_file($this->file['tmp_name'], $this->target.$filename)) {
                throw new Exception('Upload gagal');
            }
        }

        return $filename;

    }
}
