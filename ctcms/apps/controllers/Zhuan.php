<?php
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-11
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Zhuan extends Ctcms_Controller {

	function __construct(){
		parent::__construct();
	}

    //视频转码
	public function init(){
		$id = (int)$this->input->get('id');
		if($id == 0) exit;
		$row = $this->csdb->get_row('zhuanma','*',array('id'=>$id));
		if(!$row) exit;

		$m3u8_path = $row->m3u8_dir.$row->m3u8_path;
		if(!file_exists($m3u8_path)){
			if(!file_exists($row->path)){
				$this->csdb->get_del('zhuanma',$id);
				exit('视频源文件不存在');
			}
			//执行转码
			$this->db->update('zhuanma',array('sid'=>1),array('id'=>$id));
			//开始转码
			$this->load->library('xyz');
			$pic_path = $row->m3u8_dir.$row->pic_path;
			$res = $this->xyz->transcode($row->path,$m3u8_path,$pic_path);
			if($res == 'ok'){
				$this->csdb->get_del('zhuanma',$id);
			}
			echo $res;
		}else{
			$m3u8_neir = file_get_contents($m3u8_path);
			if(strpos($m3u8_neir,'#EXT-X-ENDLIST') !== false){
				//转换完毕
				$this->csdb->get_del('zhuanma',$id);
			}
		}
	}
}

