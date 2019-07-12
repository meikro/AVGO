<?php
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-08
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');
class User extends CI_Model{
    function __construct (){
		parent:: __construct ();
		if(!defined('IS_LOG')) define('USER', true);
		$this->load->library('session');
    }
    //判断是否登入
    function login($sid=0,$key=''){
		$session = 1;
		if(empty($key)){
			$id = !isset($_SESSION['user_id']) ? 0 : $_SESSION['user_id'];
			$login =  !isset($_SESSION['user_login']) ? '' :  $_SESSION['user_login'];
			if(empty($id) || empty($login)){
				$id = !isset($_COOKIE['ctcms_uid']) ? 0 : (int)$_COOKIE['ctcms_uid'];
				$login =  !isset($_COOKIE['ctcms_log']) ? '' :  $_COOKIE['ctcms_log'];
				$session = 0;
			}
		}else{
			$str  = unserialize(stripslashes(sys_auth($key,1)));
			$id   = isset($str['id'])?intval($str['id']):0;
			$login = isset($str['login'])?$str['login']:'';
		}
		if(empty($id) || empty($login)){
		    if($sid==0){
		        msg_url('登陆已超时，请登陆~!',links('user','login'));
			}else{
		        return 0;
			} 
		}
		$user=$this->csdb->get_row('user','name,pass,vip,viptime',array('id'=>$id));
		if($user){
		    if(md5($id.$user->name.$user->pass.CT_Encryption_Key)!=$login){
				$this->get_log('logout'); //退出COOKIE
				if($sid==0){
		            msg_url('登陆已超时，请登陆~!',links('user','login'));
				}else{
					return 0;
				}
		    }else{
				$this->get_log('login',$session,$id,$login); //写入SESSION
		        //判断VIP
				if($user->vip==1){
					if($user->viptime<time()){
		                $edit['vip'] = 0;
		                $this->csdb->get_update('user',$id,$edit);
					    setcookie('ctcms_vip','',time()-86400,'/');
					}else{
					    setcookie('ctcms_vip','ok',time()+86400,'/');
					}
				}else{
		            setcookie('ctcms_vip','',time()-86400,'/');
				}
			}
		}else{
		    $this->get_log('logout'); //退出COOKIE
			if($sid==0){
		        msg_url('登陆已超时，请登陆~!',links('user','login'));
			}else{
				return 0;
			}
		}
		if($sid==1) return 1;
    }

	//登陆超时推出COOKIE
    function get_log($type='logout',$session=1,$uid=0,$log=''){
		if($type=='login'){
			if($session==0){
				$this->session->set_tempdata('user_id', $uid, 86400);
				$this->session->set_tempdata('user_login', $log, 86400);
			}
		}else{
			setcookie('ctcms_vip','',86400-time(),'/');
			setcookie('ctcms_uid','',86400-time(),'/');
			setcookie('ctcms_log','',86400-time(),'/');
		}
	}

    //删除收藏
    function get_del_fav($where,$zd='id'){
		if(is_array($where)){
			foreach ($where as $k=>$v){
				$this->db->where($k,$v);
			}
		}else{
			$this->db->where($zd,$where);
		}
		if($this->db->delete('fav')){
			return true;
		}else{
			return false;
		}
	}
}