<?php
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-11
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Pay extends Ctcms_Controller {

	public function __construct(){
		parent::__construct();
		//加载会员模型
		$this->load->model('user');
        //判断登陆
		$this->user->login();
        //当前模版
		$this->load->get_templates('user');
	}

	//在线充值金币
    public function index() {
		$data['ctcms_title'] = '在线充值金币 - '.Web_Name;
		$data['ctcms_formurl'] = links('user','pay/save');
		$row = $this->csdb->get_row_arr('user','*',array('id'=>$_SESSION['user_id']));
		//获取模板
		$str=load_file('pay.html','user');
		//全局解析
		$str=$this->parser->parse_string($str,$data,true,false);
		//当前会员数据
		$str=$this->parser->ctcms_tpl('user',$str,$str,$row);
		//IF判断解析
		$str=$this->parser->labelif($str);
		echo $str;
	}

	//点卡购买
    public function card() {
		$data['ctcms_title'] = '点卡在线充值 - '.Web_Name;
		$data['ctcms_formurl'] = links('user','pay/card_save');
		$row = $this->csdb->get_row_arr('user','*',array('id'=>$_SESSION['user_id']));
		//获取模板
		$str=load_file('pay-card.html','user');
		//全局解析
		$str=$this->parser->parse_string($str,$data,true,false);
		//当前会员数据
		$str=$this->parser->ctcms_tpl('user',$str,$str,$row);
		//IF判断解析
		$str=$this->parser->labelif($str);
		echo $str;
	}

	//充值点卡
    public function card_save() {
		 $kh = safe_replace($this->input->post('kh',true));
		 $pass = $this->input->post('pass',true);
		 if(empty($kh) || empty($pass)) msg_url('卡号和卡密不能为空~!','javascript:history.back();');
		 $row = $this->csdb->get_row_arr('card','*',array('kh'=>$kh,'pass'=>$pass));
		 if(!$row) msg_url('卡片不存在~!','javascript:history.back();');
		 if($row['uid']>0) msg_url('该卡片已经被使用~!','javascript:history.back();');
		 //会员信息
		 $rowu = $this->csdb->get_row_arr('user','cion,vip,viptime',array('id'=>$_SESSION['user_id']));
		 //增加金币或者VIP
		 if($row['cid']==1){ //VIP卡
             $editu['vip'] = 1;
             $editu['viptime'] = $rowu['vip']==1 ? $rowu['viptime']+$row['day']*86400 : time()+$row['day']*86400;
		 }else{  //金币卡
             $editu['cion'] = $rowu['cion']+$row['cion'];
		 }
         $res = $this->csdb->get_update('user',$_SESSION['user_id'],$editu);
         if($res){
		     //修改卡片状态
		     $edit['uid'] = $_SESSION['user_id'];
		     $edit['totime'] = time();
             $this->csdb->get_update('card',$row['id'],$edit);
             msg_url('恭喜您，充值成功~!',links('user','pay/cardlist'),'ok');
		 }else{
             msg_url('充值失败，请稍后再试~!','javascript:history.back();');
		 }
	}

    //提交订单
    public function save() {
		 $type= $this->input->get_post('type',true);
         $rmb = (int)$this->input->get_post('rmb',true);
         $day = (int)$this->input->get_post('day',true);
         $sid = (int)$this->input->get_post('sid',true);
		 if($type=='cion' && ($rmb<1 || $rmb>9999)) msg_url('充值金额错误~!','javascript:history.back();');
		 if($type=='yue' && $day==0) msg_url('充值时间错误~!','javascript:history.back();');
		 if($sid>4) $sid=0;
		 //包月计算价格
		 if($type == 'yue'){
             if($day==1){
				 $rmb = CT_Vip1_Rmb;
             }elseif($day==30){
				 $rmb = CT_Vip2_Rmb;
             }elseif($day==180){
				 $rmb = CT_Vip3_Rmb;
             }elseif($day==365){
				 $rmb = CT_Vip4_Rmb;
			 }else{
				 msg_url('充值时间错误~!','javascript:history.back();');
			 }
		 }
		 //判断金币购买VIP
		 if($sid==0 && $type=='yue'){
			 $cion = $rmb*CT_Rmb_To_Cion;
			 //会员信息
			 $rowu = $this->csdb->get_row_arr('user','cion,vip,viptime',array('id'=>$_SESSION['user_id']));
			 //判断金币是否足够
			 if($rowu['cion']<$cion){
				  msg_url('您当前的金币不够支付'.$day.'天的VIP会员~!','javascript:history.back();');
			 }
			 //增加VIP
			 $editu['cion'] = $rowu['cion']-$cion;
			 $editu['vip'] = 1;
			 $editu['viptime'] = $rowu['vip']==1 ? $rowu['viptime']+$day*86400 : time()+$day*86400;
			 $res = $this->csdb->get_update('user',$_SESSION['user_id'],$editu);
             msg_url('<font color=red>升级VIP会员成功~!</font>',links('user'),'ok');
		 }
         //记录订单
		 $add['dingdan'] = date('YmdHis').rand(1111,9999);
		 $add['rmb'] = $rmb;
		 $add['sid'] = $sid;
		 $add['cid'] = $type=='cion' ? 0 : 1;
		 $add['day'] = $type=='cion' ? 0 : $day;
		 $add['uid'] = $_SESSION['user_id'];
		 $add['addtime'] = time();
		 $res = $this->csdb->get_insert('pay',$add);
		 if($res){
		     //直接跳转
			 header("location:".links('pay','topay',$res));
		 }else{
             msg_url('订单记录失败~!','javascript:history.back();');
		 }
	}

	//我的卡片记录
    public function cardlists($page=0) {
		$page=(int)$page;
		if($page==0) $page=(int)$this->input->get('page');
		if($page==0) $page=1;
		$data['ctcms_title'] = '我的点卡 - '.Web_Name;
		$row = $this->csdb->get_row_arr('user','*',array('id'=>$_SESSION['user_id']));
		//获取模板
		$str=load_file('pay-cardlist.html','user');
        //预先解析分页标签
		preg_match_all('/{ctcms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/ctcms:\1}/',$str,$page_arr);
		if(!empty($page_arr) && !empty($page_arr[3])){
            //每页数量
			$per_page = (int)$page_arr[3][0];
			//组装SQL数据
			$sql = "select {field} from ".CT_SqlPrefix."card where uid=".$_SESSION['user_id'];
			$sqlstr = $this->parser->ctcms_sql($page_arr[1][0],$page_arr[2][0],$page_arr[0][0],$page_arr[4][0],$sql);
			//总数量
			$total = $this->csdb->get_sql_nums($sqlstr);
			//总页数
	        $pagejs = ceil($total / $per_page);
			if($total<$per_page) $per_page=$total;
			$sqlstr .= ' limit '.$per_page*($page-1).','.$per_page;
			$str = $this->parser->ctcms_skins($page_arr[1][0],$page_arr[2][0],$page_arr[0][0],$page_arr[4][0],$str, $sqlstr);
            //解析分页
			$pagenum = getpagenum($str);
			$pagearr = get_page($total,$pagejs,$page,$pagenum,'user','pay/cardlists'); 
			$pagearr[] = $per_page;$pagearr[] = $total;$pagearr[] = $pagejs;$pagearr[] = $page;
			$str = getpagetpl($str,$pagearr);
		}
		//全局解析
		$str=$this->parser->parse_string($str,$data,true,false);
		//当前会员数据
		$str=$this->parser->ctcms_tpl('user',$str,$str,$row);
		//IF判断解析
		$str=$this->parser->labelif($str);
		echo $str;
	}

	//我的充值记录
    public function lists($page=0) {
		$page=(int)$page;
		if($page==0) $page=(int)$this->input->get('page');
		if($page==0) $page=1;
		$data['ctcms_title'] = '我的充值记录 - '.Web_Name;
		$row = $this->csdb->get_row_arr('user','*',array('id'=>$_SESSION['user_id']));
		//获取模板
		$str=load_file('pay-list.html','user');
        //预先解析分页标签
		preg_match_all('/{ctcms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/ctcms:\1}/',$str,$page_arr);
		if(!empty($page_arr) && !empty($page_arr[3])){
            //每页数量
			$per_page = (int)$page_arr[3][0];
			//组装SQL数据
			$sql = "select {field} from ".CT_SqlPrefix."pay where uid=".$_SESSION['user_id'];
			$sqlstr = $this->parser->ctcms_sql($page_arr[1][0],$page_arr[2][0],$page_arr[0][0],$page_arr[4][0],$sql);
			//总数量
			$total = $this->csdb->get_sql_nums($sqlstr);
			//总页数
	        $pagejs = ceil($total / $per_page);
			if($total<$per_page) $per_page=$total;
			$sqlstr .= ' limit '.$per_page*($page-1).','.$per_page;
			$str = $this->parser->ctcms_skins($page_arr[1][0],$page_arr[2][0],$page_arr[0][0],$page_arr[4][0],$str, $sqlstr);
            //解析分页
			$pagenum = getpagenum($str);
			$pagearr = get_page($total,$pagejs,$page,$pagenum,'user','pay/lists'); 
			$pagearr[] = $per_page;$pagearr[] = $total;$pagearr[] = $pagejs;$pagearr[] = $page;
			$str = getpagetpl($str,$pagearr);
		}
		//全局解析
		$str=$this->parser->parse_string($str,$data,true,false);
		//当前会员数据
		$str=$this->parser->ctcms_tpl('user',$str,$str,$row);
		//IF判断解析
		$str=$this->parser->labelif($str);
		echo $str;
	}
}