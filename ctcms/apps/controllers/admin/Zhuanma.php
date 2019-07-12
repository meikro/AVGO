<?php
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-11
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Zhuanma extends Ctcms_Controller {

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
		$this->load->view('zhuanma.tpl');
	}

	public function save()
	{
		$Zhuan_Is = (int)$this->input->post('Zhuan_Is', TRUE);
		$Zhuan_Kbps = $this->input->post('Zhuan_Kbps', TRUE);
		$Zhuan_Size = $this->input->post('Zhuan_Size', TRUE);
		$Zhuan_Time = (int)$this->input->post('Zhuan_Time', TRUE);
		$Zhuan_Path = $this->input->post('Zhuan_Path', TRUE);
		$Zhuan_Url = $this->input->post('Zhuan_Url', TRUE);
		$Zhuan_M3u8_Url = $this->input->post('Zhuan_M3u8_Url', TRUE);
		$Zhuan_Sy_Is = (int)$this->input->post('Zhuan_Sy_Is', TRUE);
		$Zhuan_Sy_Pos = (int)$this->input->post('Zhuan_Sy_Pos');
		$Zhuan_Sy_Lt = $this->input->post('Zhuan_Sy_Lt', TRUE);
		$Zhuan_Sy_Pic = $this->input->post('Zhuan_Sy_Pic', TRUE);
		$Zhuan_Jpg_Size = $this->input->post('Zhuan_Jpg_Size', TRUE);
		$Zhuan_Jpg_Num = (int)$this->input->post('Zhuan_Jpg_Num', TRUE);
		$Zhuan_Jpg_Time = (int)$this->input->post('Zhuan_Jpg_Time', TRUE);
		$Zhuan_Sk = (int)$this->input->post('Zhuan_Sk', TRUE);
		$Zhuan_Sk_Type = (int)$this->input->post('Zhuan_Sk_Type', TRUE);
		$Zhuan_Sk_Time = (int)$this->input->post('Zhuan_Sk_Time', TRUE);
		$Zhuan_Sk_Nums = (int)$this->input->post('Zhuan_Sk_Nums', TRUE);

		if($Zhuan_Time == 0) $Zhuan_Time = 1;
		if($Zhuan_Jpg_Time == 0) $Zhuan_Jpg_Time = 1;
		if($Zhuan_Sk_Time == 0) $Zhuan_Sk_Time = 10;

		$arr = explode(':',$Zhuan_Sy_Lt);
		$a1 = (int)$arr[0];
		$a2 = (int)$arr[1];
		$Zhuan_Sy_Lt = $a1.':'.$a2;

		$arr = explode('x',$Zhuan_Jpg_Size);
		$a1 = (int)$arr[0];
		$a2 = (int)$arr[1];
		if($a1 == 0 || $a2 == 0) $Zhuan_Jpg_Size = '';

		$arr = explode('x',$Zhuan_Size);
		$a1 = (int)$arr[0];
		$a2 = (int)$arr[1];
		if($a1 == 0 || $a2 == 0) $Zhuan_Size = '';

        //判断主要数据不能为空
		if($Zhuan_Sy_Is == 1){
		    if(empty($Zhuan_Sy_Pic)) admin_msg('水印图片路径不能为空',links('zhuanma'),'no');
			if(!file_exists($Zhuan_Sy_Pic)) admin_msg('水印图片文件不存在',links('zhuanma'),'no');
		}

		if(empty($Zhuan_Path) || !file_exists($Zhuan_Path)) admin_msg('转码保存目录不存在',links('zhuanma'),'no');

		$strs="<?php"."\r\n";
		$strs.="define('Zhuan_Is',".$Zhuan_Is.");\r\n";
		$strs.="define('Zhuan_Kbps','".$Zhuan_Kbps."');\r\n";
		$strs.="define('Zhuan_Size','".$Zhuan_Size."');\r\n";
		$strs.="define('Zhuan_Time',".$Zhuan_Time.");\r\n";
		$strs.="define('Zhuan_Path','".$Zhuan_Path."');\r\n";
		$strs.="define('Zhuan_Url','".$Zhuan_Url."');\r\n";
		$strs.="define('Zhuan_M3u8_Url','".$Zhuan_M3u8_Url."');\r\n";
		$strs.="define('Zhuan_Sy_Is',".$Zhuan_Sy_Is.");\r\n";
		$strs.="define('Zhuan_Sy_Pos',".$Zhuan_Sy_Pos.");\r\n";
		$strs.="define('Zhuan_Sy_Lt','".$Zhuan_Sy_Lt."');\r\n";
		$strs.="define('Zhuan_Sy_Pic','".$Zhuan_Sy_Pic."');\r\n";
		$strs.="define('Zhuan_Jpg_Size','".$Zhuan_Jpg_Size."');\r\n";
		$strs.="define('Zhuan_Jpg_Num',".$Zhuan_Jpg_Num.");\r\n";
		$strs.="define('Zhuan_Jpg_Time',".$Zhuan_Jpg_Time.");\r\n";
		$strs.="define('Zhuan_Sk',".$Zhuan_Sk.");\r\n";
		$strs.="define('Zhuan_Sk_Type',".$Zhuan_Sk_Type.");\r\n";
		$strs.="define('Zhuan_Sk_Time',".$Zhuan_Sk_Time.");\r\n";
		$strs.="define('Zhuan_Sk_Nums',".$Zhuan_Sk_Nums.");";

        //写文件
        if (!write_file(CTCMSPATH.'libs/Ct_Zhuan.php', $strs)){
             admin_msg('抱歉，修改失败，请检查文件写入权限~!',links('zhuanma'),'no');
		}else{
             admin_msg('恭喜您，配置修改成功~！',links('zhuanma'));
		}
	}
}
