<?php defined('ROOT') or die ('Not allowed!');

class Order extends Data
{
    /**
     * {inheritdoc}
     */
    protected static $table = 'tbl_order';

    /**
     * {inheritdoc}
     */
    protected static $primary = 'id_order';

    public static $statuses = ['Belum dibayar', 'Lunas'];

    public static function status($status)
    {
        return isset(self::$statuses[$status]) ? self::$statuses[$status] : '-';
    }

    public static function show($where = array())
    {
        $db = static::db();
        $join_primary = Customer::primary();

        $sql = sprintf(
            'SELECT a.*, b.nama_lengkap, b.alamat, b.kota, b.telp FROM %1$s a LEFT JOIN %2$s b USING (%3$s)',
            static::table(),
            Customer::table(),
            $join_primary
        );

        if (!empty($where)) {
            if (isset($where[static::$primary])) {
                $where['a.'.static::$primary] = $where[static::$primary];
                unset($where[static::$primary]);
            }

            if (isset($where[$join_primary])) {
                $where['b.'.$join_primary] = $where[$join_primary];
                unset($where[$join_primary]);
            }

            $where = $db->_parseWhere($where);
            $sql .= ' %s';
        }

        return $db->query($sql, $where);
    }

    public static function add($data)
    {
        if ($return = static::db()->insert(static::$table, $data)) {
            $prod_primary = Product::primary();

            foreach (unserialize($data['produk']) as $id => $qty) {
                $product = Product::show([$prod_primary => $id])->fetchOne();

                if (!$return) break;

                $return = Product::edit([
                    'stok' => ($product->stok - $qty)
                ], [$prod_primary => $product->id_produk]);
            }

            return $return;
        }

        return false;
    }

    public static function cart($id, $do, $items)
    {
        if ($do == 'add') {
            $items[$id] = isset($items[$id]) ? $items[$id] + 1 : 1;
        } elseif ($do == 'remove') {
            if (isset($items[$id])) {
                if ($items[$id] > 1) {
                    $items[$id] = $items[$id] - 1;
                } else {
                    unset($items[$id]);
                }
            }
        } elseif ($do == 'clear') {
            $items = array();
        }

        return $items;
    }

    /**
     * Method untuk mendapatkan data kota
     *
     * @return array
     */
    public static function cities()
    {
        static $cities;

        if (empty($cities)) {
            if (file_exists($cities_path = __DIR__.'/../cities.json')) {
                $cities = file_get_contents($cities_path);
            }
        }
        return (array) json_decode($cities);
    }

    /**
     * Method untku menampilkan ID dari kota berdasarkan $name
     *
     * @param  string $name Nama kota
     * @return int
     */
    public static function cityId($name = '')
    {
        $name || $name = conf('app.city');

        $name = strtolower($name);
        if (($id = array_search($name, static::cities())) !== false) {
            return (int) $id;
        }
    }
}
