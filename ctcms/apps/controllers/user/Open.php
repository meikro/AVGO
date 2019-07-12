<?php 
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-11
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Open extends Ctcms_Controller {

	function __construct(){
	    parent::__construct();
		//加载会员模型
		$this->load->model('user');
        //当前模版
		$this->load->get_templates('user');
		$this->load->library('denglu');
	}

    //登录
	public function login($ac=''){
		if(empty($ac)) $ac = $this->input->get('id');
		$log_state = md5(uniqid(rand(), TRUE));
		$dos = array('qq', 'weixin');
		$ac = (!empty($ac) && in_array($ac, $dos))?$ac:'qq';
		//保存到cookie
		setcookie('log_ac',$ac,time()+1800,'/');
		setcookie('log_state',$log_state,time()+1800,'');
        if(isset($_SERVER['HTTP_REFERER'])){
			setcookie('log_fhurl',$_SERVER['HTTP_REFERER'],time()+1800,'');
        }
		//连接
        $this->denglu->login($ac,$log_state);
	}

    //返回
	public function callback(){
	    $ac = isset($_COOKIE["log_ac"]) ? $_COOKIE["log_ac"] : 'qq';
	    $log_state = isset($_COOKIE['log_state']) ? $_COOKIE['log_state'] : ''; //安全验证
	    $log_fhurl = isset($_COOKIE['log_fhurl']) ? $_COOKIE['log_fhurl'] : links('user','index'); //返回地址
        $arr = $this->denglu->callback($ac,$log_state);
		$uid = $arr['uid'];
		$pic = $arr['pic'];
		$nichen = $arr['nichen'];

		if(!empty($uid)){
			if($ac=='qq'){
				$name = 'qq'.time();
				$zd = 'qqid';
			}else{
				$name = 'wx'.time();
				$zd = 'wxid';
			}
			$row = $this->csdb->get_row('user','*',array($zd=>$uid));
			if($row){
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
				//保存VIP COOKIE
				if($row->vip>0){
				  setcookie('ctcms_vip','ok',86400+time(),'/');
				}else{
				  setcookie('ctcms_vip','no',time()-86400,'/');
				}

				$uid = $row->id;
				$uname = $row->name;
				$upass = $row->pass;

			}else{
				$add['name'] = $name;
				$add['pass'] = md5(time());
				$add['email'] = '';
				$add['nichen'] = $nichen;
				$add['pic'] = $pic;
				$add[$zd] = $uid;
				$add['cion'] = User_Reg_Cion+User_Log_Cion;
				$add['regtime'] = time();
				$add['logtime'] = time();
				$add['lognum'] = 1;
				$add['logip'] = getip();
				//入库
				$res = $this->csdb->get_insert('user',$add);
				if(!$res){
					msg_url('登录失败，稍后再试~!','javascript:history.back();');
				}
				$uid = $res;
				$uname = $add['name'];
				$upass = $add['pass'];
			}

			setcookie('log_ac','',time()-1800,'/');
			setcookie('log_state','',time()-1800,'');
			setcookie('log_fhurl','',time()-1800,'');
			//登陆
			$this->session->set_tempdata('user_id', $uid, 86400);
			$this->session->set_tempdata('user_name', $uname, 86400);
			$this->session->set_tempdata('user_login', md5($uid.$uname.$upass.CT_Encryption_Key), 86400);

			//记住登陆
			setcookie('ctcms_uid',$uid,86400*10+time(),'/');
			setcookie('ctcms_log',md5($uid.$uname.$upass.CT_Encryption_Key),86400*10+time(),'/');

			header("Location:$log_fhurl");

		}else{
			msg_url('登录失败，稍后再试~!','javascript:history.back();');
		}
	}
}
