<?php
/**
 * @Ctcms open source management system
 * @copyright 2008-2019 ctcms.cn. All rights reserved.
 * @Author:Cheng Kai Jie
 * @Dtime:2015-12-11
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Player extends Ctcms_Controller {

	function __construct(){
	    parent::__construct();
		//加载后台模型
		$this->load->model('admin');
        //当前模版
		$this->load->get_templates('admin');
		//判断是否登陆
		$this->admin->login();
	}

	//播放器列表
	public function index()
	{
 	    $page = intval($this->input->get('page'));
        if($page==0) $page=1;

	    $data['page'] = $page;
        //总数量
	    $total = $this->csdb->get_nums('player');
		//每页数量
	    $per_page = 15; 
		//总页数
	    $pagejs = ceil($total / $per_page);
	    if($total<$per_page) $per_page=$total;
	    $limit=array($per_page,$per_page*($page-1));
        //记录数组
	    $data['array'] = $this->csdb->get_select('player','*','','xid ASC',$limit);
		//当前链接
		$base_url = links('player','index');
		//分页
	    $data['pages'] = admin_page($base_url,$total,$pagejs,$page);  //获取分页类
	    $data['nums'] = $total;
		$this->load->view('head.tpl',$data);
		$this->load->view('player_index.tpl');
	}

	//增加编辑
	public function edit()
	{
 	    $id = intval($this->input->get('id'));
		if($id==0){
            $data['id'] = 0;
            $data['name'] = '';
            $data['text'] = '';
            $data['bs'] = '';
            $data['js'] = '';
            $data['xid'] = 0;
		}else{
            $data = $this->csdb->get_row_arr("player","*",array('id'=>$id)); 
		}
        $this->load->view('head.tpl',$data);
        $this->load->view('player_edit.tpl',$data);
	}

	//修改
	public function save()
	{
		$id = (int)$this->input->post('id');
		$data['name'] = $this->input->post('name',true);
		$data['bs'] = $this->input->post('bs',true);
		$data['text'] = $this->input->post('text',true);
		$data['js'] = str_encode($this->input->post('js'));
		$data['xid'] = (int)$this->input->post('xid',true);
		if(empty($data['name']) || empty($data['bs']) || empty($data['js'])){
            admin_msg('标示和名称、代码不能为空~！','javascript:history.back();','no');
		}
		if($id==0){
            $this->csdb->get_insert('player',$data);
		}else{
            $this->csdb->get_update('player',$id,$data);
		}
        echo "<script>
		      parent.layer.msg('恭喜您，操作成功~!');
		      setInterval('parent.location.reload()',1000); 
              </script>";
	}

    //删除
	public function del()
	{
 	    $id = $this->input->post('id');
		$res=$this->csdb->get_del('player',$id);
		$data['error']=$res ? 'ok' : '删除失败~!';
		echo json_encode($data);
	}
}