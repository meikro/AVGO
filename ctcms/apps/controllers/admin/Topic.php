<?php
/**
 * @Ctcms open source management system
 * @copyright 2008-2019 ctcms.cn. All rights reserved.
 * @Author:Cheng Kai Jie
 * @Dtime:2015-12-11
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Topic extends Ctcms_Controller {

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
			$where['addtime>']=strtotime($kstime);
		}
		if(!empty($jstime)){
			$where['addtime<']=strtotime($jstime);
		}
		if(!empty($key)){
			if($ziduan=='id'){
				$where[$ziduan]=(int)$key;
			}else{
				$like[$ziduan]=$key;
			}
		}

        //总数量
	    $total = $this->csdb->get_nums('topic',$where,$like);
		//每页数量
	    $per_page = 15; 
		//总页数
	    $pagejs = ceil($total / $per_page);
	    if($total<$per_page) $per_page=$total;
		$limit=array($per_page,$per_page*($page-1));
        //记录数组
	    $data['array'] = $this->csdb->get_select('topic','*',$where,'addtime DESC',$limit,$like);
		//当前链接
		$base_url = links('topic','index');
		//分页
	    $data['pages'] = admin_page($base_url,$total,$pagejs,$page);  //获取分页类
	    $data['nums'] = $total;
		$this->load->view('head.tpl',$data);
		$this->load->view('topic_index.tpl');
	}

	//专辑增加编辑
	public function edit()
	{
 	    $id = intval($this->input->get('id'));
		if($id==0){
		     $data['id'] = 0;
		     $data['name'] = '';
		     $data['pic'] = '';
		     $data['tpic'] = '';
			 $data['text'] = '';
			 $data['hits']= 0;
		     $data['skin'] = 'topic-show.html';
		}else{
		     $row = $this->csdb->get_row("topic","*",array('id'=>$id)); 
		     $data['id'] = $id;
		     $data['name'] = $row->name;
		     $data['pic'] = $row->pic;
		     $data['tpic'] = $row->tpic;
		     $data['text'] = $row->text;
		     $data['hits'] = $row->hits;
		     $data['skin'] = $row->skin;
		}
        $this->load->view('head.tpl',$data);
        $this->load->view('topic_edit.tpl',$data);
	}

	//修改入库
	public function save()
	{
		$id = (int)$this->input->post('id');
		$data['name'] = $this->input->post('name',true);
		$data['pic'] = $this->input->post('pic',true);
		$data['tpic'] = $this->input->post('tpic',true);
		$data['text'] = $this->input->post('text',true);
		$data['skin'] = $this->input->post('skin',true);
		$data['hits'] = (int)$this->input->post('hits');
		$addtime = (int)$this->input->post('addtime');
		if(empty($data['name'])){
            admin_msg('专题标题不能为空~！','javascript:history.back();','no');
		}

		if($id==0){
			$data['addtime'] = time();
            $this->csdb->get_insert('topic',$data);
		}else{
			if($addtime==1) $data['addtime'] = time();
            $this->csdb->get_update('topic',$id,$data);
		}
        echo "<script>
		    parent.layer.msg('恭喜您，操作成功~!');
		    setInterval('parent.location.reload()',1000); 
        </script>";
	}

    //删除
	public function del()
	{
 	    $ac = $this->input->get('ac');
		$id = $this->input->post('id');
		$res = $this->csdb->get_del('topic',$id);	
		if($ac == 'all'){
			if($res){
                admin_msg('恭喜您，删除完成~！',links('topic'));
			}else{
                admin_msg('删除失败，请稍后再试~！','javascript:history.back();','no');
			}
		}else{
		    $data['error']=$res ? 'ok' : '删除失败~!';
		    echo json_encode($data);
		}
	}
}