<?php
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-11
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Hot extends Ctcms_Controller {

	function __construct(){
	    parent::__construct();
        //当前模版
		$this->load->get_templates();
		$this->load->library('parser');
	}

	public function index($cid=0,$sid=0,$page=0)
	{
		$cid = (int)$cid;
		$sid = (int)$sid;
		$page=(int)$page;
		if($cid == 0) $cid = (int)$this->input->get('id');
		if($sid == 0) $sid = (int)$this->input->get('sid');
		if($page==0) $page = (int)$this->input->get('page');
		if($page==0) $page=1;
		if($sid > 4) $sid = 0;
		$sort_arr = array('hits','rhits','zhits','yhits','dhits');
		$sort = $sort_arr[$sid];
	    $cache_id ="hot_".$cid."_".$sid."_".$page;
	    $fid = $cid > 0 ? getzd('class','fid',$cid) : 0;
	    if($fid == 0) $fid = $cid;
	    if(!($this->cache->start($cache_id))){
                //这里可以自定义数组内容到模板 ，$data['title'] = '内容';
			    $data = array(
			    	'ctcms_cid' => $cid,
			    	'ctcms_sid' => $sid,
			    	'ctcms_fid' => $fid,
					'ctcms_cname' => $cid > 0 ? getzd('class','name',$cid) : '全部'
			    );
		        //获取模板
		        $str = load_file('hot.html');
                //预先解析分页标签
				preg_match_all('/{ctcms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/ctcms:\1}/',$str,$page_arr);
		        if(!empty($page_arr) && !empty($page_arr[3])){
                      //每页数量
					  $per_page = (int)$page_arr[3][0];
				      //组装SQL数据
				      $sql = "select {field} from ".CT_SqlPrefix."vod";
				      if($cid>0){
						  $cids = getcid($cid); //获取分类下所有ID
						  if(strpos($cids,',') !== FALSE){
						      $sql .= " where cid in(".$cids.")";
						  }else{
						      $sql .= " where cid=".$cid;
						  }
					  }
				      $page_arr[2][0] = str_replace('[sort]', $sort, $page_arr[2][0]);
					  $sqlstr = $this->parser->ctcms_sql($page_arr[1][0],$page_arr[2][0],$page_arr[0][0],$page_arr[4][0],$sql);
					  //总数量
					  $total = $this->csdb->get_sql_nums($sqlstr);
					  //总页数
	                  $pagejs = ceil($total / $per_page);
					  if($total < $per_page) $per_page = $total;
					  $sqlstr .= ' limit '.$per_page*($page-1).','.$per_page;
					  $str = $this->parser->ctcms_skins($page_arr[1][0],$page_arr[2][0],$page_arr[0][0],$page_arr[4][0],$str, $sqlstr);
                      //解析分页
					  $pagenum = getpagenum($str);
					  $pagearr = get_page($total,$pagejs,$page,$pagenum,'index'); 
			          $pagearr[] = $per_page;$pagearr[] = $total;$pagearr[] = $pagejs;$pagearr[] = $page;
			          $str = getpagetpl($str,$pagearr);
				}
				//全局解析
		        $str=$this->parser->parse_string($str,$data,true);
				echo $str;
		        $this->cache->end();
		}
	}
}
