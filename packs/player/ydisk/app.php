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

//接收参数
$type = '';
$url = $vid = empty($_GET['url']) ? $_POST['url'] : $_GET['url'];
if(VOD_JM==1){
	if(strpos($url,'~') !== FALSE){
		$uarr = explode('~',$url);
		$vid = sys_auth($uarr[0]);
		if(!empty($uarr[1])) $type = $uarr[1];
	}else{
		$vid = sys_auth($url);	
		if(strpos($url,'&type=') !== FALSE){
			$type = end(explode('&type=',$url));
		}
	}
}
$hd = empty($_GET['hd']) ? VOD_HD : $_GET['hd'];
$wap = 1;

//判断地址解密
if(empty($vid)){
    get_json(array('msg'=>'Url非法操作~!'));
}

//判断http地址模式
$arr = explode('~',$vid);
$vid = $arr[0];
if(empty($type)){
	$type = !empty($arr[1]) ? $arr[1]:'';
}
$ps = isset($_POST['data']) ? 1 : 0;
if($type=='ydisk') $type='';

//m3u8、mp4直连
if(strpos($vid, '.m3u8') !== false) $type = 'm3u8';
if($type=='mp4' || $type=='m3u8'){
	$data['url'] = $vid;
    $data['ext'] = $type;
    $data['msg'] = 'ok';
	get_json($data);
}

//判断切片
if(strpos($vid, '/share/') !== false){
	$filemd5 = FCPATH.'cache/'.md5($vid.USER_TOKEN);
	if(file_exists($filemd5) && ((time() - filemtime($filemd5)) < 1800)){
		$purl = file_get_contents($filemd5);
	}else{
		$purl = share($vid);
		if(!empty($purl)) file_put_contents($filemd5,$purl);
	}
	//兼容乐视地址
	$purl = str_replace('&tss=ios', '&tss=android', $purl);
	
	$data['url'] = $purl;
    $data['ext'] = 'm3u8';
    $data['msg'] = 'ok';
	get_json($data);
}

//判断其他解析
$host = $_GET['host'];
if(!empty($host)){
	//平民解析 , APP接口文件返回的一定要是直接播放的视频地址
	if(strpos($host, 'mdparse') !== false){
		$purl = $host.'?id='.$vid.'&sign='.md5('平民密钥'.$vid);
		$data['url'] = $purl;
		$data['ext'] = 'm3u8'; //m3u8 or mp4
	    $data['msg'] = 'ok';
		get_json($data);
	}
	//其他解析接口待增加
	//...
}

//组装URL参数
$param = 'url='.$vid.'&type='.$type.'&hd='.$hd.'&wap='.$wap;

//判断缓存是否存在
$wx = 0;
$cache=0;
$filemd5 = FCPATH.'cache/'.md5($param.USER_TOKEN.$wx);
if($up==0 && file_exists($filemd5)){
	$json = file_get_contents($filemd5);
	$arr = json_decode($json,1);
	$ext = $arr['ext'];
	$ctime = $arr['ctime'];
	$vodurl = $arr['url'];
	if($ctime > time()) $cache++;
}
if($cache==0){
	$apiurl = API_URL.'?uid='.USER_ID.'&up='.$up.'&token='.USER_TOKEN.'&'.$param;
	$json = get_url($apiurl);
	$arr = json_decode($json,1);
	$ext = $arr['ext'];
	$vodurl = $arr['url'];
	if(empty($vodurl) || $arr['success']==0){
		get_json(array('msg'=>$arr['msg']));
	}
	if($ext=='m3u8_list'){

		$filem3u8 = FCPATH.'cache/'.md5($vid.USER_TOKEN).'.m3u8';
		file_put_contents($filem3u8,base64_decode($vodurl));

		$arr['url'] = 'http://'.$_SERVER['HTTP_HOST'].WEB_PATH.'cache/'.md5($vid.USER_TOKEN).'.m3u8';
		$arr['ext'] = 'm3u8';
		$json = json_encode($arr);
		$vodurl = $arr['url'];
		$ext = $arr['ext'];
	}
	file_put_contents($filemd5,$json);
}
if($ext=='xml'){
	if(is_array($vodurl)){
		$ext = 'fd';
	}else{
		$ext = strpos($vodurl, '.m3u8') !== false ? 'm3u8' : 'mp4';
	}
}
if($ext=='m3u8' && is_array($vodurl)){
	$vodurl = $vodurl[0];
}
if(strpos($vodurl, 'lecloud.com')){
	$vodurl = str_replace('&tss=ios', '&tss=android', $vodurl);
}
$data['url'] = $vodurl;
$data['ext'] = $ext;
$data['msg'] = 'ok';
get_json($data);


//解析share
function share($url){
	$host = current(explode('/share/', $url));
	$str = geturl2($url);
	$m3u8  = str_substr('var main = "','"',$str);
	if(empty($m3u8)){
		$token = str_substr('var requestToken = "','"',$str);
		$tokenurl = $host.'/token/'.$token;
		$str = geturl2($tokenurl);
		$m3u8 = str_substr('"main":"','"',$str);
	}
	if(empty($m3u8)) return '';
	$purl = $host.$m3u8;
	return $purl;
}
// 字符串截取函数
function str_substr($start, $end, $str){
    $temp = explode($start, $str, 2);
    $content = explode($end, $temp[1], 2);
    return $content[0];
}

//获取远程内容
function geturl2($url,$header='',$post='',$ip='',$ups=''){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_ENCODING, "gzip");
	if(!empty($header)){
	   curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	}
	if(!empty($post)){
	   curl_setopt($ch, CURLOPT_POST, 1);
	   curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}
	if(!empty($ip)){
	    curl_setopt($ch, CURLOPT_PROXY, $ip);
		if(!empty($ups)){
		    curl_setopt($ch, CURLOPT_PROXYUSERPWD, $ups);
		}
	}
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);//获取跳转后的
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}