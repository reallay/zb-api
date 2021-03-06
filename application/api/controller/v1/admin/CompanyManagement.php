<?php

namespace app\api\controller\v1\admin;

use app\api\model\CompanyManagementModel;
use think\Request;
use Util\Check;
use Util\Util;

class CompanyManagement extends AdminBase
{
    /**
     * 创建公司
     */
    public function add()
    {
        $params = Request::instance()->param();
        $industryId = Check::check($params['industryId'] ?? ''); //行业分类
        $name = Check::check($params['name'] ?? '');
        $province = Check::check($params['province'] ?? ''); //省份
        $city = Check::check($params['city'] ?? ''); // 城市
        $area = Check::check($params['area'] ?? ''); // 区/县
        $address = Check::check($params['address'] ?? '');
        $contact = Check::check($params['contact'] ?? ''); // 联系人
        $phone = Check::check($params['phone'] ?? '', 0, 11);
        $wxNumber = Check::check($params['wxNumber'] ?? ''); //微信号
        $leader = Check::check($params['leader'] ?? ''); //负责人
        $nature = Check::check($params['nature'] ?? ''); // 公司性质
        $profile = Check::check($params['profile'] ?? ''); //公司简介
        $profile = stripslashes($profile);
        $remark = Check::check($params['remark'] ?? '');
        $dataBank = Check::check($params['dataBank'] ?? ''); //资料库
        $userId = $GLOBALS['userId'];

        $companyManagementModel = new CompanyManagementModel();

        if ($name == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }

        if ($companyManagementModel->checkName($name)) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '名字重复');
            exit;
        }

        if ($dataBank != '') {
            $dataBankArr = explode(',', $dataBank);
            $dataBankArrJson = json_encode($dataBankArr, JSON_UNESCAPED_UNICODE);
        } else {
            $dataBankArrJson = json_encode(array());
        }

        $data = [
            'industryId' => $industryId,
            'name' => $name,
            'province' => $province,
            'city' => $city,
            'area' => $area,
            'address' => $address,
            'contact' => $contact,
            'phone' => $phone,
            'wxNumber' => $wxNumber,
            'leader' => $leader,
            'nature' => $nature,
            'profile' => htmlspecialchars_decode($profile),
            'remark' => $remark,
            'dataBank' => $dataBankArrJson,
            'createTime' => currentTime(),
            'createBy' => $userId,
            'updateTime' => currentTime(),
            'updateBy' => $userId
        ];

        $insertRow = $companyManagementModel->save($data);
        if ($insertRow > 0) {
            $arr['id'] = $companyManagementModel->id;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_INSERT'], '添加失败');
            exit;
        }
    }

    /**
     * 编辑公司
     */
    public function edit()
    {
        $params = Request::instance()->param();
        $companyId = Check::checkInteger($params['companyId'] ?? '');
        $industryId = Check::check($params['industryId'] ?? ''); // 行业分类
        $name = Check::check($params['name'] ?? '');
        $province = Check::check($params['province'] ?? ''); //省份
        $city = Check::check($params['city'] ?? ''); // 城市
        $area = Check::check($params['area'] ?? ''); // 区/县
        $address = Check::check($params['address'] ?? '');
        $contact = Check::check($params['contact'] ?? ''); // 联系人
        $phone = Check::check($params['phone'] ?? '', 0, 11);
        $wxNumber = Check::check($params['wxNumber'] ?? ''); //微信号
        $leader = Check::check($params['leader'] ?? ''); //负责人
        $nature = Check::check($params['nature'] ?? ''); // 公司性质
        $profile = Check::check($params['profile'] ?? ''); //公司简介
        $profile = stripslashes($profile);
        $remark = Check::check($params['remark'] ?? '');
        $dataBank = Check::check($params['dataBank'] ?? ''); //资料库
        $userId = $GLOBALS['userId'];

        $companyManagementModel = new CompanyManagementModel();
        if ($name == '') {
            Util::printResult($GLOBALS['ERROR_PARAM_MISSING'], '缺少参数');
            exit;
        }

        $detail = $companyManagementModel->getDetail($companyId);

        if ($detail['name'] != $name && $companyManagementModel->checkName($name)) {
            Util::printResult($GLOBALS['ERROR_PARAM_WRONG'], '名字重复');
            exit;
        }

        if ($dataBank != '') {
            $dataBankArr = explode(',', $dataBank);
            $dataBankArrJson = json_encode($dataBankArr, JSON_UNESCAPED_UNICODE);
        } else {
            $dataBankArrJson = json_encode(array());
        }
        $data = [
            'industryId' => $industryId,
            'name' => $name,
            'province' => $province,
            'city' => $city,
            'area' => $area,
            'address' => $address,
            'contact' => $contact,
            'phone' => $phone,
            'wxNumber' => $wxNumber,
            'leader' => $leader,
            'nature' => $nature,
            'profile' => htmlspecialchars_decode($profile),
            'remark' => $remark,
            'dataBank' => $dataBankArrJson,
            'updateTime' => currentTime(),
            'updateBy' => $userId
        ];

        $updateRow = $companyManagementModel->edit($companyId, $data);
        if ($updateRow > 0) {
            $arr['updateRow'] = $updateRow;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_UPDATE'], '编辑失败');
            exit;
        }
    }

    /**
     * 删除公司
     */
    public function del()
    {
        $params = Request::instance()->param();
        $companyId = Check::checkInteger($params['companyId'] ?? '');
        $companyManagementModel = new CompanyManagementModel();
        $data = [
            'id' => $companyId,
            'isDelete' => 1
        ];
        $delRow = $companyManagementModel->isUpdate(true)->save($data);
        if ($delRow > 0) {
            $arr['delRow'] = $delRow;
            Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
            exit;
        } else {
            Util::printResult($GLOBALS['ERROR_SQL_DELETE'], '删除失败');
            exit;
        }
    }

    /**
     * 获取公司详情
     */
    public function getDetail()
    {
        $params = Request::instance()->param();
        $companyId = Check::checkInteger($params['companyId'] ?? '');

        $companyManagementModel = new CompanyManagementModel();
        $detail = $companyManagementModel->getDetail($companyId);

        if ($detail != null) {
            $detail['dataBank'] = json_decode($detail['dataBank'], true);
        }

        $arr['detail'] = $detail;
        Util::printResult($GLOBALS['ERROR_SUCCESS'], $arr);
    }

    /**
     * 分页获取公司
     */
    public function getByPage()
    {
        $params = Request::instance()->param();
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);

        $companyManagementModel = new CompanyManagementModel();
        $page = $companyManagementModel->getByPage($pageIndex, $pageSize);

        $pageData = $page->toArray();
        $pageArr = $pageData['data'];

        foreach ($pageArr as $k => $v) {
            $pageArr[$k]['dataBank'] = json_decode($v['dataBank'], true);
        }

        $pageData['data'] = $pageArr;
        $data['page'] = $pageData;

        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    /**
     * 获取所有公司
     */
    public function getAll()
    {
        $companyManagementModel = new CompanyManagementModel();
        $result = $companyManagementModel->getAll();

        foreach ($result as $k => $v) {
            $result[$k]['dataBank'] = json_decode($v['dataBank'], true);
        }

        $data['list'] = $result;

        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    /**
     * 分页筛选公司
     */
    public function filterCompanyPage()
    {
        $params = Request::instance()->param();
        $areaInfo = Check::check($params['areaInfo'] ?? '');
        $industryInfo = Check::check($params['industryInfo'] ?? '');
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);

        $companyModel = new CompanyManagementModel();
        list($r1,$r2) = $companyModel->filterCompanyPage($areaInfo,$industryInfo, $pageIndex, $pageSize);

        foreach ($r1 as $k => $v) {
            $r1[$k]['dataBank'] = json_decode($v['dataBank'], true);
        }

        $data['total'] = $r2;
        $data['pageIndex'] = $pageIndex;
        $data['pageSize'] = $pageSize;
        $data['page'] = $r1;

        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

    /**
     * 分页筛选公司和行业分类
     */
    public function filterCompanyByIndustryInfoPage()
    {
        $params = Request::instance()->param();
        $info = Check::check($params['info'] ?? '');
        $pageIndex = Check::checkInteger($params['pageIndex'] ?? 1);
        $pageSize = Check::checkInteger($params['pageSize'] ?? 10);

        $companyModel = new CompanyManagementModel();

        $page = $companyModel->filterCompanyByIndustryPage($info, $pageIndex, $pageSize);

        $pageData = $page->toArray();
        $pageArr = $pageData['data'];

        foreach ($pageArr as $k => $v) {
            $pageArr[$k]['dataBank'] = json_decode($v['dataBank'], true);
        }

        $pageData['data'] = $pageArr;
        $data['page'] = $pageData;

        Util::printResult($GLOBALS['ERROR_SUCCESS'], $data);
    }

}