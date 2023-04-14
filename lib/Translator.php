<?php namespace Lib;

/**
 * 语言包调用封装
 * Class Translator
 * @package Lib
 */
class Translator
{
    protected $file_path;
    protected $messages = [];
    protected $domain = 'common';

    public function __construct($file_path)
    {
        $this->file_path = $file_path;
    }

    public function setDomain($domain): Translator
    {
        $this->domain = $domain;
        return $this;
    }

    public function trans($message)
    {
        if (!isset($this->messages[$this->domain])) {
            $msg_file = $this->file_path .'/'. $this->domain .'.php';
            if (is_file($msg_file)) {
                $this->messages[$this->domain] = include($msg_file);
            }
        }
        if (isset($this->messages[$this->domain][$message])) {
            $message = $this->messages[$this->domain][$message];
        }
        $args = func_get_args();
        if (count($args) > 2) {
            $args[1] = $message;
            unset($args[0]);
            return call_user_func_array('sprintf', $args);
        } else {
            return $message;
        }
    }
}