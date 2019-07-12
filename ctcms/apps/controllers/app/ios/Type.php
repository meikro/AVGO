<?php
/** * 
@Ctcms open source management system * 
@copyright 2008-2016 chshcms.com. All rights reserved. * 
@Author:Cheng Kai Jie * 
@Dtime:2015-12-11 
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Type extends Ctcms_Controller {
	
	function __construct(){	    
		parent::__construct();   
	}

	//分类列表
	public function index()	{
		$size = (int)$this->input->get_post('size'); //数量
		$vsize = (int)$this->input->get_post('vsize'); //视频数量
		if($size == 0) $size = 12;
		if($vsize > 50) $vsize = 50;
		$wh = array('fid'=>0);
		$id = (int)$this->input->get_post('id');
		if($id>0){
			$wh = array('fid'=>$id);
		}
		$data = $this->csdb->get_select('class','id,name',$wh,'xid ASC',$size,'',1);
		if($vsize > 0){
			foreach ($data as $k => $v) {
				$cid = $v['fid'] == 0 ? getcid($v['id']) : $v['id'];
				$vod = $this->csdb->get_select('vod','id,name,pic,info,state,type,hits,pf,vip,cion',array('cid'=>$cid),'id DESC',$vsize,'',1);
				foreach ($vod as $key => $value) {
					$vod[$key]['pic'] = getpic($vod[$key]['pic']);
					if(substr($vod[$key]['pic'],0,12)=='/attachment/') $vod[$key]['pic'] = 'http://'.Web_Url.$vod[$key]['pic'];
					$vod[$key]['hits'] = format_wan($vod[$key]['hits']);
				}
				$data[$k]['vod'] = $vod;
			}
		}
		$array['code'] = 0;
		$array['data'] = $data;
		echo json_encode($array);
	}  

	//类型列表
	public function lists(){
		$id = (int)$this->input->get_post('id');
		if($id==0){
			echo json_encode(array('code'=>1,'msg'=>'分类ID错误'));
			exit;
		}
		$row = $this->csdb->get_row_arr('class','fid',array('id'=>$id));
		if($row && $row['fid'] > 0) $id = $row['fid'];
		$array['code'] = 0;
		//类型
	    $arr = explode('###',Web_Type); 
		$arr1 = explode("\n",Web_Type);
		$newarr = array();
		for($i=0;$i<count($arr1);$i++){
			$arr2 = explode("#",$arr1[$i]);
			$cid = $arr2[0];
			$newarr[$cid] = $arr2[1];
		}
		$array['data']['type'] = empty($newarr[$id]) ? array() : explode('|',$newarr[$id]);
		$array['data']['diqu'] = explode('|',Web_Diqu);
		$array['data']['yuyan'] = explode('|',Web_Yuyan);
		$array['data']['year'] = explode('|',Web_Year);
		echo json_encode($array);
	}
}