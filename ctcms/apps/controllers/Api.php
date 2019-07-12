<?php
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-11
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Api extends Ctcms_Controller {

	function __construct(){
		parent::__construct();
		header('Content-Type:application/json;Charset=utf-8');
		if(!defined('IS_Api') || IS_Api == 0){
			$arr['code'] = 0;
			$arr['msg'] = '站点已经关闭了API采集~';
			echo json_encode($arr);exit;
		}
	}

    //视频列表
	public function index(){
		$ac = $this->input->get('ac',true);
		if($ac=='show'){
			$this->show();
			exit;
		}
		$data = array();
		$cid = (int)$this->input->get('cid');
		$day = (int)$this->input->get('day');
		$page = (int)$this->input->get('page');
		$ziduan = $this->input->get('zd',true);
		$play = str_checkhtml($this->input->get('play',true),1);
		$key = str_checkhtml($this->input->get('key',true),1);
		if($ziduan!='url') $ziduan = 'url';
		if($page==0) $page=1;

		$where[] = "yid=0";
		if(!empty($play)) $where[] = $ziduan." like '%".$play."%'";
		if(!empty($key)) $where[] = "name like '%".$key."%'";
		if($cid>0){
			$cids = getcid($cid);
		    if(strpos($cids,',') !== FALSE){
			    $where[] = "cid in(".$cids.")";
		    }else{
			    $where[] = "cid=".$cid;
		    }
		}
		if($day>0){
			$time = time()-3600*$day;
			$where[]='addtime>'.$time;
		}

		//组装SQL
		$sql = 'select id,cid,name,state,'.$ziduan.',addtime from '.CT_SqlPrefix.'vod';
		if(!empty($where)) $sql.=' where '.implode(' and ',$where);
        //总数量
	    $total = $this->csdb->get_sql_nums($sql);
		//每页数量
	    $per_page = 30; 
		//总页数
	    $pagejs = ceil($total / $per_page);
	    if($total<$per_page && $total>0) $per_page=$total;
		$limit=$per_page*($page-1).','.$per_page;
        //记录数组
		$sql.=' order by addtime desc limit '.$limit;
		$vod = $this->csdb->get_sql($sql,1);
		foreach($vod as $k=>$v){
			$vod[$k]['cname'] = getzd('class','name',$v['cid']);
			$vod[$k]['laiy'] = $this->ly($v[$ziduan]);
			$vod[$k]['addtime'] = date('Y-m-d H:i:s',$v['addtime']);
			unset($vod[$k][$ziduan]);
		}
		$data['code'] = 1;
		$data['page'] = $page;
		$data['pagejs'] = $pagejs;
		$data['pagecount'] = $total;
		$data['pagesize'] = $per_page;
		$data['vod'] = $vod;
		$data['class'] = $this->csdb->get_select('class','id,name','','xid ASC',100,'',1);
		//print_r($data);exit;
		echo json_encode($data);
	}

	//视频详情
	public function show(){

		$data = array();
		$ids = $this->input->get('ids',true);
		$cid = (int)$this->input->get('cid');
		$day = (int)$this->input->get('day');
		$page = (int)$this->input->get('page');
		$ac = $this->input->get('ac',true);
		$play = str_checkhtml($this->input->get('play',true),1);
		$key = str_checkhtml($this->input->get('key',true),1);
		$ziduan = $ac=='down' ? 'down' : 'url';
		if($page==0) $page=1;

		$where[] = "yid=0";
		if(!empty($play)) $where[] = $ziduan." like '%".$play."%'";
		if(!empty($key)) $where[] = "name like '%".$key."%'";
		if($cid>0){
			$cids = getcid($cid);
		    if(strpos($cids,',') !== FALSE){
			    $where[] = "cid in(".$cids.")";
		    }else{
			    $where[] = "cid=".$cid;
		    }
		}
		if($day>0){
			$time = time()-3600*$day;
			$where[]='addtime>'.$time;
		}
		if(!empty($ids)){
			$arr = explode(',',$ids);
			$arr2 = array();
			for($i=0;$i<count($arr);$i++){
				if((int)$arr[$i]>0){
					$arr2[] = (int)$arr[$i];
				}
			}
			if(!empty($arr2)){
				$where[] = 'id in('.implode(',',$arr2).')';
			}
		}

		//组装SQL
		$sql = 'select id,cid,name,pic,zhuyan,daoyan,type,yuyan,diqu,year,text,state,'.$ziduan.' from '.CT_SqlPrefix.'vod';
		if(!empty($where)) $sql.=' where '.implode(' and ',$where);
        //总数量
	    $total = $this->csdb->get_sql_nums($sql);
		//每页数量
	    $per_page = 30; 
		//总页数
	    $pagejs = ceil($total / $per_page);
	    if($total<$per_page && $total>0) $per_page=$total;
		$limit=$per_page*($page-1).','.$per_page;
        //记录数组
		$sql.=' order by addtime desc limit '.$limit;
		$vod = $this->csdb->get_sql($sql,1);
		foreach($vod as $k=>$v){
			$vod[$k]['cname'] = getzd('class','name',$v['cid']);
			$vod[$k]['data'] = $this->url($v[$ziduan],$play);
			unset($vod[$k][$ziduan]);
		}
		$data['code'] = 1;
		$data['page'] = $page;
		$data['pagejs'] = $pagejs;
		$data['pagecount'] = $total;
		$data['pagesize'] = $per_page;
		$data['vod'] = $vod;
		//print_r($data);exit;
		echo json_encode($data);
	}

	//获取来源
	public function ly($url){
		$arr = explode('#ctcms#',$url);
		$bsarr = array();
		for($i=0;$i<count($arr);$i++){
			$parr = explode('###',$arr[$i]);
			$bsarr[] = $parr[0];
		}
		return implode('/',$bsarr);
	}

	//获取地址
	public function url($url,$ly=''){
		$narr = array();
		$arr = explode('#ctcms#',$url);
		for($i=0;$i<count($arr);$i++){
			$parr = explode('###',$arr[$i]);
			if(!empty($ly) && $parr[0]!=$ly) continue;
			$narr[$parr[0]] = explode("\n",$parr[1]);
		}
		return $narr;
	}
}

