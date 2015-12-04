<?php defined('ROOT') or die ('Not allowed!');

class Error
{
    public static $types = [
        'warning' => 'Oops!',
        'error'   => 'Error!',
        'notice'  => 'Info!',
        'success' => 'Success!'
    ];

    public static function errHandler($errno, $message, $file, $line, $context)
    {
        $die = false;
        switch ($errno) {
            case E_USER_ERROR:
                $type = 'error';
                $die = true;
            break;
            case E_USER_WARNING:
            case E_WARNING:
            case @E_RECOVERABLE_ERROR:
                $type = 'warning';
            break;
            case E_USER_NOTICE:
            case E_NOTICE:
            case @E_STRICT:
                $type = 'notice';
            break;
            default:
                $type = '';
                $die = true;
            break;
        }

        $text = $message;
        $file = str_replace(array(ROOT, '/'), array('', DIRECTORY_SEPARATOR), $file);
        $message = '<strong>'.$text.'</strong> in <code>'.$file.' ('.$line.')</code>';

        self::alert($message, $type, $die);
    }

    public static function excHandler($exc)
    {
        self::alert($exc);
    }

    static function alert($message, $type = '', $die = false)
    {
        $types = self::$types;

        if ($type == '' || !in_array($type, array_keys($types))) {
            $type = 'warning';
        }

        if ($message instanceof Exception) {
            $type = 'error';
            $die = true;
            $message = $message->getMessage();
        }

        $message = '<strong>'.$types[$type].':</strong><br>'.$message;

        if (PHP_SAPI == 'cli') {
            $message = str_replace(
                array('<strong>', '</strong>', '<code>', '</code>', '<br>'),
                array('', '', '"', '"', PHP_EOL),
                $message
            );
        }

        $message = PHP_SAPI != 'cli' ? '<p class="alert '.$type.'">'.$message.'</p>' : PHP_EOL.$message.PHP_EOL;

        if ($die) {
            $app =& App::instance();
            $app->header(500);

            die($message);
        } else {
            echo $message;
        }
    }
}
