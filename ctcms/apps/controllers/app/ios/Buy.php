<?php
/** * 
@Ctcms open source management system * 
@copyright 2008-2016 chshcms.com. All rights reserved. * 
@Author:Cheng Kai Jie * 
@Dtime:2015-12-11 
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Buy extends Ctcms_Controller {
	
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

	//点播视频观看记录
	public function index(){
		$uid = $this->uid;
		$size = (int)$this->input->get_post('size'); //每页数量
		$page = (int)$this->input->get_post('page'); //当前页数
		if($size==0) $size = 12;
		if($page==0) $page = 1;

		$sql = 'select * from '.CT_SqlPrefix.'buy where uid='.$uid.' order by id desc';
		$total = $this->csdb->get_sql_nums($sql);
		$pagejs = ceil($total / $size);
	    $sql .= ' limit '.$size*($page-1).','.$size;
	    $res = $this->csdb->get_sql($sql,1);
	    $vod = array();
	    foreach ($res as $k=>$row) {
	    	$vod[$k] = $this->csdb->get_row_arr('vod','id,cid,name,pic,zhuyan,type',array('id'=>$row['did']));
	    	$vod[$k]['cion'] = $row['cion'];
	    	$vod[$k]['addtime'] = date('Y-m-d H:i:s',$row['addtime']);
			$vod[$k]['pic'] = getpic($vod[$k]['pic']);
			if(substr($vod[$k]['pic'],0,12)=='/attachment/') $vod[$k]['pic'] = 'http://'.Web_Url.$vod[$k]['pic'];
	    }
		//输出
		$arr['code'] = 0;
		$arr['data'] = $vod;
		echo json_encode($arr);
	}

	//购买视频
	public function add(){
		$uid = $this->uid;
		$id = (int)$this->input->get_post('id');
		if($id==0){
			echo json_encode(array('code'=>1,'msg'=>'视频ID错误'));exit;
		}
		$row = $this->csdb->get_row_arr('vod','cid,vip,cion',$id);
		if(!$row){
			echo json_encode(array('code'=>1,'msg'=>'视频不存在'));exit;
		}
		$rows = $this->csdb->get_row('buy','id',array('uid'=>$uid,'did'=>$id));
		if(!$rows){ //未购买
            $rowu = $this->user;
			$cion = $row['cion'];
			if($row['vip']==2){ //Vip会员5折
				if($rowu['vip']>0){
					$cion = ceil($cion * 0.5);
				}
			}
			if($rowu['cion']<$cion){
				echo json_encode(array('code'=>1,'msg'=>'金币不足，请充值~!'));exit;
			}
			//扣除金币
			$this->db->query("update ".CT_SqlPrefix."user set cion=cion-".$cion." where id=".$uid."");
			//写入购买记录
			$add['uid'] = $uid;
			$add['did'] = $id;
			$add['cid'] = $row['cid'];
			$add['cion'] = $cion;
			$add['addtime'] = time();
			$this->csdb->get_insert('buy',$add);
		}
		echo json_encode(array('code'=>0,'msg'=>'购买成功~!'));
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