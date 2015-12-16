<?php defined('ROOT') or die ('Not allowed!');

class Uri
{
    protected $path = '';
    protected $segments = [];

    /**
     * Mendapatkan url cantik untuk aplikasi
     *
     * @param   bool    $prefix_slash  Apakah output url ingin diawali dengan '/' diawal.
     * @return  string
     */
    public function __construct($prefix_slash = false)
    {
        if (isset($_SERVER['PATH_INFO'])) {
            $uri = $_SERVER['PATH_INFO'];
        } elseif (isset($_SERVER['REQUEST_URI'])) {
            $uri = $_SERVER['REQUEST_URI'];
            $script = $_SERVER['SCRIPT_NAME'];

            if (strpos($uri, $script) === 0) {
                $uri = substr($uri, strlen($script));
            } elseif (strpos($uri, dirname($script)) === 0) {
                $uri = substr($uri, strlen(dirname($script)));
            }

            if (strncmp($uri, '?/', 2) === 0) {
                $uri = substr($uri, 2);
            }

            $parts = preg_split('#\?#i', $uri, 2);
            $uri = $parts[0];

            if (isset($parts[1])) {
                $_SERVER['QUERY_STRING'] = $parts[1];
                parse_str($_SERVER['QUERY_STRING'], $_GET);
            } else {
                $_SERVER['QUERY_STRING'] = '';
                $_GET = [];
            }

            $uri = parse_url($uri, PHP_URL_PATH);
        } else {
            $uri = '';
            // Couldn't determine the URI, so just return false
            $this->path = '';
        }

        // Do some final cleaning of the URI and return it
        if ($this->path = ($prefix_slash ? '/' : '').str_replace(['//', '../'], '/', trim($uri, '/'))) {
            $this->segments = explode('/', $this->path);
        }
    }

    /**
     * Basis URL aplikasi
     *
     * @param   string  Permalink
     * @return  string
     */
    public function base($permalink = '')
    {
        $permalink = str_replace(ROOT, '', $permalink);
        if (in_array(substr($permalink, 0, 1), ['#', '?'])) {
            $permalink = $this->path.$permalink;
        }

        return conf('baseurl').$permalink;
    }

    /**
     * Digunakan untuk mendapatkan URL saat ini
     *
     * @param   string  $permalink  URL tambahan bila perlu
     * @return  string
     */
    public function current($permalink = '', $trim = false)
    {
        $req = !empty($_GET) ? '?'.http_build_query($_GET) : '';
        $url = $this->base($this->path.$req);
        $permalink = '/'.$permalink;

        if ($trim === true) {
            $url = rtrim($url, '/');
        }

        return $url.$permalink;
    }

    /**
     * Method untuk mendapatkan segmentasi URL
     *
     * @param   int     $num  Segment Url
     * @return  string
     */
    public function segment($num)
    {
        $num -= 1;

        if (isset($this->segments[$num])) {
            return $this->segments[$num];
        }
    }

    /**
     * Uri segments
     *
     * @return  string
     */
    public function segments()
    {
        return $this->segments ;
    }

    /**
     * Uri string
     *
     * @return  string
     */
    public function path()
    {
        return $this->path ;
    }
}
