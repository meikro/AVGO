<?php
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-11
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Coll extends Ctcms_Controller {

	function __construct(){
	    parent::__construct();
		//加载后台模型
		$this->load->model('admin');
        //当前模版
		$this->load->get_templates('admin');
		//判断是否登陆
		$this->admin->login();
		//加载数据库连接
        $this->load->database();
	}

    //文章列表
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
        /*文章标题和id的转换*/
        if($ziduan == 'title'){
        	if(!empty($key)){
				$like2[$ziduan]=$key;
				$qtid = $this->csdb->get_select('comm','id','','addtime DESC','100',$like2);
	            $tid = array();
				foreach ($qtid as $row) {
					$tid[]=$row->id;
				}
				if(!empty($tid)){
					$where['tid'] = implode(',',$tid);
				}else{
					$where['tid'] = 0;
				}
			}
        }else{
        	if(!empty($key)){
				$like[$ziduan]=$key;
			}
        }

		if(!empty($kstime)){
			$where['regtime>']=strtotime($kstime);
		}
		if(!empty($jstime)){
			$where['regtime<']=strtotime($jstime);
		}

        //总数量
	    $total = $this->csdb->get_nums('coll',$where,$like);
		//每页数量
	    $per_page = 15; 
		//总页数
	    $pagejs = ceil($total / $per_page);
	    if($total<$per_page) $per_page=$total;
	    if($page>$pagejs) $page=$pagejs;
		$limit=array($per_page,$per_page*($page-1));
        //记录数组
	    $data['array'] = $this->csdb->get_select('coll','*',$where,'addtime DESC',$limit,$like);
	    foreach ($data['array'] as $k => $value) {
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

	    	$query = $this->csdb->get_row('comm','*',$value->tid);
	    	$value->title = $query->title;
	    }
		//当前链接
		$base_url = links('coll','index',0,'ziduan='.$ziduan.'&key='.urlencode($key).'&kstime='.$kstime.'&jstime='.$jstime);
		
		//分页
	    $data['pages'] = admin_page($base_url,$total,$pagejs,$page);  //获取分页类
	    $data['nums'] = $total;
		$this->load->view('head.tpl',$data);
		$this->load->view('coll_index.tpl');
	}

    //删除点赞
	public function del()
	{
 	    $ac = $this->input->get('ac');
 	    $id = $this->input->post('id');
 	    $query = $this->csdb->get_row('coll','*',$id);
		$res=$this->csdb->get_del('coll',$id);
		if($res){
			 $this->db->query("update ".CT_SqlPrefix."comm set collnum=collnum-1 where id=".$query->tid."");
		}
		if($ac=='all'){
			if($res){
                admin_msg('恭喜您，删除完成~！',links('coll'));
			}else{
                admin_msg('删除失败，请稍后再试~！','javascript:history.back();','no');
			}
		}else{
		    $data['error']=$res ? 'ok' : '删除失败~!';
		    echo json_encode($data);
		}
	}
}