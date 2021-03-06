<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/31 0031
 * Time: 15:29
 */

namespace Util;

class Util
{
    /**
     * 输出结果的函数
     * @param success 是否成功 true是成功，false是失败
     */
    public static function printResult($errorCode, $data)
    {
        $result['errorCode'] = $errorCode;
        if (is_array($data)) {
            $result['msg'] = "";
            $result['data'] = $data;
        } else {
            $result['msg'] = $data;
            $result['data'] = new \stdClass();
        }
        echo json_encode($result, JSON_UNESCAPED_SLASHES);
    }

    //生成随机码
    public static function generateRandomCode($len)
    {
        $code = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $str = "";
        $strlen = strlen($code);
        for ($i = 0; $i < $len; $i++) {
            $str = $str . substr($code, rand(0, $strlen - 1), 1);
        }
        return $str;
    }

    /**
     * 生成验证码
     * @param $num 验证码位数
     * @return string 验证码
     */
    public static function generateVcode($num)
    {
        $res = "";
        while (($num--) > 0) {
            $res .= rand(0, 9);
        }
        return $res;
    }
}