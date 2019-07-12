<?php
/**
 * @Ctcms open source management system
 * @copyright 2008-2019 ctcms.cn. All rights reserved.
 * @Author:Cheng Kai Jie
 * @Dtime:2018-01-23
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Pl extends Ctcms_Controller {

	function __construct(){
	    parent::__construct();
        //当前模版
		$this->load->get_templates();
		$this->load->library('parser');
		define('IS_LOG', true);
		$this->load->model('user');//加载会员模型
	}

	//评论列表
	public function index()
	{
		$did = (int)$this->input->get_post('did');
		$op = $this->input->get_post('op',true);
		$page = (int)$this->input->get_post('page');
		if($page==0) $page=1;
		if($did>0){
			//获取模板
			$str = $op == 'ajax' ? load_file('pl_ajax.html') : load_file('pl.html');
			$data = array();
			$data['did'] = $did;
			$data['pl_login'] = $this->user->login(1) ? 'ok' : 'no';
			$data['pl_savelink'] = links('pl','add');
			$data['pl_dellink'] = links('pl','del');
			$pagejs = 1;
            //预先解析分页标签
			preg_match_all('/{ctcms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/ctcms:\1}/',$str,$page_arr);
	        if(!empty($page_arr) && !empty($page_arr[3])){
				//每页数量
				$per_page = (int)$page_arr[3][0];
				//组装SQL数据
				$sql = 'select {field} from '.CT_SqlPrefix.'pl where did='.$did;
				$sqlstr = $this->parser->ctcms_sql($page_arr[1][0],$page_arr[2][0],$page_arr[0][0],$page_arr[4][0],$sql);
				//总数量
				$total = $this->csdb->get_sql_nums($sqlstr);
				//总页数
				$pagejs = ceil($total / $per_page);
				if($total<$per_page) $per_page=$total;
				$sqlstr .= ' limit '.$per_page*($page-1).','.$per_page;
				$str = $this->parser->ctcms_skins($page_arr[1][0],$page_arr[2][0],$page_arr[0][0],$page_arr[4][0],$str,$sqlstr);
				//解析分页
				$pagenum = getpagenum($str);
				$pagearr = get_page($total,$pagejs,$page,$pagenum,'pl','index',$did);
				$pagearr[] = $per_page;$pagearr[] = $total;$pagearr[] = $pagejs;$pagearr[] = $page;
				$str = getpagetpl($str,$pagearr);
			}
			//全局解析
			$str = $this->parser->parse_string($str,$data,true,false);
			//当前数据
			$row['id'] = $did;
			$row['did'] = $did;
	        $str=$this->parser->ctcms_tpl('pl',$str,$str,$row);
			//IF判断解析
	        $str=$this->parser->labelif($str);
		}else{
			$str = '视频ID为空';
		}
		echo json_encode(array('pagejs'=>$pagejs,'html'=>$str));
	}

	//新增评论
	public function add()
	{
		$did = (int)$this->input->get_post('did');
		$fid = (int)$this->input->get_post('fid');
		$text = $this->input->get_post('text',true);
		if($did == 0){
			$str = 'DID参数错误';
		}else{
			$row = $this->csdb->get_row("vod","id",array('id'=>$did));
			if(!$row){
				$str = '视频不存在';
			}else{
				if($this->user->login(1)){
					$add['uid'] = $_SESSION['user_id'];
					$add['did'] = $did;
					$add['fid'] = $fid;
					$add['text'] = $text;
					$add['addtime'] = time();
					$res = $this->csdb->get_insert('pl',$add);
					if($res){
						$str = 'ok';
					}else{
						$str = "数据异常，请重试";
					}
				}else{
					$str = '登录超时';
				}
			}
		}
		echo json_encode(array('msg'=>$str));
	}

	//删除评论
	public function del()
	{
		$id = (int)$this->input->get_post('id');
		if($this->user->login(1)){
			if($id >0){
				$uid = getzd('pl','uid',$id);
				if($uid == $_SESSION['user_id']){
					$this->csdb->get_del('pl',$id);
					//删除下面所有回复
					$this->csdb->get_del('pl',$id,'fid');
					$str = "ok";
				}else{
					$str = "没有权限操作";
				}
			}else{
				$str = "数据异常，请重试";
			}
		}else{
			$str = '登录超时';
		}
		echo json_encode(array('msg'=>$str));
	}
}