<?php defined('ROOT') or die ('Not allowed!');

class Customer extends Data
{
    /**
     * {inheritdoc}
     */
    protected static $table = 'tbl_pelanggan';

    /**
     * {inheritdoc}
     */
    protected static $primary = 'id_pelanggan';

    public static function show($where = [])
    {
        $db = static::db();
        $join_primary = User::primary();

        $sql = sprintf(
            'SELECT a.*, b.username, b.email, b.aktif FROM %1$s a LEFT JOIN %2$s b USING (%3$s)',
            static::table(),
            User::table(),
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
}
