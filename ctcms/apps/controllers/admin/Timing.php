<?php
/**
 * @Ctcms open source management system
 * @copyright 2008-2019 ctcms.cn. All rights reserved.
 * @Author:Cheng Kai Jie
 * @Dtime:2015-12-11
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Timing extends Ctcms_Controller {

	function __construct(){
	    parent::__construct();
		//加载后台模型
		$this->load->model('admin');
        //当前模版
		$this->load->get_templates('admin');
	}

    //选择采集类型
	public function index()
	{
		//判断是否登陆
		$this->admin->login();
		$data['ting'] = require_once CTCMSPATH.'libs/Ct_Timing.php';
		$data['type'] = array('优酷','腾讯','土豆','乐视','PPTV','风行','芒果','奇艺','华数','秒拍','哔哩哔哩','AcFun弹幕','微录客','YY神曲','音悦台');
		$zyku = require_once(CTCMSPATH.'libs/Ct_Ku.php');
        $data['api'] = $zyku;
		$this->load->view('head.tpl',$data);
		$this->load->view('timing_index.tpl');
	}

	//采集配置保存
	public function save()
	{
		//判断是否登陆
		$this->admin->login();
        $type = $this->input->post('type',true);
        $d = $this->input->post('d',true);
        $h = $this->input->post('h',true);
        $day = (int)$this->input->post('day',true);

		$arr['t'] = $type;
		$arr['d'] = $d;
		$arr['h'] = $h;
		$arr['day'] = $day;

        $con = var_export($arr,true);
	    $strs="<?php if (!defined('FCPATH')) exit('No direct script access allowed');".PHP_EOL;
	    $strs.="return $con;";
	    $strs.="?>";
        if(!write_file(CTCMSPATH.'libs/Ct_Timing.php', $strs)){
			admin_msg('抱歉，修改失败，请检查文件写入权限~!',links('timing'),'no');
		}else{
			admin_msg('恭喜你，保存成功~！',links('timing'),'ok');
		}
	}
		
	//采集
	public function caiji()
	{
		$token = $this->input->get('token',true);
		$t = (int)$this->input->get('t',true);
		$page = intval($this->input->get('page'));
		if($page==0) $page=1;
		$key = sys_auth($token,1);
		if($key != 'ting') exit('非法请求');

        //加载CSS样式
		echo '<LINK href="'.base_url().'packs/admin/css/H-ui.min.css" type="text/css" rel="stylesheet"><br>';

        //采集默认配置
        $Ting = require_once(CTCMSPATH.'libs/Ct_Timing.php');
		$day = $Ting['day'];

		//判断是否采集完成
		if($t>count($Ting['t'])-1){
			exit('&nbsp;&nbsp;<b><font color=red>全部采集完成，等待下个时间点采集~！</font></b>');
		}
		$tarr = explode('_', $Ting['t'][$t]);
		$tid = (int)$tarr[0];
		$rid = (int)$tarr[1];
		//API地址
		$zyku = require_once(CTCMSPATH.'libs/Ct_Ku.php');
		$cjurl = !empty($zyku[$tid]['list'][$rid]['apiurl']) ? $zyku[$tid]['list'][$rid]['apiurl'] : $zyku[$tid]['apiurl'];
		//base64解密
		if(strpos($cjurl, 'http://') === false && strpos($cjurl, 'https://') === false){
			$cjurl = base64_decode($cjurl);
		}
		//下载组标识
		$CT_Down = unserialize(CT_Down);
		//绑定分类数组
		$LIST = require_once(CTCMSPATH.'libs/Ct_Bind.php');
        //采集开始
		$API_URL=$cjurl.'?ac=videolist&rid='.$zyku[$tid]['list'][$rid]['rid'].'&wd=&t=0&h='.$day.'&ids=&pg='.$page;
		$strs=htmlall($API_URL);
		if(empty($strs)) exit('<font color=red>采集失败，请多试几次，如一直出现该错误，通常为网络不稳定或禁用了采集！</font>,<a href="javascript:;" onclick="window.location.reload();">我要刷新</a>');

		//组合分页信息
		preg_match('<list page="([0-9]+)" pagecount="([0-9]+)" pagesize="([0-9]+)" recordcount="([0-9]+)">',$strs,$page_array);
		if(!empty($page_array)){
			$recordcount = $page_array[4];
			$pagecount = $page_array[2];
			$pagesize = $page_array[3];
			$pageindex = $page_array[1];	
		}else{
			$recordcount = 0;
			$pagecount = 0;
			$pagesize = 0;
			$pageindex = 0;	
		}

		echo '<div id="loading" style="display:none;position: absolute;left:40%;top:300px;z-index:10;background-color:#ccc;"><span style="width:120px;height:40px;line-height:40px;background-color:#ccc;">&nbsp;&nbsp;<img align="absmiddle" src="'.Web_Path.'packs/admin/images/loading.gif">数据加载中...</span></div>';
		echo "&nbsp;".$zyku[$tid]['list'][$rid]['name']."资源&nbsp;<b><font color=#0000ff>当前页共有".$recordcount."个数据，需要采集".$pagecount."次，每一次采集".$pagesize."个，正在执行第".$pageindex."次采集任务</font></b><br/>";

		//组合列表
		$vod='';
		preg_match_all('/<video><last>([\s\S]*?)<\/last><id>([0-9]+)<\/id><tid>([0-9]+)<\/tid><name><\!\[CDATA\[([\s\S]*?)\]\]><\/name><type>([\s\S]*?)<\/type><pic>([\s\S]*?)<\/pic><lang>([\s\S]*?)<\/lang><area>([\s\S]*?)<\/area><year>([\s\S]*?)<\/year><state>([\s\S]*?)<\/state><note><\!\[CDATA\[([\s\S]*?)\]\]><\/note><actor><\!\[CDATA\[([\s\S]*?)\]\]><\/actor><director><\!\[CDATA\[([\s\S]*?)\]\]><\/director><dl>([\s\S]*?)<\/dl><des><\!\[CDATA\[([\s\S]*?)\]\]><\/des>([\s\S]*?)<\/video>/',$strs,$vod_array);
		$s=1;
		//print_r($vod_array);exit($strs);
		foreach($vod_array[1] as $key=>$value){

			$p=($pageindex-1)*$pagesize+$s;

			$add['name']     = str_replace("'","",htmlspecialchars_decode($vod_array[4][$key]));
			$add['type']     = str_replace(" ","/",str_replace("'","",htmlspecialchars_decode($vod_array[5][$key])));
			$add['pic']      = $vod_array[6][$key];
			$add['daoyan']   = str_replace(" ","/",str_replace("'","",htmlspecialchars_decode($vod_array[13][$key])));
			$add['zhuyan']     = str_replace(" ","/",str_replace("'","",htmlspecialchars_decode($vod_array[12][$key])));
			$add['year']     = $vod_array[9][$key];
			$add['diqu']     = $vod_array[8][$key];
			$add['yuyan']    = $vod_array[7][$key];
			$add['state']   = (empty($vod_array[10][$key]))?'完结':$vod_array[10][$key];
			$add['text']  = str_replace("'","",htmlspecialchars_decode($vod_array[15][$key]));
			$add['addtime']  = time();

			$add['daoyan']   = str_replace("//","/",$add['daoyan']);
			$add['zhuyan']   = str_replace("//","/",$add['zhuyan']);
			$add['type']   = str_replace("//","/",$add['type']);

			//替换标题
			$add['name']=str_replace("--电视剧","",$add['name']);
			$add['name']=str_replace("--微电影","",$add['name']);
			$add['name']=str_replace("--综艺","",$add['name']);
			$add['name']=str_replace("--动漫","",$add['name']);
			$add['name']=str_replace(" 电视剧","",$add['name']);
			$add['name']=str_replace(" 微电影","",$add['name']);
			$add['name']=str_replace(" 综艺","",$add['name']);
			$add['name']=str_replace(" 动漫","",$add['name']);

			preg_match_all('/<dd flag="([\s\S]*?)"><\!\[CDATA\[([\s\S]*?)\]\]><\/dd>/',$vod_array[14][$key],$url_arr);

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
				$purl=htmlspecialchars_decode($purl);
				$purl=str_replace("xigua","xgvod",$purl);
				$purl=str_replace("xfplay","yyxf",$purl);
				$purl=str_replace("yyxf://","xfplay://",$purl);
				$purl=str_replace("百度影音","bdhd",$purl);
				$purl=str_replace("$".$laiy2,"",$purl);


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
			$zd = $zyku[$tid]['type']=='down' ? 'down' : 'url';
			$add[$zd] = $purl;

			//判断绑定
			$val=arr_key_value($LIST,'ctcmszy_'.$vod_array[3][$key]);
			if(!$val){

				echo "&nbsp;&nbsp;&nbsp;第".$p."个影片&nbsp;<font color=red>".$vod_array[4][$key]."</font>&nbsp;&nbsp;数据没有绑定分类，不进行入库处理！<br/>";

			//判断数据完整性
			}elseif(empty($vod_array[4][$key]) || empty($purl)){

				echo "&nbsp;&nbsp;&nbsp;第".$p."个影片&nbsp;<font color=red>".$vod_array[4][$key]."</font>&nbsp;&nbsp;数据不完整，不进行入库处理！<br/>";

			}else{

				$add['cid']  = $val;
				//判断数据是否存在
				$set = 0;
				if(!empty($add['year'])){
					$sql="SELECT id,name,year,state,url,down FROM ".CT_SqlPrefix."vod where name='".$add['name']."' and cid=".$val." order by id desc limit 1";
					$row=$this->db->query($sql)->row();
					if($row){
						if(!empty($row->year) && $row->year!=$add['year']){
							$name = $add['name'].'('.$add['year'].')';
							$sql="SELECT id,state,url,down FROM ".CT_SqlPrefix."vod where name='".$name."' and cid=".$val." order by id desc limit 1";
							$row=$this->db->query($sql)->row();
							if($row){
								$set = 1;
							}
						}else{
							$set = 1;
						}
					}
				}else{
					$sql="SELECT id,url,down,state FROM ".CT_SqlPrefix."vod where name='".$add['name']."'";
					$row=$this->db->query($sql)->row();
					if($row) $set = 1;
				}
				if($set==0){

					   $this->csdb->get_insert('vod',$add);
					   echo "&nbsp;&nbsp;&nbsp;第".$p."个影片&nbsp;<font color=#ff00ff>".$add['name']."</font>数据库中没有记录，已入库完成！<br/>";

				}else{

					 //判断更新状态
					 if($row->$zd==$purl){

						  echo "&nbsp;&nbsp;&nbsp;第".$p."个影片&nbsp;<font color=#ff6600>".$add['name']."</font>&nbsp;&nbsp;数据相同，暂无不需要更新<br/>";

					 }else{
						 if($row->state!='完结' && (int)$add['state']>(int)$row->state && $zd=='url'){
							 $edit['state']  = $add['state'];
						 }
						 $edit[$zd] = $this->isdata($purl,$row->$zd);
						 echo "&nbsp;&nbsp;&nbsp;第".$p."个影片&nbsp;<font color=#ff00ff>".$add['name']."</font>&nbsp;&nbsp;数据存在，数据更新成功~!<br/>";

						  //更新数据
						  $edit['year'] = $add['year'];
						  $edit['addtime'] = time();
						  $this->csdb->get_update('vod',$row->id,$edit);
					 }
				}
			}
		$s++;
		}

		if($pageindex < $pagecount){
			//跳转到下一页
			echo("</br>&nbsp;&nbsp;&nbsp;<a href='".links('timing','zt')."'>紧急停止</a>&nbsp;&nbsp;&nbsp;<b>&nbsp;第<font color=red>".$page."</font>页入库完毕,暂停<font color=red>3</font>秒继续。。。。。</b><script>setTimeout('updatenext();',3000);
			function updatenext(){
				document.getElementById('loading').style.display = 'block';
				location.href='".links('timing','caiji',0,'token='.$token.'&t='.$t.'&page='.($page+1))."';
			}
			</script></br></br>");
		}else{
		    $link= links('timing','caiji',0,'token='.$token.'&t='.($t+1));
			echo("</br>&nbsp;&nbsp;&nbsp;&nbsp;<b>恭喜您，".$zyku[$tid]['name']."全部入库完成啦。。。。。</b><script>
			setTimeout('updatenext();',3000);
			function updatenext(){
				document.getElementById('loading').style.display = 'block';
				location.href='".$link."';
			}
			</script>");				
		}
	}

	public function zt()
	{
		//加载CSS样式
		echo '<LINK href="'.base_url().'packs/admin/css/H-ui.min.css" type="text/css" rel="stylesheet"><br>';
        echo '-----------等待下个采集时间点开始~!--------------';
	}

	//合并新老数据
	public function isdata($xpurl,$ypurl)
	{
	    $arr1=$arr2=array();
		$xarr=explode("#ctcms#",$xpurl);
		$xcount=count($xarr);
		for($i=0;$i<$xcount;$i++){
             $ly=explode("###",$xarr[$i]);
             $arr1[$ly[0]][]=$xarr[$i];
		}
		$yarr=explode("#ctcms#",$ypurl);
		$ycount=count($yarr);
		for($i=0;$i<$ycount;$i++){
             $ly=explode("###",$yarr[$i]);
             $arr2[$ly[0]][]=$yarr[$i];
		}
		foreach ($arr1 as $key => $value) {
			if(isset($arr2[$key])){
				foreach ($arr1[$key] as $k => $v) {
					if(isset($arr2[$key][$k])){
						$a1 = str_replace($key.'###', '', $arr1[$key][$k]);
						$v1 = explode("\n", $a1);
						$a2 = str_replace($key.'###', '', $arr2[$key][$k]);
						$v2 = explode("\n", $a2);
						if(sizeof($v1) <= sizeof($v2)){
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