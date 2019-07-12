<?php
/**
 * @Ctcms open source management system
 * @copyright 2008-2016 www.ctcms.cn. All rights reserved.
 * @Author:Cheng Kai Jie
 * @Dtime:2016-09-03
 */
header('Content-Type: text/html; charset=utf-8');
//装载全局配置文件
require_once 'Ct_DB.php';
require_once 'Ct_Config.php';
require_once 'Ct_Pay.php';
require_once 'Ct_Mail.php';
require_once 'Ct_Down.php';
require_once 'Ct_Version.php';
require_once 'Ct_Yunparse.php';
require_once 'Ct_Html.php';
require_once 'Ct_Denglu.php';
require_once 'Ct_App.php';
require_once 'Ct_Zhuan.php';
//手机客户端访问标示
if(preg_match("/(iPhone|iPad|iPod|Android|Linux)/i", strtoupper($_SERVER['HTTP_USER_AGENT']))){
    define('MOBILE', true);	
}
//判断网站运行状态
if(!defined('IS_ADMIN') && Web_Off==1){
	require_once VIEWPATH.'errors/html/open.php';
    exit;
}
//URL运行模式则自动加上D参数admin
if (defined('IS_ADMIN') && Web_Mode==2){
    $_GET['d']='admin';
}
//判断会员开关
if(!defined('IS_ADMIN') && User_Off==1 && (strpos($_SERVER['REQUEST_URI'],'/user/') !== FALSE || strpos($_SERVER['REQUEST_URI'],'c=user') !== FALSE)){
    exit(User_Onneir);
}
//判断头像上传
if(strpos($_SERVER['REQUEST_URI'],'user/edit/logo_save') !== FALSE){
    define('USERLOGO', true);	
}
//判断APP
if(strpos($_SERVER['REQUEST_URI'],'/app/') !== FALSE || strpos($_SERVER['REQUEST_URI'],'/play/m3u8/') !== FALSE){
    define('CTAPP', true);	
}
//判断手机客户端访问
if(defined('MOBILE')){
    if(!defined('IS_ADMIN') && Wap_Is==1){
          if(Wap_Url!='' && $_SERVER['HTTP_HOST']!=Wap_Url){
				$Web_Link="http://".str_replace('//','/',Wap_Url.Web_Path.ctcms_cur_url());
                header("location:".$Web_Link);exit;
		  }
    }
}else{
	//判断是否关闭PC端
	if(!defined('IS_ADMIN') && defined('Web_Pc') && Web_Pc==0){
		if(strpos($_SERVER['REQUEST_URI'],'/wx/code') === FALSE && strpos($_SERVER['REQUEST_URI'],'c=wx&m=code') === FALSE){
			require_once VIEWPATH.'errors/html/pc.php';
			exit;
		}
	}
    $Web_Link="http://".str_replace('//','/',Web_Url.Web_Path.ctcms_cur_url());
	if($_SERVER['HTTP_HOST']==Wap_Url){
        header("location:".$Web_Link);exit;
	}
}
//获取当前目录路径参数
function ctcms_cur_url() { 
    if(!empty($_SERVER["REQUEST_URI"])){ 
        $scrtName = $_SERVER["REQUEST_URI"]; 
        $nowurl = $scrtName; 
    } else { 
        $scrtName = $_SERVER["PHP_SELF"]; 
        if(empty($_SERVER["QUERY_STRING"])) { 
            $nowurl = $scrtName; 
        } else { 
            $nowurl = $scrtName."?".$_SERVER["QUERY_STRING"]; 
        } 
    } 
    return $nowurl; 
}