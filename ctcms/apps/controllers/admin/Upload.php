<?php 
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-11
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload extends Ctcms_Controller {

	function __construct() {
	    parent::__construct();
		//加载后台模型
		$this->load->model('admin');
		$this->load->helper('string');
        //当前模版
		$this->load->get_templates('admin');
	}

	public function index()
	{
		$this->admin->login();
        $ac = $this->input->get('ac',true);
		$dir = array('vpic','vod','link','liwu','other','editor');
		if(!in_array($ac,$dir)) $ac='other';
        //定义允许上传的文件扩展名
        $ext_arr = array('*.gif', '*.jpg', '*.jpeg', '*.png', '*.bmp');
		$data['types'] = implode(';',$ext_arr);
		$str['id'] = (int)$_SESSION['admin_id'];
		$str['login'] = $_SESSION['admin_login'];
        $data['key'] = sys_auth(addslashes(serialize($str)));
        $data['dir'] = $ac;
        $data['sid'] = $this->input->get('sid',true);
        $data['len'] = (int)$this->input->get('len',true);
        $data['upsave'] = links('upload','save');
		$this->load->view('upload.tpl',$data);
	}

    //保存附件
	public function save()
	{	
		$key = $this->input->get_post('key',true);
		$login = $this->admin->login($key,1);
		if($login == 0) $this->msg(1,'您已登陆超时~!');
        $dir = $this->input->get_post('dir',true);
        $dir_arr = array('vpic','vod','link','liwu','other','editor');
		if(!in_array($dir,$dir_arr)) $dir='other';

		//上传目录
		$path = FCPATH.'attachment/'.$dir.'/'.date('Ym').'/'.date('d').'/';
		if (!is_dir($path)) {
            mkdirss($path);
        }
        $files = $dir == 'editor' ? 'file' : 'Filedata';
		$tempFile = $_FILES[$files]['tmp_name'];
		$file_name = $_FILES[$files]['name'];
		$file_size = filesize($tempFile);
        $file_ext = strtolower(trim(substr(strrchr($file_name, '.'), 1)));

        //检查扩展名
        if($file_ext=='jpg' || $file_ext=='png' || $file_ext=='gif' || $file_ext=='bmp' || $file_ext=='jpeg'){
			list($width, $height, $type, $attr) = getimagesize($tempFile);
			if ( intval($width) < 10 || intval($height) < 10 || $type == 4 ) {
                $this->msg(1,'图片格式不正确');
			}
		}else{
			$this->msg(1,'文件格式不支持');
		}
        //PHP上传失败
        if (!empty($_FILES[$files]['error'])) {
            switch($_FILES[$files]['error']){
	            case '1':
		            $error = '超过php.ini允许的大小。';
		            break;
	            case '2':
		            $error = '超过表单允许的大小。';
		            break;
	            case '3':
		            $error = '图片只有部分被上传。';
		            break;
	            case '4':
		            $error = '请选择图片。';
		            break;
	            case '6':
		            $error = '找不到临时目录。';
		            break;
	            case '7':
		            $error = '写文件到硬盘出错。';
		            break;
	            case '8':
		            $error = 'File upload stopped by extension。';
		            break;
	            case '999':
	            default:
		            $error = '未知错误。';
            }
            $this->msg(1,$error);
        }
        //新文件名
		$file_name = random_string('alnum', 20). '.' . $file_ext;
		$file_path = $path.$file_name;
		if(move_uploaded_file($tempFile, $file_path) !== false) { //上传成功
			$filepath = '/'.date('Ym').'/'.date('d').'/'.$file_name;
			$filepath = Web_Path.'attachment/'.$dir.$filepath;
			if($dir == 'editor'){
				$this->msg(0,array('data'=>array('src'=>$filepath)));
			}else{
				$this->msg(0,$filepath);
			}
		}else{ //上传失败
			$this->msg(1,'上传失败');
		}
	}

	public function vod()
	{
		$this->admin->login();
        //定义允许上传的文件扩展名
		$str['id'] = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : $_COOKIE['ctcms_admin_id'];
		$str['login'] = isset($_SESSION['admin_login']) ? $_SESSION['admin_login'] :  $_COOKIE['ctcms_admin_log'];
        $key = sys_auth(addslashes(serialize($str)));
        $data['sid'] = (int)$this->input->get('sid');
        $data['upsave'] = links('upload','vod_save','','key='.$key);
		$this->load->view('upload_vod.tpl',$data);
	}

	//批量保存
	public function vod_save(){
		$key = $this->input->get('key');
		$log = $this->admin->login($key,1);
		if($log == 0) $this->msg(1,'非法请求');
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