<?php

namespace app\api\controller\v1;

use app\api\model\NewsModel;
use think\cache\driver\Redis;
use think\Request;
use Util\Check;
use Util\Util;

class News extends AdminBase
{
    public function add()
    {
        $params = Request::instance()->request();
        $categoryId = Check::checkInteger($params['categoryId'] ?? '');
        $title = Check::check($params['title'] ?? '');
        $keywords = Check::check($params['keywords'] ?? '');
        $description = Check::check($params['description'] ?? '');
        $content = Check::check($params['content'] ?? '');
        $imgUrl = Check::check($params['imgUrl'] ?? '');
        $userId = $GLOBALS['userId'];

        if ($title == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $newsModel = new NewsModel();
        $data = [
            'categoryId' => $categoryId,
            'title' => $title,
            'keywords' => $keywords,
            'description' => $description,
            'content' => $content,
            'imgUrl' => $imgUrl,
            'createTime' => currentTime(),
            'createBy' => $userId,
            'updateTime' => currentTime(),
            'updateBy' => $userId
        ];

        $insertRow = $newsModel->save($data);
        if ($insertRow > 0) {
            $arr['id'] = $newsModel->id;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '添加失败');
            exit;
        }

    }

    public function edit()
    {
        $params = Request::instance()->request();
        $newsId = Check::checkInteger($params['newsId'] ?? '');
        $categoryId = Check::checkInteger($params['categoryId'] ?? '');
        $title = Check::check($params['title'] ?? '');
        $keywords = Check::check($params['keywords'] ?? '');
        $description = Check::check($params['description'] ?? '');
        $content = Check::check($params['content'] ?? '');
        $imgUrl = Check::check($params['imgUrl'] ?? '');
        $userId = $GLOBALS['userId'];

        if ($title == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }
        $newsModel = new NewsModel();
        $data = [
            'categoryId' => $categoryId,
            'title' => $title,
            'keywords' => $keywords,
            'description' => $description,
            'content' => $content,
            'imgUrl' => $imgUrl,
            'updateTime' => currentTime(),
            'updateBy' => $userId
        ];

        $updateRow = $newsModel->edit($newsId, $data);
        if ($updateRow > 0) {
            $arr['updateRow'] = $updateRow;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_UPDATE'], '编辑失败');
            exit;
        }

    }

    public function del()
    {
        $params = Request::instance()->request();
        $newsId = Check::checkInteger($params['newsId'] ?? '');

        $newsModel = new NewsModel();
        $data = [
            'id' => $newsId,
            'isDelete' => 1
        ];
        $delRow = $newsModel->isUpdate(true)->save($data);
        if ($delRow > 0) {
            $arr['delRow'] = $delRow;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_DELETE'], '删除失败');
            exit;
        }

    }

    public function getByPage()
    {
        $params = Request::instance()->request();
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);

        $newsModel = new NewsModel();
        $page = $newsModel->getByPage($pageIndex, $pageSize);
        $data['page'] = $page;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    public function getDetail()
    {
        $params = Request::instance()->request();
        $newsId = Check::checkInteger($params['newsId'] ?? '');

        $newsModel = new NewsModel();
        $detail = $newsModel->getDetail($newsId);
        $data['detail'] = $detail;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    public function getNewsPageByCateId()
    {
        $params = Request::instance()->request();
        $categoryId = Check::checkInteger($params['categoryId'] ?? '');
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);

        $newsModel = new NewsModel();
        $page = $newsModel->getNewsByCateIdPage($categoryId, $pageIndex, $pageSize);

        $data['page'] = $page;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }


}