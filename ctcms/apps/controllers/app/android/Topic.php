<?php
/** * 
@Ctcms open source management system * 
@copyright 2008-2016 chshcms.com. All rights reserved. * 
@Author:Cheng Kai Jie * 
@Dtime:2015-12-11 
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Topic extends Ctcms_Controller {	

	function __construct(){	    
		parent::__construct();        
	}

	//专题列表
	public function index()	{
		$wh = array();
		$size = (int)$this->input->get_post('size');
		if($size == 0) $size = 5;
		$vsize = (int)$this->input->get_post('vsize');
		if($vsize > 50) $vsize = 50;
		//$reco = (int)$this->input->get_post('reco');
		//if($reco > 0) $wh['tid'] = 1;
		$array['code'] = 0;
		$data = $this->csdb->get_select('topic','id,name,pic',$wh,'id ASC',$size,'',1);
		if($vsize > 0){
			foreach ($data as $k => $row) {
				$data[$k]['vod'] = $this->csdb->get_select('vod','id,name,pic,hits,info,state,type',array('ztid'=>$row['id']),'id DESC',$vsize,'',1);
			}
		}
		$array['data'] = $data;
		echo json_encode($array);
	}
}