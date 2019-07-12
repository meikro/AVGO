<?php
/**
 * @Ctcms open source management system
 * @copyright 2008-2019 ctcms.cn. All rights reserved.
 * @Author:Cheng Kai Jie
 * @Dtime:2015-12-11
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Topic extends Ctcms_Controller {

	function __construct(){
	    parent::__construct();
        //当前模版
		$this->load->get_templates();
		$this->load->library('parser');
	}
	
	//列表

	public function index($page=0)
	{
		if((int)$page==0){
		    $page=(int)$this->input->get('page');
		}
		if($page==0) $page=1;
	    $cache_id ="topic_list_".$page;
	    if(!($this->cache->start($cache_id))){
			    //网站标题
		        $data['ctcms_title'] = '视频专辑列表 - '.Web_Name;
		        //获取模板
		        $str = load_file('topic.html');
                //预先解析分页标签
				preg_match_all('/{ctcms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/ctcms:\1}/',$str,$page_arr);
		        if(!empty($page_arr) && !empty($page_arr[3])){
                      //每页数量
					  $per_page = (int)$page_arr[3][0];
					  $sqlstr = $this->parser->ctcms_sql($page_arr[1][0],$page_arr[2][0],$page_arr[0][0],$page_arr[4][0]);
					  //总数量
					  $total = $this->csdb->get_sql_nums($sqlstr);
					  //总页数
	                  $pagejs = ceil($total / $per_page);
					  if($total<$per_page) $per_page=$total;
					  $sqlstr .= ' limit '.$per_page*($page-1).','.$per_page;
				      $str=$this->parser->ctcms_skins($page_arr[1][0],$page_arr[2][0],$page_arr[0][0],$page_arr[4][0],$str,$sqlstr);
                      //解析分页
					  $pagenum = getpagenum($str);
					  $pagearr = get_page($total,$pagejs,$page,$pagenum,'topic','index'); 
			          $pagearr[] = $per_page;$pagearr[] = $total;$pagearr[] = $pagejs;$pagearr[] = $page;
			          $str = getpagetpl($str,$pagearr);
				}
				//全局解析
		        $str=$this->parser->parse_string($str,$data,true,false);
				//IF判断解析
		        $str=$this->parser->labelif($str);
				echo $str;
		        $this->cache->end();
		}
	}

    //内容
	public function show($id=0,$page=0)
	{
		//ID
		if($id==0) $id = (int)$this->input->get('id');
		if($id==0) msg_url('缺少专题ID',Web_Path);
		if((int)$page==0) $page=(int)$this->input->get('page');
		if($page==0) $page=1;
	    $cache_id ="topic_show_".$id."_".$page;
	    if(!($this->cache->start($cache_id))){

			$row=$this->csdb->get_row_arr('topic','*',array('id'=>$id));
			if(!$row) msg_url('专题不存在~!',Web_Path);
			//当前ID
			$data['ctcms_tid'] = $data['ctcms_cid'] = $row['id'];
			//标题
			$data['ctcms_title'] = $row['name'].' - '.Web_Name;
			//模版
			$skins = empty($row['show']) ? 'topic-show.html' : $row['show'];
			//获取模板
			$str = load_file($skins);
            //预先解析分页标签
			preg_match_all('/{ctcms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/ctcms:\1}/',$str,$page_arr);
	        if(!empty($page_arr) && !empty($page_arr[3])){
				//每页数量
				$per_page = (int)$page_arr[3][0];
				$sql = 'select {field} from '.CT_SqlPrefix.'vod where ztid='.$id;
				$sqlstr = $this->parser->ctcms_sql($page_arr[1][0],$page_arr[2][0],$page_arr[0][0],$page_arr[4][0],$sql);
				//总数量
				$total = $this->csdb->get_sql_nums($sqlstr);
				//总页数
				$pagejs = ceil($total / $per_page);
				if($total<$per_page) $per_page=$total;
				$sqlstr .= ' limit '.$per_page*($page-1).','.$per_page;
				$str=$this->parser->ctcms_skins($page_arr[1][0],$page_arr[2][0],$page_arr[0][0],$page_arr[4][0],$str,$sqlstr);
				//解析分页
				$pagenum = getpagenum($str);
				$pagearr = get_page($total,$pagejs,$page,$pagenum,'topic','show',$id); 
				$pagearr[] = $per_page;$pagearr[] = $total;$pagearr[] = $pagejs;$pagearr[] = $page;
				$str = getpagetpl($str,$pagearr);
			}
			//全局解析
			$str=$this->parser->parse_string($str,$data,true,false);
			//当前数据
			$str=$this->parser->ctcms_tpl('topic',$str,$str,$row);
			//IF判断解析
			$str=$this->parser->labelif($str);
			//增加人气
			if($page == 1){
				$arr = explode('</body>',$str);
				$jsurl = '<script type="text/javascript" src="'.links('hits','topic',$row['id']).'"></script></body>';
				echo $arr[0].$jsurl.$arr[1];
			}else{
				echo $str;
			}
			$this->cache->end();
		}
	}
}

