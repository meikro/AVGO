<?php
/**
 * @Ctcms open source management system
 * @copyright 2008-2019 ctcms.cn. All rights reserved.
 * @Author:Cheng Kai Jie
 * @Dtime:2015-12-11
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Fav extends Ctcms_Controller {
	function __construct(){
	    parent::__construct();
		//加载后台模型
		$this->load->model('admin');
        //当前模版
		$this->load->get_templates('admin');
		//判断是否登陆
		$this->admin->login();
	}

    //列表
	public function index()
	{
 	    $page = intval($this->input->get('page'));
 	    $ziduan = $this->input->get_post('ziduan',true);
 	    $key = $this->input->get_post('key',true);
 	    $cid = (int)$this->input->get_post('cid',true);
 	    $kstime = $this->input->get_post('kstime',true);
 	    $jstime = $this->input->get_post('jstime',true);
        if($page==0) $page=1;
	    $data['key'] = $key;
	    $data['ziduan'] = $ziduan;
	    $data['kstime'] = $kstime;
	    $data['jstime'] = $jstime;
	    $data['page'] = $page;
	    $data['cid'] = $cid;

		$where=$like='';
		if(!empty($kstime)){
			$where['addtime>']=strtotime($kstime);
		}
		if(!empty($jstime)){
			$where['addtime<']=strtotime($jstime);
		}
		if(!empty($key)){
			if($ziduan=='name'){
                $row = $this->db->select('id')->like('name',$key)->get('vod')->row();
				if($row){
                    $where['did']=$row->id;
				}else{
                    $where['did']=0;
				}
			}elseif($ziduan=='user'){
                $uid = getzd('user','id',$key,'name');
				$where['uid']=(int)$uid;
			}else{
			    $where[$ziduan]=(int)$key;
			}
		}
		if($cid>0){
			$where['cid']=$cid;
		}
        //总数量
	    $total = $this->csdb->get_nums('fav',$where,$like);
		//每页数量
	    $per_page = 15; 
		//总页数
	    $pagejs = ceil($total / $per_page);
	    if($total<$per_page) $per_page=$total;
		$limit=array($per_page,$per_page*($page-1));
        //记录数组
	    $data['array'] = $this->csdb->get_select('fav','*',$where,'addtime DESC',$limit,$like);
		//当前链接
		$base_url = links('fav','index',0,'cid='.$cid.'&ziduan='.$ziduan.'&key='.urlencode($key).'&kstime='.$kstime.'&jstime='.$jstime);
		//分页
	    $data['pages'] = admin_page($base_url,$total,$pagejs,$page);  //获取分页类
	    $data['nums'] = $total;
		//分类
		$data['lists'] = $this->csdb->get_select('class','id,name',array('fid'=>0),'xid ASC');
		$this->load->view('head.tpl',$data);
		$this->load->view('fav_index.tpl');
	}

    //删除
	public function del()
	{
 	    $ac = $this->input->get('ac');
 	    $id = $this->input->post('id');
		$res=$this->csdb->get_del('fav',$id);
		if($ac=='all'){
			if($res){
                admin_msg('恭喜您，删除完成~！',links('fav'));
			}else{
                admin_msg('删除失败，请稍后再试~！','javascript:history.back();','no');
			}
		}else{
		    $data['error']=$res ? 'ok' : '删除失败~!';
		    echo json_encode($data);
		}
	}
}