<?php
/**
 * @Ctcms open source management system
 * @copyright 2008-2019 ctcms.cn. All rights reserved.
 * @Author:Cheng Kai Jie
 * @Dtime:2015-12-11
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Card extends Ctcms_Controller {
	function __construct(){
	    parent::__construct();
		//加载后台模型
		$this->load->model('admin');
        //当前模版
		$this->load->get_templates('admin');
		//判断是否登陆
		$this->admin->login();
	}

    //点卡列表
	public function index()
	{
 	    $page = intval($this->input->get('page'));
 	    $size = intval($this->input->get_post('size'));
 	    $ziduan = $this->input->get_post('ziduan',true);
 	    $key = $this->input->get_post('key',true);
 	    $cid = (int)$this->input->get_post('cid',true);
 	    $kstime = $this->input->get_post('kstime',true);
 	    $jstime = $this->input->get_post('jstime',true);
        if($page==0) $page=1;
        if($size==0) $size=15;

	    $data['key'] = $key;
	    $data['ziduan'] = $ziduan;
	    $data['kstime'] = $kstime;
	    $data['jstime'] = $jstime;
	    $data['page'] = $page;
	    $data['cid'] = $cid;
	    $data['size'] = $size;
		$where=$like='';
		if(!empty($kstime)){
			$where['totime>']=strtotime($kstime);
		}
		if(!empty($jstime)){
			$where['totime<']=strtotime($jstime);
		}
		if(!empty($key)){
			if($ziduan=='user'){
                 $uid = (int)getzd('user','name',$key);
				 $where['uid']=$uid;
			}elseif($ziduan=='cion' || $ziduan=='day'){
				 $where[$ziduan]=(int)$key;
			}else{
			     $like[$ziduan]=$key;
			}
		}
		if($cid>0){
			$where['cid']=$cid-1;
		}
        //总数量
	    $total = $this->csdb->get_nums('card',$where,$like);
		//每页数量
	    $per_page = $size;
		//总页数
	    $pagejs = ceil($total / $per_page);
	    if($total<$per_page) $per_page=$total;
		$limit=array($per_page,$per_page*($page-1));
        //记录数组
	    $data['array'] = $this->csdb->get_select('card','*',$where,'id DESC',$limit,$like);
		//当前链接
		$base_url = links('card','index',0,'size='.$size.'&cid='.$cid.'&ziduan='.$ziduan.'&key='.urlencode($key).'&kstime='.$kstime.'&jstime='.$jstime);
		//分页
	    $data['pages'] = admin_page($base_url,$total,$pagejs,$page);  //获取分页类
	    $data['nums'] = $total;
		$this->load->view('head.tpl',$data);
		$this->load->view('card_index.tpl');
	}
	//点卡编辑
	public function edit()
	{
        $this->load->view('head.tpl',$data);
        $this->load->view('card_add.tpl',$data);
	}
	//入库
	public function save()
	{
		$nums = (int)$this->input->post('nums');
		$cid = (int)$this->input->post('cid');
		$cion = (int)$this->input->post('cion');
		$day = (int)$this->input->post('day');
		if($nums==0) admin_msg('数量不能为0~！','javascript:history.back();','no');
		if($nums>100) admin_msg('一次最多只能生成100张~！','javascript:history.back();','no');
		if($cid==0 && $cion==0) admin_msg('金币数量不能为0~！','javascript:history.back();','no');
		if($cid==1 && $day==0) admin_msg('VIP天数不能为0~！','javascript:history.back();','no');

        $this->load->helper('string');
        for($i=0;$i<$nums;$i++){
		    $data['kh'] = random_string('alnum',20);
		    $data['pass'] = random_string('alnum',12);
		    $data['cid'] = $cid;
		    $data['cion'] = $cion;
		    $data['day'] = $day;
			$this->csdb->get_insert('card',$data);
		}
        echo "<script>
		      parent.layer.msg('恭喜您，成功增加张".$nums."卡~!');
              setInterval('parent.location.reload()',1000); 
              </script>";
	}
    //删除点卡
	public function del()
	{
 	    $ac = $this->input->get('ac');
 	    $id = $this->input->post('id');
		$res=$this->csdb->get_del('card',$id);
		if($ac=='all'){
			if($res){
                admin_msg('恭喜您，删除完成~！',links('card'));
			}else{
                admin_msg('删除失败，请稍后再试~！','javascript:history.back();','no');
			}
		}else{
		    $data['error']=$res ? 'ok' : '删除失败~!';
		    echo json_encode($data);
		}
	}

	//导出
	public function daochu(){
		$ids = $this->input->get_post('id');
		if(empty($ids)){
			admin_msg('请选择导出的卡密~！','javascript:history.back();','no');
		}
		$where['id'] = implode(',', $ids);
		$res = $this->csdb->get_select('card','*',$where);
		$txt = '';
		foreach ($res as $k=>$row) {
			if($k == 0){
				$txt = $row->kh." ".$row->pass;
			}else{
				$txt .= "\r\n".$row->kh." ".$row->pass;
			}
		}
		$this->load->helper('download');
		$name = 'card_'.date('Y-m-d H:i:s').'.txt';
		force_download($name, $txt);
	}
}