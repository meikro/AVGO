<?php
/** * 
@Ctcms open source management system *
@copyright 2008-2017 ctcms.cn. All rights reserved. *
@Author:Chi Tu * 
@Dtime:2016-11-09 
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Hct extends Ctcms_Controller {	
	function __construct(){	    
		parent::__construct();        
		//当前模版		
		$this->load->get_templates();
		$this->load->library('parser');
		//免登入接口密码,默认为1234，请自行修改
		$this->pass='1234';	
	}    
	//分类列表	
	public function lists()	{        
		echo "<select name='list'>";              
		$sqlstr="select id,name from ".CT_SqlPrefix."class";		      
		$result=$this->db->query($sqlstr);              
		foreach ($result->result() as $row) {                   
			echo "<option value='".$row->id."'>".$row->name."</option>\n";			  
		}        
		echo '</select>';	
	}    
	//入库	
	public function ruku()	{        
		//判断密码		
		$pass=$this->input->get_post('pass',TRUE);        
		if($this->pass=='1234' || $pass!=$this->pass){            
			exit('密码错误');        
		}             
		//------------------------------------------//		
		$data['name']=$this->input->post('name',TRUE); //[标签:名称]		
		$data['pic']=$this->input->post('pic',TRUE); //[标签:图片]		
		$data['cid']=$this->input->post('cid',TRUE);//[标签:分类id]
		$data['daoyan']=$this->input->post('daoyan',TRUE);//[标签:导演]		
		$data['zhuyan']=$this->input->post('zhuyan',TRUE);//[标签:主演]		
		$data['type']=$this->input->post('type',TRUE);//[标签:类型]		
		$data['diqu']=$this->input->post('diqu',TRUE);//[标签:地区]		
		$data['yuyan']=$this->input->post('yuyan',TRUE);//[标签:语言]		
		$data['year']=$this->input->post('year',TRUE);//[标签:年份]		
		$data['state']=$this->input->post('state',TRUE);//[标签:状态]		
		$data['text']=$this->input->post('text',TRUE);//[标签:介绍]		
		$play=$this->input->post('play',TRUE);//[标签:来源]		
		$url=$this->input->post('url');//[标签:地址]		      
		//组装地址	              
		$arr = explode("\n",$url);
		$parr = array();
		//判断播放器来源
		$row=$this->db->query("select id from ".CT_SqlPrefix."player where bs='".$play."'")->row(); 
		for($i=0;$i<count($arr);$i++){
			if(!empty($arr[$i])){
			    $parr[] = ($row) ? $arr[$i] : $arr[$i].'&type='.$play;
			}
		}
		$data['url'] = ($row)? $play.'###'.implode("\n",$parr) : 'ydisk###'.implode("\n",$parr);
		//开始处理数据	    
		if(empty($data['name'])){
			echo "数据不完整";		
		}else{		       
			//判断数据是否相同
			$row=$this->db->query("select id,url from ".CT_SqlPrefix."vod where name='".$data['name']."' and cid=".$data['cid']."")->row();               
			if($row){ //存在同名数据则修改
				$s=0;						
				if($data['url'] != $row->url){
					$vod2['url']=$this->isdata($data['url'],$row->url);
					$vod2['addtime']=time();
					$vod2['state']=$data['state'];
					$s++;	
				}   
				if($s>0){								
					$this->db->where('id',$row->id);
					$this->db->update('vod',$vod2);
					echo("数据存在,覆盖成功");
				}else{
					echo "数据相同,跳过";
				}
			}else{ //不存在则新增   
				$data['addtime']=time();  
				$this->db->insert('vod',$data); 
				$id=$this->db->insert_id();  
				if($id>0){       
					echo("增加成功"); 
				}else{              
					echo("增加失败");
				}			   
			}		
		}	
	}

	//合并新老数据
	public function isdata($xpurl,$ypurl)
	{
	    $arr1 = $arr2 = array();
		$xarr = explode("#ctcms#",$xpurl);
		$yarr = explode("#ctcms#",$ypurl);
		$xcount = count($xarr);
		$ycount = count($yarr);
		for($i=0;$i<$xcount;$i++){
	        $ly = explode("###",$xarr[$i]);
	        $arr1[$ly[0]][] = $xarr[$i];
		}
		for($i=0;$i<$ycount;$i++){
	        $ly = explode("###",$yarr[$i]);
	        $arr2[$ly[0]][] = $yarr[$i];
		}
		foreach ($arr1 as $key => $value) {
			if(isset($arr2[$key])){
				foreach ($arr1[$key] as $k => $v) {
					if(isset($arr2[$key][$k])){
						$a1 = str_replace($key.'###', '', $arr1[$key][$k]);
						$v1 = explode("\n", $a1);
						$a2 = str_replace($key.'###', '', $arr2[$key][$k]);
						$v2 = explode("\n", $a2);
						if(sizeof($v1) < sizeof($v2)){
							$arr1[$key][$k] = $arr2[$key][$k];
						}
					}
				}
				$arr1[$key] = $arr1[$key] + $arr2[$key];
			}
		}
		$newarr = $arr1 + $arr2;
		$xinarr = array();
		foreach ($newarr as $k=>$v) {
			foreach ($v as $n=>$vs) {
		        $xinarr[] = $vs;
			}
		}
		$str=implode('#ctcms#',$xinarr);
		return $str;
	}
}