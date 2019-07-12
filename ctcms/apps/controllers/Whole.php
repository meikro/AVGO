<?php
/**
 * @Ctcms open source management system
 * @copyright 2008-2019 ctcms.cn. All rights reserved.
 * @Author:Cheng Kai Jie
 * @Dtime:2015-12-11
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Whole extends Ctcms_Controller {

	function __construct(){
	    parent::__construct();
        //当前模版
		$this->load->get_templates();
		$this->load->library('parser');
	}

	public function index($key='')
	{
		if(empty($key)) $key = $this->input->get('key',true);
		$key = rawurldecode(get_bm($key));
		$arr = explode('_',$key); //分割,1分类ID、2地区、3类型、4语言、5时间、6清晰度、7状态、8收费、9排序、10分页
		$cid=$buy=$page=0;$diqu=$type=$year=$state=$info=$yuyan=$cname='';$sort='addtime';$order='desc';
		$sortarr = array('hits','yhits','zhits','rhits','addtime','id');
		if(!empty($arr[0])) $cid=(int)$arr[0];
		if(!empty($arr[1])) $diqu=safe_replace($arr[1]);
		if(!empty($arr[2])) $type=safe_replace($arr[2]);
		if(!empty($arr[3])) $yuyan=safe_replace($arr[3]);
		if(!empty($arr[4])) $year=safe_replace($arr[4]);
		if(!empty($arr[5])) $info=safe_replace($arr[5]);
		if(!empty($arr[6])) $state=safe_replace($arr[6]);
		if(!empty($arr[7])) $buy=$arr[7];
		if(!empty($arr[8])) $sort=$arr[8];
		if(!empty($arr[9])) $page=(int)$arr[9];
		if(!in_array($sort, $sortarr)) $sort='addtime';
		if($page==0) $page=1;
		$where = $cid.'_'.$diqu.'_'.$type.'_'.$yuyan.'_'.$year.'_'.$info.'_'.$state.'_'.$buy.'_'.$sort.'_';
		//分类名字
		if($cid>0){
			$rowc = $this->csdb->get_row_arr("class","*",array('id'=>$cid));
			$cname = $rowc['name'];
		}
		//分类父ID
		$fid = $cid > 0 ? getzd('class','fid',$cid) : 0;
		//当前搜索字段输出
		$data['ctcms_cid'] = $cid; //分类ID
		$data['ctcms_fid'] = $fid == 0 ? $cid : $fid; //分类父ID
		$data['ctcms_cname'] = $cname; //分类名字
		$data['ctcms_diqu'] = $diqu; //地区
		$data['ctcms_yuyan'] = $yuyan; //语言
		$data['ctcms_type'] = $type; //类型
		$data['ctcms_year'] = $year; //年份
		$data['ctcms_info'] = $info; //清晰度
		$data['ctcms_state'] = $state; //状态
		$data['ctcms_buy'] = $buy; //是否收费
		$data['ctcms_sort'] = $sort; //排序

        //网站标题
		if($cid==0){
			$data['ctcms_title'] = '视频智能检索 - '.Web_Name;
		}else{
			$data['ctcms_title'] = $cname.' - '.Web_Name;
			if(!empty($rowc['title'])) $data['ctcms_title'] = $rowc['title'];
			if(!empty($rowc['keywords'])) $data['ctcms_keywords'] = $rowc['keywords'];
			if(!empty($rowc['description'])) $data['ctcms_description'] = $rowc['description'];
		}

        //获取模板
        $str = load_file('whole.html');
        //预先解析分页标签
        preg_match_all('/{ctcms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/ctcms:\1}/',$str,$page_arr);
        if(!empty($page_arr) && !empty($page_arr[3])){
              //每页数量
        	  $per_page = (int)$page_arr[3][0];
	          //组装SQL数据
        	  $sql = 'select {field} from '.CT_SqlPrefix.'vod';
			  $warr = array();
			  if($cid>0){
				  $cids = getcid($cid); //获取分类下所有ID
				  if(strpos($cids,',') !== FALSE){
				      $warr[] = "cid in(".$cids.")";
				  }else{
				      $warr[] = "cid=".$cid;
				  }
			  }

			  if(!empty($diqu)) $warr[] = "diqu like '%".$diqu."%'";
			  if(!empty($type)) $warr[] = "type like '%".$type."%'";
			  if(!empty($year)) $warr[] = "year like '%".$year."%'";
			  if(!empty($info)) $warr[] = "info like '%".$info."%'";
			  if(!empty($yuyan)) $warr[] = "yuyan like '%".$yuyan."%'";
			  if(!empty($state)){
				  if($state=='全集' || $state=='完结'){
				      $warr[] = "(state like '%".$state."%' or state like '%完结%')";
				  }elseif($state=='更新' || $state=='预告'){
				      $warr[] = "state like '%".$state."%'";
				  }else{
				      $warr[] = "(state NOT like '%更新%' and state NOT like '%全集%' and state NOT like '%完结%')";
				  }
			  }
			  if($buy==1) $warr[] = "vip=0"; //免费
			  if($buy==2) $warr[] = "vip>0"; //收费
			  if($buy==3) $warr[] = "cion>0"; //点播
			  if($buy==4) $warr[] = "vip=3"; //包月
			  //组装条件
			  if(!empty($warr)) $sql.=' where '.implode(' and ',$warr);
			  //获取全局SQL解析
        	  $sqlstr = $this->parser->ctcms_sql($page_arr[1][0],$page_arr[2][0],$page_arr[0][0],$page_arr[4][0],$sql);
			  $sqlstr = current(explode('order by',$sqlstr)).'order by '.$sort.' desc';
        	  //总数量
        	  $total = $this->csdb->get_sql_nums($sqlstr);
        	  //总页数
	          $pagejs = ceil($total / $per_page);
        	  if($total<$per_page) $per_page=$total;
        	  $sqlstr .= ' limit '.$per_page*($page-1).','.$per_page;
			  //exit($sqlstr);
        	  $str = $this->parser->ctcms_skins($page_arr[1][0],$page_arr[2][0],$page_arr[0][0],$page_arr[4][0],$str, $sqlstr);
              //解析分页
        	  $pagenum = getpagenum($str);
        	  $pagearr = get_page($total,$pagejs,$page,$pagenum,'whole','index',0,$where);
			  $pagearr[] = $per_page;$pagearr[] = $total;$pagearr[] = $pagejs;$pagearr[] = $page;
			  $str = getpagetpl($str,$pagearr);
        }

        //全局解析
        $str = $this->parser->parse_string($str,$data,true,false);
		//解析智能检索链接
        preg_match_all('/\[whole:url\s+([0-9a-zA-Z]+)=(.*?)\]/',$str,$u_arr);
        for($i=0;$i<count($u_arr[0]);$i++){
            $wheres = whole_url($u_arr[1][$i],$u_arr[2][$i],$where);
			$str = str_replace($u_arr[0][$i],links('whole','index',0,$wheres),$str);
		}

		//IF判断解析
		$str=$this->parser->labelif($str);
		echo $str;
	}
}

