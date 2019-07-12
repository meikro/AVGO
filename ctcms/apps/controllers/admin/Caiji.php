<?php
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-11
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Caiji extends Ctcms_Controller {

	function __construct() {
	    parent::__construct();
		//加载后台模型
		$this->load->model('admin');
        //当前模版
		$this->load->get_templates('admin');
		//判断是否登陆
		$this->admin->login();
	}

    //资源库
	public function index()
	{
        $form = $this->input->get('form',true);
        $type = $this->input->get('type',true);
        $ac = $this->input->get('ac',true);
        $op = $this->input->get('op',true);
        $do = $this->input->get('do',true);
		$rid  = intval($this->input->get('rid'));
		if($form != 'json') $form = 'xml';
		if($type != 'down') $type = 'url';

		if($do=='caiji'){ //入库

            $api  = $this->input->get('api',TRUE);
            $page = intval($this->input->get('page'));
            $cid  = intval($this->input->get('cid'));
            $ac   = $this->input->get('ac',TRUE);
            $ops   = $this->input->get('op',TRUE);
            $key  = $keys = $this->input->get('key',TRUE);
            $ids  = $this->input->get('ids',TRUE);
            $k  = (int)$this->input->get('k',TRUE);
			if($page==0) $page=1;
			if($ops=='24') $ops='day';
			if($ops=='day'){
				$op=24;
			}elseif($ops=='week'){
			    $op=98;
			}else{
			    $op=0;
			}
			//采集
			if($api){
				$this->$form($api,$rid,$ac,$op,$cid,$key,$ids,$page,$type,$k);
			}else{
				admin_msg('<font color=red>API错误！</font>','javascript:history.go(-1);');
			}

		}elseif(!empty($ac)){  //资源库查看

    		$api  = $this->input->get('api',TRUE);
    		$page = intval($this->input->get('page'));
   		    $cid  = intval($this->input->get('cid'));
   		    $k    = intval($this->input->get('k'));
    		$ac   = $this->input->get('ac',TRUE);
    		$op   = $this->input->get('op',TRUE);
    		$key  = $this->input->get('key',TRUE);
    		$gf  = intval($this->input->get('gf'));
			if($page==0) $page=1;
			if($op=='all') $op=0;

    		if($api){
				if($form == 'xml'){
					$data = $this->xml_list($api,$rid,$ac,$op,$cid,$key,0,$page,$type,$k);
				}else{
					$data = $this->json_list($api,$rid,$ac,$op,$cid,$key,0,$page,$type,$k);
				}
				$data['key'] = $key;
				$data['op']  = $op;
				$data['cid'] = $cid;
				$data['rid'] = $rid;
				$data['k'] = $k;
				$data['gf'] = $gf == 1 ? 'gf' : '';
				$data['api'] = urlencode($api);
				$data['ac'] = $ac;
				$data['type'] = $type;
				$data['page'] = $page;

				$data['LIST'] = require_once(CTCMSPATH.'libs/Ct_Bind.php');
				$this->load->view('head.tpl',$data);
				$this->load->view('caiji_list.tpl');

    		}else{
        		 admin_msg('<font color=red>API错误！</font>','javascript:history.go(-1);');
			}

		}elseif(empty($do)){  //资源库首页
			$json = htmlall('http://api.ctcms.cn/vodapi/ctcms_v3.json?v='.time());
            $data['api_gf'] = json_decode($json,1);
            $data['api'] = require_once(CTCMSPATH.'libs/Ct_Ku.php');
            $this->load->view('head.tpl',$data);
            $this->load->view('caiji.tpl');
		}
	}

    //资源库站点列表
	public function type()
	{
		$k = (int)$this->input->get_post('k');
		$ac = $this->input->get_post('ac',true);
		$op = $this->input->get_post('op',true);
		$data['jumpurl'] = file_get_contents(FCPATH."caches/jumpurl.txt");
		if($op == 'gf'){
			$json = htmlall('http://api.ctcms.cn/vodapi/ctcms_v3.json?v='.time());
			$zyku = json_decode($json,1);
		}else{
			$zyku = require_once(CTCMSPATH.'libs/Ct_Ku.php');
		}
        $data['k'] = $k;
        $data['op'] = $op;
        $data['api'] = $zyku[$k];
        $this->load->view('head.tpl',$data);
        $this->load->view('caiji_type.tpl');
	}

    //资源库新增、修改
	public function edit($op='')
	{
		if(empty($op)) $op = $this->input->get('id',true);
		$k = (int)$this->input->get_post('k');
		if($op=='add'){
			$data['name'] = '';
			$data['ac'] = '';
			$data['apiurl'] = '';
			$data['type'] = '';
			$data['form'] = '';
			$data['info'] = '';
		}else{
			$zyku = require_once(CTCMSPATH.'libs/Ct_Ku.php');
			$data['name'] = $zyku[$k]['name'];
			$data['ac'] = $zyku[$k]['ac'];
			$data['apiurl'] = $zyku[$k]['apiurl'];
			$data['type'] = $zyku[$k]['type'];
			$data['form'] = $zyku[$k]['form'];
			$data['info'] = $zyku[$k]['info'];
		}
		if(empty($op)) $op = 'edit';
		$data['op'] = $op;
		$data['k'] = $k;
        $this->load->view('head.tpl',$data);
        $this->load->view('caiji_edit.tpl');
	}

    //资源库保存
	public function save($op='')
	{
		if(empty($op)) $op = $this->input->get('id',true);
		$k = (int)$this->input->get_post('k');

		$data['name'] = $this->input->get_post('name',true);
		$data['ac'] = $this->input->get_post('ac',true);
		$data['apiurl'] = $this->input->get_post('apiurl',true);
		$data['type'] = $this->input->get_post('type',true);
		$data['form'] = $this->input->get_post('form',true);
		$data['info'] = $this->input->get_post('info',true);

		if(empty($data['name']) || empty($data['apiurl']) || empty($data['ac'])){
             admin_msg('数据不完整~！','javascript:history.back();','no');
		}
		if($data['type'] != 'down') $data['type'] = 'play';
		if($data['form'] != 'json') $data['form'] = 'xml';
		if(empty($data['info'])) $data['info'] = '由资源网'.$data['name'].'提供,保持同步更新~!';

		$zyku = require_once(CTCMSPATH.'libs/Ct_Ku.php');
		if($op=='add'){
			$data['list'] = array();
			$zyku[] = $data;
		}else{
			$data['list'] = $zyku[$k]['list'];
			$zyku[$k] = $data;
		}
		arr_file_edit($zyku,CTCMSPATH.'libs/Ct_Ku.php');
		echo "<script>
		    parent.layer.msg('恭喜您，操作成功~!');
		    setInterval('parent.location.reload()',1000); 
            </script>";
	}

    //资源库删除
	public function del()
	{
		$k = (int)$this->input->post('id');
		$zyku = require_once(CTCMSPATH.'libs/Ct_Ku.php');
		unset($zyku[$k]);
		$zyku = array_merge($zyku);
		arr_file_edit($zyku,CTCMSPATH.'libs/Ct_Ku.php');
		$data['error']= 'ok';
		echo json_encode($data);
	}

    //资源站点新增、修改
	public function type_edit($op='')
	{
		if(empty($op)) $op = $this->input->get('id',true);
		$k = (int)$this->input->get_post('k');
		$n = (int)$this->input->get_post('n');
		if($op=='add'){
			$data['name'] = '';
			$data['rid'] = '';
			$data['apiurl'] = '';
		}else{
			$zyku = require_once(CTCMSPATH.'libs/Ct_Ku.php');
			$data['name'] = $zyku[$k]['list'][$n]['name'];
			$data['rid'] = $zyku[$k]['list'][$n]['rid'];
			$data['apiurl'] = $zyku[$k]['list'][$n]['apiurl'];
		}
		if(empty($op)) $op = 'edit';
		$data['op'] = $op;
		$data['k'] = $k;
		$data['n'] = $n;
        $this->load->view('head.tpl',$data);
        $this->load->view('caiji_type_edit.tpl');
	}

    //资源站点保存
	public function type_save($op='')
	{
		if(empty($op)) $op = $this->input->get('id',true);
		$k = (int)$this->input->get_post('k');
		$n = (int)$this->input->get_post('n');

		$data['name'] = $this->input->get_post('name',true);
		$data['apiurl'] = $this->input->get_post('apiurl',true);
		$data['rid'] = $this->input->get_post('rid',true);

		if(empty($data['name'])){
             admin_msg('数据不完整~！','javascript:history.back();','no');
		}

		$zyku = require_once(CTCMSPATH.'libs/Ct_Ku.php');
		if($op=='add'){
			$zyku[$k]['list'][] = $data;
		}else{
			$zyku[$k]['list'][$n] = $data;
		}
		arr_file_edit($zyku,CTCMSPATH.'libs/Ct_Ku.php');
		echo "<script>
		    parent.layer.msg('恭喜您，操作成功~!');
		    setInterval('parent.location.reload()',1000); 
            </script>";
	}

    //资源站点删除
	public function type_del()
	{
		$k = (int)$this->input->post('id');
		$n = (int)$this->input->post('n');
		$zyku = require_once(CTCMSPATH.'libs/Ct_Ku.php');
		unset($zyku[$k]['list'][$n]);
		$zyku[$k]['list'] = array_merge($zyku[$k]['list']);
		arr_file_edit($zyku,CTCMSPATH.'libs/Ct_Ku.php');
		$data['error']= 'ok';
		echo json_encode($data);
	}

	//XML资源列表
	public function xml_list($api,$rid,$ac,$op,$cid,$key,$ids,$page,$type='url',$k=0)
	{
		$data['api_url'] ='api='.$api.'&rid='.$rid.'&op='.$op.'&ac='.$ac.'&key='.$key.'&cid='.$cid.'&typ='.$type.'&form=xml&k='.$k;
		//判断API地址加密
		if(substr($api,0,7)!='http://' && substr($api,0,8)!='https://'){
			$api =  base64_decode($api);
		}
		$api.= strstr($api,'?') ? '&' : '?';
		$API_URL = $api.'ac=list&rid='.$rid.'&wd='.$key.'&t='.$cid.'&h=0&ids=&pg='.$page;
		$strs=htmlall($API_URL);
		if(empty($strs)) admin_msg('<font color=red>获取列表失败，请多试几次，如一直出现该错误，通常为网络不稳定或禁用了采集！</font>','javascript:history.go(-1);');
		//组合分页信息
		preg_match('<list page="([0-9]+)" pagecount="([0-9]+)" pagesize="([0-9]+)" recordcount="([0-9]+)">',$strs,$page_array);
		$data['recordcount'] = $page_array[4];
		$data['pagecount'] = $page_array[2];
		$data['pagesize'] = $page_array[3];
		$data['pageindex'] = $page_array[1];	

		$path=links('caiji','index',0,$data['api_url'].'&key='.$key.'&cid='.$cid.'&type='.$type.'&form=xml&');
		$data['pages'] = admin_page($path,$data['recordcount'],$data['pagecount'],$page);

		//组合列表
		$vod='';
		preg_match_all('/<video>([\s\S]*?)<\/video>/',$strs,$vod_array);
		foreach($vod_array[1] as $key=>$value){
			preg_match_all('/<last>([\s\S]*?)<\/last>/',$value,$times);
			preg_match_all('/<id>([0-9]+)<\/id>/',$value,$ids);
			preg_match_all('/<tid>([0-9]+)<\/tid>/',$value,$cids);
			preg_match_all('/<name><\!\[CDATA\[([\s\S]*?)\]\]><\/name>/',$value,$names);
			preg_match_all('/<type>([\s\S]*?)<\/type>/',$value,$cnames);
			preg_match_all('/<dt>([\s\S]*?)<\/dt>/',$value,$dts);
			$vod[$key]['addtime'] = $times[1][0];
			$vod[$key]['id'] = $ids[1][0];
			$vod[$key]['cid'] = intval($cids[1][0]);
			$vod[$key]['name'] = $names[1][0];
			$vod[$key]['laiy'] = (!empty($dts[1][0]))?$dts[1][0]:$ac;
			$vod[$key]['cname'] = $cnames[1][0];
		}

		//组合分类
		preg_match_all('/<ty id="([0-9]+)">([\s\S]*?)<\/ty>/',$strs,$list_array);
		foreach($list_array[1] as $key=>$value){
			$vod_list[$key]['id'] = $value;
		    $vod_list[$key]['name'] = $list_array[2][$key];
		}

		$data['form']='xml';
		$data['vod']=$vod;
		$data['vod_list']=$vod_list;
		return $data;
    }

	//JSON资源列表
	public function json_list($api,$rid,$ac,$op,$cid,$key,$ids,$page,$type='url',$k=0)
	{
		$data['api_url'] ='api='.urlencode($api).'&rid='.$rid.'&op='.$op.'&ac='.$ac.'&key='.$key.'&cid='.$cid.'&type='.$type.'&form=json&k='.$k;
		//判断API地址加密
		if(substr($api,0,7)!='http://' && substr($api,0,8)!='https://'){
			$api =  base64_decode($api);
		}
		$api.= strstr($api,'?') ? '&' : '?';
		$API_URL = $api.'play='.$rid.'&key='.$key.'&cid='.$cid.'&page='.$page;
		$strs=htmlall($API_URL);
		if(empty($strs)) admin_msg('<font color=red>获取列表失败，请多试几次，如一直出现该错误，通常为网络不稳定或禁用了采集！</font>','javascript:history.go(-1);');
		$arr = json_decode($strs,1);
		if($arr['code']==0) admin_msg('<font color=red>'.$arr['msg'].'</font>','javascript:history.go(-1);');
		//组合分页信息
		$data['recordcount'] = $arr['pagecount'];
		$data['pagecount'] = $arr['pagejs'];
		$data['pagesize'] = $arr['pagesize'];
		$data['pageindex'] = $arr['page'];	

		$path=links('caiji','index',0,$data['api_url'].'&key='.$key.'&cid='.$cid.'&type='.$type.'&form=json&');
		$data['pages'] = admin_page($path,$data['recordcount'],$data['pagecount'],$page);

		$data['form']='json';
		$data['vod'] = $arr['vod'];
		$data['vod_list'] = $arr['class'];
		return $data;
    }

	//XML资源采集
	public function xml($api,$rid,$ac,$op,$cid,$key,$ids,$page,$type='url',$k=0)
	{
		$api_url ='api='.urlencode($api).'&rid='.$rid.'&op='.$op.'&ac='.$ac.'&do=caiji&key='.$key.'&cid='.$cid.'&type='.$type.'&form=xml&k='.$k;
		//绑定分类数组
		$LIST = require_once(CTCMSPATH.'libs/Ct_Bind.php');
		//下载组标识
		$CT_Down = unserialize(CT_Down);

		//判断API地址加密
		if(substr($api,0,7)!='http://' && substr($api,0,8)!='https://'){
			$api =  base64_decode($api);
		}
		$api.= strstr($api,'?') ? '&' : '?';
		$API_URL = $api.'ac=videolist&rid='.$rid.'&wd='.$key.'&t='.$cid.'&h='.$op.'&ids='.$ids.'&pg='.$page;

		$strs=htmlall($API_URL);
		if(empty($strs)) admin_msg('<font color=red>采集失败，请多试几次，如一直出现该错误，通常为网络不稳定或禁用了采集！</font>','javascript:history.go(-1);');

		//组合分页信息
		$recordcount = 0;
		$pagecount = 0;
		$pagesize = 0;
		$pageindex = 0;	

		preg_match('<list page="([0-9]+)" pagecount="([0-9]+)" pagesize="([0-9]+)" recordcount="([0-9]+)">',$strs,$page_array);
		if(!empty($page_array)){
		   $recordcount = (int)$page_array[4];
		   $pagecount = (int)$page_array[2];
		   $pagesize = (int)$page_array[3];
		   $pageindex = (int)$page_array[1];	
		}


		echo '<LINK href="'.base_url().'packs/admin/css/H-ui.min.css" type="text/css" rel="stylesheet"><br>';
		echo '<div id="loading" style="display:none;position: absolute;left:40%;top:300px;z-index:10;background-color:#ccc;"><span style="width:120px;height:40px;line-height:40px;background-color:#ccc;">&nbsp;&nbsp;<img align="absmiddle" src="'.Web_Path.'packs/admin/images/loading.gif">数据加载中...</span></div>';
		echo "&nbsp;&nbsp;<b><font color=#0000ff>当前页共有".$recordcount."个数据，需要采集".$pagecount."次，每一次采集".$pagesize."个，正在执行第".$pageindex."次采集任务</font></b><br/>";

		//组合列表
		$vod='';
		preg_match_all('/<video>([\s\S]*?)<\/video>/',$strs,$vod_array);

		//print_r($vod_array);exit($strs);
		foreach($vod_array[1] as $key=>$value){

			$s = $key+1;
			$p=($pageindex-1)*$pagesize+$s;

			preg_match_all('/<tid>([0-9]+)<\/tid>/',$value,$cidarr);

			preg_match_all('/<name><\!\[CDATA\[([\s\S]*?)\]\]><\/name>/',$value,$namearr);
			$add['name']     = str_replace("'","",htmlspecialchars_decode($namearr[1][0]));
			preg_match_all('/<type>([\s\S]*?)<\/type>/',$value,$typearr);
			$add['type']     = str_replace(" ","/",str_replace("'","",htmlspecialchars_decode($typearr[1][0])));
			preg_match_all('/<pic>([\s\S]*?)<\/pic>/',$value,$picarr);
			$add['pic']      = $picarr[1][0];
			preg_match_all('/<director><\!\[CDATA\[([\s\S]*?)\]\]><\/director>/',$value,$daoyanarr);
			$add['daoyan']   = str_replace(" ","/",str_replace("'","",htmlspecialchars_decode($daoyanarr[1][0])));
			preg_match_all('/<actor><\!\[CDATA\[([\s\S]*?)\]\]><\/actor>/',$value,$zhuyanarr);
			$add['zhuyan']     = str_replace(" ","/",str_replace("'","",htmlspecialchars_decode($zhuyanarr[1][0])));
			preg_match_all('/<year>([\s\S]*?)<\/year>/',$value,$yeararr);
			$add['year']     = $yeararr[1][0];
			preg_match_all('/<area>([\s\S]*?)<\/area>/',$value,$diquarr);
			$add['diqu']     = $diquarr[1][0];
			preg_match_all('/<lang>([\s\S]*?)<\/lang>/',$value,$yuyanarr);
			$add['yuyan']    = $yuyanarr[1][0];
			preg_match_all('/<state>([\s\S]*?)<\/state>/',$value,$statearr);
			$add['state']   = (empty($statearr[1][0]))?'完结':$statearr[1][0];
			preg_match_all('/<des><\!\[CDATA\[([\s\S]*?)\]\]><\/des>/',$value,$textarr);
			$add['text']  = str_replace("'","",htmlspecialchars_decode($textarr[1][0]));
			$add['addtime']  = time();

			$add['daoyan']   = str_replace("//","/",$add['daoyan']);
			$add['zhuyan']   = str_replace("//","/",$add['zhuyan']);
			$add['type']   = str_replace("//","/",$add['type']);
			if(strlen($add['daoyan'])>128){
				$add['daoyan'] = sub_str($add['daoyan'],64);
			}
			if(strlen($add['zhuyan'])>128){
				$add['zhuyan'] = sub_str($add['zhuyan'],64);
			}

			//替换标题
			$add['name']=str_replace("--电视剧","",$add['name']);
			$add['name']=str_replace("--微电影","",$add['name']);
			$add['name']=str_replace("--综艺","",$add['name']);
			$add['name']=str_replace("--动漫","",$add['name']);
			$add['name']=str_replace(" 电视剧","",$add['name']);
			$add['name']=str_replace(" 微电影","",$add['name']);
			$add['name']=str_replace(" 综艺","",$add['name']);
			$add['name']=str_replace(" 动漫","",$add['name']);

			preg_match_all('/<dd flag="([\s\S]*?)"><\!\[CDATA\[([\s\S]*?)\]\]><\/dd>/',$value,$url_arr);

			$purl_arr=array();
			for($j=0;$j<count($url_arr[2]);$j++){

				$laiy = $laiy2 = $url_arr[1][$j];

				//资源站来源替换
				$laiy=str_replace("youkuyun","ykyun",$laiy);
				$laiy=str_replace("cloud","ykyun",$laiy);
				$laiy=str_replace("优酷云","ykyun",$laiy);
				$laiy=str_replace("xigua","xgvod",$laiy);
				$laiy=str_replace("xfplay","yyxf",$laiy);
				$laiy=str_replace("百度影音","bdhd",$laiy);
				//粉丝多资源来源替换
				$laiy=str_replace("乐视网","letv",$laiy);
				$laiy=str_replace("优酷","youku",$laiy);
				$laiy=str_replace("影音先锋","yyxf",$laiy);
				$laiy=str_replace("吉吉影音","jjvod",$laiy);
				//凡高资源来源替换
				$laiy=str_replace("hdbaofeng1080P","baofeng",$laiy);
				$laiy=str_replace("hdbaofeng720P","baofeng",$laiy);
				$laiy=str_replace("hdbaofeng480P","baofeng",$laiy);
				$laiy=str_replace("hdbaofeng240P","baofeng",$laiy);
				$laiy=str_replace("yinyuetai","yyt",$laiy);


				$purl = $url_arr[2][$j];
				$parrs = explode('#', $purl);
				for($s=0;$s<count($parrs);$s++){
					if(strpos($parrs[$s],'$') === FALSE){
						$parrs[$s] = ($s+1).'$'.$parrs[$s];
					}else{
						$parr2 = explode('$', $parrs[$s]);
						if(isset($parr2[2])){
							$parrs[$s] = $parr2[0].'$'.$parr2[1];
						}
						if(strpos($parrs[$s],'://') === FALSE){
							$parrs[$s].="&type=".$laiy;
						}
					}
				}
				$purl=implode('#',$parrs);
				$purl=htmlspecialchars_decode($purl);
				$purl=str_replace("xigua","xgvod",$purl);
				$purl=str_replace("xfplay","yyxf",$purl);
				$purl=str_replace("yyxf://","xfplay://",$purl);
				$purl=str_replace("百度影音","bdhd",$purl);

				//判断集数
				if(strpos($purl,'$') === FALSE){
					$purl='正片$'.$purl;
				}
				if(!empty($purl)){
					if(!empty($CT_Down[$laiy])){
						$purl=str_replace("#","\n",$purl);
						$purl_arr[]=$laiy."###".$purl;
					}elseif($laiy=='ykyun'){
						$purl=str_replace("#","\n",$purl);
						$purl_arr[]="ykyun###".$purl;
					}else{
						//判断来源是否存在，不存在则为ydisk
						$row=$this->db->query("SELECT id FROM ".CT_SqlPrefix."player where bs='".safe_replace($laiy)."'")->row();
						//组装地址
						$purl = str_replace("#","\n",$purl);
						if($row){
							$purl_arr[] = $laiy."###".$purl;
						}else{
							$purl_arr[] = "ydisk###".$purl;
						}
					}
				}
			}
			$purl = implode('#ctcms#',$purl_arr);
			$zd = ($type=='down' || !empty($CT_Down[$laiy])) ? 'down' : 'url';
			$add[$zd] = $purl;
			//判断绑定
			$val=arr_key_value($LIST,$ac.'_'.$cidarr[1][0]);
			if(!$val){

				echo "&nbsp;&nbsp;&nbsp;第".$p."个影片&nbsp;<font color=red>".$add['name']."</font>&nbsp;&nbsp;数据没有绑定分类，不进行入库处理！<br/>";

			//判断数据完整性
			}elseif(empty($add['name']) || empty($purl)){

				echo "&nbsp;&nbsp;&nbsp;第".$p."个影片&nbsp;<font color=red>".$add['name']."</font>&nbsp;&nbsp;数据不完整，不进行入库处理！<br/>";

			}else{

				$add['cid']  = $val;

				//判断数据是否存在
				$Cj_Add = defined('Cj_Add') ? Cj_Add : 'cid';
				$where = array();
				$where['name'] = $add['name'];
				$addarr = explode(',', $Cj_Add);
				$row = $this->csdb->get_row('vod','id,name,year,state,url,down',$where);
				if(!$row){
					$this->csdb->get_insert('vod',$add);
					echo "&nbsp;&nbsp;&nbsp;第".$p."个影片&nbsp;<font color=#ff00ff>".$add['name']."</font>数据库中没有记录，已入库完成！<br/>";
				}else{
					//判断更新状态
    				$Cj_Edit = defined('Cj_Edit') ? Cj_Edit : 'addtime,url,state';
					$editarr = explode(',', $Cj_Edit);
					$edit = array();
					foreach ($editarr as $v) {
						if($v == 'url' || $v == 'down'){
							$edit[$zd] = $this->isdata($purl,$row->$zd);
						}else{
							$edit[$v] = $add[$v];
						}
					}
					if(!empty($edit)) $this->csdb->get_update('vod',$row->id,$edit);
					echo "&nbsp;&nbsp;&nbsp;第".$p."个影片&nbsp;<font color=#ff00ff>".$add['name']."</font>&nbsp;&nbsp;数据存在，数据更新成功~!<br/>";
				}
			}
		}

		if($pageindex < $pagecount){
			//缓存断点续采
			$jumpurl = links('caiji','index',0,$api_url.'&page='.($page+1));
			write_file(FCPATH."caches/jumpurl.txt", $jumpurl);
			//跳转到下一页
			echo("</br>&nbsp;&nbsp;&nbsp;<a href='".links('caiji','index',0,'api='.$api.'&op='.$ops.'&ac='.$ac.'&key='.$keys.'&cid='.$cid)."'>紧急停止</a>&nbsp;&nbsp;&nbsp;<b>&nbsp;第<font color=red>".$page."</font>页入库完毕,暂停<font color=red>3</font>秒继续。。。。。</b><script>setTimeout('updatenext();',3000);
			function updatenext(){
				location.href='".$jumpurl."';
			}
			</script></br></br>");
		}else{
			//清除断点续采
			write_file(FCPATH."caches/jumpurl.txt", "0");
			echo("</br>&nbsp;&nbsp;&nbsp;&nbsp;<b>恭喜您，全部入库完成啦。。。。。</b><script>
			setTimeout('updatenext();',3000);
			function updatenext(){
				location.href='".links('caiji','index',0,str_replace('&do=caiji','',$api_url))."';
			}
			</script>");				
		}
    }

	//JSON资源采集
	public function json($api,$rid,$ac,$op,$cid,$key,$ids,$page,$type='url',$k=0)
	{
		$api_url ='api='.urlencode($api).'&rid='.$rid.'&op='.$op.'&ac='.$ac.'&do=caiji&key='.$key.'&cid='.$cid.'&type='.$type.'&form=json&k='.$k;
		//绑定分类数组
		$LIST = require_once(CTCMSPATH.'libs/Ct_Bind.php');
		//下载组标识
		$CT_Down = unserialize(CT_Down);
		//判断API地址加密
		if(substr($api,0,7)!='http://' && substr($api,0,8)!='https://'){
			$api =  base64_decode($api);
		}
		$api.= strstr($api,'?') ? '&' : '?';
		$API_URL = $api.'ac=show&play='.$rid.'&key='.$key.'&cid='.$cid.'&day='.$op.'&ids='.$ids.'&page='.$page;
		$strs=htmlall($API_URL);
		if(empty($strs)) admin_msg('<font color=red>采集失败，请多试几次，如一直出现该错误，通常为网络不稳定或禁用了采集！</font>','javascript:history.go(-1);');
		$arr = json_decode($strs,1);
		if($arr['code']==0) admin_msg('<font color=red>'.$arr['msg'].'</font>','javascript:history.go(-1);');

		//组合分页信息
		$recordcount = $arr['pagecount'];
		$pagecount = $arr['pagejs'];
		$pagesize = $arr['pagesize'];
		$pageindex = $arr['page'];

		echo '<LINK href="'.base_url().'packs/admin/css/H-ui.min.css" type="text/css" rel="stylesheet"><br>';
		echo '<div id="loading" style="display:none;position: absolute;left:40%;top:300px;z-index:10;background-color:#ccc;"><span style="width:120px;height:40px;line-height:40px;background-color:#ccc;">&nbsp;&nbsp;<img align="absmiddle" src="'.Web_Path.'packs/admin/images/loading.gif">数据加载中...</span></div>';
		echo "&nbsp;&nbsp;<b><font color=#0000ff>当前页共有".$recordcount."个数据，需要采集".$pagecount."次，每一次采集".$pagesize."个，正在执行第".$pageindex."次采集任务</font></b><br/>";

		//组合列表
		$s=1;
		foreach($arr['vod'] as $key=>$value){

			$p=($pageindex-1)*$pagesize+$s;

			$add['name']     = str_checkhtml($value['name']);
			$add['type']     = str_checkhtml($value['type']);
			$add['pic']      = $value['pic'];
			$add['daoyan']   = str_checkhtml($value['daoyan']);
			$add['zhuyan']   = str_checkhtml($value['zhuyan']);
			$add['year']     = $value['year'];
			$add['diqu']     = $value['diqu'];
			$add['yuyan']    = $value['yuyan'];
			$add['state']    = $value['state'];
			$add['text']	   = str_checkhtml($value['text']);
			$add['addtime']  = time();
			if(strlen($add['daoyan'])>128){
			  $add['daoyan'] = sub_str($add['daoyan'],64);
			}
			if(strlen($add['zhuyan'])>128){
			  $add['zhuyan'] = sub_str($add['zhuyan'],64);
			}

			//组装地址
			$purl_arr = array();
			foreach($value['data'] as $k=>$v){
				//判断来源是否存在，不存在则为ydisk
				$row=$this->db->query("SELECT id FROM ".CT_SqlPrefix."player where bs='".safe_replace($k)."'")->row();
				if(!$row) $k = 'ydisk';
				$purl_arr[] = $k.'###'.implode("\n",$v);
			}
			$purl = implode('#ctcms#',$purl_arr);
			$zd = $type=='down' ? 'down' : 'url';
			$add[$zd] = $purl;

			//判断绑定
			$val=arr_key_value($LIST,$ac.'_'.$value['cid']);
			if(!$val){

				echo "&nbsp;&nbsp;&nbsp;第".$p."个影片&nbsp;<font color=red>".$value['name']."</font>&nbsp;&nbsp;数据没有绑定分类，不进行入库处理！<br/>";

			//判断数据完整性
			}elseif(empty($value['name']) || empty($purl)){

				echo "&nbsp;&nbsp;&nbsp;第".$p."个影片&nbsp;<font color=red>".$value['name']."</font>&nbsp;&nbsp;数据不完整，不进行入库处理！<br/>";

			}else{

				$add['cid']  = $val;
				
				//判断数据是否存在
				$Cj_Add = defined('Cj_Add') ? Cj_Add : 'cid';
				$where = array();
				$where['name'] = $add['name'];
				$addarr = explode(',', $Cj_Add);
				$row = $this->csdb->get_row('vod','id,name,year,state,url,down',$where);
				if(!$row){
					$this->csdb->get_insert('vod',$add);
					echo "&nbsp;&nbsp;&nbsp;第".$p."个影片&nbsp;<font color=#ff00ff>".$add['name']."</font>数据库中没有记录，已入库完成！<br/>";
				}else{
					//判断更新状态
    				$Cj_Edit = defined('Cj_Edit') ? Cj_Edit : 'addtime,url,state';
					$editarr = explode(',', $Cj_Edit);
					$edit = array();
					foreach ($editarr as $v) {
						if($v == 'url' || $v == 'down'){
							$edit[$zd] = $this->isdata($purl,$row->$zd);
						}else{
							$edit[$v] = $add[$v];
						}
					}
					if(!empty($edit)) $this->csdb->get_update('vod',$row->id,$edit);
					echo "&nbsp;&nbsp;&nbsp;第".$p."个影片&nbsp;<font color=#ff00ff>".$add['name']."</font>&nbsp;&nbsp;数据存在，数据更新成功~!<br/>";
				}
			}
		$s++;
		}

		if($pageindex < $pagecount){
			//缓存断点续采
			$jumpurl = links('caiji','index',0,$api_url.'&page='.($page+1));
			write_file(FCPATH."caches/jumpurl.txt", $jumpurl);
			//跳转到下一页
			echo("</br>&nbsp;&nbsp;&nbsp;<a href='".links('caiji','index',0,'api='.$api.'&op='.$ops.'&ac='.$ac.'&key='.$keys.'&cid='.$cid)."'>紧急停止</a>&nbsp;&nbsp;&nbsp;<b>&nbsp;第<font color=red>".$page."</font>页入库完毕,暂停<font color=red>3</font>秒继续。。。。。</b><script>setTimeout('updatenext();',3000);
			function updatenext(){
				location.href='".$jumpurl."';
			}
			</script></br></br>");
		}else{
			//清除断点续采
			write_file(FCPATH."caches/jumpurl.txt", "0");
			echo("</br>&nbsp;&nbsp;&nbsp;&nbsp;<b>恭喜您，全部入库完成啦。。。。。</b><script>
			setTimeout('updatenext();',3000);
			function updatenext(){
				location.href='".links('caiji','index',0,str_replace('&do=caiji','',$api_url))."';
			}
			</script>");				
		}
    }

    //绑定分类
	public function bind()
	{
        $csid = intval($this->input->get('csid'));
	        $ac  = $this->input->get('ac',TRUE);

        $LIST = require_once(CTCMSPATH.'libs/Ct_Bind.php');
		$val=arr_key_value($LIST,$ac.'_'.$csid);
        $strs='<option value="0">&nbsp;|—选择目标分类</option>';
        $query = $this->db->query("SELECT id,name FROM ".CT_SqlPrefix."class where fid=0 order by xid asc"); 
        foreach ($query->result() as $row) {
            $clas=($row->id==$val)?' selected="elected"':'';
            $strs.='<option value="'.$row->id.'"'.$clas.'>&nbsp;|—'.$row->name.'</option>';
            $query2 = $this->db->query("SELECT id,name FROM ".CT_SqlPrefix."class where fid=".$row->id." order by xid asc"); 
            foreach ($query2->result() as $row2) {
                $clas2=($row2->id==$val)?' selected="elected"':'';
                $strs.='<option value="'.$row2->id.'"'.$clas2.'>&nbsp;|&nbsp;&nbsp;&nbsp;|—'.$row2->name.'</option>';
            }
        }
        echo '<span class="select-box inline"><select class="select" name="cid" id="cid">'.$strs.'
            </select></span><input style="width:50px;" type="button" value="提 交" onClick="submitbind(\''.$ac.'\',\''.$csid.'\');" style="cursor:pointer"> <input name="button" type="button" value="取 消" style="width:50px;" onClick="hidebind();" style="cursor:pointer">
			';
    }

    //绑定分类存储
	public function bind_save()
	{
 	    $ac   = $this->input->get('ac',TRUE);
	    $csid = intval($this->input->get_post('csid'));
	    $id = intval($this->input->get_post('cid'));

	    $LIST = require_once(CTCMSPATH.'libs/Ct_Bind.php');
	    $LIST[$ac.'_'.$csid] = $id;
		arr_file_edit($LIST,CTCMSPATH.'libs/Ct_Bind.php');
        echo 'ok';
	}

	//解除全部绑定
	public function jie_bind()
	{
        $api  = $this->input->get('api',TRUE);
        $ac   = $this->input->get('ac',TRUE);
        $LIST = require_once(CTCMSPATH.'libs/Ct_Bind.php');
        foreach ($LIST as $k=>$v) {
            if(strpos($k,$ac.'_') !== FALSE){
                unset($LIST[$k]);
			}
		}
		arr_file_edit($LIST,CTCMSPATH.'libs/Ct_Bind.php');
        header("Location: ".links('caiji','index',0,'api='.$api.'&ac='.$ac)); 
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