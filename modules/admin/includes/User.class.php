<?php defined('ROOT') or die ('Not allowed!');

class User extends Data
{
    /**
     * {inheritdoc}
     */
    protected static $table = 'tbl_pengguna';

    /**
     * {inheritdoc}
     */
    protected static $primary = 'id_pengguna';

    /**
     * Daftar level pengguna
     *
     * @var array
     */
    protected static $levels = ['pelanggan', 'admin'];

    /**
     * Method untuk mendapatkan semua data level pengguna
     *
     * @return array
     */
    public static function levels()
    {
        return self::$levels;
    }

    /**
     * Method untuk memastikan bahwa user telah login
     *
     * @return bool
     */
    public static function loggedin()
    {
        return session('auth') !== null;
    }

    /**
     * Method untuk mendapatkan data session dari user berdasarkan $key
     *
     * @param  string $key Session key
     * @return mixed
     */
    public static function current($key)
    {
        return session($key);
    }

    /**
     * Method untuk memastikan bahwa pengguna yang login adalah $alias
     *
     * @param  string $alias Alias untuk level pengguna
     * @return bool
     */
    public static function is($alias)
    {
        $level = (int) session('level');

        if (!isset(self::$levels[$level])) {
            return false;
        }

        return self::$levels[$level] == $alias;
    }

    /**
     * Method untuk mendapatkan alias dari level pengguna
     *
     * @param  string $key Kunci level
     * @return string
     */
    public static function getAlias($key)
    {
        return isset(self::$levels[$key]) ? ucfirst(self::$levels[$key]) : '-';
    }
}
