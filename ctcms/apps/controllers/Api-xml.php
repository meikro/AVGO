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
		if(!defined('IS_Api') || IS_Api == 0){
			exit('站点已经关闭了API采集~');
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
		$cid = (int)$this->input->get('t');
		$day = (int)$this->input->get('h');
		$page = (int)$this->input->get('page');
		$ids = $this->input->get('ids',true);
		$play = str_checkhtml($this->input->get('play',true),1);
		$key = str_checkhtml($this->input->get('wd',true),1);
		if($page==0) $page=1;

		$where[] = "yid=0";
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
			if(strpos($ids,',') !== FALSE){
			    $where[] = "id in(".$ids.")";
		    }else{
			    $where[] = "id=".(int)$ids;
		    }
		}

		//组装SQL
		$sql = 'select id,cid,name,state,url,addtime from '.CT_SqlPrefix.'vod';
		if(!empty($where)) $sql.=' where '.implode(' and ',$where);
        //总数量
	    $total = $this->csdb->get_sql_nums($sql);
		//每页数量
	    $per_page = 50; 
		//总页数
	    $pagejs = ceil($total / $per_page);
	    if($total<$per_page && $total>0) $per_page=$total;
		$limit=$per_page*($page-1).','.$per_page;
        //记录数组
		$sql.=' order by addtime desc limit '.$limit;
		$vod = $this->csdb->get_sql($sql,1);

		header("Content-type:text/xml;charset=utf-8");
		echo '<?xml version="1.0" encoding="UTF-8"?><rss version="5.1"><list page="'.$page.'" pagecount="'.$pagejs.'" pagesize="'.$per_page.'" recordcount="'.$total.'">';
		foreach($vod as $k=>$v){
			echo '<video><last>'.date('Y-m-d H:i:s',$v['addtime']).'</last><id>'.$v['id'].'</id><tid>'.$v['cid'].'</tid><name><![CDATA['.$v['name'].']]></name><type>'.getzd('class','name',$v['cid']).'</type><dt>'.$this->ly($v['url']).'</dt><note><![CDATA['.$v['state'].']]></note></video>';
		}
		echo '</list><class>';
		$class = $this->csdb->get_select('class','id,name','','xid ASC',100,'',1);
		foreach ($class as $row) { 
			echo '<ty id="'.$row['id'].'">'.$row['name'].'</ty>';
		}
		echo '</class></rss>';
	}

	//视频详情
	public function show(){

		$data = array();
		$ids = $this->input->get('ids',true);
		$cid = (int)$this->input->get('t');
		$day = (int)$this->input->get('h');
		$page = (int)$this->input->get('page');
		$play = str_checkhtml($this->input->get('play',true),1);
		$key = str_checkhtml($this->input->get('key',true),1);
		if($page==0) $page=1;

		$where[] = "yid=0";
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
		$sql = 'select id,cid,name,pic,zhuyan,daoyan,type,yuyan,diqu,year,text,state,url,addtime from '.CT_SqlPrefix.'vod';
		if(!empty($where)) $sql.=' where '.implode(' and ',$where);
        //总数量
	    $total = $this->csdb->get_sql_nums($sql);
		//每页数量
	    $per_page = 50; 
		//总页数
	    $pagejs = ceil($total / $per_page);
	    if($total<$per_page && $total>0) $per_page=$total;
		$limit=$per_page*($page-1).','.$per_page;
        //记录数组
		$sql.=' order by addtime desc limit '.$limit;
		$vod = $this->csdb->get_sql($sql,1);

		header("Content-type:text/xml;charset=utf-8");
		echo '<?xml version="1.0" encoding="UTF-8"?><rss version="5.1"><list page="'.$page.'" pagecount="'.$pagejs.'" pagesize="'.$per_page.'" recordcount="'.$total.'">';
		foreach($vod as $k=>$v){
			echo '<video><last>'.date('Y-m-d H:i:s',$v['addtime']).'</last><id>'.$v['id'].'</id><tid>'.$v['cid'].'</tid><name><![CDATA['.$v['name'].']]></name><type>'.getzd('class','name',$v['cid']).'</type><pic>'.getpic($v['pic']).'</pic><lang>'.$v['yuyan'].'</lang><area>'.$v['diqu'].'</area><year>'.$v['year'].'</year><state>0</state><note><![CDATA['.$v['state'].']]></note><actor><![CDATA['.$v['zhuyan'].']]></actor><director><![CDATA['.$v['daoyan'].']]></director><dl>';

			$url = $this->url($v['url'],$play);
			foreach ($url as $ly=>$val) {
				echo '<dd flag="'.$ly.'"><![CDATA['.implode("#",$val).']]></dd>';
			}
			echo '</dl><des><![CDATA['.$row['text'].']]></des></video>';
		}
		echo '</list></rss>';
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

