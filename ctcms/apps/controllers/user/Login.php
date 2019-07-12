<?php
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-11
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends Ctcms_Controller {
	
	public function __construct(){
		parent::__construct();
		//加载会员模型
		$this->load->model('user');
	}

	//会员登陆
    public function index() {
		 $_SESSION['lailu_url'] = $_SERVER['HTTP_REFERER'];
		 $data['ctcms_title'] = '会员登陆 - '.Web_Name;
		 $data['ctcms_formurl'] = links('user','login/save');
		 //获取模板
		 $str=load_file('login.html','user');
		 //全局解析
		 $this->parser->parse_string($str,$data);
	}

    //判断登陆
    public function save() {
         $name = safe_replace($this->input->post('name',true));
         $pass = $this->input->post('pass',true);
		 if(empty($name) || empty($pass)) msg_url('账号、密码不能为空~!','javascript:history.back();');

		 $row = $this->csdb->get_row('user','*',array('name'=>$name));
		 if(!$row) $row = $this->csdb->get_row('user','*',array('email'=>$name));
		 if(!$row) $row = $this->csdb->get_row('user','*',array('tel'=>$name));
		 if(!$row || $row->pass!=md5($pass)) msg_url('账号、密码错误~!','javascript:history.back();');

		 //记录登陆IP、时间、次数
		 $edit['logip'] = getip();
		 $edit['lognum'] = $row->lognum+1;
		 $edit['logtime'] = time();
		 //每天登录送金币
		 if(date('Y-m-d',$row->logtime) != date('Y-m-d')){
		 	$edit['cion'] = $row->cion+User_Log_Cion;
		 }
         //判断VIP
		 if($row->vip==1 && $row->viptime<time()){
             $edit['vip'] = 0;
			 $row->vip = 0;
		 }
		 $this->csdb->get_update('user',$row->id,$edit);

         //登陆
		 $this->session->set_tempdata('user_id', $row->id, 86400);
		 $this->session->set_tempdata('user_name', $row->name, 86400);
		 $this->session->set_tempdata('user_login', md5($row->id.$row->name.$row->pass.CT_Encryption_Key), 86400);
		 
		 //保存VIP COOKIE
		 if($row->vip>0){
              setcookie('ctcms_vip','ok',86400+time(),'/');
		 }else{
              setcookie('ctcms_vip','no',time()-86400,'/');
		 }
		 //记住登陆
         setcookie('ctcms_uid',$row->id,86400*10+time(),'/');
         setcookie('ctcms_log',md5($row->id.$row->name.$row->pass.CT_Encryption_Key),86400*10+time(),'/');
		 
				
		 //直接跳转
		 if(!empty($_SESSION['lailu_url']) && strpos($_SESSION['lailu_url'],'user') === FALSE){
             header("location:".$_SESSION['lailu_url']);
		 }else{
             header("location:".links('user','index'));
		 }
	}
}
