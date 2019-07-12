<?php
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-11
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Circle extends Ctcms_Controller {

	function __construct(){
	    parent::__construct();
		//加载后台模型
		$this->load->model('admin');
        //当前模版
		$this->load->get_templates('admin');
		//判断是否登陆
		$this->admin->login();
	}

    //圈子列表
	public function index()
	{
 	    $page = intval($this->input->get('page'));
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

		$where=$like='';
		if(!empty($kstime)){
			$where['regtime>']=strtotime($kstime);
		}
		if(!empty($jstime)){
			$where['regtime<']=strtotime($jstime);
		}
		if(!empty($key)){
			$like[$ziduan]=$key;
		}

        //总数量
	    $total = $this->csdb->get_nums('circle',$where,$like);
		//每页数量
	    $per_page = 15; 
		//总页数
	    $pagejs = ceil($total / $per_page);
	    if($total<$per_page) $per_page=$total;
	    if($page>$pagejs) $page=$pagejs;
		$limit=array($per_page,$per_page*($page-1));
        //记录数组
	    $data['array'] = $this->csdb->get_select('circle','*',$where,'xid ASC',$limit,$like);
	    	
    	//获取文章量
    	foreach ($data['array'] as $k => $value) {
    		$value->commnum = $this->csdb->get_nums('comm',array('cid'=>$value->id));
    	}
		//当前链接
		$base_url = links('circle','index',0,'ziduan='.$ziduan.'&key='.urlencode($key).'&kstime='.$kstime.'&jstime='.$jstime);
		
		//分页
	    $data['pages'] = admin_page($base_url,$total,$pagejs,$page);  //获取分页类
	    $data['nums'] = $total;
		$this->load->view('head.tpl',$data);
		$this->load->view('circle_index.tpl');
	}

	//圈子编辑
	public function edit()
	{
 	    $id = intval($this->input->get('id'));
        if($id==0){
		    $data['xid'] = 1; 
		    $data['name'] = '';  
		}else{
		    $row = $this->csdb->get_row_arr("circle","*",array('id'=>$id)); 
		    $data['xid'] = $row['xid'];
		    $data['name'] = $row['name'];  
		}
		$data['id'] = $id; 

        $this->load->view('head.tpl',$data);
        $this->load->view('circle_edit.tpl',$data);
	}

	//修改入库
	public function save()
	{
		$id = (int)$this->input->post('id');
		$data['xid'] = $this->input->post('xid',true);
		$data['name'] = $this->input->post('name');

		if(empty($data['xid']) || empty($data['name'])){
             admin_msg('序号、名称不能为空~！','javascript:history.back();','no');
		}
		
		if($id>0){
            $this->csdb->get_update('circle',$id,$data);
		}else{
            $this->csdb->get_insert('circle',$data);
		}
        echo "<script>
		      parent.layer.msg('恭喜您，操作成功~!');
              setInterval('parent.location.reload()',1000); 
              </script>";
	}

    //删除圈子
	public function del()
	{
 	    $ac = $this->input->get('ac');
 	    $id = $this->input->post('id');
		$res=$this->csdb->get_del('circle',$id);
		if($res){
	        $this->csdb->get_del('comm',$id,'cid');
		}
		if($ac=='all'){
			if($res){
                admin_msg('恭喜您，删除完成~！',links('circle'));
			}else{
                admin_msg('删除失败，请稍后再试~！','javascript:history.back();','no');
			}
		}else{
		    $data['error']=$res ? 'ok' : '删除失败~!';
		    echo json_encode($data);
		}
	}
}