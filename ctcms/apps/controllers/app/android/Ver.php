<?php
/** * 
@Ctcms open source management system * 
@copyright 2008-2016 chshcms.com. All rights reserved. * 
@Author:Cheng Kai Jie * 
@Dtime:2015-12-11 
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Ver extends Ctcms_Controller {	
	function __construct(){	    
		parent::__construct();        
	}      

	//检测版本
	public function index()	{
		$array['data']['ver'] = CT_App_Ver;
		$array['data']['upurl'] = CT_App_Uplink;
		echo json_encode($array);
	}

	//在线更新
	public function down()	{
		echo '<!DOCTYPE html><html><head><meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"><title>APP下载</title></head><body style="padding: 20px;text-align: center;"><p>版本号：V'.CT_App_Ver.'</p><p><a href="http://'.Web_Url.Web_Path.CT_App_Ver.'.apk">点击下载更新APP</a></p></body></html>';
	}
}