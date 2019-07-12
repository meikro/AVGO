<?php
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-11
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Pass extends Ctcms_Controller {
	public function __construct(){
		parent::__construct();
		//加载会员模型
		$this->load->model('user');
	}

	//密码找回
    public function index() {
		$data['ctcms_title'] = '密码找回 - '.Web_Name;
		$data['ctcms_formurl'] = links('user','pass/save');
		$data['ctcms_codeurl'] = links('user','pass/code_save');
		//获取模板
		$str=load_file('pass.html','user');
		//全局解析
		$this->parser->parse_string($str,$data);
	}

    //修改
    public function save() {
		$email = $this->input->post('email',true);
		$code = $this->input->post('code',true);
		$pass = $this->input->post('pass',true);
		if(empty($email)) msg_url('邮箱不能为空~!','javascript:history.back();');
		if(empty($code)) msg_url('验证码不能为空~!','javascript:history.back();');
		if($code != $_SESSION['pass_code']) msg_url('验证码不正确~!','javascript:history.back();');
		if(empty($pass)) msg_url('新密码不能为空~!','javascript:history.back();');
		$edit['pass'] = md5($pass);
		$res = $this->csdb->get_update('user',$_SESSION['pass_uid'],$edit);
		if($res){
			unset(
			    $_SESSION['pass_id'],
			    $_SESSION['pass_code']
			);
			msg_url('密码修改成功~!',links('user','login'),'ok');
		}else{
			msg_url('密码修改失败，稍后再试~!','javascript:history.back();');
		}
	}

    //发送验证码
    public function code_save() {
        $email = safe_replace($this->input->post('email',true));
		if(empty($email)){
			echo json_encode(array('code'=>0,'msg'=>'邮箱不能为空~!'));
			exit;
		}
		$row = $this->csdb->get_row('user','id',array('email'=>$email));
		if(!$row){
			echo json_encode(array('code'=>0,'msg'=>'邮箱不存在~!'));
			exit;
		}
		$config['crlf']          = "\r\n";
		$config['newline']       = "\r\n";
		$config['charset']       = 'utf-8';
		$config['protocol']      = 'smtp';
		$config['smtp_timeout']  = 5;
		$config['wordwrap']      = TRUE;
		$config['mailtype']      = 'html';
		$config['smtp_host']	 = CT_Smtphost; 
		$config['smtp_port']     = CT_Smtpport;
		$config['smtp_user']     = CT_Smtpuser;
		$config['smtp_pass']     = CT_Smtppass;
		$this->load->library('email', $config);

		$code = rand(111111111,999999999);
		$title = Web_Name.'验证码';
		$neir = '尊敬的会员，这是来自'.Web_Name.'的密码重置邮件。您的验证码：<br>'.$code.'<br>十分钟内有效，如非本人操作，请忽略。<br>'.Web_Name.' <br>'.date('Y-m-d H:i:s');
		$this->email->from(CT_Smtpmail, CT_Smtpname);
		$this->email->to(trim($email)); 
		$this->email->subject($title);
		$this->email->message($neir); 
		if($this->email->send()){
			$_SESSION['pass_id'] = $row->id;
			$_SESSION['pass_code'] = $code;
		 	echo json_encode(array('code'=>1,'msg'=>'密码已发送至您的邮箱，请登陆邮箱查看~!'));
		}else{
			echo json_encode(array('code'=>0,'msg'=>'邮件发送失败，稍后再试~!'));
		}
	}
}