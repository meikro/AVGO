<?php
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-11
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Comm extends Ctcms_Controller {

	function __construct(){
	    parent::__construct();
		//加载后台模型
		$this->load->model('admin');
        //当前模版
		$this->load->get_templates('admin');
		//判断是否登陆
		$this->admin->login();
	}

    //文章列表
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
			$where['regtime>']=strtotime($kstime);
		}
		if(!empty($jstime)){
			$where['regtime<']=strtotime($jstime);
		}
		if(!empty($key)){
			$like[$ziduan]=$key;
		}
		if($cid>0){
			$where['cid']=$cid;
		}

        //总数量
	    $total = $this->csdb->get_nums('comm',$where,$like);
		//每页数量
	    $per_page = 15; 
		//总页数
	    $pagejs = ceil($total / $per_page);
	    if($total<$per_page) $per_page=$total;
	    if($page>$pagejs) $page=$pagejs;
		$limit=array($per_page,$per_page*($page-1));
        //记录数组
	    $data['array'] = $this->csdb->get_select('comm','*',$where,'addtime DESC',$limit,$like);
	    foreach ($data['array'] as $k => $value) {
	    	//获取圈子名称
	    	if($value->cid == 0){
	    		$value->circle = '未加入圈子';
	    	}else{
	    		$query = $this->csdb->get_row('circle','*',$value->cid);
	    		$value->circle = $query->name;
	    	}
	    	
	    	//获取作者昵称
	    	if($value->uid == 0){
	    		$value->nichen = '管理员';
	    	}else{
	    		$query1 = $this->csdb->get_row('user','*',$value->uid);
		    	if(empty($value->nichen)){
		    		$value->nichen = $query1->name;
		    	}else{
		    		$value->nichen = $query1->nichen;
		    	}
	    	}
	    	
	    	//获取收藏量
	    	//$value->coll = $this->csdb->get_nums('coll',array('tid'=>$value->id));
	    	//获取点赞量
	    	//$value->dz = $this->csdb->get_nums('dz',array('tid'=>$value->id));
	    	//获取回复量
	    	$value->msg = $this->csdb->get_nums('msg',array('tid'=>$value->id));
	    }
	    //圈子列表
	    $data['circle'] = $this->csdb->get_select('circle','*','','xid ASC');
		//当前链接
		if($cid == 0){
			$base_url = links('comm','index',0,'ziduan='.$ziduan.'&key='.urlencode($key).'&kstime='.$kstime.'&jstime='.$jstime);
		}else{
			$base_url = links('comm','index',0,'cid='.$cid.'&ziduan='.$ziduan.'&key='.urlencode($key).'&kstime='.$kstime.'&jstime='.$jstime);
		}
		
		//分页
	    $data['pages'] = admin_page($base_url,$total,$pagejs,$page);  //获取分页类
	    $data['nums'] = $total;
		$this->load->view('head.tpl',$data);
		$this->load->view('comm_index.tpl');
	}

	//文章编辑
	public function edit()
	{
 	    $id = intval($this->input->get('id'));
        if($id==0){
		    $data['title'] = ''; 
		    $data['content'] = ''; 
		    $data['cid'] = 1; 
		}else{
		    $row = $this->csdb->get_row_arr("comm","*",array('id'=>$id)); 
		    $data['title'] = $row['title'];
		    $data['content'] = $row['content']; 
		    $data['cid'] = $row['cid']; 
		}
		$data['id'] = $id; 
	    //获取圈子名称
	    $query = $this->csdb->get_select('circle','*','','xid ASC','100','',1);
	    $data['circle'] = $query;

        $this->load->view('head.tpl',$data);
        $this->load->view('comm_edit.tpl',$data);
	}

	//修改入库
	public function save()
	{
		$id = (int)$this->input->post('id');
		$data['title'] = $this->input->post('title',true);
		$data['content'] = remove_xss($this->input->post('content'));
		$data['cid'] = (int)$this->input->post('cid');

		if(empty($data['title']) || empty($data['content'])){
             admin_msg('标题、内容不能为空~！','javascript:history.back();','no');
		}
		
		if($id>0){
            $this->csdb->get_update('comm',$id,$data);
		}else{
			$data['uid'] = 1;
			$data['addtime'] = time();
            $this->csdb->get_insert('comm',$data);
		}
        echo "<script>
		      parent.layer.msg('恭喜您，操作成功~!');
              setInterval('parent.location.reload()',1000); 
              </script>";
	}

    //删除文章
	public function del()
	{
 	    $ac = $this->input->get('ac');
 	    $id = $this->input->post('id');
		$res=$this->csdb->get_del('comm',$id);
		if($res){
			$this->csdb->get_del('coll',$id,'tid');
			$this->csdb->get_del('dz',$id,'tid');
			$this->csdb->get_del('msg',$id,'tid');
		}
		if($ac=='all'){
			if($res){
                admin_msg('恭喜您，删除完成~！',links('comm'));
			}else{
                admin_msg('删除失败，请稍后再试~！','javascript:history.back();','no');
			}
		}else{
		    $data['error']=$res ? 'ok' : '删除失败~!';
		    echo json_encode($data);
		}
	}
}