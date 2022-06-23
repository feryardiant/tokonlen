<?php defined('ROOT') or die ('Not allowed!');

class App
{
    // Konfigurasi
    protected $conf;

    // Container aplikasi
    private $container = [];

    // Cache Container aplikasi
    private $containerCache = [];

    // Buffer Level
    private $buffer;

    /**
     * App Instance
     *
     * @var App
     */
    private static $instance = null;

    /**
     * Class Constructor
     *
     * @param  Config  $configs  Konfigurasi
     */
    public function __construct(Config $conf = null)
    {
        // Menerapkan konfigurasi
        $this->conf = $conf;
        // Menerapkan konfigurasi (@link http://php.net/manual/en/function.ob-get-level.php)
        $this->buffer = ob_get_level();

        self::$instance =& $this;

        // Default konfigurasi $baseurl
        if ($this->conf('baseurl') == '' && isset($_SERVER['HTTP_HOST'])) {
            $this->conf('baseurl', '//'.$_SERVER['HTTP_HOST'].'/');
        }

        session_name($this->conf('basename'));
        session_start();

        // Inisiasi routing container
        $this->add('errors', function () {
            if (!class_exists('Error')) {
                require_once __DIR__.'/Error.php';
            }

            return new Error;
        });

        // Inisiasi uri container
        $this->add('uri', function () {
            return new Uri;
        });

        // Inisiasi modules container
        $this->add('modules', function ($c, $name) {
            $modules = new Modules($name);
            // Menyimpan sementara semua modules yang ada dalam cache
            $c->add($name, $modules->all());

            return $modules;
        });

        // Inisiasi stylesheets container
        $this->add('asset.css', function ($c, $name) {
            $out = [];

            foreach ($c->get($name) as $css) {
                if (strpos($css, ROOT) === 0) {
                    $css = str_replace(ROOT, '', $css);
                }

                $out[] = '<link href="'.site_url($css).'" rel="stylesheet">';
            }

            return implode(PHP_EOL, $out);
        });

        // Inisiasi javascripts container
        $this->add('asset.js', function ($c, $name) {
            $out = [];

            foreach ($c->get($name) as $js) {
                if (strpos($js, ROOT) === 0) {
                    $js = str_replace(ROOT, '', $js);
                }

                $out[] = '<script src="'.site_url($js).'"></script>';
            }

            return implode(PHP_EOL, $out);
        });

        // Inisiasi router storage
        $this->conf('routes', []);

        // Inisiasi stylesheets storage
        $this->conf('asset.css', [
            'asset/lib/jquery-ui.css',
            'asset/reset.css',
            'asset/style.css',
        ]);

        // Inisiasi javascripts storage
        $this->conf('asset.js', [
            'asset/lib/jquery.min.js',
            'asset/lib/jquery-ui.min.js',
            'asset/lib/jquery-validate.min.js',
            'asset/lib/nicedit.js',
            'asset/script.js',
        ]);
    }

    /**
     * Method untuk mendapatkan instansi dari class
     *
     * @return  self
     */
    public static function &instance()
    {
        return self::$instance;
    }

    /**
     * Magic Method yang digunakan ketia class ini dipanggil sebagai function
     * @link    http://php.net/manual/en/language.oop5.magic.php#object.invoke
     *
     * @param   string  $container  Nama container
     * @return  mixed
     */
    public function __invoke($container = '')
    {
        // Mendapatkan semua instansi class App
        $app =& self::instance();

        // Kondisi jika Kontainer tidak kosong
        if ($container) {
            return $app->get($container);
        }

        return $app;
    }

    /**
     * Method untuk mendapatkan atau menerapkan $value dari $key konfigurasi
     *
     * @param   string  $key    Nama Konfigurasi
     * @param   mixed   $value  Nilai Konfigurasi
     * @return  mixed
     */
    public function conf($key = null, $value = null)
    {
        if (is_null($key)) {
            return $this->conf->allFlatten();
        }

        if (is_null($value)) {
            return $this->conf->get($key);
        }

        $this->conf->add($key, $value);
    }

    /**
     * Menambahkan kontainer baru
     *
     * @param string   $name     Nama Container
     * @param callable $callback Closure (http://php.net/manual/en/class.closure.php)
     */
    public function add($name, callable $callback)
    {
        // if ($callback instanceof Closure) {
        //     $callback = $callback->bindTo(self::$instance);
        // }

        $this->container[$name] = $callback;
    }

    /**
     * Menambahkan kontainer baru
     *
     * @param string   $name     Nama Container
     * @param callable $callback Closure (http://php.net/manual/en/class.closure.php)
     */
    public function extend($name, callable $callback)
    {
        if (!isset($this->container[$name])) {
            throw new Exception('Could not extend non existing Container ' . $name);
        }

        $prev = $this->get($name);
        $this->container[$name] = function ($conf, $name) use ($prev, $callback) {
            return $callback($prev, $conf, $name);
        };
    }

    /**
     * Get container
     *
     * @param   string  $name  Container name
     * @return  mixed
     */
    public function get($name)
    {
        if (!isset($this->container[$name])) {
            throw new Exception('Container ' . $name . ' not found.');
        }

        if (!isset($this->containerCache[$name])) {
            $callback = $this->container[$name];
            $this->containerCache[$name] = $callback($this->conf, $name);
        }

        return $this->containerCache[$name];
    }

    /**
     * Method untuk menampilkan atau menyembunyikan Error System
     *
     * @param   bool  $enabled  True untuk menampilkan dan False untuk menyembunyikan
     * @return  void
     */
    public static function debug($enable = false)
    {
        $app =& self::instance();
        $err = $app->get('errors');

        // @link http://php.net/manual/en/function.set-error-handler.php
        @set_error_handler([$err, 'errHandler']);
        // @link http://php.net/manual/en/function.set-exception-handler.php
        @set_exception_handler([$err, 'excHandler']);

        if ($enable) {
            error_reporting(E_ALL);
            ini_set("display_errors", 1);
            ini_set("display_startup_errors", 1);
            ini_set("html_errors", 1);
        } else {
            error_reporting(0);
            ini_set("display_errors", 0);
        }
    }

    public function header($code = 200, $type = '') {
        // HTTP response status codes (http://httpstatus.es)
        $http = [
            // Successful 2xx
            200 => 'OK',
            // Redirection 3xx
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            304 => 'Not Modified',
            // Client Error 4xx
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            // Server Error 5xx
            500 => 'Internal Server Error',
        ];

        $type || $type = 'text/html';
        if (isset($http[$code])) {
            if (IS_AJAX) {
                $type = 'application/json';
            }

            header('Content-type: '.$type.'; charset=utf-8', true, $code);
        }
    }

    /**
     * Method untuk memulai aplikasi
     *
     * @param   string  $modDir  Module directori
     * @return  string
     */
    public function start($modDir = 'modules')
    {
        try {
            $uri    = $this->get('uri');
            $module = $this->get('modules');

            foreach ($this->conf('modules') as $mod) {
                $init = ucfirst($mod).'::initialize';
                if (is_callable($init)) {
                    call_user_func($init, $this, $this->conf);
                }
            }

            $this->get('routes');
            $routes = $this->conf('routes');
            $path = $uri->path() ?: $this->conf('basemod');

            list($route) = explode('/', $path);
            if (isset($routes[$route])) {
                $path = str_replace($route, $routes[$route], $path);
            }

            $response = $module->call($path);

            if (is_array($response) or is_object($response)) {
                $response = json_encode($response);
            }

            echo $response;

            if (session('flash')) {
                session('flash', null);
                session_clear('flash');
            }
        } catch (Exception $e) {
            echo $this->show404($e->getMessage());
            exit();
        }
    }

    /**
     * Method untuk memuat file
     *
     * @param   string  $filepath  Lokasi file yang akan dimuat
     * @return  string
     */
    public function render($view, array $data = [], $alone = false)
    {
        if (is_int($view) && $view !== 404) {
            if ($view === 200) {
                return json_encode($data);
            } else {
                $this->header($view);
            }
        } else {
            $layout = SYSPATH.'layouts/';

            if ($view === 404) {
                $this->header($view);
                $view = $layout.'error.php';
            } else {
                $module = $this->get('modules');
                $view = $module->path().'layouts/'.$view.'.php';
            }

            if (!empty($data)) {
                extract($data);
            }

            ob_start();
            if ($alone === true) {
                require $view;
            } else {
                require $layout.'header.php';
                require $view;
                require $layout.'footer.php';
            }

            unset($view);
            $contents = ob_get_clean();
            if (ob_get_level() > $this->buffer + 1) {
                ob_end_flush();
            }

            return $contents;
        }
    }

    /**
     * Mendampilkan Halaman 404 not found jika router tidak ditemukan
     *
     * @param   string  $filepath  Router tujuan
     * @return  string
     */
    public function show404($filepath = '')
    {
        $filepath or $filepath = app('uri')->path();

        return $this->render(404, [
            'heading' => 'Oops! Error bro.',
            'message' => 'Tidak dapat memuat halaman \''.$filepath.'\' atau halaman tesebut tidak ada dalam server kami.',
        ]);
    }

    /**
     * Method untuk melempar error sistem sebagai RuntimeException
     *
     * @param   string  $string  Error text
     * @throws  RuntimeException
     */
    public static function error($string)
    {
        throw new RuntimeException($string);
    }
}
