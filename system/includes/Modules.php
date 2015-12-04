<?php defined('ROOT') or die ('Not allowed!');

class Modules
{
    protected $dirs = [];

    protected $path = '';

    public function __construct($modDir = 'modules')
    {
        foreach (glob($modDir.'/**/function.php') as $module) {
            $modPath = dirname($module);
            $modName = pathinfo($modPath, PATHINFO_BASENAME);
            $this->dirs[$modName] = $modPath;

            require_once $module;
        }
    }

    public function path()
    {
        return $this->path.DS;
    }

    public function all()
    {
        return array_keys($this->dirs);
    }

    public function call($modPath)
    {
        $args  = explode(DS, $modPath);
        $class = array_shift($args);

        if (!isset($this->dirs[$class])) {
            App::error($modPath);
        }

        $this->path = $this->dirs[$class];
        $Class  = new $class();
        $method = count($args) >= 1 ? array_shift($args) : 'index';
        $path   = $class.'/'.$method;

        if (!method_exists($Class, $method)) {
            App::error($modPath);
        }

        return call_user_func_array([$Class, $method], $args);
    }
}
