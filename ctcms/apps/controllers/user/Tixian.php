<?php
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-11
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Tixian extends Ctcms_Controller {

	public function __construct(){
		parent::__construct();
		//加载会员模型
		$this->load->model('user');
        //判断登陆
		$this->user->login();
        //当前模版
		$this->load->get_templates('user');
	}

	//提现申请
    public function index() {
		$data['ctcms_title'] = '申请提现 - '.Web_Name;
		$data['ctcms_formurl'] = links('user','tixian/save');
		$row = $this->csdb->get_row_arr('user','*',array('id'=>$_SESSION['user_id']));
		$row['tx_yh'] = '';
		$row['tx_xm'] = '';
		$row['tx_kh'] = '';
		$row['tx_city'] = '';
		$sql = "SELECT pay FROM ".CT_SqlPrefix."tixian WHERE uid=".$_SESSION['user_id']." ORDER BY id LIMIT 1";
		$row2 = $this->db->query($sql)->row_array();
		if($row2){
			$arr = explode('<br>', $row2['pay']);
			$bank = str_replace('银行：', '', $arr[0]);
			$barr = explode('-', $bank);
			$row['tx_yh'] = $barr[0];
			$row['tx_xm'] = str_replace('收款人：', '', $arr[1]);
			$row['tx_kh'] = str_replace('卡号：', '', $arr[2]);
			$row['tx_city'] = isset($barr[1]) ? $barr[1] : '';
		}
		//获取模板
		$str=load_file('tixian.html','user');
		//全局解析
		$str=$this->parser->parse_string($str,$data,true,false);
		//当前会员数据
		$str=$this->parser->ctcms_tpl('user',$str,$str,$row);
		//IF判断解析
		$str=$this->parser->labelif($str);
		echo $str;
	}

	//我的提现记录
    public function lists($page=0) {
		$page=(int)$page;
		if($page==0) $page=(int)$this->input->get('page');
		if($page==0) $page=1;
		$data['ctcms_title'] = '我的提现记录 - '.Web_Name;

		$user = safe_replace($this->input->get_post('user',true));

		$row = $this->csdb->get_row_arr('user','*',array('id'=>$_SESSION['user_id']));
		//获取模板
		$str=load_file('tixian-list.html','user');
        //预先解析分页标签
		preg_match_all('/{ctcms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/ctcms:\1}/',$str,$page_arr);
		if(!empty($page_arr) && !empty($page_arr[3])){
            //每页数量
			$per_page = (int)$page_arr[3][0];
			//组装SQL数据
			$sql = "select {field} from ".CT_SqlPrefix."tixian where uid=".$_SESSION['user_id'];
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
			$pagearr = get_page($total,$pagejs,$page,$pagenum,'user','tixian/lists'); 
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

	//提现入库
    public function save() {
    	$rmb = (float)$this->input->post('rmb',true);
    	$yh = $this->input->post('yh',true);
    	$xm = $this->input->post('xm',true);
    	$kh = $this->input->post('kh',true);
    	$city = $this->input->post('city',true);
    	if(empty($yh) || empty($xm) || empty($kh)) msg_url('银行、收款人、收款账号不能为空~!','javascript:history.back();');
    	if($yh != '支付宝' && empty($city)) msg_url('银行开户地址不能为空~!','javascript:history.back();');
    	if($rmb < User_Fc_Tx) msg_url('单次提现金额不能低于'.User_Fc_Tx.'元','javascript:history.back();');
    	$row = $this->csdb->get_row_arr('user','rmb',array('id'=>$_SESSION['user_id']));
    	if($rmb > $row['rmb']) msg_url('你的可提现金额不足'.User_Fc_Tx.'元','javascript:history.back();');

    	if($yh != '支付宝') $yh .= '-'.$city;
    	$add['rmb'] = $rmb;
    	$add['uid'] = $_SESSION['user_id'];
    	$add['pid'] = 0;
    	$add['pay'] = '银行：'.$yh.'<br>收款人：'.$xm.'<br>卡号：'.$kh;
    	$add['addtime'] = time();
 		$res = $this->csdb->get_insert('tixian',$add);
		if($res){
			$this->csdb->get_update('user',$_SESSION['user_id'],array('rmb'=>$row['rmb']-$rmb));
			msg_url('提现提交完成，请等待站长给你打款~!',links('user','tixian/lists'),'ok');
		}else{
            msg_url('订单记录失败~!','javascript:history.back();');
		}
    }
}