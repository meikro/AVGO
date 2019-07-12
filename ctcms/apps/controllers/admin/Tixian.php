<?php
/**
 * @Ctcms open source management system
 * @copyright 2008-2019 ctcms.cn. All rights reserved.
 * @Author:Cheng Kai Jie
 * @Dtime:2015-12-11
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Tixian extends Ctcms_Controller {

	function __construct(){
	    parent::__construct();
		//加载后台模型
		$this->load->model('admin');
        //当前模版
		$this->load->get_templates('admin');
		//判断是否登陆
		$this->admin->login();
	}

    //分销记录
	public function index()
	{
 	    $page = intval($this->input->get('page'));
 	    $pid = intval($this->input->get_post('pid'));
 	    $ziduan = $this->input->get_post('ziduan',true);
 	    $key = $this->input->get_post('key',true);
 	    $kstime = $this->input->get_post('kstime',true);
 	    $jstime = $this->input->get_post('jstime',true);
        if($page==0) $page=1;

	    $data['key'] = $key;
	    $data['ziduan'] = $ziduan;
	    $data['kstime'] = $kstime;
	    $data['jstime'] = $jstime;
	    $data['page'] = $page;
	    $data['pid'] = $pid;

		$where=$like='';
		if(!empty($kstime)){
			$where['addtime>']=strtotime($kstime);
		}
		if(!empty($jstime)){
			$where['addtime<']=strtotime($jstime);
		}
		if($pid>0){
			$where['pid']=$pid-1;
		}

		if(!empty($key)){
			if($ziduan=='user'){
                $uid = (int)getzd('user','id',$key,'name');
				$where['uid']=$uid;
			}elseif($ziduan=='uid'){
				$where['uid']=(int)$key;
			}else{
				$like['pay']=$key;
			}
		}

        //总数量
	    $total = $this->csdb->get_nums('tixian',$where,$like);
		//每页数量
	    $per_page = 15; 
		//总页数
	    $pagejs = ceil($total / $per_page);
	    if($total<$per_page) $per_page=$total;
		$limit=array($per_page,$per_page*($page-1));
        //记录数组
	    $data['array'] = $this->csdb->get_select('tixian','*',$where,'addtime DESC',$limit,$like);
		//当前链接
		$base_url = links('tixian','index',0,'pid='.$pid.'&ziduan='.$ziduan.'&key='.urlencode($key).'&kstime='.$kstime.'&jstime='.$jstime);
		//分页
	    $data['pages'] = admin_page($base_url,$total,$pagejs,$page);  //获取分页类
	    $data['nums'] = $total;
		$this->load->view('head.tpl',$data);
		$this->load->view('tixian_index.tpl');
	}

    //删除
	public function del()
	{
 	    $ac = $this->input->get('ac');
 	    $id = $this->input->post('id',true);
		$res=$this->csdb->get_del('tixian',$id);
		if($ac=='all'){
			if($res){
                admin_msg('恭喜您，删除完成~！',links('tixian'));
			}else{
                admin_msg('删除失败，请稍后再试~！','javascript:history.back();','no');
			}
		}else{
		    $data['error']=$res ? 'ok' : '删除失败~!';
		    echo json_encode($data);
		}
	}

    //打款确定
	public function init()
	{
 	    $id = (int)$this->input->get_post('id');
 	    $pid = (int)$this->input->get_post('pid');
 	    $err = $this->input->get_post('err',true);
 	    if($id>0){
 	    	$this->csdb->get_update('tixian',$id,array('pid'=>$pid,'err'=>$err));

 	    	$data['error'] = 'ok';
 	    }else{
 	    	$data['error'] = 'ID错误~!';
 	    }
 	    echo json_encode($data);
	}
}