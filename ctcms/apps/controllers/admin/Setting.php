<?php
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-11
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends Ctcms_Controller {

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
		$this->load->helper('directory');
		//获取所有模板
		$map = directory_map('./template/skins/', 1);
		$skin=array();
        foreach ($map as $dir) {
			$dir=str_replace("\\","/",$dir);
			$dir=str_replace("/","",$dir);
			$skin[]=$dir;
		}
        $data['skin'] = $skin;
		//获取所有手机模板
		$map = directory_map('./template/mobile', 1);
		$wapskin=array();
        foreach ($map as $dir) {
			$dir=str_replace("\\","/",$dir);
			$dir=str_replace("/","",$dir);
			$wapskin[]=$dir;
		}
        $data['wapskin'] = $wapskin;
		//获取所有会员手机模板
		$map = directory_map('./template/mobile_user', 1);
		$wapuserskin=array();
        foreach ($map as $dir) {
			$dir=str_replace("\\","/",$dir);
			$dir=str_replace("/","",$dir);
			$wapuserskin[]=$dir;
		}
        $data['wapuserskin'] = $wapuserskin;
		//获取所有会员模板
		$map = directory_map('./template/user', 1);
		$userskin=array();
        foreach ($map as $dir) {
			$dir=str_replace("\\","/",$dir);
			$dir=str_replace("/","",$dir);
			$userskin[]=$dir;
		}
        $data['userskin'] = $userskin;
 		$this->load->view('head.tpl',$data);
		$this->load->view('setting.tpl');
	}

	public function save()
	{
		$Web_Name = $this->input->post('Web_Name', TRUE);
		$Web_Url = $this->input->post('Web_Url', TRUE);
		$Web_Path = $this->input->post('Web_Path', TRUE);
		$Base_Path = $this->input->post('Base_Path', TRUE);
		$Admin_Code = $this->input->post('Admin_Code', TRUE);
		$Admin_Log_Day = intval($this->input->post('Admin_Log_Day'));
		$Admin_Log_Ip = $this->input->post('Admin_Log_Ip', TRUE);
		$Web_Off = intval($this->input->post('Web_Off', TRUE));
		$Web_Onneir = $this->input->post('Web_Onneir', TRUE);
		$Web_Mode = intval($this->input->post('Web_Mode', TRUE));
		$Web_Icp = $this->input->post('Web_Icp', TRUE);
		$Admin_QQ = $this->input->post('Admin_QQ', TRUE);
		$Admin_Mail = $this->input->post('Admin_Mail', TRUE);
		$Web_Count = $_POST['Web_Count'];
		$Web_Title = $this->input->post('Web_Title', TRUE);
		$Web_Keywords = $this->input->post('Web_Keywords', TRUE);
		$Web_Description = $this->input->post('Web_Description', TRUE);
		$Cache_Is = intval($this->input->post('Cache_Is', TRUE));
		$Cache_Time = intval($this->input->post('Cache_Time', TRUE));
		$Web_Skin = $this->input->post('Web_Skin', TRUE);
		$Weixin = $this->input->post('Weixin', TRUE);
		$Weixin_Url = $this->input->post('Weixin_Url', TRUE);
		$Weixin_Token = $this->input->post('Weixin_Token', TRUE);
		$Web_Pl = $_POST['Web_Pl'];
			
		$Wap_Is = intval($this->input->post('Wap_Is', TRUE));
		$Wap_Skin = $this->input->post('Wap_Skin', TRUE);
		$Wap_User_Skin = $this->input->post('Wap_User_Skin', TRUE);
		$Wap_Url = $this->input->post('Wap_Url', TRUE);

		$User_Off = intval($this->input->post('User_Off', TRUE));
		$User_Onneir = $this->input->post('User_Onneir', TRUE);
		$User_Reg_Cion = intval($this->input->post('User_Reg_Cion', TRUE));
		$User_Log_Cion = intval($this->input->post('User_Log_Cion', TRUE));
		$User_Qd_Cion = intval($this->input->post('User_Qd_Cion', TRUE));
		$User_Skin = $this->input->post('User_Skin', TRUE);
		$User_Fc_Off = intval($this->input->post('User_Fc_Off', TRUE));
		$User_Fc_1 = (float)$this->input->post('User_Fc_1', TRUE);
		$User_Fc_2 = (float)$this->input->post('User_Fc_2', TRUE);
		$User_Fc_3 = (float)$this->input->post('User_Fc_3', TRUE);
		$User_Fc_Tx = intval($this->input->post('User_Fc_Tx', TRUE));

		$Uri_Mode =(int)$this->input->post('Uri_Mode', TRUE);
		$Uri_List = $this->input->post('Uri_List', TRUE);
		$Uri_Show = $this->input->post('Uri_Show', TRUE);
		$Uri_Play = $this->input->post('Uri_Play', TRUE);
		$Uri_Comm = $this->input->post('Uri_Comm', TRUE);
		$Uri_Article = $this->input->post('Uri_Article', TRUE);

		$Web_Diqu = $this->input->post('Web_Diqu', TRUE);
		$Web_Yuyan = $this->input->post('Web_Yuyan', TRUE);
		$Web_Year = $this->input->post('Web_Year', TRUE);
		$Web_Type = str_encode($this->input->post('Web_Type'));

		$Gbook_Is = (int)$this->input->post('Gbook_Is', TRUE);
		$Gbook_Log = (int)$this->input->post('Gbook_Log', TRUE);
		$Gbook_Sh = (int)$this->input->post('Gbook_Sh', TRUE);
		$Gbook_Str = $this->input->post('Gbook_Str', TRUE);

		$Ftp_Is = (int)$this->input->post('Ftp_Is', TRUE);
		$Ftp_Port = (int)$this->input->post('Ftp_Port', TRUE);
		$Ftp_Token = $this->input->post('Ftp_Token', TRUE);
		$Ftp_Pid = $this->input->post('Ftp_Pid', TRUE);
		$Ftp_Url = $this->input->post('Ftp_Url', TRUE);
		$Ftp_Server = $this->input->post('Ftp_Server', TRUE);
		$Ftp_User = $this->input->post('Ftp_User', TRUE);
		$Ftp_Pass = $this->input->post('Ftp_Pass', TRUE);
		$Ftp_Ive = $this->input->post('Ftp_Ive', TRUE);
		$Ftp_Dir = $this->input->post('Ftp_Dir', TRUE);
		$Ftp_Ive = ($Ftp_Ive==1) ? 'TRUE' : 'FALSE';
		
		$Web_Pc = intval($this->input->post('Web_Pc', TRUE));
		$Uri_Topic = $this->input->post('Uri_Topic', TRUE);
		$Uri_Topic_Show = $this->input->post('Uri_Topic_Show', TRUE);
		$IS_Api = intval($this->input->post('IS_Api', TRUE));

        if($Cache_Time==0)  $Cache_Time=600;
        //HTML转码 
        $Web_Title= str_encode($Web_Title); 
        $Web_Keywords= str_encode($Web_Keywords); 
        $Web_Description= str_encode($Web_Description); 
		$Web_Count= str_encode($Web_Count); 
		$Web_Pl= str_encode($Web_Pl);
		$Gbook_Str= str_encode($Gbook_Str);

		$Html_Off = intval($this->input->post('Html_Off', TRUE));
		$Html_Play_Off = intval($this->input->post('Html_Play_Off', TRUE));
		$Html_Index = $this->input->post('Html_Index', TRUE);
		$Html_List = $this->input->post('Html_List', TRUE);
		$Html_Show = $this->input->post('Html_Show', TRUE);
		$Html_Play = $this->input->post('Html_Play', TRUE);
		$Html_Topic = $this->input->post('Html_Topic', TRUE);
		$Html_Topic_Show = $this->input->post('Html_Topic_Show', TRUE);
		$Html_News_List = $this->input->post('Html_News_List', TRUE);
		$Html_News_Show = $this->input->post('Html_News_Show', TRUE);
		if(!empty($Html_Dir)){
			if(empty($Html_Url)) admin_msg('静态生成目录访问地址不能为空',links('setting'),'no');
		}else{
			$Html_Url = '';
		}

		$Qq_Log = intval($this->input->post('Qq_Log', TRUE));
		$Qq_Appid = $this->input->post('Qq_Appid', TRUE);
		$Qq_Appkey = $this->input->post('Qq_Appkey', TRUE);
		$Wx_Log = intval($this->input->post('Wx_Log', TRUE));
		$Wx_Appid = $this->input->post('Wx_Appid', TRUE);
		$Wx_Appkey = $this->input->post('Wx_Appkey', TRUE);

		$cjadd = $this->input->post('cjadd',TRUE);
		$Cj_Add = implode(',', $cjadd);
		$cjedit = $this->input->post('cjedit',TRUE);
		$Cj_Edit = implode(',', $cjedit);

        //判断主要数据不能为空
		if (empty($Web_Name)||empty($Web_Url)||empty($Web_Path)||empty($Admin_Code)){
		       admin_msg('网站名称、域名、路径、后台认证码不能为空',links('setting'),'no');
		}

		//URL路由
		if($Uri_Mode==1){
			if (empty($Uri_List)||empty($Uri_Show)||empty($Uri_Play)||empty($Uri_Comm)||empty($Uri_Article)){
		         admin_msg('URL路由规则不能为空',links('setting'),'no');
		    }
            $uri = array(
                    'list'=>$Uri_List,
                    'show'=>$Uri_Show,
                    'play'=>$Uri_Play,
                    'comm'=>$Uri_Comm,
                    'article'=>$Uri_Article,
                    'topic'=>$Uri_Topic,
                    'topic_show'=>$Uri_Topic_Show
			);
            $this->_route_file($uri);
		}

		$strs="<?php"."\r\n";
		$strs.="define('Web_Name','".$Web_Name."'); //站点名称  \r\n";
		$strs.="define('Web_Url','".$Web_Url."'); //站点域名  \r\n";
		$strs.="define('Web_Path','".$Web_Path."'); //站点路径  \r\n";
		$strs.="define('Web_Off',".$Web_Off.");  //网站开关  \r\n";
		$strs.="define('Web_Onneir','".$Web_Onneir."');  //网站关闭内容  \r\n";
		$strs.="define('Web_Pc',".$Web_Pc.");  //电脑访问开关  \r\n";
		$strs.="define('Web_Mode',".$Web_Mode.");  //网站运行模式  \r\n";
		$strs.="define('Web_Icp','".$Web_Icp."');  //网站ICP  \r\n";
		$strs.="define('Web_Count','".$Web_Count."');  //统计代码  \r\n";
		$strs.="define('Web_Title','".$Web_Title."'); //SEO-标题  \r\n";
		$strs.="define('Web_Keywords','".$Web_Keywords."'); //SEO-Keywords  \r\n";
		$strs.="define('Web_Description','".$Web_Description."'); //SEO-description  \r\n";
		$strs.="define('Web_Skin','".$Web_Skin."'); //网站默认模板  \r\n";
		$strs.="define('Admin_QQ','".$Admin_QQ."');  //站长QQ  \r\n";
		$strs.="define('Admin_Mail','".$Admin_Mail."');  //站长EMAIL  \r\n";
		$strs.="define('Admin_Code','".$Admin_Code."');  //后台验证码  \r\n";
		$strs.="define('Admin_Log_Day',".$Admin_Log_Day.");  //后台登陆日志保存天数  \r\n";
		$strs.="define('Admin_Log_Ip','".$Admin_Log_Ip."');  //允许访问后台的IP列表  \r\n";
		$strs.="define('Cache_Is',".$Cache_Is.");  //缓存开关  \r\n";
		$strs.="define('Cache_Time',".$Cache_Time.");  //缓存时间  \r\n";
		$strs.="define('Base_Path','".$Base_Path."'); //附件路径，包含后台css、js、images  \r\n";
		$strs.="define('Weixin','".$Weixin."');  //微信号    \r\n";
		$strs.="define('Weixin_Url','".$Weixin_Url."');  //微信号链接\r\n";
		$strs.="define('Weixin_Token','".$Weixin_Token."');  //微信自动回复token\r\n";
		$strs.="define('Wap_Is',".$Wap_Is.");  //手机版开关\r\n";
		$strs.="define('Wap_Url','".$Wap_Url."');  //手机版地址\r\n";
		$strs.="define('Wap_Skin','".$Wap_Skin."');  //手机版模版\r\n";
		$strs.="define('Wap_User_Skin','".$Wap_User_Skin."');  //手机版会员模版\r\n";
		$strs.="define('User_Off',".$User_Off.");  //会员开关  \r\n";
		$strs.="define('User_Onneir','".$User_Onneir."');  //会员关闭内容  \r\n";
		$strs.="define('User_Reg_Cion',".$User_Reg_Cion.");  //会员注册赠送金币  \r\n";
		$strs.="define('User_Log_Cion',".$User_Log_Cion.");  //会员注册赠送金币  \r\n";
		$strs.="define('User_Qd_Cion',".$User_Qd_Cion.");  //会员注册赠送金币  \r\n";
		$strs.="define('User_Skin','".$User_Skin."'); //会员默认模板  \r\n";
		$strs.="define('User_Fc_Off',".$User_Fc_Off.");  //三级分销开关  \r\n";
		$strs.="define('User_Fc_Tx',".$User_Fc_Tx.");  //分成最少提现额  \r\n";
		$strs.="define('User_Fc_1',".$User_Fc_1.");  //一级分销分成比例  \r\n";
		$strs.="define('User_Fc_2',".$User_Fc_2.");  //二级分销分成比例  \r\n";
		$strs.="define('User_Fc_3',".$User_Fc_3.");  //三级分销分成比例  \r\n";
		$strs.="define('Uri_Mode',".$Uri_Mode.");  //是否启用Url路由  \r\n";
		$strs.="define('Uri_List','".$Uri_List."');  //分类页路由规则  \r\n";
		$strs.="define('Uri_Show','".$Uri_Show."');  //内容页路由规则  \r\n";
		$strs.="define('Uri_Play','".$Uri_Play."');  //播放页路由规则  \r\n";
		$strs.="define('Uri_Comm','".$Uri_Comm."');  //圈子分类路由规则  \r\n";
		$strs.="define('Uri_Article','".$Uri_Article."');  //圈子内容路由规则  \r\n";
		$strs.="define('Uri_Topic','".$Uri_Topic."');  //专题路由规则  \r\n";
		$strs.="define('Uri_Topic_Show','".$Uri_Topic_Show."');  //专题内容路由规则  \r\n";
		$strs.="define('Web_Diqu','".$Web_Diqu."');  //地区  \r\n";
		$strs.="define('Web_Yuyan','".$Web_Yuyan."');  //语言  \r\n";
		$strs.="define('Web_Year','".$Web_Year."');  //年份  \r\n";
		$strs.="define('Web_Type','".$Web_Type."');  //类型  \r\n";
		$strs.="define('Web_Pl','".$Web_Pl."');  //评论代码  \r\n";
		$strs.="define('Gbook_Is',".$Gbook_Is.");  //留言开关  \r\n";
		$strs.="define('Gbook_Log',".$Gbook_Log.");  //留言需要登陆  \r\n";
		$strs.="define('Gbook_Sh',".$Gbook_Sh.");  //留言需要审核  \r\n";
		$strs.="define('Gbook_Str','".$Gbook_Str."');  //留言过滤关键字  \r\n";
		$strs.="define('Ftp_Is',".$Ftp_Is.");  //FTP远程附件开关  \r\n";
		$strs.="define('Ftp_Token','".$Ftp_Token."');  //贴图库Token  \r\n";
		$strs.="define('Ftp_Pid','".$Ftp_Pid."');  //贴图库相册ID  \r\n";
		$strs.="define('Ftp_Url','".$Ftp_Url."');  //FTP图片远程访问地址  \r\n";
		$strs.="define('Ftp_Server','".$Ftp_Server."');  //FTP链接IP  \r\n";
		$strs.="define('Ftp_User','".$Ftp_User."');  //FTP连接账号  \r\n";
		$strs.="define('Ftp_Pass','".$Ftp_Pass."');  //FTP链接密码  \r\n";
		$strs.="define('Ftp_Port','".$Ftp_Port."');  //FTP链接端口  \r\n";
		$strs.="define('Ftp_Ive',".$Ftp_Ive.");  //FTP是否使用被动模式  \r\n";
		$strs.="define('Ftp_Dir','".$Ftp_Dir."');  //FTP默认目录  \r\n";
		$strs.="define('IS_Api',".$IS_Api.");  //是否启用资源api采集\r\n";
		$strs.="define('Cj_Add','".$Cj_Add."');  //采集入库重复规则\r\n";
		$strs.="define('Cj_Edit','".$Cj_Edit."');  //采集二次更新";

		$strs2="<?php"."\r\n";
		$strs2.="define('Html_Off',".$Html_Off.");  //生成开关\r\n";
		$strs2.="define('Html_Play_Off',".$Html_Play_Off.");  //播放页生成开关\r\n";
		$strs2.="define('Html_Dir','".$Html_Dir."');  //生成目录\r\n";
		$strs2.="define('Html_Url','".$Html_Url."');  //生成目录访问地址\r\n";
		$strs2.="define('Html_Index','".$Html_Index."');  //主页生成地址\r\n";
		$strs2.="define('Html_List','".$Html_List."');  //列表页生成地址\r\n";
		$strs2.="define('Html_Show','".$Html_Show."');  //内容页生成地址\r\n";
		$strs2.="define('Html_Play','".$Html_Play."');  //播放页生成地址\r\n";
		$strs2.="define('Html_Topic','".$Html_Topic."');  //专题列表页生成地址\r\n";
		$strs2.="define('Html_Topic_Show','".$Html_Topic_Show."');  //专题内容页生成地址\r\n";
		$strs2.="define('Html_News_List','".$Html_News_List."');  //文章列表页生成地址\r\n";
		$strs2.="define('Html_News_Show','".$Html_News_Show."');  //文章内容页生成地址";

		$strs3="<?php"."\r\n";
		$strs3.="define('Qq_Log',".$Qq_Log.");\r\n";
		$strs3.="define('Qq_Appid','".$Qq_Appid."');\r\n";
		$strs3.="define('Qq_Appkey','".$Qq_Appkey."');\r\n";
		$strs3.="define('Wx_Log',".$Wx_Log.");\r\n";
		$strs3.="define('Wx_Appid','".$Wx_Appid."');\r\n";
		$strs3.="define('Wx_Appkey','".$Wx_Appkey."');";
		
        //写文件
        if (!write_file(CTCMSPATH.'libs/Ct_Config.php', $strs)){
             admin_msg('抱歉，修改失败，请检查文件写入权限~!',links('setting'),'no');
		}else{
			 write_file(CTCMSPATH.'libs/Ct_Html.php', $strs2);
			 write_file(CTCMSPATH.'libs/Ct_Denglu.php', $strs3);
			 if($Web_Mode!=Web_Mode){
                 die("<script language='javascript'>alert('修改成功~!');top.location='".links('index')."';</script>");
			 }else{
                 admin_msg('恭喜您，配置修改成功~！',links('setting'));
			 }
		}
	}

	public function pay()
	{
 		$this->load->view('head.tpl');
		$this->load->view('setting_pay.tpl');
	}

	public function pay_save()
	{
		$CT_Rmb_To_Cion = (int)$this->input->post('CT_Rmb_To_Cion', TRUE);
		$CT_Vip1_Rmb = (int)$this->input->post('CT_Vip1_Rmb', TRUE);
		$CT_Vip2_Rmb = (int)$this->input->post('CT_Vip2_Rmb', TRUE);
		$CT_Vip3_Rmb = (int)$this->input->post('CT_Vip3_Rmb', TRUE);
		$CT_Vip4_Rmb = (int)$this->input->post('CT_Vip4_Rmb', TRUE);
		$CT_Pay = (int)$this->input->post('CT_Pay', TRUE);
		$CT_Pay_ID = $this->input->post('CT_Pay_ID', TRUE);
		$CT_Pay_Key = $this->input->post('CT_Pay_Key', TRUE);

		$strs="<?php"."\r\n";
		$strs.="define('CT_Rmb_To_Cion',".$CT_Rmb_To_Cion.");  //1元=多少个金币   \r\n";
		$strs.="define('CT_Vip1_Rmb',".$CT_Vip1_Rmb.");  //1天VIP=多少RMB  \r\n";
		$strs.="define('CT_Vip2_Rmb',".$CT_Vip2_Rmb.");  //30天VIP=多少RMB  \r\n";
		$strs.="define('CT_Vip3_Rmb',".$CT_Vip3_Rmb.");  //180天VIP=多少RMB  \r\n";
		$strs.="define('CT_Vip4_Rmb',".$CT_Vip4_Rmb.");  //365天VIP=多少RMB  \r\n";
		$strs.="define('CT_Pay',".$CT_Pay.");  //支付宝开关    \r\n";
		$strs.="define('CT_Pay_ID','".$CT_Pay_ID."');  //合作者ID    \r\n";
		$strs.="define('CT_Pay_Key','".$CT_Pay_Key."');  //安全验效码KEY";
		
        //写文件
        if(!write_file(CTCMSPATH.'libs/Ct_Pay.php', $strs)){
             admin_msg('抱歉，修改失败，请检查文件写入权限~!',links('setting','pay'),'no');
		}else{
             admin_msg('恭喜您，配置修改成功~！',links('setting','pay'));
		}
	}

	public function email()
	{
 		$this->load->view('head.tpl');
		$this->load->view('setting_email.tpl');
	}

	public function email_save()
	{
		$CT_Smtphost = $this->input->post('CT_Smtphost', TRUE);
		$CT_Smtpport = (int)$this->input->post('CT_Smtpport', TRUE);
		$CT_Smtpuser = $this->input->post('CT_Smtpuser', TRUE);
		$CT_Smtppass = $this->input->post('CT_Smtppass', TRUE);
		$CT_Smtpmail = $this->input->post('CT_Smtpmail', TRUE);
		$CT_Smtpname = $this->input->post('CT_Smtpname', TRUE);


		$strs="<?php"."\r\n";
		$strs.="define('CT_Smtphost','".$CT_Smtphost."');  //SMTP服务器    \r\n";
		$strs.="define('CT_Smtpport','".$CT_Smtpport."');  ////SMTP端口    \r\n";
		$strs.="define('CT_Smtpuser','".$CT_Smtpuser."');  //SMTP帐号  \r\n";
		$strs.="define('CT_Smtppass','".$CT_Smtppass."');  //SMTP密码  \r\n";
		$strs.="define('CT_Smtpmail','".$CT_Smtpmail."');  //发送EMAIL  \r\n";
		$strs.="define('CT_Smtpname','".$CT_Smtpname."');  //发送者名称";
		
        //写文件
        if(!write_file(CTCMSPATH.'libs/Ct_Mail.php', $strs)){
             admin_msg('抱歉，修改失败，请检查文件写入权限~!',links('setting','email'),'no');
		}else{
             admin_msg('恭喜您，配置修改成功~！',links('setting','email'));
		}
	}

	//将路由规则生成至文件
	public function _route_file($uri) {
        $yuri = array(
              'list'=>'lists/index/[cid]/[page]',
              'show'=>'show/index/[id]',
              'play'=>'play/index/[id]/[zu]/[ji]',
              'comm'=>'comm/index/[cid]/[page]',
              'article'=>'comm/article/[id]/[page]',
              'topic'=>'topic/index/[page]',
              'topic_show'=>'topic/show/[id]'
	    );
		$string = '<?php'.PHP_EOL;
		$string.= 'if (!defined(\'BASEPATH\')) exit(\'No direct script access allowed\');'.PHP_EOL;
		$string.= '$route[\'whole/(.+).html\'] = \'whole/index/$1\'; '.PHP_EOL;
		if($uri) {
			arsort($uri);
			foreach ($uri as $key => $val1) {
				$val2 = $yuri[$key];
				if($key == 'list' ){
					$val1 = str_replace(array('[cid]','[page]'),array('(\d+)','(\d+)'),$val1);
					$val2 = str_replace(array('[cid]','[page]'),array('$1','$2'),$val2);
				    $string.= '$route[\''.$val1.'\'] = \''.$val2.'\'; '.PHP_EOL;
				}elseif($key == 'show' ){
					$val1 = str_replace(array('[id]'),array('(\d+)'),$val1);
					$val2 = str_replace(array('[id]'),array('$1'),$val2);
				    $string.= '$route[\''.$val1.'\'] = \''.$val2.'\'; '.PHP_EOL;
				}elseif($key == 'play' ){
					$val1 = str_replace(array('[id]','[zu]','[ji]'),array('(\d+)','(\d+)','(\d+)'),$val1);
					$val2 = str_replace(array('[id]','[zu]','[ji]'),array('$1','$2','$3'),$val2);
				    $string.= '$route[\''.$val1.'\'] = \''.$val2.'\'; '.PHP_EOL;
				}elseif($key == 'comm' ){
					$val1 = str_replace(array('[cid]','[page]'),array('([a-z0-9]+)','(\d+)'),$val1);
					$val2 = str_replace(array('[cid]','[page]'),array('$1','$2'),$val2);
				    $string.= '$route[\''.$val1.'\'] = \''.$val2.'\'; '.PHP_EOL;
				}elseif($key == 'article' ){
					$val1 = str_replace(array('[id]','[page]'),array('(\d+)','(\d+)'),$val1);
					$val2 = str_replace(array('[id]','[page]'),array('$1','$2'),$val2);
				    $string.= '$route[\''.$val1.'\'] = \''.$val2.'\'; '.PHP_EOL;
				}elseif($key == 'topic' ){
					$val1 = str_replace('[page]','(\d+)',$val1);
					$val2 = str_replace('[page]','$1',$val2);
				    $string.= '$route[\''.$val1.'\'] = \''.$val2.'\'; '.PHP_EOL;
				}elseif($key == 'topic_show' ){
					$val1 = str_replace('[id]','(\d+)',$val1);
					$val2 = str_replace('[id]','$1',$val2);
				    $string.= '$route[\''.$val1.'\'] = \''.$val2.'\'; '.PHP_EOL;
				}
			}
		}
		write_file(CTCMSPATH.'libs/Ct_Rewrite.php', $string);
	}
}
