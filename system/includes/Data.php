<?php defined('ROOT') or die ('Not allowed!');

class Data
{
    /**
     * Table name
     *
     * @var string
     */
    protected static $table = '';

    /**
     * Primary key
     *
     * @var string
     */
    protected static $primary = 'id';

    /**
     * Method untuk mendapatkan instance database
     *
     * @return Db
     */
    final protected static function db()
    {
        $db = App::instance()->get('db');
        $db->primary(static::$primary);
        return $db;
    }

    /**
     * Method untuk mendapatkan nilai $table
     *
     * @return string
     */
    final public static function table()
    {
        return static::$table;
    }

    /**
     * Method untuk mendapatkan nilai $primary
     *
     * @return string
     */
    final public static function primary()
    {
        return static::$primary;
    }

    /**
     * Method untuk mendapatkan data dari static::$table berdasarkan $where
     *
     * @param  array        $where Query yang dicari
     * @param  false|string $sort  Sorting data
     * @return mixed
     */
    public static function show($where = [], $sort = false)
    {
        if (is_null(static::$table)) return null;

        if (is_numeric($where) or is_int($where)) {
            $where = [static::$primary => (int) $where];
        }

        $sort = !empty($sort) ? $sort : '';

        return self::db()->select(static::$table, '', $where, $sort);
    }

    /**
     * Method untuk menyimpan atau menambahkan $data ke static::$table berdasarkan $id
     *
     * @param  array  $data Data yang akan simpan
     * @param  string $id   ID dari data
     * @return bool
     */
    public static function save($data, $id = '')
    {
        if (is_null(static::$table)) return null;

        if ($id) {
            return self::db()->update(static::$table, $data, [static::$primary => $id]);
        } else {
            return self::db()->insert(static::$table, $data);
        }
    }

    /**
     * Method untuk menambahkan $data ke static::$table
     *
     * @param array $data Data yang akan ditambahkan
     */
    public static function add($data)
    {
        if (is_null(static::$table)) return null;

        return self::db()->insert(static::$table, $data);
    }

    /**
     * Method untuk memperbarui $data ke static::$table
     *
     * @param  array $data  Data yang akan diperbarui
     * @param  array $terms Lokasi data yang akan disimpan
     * @return bool
     */
    public static function edit($data, $terms)
    {
        if (is_null(static::$table)) return null;

        if (is_numeric($terms) or is_int($terms)) {
            $terms = [static::$primary => (int) $terms];
        }

        return self::db()->update(static::$table, $data, $terms);
    }

    /**
     * Method untuk menghapus data berdasarkan $where
     *
     * @param  array $where Lokasi data yang akan dihapus
     * @return bool
     */
    public static function del($where)
    {
        if (is_null(static::$table)) return null;

        if (is_numeric($terms) or is_int($terms)) {
            $terms = [static::$primary => (int) $terms];
        }

        return self::db()->delete(static::$table, $where);
    }
}
