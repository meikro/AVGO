<?php
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-11
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Logout extends Ctcms_Controller {
	
	public function __construct(){
		parent::__construct();
		//加载会员模型
		$this->load->model('user');
	}

	//退出登陆
    public function index() {
		unset(
             $_SESSION['user_id'],
             $_SESSION['user_name'],
             $_SESSION['user_login']
        );
		$this->session->set_tempdata('user_id', '');
		$this->session->set_tempdata('user_name', '');
		$this->session->set_tempdata('user_login', '');
	    setcookie('ctcms_vip','',86400-time(),'/');
        setcookie('ctcms_uid','',86400-time(),'/');
        setcookie('ctcms_log','',86400-time(),'/');
		if(!empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'],'user') === FALSE){
             header("location:".$_SERVER['HTTP_REFERER']);
		}else{
             header("location:".links('user','login'));
		}
	}
}
