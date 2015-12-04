<?php defined('ROOT') or die ('Not allowed!');

class Upload
{
    protected $target = '';

    protected $file = '';

    public function __construct($field, $target = '')
    {
        if (!isset($_FILES[$field])) {
            App::error('Invalid upload field');
        }

        $this->target = $target;
        $this->file = $_FILES[$field];

        if (!$this->target) {
            $this->target = ROOT.'asset/uploads/';
        }
    }

    public function doUpload()
    {
        if (!is_uploaded_file($this->file['tmp_name'])) {
            App::error('Upload gagal');
        }

        $filename = preg_replace('/\s+/', '_', $this->file['name']);
        $basename = basename($filename);
        $fileext  = pathinfo($filename, PATHINFO_EXTENSION);
        $filename = md5($basename.time()).'.'.$fileext;

        if (!copy($this->file['tmp_name'], $this->target.$filename)) {
            if (!move_uploaded_file($this->file['tmp_name'], $this->target.$this->file['name'])) {
                App::error('Upload gagal');
            }
        }

        return $filename;
    }
}
