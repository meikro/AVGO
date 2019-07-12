<?php
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-11
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Vod extends Ctcms_Controller {

	public function __construct(){
		parent::__construct();
		//加载会员模型
		$this->load->model('user');
        //判断登陆
		$this->user->login();
        //当前模版
		$this->load->get_templates('user');
	}

	//我上传的视频
    public function index($cid=0,$page=0) {
		$cid=(int)$cid;
		$page=(int)$page;
		if($cid==0) $cid=(int)$this->input->get('id');
		if($page==0) $page=(int)$this->input->get('page');
		if($page==0) $page=1;
		$data['ctcms_title'] = '我的视频 - '.Web_Name;
		$data['ctcms_cid'] = $cid;

		$row = $this->csdb->get_row_arr('user','*',array('id'=>$_SESSION['user_id']));
		//获取模板
		$str=load_file('vod.html','user');
        //预先解析分页标签
		preg_match_all('/{ctcms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/ctcms:\1}/',$str,$page_arr);
		if(!empty($page_arr) && !empty($page_arr[3])){
            //每页数量
			$per_page = (int)$page_arr[3][0];
			//组装SQL数据
			$sql = "select {field} from ".CT_SqlPrefix."vod where uid=".$_SESSION['user_id'];
			if($cid>0) $sql.=" and cid=".$cid;
			$sqlstr = $this->parser->ctcms_sql($page_arr[1][0],$page_arr[2][0],$page_arr[0][0],$page_arr[4][0],$sql);
			//总数量
			$total = $this->csdb->get_sql_nums($sqlstr);
			//总页数
	        $pagejs = ceil($total / $per_page);
			if($total<$per_page) $per_page=$total;
			$sqlstr .= ' limit '.$per_page*($page-1).','.$per_page;
			$str = $this->parser->ctcms_skins($page_arr[1][0],$page_arr[2][0],$page_arr[0][0],$page_arr[4][0],$str, $sqlstr);
            //解析分页
			$pagenum = getpagenum($str);
			$pagearr = get_page($total,$pagejs,$page,$pagenum,'user','vod/index',$cid); 
			$pagearr[] = $per_page;$pagearr[] = $total;$pagearr[] = $pagejs;$pagearr[] = $page;
			$str = getpagetpl($str,$pagearr);
		}
		//全局解析
		$str=$this->parser->parse_string($str,$data,true,false);
		//当前会员数据
		$str=$this->parser->ctcms_tpl('user',$str,$str,$row);
		//IF判断解析
		$str=$this->parser->labelif($str);
		echo $str;
	}

	//上传视频
    public function add() {
		$data['ctcms_title'] = '分享视频 - '.Web_Name;
		$data['ctcms_formurl'] = links('user','vod/save');
		$data['ctcms_vodsave'] = links('user','vod/upvod');
		$data['ctcms_zmurl'] = (Zhuan_Url == '' ? '' : '//'.Zhuan_Url).links('zhuan','init');
		$row = $this->csdb->get_row_arr('user','*',array('id'=>$_SESSION['user_id']));
		//获取模板
		$str=load_file('vod-add.html','user');
		//全局解析
		$str=$this->parser->parse_string($str,$data,true,false);
		//当前会员数据
		$str=$this->parser->ctcms_tpl('user',$str,$str,$row);
		//IF判断解析
		$str=$this->parser->labelif($str);
		echo $str;
    }

    //保存视频
    public function save() {
    	$arr['name'] = $this->input->get_post('name',true);
    	$arr['cid'] = (int)$this->input->get_post('cid',true);
    	$arr['pic'] = $this->input->get_post('pic',true);
    	$arr['zhuyan'] = $this->input->get_post('zhuyan',true);
    	$arr['type'] = $this->input->get_post('type',true);
    	$url = $this->input->get_post('url',true);
    	if(empty($arr['name'])) msg_url('视频标题不能为空~!','javascript:history.back();','no');
    	if($arr['cid'] == 0) msg_url('请选择视频分类~!','javascript:history.back();','no');
    	if(empty($url)) msg_url('请先上传视频~!','javascript:history.back();','no');
    	$arr['url'] = 'ck###高清$'.$url;
    	$arr['uid'] = $_SESSION['user_id'];
    	$arr['yid'] = 1;
    	$arr['addtime'] = time();
		//入库
		$res = $this->csdb->get_insert('vod',$arr);
		if($res){
			msg_url('视频分享成功，等待转码!',links('user','vod'),'ok');
		}else{
			msg_url('入库失败，稍后再试~!','javascript:history.back();','no');
		}
    }

	//批量保存
	public function upvod(){
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
		header("Cache-Control: no-store, no-cache, must-revalidate"); 
		header("Cache-Control: post-check=0, pre-check=0", false); 
		header("Pragma: no-cache"); 
		set_time_limit(0);

		if(Zhuan_Is == 1){
			if(substr(Zhuan_Path,0,2) == './'){
				$Video_Path = FCPATH.substr(Zhuan_Path,2);
			}else{
				$Video_Path = Zhuan_Path;
			}
		}else{
			$Video_Path = FCPATH.'vod/';
		}
		if(substr($Video_Path,-1) != '/') $Video_Path .= '/';
		//分片文件夹
		$targetDir = $Video_Path.'temp/';
		//保存盘符路径
		$uploadDir = $Video_Path.'data/'.date('Ymd').'/';
		//定义允许上传的文件扩展名
		$ext_arr = array_filter(explode(',', 'avi,wmv,mpeg,mp4,mov,mkv,flv,f4v,m4v,rmvb,rm,mpg'));
		//防止外部跨站提交
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { 
			$this->msg(1,'外部跨站提交');
		}
		//上传出错
		if (!empty($_REQUEST[ 'debug' ]) ) { 
			$random = rand(0, intval($_REQUEST[ 'debug' ]) ); 
			if ( $random === 0 ) { 
				$this->msg(1,'未知错误');
			}
		}
		//创建目录
		if (!file_exists($uploadDir)) { 
			mkdirss($uploadDir); 
		}
		//原文件名
		if(!isset($_FILES['video']['name'])){
			$this->msg(1,'No FILES');
		}
		$file_name = $_FILES['video']['name'];
		//文件后缀
		$file_ext = strtolower(trim(substr(strrchr($file_name, '.'), 1)));
		//判断文件后缀
		if(in_array($file_ext, $ext_arr) == false) {
			$this->msg(1,"不支持的格式");
		}
		//根据文件名和会员ID生成一个唯一MD5
		$fileName = md5($file_name).'.'.$file_ext;
		$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName; 
		//分片ID
		$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0; 
		//分片总数量
		$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;  
		//打开临时文件 
		if (!$out = @fopen("{$filePath}_{$chunk}.parttmp", "wb")) { 
			$this->msg(1,"Failed to open output stream.");
		} 
		if (!empty($_FILES)) {
			if ($_FILES["video"]["error"] || !is_uploaded_file($_FILES["video"]["tmp_name"])) { 
				$this->msg(1,"Failed to move uploaded file,error->".$_FILES["video"]["error"]);
			} 
			//读取二进制输入流并将其附加到临时文件
			if (!$in = @fopen($_FILES["video"]["tmp_name"], "rb")) { 
				$this->msg(1,"Failed to open input stream.");
			} 
		} else { 
			if (!$in = @fopen("php://input", "rb")) { 
				$this->msg(1,"Failed to open input stream.");
			}
		} 
		while ($buff = fread($in, 4096)) { 
			fwrite($out, $buff); 
		} 
		@fclose($out); 
		@fclose($in); 
		rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part"); 
		$index = 0; 
		$done = true; 
		for( $index = 0; $index < $chunks; $index++ ) { 
			if (!file_exists("{$filePath}_{$index}.part") ) { 
				$done = false; 
				break; 
			} 
		}
		if($done){ 
			$pathInfo = pathinfo($fileName); 
			$hashStr = substr(md5($pathInfo['basename']),8,5);
			$hashName = date('YmdHis').$hashStr.'.'.$pathInfo['extension']; 
			$uploadPath = $uploadDir.$hashName;
			if (!$out = @fopen($uploadPath, "wb")) { 
				$this->msg(1,"Failed to open output stream.");
			} 
			if ( flock($out, LOCK_EX) ) { 
				for( $index = 0; $index < $chunks; $index++ ) { 
					if (!$in = @fopen("{$filePath}_{$index}.part", "rb")) { 
						break; 
					} 
					while ($buff = fread($in, 4096)) { 
						fwrite($out, $buff); 
					} 
					@fclose($in); 
					@unlink("{$filePath}_{$index}.part"); 
				} 
				flock($out, LOCK_UN); 
			} 
			@fclose($out);

			if(Zhuan_Is == 1){
				$this->load->library('xyz');
				$format = $this->xyz->format($uploadPath);
				$data['duration'] = $format['duration'];
				$data['path'] = $uploadPath;
				$data['addtime'] = time();
				$data['m3u8_dir'] = substr(Zhuan_Path,0,2) == './' ? FCPATH : Zhuan_Path;
				$data['m3u8_path'] = get_m3u8_url($data['addtime'],$data['path']);
				$data['pic_path'] = get_m3u8_url($data['addtime'],$data['path'],'jpg');
				$this->db->insert("zhuanma",$data);

				$arr['id'] = $this->db->insert_id();
				$arr['name'] = str_replace('.'.$file_ext, '', $file_name);
				if(Zhuan_M3u8_Url !== ''){
					$arr['url'] = 'http://'.Zhuan_M3u8_Url.$data['m3u8_path'];
					$arr['pic'] = 'http://'.Zhuan_M3u8_Url.$data['pic_path'];
				}else{
					$arr['url'] = $data['m3u8_path'];
					$arr['pic'] = $data['pic_path'];
				}
				//增加视频截图
				$pid = array();
				for($i=1; $i<=Zhuan_Jpg_Num;$i++){ 
					$addp['url'] = str_replace('.jpg', '_'.$i.'.jpg', $data['pic_path']);
					$pid[] = $this->csdb->get_insert('pic',$addp);
				}
				if(!empty($pid)){
					setcookie('vpic',implode('|',$pid),time()+7200,Web_Path);
				}else{
					setcookie('vpic','',time()-3600,Web_Path);
				}
			}else{
				$arr['id'] = 0;
				$arr['name'] = '';
				$arr['url'] = str_replace(FCPATH,Web_Path,$uploadPath);
				$arr['pic'] = '';
			}
			$arr['msg'] = '上传成功';

			$this->msg(0,$arr);
		}
	}

	//上传返回
	public function msg($code=0,$str='')
	{
		if(is_array($str)){
			$arr = $str;
			$arr['code']=$code;
		}else{
			$arr['code']=$code;
			$arr['str']=$str;
		}
		echo json_encode($arr);
		exit;
	}
}