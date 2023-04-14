<?php
abstract class ControllerAbstract
{
    const CODE_SUCCESS = 200;
    const CODE_NOTFOUND = 404;
    const CODE_FORBIDDEN = 403;
    const CODE_ERR_GENERAL = 1000;
    const CODE_ERR_PARAMS = 1101;
    const CODE_ERR_SIGN = 1102;
    const CODE_ERR_DATA_NOT_EXISTS = 1201;

    /**
     * return success
     */
    protected function success($data = null, $meta = null): array
    {
        $res = array(
            'code' => self::CODE_SUCCESS
        );
        if (!empty($data)) {
            $res['data'] = $data;
        }
        if (!empty($meta)) {
            $res['meta'] = $meta;
        }
        return $res;
    }

    /**
     * return fail
     */
    protected function fail($msg, $code = self::CODE_ERR_GENERAL, $data = null): array
    {
        $res = array(
            'code' => $code,
            'msg' => $msg
        );
        if (!empty($data)) {
            $res['data'] = $data;
        }
        return $res;
    }
}