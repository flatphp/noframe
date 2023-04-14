<?php namespace Lib;

use Exception;

/**
 * Curl封装
 * Class Curl
 * @package Lib
 */
class Curl
{
    const DECODER_JSON = 'jsonDecode';
    const DECODER_NONE = 'noneDecode';

    protected $retry_times = 0;
    protected $timeout = 10;
    protected $decoder = self::DECODER_JSON;  // json_decode default
    protected $headers = array();


    public function __construct(array $options = [])
    {
        foreach ($options as $key => $value) {
            $this->$key = $value;
        }
    }

    public function setRetryTimes($retry_times): Curl
    {
        $this->retry_times = (int)$retry_times;
        return $this;
    }

    public function setTimeout($timeout): Curl
    {
        $this->timeout = (int)$timeout;
        return $this;
    }

    public function raw(): Curl
    {
        $this->decoder = self::DECODER_NONE;
        return $this;
    }

    public function setHeaders(array $value): Curl
    {
        $this->headers = $value;
        return $this;
    }

    public function addHeader($key, $value): Curl
    {
        $this->headers[$key] = $value;
        return $this;
    }

    /**
     * GET
     * @param mixed $params
     * @return mixed
     * @throws Exception
     */
    public function get($url, $params = null)
    {
        return $this->requestHandle('GET', $url, $params);
    }

    /**
     * POST
     * @param mixed $params
     * @return mixed
     * @throws Exception
     */
    public function post($url, $params)
    {
        return $this->requestHandle('POST', $url, $params);
    }

    /**
     * post json
     * @param mixed $params
     * @return mixed
     * @throws Exception
     */
    public function postJson($url, $params)
    {
        $this->headers['Content-Type'] = 'application/json';
        return $this->requestHandle('POSTJSON', $url, $params);
    }

    /**
     * @throws Exception
     */
    protected function requestHandle($method, $url, $params)
    {
        $res = $this->request($method, $url, $params);
        if ($res === false && $this->retry_times > 0) {
            $this->retry_times -= 1;
            $res = $this->requestHandle($method, $url, $params);
        }
        return $res;
    }


    /**
     * 获取请求内容
     * @param $method
     * @param $url
     * @param $params
     * @return mixed
     * @throws Exception
     */
    protected function request($method, $url, $params)
    {
        $headers = [];
        foreach ($this->headers as $key => $value) {
            $headers[] = $key . ': ' . $value;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->buildPostData($params));
        } elseif ($method == 'POSTJSON') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->buildJsonData($params));
        } else {
            $url = $this->buildUrl($url, $params);
            curl_setopt($ch, CURLOPT_HTTPGET, true);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $res = curl_exec($ch);

        if ($res === false) {
            $errmsg = curl_error($ch);
            $errcode = curl_errno($ch);
            curl_close($ch);
            throw new Exception($errmsg, $errcode);
        } else {
            $de_method = $this->decoder;
            $res = $this->$de_method($res);
        }
        curl_close($ch);
        return $res;
    }


    protected function buildUrl($url, $mixed_data = ''): string
    {
        $query_string = '';
        if (!empty($mixed_data)) {
            $query_mark = strpos($url, '?') > 0 ? '&' : '?';
            if (is_string($mixed_data)) {
                $query_string .= $query_mark . $mixed_data;
            } elseif (is_array($mixed_data)) {
                $query_string .= $query_mark . http_build_query($mixed_data, '', '&');
            }
        }
        return $url . $query_string;
    }

    protected function buildPostData($data): string
    {
        if (is_string($data)) {
            return $data;
        } else {
            return http_build_query($data, '', '&');
        }
    }

    protected function buildJsonData($data)
    {
        if (is_string($data)) {
            return $data;
        } else {
            return json_encode($data, JSON_UNESCAPED_UNICODE);
        }
    }

    protected function noneDecode($data)
    {
        return $data;
    }

    protected function jsonDecode($data)
    {
        return json_decode($data, true);
    }
}