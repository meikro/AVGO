<?php
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-11
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Fenxiao extends Ctcms_Controller {

	public function __construct(){
		parent::__construct();
		//加载会员模型
		$this->load->model('user');
        //判断登陆
		$this->user->login();
        //当前模版
		$this->load->get_templates('user');
	}

	//我的下线
    public function index($page=0) {
		$page=(int)$page;
		if($page==0) $page=(int)$this->input->get('page');
		if($page==0) $page=1;
		$data['ctcms_title'] = '我的下线 - '.Web_Name;

		$row = $this->csdb->get_row_arr('user','*',array('id'=>$_SESSION['user_id']));
		//获取模板
		$str=load_file('fenxiao.html','user');
        //预先解析分页标签
		preg_match_all('/{ctcms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/ctcms:\1}/',$str,$page_arr);
		if(!empty($page_arr) && !empty($page_arr[3])){
            //每页数量
			$per_page = (int)$page_arr[3][0];
			//组装SQL数据
			$sql = "select {field} from ".CT_SqlPrefix."user where uid=".$_SESSION['user_id'];
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
			$pagearr = get_page($total,$pagejs,$page,$pagenum,'user','fenxiao/index');
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

	//我的分销记录
    public function lists($page=0) {
		$page=(int)$page;
		if($page==0) $page=(int)$this->input->get('page');
		if($page==0) $page=1;
		$data['ctcms_title'] = '我的分销记录 - '.Web_Name;

		$user = safe_replace($this->input->get_post('user',true));

		$row = $this->csdb->get_row_arr('user','*',array('id'=>$_SESSION['user_id']));
		//获取模板
		$str=load_file('fenxiao-list.html','user');
        //预先解析分页标签
		preg_match_all('/{ctcms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/ctcms:\1}/',$str,$page_arr);
		if(!empty($page_arr) && !empty($page_arr[3])){
            //每页数量
			$per_page = (int)$page_arr[3][0];
			//组装SQL数据
			$sql = "select {field} from ".CT_SqlPrefix."fxlist where uida=".$_SESSION['user_id'];
			if(!empty($user)){
				$uid = (int)getzd('user','id',$user,'name');
				$sql .= " and uidb=".$uid;
			}
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
			$pagearr = get_page($total,$pagejs,$page,$pagenum,'user','fenxiao/lists'); 
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