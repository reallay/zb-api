<?php

namespace app\api\controller\v1;

use think\Controller;
use think\Request;
use Util\Util;

class AdminBase extends Controller
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub

        $pathInfo = Request::instance()->pathinfo();
        include_once APP_PATH . 'api/filter/adminCheckLoginList.php';

        if (in_array($pathInfo, $whiteList)) {
            $adminUser = new AdminUser();
            if (!$adminUser->checkLogin()) {
                Util::printResult($GLOBALS['ERROR_LOGIN'], '未登录');
                exit;
            }
        }

//        $validateApi = new ValidateApi();
//        $validateApi->respond();

    }
}