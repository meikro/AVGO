<?php
/**
 * @Ctcms open source management system
 * @copyright 2008-2019 ctcms.cn. All rights reserved.
 * @Author:Cheng Kai Jie
 * @Dtime:2015-12-11
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Ctcms_Controller {

	function __construct(){
	    parent::__construct();
		//加载后台模型
		$this->load->model('admin');
        //当前模版
		$this->load->get_templates('admin');
		//判断是否登陆
		$this->admin->login();
	}

    //会员列表
	public function index()
	{
 	    $page = intval($this->input->get('page'));
 	    $ziduan = $this->input->get_post('ziduan',true);
 	    $key = $this->input->get_post('key',true);
 	    $vip = (int)$this->input->get_post('vip',true);
 	    $kstime = $this->input->get_post('kstime',true);
 	    $jstime = $this->input->get_post('jstime',true);
        if($page==0) $page=1;

	    $data['key'] = $key;
	    $data['ziduan'] = $ziduan;
	    $data['kstime'] = $kstime;
	    $data['jstime'] = $jstime;
	    $data['page'] = $page;
	    $data['vip'] = $vip;

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
		if($vip>0){
			$where['vip']=$vip-1;
		}

        //总数量
	    $total = $this->csdb->get_nums('user',$where,$like);
		//每页数量
	    $per_page = 15; 
		//总页数
	    $pagejs = ceil($total / $per_page);
	    if($total<$per_page) $per_page=$total;
		$limit=array($per_page,$per_page*($page-1));
        //记录数组
	    $data['array'] = $this->csdb->get_select('user','*',$where,'regtime DESC',$limit,$like);
		//当前链接
		$base_url = links('user','index',0,'vip='.$vip.'&ziduan='.$ziduan.'&key='.urlencode($key).'&kstime='.$kstime.'&jstime='.$jstime);
		//分页
	    $data['pages'] = admin_page($base_url,$total,$pagejs,$page);  //获取分页类
	    $data['nums'] = $total;
		$this->load->view('head.tpl',$data);
		$this->load->view('user_index.tpl');
	}

	//会员编辑
	public function edit()
	{
 	    $id = intval($this->input->get('id'));
        if($id==0){
		    $data['name'] = ''; 
		    $data['pass'] = ''; 
		    $data['email'] = ''; 
		    $data['tel'] = ''; 
		    $data['cion'] = 0; 
		    $data['rmb'] = 0.00; 
		    $data['vip'] = 0; 
			$data['viptime'] = '';
		}else{
		    $row = $this->csdb->get_row_arr("user","*",array('id'=>$id)); 
		    $data['name'] = $row['name']; 
		    $data['pass'] = $row['pass']; 
		    $data['email'] = $row['email']; 
		    $data['tel'] = $row['tel']; 
		    $data['cion'] = $row['cion']; 
		    $data['rmb'] = $row['rmb']; 
		    $data['vip'] = $row['vip']; 
		    $data['viptime'] = $row['viptime']; 
		}
		$data['id'] = $id; 
        $this->load->view('head.tpl',$data);
        $this->load->view('user_edit.tpl',$data);
	}

	//修改入库
	public function save()
	{
		$id = (int)$this->input->post('id');
		$pass = $this->input->post('pass');
		$viptime = $this->input->post('viptime');
		$data['name'] = $this->input->post('name',true);
		$data['email'] = $this->input->post('email',true);
		$data['tel'] = $this->input->post('tel');
		$data['cion'] = (int)$this->input->post('cion');
		$data['rmb'] = (float)$this->input->post('rmb');
		$data['vip'] = (int)$this->input->post('vip');
		if(!empty($pass)) $data['pass']=md5($pass);

		if($data['vip']==1){
			if(empty($viptime)) admin_msg('VIP到期时间不能为空~！','javascript:history.back();','no');
			$data['viptime'] = strtotime($viptime);
		}else{
			$data['viptime'] = 0;
		}

		if(empty($data['name']) || empty($data['email'])){
             admin_msg('账号、邮箱不能为空~！','javascript:history.back();','no');
		}

		if($id>0){
            $this->csdb->get_update('user',$id,$data);
		}else{
			$data['regtime']=time();
            $this->csdb->get_insert('user',$data);
		}
        echo "<script>
		      parent.layer.msg('恭喜您，操作成功~!');
              setInterval('parent.location.reload()',1000); 
              </script>";
	}

    //删除会员
	public function del()
	{
 	    $ac = $this->input->get('ac');
 	    $id = $this->input->post('id');
		$res=$this->csdb->get_del('user',$id);
		if($res){
            $this->csdb->get_del('buy',$id,'uid');
            $this->csdb->get_del('pay',$id,'uid');
		}
		if($ac=='all'){
			if($res){
                admin_msg('恭喜您，删除完成~！',links('user'));
			}else{
                admin_msg('删除失败，请稍后再试~！','javascript:history.back();','no');
			}
		}else{
		    $data['error']=$res ? 'ok' : '删除失败~!';
		    echo json_encode($data);
		}
	}
}