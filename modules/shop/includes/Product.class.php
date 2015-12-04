<?php defined('ROOT') or die ('Not allowed!');

class Product extends Data
{
    /**
     * {inheritdoc}
     */
    protected static $table = 'tbl_produk';

    /**
     * {inheritdoc}
     */
    protected static $primary = 'id_produk';
}
