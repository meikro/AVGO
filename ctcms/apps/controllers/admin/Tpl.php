<?php
/**
 * @Ctcms open source management system
 * @copyright 2008-2019 ctcms.cn. All rights reserved.
 * @Author:Cheng Kai Jie
 * @Dtime:2016-10-20
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Tpl extends Ctcms_Controller {
	function __construct(){
	    parent::__construct();
		//加载后台模型
		$this->load->model('admin');
        //当前模版
		$this->load->get_templates('admin');
		//判断是否登陆
		$this->admin->login();
		$this->load->helper('file');
	}

    //模版列表
	public function index($dir2='skins')
	{
		$dir = $this->input->get('id',true);
		$path = $this->input->get('path',true);
		$path = str_replace('//','',str_replace('.','',$path));
		if(empty($dir)) $dir = $dir2;
		$dirarr = array('skins','user','mobile','mobile_user');
		if(!in_array($dir,$dirarr)){
			admin_msg('非法模版路径~！','javascript:history.back();','no');
		}
		$dir_path=FCPATH.'template/'.$dir.'/'.$path;
		if(!is_dir($dir_path)){
			admin_msg('模版路径错误~！','javascript:history.back();','no');
		}
		$dir_arr=get_dir_file_info($dir_path, $top_level_only = TRUE);
		$dirs=$list=array();
		if(!empty($dir_arr)){
				foreach ($dir_arr as $t) {
					$times=date('Y-m-d H:i:s',$t['date']);
					if (is_dir($t['server_path'])) {
						$mr = 0;
						if(empty($path)){
							$mrlink = links('tpl','init',0,'ac='.$dir.'&dir='.$t['name']);
						}
						if(($t['name'] == Web_Skin && $dir=='skins') || 
							($t['name'] == Wap_Skin && $dir=='mobile') || 
							($t['name'] == Wap_User_Skin && $dir=='mobile_user') || 
							($t['name'] == User_Skin && $dir=='user')){
								$mr = 1;
								$mrlink = '';
						}
						$dirs[] = array(
						   'name' => $t['name'],
						   'mr'   => $mr,
						   'date' => (date('Y-m-d',$t['date'])==date('Y-m-d'))?'<font color=red>'.$times.'<font>':$times,
						   'size' => '--',
						   'link' => links('tpl','index',$dir,'path='.$path.$t['name'].'/'),
						   'mrlink' => $mrlink,
						   'dellink' => links('tpl','del',0,'file='.$dir.'/'.$path.$t['name'].'/'),
						);
					} else {
						$exts = strtolower(trim(strrchr($t['name'], '.'), '.'));
						if($exts=='html' || $exts=='css' || $exts=='js' || $exts=='jpg' || $exts=='jpge' || $exts=='png' || $exts=='bmp'){
							$times=date('Y-m-d H:i:s',$t['date']);
							$type = 0;
							if($exts=='jpg' || $exts=='jpge' || $exts=='png' || $exts=='bmp'){
								$link = 'http://'.Web_Url.Web_Path.'template/'.$dir.'/'.$path.$t['name'];
								$type = 1;
							}else{
								$link = links('tpl','edit',0,'file='.$dir.'/'.$path.$t['name']);
							}
							$list[] = array(
								'name' => $t['name'],
								'title'=> $this->ext($t['name'],$exts,$dir),
								'date' => (date('Y-m-d',$t['date'])==date('Y-m-d'))?'<font color=red>'.$times.'<font>':$times,
								'size' => formatsize($t['size']),
								'link' => $link,
								'type' => $type,
								'blink' => links('tpl','copyt',0,'file='.$dir.'/'.$path.$t['name']),
								'dellink' => links('tpl','del',0,'file='.$dir.'/'.$path.$t['name']),
							);
						}
					}
				}
		}
        $data['dir'] = $dirs;
        $data['list'] = $list;
		$data['path'] = $dir.'/'.$path;
        if(substr($data['path'],-1)=='/') $data['path'] = substr($data['path'],0,-1);
        $data['one'] = empty($path) ? 0 : 1;
		$this->load->view('head.tpl',$data);
		$this->load->view('tpl_index.tpl');
	}

    //模版新增
	public function add()
	{
		$file = $this->input->get('path',true);
		$file = str_replace('//','',str_replace('..','',$file));
		$file_path = FCPATH.'template/'.$file;
		if(!is_dir($file_path)){
			admin_msg('模版路径错误~！','javascript:history.back();','no');
		}
		$data['html']='';
		$data['file'] = '';
		$data['dir'] = $file;
		$this->load->view('head.tpl',$data);
		$this->load->view('tpl_edit.tpl');
	}

    //模版编辑
	public function edit()
	{
		$file = $this->input->get('file',true);
		$file = str_replace('//','',str_replace('..','',$file));
		$file_path = FCPATH.'template/'.$file;
		if(!file_exists($file_path)){
			admin_msg('模版文件不存在~！','javascript:history.back();','no');
		}
        $html=get_bm(read_file($file_path));
		$data['html']=str_replace('</textarea>','&lt;/textarea&gt;',$html);
		$wj = end(explode('/',$file));
		$data['file'] = $wj;
		$data['dir'] = str_replace('/'.$wj,'',$file);
		$this->load->view('head.tpl',$data);
		$this->load->view('tpl_edit.tpl');
	}

    //模版修改
	public function save()
	{
		$dir = $this->input->post('dir',true);
		$file = $this->input->post('file',true);
		$dir = str_replace('//','',str_replace('..','',$dir));
		$dir = str_replace(';','_',$dir);
		$file = str_replace('//','',str_replace('..','',$file));
		$file = str_replace(';','_',$file);
		$file_path = FCPATH.'template/'.$dir.'/'.$file;
 	    $html = $this->input->post('html');
		if(empty($file)){
			admin_msg('文件名不能为空~！','javascript:history.back();','no');
		}
		if(!is_dir(FCPATH.'template/'.$dir)){
			admin_msg('模版路径错误~！','javascript:history.back();','no');
		}
		//文件后缀
		$file_ext = strtolower(trim(substr(strrchr($file, '.'), 1)));
		if($file_ext!='html' && $file_ext1='htm' && $file_ext!='js' && $file_ext!='css'){
			admin_msg('文件类型不支持~！','javascript:history.back();','no');
		}

		//写文件
		if (!write_file($file_path, $html)){
			admin_msg('模版保存失败，请稍后再试~!','javascript:history.back();','no');
		}else{
			echo "<script>
		      parent.layer.msg('恭喜您，模版操作成功~!');
		      setInterval('parent.location.reload()',1000); 
              </script>";
		}
	}

    //文件备份
	public function copyt()
	{
		$file = $this->input->get('file',true);
		$file = str_replace('//','',str_replace('..','',$file));
		$file_path = FCPATH.'template/'.$file;
		if(!file_exists($file_path)){
			admin_msg('模版文件不存在~！','javascript:history.back();','no');
		}
		$wj = end(explode('/',$file));
		$dir = str_replace('/'.$wj,'',$file);
		$new_file = str_replace($wj,'backups-'.$wj,$file_path);
		if(copy($file_path,$new_file)){
            admin_msg('模版备份成功~！','javascript:history.back();');
		}else{
            admin_msg('模版备份失败，稍后再试~！','javascript:history.back();','no');
		}
	}

    //文件删除
	public function del()
	{
		$file = $this->input->get('file',true);
		$file = str_replace('//','',str_replace('..','',$file));
		$file_path = FCPATH.'template/'.$file;
		//判断文件夹
		if(is_dir($file_path)){
            $res = deldir($file_path);
		}else{
			if(!file_exists($file_path)){
				admin_msg('模版文件不存在~！','javascript:history.back();','no');
			}
			$res = unlink($file_path);
		}
		if($res){
			admin_msg('模版删除成功~！','javascript:history.back();');
		}else{
			admin_msg('模版删除失败，稍后再试~！','javascript:history.back();','no');
		}
	}

    //根据后缀获取模版名
	public function ext($name,$ext='',$dir='skins')
	{
		if($ext=='css'){
            $title = 'CSS文件';
		}elseif($ext=='js'){
            $title = 'JS文件';
		}elseif($ext=='jpg' || $ext=='jpge' || $ext=='png' || $ext=='bmp'){
            $title = '图片文件';
		}elseif($ext=='html'){
			switch($name){
				case 'head.html':$title='模版头部';break;
				case 'bottom.html':$title='模版底部';break;
				case 'index.html':$title=($dir=='user')?'会员主页':'网站主页';break;
				case 'list.html':$title='视频分类页';break;
				case 'article.html':$title='文章内容';break;
				case 'comm.html':$title='文章列表';break;
				case 'editor.html':$title='发布文章';break;
				case 'pages.html':$title='自定义页面';break;
				case 'play.html':$title='视频播放';break;
				case 'show.html':$title='视频内容';break;
				case 'whole.html':$title='视频智能检索';break;
				case 'ulogin.html':$title='登陆框（登陆前）';break;
				case 'uinfo.html':$title='登陆框（登录后）';break;
				case 'buy.html':$title='消费记录';break;
				case 'comm-fav.html':$title='收藏文章记录';break;
				case 'comm-zan.html':$title='点赞文章记录';break;
				case 'edit.html':$title='资料修改';break;
				case 'edit-logo.html':$title='修改头像';break;
				case 'edit-pass.html':$title='修改密码';break;
				case 'fav.html':$title='视频收藏记录';break;
				case 'left.html':$title='会员左边导航';break;
				case 'login.html':$title='会员登陆';break;
				case 'pass.html':$title='找回密码';break;
				case 'pass-edit.html':$title='找回密码修改';break;
				case 'pay.html':$title='充值升级';break;
				case 'pay-card.html':$title='卡密充值';break;
				case 'pay-cardlist.html':$title='卡密充值记录';break;
				case 'pay-list.html':$title='在线充值记录';break;
				case 'reg.html':$title='消会员注册';break;
				case 'topic.html':$title='视频专题列表';break;
				case 'topic-show.html':$title='视频专题内容';break;
				default:
					if(substr($name,0,4)=='opt-'){
                         $title='opt-自定义';
					}else{
                         $title='其他模版';
					}
				break;
			}
		}else{
            $title = '其他文件';
		}
		return $title;
	}
	
	//设为默认
	public function init()
	{
		$ac = $this->input->get('ac',true);
		$dir = $this->input->get('dir',true);
		$dir = str_replace('/','',str_replace('.','',$dir));
		$arr = array('skins','user','mobile','mobile_user');
		if(!in_array($ac,$arr)){
			admin_msg('非法模版路径~！','javascript:history.back();','no');
		}
		$this->load->helper('file');
		$conf = read_file(CTCMSPATH.'libs/Ct_Config.php');
		if($ac=='user'){
	         $conf = preg_replace("/'User_Skin','(.*?)'/","'User_Skin','".$dir."'",$conf);
		}elseif($ac=='mobile'){
	         $conf = preg_replace("/'Wap_Skin','(.*?)'/","'Wap_Skin','".$dir."'",$conf);
		}elseif($ac=='mobile_user'){
	         $conf = preg_replace("/'Wap_User_Skin','(.*?)'/","'Wap_User_Skin','".$dir."'",$conf);
		}else{
	         $conf = preg_replace("/'Web_Skin','(.*?)'/","'Web_Skin','".$dir."'",$conf);
		}
		$res = write_file(CTCMSPATH.'libs/Ct_Config.php', $conf);
		if(!$res){
			admin_msg('设置默认模板失败~！','javascript:history.back();','no');
		}else{
			admin_msg('模板设置成功~！',links('tpl','index',$ac));
		}
	}
}