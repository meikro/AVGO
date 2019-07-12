<?php
/*
  Yun Parse 云解析,QQ:157503886
  请在下面地址查询统计情况。
  http://120.27.155.106/login
*/

//文件名称
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
// 网站根目录
define('FCPATH', str_replace("\\", "/", str_replace(SELF, '', __FILE__)));
//加载配置文件
require_once FCPATH.'sys.php';

//防盗链
if(!is_referer()){
	get_json(array('msg'=>'非法访问，403~!'));
}

//接收参数
$url = empty($_GET['url']) ? $_POST['url'] : $_GET['url'];
$vid = get_key($url,'D');
$hd = empty($_GET['hd']) ? VOD_HD : $_GET['hd'];
$up = (int)$_POST['up'];
$post = $_POST['data'];

//判断地址解密
if(empty($vid)){
    get_json(array('msg'=>'Url非法操作~!'));
}

//判断http地址模式
$arr = explode('~',$vid);
$vid = $arr[0];
$type = !empty($arr[1])?$arr[1]:'';

if(strpos($vid,'m3u8') !== false){
	$data['url'] = $vid;
	$data['ext'] = 'm3u8';
}else{
	$data['url'] = 'http://206dy.com/vip.php?url='.$vid;
	$data['ext'] = 'link';
}
$data['msg'] = 'ok';
get_json($data);

//组装URL参数
$param = 'url='.$vid.'&type='.$type.'&hd='.$hd.'&wap='.$wap;

//判断缓存是否存在
$cache=0;
$filemd5 = FCPATH.'cache/'.md5($param.USER_TOKEN);
$filem3u8 = FCPATH.'cache/'.md5($vid.USER_TOKEN).'.m3u8';
if(empty($post) && $up==0 && file_exists($filemd5)){
	$json = file_get_contents($filemd5);
	$arr = json_decode($json,1);
	$arr = get_new_arr($arr);
	if($arr['ctime'] > time()){
		$cache++;
		if($arr['ext']=='m3u8_list' && (!file_exists($filem3u8) || $arr['type']=='qiyi')){
			file_put_contents($filem3u8,base64_decode($arr['url']));
		}
	}
}
if($cache==0){
	if(!empty($post)){
		$apiurl = get_api(1).'?uid='.USER_ID.'&up='.$up.'&token='.USER_TOKEN.'&'.$param;
		$json = get_url($apiurl,'data='.urlencode($post));
	}else{
		$json = get_url(API_URL.'?uid='.USER_ID.'&up='.$up.'&token='.USER_TOKEN.'&'.$param);
	}
	$arr = json_decode($json,1);
	$arr = get_new_arr($arr);
	if(empty($arr['url']) || $arr['success']==0){
		get_json(array('msg'=>$arr['msg']));
	}else{
		file_put_contents($filemd5,$json);
		if($arr['ext']=='m3u8_list'){
		    file_put_contents($filem3u8,base64_decode($arr['url']));
		}
	}
}

//解析输出
if($_GET['url']){
	if($arr['ext']=='xml'){
		get_xml($arr);
	}elseif($arr['ext']=='m3u8_list'){
		$m3u8 = base64_decode($arr['url']);
		//115解析
		if($arr['type']=='115' && strpos($m3u8, '.115.com/') !== false){
		    preg_match_all('/http:\/\/([\s\S]+?)#/',$m3u8,$arr2);
		    $xstr=$ystr=array();
		    if(!empty($arr2[1])){
		        for($i=0;$i<count($arr2[1]);$i++){
					if(strpos($arr2[1][$i], 'video.key') === false){
		               $ystr[]="http://".$arr2[1][$i];
		                   $xstr[]="key.php?url=".rawurlencode("http://".$arr2[1][$i]);
					}
		        }
		    }
		    $m3u8=str_replace($ystr,$xstr,$m3u8);
		    $m3u8=str_replace("%0A","\n",$m3u8);
		}
		header('Content-type: application/vnd.apple.mpegurl');
		header('Content-disposition: attachment; filename=video.m3u8');
		exit($m3u8);
	}else{
		$data['msg'] = '缺少必须参数ext~!';
		get_json($data);
	}
}else{
	$ip = empty($_GET['ip']) ? $_POST['ip'] : $_GET['ip'];
	if($arr['ext']=='h5_fd'){
		$purl = $arr['url'];
		if(is_array($arr['url'])){
			 $purl = array();
			 for($i=0;$i<count($arr['url']);$i++){
				 $purl[] = $arr['url'][$i]['purl'];
			 }
		}
		$data['url'] = $purl;
	}elseif($arr['ext']=='xml' && $wap==0){
		$data['url'] = WEB_PATH.'api.php?url='.$url.'&ip='.$ip;
	}elseif($arr['ext']=='m3u8_list'){
		$m3u8url = 'http://'.$_SERVER['HTTP_HOST'].WEB_PATH.str_replace(FCPATH,'',$filem3u8);
		$handle = curl_init($m3u8url);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 10);
		curl_exec($handle);
		$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
		curl_close($handle);
		if($httpCode == 404) {
			$data['url'] = 'http://'.$_SERVER['HTTP_HOST'].WEB_PATH.'api.php?url='.$url.'&ip='.$ip;
		}else{
			$data['url'] = $m3u8url;
		}
		if($arr['type']=='bdyun'){
			$arr['ext'] = 'link';
			if($wap==0){
				$data['url'] = $arr['play_swf'].'?file='.rawurlencode($m3u8url);
			}else{
				if((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')){
					$data['url'] = $m3u8url;
				}else{
					$data['url'] = 'https://yunparse.duapp.com/yun.html?url='.rawurlencode($m3u8url);
				}
			}
		}
	}else{
		if($arr['ext']=='m3u8' && $wap==0){
			$arr['url'] = rawurlencode($arr['url']);
		}
		$data['url'] = $arr['url'];
	}
    $data['ext'] = $arr['ext'];
    $data['msg'] = 'ok';
	get_json($data);
}
