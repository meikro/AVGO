<?php
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-11
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Yunparse extends Ctcms_Controller {

	function __construct(){
	    parent::__construct();
		//加载后台模型
		$this->load->model('admin');
        //当前模版
		$this->load->get_templates('admin');
		//判断是否登陆
		$this->admin->login();
	}

	public function index()
	{
 		$this->load->view('head.tpl');
		$this->load->view('yunparse.tpl');
	}

	public function save()
	{
		$REFERER_URL = $this->input->post('REFERER_URL', TRUE);
		$USER_ID = $this->input->post('USER_ID', TRUE);
		$USER_TOKEN = $this->input->post('USER_TOKEN', TRUE);
		$VOD_HD = (int)$this->input->post('VOD_HD', TRUE);
		$VOD_API = (int)$this->input->post('VOD_API', TRUE);
		$VOD_JM = (int)$this->input->post('VOD_JM', TRUE);
		$VOD_TIME = (int)$this->input->post('VOD_TIME', TRUE);

		if($VOD_HD==0) $VOD_HD = 2;
		if($VOD_API==0) $VOD_API = 1;

        //判断主要数据不能为空
		if (empty($USER_ID)||empty($USER_TOKEN)){
		       admin_msg('会员UID和会员TOKEN不能为空',links('yunparse'),'no');
		}

		$strs="<?php"."\r\n";
		$strs.="define('REFERER_URL', '".trim($REFERER_URL)."');  //防盗链域名  \r\n";
		$strs.="define('USER_ID', '".trim($USER_ID)."');//解析UID  \r\n";
		$strs.="define('USER_TOKEN', '".trim($USER_TOKEN)."');//解析TOKEN  \r\n";
		$strs.="define('VOD_HD', '".$VOD_HD."'); //默认清晰度  \r\n";
		$strs.="define('VOD_API', '".$VOD_API."');//解析默认线路  \r\n";
		$strs.="define('VOD_JM', '".$VOD_JM."');//解析是否加密  \r\n";
		$strs.="define('VOD_TIME', '".$VOD_TIME."');//解析加密有效期 ";
		
        //写文件
        if (!write_file(CTCMSPATH.'libs/Ct_Yunparse.php', $strs)){
             admin_msg('抱歉，修改失败，请检查文件写入权限~!',links('yunparse'),'no');
		}else{
			 admin_msg('恭喜您，配置修改成功~！',links('yunparse'));
		}
	}
}
