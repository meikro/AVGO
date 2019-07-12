<?php
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-11
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Error extends Ctcms_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('user');
	}

	public function index($id=0,$zu=0,$ji=0)
	{
		if((int)$id==0){
		    $id=(int)$this->input->get('id');
		    $zu=(int)$this->input->get('zu');
		    $ji=(int)$this->input->get('ji');
		}
		if($id==0) msg_url('参数错误',Web_Path);
		$zu = (int)$zu;
		$ji = (int)$ji;

		//判断上次报错时间
		if(isset($_SESSION['error_time']) && $_SESSION['error_time']>time()){
           msg_url('距离上次报错时间太短，视为灌水~!',Web_Path);
		}

        $row=$this->csdb->get_row_arr('vod','id,name',$id);
		if(!$row || $row['yid']==1) msg_url('视频不存在~!',Web_Path);

        //判断报错数据是否存在
        $rows=$this->csdb->get_row_arr('error','id,addtime',array('did'=>$id,'zu'=>$zu,'ji'=>$ji));
		$daytime = strtotime(date('Y-m-d'));
		if(!$rows || $rows['addtime'] < $daytime){
			//记录报错
			$add['did'] = $id;
			$add['name'] = $row['name'];
			$add['zu'] = $zu;
			$add['ji'] = $ji;
			$add['addtime'] = time();
			$this->csdb->get_insert('error',$add);
		}
		$_SESSION['error_time'] = time()+300;
        msg_url('<b><font color=#f30>感谢您，视频报错成功~!</font></b>','javascript:history.back();','ok');
	}
}

