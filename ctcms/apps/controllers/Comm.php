<?php
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-11
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Comm extends Ctcms_Controller {

	function __construct(){
	    parent::__construct();
		$this->load->get_templates();
		$this->load->library('parser');
		define('IS_LOG', true);
		$this->load->model('user');//加载会员模型
	}

    //圈子列表
	public function index($cid=0,$page=0){
		$cid = (int)$cid;
		$page = (int)$page;
		if($cid==0) $cid = (int)$this->input->get('id');
		if($page==0) $page=(int)$this->input->get('page');
		if($page==0) $page=1;

		$data = array();
		$data['ctcms_title'] = '交流圈 - '.Web_Name;
		$data['ctcms_cid'] = $cid;
		$data['ctcms_commaddlink'] = links('comm','editor',$cid);
        if($cid>0){
            $thisc = $this->csdb->get_row_arr('circle','*',$cid);
			if(!$thisc) msg_url('该分类不存在~!',links('comm'));
			$data['ctcms_title'] = $thisc['name'].' - 交流圈';
        }else{
            $thisc['id'] = 0;
        }

        //获取模板
        $str = load_file('comm.html');
        //预先解析分页标签
		preg_match_all('/{ctcms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/ctcms:\1}/',$str,$page_arr);
        if(!empty($page_arr) && !empty($page_arr[3])){
              //每页数量
			  $per_page = (int)$page_arr[3][0];
		      //组装SQL数据
		      if($cid>0){
                  $sql = "select {field} from ".CT_SqlPrefix."comm where cid=".$cid;
              }else{
                  $sql = '';
              }
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
			  $cid2 = ($cid==0) ? 'all' : $cid;
              $pagearr = get_page($total,$pagejs,$page,$pagenum,'comm','index',$cid2);
	          $pagearr[] = $per_page;$pagearr[] = $total;$pagearr[] = $pagejs;$pagearr[] = $page;
	          $str = getpagetpl($str,$pagearr);
		}
		$str=$this->parser->parse_string($str,$data,true,FALSE);
		$str=$this->parser->ctcms_tpl('circle',$str,$str,$thisc);
		//IF判断解析
		$str=$this->parser->labelif($str);
		echo $str;
	}

    //文章内容
	public function article($id=0,$page=null){

		$id=(int)$id;
		$page=(int)$page;
		if($id==0) $id=(int)$this->input->get('id');
		if($page==0) $page=(int)$this->input->get('page');
		if($page==0) $page=1;
		if($id==0) msg_url('参数错误',links('comm'));

	    $data = array();
	    $thisa = $this->csdb->get_row_arr('comm','*',$id);
		if(!$thisa) msg_url('该文章不存在~!',links('comm'));

	    $thisa['colllink'] = links('comm','collect');
	    $thisa['dzlink'] = links('comm','clike');
	    $thisa['replylink'] = links('comm','reply');
	    $thisa['replydellink'] = links('comm','replydel');

		$data['ctcms_title'] = $thisa['title'].' - 交流圈 - '.Web_Name;

	    if(empty($_SESSION['user_id']) || (int)$_SESSION['user_id']==0){
	    	$thisa['coll'] = 0;
	    	$thisa['dz'] = 0;
	    }else{
		    $coll = $this->csdb->get_row_arr('coll','*',array('uid'=>$_SESSION['user_id'],'tid'=>$thisa['id']));
		    if($coll){$thisa['coll'] = 1;}else{$thisa['coll'] = 0;}
		    $dz = $this->csdb->get_row_arr('dz','*',array('uid'=>$_SESSION['user_id'],'tid'=>$thisa['id']));
		    if($dz){$thisa['dz'] = 1;}else{$thisa['dz'] = 0;}
	    }

        //获取模板
        $str = load_file('article.html');
        //预先解析分页标签
		preg_match_all('/{ctcms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/ctcms:\1}/',$str,$page_arr);
        if(!empty($page_arr) && !empty($page_arr[3])){

              //每页数量
			  $per_page = (int)$page_arr[3][0];
		      //组装SQL数据
			  $sql = "select {field} from ".CT_SqlPrefix."msg where tid=".$id;
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
			  $pagearr = get_page($total,$pagejs,$page,$pagenum,'comm','article',$id);
	          $pagearr[] = $per_page;$pagearr[] = $total;$pagearr[] = $pagejs;$pagearr[] = $page;
	          $str = getpagetpl($str,$pagearr);
		}

		$str=$this->parser->parse_string($str,$data,true,FALSE);
		$str=$this->parser->ctcms_tpl('article',$str,$str,$thisa);
		$str=$this->parser->labelif($str);

		$arr = explode('</body>',$str);
		$jsurl = '<script type="text/javascript" src="'.links('hits','article',$id).'"></script></body>';
		echo $arr[0].$jsurl.$arr[1];
	}

    //发表文章
	public function editor($cid=0){
		if((int)$cid==0) $cid = (int)$this->input->get('id');

		if(!$this->user->login(1)){
			msg_url('请先登陆~!',links('user','login'));
		}
	    //这里可以自定义数组内容到模板 ，$data['title'] = '内容';
		$data['ctcms_title'] = '发表文章 - '.Web_Name;
		$data['addlink'] = links('comm','edsubmit');
		$data['ctcms_uppicurl'] = links('comm','uppic');
		$data['ctcms_times'] = time();
		$data['ctcms_token'] = md5($_SESSION['user_id'].$data['ctcms_times'].CT_Encryption_Key);
		$data['ctcms_cid'] = $cid;
		$data['ctcms_codeurl'] = links('comm','codes');
	    //获取模板
	    $str = load_file('editor.html');
		//全局解析
	    $this->parser->parse_string($str,$data);
	}

	//文章入库
	public function edsubmit(){
		if(!$this->user->login(1)){
			echo json_encode(array('msg'=>'请先登陆'));
			exit;
		}
        //滑动验证
        require_once CTCMSPATH . 'codes/class.geetestlib.php';
	    require_once CTCMSPATH . 'codes/config.php';
	    $GtSdk = new GeetestLib(CAPTCHA_ID, PRIVATE_KEY);
	    $codes_id = $_SESSION['codes_id'];
		$geetest_challenge = $this->input->post('challenge');
		$geetest_validate = $this->input->post('validate');
		$geetest_seccode = $this->input->post('seccode');
	    if ($_SESSION['gtserver'] == 1) {
    	     $result = $GtSdk->success_validate($geetest_challenge, $geetest_validate, $geetest_seccode, $codes_id);
    	     if (!$result) {
				 $data['msg'] = "滑动验证失败!";
				 echo json_encode($data);exit;
    	     }
	    }else{
    	     if(!$GtSdk->fail_validate($geetest_challenge,$geetest_validate,$geetest_seccode)){
				 $data['msg'] = "滑动验证失败!";
				 echo json_encode($data);exit;
    	     }
	    }

		$post = array(
			'title' 	=> $this->input->post('title',true),
			'content' 	=> $this->input->post('content'),
			'cid' 	=> (int)$this->input->post('circle'),
			'uid' 		=> $_SESSION['user_id'],
			'addtime' 	=> time(),
		);
		//判断插入视频
		preg_match_all('/<iframe height="(.*)" width="(.*)" src="(.*)" frameborder="0" allowfullscreen=""><\/iframe>/',$post['content'],$arr);
		$vod = array();
		for($i=0;$i<count($arr[0]);$i++){
            if(substr($arr[3][$i],0,24)=='http://player.youku.com/' || 
               substr($arr[3][$i],0,22)=='http://open.iqiyi.com/' || 
			   substr($arr[3][$i],0,16)=='http://v.qq.com/' || 
			   substr($arr[3][$i],0,21)=='http://www.tudou.com/'){
               $post['content'] = str_replace($arr[0][$i], '%s', $post['content']);
               $vod[]=$arr[0][$i];
			}
		}
		$post['content'] = remove_xss($post['content']);
		//换回原来的视频代码
		if(!empty($vod)){
			$post['content'] = vsprintf($post['content'],$vod);
		}
		$res = $this->csdb->get_insert('comm',$post);
		if($res){
			$data['link'] = links('comm','article',$res);
			$data['msg'] = 'ok';
		}else{
			$data['msg'] = "数据异常，请重试";
		}
        echo json_encode($data);
	}

    //回复文章
	public function reply(){
		if(!$this->user->login(1)){
			echo json_encode(array('msg'=>'请登录后参与回复！'));exit;
		}
		$post = array(
			'rid' => (int)$this->input->post('id'),
			'ruid' => (int)$this->input->post('uid'),
			'tid' => (int)$this->input->post('tid'),
			'content' => $this->input->post('content', TRUE),
			'addtime' => time(),
			'uid' => $_SESSION['user_id'],
		);

		$res = $this->csdb->get_insert('msg',$post);
		if($res){
			$data['msg']='ok';
			$data['id']=$res;
			$data['uid']=$_SESSION['user_id'];
			$data['upic']=getzd('user','pic',$_SESSION['user_id']);
			$data['uname']=getzd('user','nichen',$_SESSION['user_id']);
			$data['addtime']=date('Y-m-d H:i:s');
			$data['dellink'] = links('comm','replydel');
		}else{
			$data['msg']='数据异常，请稍后重试！';
		}
        echo json_encode($data);
	}

    //赞文章
	public function clike(){
		if(!$this->user->login(1)){
			echo "请登录后参与点赞！";exit();
		}
		$post = array(
			'tid' => (int)$this->input->post('tid'),
			'uid' => $_SESSION['user_id'],
			'addtime' => time(),
		);
		if($post['tid'] == 0) exit('参数错误');
		$arr = array(
			'uid'=>$post['uid'],
			'tid'=>$post['tid'],
		);
		$result = $this->csdb->get_row_arr('dz','*',$arr);
		$result2 = $this->csdb->get_row_arr('comm','*',$post['tid']);
		if($result){
			$res = $this->csdb->get_del('dz',$result['id']);
			$res2 = $this->csdb->get_update('comm',$post['tid'],array('dznum'=>$result2['dznum']-1));
		}else{
			$res = $this->csdb->get_insert('dz',$post);
			$res2 = $this->csdb->get_update('comm',$post['tid'],array('dznum'=>$result2['dznum']+1));
		}
		if($res){
			echo '1';
		}else{
			echo "数据异常，请稍后重试！";
		}
		
	}

    //收藏文章
	public function collect(){
		if(!$this->user->login(1)){
			echo "请登录后收藏文章！";exit();
		}
		$post = array(
			'tid' => (int)$this->input->post('tid'),
			'uid' => $_SESSION['user_id'],
			'addtime' => time(),
		);
		if($post['tid'] == 0) exit('参数错误');
		$arr = array(
			'uid'=>$post['uid'],
			'tid'=>$post['tid'],
		);
		$result = $this->csdb->get_row_arr('coll','*',$arr);
		$result2 = $this->csdb->get_row_arr('comm','*',$post['tid']);
		if($result){
			$res = $this->csdb->get_del('coll',$result['id']);
			$res2 = $this->csdb->get_update('comm',$post['tid'],array('collnum'=>$result2['collnum']-1));
		}else{
			$res = $this->csdb->get_insert('coll',$post);
			$res2 = $this->csdb->get_update('comm',$post['tid'],array('collnum'=>$result2['collnum']+1));
		}
		if($res){
			echo '1';
		}else{
			echo "数据异常，请稍后重试！";
		}
		
	}

	//删除回复
	public function replydel(){
		if(!$this->user->login(1)){
			exit('请先登陆~!');
		}
		$id = (int)$this->input->post('id');
		if($id==0) exit('参数错误');
		$row = $this->csdb->get_row_arr('msg','uid',$id);
		if($row['uid']!=$_SESSION['user_id']) exit('您没有权限删除别人的留言~!');
        $this->csdb->get_del('msg',$id);
		//下级回复
		$this->csdb->get_del('msg',$id,'rid');
		echo 'ok';
	}

    //上传图片
	public function uppic(){

        $image = array('gif', 'jpg', 'jpeg', 'png', 'bmp');
		$uid = (int)$this->input->get_post('uid');
		$t = $this->input->get_post('t');
		$token = $this->input->get_post('token');

		if($uid==0 || empty($t) || empty($token)){
            echo json_encode(array('msg'=>'参数错误'));exit;
		}
		$time = time()-600;
		if($token!=md5($uid.$t.CT_Encryption_Key) || $t<$time){
            echo json_encode(array('msg'=>'非法上传'));exit;
		}
		foreach ($_FILES as $k=>$v) {
            $tempFile = $v["tmp_name"];
            $tempname = $v["name"];
		}
		$file_ext = strtolower(trim(substr(strrchr($tempname, '.'), 1)));
		//判断文件后缀
		if (in_array($file_ext, $image) === false) {
		    echo json_encode(array('msg'=>'请上传正确的图片'));exit;
	    }
		$picname  = date('YmdHis').rand(1111,9999).".".$file_ext;
		$picdirs  = date('Ym')."/".date('d')."/".$picname;
		$filename = FCPATH.'attachment/comm/'.$picdirs; 
		$filepath = Web_Path.'attachment/comm/'.$picdirs; 
		if (!empty($tempFile)) {
			//创建当前文件件
			$dir = FCPATH."attachment/comm/".date('Ym')."/".date('d');
			mkdirss($dir);

			if (move_uploaded_file($tempFile, $filename) === false) { 
			   echo json_encode(array('msg'=>'上传失败'));exit;
			}

			list($width, $height, $type, $attr) = getimagesize($filename);
			if ( intval($width) < 10 || intval($height) < 10 || $type == 4 ) {
				unlink($filename);
			    echo json_encode(array('msg'=>'请上传正确的图片'));exit;
			}

			//判断远程附件
			if(Ftp_Is>0){
				$this->load->library('ftpup');
				$res = $this->ftpup->up($filename,$picname);
				if($res) $filepath = $res;
			}
			echo json_encode(array('code'=>0,'msg'=>'上传成功','data'=>array('src'=>$filepath)));exit;           
		} else {
			echo json_encode(array('msg'=>'上传失败'));exit;
		}
	}

	//生成发文章验证码
	public function codes(){
	    require_once CTCMSPATH . 'codes/class.geetestlib.php';
	    require_once CTCMSPATH . 'codes/config.php';
	    $GtSdk = new GeetestLib(CAPTCHA_ID, PRIVATE_KEY);
	    $status = $GtSdk->pre_process(1500);
	    $_SESSION['gtserver'] = $status;
	    $_SESSION['codes_id'] = rand(11111,99999);
	    echo $GtSdk->get_response_str();
	}
}