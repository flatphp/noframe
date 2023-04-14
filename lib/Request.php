<?php namespace Lib;

/**
 * 请求辅助类
 * Class Request
 * @package Lib
 */
class Request
{
    /**
     * Get all input request
     * @return array
     */
    public static function all(): array
    {
        return array_replace_recursive($_GET, $_POST);
    }

    /**
     * POST or GET
     * @param $key
     * @param mixed $default
     * @return mixed
     */
    public static function input($key, $default = null)
    {
        return filter_has_var(INPUT_POST, $key) ? self::post($key, $default) : self::get($key, $default);
    }

    /**
     * Get a get request data
     * default filter by FILTER_SANITIZE_STRING + FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH
     * @param string|null $key
     * @param mixed $default
     * @param int $filter
     * @param int $flags
     * @return mixed
     */
    public static function get(string $key = null, $default = null, int $filter = FILTER_SANITIZE_STRING, int $flags = FILTER_FLAG_STRIP_LOW)
    {
        if (null === $key) {
            return filter_var($_GET, $filter, FILTER_REQUIRE_ARRAY|$flags);
        }
        if (!isset($_GET[$key])) {
            return $default;
        }
        if (is_array($_GET[$key])) {
            return filter_input(INPUT_GET, $key, $filter, array(
                'flags' => FILTER_REQUIRE_ARRAY|$flags,
                'options' => ['default' => $default]
            ));
        } else {
            return filter_input(INPUT_GET, $key, $filter, array(
                'flags' => $flags,
                'options' => ['default' => $default]
            ));
        }
    }

    /**
     * Get a post request data
     * @param string|null $key
     * @param mixed $default
     * @param int $filter
     * @param int|null $flags
     * @return mixed
     */
    public static function post(string $key = null, $default = null, int $filter = FILTER_UNSAFE_RAW, int $flags = null)
    {
        if (null === $key) {
            return $_POST;
        }
        if (!isset($_POST[$key])) {
            return $default;
        }
        if (is_array($_POST[$key])) {
            return filter_input(INPUT_POST, $key, $filter, array(
                'flags' => FILTER_REQUIRE_ARRAY|$flags,
                'options' => ['default' => $default]
            ));
        } else {
            return filter_input(INPUT_POST, $key, $filter, array(
                'flags' => $flags,
                'options' => ['default' => $default]
            ));
        }
    }


    /**
     * Get all files uploaded
     * @return array
     */
    public static function files(): array
    {
        return $_FILES;
    }

    /**
     * Get a file uploaded
     * @param $key
     * @return array|null
     */
    public static function file($key): ?array
    {
        return $_FILES[$key] ?? null;
    }

    /**
     * Get host
     */
    public static function host()
    {
        return filter_input(INPUT_SERVER, 'HTTP_HOST');
    }


    /**
     * Get REQUEST_URI
     * @return mixed
     */
    public static function uri()
    {
        return filter_input(INPUT_SERVER, 'REQUEST_URI');
    }

    /**
     * Get script name
     * @return mixed
     */
    public static function script()
    {
        return filter_has_var(INPUT_SERVER, 'SCRIPT_NAME') ? filter_input(INPUT_SERVER, 'SCRIPT_NAME') : filter_input(INPUT_SERVER, 'PHP_SELF');
    }

    /**
     * Get url base path
     * @return string
     */
    public static function base(): string
    {
        return rtrim(dirname(self::script()), '\\/');
    }

    /**
     * Get url path
     * @return string
     */
    public static function path(): string
    {
        $uri_path = self::uri();
        if (($poz = strpos($uri_path, '?')) !== false) {
            $uri_path = substr($uri_path, 0, $poz);
        }
        return trim($uri_path, '/');
    }


    /**
     * Check if is POST
     */
    public static function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    /**
     * Check if is GET
     */
    public static function isGet(): bool
    {
        return $_SERVER['REQUEST_METHOD'] == 'GET';
    }

    /**
     * Check if is AJAX
     */
    public static function isAjax(): bool
    {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    }

}