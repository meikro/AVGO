<?php
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-11
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Reg extends Ctcms_Controller {
	public function __construct(){
		parent::__construct();
		//加载会员模型
		$this->load->model('user');
	}

	//会员注册
    public function index($uid=0) {
    	$uid = (int)$uid;
    	if($uid==0) $uid = (int)$this->input->get('id');
    	if($uid > 0) $_SESSION['user_regid'] = $uid;
		$data['ctcms_title'] = '会员注册 - '.Web_Name;
		$data['ctcms_formurl'] = links('user','reg/save');
		$data['ctcms_codeurl'] = links('code');
		//获取模板
		$str=load_file('reg.html','user');
		//全局解析
		$this->parser->parse_string($str,$data);
	}

    //注册入库
    public function save() {
		$name = safe_replace($this->input->post('name',true));
		$pass = $this->input->post('pass',true);
		$email = safe_replace($this->input->post('email',true));
		$code = $this->input->post('code',true);
		if(empty($name) || empty($pass) || empty($email)) msg_url('账号、密码、邮箱不能为空~!','javascript:history.back();');
		if(!is_username($name)) msg_url('账号格式不正确~!','javascript:history.back();');
		if(!is_userpass($pass)) msg_url('请输入6-20位的密码~!','javascript:history.back();');
		if(!is_email($email)) msg_url('邮箱格式不正确~!','javascript:history.back();');
		if(strtolower($code)!=$_SESSION['codes']) msg_url('验证码不正确~!','javascript:history.back();');

		$row = $this->csdb->get_row('user','id',array('name'=>$name));
		if($row) msg_url('账号已被注册~!','javascript:history.back();');
		$row = $this->csdb->get_row('user','id',array('email'=>$email));
		if($row) msg_url('邮箱已被注册~!','javascript:history.back();');

		$ip = getip();
		$uid = (int)$_SESSION['user_regid'];
		if($uid > 0){
			$rowp = $this->csdb->get_row('user','id',array('logip'=>$ip));
			if(!$rowp) $add['uid'] = $uid;
		}
		$add['name'] = $name;
		$add['pass'] = md5($pass);
		$add['email'] = $email;
		$add['cion'] = User_Reg_Cion+User_Log_Cion;
		$add['regtime'] = time();
		$add['logtime'] = time();
		$add['lognum'] = 1;
		$add['logip'] = $ip;
		//入库
		$res = $this->csdb->get_insert('user',$add);
		if($res){
			//登陆
			$_SESSION['user_id'] = $res;
			$_SESSION['user_name'] = $name;
			$_SESSION['user_login'] = md5($res.$name.md5($pass).CT_Encryption_Key);
			//直接跳转
			header("location:".links('user','index'));
		}else{
			msg_url('注册失败，稍后再试~!','javascript:history.back();');
		}
	}
}