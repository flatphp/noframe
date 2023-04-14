<?php namespace Lib;

/**
 * Config
 */
class Config
{
    protected static $_pool = [];

    /**
     * @param mixed $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        return self::$_pool[$key] ?? $default;
    }

    /**
     * @param string|array $key
     * @param mixed $value
     */
    public static function set($key, $value = null)
    {
        if (is_array($key)) {
            self::$_pool = array_merge(self::$_pool, $key);
        } else {
            self::$_pool[$key] = $value;
        }
    }

    /**
     * Check if exists
     * @param mixed $key
     * @return bool
     */
    public static function has($key): bool
    {
        return isset(self::$_pool[$key]);
    }

    /**
     * Get all data
     * @return array
     */
    public static function all(): array
    {
        return self::$_pool;
    }
}