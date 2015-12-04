<?php defined('ROOT') or die ('Not allowed!');

class Config
{
    // Tempat penampungan configurasi
    protected $conf;

    // Konfigurasi Bawaan
    private $base = [
        // URL Aplikasi. (http://localhost/aplikasi)
        'baseurl' => '',
        // Basis nama aplikasi
        'basename' => 'aplikasi',
        // Module utama
        'basemod' => 'home',
        // Aktifasi Debugin
        'debug'   => false,
        // Format Tanggal
        'fmtdate' => 'd-m-Y',
        // Tentang Aplikasi
        'app'     => [
            // Judul Aplikasi
            'title' => 'Toko Online',
            // Keterangan Aplikasi
            'desc' => 'Sekedar Toko Online',
        ],
        'db' => [
            // Database Host
            'host' => 'localhost',
            // Database Username
            'user' => 'root',
            // Database Password
            'pass' => 'password',
            // Database Name
            'name' => 'app_base',
            // Database Output limit
            'limit' => 10,
        ],
    ];

    /**
     * Class Constructor
     *
     * @param  array  $configs  Konfigurasi
     */
    public function __construct(array $conf = [])
    {
        $this->conf = array_merge($this->base, $conf);
    }

    /**
     * Method untuk mendapatkan semua nilai konfigurasi
     *
     * @return  array
     */
    public function all()
    {
        return $this->conf;
    }

    /**
     * Method untuk mendapatkan semua nilai konfigurasi secara flat
     *
     * @return  array
     */
    public function allFlatten()
    {
        $conf = [];
        foreach ($this->conf as $key => $value) {
            if (!is_array($value)) {
                $conf[$key] = $value;
            } else {
                foreach ($value as $vKey => $vVal) {
                    $conf[$key.'.'.$vKey] = $vVal;
                }
            }
        }

        return $conf;
    }

    /**
     * Method untuk mendapatkan nilai dari konfigurasi $key
     *
     * @param   string  $key  Nama Konfigurasi
     * @return  mixed
     */
    public function get($key)
    {
        if (isset($this->conf[$key])) {
            return $this->conf[$key];
        }

        return null;
    }

    /**
     * Method untuk menerapkan $value ke konfigurasi $key
     *
     * @param  string  $key    Nama Konfigurasi
     * @param  mixed   $value  Nilai Konfigurasi
     */
    public function add($key, $value)
    {
        if ($value instanceof Closure) {
            $value = $value($this);
        }

        $this->conf[$key] = $value;
    }
}
