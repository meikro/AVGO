<?php
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-11
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Show extends Ctcms_Controller {

	function __construct(){
	    parent::__construct();
        //当前模版
		$this->load->get_templates();
		$this->load->library('parser');
	}

	public function index($id=0)
	{
		if((int)$id==0){
		    $id=(int)$this->input->get('id');
		}
		if($id==0) msg_url('参数错误',Web_Path);

	    $cache_id ="show_".$id;
	    if(!($this->cache->start($cache_id))){

                $row=$this->csdb->get_row_arr('vod','*',$id);
		        if(!$row) msg_url('视频不存在~!',Web_Path);
		        if($row['yid']==1) msg_url('视频审核中~!',Web_Path);
				//当前数据
				foreach ($row as $key => $val){
				    $data['vod_'.$key] = $val;
				}
                //站点标题
		        $data['ctcms_title'] = $row['name'].' - '.Web_Name;
				//当前CID
				$data['ctcms_cid'] = $row['cid'];
                //播放组
				$arr = $zuarr = array();
				if(!empty($row['url'])) $arr = explode("#ctcms#",$row['url']);
		        for($i=0;$i<count($arr);$i++){
			          $arr2 = explode("###",$arr[$i]);
                      $zuarr[$i]['ly'] = $arr2[0];
                      $zuarr[$i]['name'] = getzd('player','name',$arr2[0],'bs');
                      $zuarr[$i]['xu'] = $i+1;
			          $arr3 = explode("\n",$arr2[1]);
                      $zuarr[$i]['count'] = count($arr3);
			          $jiarr=array();
			          for($k=0;$k<count($arr3);$k++){
						  if(!empty($arr3[$k])){
								$arr4 = explode("$",$arr3[$k]);
								$jiarr[$k]['xu'] = $k+1;
								$jiarr[$k]['zu'] = $i+1;
								$jiarr[$k]['zly'] = $zuarr[$i]['ly'];
								$jiarr[$k]['zname'] = $zuarr[$i]['name'];
								$jiarr[$k]['name'] = $arr4[0];
								$jiarr[$k]['url'] = trim($arr4[1]);
								if($arr4[0]=='版权限制'){
									$jiarr[$k]['link'] = '###';
								}else{
									$jiarr[$k]['link'] = links('play','index',$id.'/'.$i.'/'.$k);
								}
						  }
			          }
			          $zuarr[$i]['ctcms_ji']=$jiarr;
		        }
		        $data['ctcms_zu']=$zuarr;
		        //视频播放器
		        $rowp = $this->csdb->get_row_arr('player','*',array('bs'=>$zuarr[0]['ly']));
		        $data['ctcms_player'] = str_replace("{url}",$zuarr[0]['ctcms_ji'][0]['url'],str_decode($rowp['js']));
				//当前播放地址
		        $data['ctcms_url'] = $zuarr[0]['ctcms_ji'][0]['url'];
                //下载组
				$arr = $zuarr = array();$CT_Down = unserialize(CT_Down);
				if(!empty($row['down'])) $arr = explode("#ctcms#",$row['down']);
		        for($i=0;$i<count($arr);$i++){
			          $arr2 = explode("###",$arr[$i]);
					  $ly = $arr2[0];
                      $zuarr[$i]['ly'] = $ly;
                      $zuarr[$i]['name'] = !empty($CT_Down[$ly])?$CT_Down[$ly]:'其他';
                      $zuarr[$i]['xu'] = $i+1;
			          $arr3 = explode("\n",$arr2[1]);
                      $zuarr[$i]['count'] = count($arr3);
			          $jiarr=array();
			          for($k=0;$k<count($arr3);$k++){
						  if(!empty($arr3[$k])){
								$arr4 = explode("$",$arr3[$k]);
								$jiarr[$k]['xu'] = $k+1;
								$jiarr[$k]['zu'] = $i+1;
								$jiarr[$k]['zly'] = $ly;
								$jiarr[$k]['zname'] = $zuarr[$i]['name'];
								$jiarr[$k]['name'] = $arr4[0];
								$jiarr[$k]['url'] = trim($arr4[1]);
						  }
			          }
			          $zuarr[$i]['ctcms_ji']=$jiarr;
		        }
		        $data['ctcms_down']=$zuarr;
		        //获取模板
		        $str = load_file('show.html');
				//全局解析
		        $str=$this->parser->parse_string($str,$data,true,false);
				//评论
				if(str_decode(Web_Pl) == '{pl}'){ //站内
					$pl = '<div id="ctcms_pl">加载中...</div><script src="'.Web_Path.'packs/js/pl.js"></script><script>var pl_did='.$id.',ctcms_pllink = "'.links('pl','index').'",ctcms_pladdlink = "'.links('pl','add').'";setTimeout(function(){get_pl(1);},1000);</script>';
		        	$str=str_replace('{ctcms_pl}',$pl,$str);
				}else{
		        	$str=str_replace('{ctcms_pl}',str_replace('{id}',$id,str_decode(Web_Pl)),$str);
				}
				//报错链接
				$row['errlink'] = links('error','index',$id);
				//当前数据
		        $str=$this->parser->ctcms_tpl('vod',$str,$str,$row);
				//IF判断解析
		        $str=$this->parser->labelif($str);
				echo $str;
		        $this->cache->end();
		}
	}
}
