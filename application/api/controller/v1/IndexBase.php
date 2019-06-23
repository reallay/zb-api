<?php

namespace app\api\controller\v1;

use app\api\controller\Auth;
use think\Controller;
use think\Request;
use Util\Util;

class IndexBase extends Controller
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub

        $pathInfo = Request::instance()->pathinfo();
        include_once APP_PATH . 'api/filter/indexCheckLoginList.php';

        if (in_array($pathInfo, $whiteList)) {
            $user = new User();
            if (!$user->checkLogin()) {
                Util::printResult($GLOBALS['ERROR_LOGIN'], '未登录');
                exit;
            }
        }

        $auth = new Auth();
        $auth->respond();
    }
}