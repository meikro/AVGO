<?php
/** * 
@Ctcms open source management system * 
@copyright 2008-2016 chshcms.com. All rights reserved. * 
@Author:Cheng Kai Jie * 
@Dtime:2015-12-11 
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Pl extends Ctcms_Controller {
	
	function __construct(){	    
		parent::__construct();
	}

	//评论列表
	public function index()	{
		$page = (int)$this->input->get_post('page'); //页数
		$size = (int)$this->input->get_post('size'); //数量
		if($page == 0) $page = 1;
		if($size == 0) $size = 12;
		$did = (int)$this->input->get_post('id');
		if($did == 0){
			echo json_encode(array('code'=>1,'msg'=>'视频ID为空'));
			exit;
		}
		$sql = 'select * from '.CT_SqlPrefix.'pl where did='.$did.' and fid=0 order by id desc';
		$total = $this->csdb->get_sql_nums($sql);
		$pagejs = ceil($total / $size);
	    $sql .= ' limit '.$size*($page-1).','.$size;
		//输出
		$array['code'] = 0;
		$data = $this->csdb->get_sql($sql,1);
		foreach ($data as $key => $value) {
			$data[$key]['upic'] = getpic('');
			$data[$key]['unichen'] = '佚名';
			if($value['uid'] > 0){
				$user = $this->csdb->get_row('user','name,nichen,pic',array('id'=>$value['uid']));
				if($user){
					$data[$key]['upic'] = getpic($user->pic);
					$data[$key]['unichen'] = empty($user->nichen) ? $user->name : $user->nichen;
				}
			}
			if(substr($data[$key]['upic'],0,12)=='/attachment/') $data[$key]['upic'] = 'http://'.Web_Url.$data[$key]['upic'];
			//获取下级评论
			$reply = $this->csdb->get_select('pl','*',array('fid'=>$data[$key]['id']),'id DESC',100,'',1);
			foreach ($reply as $k => $v) {
				$reply[$k]['upic'] = getpic('');
				$reply[$k]['unichen'] = '佚名';
				if($v['uid'] > 0){
					$user2 = $this->csdb->get_row('user','name,nichen,pic',array('id'=>$v['uid']));
					if($user2){
						$reply[$k]['upic'] = getpic($user2->pic);
						$reply[$k]['unichen'] = empty($user2->nichen) ? $user2->name : $user2->nichen;
					}
				}
				if(substr($reply[$k]['upic'],0,12)=='/attachment/') $reply[$k]['upic'] = 'http://'.Web_Url.$reply[$k]['upic'];
				$reply[$k]['addtime'] = date('Y-m-d H:i:s',$reply[$k]['addtime']);
				unset($reply[$k]['did'],$reply[$k]['fid']);
			}
			$data[$key]['addtime'] = date('Y-m-d H:i:s',$data[$key]['addtime']);
			$data[$key]['reply'] = $reply;
			unset($data[$key]['did'],$data[$key]['fid']);
		}
		$array['data'] = $data;
		echo json_encode($array);
	}

	//赞评论
	public function zan(){
		$uid = (int)$this->input->get_post('uid');
		$token = $this->input->get_post('token');
		$did = (int)$this->input->get_post('id');
		if($did==0){
			echo json_encode(array('code'=>1,'msg'=>'参数ID错误'));
			exit;
		}
		if(!$this->islog($uid,$token)){
			echo json_encode(array('code'=>1,'msg'=>'请先登录'));
			exit;
		}
		$row = $this->csdb->get_row_arr('pl','ding',array('id'=>$did));
		if(!$row){
			echo json_encode(array('code'=>1,'msg'=>'评论不存在~!'));
			exit;
		}
		$row = $this->csdb->get_row_arr('pl_zan','id',array('did'=>$did,'uid'=>$uid));
		if(!$row){
			$this->db->query("update ".CT_SqlPrefix."pl set ding=ding+1 where id=".$did);
			echo json_encode(array('code'=>0,'msg'=>'点赞成功'));
		}else{
			echo json_encode(array('code'=>1,'msg'=>'你已经赞过了'));
		}
	}

	//新增评论
	public function add(){
		$uid = (int)$this->input->get_post('uid');
		$token = $this->input->get_post('token');
		$did = (int)$this->input->get_post('id');
		$fid = (int)$this->input->get_post('fid');
		$text = $this->input->get_post('text',true);
		if($did==0){
			echo json_encode(array('code'=>1,'msg'=>'视频ID错误'));
			exit;
		}
		$row = $this->csdb->get_row_arr('vod','id',array('id'=>$did));
		if(!$row){
			echo json_encode(array('code'=>1,'msg'=>'视频不存在~!'));
			exit;
		}
		if(!$this->islog($uid,$token)){
			echo json_encode(array('code'=>1,'msg'=>'请先登录'));
			exit;
		}
		//入库
		$add['uid'] = $uid;
		$add['did'] = $did;
		$add['fid'] = $fid;
		$add['text'] = $text;
		$add['addtime'] = time();
		$res = $this->csdb->get_insert('pl',$add);
		if($res){
			echo json_encode(array('code'=>0,'msg'=>'评论成功~!'));
		}else{
			echo json_encode(array('code'=>1,'msg'=>'数据异常，请重试~!'));
		}
	}

	//删除评论
	public function del(){
		$id = (int)$this->input->get_post('id');
		$uid = (int)$this->input->get_post('uid');
		$token = $this->input->get_post('token');
		if($id == 0){
			echo json_encode(array('code'=>1,'msg'=>'参数ID错误'));
			exit;
		}
		if(!$this->islog($uid,$token)){
			echo json_encode(array('code'=>1,'msg'=>'请先登录'));
			exit;
		}
		$user_id = getzd('pl','uid',$id);
		if($user_id == $uid){
			$this->csdb->get_del('pl',$id);
			//删除下面所有回复
			$this->csdb->get_del('pl',$id,'fid');
			$str = "ok";
		}else{
			$str = "没有权限操作";
		}
		echo json_encode(array('code'=>0,'msg'=>$str));
	}

	//判断是否登陆
	private function islog($uid,$token){
		if($uid==0 || empty($token)) return false;
		$row = $this->csdb->get_row_arr('user','id,name,pass',array('id'=>$uid));
		if(!$row || md5($row['id'].$row['name'].$row['pass'].CT_Encryption_Key) != $token){
			return false;
		}else{
			return true;
		}
	}

	//我的评论
	public function my()	{
		$page = (int)$this->input->get_post('page'); //页数
		$size = (int)$this->input->get_post('size'); //数量
		if($page == 0) $page = 1;
		if($size == 0) $size = 12;
		$uid = (int)$this->input->get_post('uid');
		$token = $this->input->get_post('token');
		if(!$this->islog($uid,$token)){
			echo json_encode(array('code'=>1,'msg'=>'请先登录'));
			exit;
		}
		$sql = 'select id,did,text,ding,addtime from '.CT_SqlPrefix.'pl where uid='.$uid.' order by id desc';
		$total = $this->csdb->get_sql_nums($sql);
		$pagejs = ceil($total / $size);
	    $sql .= ' limit '.$size*($page-1).','.$size;
		//输出
		$array['code'] = 0;
		$data = $this->csdb->get_sql($sql,1);
		foreach ($data as $key => $value) {
			$data[$key]['name'] = getzd('vod','name',$data[$key]['did']);
			$data[$key]['addtime'] = date('Y-m-d H:i:s',$data[$key]['addtime']);
		}
		$array['data'] = $data;
		echo json_encode($array);
	}
}