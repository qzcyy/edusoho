<?php

namespace Topxia\Service\EduCloud\Impl;

use Topxia\Service\CloudPlatform\CloudAPIFactory;
use Topxia\Service\EduCloud\ConsultService;
use Topxia\Service\Common\BaseService;

class MicroyanConsultServiceImpl extends BaseService implements ConsultService
{
    public function connectCloud()
    {
        $api = CloudAPIFactory::create('root');

        return $api;
    }

    public function getAccount()
    {
        $api = $this->connectCloud();
        $account = $api->post("/robot/login_url");

        return $account;
    }

    public function getJsResource()
    {
        $api = $this->connectCloud();
        $jsResource  = $api->post("/robot/install");

        return $jsResource;
    }

    public function buildCloudConsult($account, $jsResource)
    {
        $cloudConsult = $this->getSettingService()->get('cloud_consult', array());

        $defaultSetting = array(
            'cloud_consult_setting_enabled' => 0,
            'cloud_consult_is_buy' => 0,
            'cloud_consult_login_url' => '',
            'cloud_consult_js' => ''
        );

        $cloudConsult = array_merge($defaultSetting, $cloudConsult);

        if ((isset($account['code']) && $account['code']== '10000') || (isset($jsResource['code']) && $jsResource['code']== '10000')) {
            $cloudConsult['cloud_consult_enabled'] = 0;
        } else if ((isset($account['code']) && $account['code']== '10001') || (isset($jsResource['code']) && $jsResource['code']== '10001')) {
            $cloudConsult['cloud_consult_enabled'] = 0;
            $cloudConsult['message'] = '账号已过期,请联系客服人员:4008041114！';
        } else if(isset($account['error']) || isset($jsResource['error'])) {
            $cloudConsult['cloud_consult_enabled'] = 0;
        } else {
            $cloudConsult['cloud_consult_enabled'] = 1;
            $cloudConsult['cloud_consult_landing_url'] = $account;
            $cloudConsult['cloud_consult_js'] = $jsResource;
        }

        return $cloudConsult;
    }

    public function getSettingService()
    {
        $this->createService('System.SettingService');
    }
}