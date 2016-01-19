<?php defined('ROOT') or die('Not allowed!');

class Menu
{
    protected $list = [];

    public function __construct(array $list)
    {
        $this->list = $list;
    }

    public function add(array $list)
    {
        $this->list = array_merge($this->list, $list);
    }

    public function prepend(array $list)
    {
        $this->list = array_merge($this->list, $list);
        $this->list = array_reverse($this->list);
    }

    public function get()
    {
        return $this->list;
    }

    public function isCurrent($link = '')
    {
        $current = app('uri')->path();

        if ($link) {
            return strpos($current, $link) === 0;
        } elseif ($link == '' and $current == $link) {
            return true;
        }
    }

    public function compile(array $list = [], $classAttr = '')
    {
        if (empty($list)) {
            $list = $this->list;
        }

        $out = '<ul'.($classAttr ? ' class="'.$classAttr.'"' : '').'>';

        foreach ($list as $link => $label) {
            if (is_int($link) and $label == '-') {
                $out .= '<li class="menu-devider"></li>';
            } else {
                $class = '';
                $link = ltrim($link, '/');

                if ($this->isCurrent($link)) {
                    $class = ' class="active"';
                }

                if (is_string($label)) {
                    $out .= '<li'.$class.'>'.anchor($link, $label).'</li>';
                } else {
                    $attrs = array_set_defaults(
                        $label, [
                        'label' => '',
                        'subs'  => [],
                        ]
                    );

                    $out .= '<li'.$class.'>';
                    $out .= anchor($link, $attrs['label']);
                    $out .= $this->compile($attrs['subs'], 'submenu');
                    $out .= '</li>';
                }
            }
        }

        $out .= '</ul>';

        return $out;
    }

    public function nav($classAttr = '')
    {
        $classAttr or $classAttr = 'menu fleft';

        return $this->compile($this->list, $classAttr);
    }
}
