<?php
/** * 
@Ctcms open source management system * 
@copyright 2008-2016 chshcms.com. All rights reserved. * 
@Author:Cheng Kai Jie * 
@Dtime:2015-12-11 
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Zan extends Ctcms_Controller {
	
	function __construct(){	    
		parent::__construct();
		$this->uid = (int)$this->input->get_post('uid',true);
		$token = $this->input->get_post('token',true);
		$this->user = $this->islog($this->uid,$token,1);
		if(!$this->user){
			echo json_encode(array('code'=>1,'msg'=>'未登录'));
			exit;
		}
	}

	//点赞记录
	public function index(){
		$uid = $this->uid;
		$size = (int)$this->input->get_post('size'); //每页数量
		$page = (int)$this->input->get_post('page'); //当前页数
		if($size==0) $size = 12;
		if($page==0) $page = 1;

		$sql = 'select did,addtime from '.CT_SqlPrefix.'zan where uid='.$uid.' order by id desc';
		$total = $this->csdb->get_sql_nums($sql);
		$pagejs = ceil($total / $size);
	    $sql .= ' limit '.$size*($page-1).','.$size;
	    $res = $this->csdb->get_sql($sql,1);
	    $vod = array();
	    foreach ($res as $k=>$row) {
	    	$vod[$k] = $this->csdb->get_row_arr('vod','id,cid,name,pic,zhuyan,type,vip,cion',array('id'=>$row['did']));
			$vod[$k]['pic'] = getpic($vod[$k]['pic']);
			if(substr($vod[$k]['pic'],0,12)=='/attachment/') $vod[$k]['pic'] = 'http://'.Web_Url.$vod[$k]['pic'];
			$vod[$k]['addtime'] = date('Y-m-d H:i:s',$row['addtime']);
	    }
		//输出
		$arr['code'] = 0;
		$arr['data'] = $vod;
		echo json_encode($arr);
	}

	//视频点赞取消点赞
	public function init(){
		$uid = $this->uid;
		$id = (int)$this->input->get_post('id');
		if($id==0){
			echo json_encode(array('code'=>1,'msg'=>'视频ID错误'));
			exit;
		}
		$row = $this->csdb->get_row('vod','cid,dhits',$id);
		if(!$row){
			echo json_encode(array('code'=>1,'msg'=>'视频不存在'));
			exit;
		}
		$rows = $this->csdb->get_row('zan','id',array('uid'=>$uid,'did'=>$id));
		if(!$rows){ //收藏
			$add['did'] = $id;
			$add['uid'] = $uid;
			$add['addtime'] = time();
			$this->csdb->get_insert('zan',$add);
			//增加点赞人气
			$this->csdb->get_update('vod',$id,array('dhits'=>($row->dhits+1)));
			echo json_encode(array('code'=>0,'data'=>array('sign'=>1,'msg'=>'恭喜您，点赞成功')));
			exit;
		}else{ //取消收藏
			$this->csdb->get_del('zan',$rows->id);
			//减少点赞人气
			$this->csdb->get_update('vod',$id,array('dhits'=>($row->dhits-1)));
			echo json_encode(array('code'=>0,'data'=>array('sign'=>0,'msg'=>'已取消点赞')));
			exit;
		}
	}

	//是否点赞
	public function is(){
		$uid = $this->uid;
		$id = (int)$this->input->get_post('id');
		if($id==0){
			echo json_encode(array('code'=>1,'msg'=>'视频ID错误'));exit;
		} 
		$row = $this->csdb->get_row('vod','cid',$id);
		if(!$row){
			echo json_encode(array('code'=>1,'msg'=>'视频不存在'));exit;
		}
		//判断收藏
		$rows = $this->csdb->get_row('zan','id',array('uid'=>$uid,'did'=>$id));
		if(!$rows){
			echo json_encode(array('code'=>0,'data'=>array('sign'=>0,'msg'=>'未赞')));
		}else{
			echo json_encode(array('code'=>0,'data'=>array('sign'=>1,'msg'=>'已赞')));
		}
	}

	//判断是否登陆
	private function islog($uid,$token,$sign=0){
		if($uid==0 || empty($token)) return 0;
		$row = $this->csdb->get_row_arr('user','*',array('id'=>$uid));
		if(!$row || md5($row['id'].$row['name'].$row['pass'].CT_Encryption_Key) != $token){
			return 0;
		}else{
			if($sign==0){
				return 1;
			}else{
				unset($row['pass']);
				return $row;
			}
		}
	}
}