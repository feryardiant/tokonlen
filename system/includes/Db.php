<?php defined('ROOT') or die('Not allowed!');

class Db
{
    /**
     * @var mysqli
     */
    private $_db;

    /**
     * @var mysqli_result
     */
    private $_results;

    private $_sql;

    private $_num_rows = 0;

    private $_primary = null;

    // Konfigurasi
    protected $configs = [
        'host' => '',
        'user' => '',
        'pass' => '',
        'name' => '',
        'pref' => '',
    ];

    /**
     * Class Constructor
     *
     * @param array $configs Konfigurasi
     */
    public function __construct(array $configs = [])
    {
        // Menerapkan setiap konfigurasi dari $configs
        $this->configs = array_merge($this->configs, $configs);

        if (!empty($this->configs['host']) && !empty($this->configs['user'])) {
            $this->connect();
        }
    }

    /**
     * Koneksi database
     *
     * @return void
     */
    public function connect(array $configs = [])
    {
        if (!empty($configs) && empty($this->configs)) {
            new self($configs);
        }

        foreach (['host', 'user', 'pass', 'name'] as $conf) {
            $$conf = $this->configs[$conf];
        }

        try {
            $this->_db = new mysqli($host, $user, $pass, $name);
            // Konek ke database menggunakan mysqli driver
            if ($this->_db->connect_error) {
                App::error('Could not connect to database, please check your configs.php file. <br>'.$this->_db->connect_error);
            } else {
                $this->_db->set_charset('utf8');
            }
        } catch (RuntimeException $e) {
            set_alert('error', $e->getMessage());
            return false;
        }
    }

    /**
     * Eksekutor
     * ----------------------------------------------------------------------
*/

    /**
     * Query database
     *
     * @param  string       $sql         SQL Query
     * @param  array|string $replacement Replacement
     * @return mixed
     */
    public function query()
    {
        $args = func_get_args();
        $sql = array_shift($args);

        // Menerapkan $replacement ke pernyataan $sql
        if (!empty($args)) {
            $sql = vsprintf($sql, $args);
        }

        // Return 'false' kalo belum ada koneksi
        if (!$this->_db) {
            $this->connect();
        }

        $this->_sql = $sql;

        // Eksekusi SQL Query
        if ($results = $this->_db->query($sql)) {
            if (is_bool($results)) {
                return $results;
            }

            $this->_results = $results;
            $this->_num_rows = $this->_results->num_rows;

            return $this;
        } else {
            App::error($this->_db->error.'<br>'.$this->_sql);
        }
    }

    /**
     * Mendapatkan jumlah record dari query
     *
     * @return int
     */
    public function count()
    {
        return $this->_num_rows;
    }

    /**
     * Menerapkan pembatasan jumlah record hasil query
     *
     * @param  mixed $limit Jumlah pembatasan
     * @return [type]
     */
    protected function doLimit($limit = null)
    {
        // Mendapatkan nilai untuk pembagian jumlah data ditampilkan tiap halaman
        $hal = get('hal') ?: 1;

        // Jika $limit bernilai 0 atau 'true' maka gunakan konfigurasi $db_limit
        if ($limit === null or $limit === true) {
            $limit = conf('db.limit');
        }

        // Jika $limit bernilai 1
        $db_limit = $limit !== 1 ? ($hal * $limit - $limit).', '.$limit : $limit;
        $_num_rows = $this->_num_rows;

        if ($this->_results) {
            $this->clear();
        }

        $this->_sql .= ' LIMIT '.$db_limit;
        $this->query($this->_sql);
        $this->_num_rows = $_num_rows;
    }

    /**
     * Mendapatkan hasil eksekusi query
     *
     * @param  string $limit Jumlah pembatasan output
     * @return mixed
     */
    public function fetch($limit = null)
    {
        if ($limit !== false) {
            $this->doLimit($limit);
        }

        $result = [];
        if ($this->_results) {
            // Lakukan perulangan dari hasil query
            while ($row = $this->_results->fetch_object()) {
                $result[] = $row;
            }
            $this->clear();
        }

        return $result;
    }

    /**
     * Mendapatkan 1 hasil eksekusi query
     *
     * @return mixed
     */
    public function fetchOne()
    {
        $result = $this->fetch(1);

        return array_shift($result);
    }

    public function clear()
    {
        $this->_results->close();
        $this->_results = null;
        $this->_num_rows = null;
    }

    /**
     * Utama
     * ----------------------------------------------------------------------
*/

    /**
     * Menampilkan data dari Database
     *
     * @param  string       $table  Nama Tabel
     * @param  string       $column Kolom
     * @param  array        $where  Pernyataan `where` dalam array
     * @param  bool|integer $limit  Batasan output
     * @param  string       $sort   Sorting data
     * @return mixed
     */
    public function select($table, $column = '', $where = [], $sort = '')
    {
        $column or $column = '*';

        $sql = 'SELECT %s FROM `%s` %s';
        $where = $this->_parseWhere($where);

        // Mendapatkan nilai untuk ordering data
        if ($sort !== '') {
            if (strpos($sort, ':') !== false) {
                list($by, $order) = explode(':', $sort);
            } else {
                $by = $sort;
                $order = 'desc';
            }

            $order = strtolower($order);
            $order = in_array($order, ['asc', 'desc']) ? $order : 'desc';
            $sql .= ' ORDER BY `'.$by.'` '.$order;
        } else {
            $sql .= ' ORDER BY `'.$this->_primary.'` DESC';
        }

        try {
            return $this->query($sql, $column, $table, $where);;
        } catch (Exception $e) {
            set_alert('error', $e->getMessage());
            return false;
        }
    }

    /**
     * Menyimpan data baru kedalam $table
     *
     * @param  string $table Nama Tabel
     * @param  array  $data  Data yang akan dimasukan
     * @return bool
     */
    public function insert($table, $data = [])
    {
        if (empty($data)) { return false;
        }

        $values = [];

        foreach (array_values($data) as $i => $val) {
            if (is_numeric($val)) {
                $val = (int) $val;
            } elseif (is_string($val)) {
                $val = '\''.$this->escape($val).'\'';
            }
            $values[$i] = $val;
        }

        // Split the column and values
        $column = implode('`, `', array_keys($data));
        $values = implode(', ', $values);

        // Escape unwanted character
        $column = $this->escape($column);

        try {
            if ($this->query('INSERT INTO `%s` (`%s`) VALUES (%s)', $table, $column, $values)) {
                return $this->getInsertId();
            }

            return false;
        } catch (Exception $e) {
            set_alert('error', $e->getMessage());
            return false;
        }
    }

    /**
     * Memperbarui data pada $table
     *
     * @param  string $table Nama Tabel
     * @param  array  $data  Data yang akan diperbarui
     * @param  array  $where Kodisi
     * @return bool
     */
    public function update($table, $data = [], $where = [])
    {
        if (empty($data)) { return false;
        }

        $wheres = $this->_parseWhere($where);
        $data = $this->_parseArgs($data, ',');

        try {
            if ($this->query("UPDATE `%s` SET %s %s", $table, $data, $wheres)) {
                return in_array($table, ['tbl_surat', 'tbl_surat_detil']) && isset($where['id']) ? $where['id'] : true;
            }

            return false;
        } catch (Exception $e) {
            set_alert('error', $e->getMessage());
            return false;
        }
    }

    /**
     * Menghapus data pada $table
     *
     * @param  string $table Nama Tabel
     * @param  array  $where Kondisi
     * @return bool
     */
    public function delete($table, $where = [])
    {
        $wheres = $this->_parseWhere($where);

        try {
            return $this->query("DELETE FROM `%s` %s", $table, $wheres);
        } catch (Exception $e) {
            set_alert('error', $e->getMessage());
            return false;
        }
    }

    // -------------------------------------------------------------------------

    /**
     * Melakukan import ke database
     *
     * @param  string $filename Nama file (database.sql) yang akan diimport
     * @return bool
     */
    public function import($filename)
    {
        $error = 0;
        $lines = file($filename);
        $query = '';

        foreach ($lines as $line) {
            if (substr($line, 0, 2) == '--' or $line == '') {
                continue;
            }

            $query .= trim($line).' ';

            if (substr(trim($line), -1, 1) == ';') {
                if (!$this->query($query)) {
                    $error++;
                    break;
                } else {
                    $query = '';
                }
            }
        }

        return $error == 0 ? true : false;
    }

    // -------------------------------------------------------------------------

    /**
     * Melakukan export (backup) database
     *
     * @todo   selesaikan!
     * @return null
     */
    public function export()
    {
        // Belum terlalu butuh, jadi ntar aja lah :P
    }

    // -------------------------------------------------------------------------

    /**
     * Helper method untuk setup primary key
     *
     * @param  string $name Primary key
     * @return $this
     */
    public function primary($name)
    {
        $this->_primary = $name;
        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Pengolahan array agar menghasilkan klausa WHERE untuk sql query
     *
     * @param  array|string $where Klausa untuk diolah
     * @return string
     */
    public function _parseWhere($where)
    {
        if (empty($where)) { return;
        }
        $return = 'WHERE';

        // Jika klausa merupakan array
        if (is_array($where)) {
            // foreach ($where as $field => $val) {
            //     $return .= 'WHERE';
            // }
            return $this->_parseWhere($this->_parseArgs($where, 'AND'));
        } elseif (is_string($where)) {
            return 'WHERE '.$where;
        }
    }

    /**
     * Mengolah array menjadi suatu klausa tertentu untuk digunakan dalam database
     *
     * @param  array  $args Array untuk diolah
     * @param  string $sep  Pembatasan tertentu
     * @return string
     */
    protected function _parseArgs(array $args, $sep = '')
    {
        $i = 0;
        $attr = '';

        foreach ($args as $key => $value) {
            // temporarely comment this out (buggy)
            // if (!empty($value)) {
            // }
            if (is_numeric($value) or is_int($value)) {
                $attr .= " {$key}={$value}";
            } elseif (is_string($value)) {
                $value = $this->escape($value);
                $attr .= " {$key}='{$value}'";
            }

            if (count($args) > 1 && (count($args) - 1) != $i) {
                $attr .= ' '.$sep.' ';
            }
            $i++;
        }

        $attr = trim($attr);
        $return = rtrim($attr, $sep);

        return $return;
    }

    /**
     * Mendapatkan nilai primary key dari data yang baru saja di masukan (simpan)
     *
     * @return string
     */
    public function getInsertId()
    {
        if ($this->_db) {
            if (isset($this->_db->insert_id)) {
                return $this->_db->insert_id;
            }

            return mysqli_insert_id($this->_db);
        }
    }

    /**
     * Menyaring karakter yang tidak diinginkan agar tidak masuk ke database
     *
     * @param  string $str Karakter yang akan disaring
     * @return string
     */
    public function escape($str)
    {
        if ($this->_db) {
            return $this->_db->real_escape_string($str);
        }
        return $str;
    }
}
