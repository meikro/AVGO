<?php
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-11
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends Ctcms_Controller {

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
		$this->load->view('app.tpl');
	}

	public function save()
	{
		$CT_App_Ver = $this->input->post('CT_App_Ver', TRUE);
		$CT_App_Cyid = $this->input->post('CT_App_Cyid', TRUE);
		$CT_App_Cykey = $this->input->post('CT_App_Cykey', TRUE);
		$CT_App_Kalink = $this->input->post('CT_App_Kalink', TRUE);
		$CT_App_Uplink = $this->input->post('CT_App_Uplink', TRUE);
		$CT_App_Jxurl = $this->input->post('CT_App_Jxurl', TRUE);
		$CT_App_Sktime = (int)$this->input->post('CT_App_Sktime', TRUE);
		$CT_App_Paytype = $this->input->post('CT_App_Paytype');
		$CT_App_Paytype = implode('|',$CT_App_Paytype);

        //判断主要数据不能为空
		if (empty($CT_App_Ver)){
		       admin_msg('版本号不能为空',links('app'),'no');
		}

		$strs="<?php"."\r\n";
		$strs.="define('CT_App_Ver','".$CT_App_Ver."'); //APP版本号  \r\n";
		$strs.="define('CT_App_Cyid','".$CT_App_Cyid."'); //APP畅言APPID  \r\n";
		$strs.="define('CT_App_Cykey','".$CT_App_Cykey."'); //APP畅言APPKEY  \r\n";
		$strs.="define('CT_App_Kalink','".$CT_App_Kalink."');  //点卡购买地址  \r\n";
		$strs.="define('CT_App_Uplink','".$CT_App_Uplink."');  //APP升级更新地址  \r\n";
		$strs.="define('CT_App_Jxurl','".$CT_App_Jxurl."');  //APP解析地址  \r\n";
		$strs.="define('CT_App_Sktime','".$CT_App_Sktime."');  //收费视频试看分钟  \r\n";
		$strs.="define('CT_App_Paytype','".$CT_App_Paytype."');  //APP付款方式  ";

        //写文件
        if (!write_file(CTCMSPATH.'libs/Ct_App.php', $strs)){
             admin_msg('抱歉，修改失败，请检查文件写入权限~!',links('app'),'no');
		}else{
             admin_msg('恭喜您，配置修改成功~！',links('app'));
		}
	}
}
