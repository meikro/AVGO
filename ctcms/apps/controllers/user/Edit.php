<?php
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-11
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Edit extends Ctcms_Controller {
	
	public function __construct(){
		parent::__construct();
		//加载会员模型
		$this->load->model('user');
        //判断登陆
		$this->user->login();
        //当前模版
		$this->load->get_templates('user');
	}

	//修改资料
    public function index() {
		$data['ctcms_title'] = '修改资料 - '.Web_Name;
		$data['ctcms_formurl'] = links('user','edit/save');
		$data['ctcms_picsave'] = links('user','edit/logo_save');
		$row = $this->csdb->get_row_arr('user','*',array('id'=>$_SESSION['user_id']));
		//获取模板
		$str=load_file('edit.html','user');
		//全局解析
		$str=$this->parser->parse_string($str,$data,true,false);
		//当前会员数据
		$str=$this->parser->ctcms_tpl('user',$str,$str,$row);
		//IF判断解析
		$str=$this->parser->labelif($str);
		echo $str;
	}

	//修改密码
    public function pass() {
		$data['ctcms_title'] = '修改密码 - '.Web_Name;
		 $data['ctcms_formurl'] = links('user','edit/pass_save');
		$row = $this->csdb->get_row_arr('user','*',array('id'=>$_SESSION['user_id']));
		//获取模板
		$str=load_file('edit-pass.html','user');
		//全局解析
		$str=$this->parser->parse_string($str,$data,true,false);
		//当前会员数据
		$str=$this->parser->ctcms_tpl('user',$str,$str,$row);
		//IF判断解析
		$str=$this->parser->labelif($str);
		echo $str;
	}

    //修改资料
    public function save() {
         $email = $this->input->post('email',true);
         $tel = $this->input->post('tel',true);
         $qq = $this->input->post('qq',true);
         $nichen = $this->input->post('nichen',true);
         $sex = (int)$this->input->post('sex',true);

		 if(empty($email)) msg_url('邮箱不能为空~!','javascript:history.back();');
		 if(!is_email($email)) msg_url('邮箱格式不正确~!','javascript:history.back();');
		 if(!empty($tel) && !is_tel($tel)) msg_url('手机格式不正确~!','javascript:history.back();');
		 if(!empty($qq) && !is_qq($qq)) msg_url('QQ格式不正确~!','javascript:history.back();');

		 //判断邮箱存在
		 $row = $this->csdb->get_row('user','id',array('email'=>$email));
		 if($row && $row->id!=$_SESSION['user_id']) msg_url('该邮箱已经存在，请更换~!','javascript:history.back();');
		 //判断手机存在
		 if(!empty($tel)){
		     $row = $this->csdb->get_row('user','id',array('tel'=>$tel));
		     if($row && $row->id!=$_SESSION['user_id']) msg_url('该手机号已经存在，请更换~!','javascript:history.back();');
		 }

		 $edit['email'] = $email;
		 $edit['tel'] = $tel;
		 $edit['qq'] = $qq;
		 $edit['nichen'] = $nichen;
		 $edit['sex'] = $sex;
		 $this->csdb->get_update('user',$_SESSION['user_id'],$edit);

		 //直接跳转
         msg_url('资料修改成功~!',links('user'),'ok');
	}

    //修改密码
    public function pass_save() {
         $pass = $this->input->post('pass',true);
         $pass1 = $this->input->post('pass1',true);
         $pass2 = $this->input->post('pass2',true);
		 if(empty($pass) || empty($pass1)) msg_url('原密码、新密码不能为空~!','javascript:history.back();');
		 if($pass1!=$pass2) msg_url('两次密码不一致~!','javascript:history.back();');

		 $row = $this->csdb->get_row('user','id,name,pass',array('id'=>$_SESSION['user_id']));
		 if($row->pass!=md5($pass)) msg_url('原密码错误~!','javascript:history.back();');

		 $edit['pass'] = md5($pass1);
		 $this->csdb->get_update('user',$row->id,$edit);
		 $_SESSION['user_login'] = md5($row->id.$row->name.$edit['pass'].CT_Encryption_Key);

		 //直接跳转
         msg_url('密码修改成功~!',links('user'),'ok');
	}

	//修改头像
    public function logo() {
		$data['ctcms_title'] = '修改头像 - '.Web_Name;
		$row = $this->csdb->get_row_arr('user','*',array('id'=>$_SESSION['user_id']));
		//获取模板
		$str=load_file('edit-logo.html','user');
		//全局解析
		$str=$this->parser->parse_string($str,$data,true,false);
		//当前会员数据
		$str=$this->parser->ctcms_tpl('user',$str,$str,$row);
		//IF判断解析
		$str=$this->parser->labelif($str);
		echo $str;
	}

    //上传头像
	public function logo_save()
	{
	    $uid = $_SESSION['user_id'];
	    if(!isset($_FILES['user_logo']['tmp_name'])){
        	$tempFile = file_get_contents("php://input");
        	$up = 0;
	    }else{
        	$tempFile = $_FILES['user_logo']['tmp_name'];
        	$up = 1;
	    }
		$picname  = $uid.".jpg";
	    $picdirs  = date('Ym')."/".date('d')."/".$uid.".jpg";
		$filename = FCPATH.'attachment/logo/'.$picdirs; 
		$filepath = Web_Path.'attachment/logo/'.$picdirs; 
	    if(!empty($tempFile) && $uid>0) {
			//创建当前文件件
			$dir = FCPATH."attachment/logo/".date('Ym')."/".date('d');
			mkdirss($dir);
			if($up == 0){
				if($handle=fopen($filename,"w+")) {   
					if(!fwrite($handle,$tempFile) == FALSE){   
						fclose($handle);
					}
				} 
			}else{
				move_uploaded_file($tempFile, $filename);
			}
			list($width, $height, $type, $attr) = getimagesize($filename);
			if ( intval($width) < 10 || intval($height) < 10 || $type == 4 ) {
				@unlink($filename);
				if($up == 0){
			    	exit('UploadPicError');
			    }else{
			    	echo json_encode(array('code'=>0,'msg'=>'图片格式不正确!'));
			    	exit;
			    }
			}
			//判断远程附件
			if(Ftp_Is>0){
				$this->load->library('ftpup');
				$res = $this->ftpup->up($filename,$picname);
				if($res) $filepath = $res;
			}
			//写入数据库
			$this->db->query("update ".CT_SqlPrefix."user set pic='".$filepath."' where id=".$uid."");
			if($up == 0){
				exit('UploadPicSucceed');
			}else{
			    echo json_encode(array('code'=>1,'msg'=>'图片上传完成!'));
			    exit;
			}
		} else {
			if($up == 0){
		    	exit('UploadPicError');
		    }else{
		    	echo json_encode(array('code'=>0,'msg'=>'图片上传失败!'));
		    	exit;
		    }
		}
	}
}
