<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Apiyun extends Ctcms_Controller {
	function __construct(){
	    parent::__construct();
	    $this->pass = '123456'; //云转码同步秘钥
	    $this->cid = 1; //入库视频分类ID
	}

	public function index()
	{
		if($_SERVER['HTTP_USER_AGENT'] != 'ctcms_yzm_api') exit('Ui Error');
		$pass = $this->input->post('key',true); //秘钥
		$ac = $this->input->post('ac',true); //提交类型，add新增，api状态
		$code = (int)$this->input->post('code',true); //请求状态,0新增，1转码完成，2转码失败
		$name = $this->input->post('name',true); //视频标题
		$m3u8url = $this->input->post('m3u8url',true); //m3u8播放地址
		$playurl = $this->input->post('playurl',true); //视频分享地址
		$jpgurl = $this->input->post('jpgurl',true); //视频图片地址
		$gifurl = $this->input->post('gifurl',true); //视频动态图片地址

		//判断秘钥是否正确
		if($pass != $this->pass) exit('Pass Error');

		//新增视频
		if($ac == 'add'){

			if(empty($name) || empty($jpgurl) || empty($m3u8url)){
				exit('Incomplete data');
			}
			$name = str_replace("'", "", $name);
			//判断重复视频
			$row = $this->csdb->get_row('vod','id',$name,'name');
			if($row) exit('Video already exists');

			$add['name'] = $name;
			$add['pic'] = $jpgurl;
			$add['url'] = 'ydisk###1$'.$m3u8url;
			$add['cid'] = $this->cid;
			$add['addtime'] = time();
			print_r($add);
			$res = $this->db->insert('vod',$add);
			if((int)$res == 0){
				echo $res;
				exit();
			}
		}else{ //转码状态提交
			if(empty($name)) exit('Incomplete name');
			//可根据您的程序操作视频转码状态
		}
		echo 'ok';
	}
}