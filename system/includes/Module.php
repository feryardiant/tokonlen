<?php defined('ROOT') or die('Not allowed!');

class Module
{
    protected $app, $data = [];

    public function __construct()
    {
        $this->app =& App::instance();

        $this->data['heading'] = '';
        $this->data['toolbar'] = null;
        $this->data['data'] = null;
    }

    /**
     * Abstract method to introduce each module when application started
     *
     * @param  App|null $app Application instances
     * @return void
     */
    public static function initialize(App $app = null, Config $config = null)
    {
        //
    }

    /**
     * Magic method to get Application container
     *
     * @param  string $container Container name
     * @return mixed
     */
    public function __get($container)
    {
        return App::instance()->get($container);
    }

    /**
     * Final method to render output
     *
     * @param  string $view  View name
     * @param  array  $data  Data you want to pass.
     * @param  bool   $alone Render it lonely?
     * @return mixed
     */
    final protected function render($view, array $data = [], $alone = false)
    {
        if (is_int($view)) {
            if (is_array($data)) {
                $data = (object) $data;
            }

            $code = !isset($data->errors) ? $view : 400;

            app()->header($code, 'application/json');
            echo json_encode($data);
            return;
        }

        $data = array_merge($this->data, $data);

        return app()->render($view, $data, $alone);
    }
}
