<?php
/** * 
@Ctcms open source management system * 
@copyright 2008-2016 chshcms.com. All rights reserved. * 
@Author:Cheng Kai Jie * 
@Dtime:2015-12-11 
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class User extends Ctcms_Controller {
	
	function __construct(){	    
		parent::__construct();        
	}  

	//获取会员资料信息
	public function index(){
		$uid = (int)$this->input->get_post('uid',true);
		$token = $this->input->get_post('token',true);
		$user = $this->islog($uid,$token,1);
		if(!$user){
			$arr['code'] = 1;
			$arr['msg'] = '未登录';
		}else{
			if($user['vip']==1 && $user['viptime']<time()){
				$this->csdb->get_update('user',$user['id'],array('vip'=>0));
				$user['vip'] = 0;
			}
			$arr['code'] = 0;
			$user['pic'] = getpic($user['pic']);
			if(substr($user['pic'],0,12)=='/attachment/') $user['pic'] = 'http://'.Web_Url.$user['pic'];
			unset($user['rmb'],$user['regtime'],$user['logtime'],$user['lognum'],$user['logip'],$user['qdtime'],$user['qdday'],$user['qdnum'],$user['uid'],$user['qqid'],$user['wxid']);
			$user['viptime'] = date('Y-m-d H:i:s',$user['viptime']);
			if($user['sex'] == 1){
				$user['sex'] = '男';
			}elseif($user['sex'] == 2){
				$user['sex'] = '女';
			}else{
				$user['sex'] = '保密';
			}
			$arr['data']['user'] = $user;
		}
		echo json_encode($arr);
	}

	//注册
	public function reg()	{
		$name = $this->input->get_post('name',true);
		$pass = $this->input->get_post('pass',true);
		$email = $this->input->get_post('email',true);
		$arr['code'] = 1;
		$data = array();
		if(empty($name) || empty($pass) || empty($email)){
			$arr['msg'] = '资料不完整';
		}elseif(!is_username($name)){
			$arr['msg'] = '账号格式不正确';
		}elseif(!is_username($pass)){
			$arr['msg'] = '密码格式不正确';
		}elseif(!is_email($email)){
			$arr['msg'] = '邮箱格式不正确';
		}else{
			$row = $this->csdb->get_row('user','id',array('name'=>$name));
			if($row){
				$arr['msg'] = '账号已被注册';
			}else{
				$row = $this->csdb->get_row('user','id',array('email'=>$email));
				if($row){
					$arr['msg'] = '邮箱已被注册';
				}else{
					$add['name'] = $name;
					$add['pass'] = md5($pass);
					$add['email'] = $email;
					$add['cion'] = User_Reg_Cion;
					$add['regtime'] = time();
					$add['logtime'] = time();
					$add['lognum'] = 1;
					$add['logip'] = getip();
					//入库
					$res = $this->csdb->get_insert('user',$add);
					if($res){
						$arr['code'] = 0;
						$data['uid'] = $res;
						$data['token'] = md5($res.$name.md5($pass).CT_Encryption_Key);
						$arr['data'] = $data;
					}else{
						$arr['msg'] = '注册失败，稍后再试';
					}
				}
			}
		}
		echo json_encode($arr);
	}

	//登陆
	public function log(){
		$name = $this->input->get_post('name',true);
		$pass = $this->input->get_post('pass',true);
		$arr['code'] = 1;
		if(empty($name)){
			$arr['msg'] = '账号不能为空';
		}elseif(empty($pass)){
			$arr['msg'] = '密码不能为空';
		}else{
			$row = $this->csdb->get_row('user','*',array('name'=>$name));
			if(!$row) $row = $this->csdb->get_row('user','*',array('email'=>$name));
			if(!$row) $row = $this->csdb->get_row('user','*',array('tel'=>$name));
			if(!$row || $row->pass!=md5($pass)){
				$arr['msg'] = '账号、密码错误';
			}else{
				//记录登陆IP、时间、次数
				$edit['logip'] = getip();
				$edit['lognum'] = $row->lognum+1;
				$edit['logtime'] = time();
				//判断VIP
				if($row->vip==1 && $row->viptime<time()){
					$edit['vip'] = 0;
				}
				$this->csdb->get_update('user',$row->id,$edit);

				$arr['code'] = 0;
				$arr['data']['uid'] = $row->id;
				$arr['data']['token'] = md5($row->id.$row->name.$row->pass.CT_Encryption_Key);
			}
		}
		echo json_encode($arr);
	}

	//修改资料
	public function edit(){
		$uid = (int)$this->input->get_post('uid',true);
		$token = $this->input->get_post('token',true);
		$user = $this->islog($uid,$token);
		if(!$user){
			echo json_encode(array('code'=>1,'msg'=>'未登录'));exit;
		}else{
			$email = $this->input->get_post('email',true);
			$tel = $this->input->get_post('tel',true);
			$qq = $this->input->get_post('qq',true);
			$nichen = $this->input->get_post('nichen',true);
			$sex = (int)$this->input->get_post('sex',true);

			if(!empty($email) &&  !is_email($email)){
				echo json_encode(array('code'=>1,'msg'=>'邮箱不能为空'));exit;
			}
			if(!empty($tel) && !is_tel($tel)){
				echo json_encode(array('code'=>1,'msg'=>'手机格式不正确'));exit;
			}
			if(!empty($qq) && !is_qq($qq)) {
				echo json_encode(array('code'=>1,'msg'=>'QQ格式不正确'));exit;
			}

			//判断邮箱存在
			if(!empty($email)){
				$row = $this->csdb->get_row('user','id',array('email'=>$email));
				if($row && $row->id!=$uid){
					echo json_encode(array('code'=>1,'msg'=>'该邮箱已经存在，请更换'));exit;
				}
				$edit['email'] = $email;
			}
			//判断手机存在
			if(!empty($tel)){
				$row = $this->csdb->get_row('user','id',array('tel'=>$tel));
				if($row && $row->id!=$uid){
					echo json_encode(array('code'=>1,'msg'=>'该手机号已经存在，请更换'));exit;
				}
				$edit['tel'] = $tel;
			}
			if(!empty($qq)) $edit['qq'] = $qq;
			if(!empty($nichen)) $edit['nichen'] = $nichen;
			if($sex > 0) $edit['sex'] = $sex;
			$this->csdb->get_update('user',$uid,$edit);

			$arr['code'] = 0;
			$arr['msg'] = '修改成功~';
		}
		echo json_encode($arr);
	}

    //修改密码
    public function pass() {
		$uid = (int)$this->input->get_post('uid',true);
		$token = $this->input->get_post('token',true);
		$user = $this->islog($uid,$token,1);
		if(!$user){
			echo json_encode(array('code'=>1,'msg'=>'未登录'));exit;
		}
		$pass = $this->input->get_post('pass',true);
		if(empty($pass)){
			echo json_encode(array('code'=>1,'msg'=>'新密码不能为空'));exit;
		}
		$edit['pass'] = md5($pass);
		$this->csdb->get_update('user',$uid,$edit);
		$token = md5($user['id'].$user['name'].$edit['pass'].CT_Encryption_Key);
		echo json_encode(array('code'=>0,'token'=>$token,'msg'=>'密码修改成功'));exit;
	}

    //修改头像
    public function logo() {
		$uid = (int)$this->input->get_post('uid',true);
		$token = $this->input->get_post('token',true);
		$user = $this->islog($uid,$token,1);
		if(!$user){
			echo json_encode(array('code'=>1,'msg'=>'未登录'));exit;
		}
		$tempFile = $_FILES['file']['tmp_name'];
		if(empty($tempFile)) {
			echo json_encode(array('code'=>1,'msg'=>'未上传图片'));exit;
		}
		$file_name = $_FILES['file']['name'];
		//上传目录
		$path = FCPATH.'attachment/logo/'.date('Ym').'/'.date('d').'/';
		if (!is_dir($path)) {
            mkdirss($path);
        }
		//后缀
        $file_ext = strtolower(trim(substr(strrchr($file_name, '.'), 1)));

        //检查扩展名
        if($file_ext=='jpg' || $file_ext=='png' || $file_ext=='gif' || $file_ext=='bmp' || $file_ext=='jpge'){
			list($width, $height, $type, $attr) = getimagesize($tempFile);
			if ( intval($width) < 10 || intval($height) < 10 || $type == 4 ) {
				echo json_encode(array('code'=>1,'msg'=>'图片格式不正确'));exit;
			}
		}else{
			echo json_encode(array('code'=>1,'msg'=>'请上传图片文件'));exit;
		}

		//新文件名
		$file_name = time().rand(111111,999999). '.' . $file_ext;
		$file_path = $path.$file_name;
		$filepath = str_replace(FCPATH, Web_Path, $file_path);
		$pic = 'http://'.Web_Url.$filepath;
		if(move_uploaded_file($tempFile, $file_path) !== false) { //上传成功
			//判断远程附件
			if(Ftp_Is>0){
				$this->load->library('ftpup');
				$res = $this->ftpup->up($file_path,$file_name);
				if($res){
					$filepath = $res;
					$pic = getpic($res);
				}
			}
            //写入数据库
            $this->db->query("update ".CT_SqlPrefix."user set pic='".$filepath."' where id=".$uid."");
			echo json_encode(array('code'=>0,'data'=>array('pic'=>$pic,'msg'=>'上传成功')));exit;
		}else{ //上传失败
			echo json_encode(array('code'=>1,'msg'=>'上传失败'));exit;
		}
	}

	//判断是否登陆
	private function islog($uid,$token,$sign=0){
		if($uid==0 || empty($token)) return 0;
		$row = $this->csdb->get_row_arr('user','*',array('id'=>$uid));
		if(!$row || md5($row['id'].$row['name'].$row['pass'].CT_Encryption_Key) != $token){
			return 0;
		}else{
			if($sign==0){
				return 1;
			}else{
				unset($row['pass']);
				return $row;
			}
		}
	}
}