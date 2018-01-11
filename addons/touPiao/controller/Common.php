<?php

namespace addons\touPiao\controller;

use addons\touPiao\model\VoteBaoming;
use app\common\controller\Addon;
use think\Db;

class Common extends Addon
{
    public $onlyWexinOpen = true;
    public $isWexinLogin = false;
    public $scope = 'snsapi_userinfo';//snsapi_base||snsapi_userinfo
    public $info;
    public $openid;

    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $bmModel = new VoteBaoming();
        $view = Db::name('vote_view')->where('mpid', '=', $this->mid)->find();
        $baoming = $bmModel->where(['mpid'=>$this->mid,'status'=>'1'])->count('bm_id');
        $vote_total = $bmModel->where(['mpid'=>$this->mid])->sum('vote_total');
        $member=getMember();
        $openid=getOrSetOpenid();
        $this->openid = isset($member['openid'])?$member['openid']:$openid;
        $this->info = $info = getAddonInfo();
        if(isset($info['mp_config'])){
            if(isset($info['mp_config']['login_type'])&& $info['mp_config']['login_type'] ==1){
                $this->wexinLogin();
            }
        }
        $this->assign('abcccc',[1,3]);
        $this->assign('mpInfo',getMpInfo($this->mid));
        $this->assign('records', ['view' => $view['view'], 'baoming' => $baoming, 'vote_total' => $vote_total]);
        $this->assign('info', $info['mp_config']);
        $this->assign('end_time', $this->info['mp_config']['end_time']);

    }

}