<?php
/**
 * @Ctcms open source management system
 * @copyright 2008-2019 ctcms.cn. All rights reserved.
 * @Author:Cheng Kai Jie
 * @Dtime:2018-01-10
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Html extends Ctcms_Controller {

	function __construct(){
	    parent::__construct();
		//加载后台模型
		$this->load->model('admin');
		//判断是否登陆
		$this->admin->login();
		if(Html_Off == 0 ){
			exit('<br>&nbsp;&nbsp;&nbsp;系统未开启静态生成~！');
		}
	}

    //主页生成
	public function index($page=1)
	{		
		echo '<div id="loading" style="display:none;position: absolute;left:40%;top:300px;z-index:10;background-color:#ccc;"><span style="width:120px;height:40px;line-height:40px;background-color:#ccc;">&nbsp;&nbsp;<img align="absmiddle" src="'.Web_Path.'packs/admin/images/loading.gif">数据加载中...</span></div><br><br>';
		define('IS_HTML',true);
		$this->load->get_templates();
		$this->load->library('parser');
		$pagejs = 1;
		//这里可以自定义数组内容到模板 ，$data['title'] = '内容';
	    $data = array();
        //获取模板
        $str = load_file('index.html');
        //预先解析分页标签
		preg_match_all('/{ctcms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/ctcms:\1}/',$str,$page_arr);
        if(!empty($page_arr) && !empty($page_arr[3])){
            //每页数量
			$per_page = (int)$page_arr[3][0];
		    //组装SQL数据
			$sqlstr = $this->parser->ctcms_sql($page_arr[1][0],$page_arr[2][0],$page_arr[0][0],$page_arr[4][0]);
			//总数量
			$total = $this->csdb->get_sql_nums($sqlstr);
			//总页数
            $pagejs = ceil($total / $per_page);
			if($pagejs==0) $pagejs=1;
			if($total<$per_page) $per_page=$total;
			$sqlstr .= ' limit '.$per_page*($page-1).','.$per_page;
			$str = $this->parser->ctcms_skins($page_arr[1][0],$page_arr[2][0],$page_arr[0][0],$page_arr[4][0],$str, $sqlstr);
            //解析分页
			$pagenum = getpagenum($str);
			$pagearr = get_page($total,$pagejs,$page,$pagenum,'index'); 
	        $pagearr[] = $per_page;$pagearr[] = $total;$pagearr[] = $pagejs;$pagearr[] = $page;
	        $str = getpagetpl($str,$pagearr);
		}
        $str = $this->parser->parse_string($str,$data,true);
        $htmlpath = FCPATH.Html_Index;
        $link = Web_Path.Html_Index;
        if(write_file($htmlpath, $str)){
        	if($pagejs <= $page){
        		echo '&nbsp;&nbsp;&nbsp;主页全部生成完毕，<a href="'.$link.'" target="_blank">访问浏览</a>';
        	}else{
        		echo '&nbsp;&nbsp;&nbsp;主页第'.$page.'页生成完成，2秒后继续...';
				//跳转到下一页
				echo "<script>
					setTimeout('updatenext();',2000);
					function updatenext(){
						document.getElementById('loading').style.display = 'block';
						location.href='".str_replace('index.php',SELF,links('html','index',0,($page+1)))."';
					}
					</script>";
        	}
        }else{
        	echo '&nbsp;&nbsp;&nbsp;主页生成失败，请检查网站目录权限!~';
        }
	}

    //列表生成
	public function lists()
	{
		$this->load->get_templates('admin');
		$this->load->view('head.tpl');
		$this->load->view('html_lists.tpl');
	}

    //内容生成
	public function show()
	{
		$this->load->get_templates('admin');
		$this->load->view('head.tpl');
		$this->load->view('html_show.tpl');
	}

    //自定义 OPT生成
	public function opt()
	{
		$this->load->get_templates('admin');
		//获取opt自定义模板
		$this->load->helper('file');
		$map = get_dir_file_info(FCPATH.'template/skins/'.Web_Skin.'/');
		$skin = array();
        foreach ($map as $k=>$v) {
        	if(substr($k,0,4) == 'opt-'){
				$skin[] = $k;
        	}
		}
		$data['skins'] = $skin;
		$this->load->view('head.tpl');
		$this->load->view('html_opt.tpl',$data);
	}

	//列表页生成开始
	public function lists_save()
	{
		define('IS_HTML',true);
		$this->load->get_templates();
		$this->load->library('parser');
		echo "<style>p{padding: 4px 30px;margin: 0;}</style><p style='border-top: 1px solid #e5e5e5;padding-bottom:20px;'></p>";

		$cid = (int)$this->input->get_post('cid');
		$xid = (int)$this->input->get_post('xid');
		$kspage = (int)$this->input->get_post('kspage');
		$jspage = (int)$this->input->get_post('jspage');
		if($page==0) $page = 1;
		if($kspage == 0) $kspage = 1;

		//未选择分类，则获取第一个分类开始
		if($cid==0){
			if($xid==0){
				$row = $this->db->query("select * from ".CT_SqlPrefix."class order by id asc limit 1")->row_array();
			}else{
				$row = $this->db->query("select * from ".CT_SqlPrefix."class where id>".$xid." order by id asc limit 1")->row_array();
			}
		}else{
			$row = $this->db->query("select * from ".CT_SqlPrefix."class where id=".$cid)->row_array();
		}
		if(!$row) exit('<p style="color:#0000ff">所有分类全部生成完毕~!</p>');

        //获取模板
        $skin = empty($row['skin']) ? 'list.html' : $row['skin'];
        $str2 = load_file($skin);
	    //网站标题
        $data['ctcms_title'] = $row['name'].' - '.Web_Name;
		//当前数据
		foreach ($row as $key => $val){
		    $data['class_'.$key] = $val;
		}
		//当前ID
		$data['ctcms_cid'] = $row['id'];
		$data['ctcms_fid'] = $row['fid'] == 0 ? $row['id'] : $row['fid'];

        //预先解析分页标签
		preg_match_all('/{ctcms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/ctcms:\1}/',$str2,$page_arr);
        if(!empty($page_arr) && !empty($page_arr[3])){
        	$id = $row['id'];
        	//每页数量
			$per_page = (int)$page_arr[3][0];
			//获取分类下所有ID
			$cids = getcid($id);
			//组装SQL数据
			if(strpos($cids,',') !== FALSE){
			  $sql = 'select {field} from '.CT_SqlPrefix.'vod where cid in('.$cids.')';
			}else{
			  $sql = 'select {field} from '.CT_SqlPrefix.'vod where cid='.$id;
			}
			$sqlstr2 = $this->parser->ctcms_sql($page_arr[1][0],$page_arr[2][0],$page_arr[0][0],$page_arr[4][0],$sql);
			//总数量
			$total = $this->csdb->get_sql_nums($sqlstr2);
			//总页数
			$pagejs = ceil($total / $per_page);
			if($pagejs==0) $pagejs=1;
			if($total<$per_page) $per_page=$total;
			//解析分页
			$pagenum = getpagenum($str2);
			//生成所有分类
			if($jspage > 0 && $pagejs >= $jspage) $pagejs = $jspage;
			for ($i=$kspage; $i <= $pagejs; $i++) { 

				//生成路径
				$link = links('lists','index',$id,$i);
		        $htmlpath = FCPATH.substr($link,1);
				//解析分页
				$pagearr = get_page($total,$pagejs,$i,$pagenum,'lists','index',$id); 
				$pagearr[] = $per_page;$pagearr[] = $total;$pagearr[] = $pagejs;$pagearr[] = $i;
				$sqlstr = $sqlstr2.' limit '.$per_page*($i-1).','.$per_page;
				$str=$this->parser->ctcms_skins($page_arr[1][0],$page_arr[2][0],$page_arr[0][0],$page_arr[4][0],$str2,$sqlstr);
				$str = getpagetpl($str,$pagearr);
				//全局解析
		        $str=$this->parser->parse_string($str,$data,true,false);
				//当前数据
		        $str=$this->parser->ctcms_tpl('class',$str,$str,$row);
				//IF判断解析
		        $str=$this->parser->labelif($str);

		        if(!write_file($htmlpath, $str)) exit('<p style="color:red">生成失败，请检查生成目录权限~!</p>');

		        echo '<p>歌曲分类[<font color=red>'.$row['name'].'</font>]第 <font color=red>'.$i.'</font> 页生成完毕，<a href="'.$link.'" target="_blank">'.$link.'</a></p>';
			}
			if($cid==0){
        		echo '<p><b>歌曲分类[<font color=red>'.$row['name'].'</font>]全部生成完成，2秒后继续下一个分类...</b></p>';
				//跳转到下一页
				$xlink = links('html','lists_save',0,'xid='.$id);
				echo "<script>
					setTimeout('updatenext();',2000);
					function updatenext(){
						location.href='".str_replace('index.php',SELF,$xlink)."';
					}
					document.getElementsByTagName('BODY')[0].scrollTop=document.getElementsByTagName('BODY')[0].scrollHeight;
					</script>";
			}else{
				exit('<p style="color:#0000ff">所有分类全部生成完毕~!</p>');
			}
        }else{

			//全局解析
	        $str=$this->parser->parse_string($str2,$data,true,false);
			//当前数据
	        $str=$this->parser->ctcms_tpl('class',$str,$str,$row);
			//IF判断解析
	        $str=$this->parser->labelif($str);

			//生成路径
			$link = links('lists','index',$cid,1);
	        $htmlpath = FCPATH.substr($link,1);
	        if(!write_file($htmlpath, $str)){
	        	exit('<p style="color:red">生成失败，请检查生成目录权限~!</p>');
	        }else{
	        	echo '<p><b>歌曲分类[<font color=red>'.$row['name'].'</font>]全部生成完毕，<a href="'.$link.'" target="_blank">'.$link.'</a></b></p>';
	        }
        }
	}

	//文章列表页生成开始
	public function news_lists()
	{
		define('IS_HTML',true);
		$this->load->get_templates();
		$this->load->library('parser');
		echo "<style>p{padding: 4px 30px;margin: 0;}</style>
		<p style='border-top: 1px solid #e5e5e5;padding-bottom:20px;'></p>";

		$cid = (int)$this->input->get_post('cid');
		$xid = (int)$this->input->get_post('xid');
		$kspage = (int)$this->input->get_post('kspage');
		$jspage = (int)$this->input->get_post('jspage');
		if($page==0) $page = 1;
		if($kspage == 0) $kspage = 1;

		//未选择分类，则获取第一个分类开始
		if($cid == 0){
			if(isset($_GET['xid'])){
				$row = $this->db->query("select * from ".CT_SqlPrefix."circle where id>".$xid." order by id asc limit 1")->row_array();
				if(!$row) exit('<p style="color:#0000ff">所有分类全部生成完毕~!</p>');
			}
		}else{
			$row = $this->db->query("select * from ".CT_SqlPrefix."circle where id=".$cid)->row_array();
			if(!$row) exit('<p style="color:#0000ff">所有分类全部生成完毕~!</p>');
		}

        //获取模板
        $skin = empty($row['skin']) ? 'comm.html' : $row['skin'];
        $str2 = load_file($skin);
		$data = array();
		$data['ctcms_title'] = '交流圈 - '.Web_Name;
		$data['ctcms_cid'] = $cid==0 ? 0 : $row['id'];
		$data['ctcms_commaddlink'] = links('comm','editor',$cid);
        if(!$row){
        	$row['id'] = 0;
            $row['name'] = '所有分类';
        }else{
			$data['ctcms_title'] = $row['name'].' - 交流圈';
        }

        //预先解析分页标签
		preg_match_all('/{ctcms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/ctcms:\1}/',$str2,$page_arr);
        if(!empty($page_arr) && !empty($page_arr[3])){
        	$id = $row['id'];
        	//每页数量
			$per_page = (int)$page_arr[3][0];
			//组装SQL数据
		    if($xid>0){
	            $sql = "select {field} from ".CT_SqlPrefix."comm where cid=".$id;
	        }else{
	            $sql = '';
	        }
			$sqlstr2 = $this->parser->ctcms_sql($page_arr[1][0],$page_arr[2][0],$page_arr[0][0],$page_arr[4][0],$sql);
			//总数量
			$total = $this->csdb->get_sql_nums($sqlstr2);
			//总页数
			$pagejs = ceil($total / $per_page);
			if($pagejs==0) $pagejs=1;
			if($total<$per_page) $per_page=$total;
			//解析分页
			$pagenum = getpagenum($str2);
			//生成所有分类
			if($jspage > 0 && $pagejs >= $jspage) $pagejs = $jspage;
			for ($i=$kspage; $i <= $pagejs; $i++) { 

				//生成路径
				$link = links('comm','index',$id,$i);
		        $htmlpath = FCPATH.substr($link,1);

				//解析分页
				$pagearr = get_page($total,$pagejs,$i,$pagenum,'comm','index',$id); 
				$pagearr[] = $per_page;$pagearr[] = $total;$pagearr[] = $pagejs;$pagearr[] = $i;
				$sqlstr = $sqlstr2.' limit '.$per_page*($i-1).','.$per_page;
				$str=$this->parser->ctcms_skins($page_arr[1][0],$page_arr[2][0],$page_arr[0][0],$page_arr[4][0],$str2,$sqlstr);
				$str = getpagetpl($str,$pagearr);
				//全局解析
		        $str=$this->parser->parse_string($str,$data,true,false);
				//当前数据
		        $str=$this->parser->ctcms_tpl('circle',$str,$str,$row);
				//IF判断解析
		        $str=$this->parser->labelif($str);

		        if(!write_file($htmlpath, $str)) exit('<p style="color:red">生成失败，请检查生成目录权限~!</p>');

		        echo '<p>文章分类[<font color=red>'.$row['name'].'</font>]第 <font color=red>'.$i.'</font> 页生成完毕，<a href="'.$link.'" target="_blank">'.$link.'</a></p>';
			}
			if($cid==0){
        		echo '<p><b>文章分类[<font color=red>'.$row['name'].'</font>]全部生成完成，2秒后继续下一个分类...</b></p>';
				//跳转到下一页
				$xlink = links('html','news_lists',0,'xid='.$id);
				echo "<script>
					setTimeout('updatenext();',2000);
					function updatenext(){
						location.href='".str_replace('index.php',SELF,$xlink)."';
					}
					document.getElementsByTagName('BODY')[0].scrollTop=document.getElementsByTagName('BODY')[0].scrollHeight;
					</script>";
			}else{
				exit('<p style="color:#0000ff">所有分类全部生成完毕~!</p>');
			}
        }else{

			//全局解析
	        $str=$this->parser->parse_string($str2,$data,true,false);
			//当前数据
	        $str=$this->parser->ctcms_tpl('circle',$str,$str,$row);
			//IF判断解析
	        $str=$this->parser->labelif($str);

			//生成路径
			$link = links('comm','index',$cid,1);
	        $htmlpath = FCPATH.substr($link,1);

	        if(!write_file($htmlpath, $str)){
	        	exit('<p style="color:red">生成失败，请检查生成目录权限~!</p>');
	        }else{
	        	echo '<p><b>文章分类[<font color=red>'.$row['name'].'</font>]全部生成完毕，<a href="'.$link.'" target="_blank">'.$link.'</a></b></p>';
	        }
        }
	}

	//专题列表页生成开始
	public function topic()
	{
		define('IS_HTML',true);
		$this->load->get_templates();
		$this->load->library('parser');
		echo "<style>p{padding: 4px 30px;margin: 0;}</style>
		<p style='border-top: 1px solid #e5e5e5;padding-bottom:20px;'></p>";

		$kspage = (int)$this->input->get_post('kspage');
		$jspage = (int)$this->input->get_post('jspage');
		if($page==0) $page = 1;
		if($kspage == 0) $kspage = 1;

        //获取模板
        $skin = 'topic.html';
        $str2 = load_file($skin);
		$data = array();
		//网站标题
		$data['ctcms_title'] = '视频专辑列表 - '.Web_Name;

        //预先解析分页标签
		preg_match_all('/{ctcms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/ctcms:\1}/',$str2,$page_arr);
        if(!empty($page_arr) && !empty($page_arr[3])){
        	$id = $row['id'];
        	//每页数量
			$per_page = (int)$page_arr[3][0];
			//组装SQL数据
			$sqlstr2 = $this->parser->ctcms_sql($page_arr[1][0],$page_arr[2][0],$page_arr[0][0],$page_arr[4][0]);
			//总数量
			$total = $this->csdb->get_sql_nums($sqlstr2);
			//总页数
			$pagejs = ceil($total / $per_page);
			if($pagejs==0) $pagejs=1;
			if($total<$per_page) $per_page=$total;
			//解析分页
			$pagenum = getpagenum($str2);
			//生成所有分类
			if($jspage > 0 && $pagejs >= $jspage) $pagejs = $jspage;
			for ($i=$kspage; $i <= $pagejs; $i++) { 

				//生成路径
				$link = links('topic','index',$id,$i);
		        $htmlpath = FCPATH.substr($link,1);

				//解析分页
				$pagearr = get_page($total,$pagejs,$i,$pagenum,'topic','index',$id); 
				$pagearr[] = $per_page;$pagearr[] = $total;$pagearr[] = $pagejs;$pagearr[] = $i;
				$sqlstr = $sqlstr2.' limit '.$per_page*($i-1).','.$per_page;
				$str=$this->parser->ctcms_skins($page_arr[1][0],$page_arr[2][0],$page_arr[0][0],$page_arr[4][0],$str2,$sqlstr);
				$str = getpagetpl($str,$pagearr);
				//全局解析
		        $str=$this->parser->parse_string($str,$data,true,false);
				//IF判断解析
		        $str=$this->parser->labelif($str);

		        if(!write_file($htmlpath, $str)) exit('<p style="color:red">生成失败，请检查生成目录权限~!</p>');

		        echo '<p>视频专题列表第 <font color=red>'.$i.'</font> 页生成完毕，<a href="'.$link.'" target="_blank">'.$link.'</a></p>';
			}
			exit('<p style="color:#0000ff">视频专题列表页全部生成完毕~!</p>');
        }else{

			//全局解析
	        $str=$this->parser->parse_string($str2,$data,true,false);
			//IF判断解析
	        $str=$this->parser->labelif($str);

			//生成路径
			$link = links('topic','index',$cid,1);
	        $htmlpath = FCPATH.substr($link,1);

	        if(!write_file($htmlpath, $str)){
	        	exit('<p style="color:red">生成失败，请检查生成目录权限~!</p>');
	        }else{
	        	echo '<p><b>视频专题列表全部生成完毕，<a href="'.$link.'" target="_blank">'.$link.'</a></b></p>';
	        }
        }
	}

	//视频内容页生成开始
	public function show_save()
	{
		define('IS_HTML',true);
		$this->load->get_templates();
		$this->load->library('parser');
		echo "<style>p{padding: 4px 30px;margin: 0;}</style>
		<p style='border-top: 1px solid #e5e5e5;padding-bottom:20px;'></p>";

		$cid = (int)$this->input->get_post('cid');
		$ksid = (int)$this->input->get_post('ksid');
		$jsid = (int)$this->input->get_post('jsid');
		$total = (int)$this->input->get_post('total');
		$page = (int)$this->input->get_post('page');
		if($page==0) $page = 1;

		$where = array();
		if($cid > 0) $where[] = 'cid='.$cid;
		if($ksid > 0) $where[] = 'id>'.($ksid-1);
		if($jsid > 0) $where[] = 'id<'.($jsid+1);

		$sql = 'select * from '.CT_SqlPrefix.'vod';
		if(!empty($where)) $sql.=' where '.implode(' and ',$where);
	    if($total == 0){
			//总数量
		    $total = $this->csdb->get_sql_nums($sql);
	    }
		//每页数量
	    $per_page = 300;
		//总页数
	    $pagejs = ceil($total / $per_page);
	    if($pagejs==0) $pagejs = 1;
	    if($page > $pagejs) exit('<p style="color:#0000ff">所有视频内容页全部生成完毕~!</p>');
	    if($total<$per_page && $total>0) $per_page=$total;
		$limit=$per_page*($page-1).','.$per_page;
        //记录数组
		$sql.=' order by id asc limit '.$limit;
	    $array = $this->csdb->get_sql($sql,1);
	    //循环生成
	    foreach ($array as $key => $row) {
	    	$id = $row['id'];
	    	$data = array();
	    	//当前数据
			foreach ($row as $key => $val){
			    $data['vod_'.$key] = $val;
			}
			//站点标题
	        $data['ctcms_title'] = $row['name'].' - '.Web_Name;
			//当前CID
			$data['ctcms_cid'] = $row['cid'];
			$data['ctcms_playlink'] = links('play','index',$id);
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

			//生成路径
			$link = links('show','index',$id,1);
	        $htmlpath = FCPATH.substr($link,1);

	        if(!write_file($htmlpath, $str)) exit('<p style="color:red">生成失败，请检查生成目录权限~!</p>');

	        echo '<p>视频:[<font color=red>'.$row['name'].'</font>]内容页生成完毕，<a href="'.$link.'" target="_blank">'.$link.'</a></p>';
	    }
	    echo '<p><b>视频内容第'.$page.'页全部生成完成，2秒后继续下一页...</b></p>';
		//跳转到下一页
		$xlink = links('html','show_save',0,'total='.$total.'&page='.($page+1));
		echo "<script>
			setTimeout('updatenext();',2000);
			function updatenext(){
				location.href='".str_replace('index.php',SELF,$xlink)."';
			}
			document.getElementsByTagName('BODY')[0].scrollTop=document.getElementsByTagName('BODY')[0].scrollHeight;
			</script>";
	}

	//视频播放页生成开始
	public function play_save()
	{
		define('IS_HTML',true);
		$this->load->get_templates();
		$this->load->library('parser');
		echo "<style>p{padding: 4px 30px;margin: 0;}</style>
		<p style='border-top: 1px solid #e5e5e5;padding-bottom:20px;'></p>";

		if(Html_Play_Off == 0) exit('<p style="color:red">视频播放页未开启生成~!</p>');

		$cid = (int)$this->input->get_post('cid');
		$ksid = (int)$this->input->get_post('ksid');
		$jsid = (int)$this->input->get_post('jsid');
		$total = (int)$this->input->get_post('total');
		$page = (int)$this->input->get_post('page');
		if($page==0) $page = 1;

		$where = array();
		if($cid > 0) $where[] = 'cid='.$cid;
		if($ksid > 0) $where[] = 'id>'.($ksid-1);
		if($jsid > 0) $where[] = 'id<'.($jsid+1);

		$sql = 'select * from '.CT_SqlPrefix.'vod';
		if(!empty($where)) $sql.=' where '.implode(' and ',$where);
	    if($total == 0){
			//总数量
		    $total = $this->csdb->get_sql_nums($sql);
	    }
		//每页数量
	    $per_page = 300;
		//总页数
	    $pagejs = ceil($total / $per_page);
	    if($pagejs==0) $pagejs = 1;
	    if($page > $pagejs) exit('<p style="color:#0000ff">所有视频播放页全部生成完毕~!</p>');
	    if($total<$per_page && $total>0) $per_page=$total;
		$limit=$per_page*($page-1).','.$per_page;
        //记录数组
		$sql.=' order by id asc limit '.$limit;
	    $array = $this->csdb->get_sql($sql,1);
	    //循环生成
	    foreach ($array as $key => $row) {
            //播放组
			$arr = $zuarr = array();
			if(!empty($row['url'])) $arr = explode("#ctcms#",$row['url']);
	        for($i=0;$i<count($arr);$i++){
		        $arr2 = explode("###",$arr[$i]);
		        $arr3 = explode("\n",$arr2[1]);
		        $jiarr=array();
		        for($k=0;$k<count($arr3);$k++){
					if(!empty($arr3[$k])){
						$this->play_zu($row,$i,$k);
					}
		        }
	        }
	    }
	    echo '<p><b>视频播放页第'.$page.'页全部生成完成，2秒后继续下一页...</b></p>';
		//跳转到下一页
		$xlink = links('html','play_save',0,'total='.$total.'&page='.($page+1));
		echo "<script>
			setTimeout('updatenext();',2000);
			function updatenext(){
				location.href='".str_replace('index.php',SELF,$xlink)."';
			}
			document.getElementsByTagName('BODY')[0].scrollTop=document.getElementsByTagName('BODY')[0].scrollHeight;
			</script>";
	}

	//视频播放组开始生成
	public function play_zu($row,$zu=0,$ji=0)
	{
		$id = $row['id'];
    	$data = array();
    	//当前数据
		foreach ($row as $key => $val){
		    $data['vod_'.$key] = $val;
		}
        //站点标题
	    $jname = $row['cid']==2 ? ' - 第'.($ji+1).'集' : '';
        $data['ctcms_title'] = '在线播放'.$row['name'].$jname.'  - '.Web_Name;
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

		//视频地址
		$purl = $zuarr[$zu]['ctcms_ji'][$ji]['url'];
		//当前播放地址
        $row['playurl'] = $zuarr[$zu]['ctcms_ji'][$ji]['url'];
		//当前组、集
        $row['zu'] = $zu+1;
        $row['ji'] = $ji+1;
		$row['laiy'] = $zuarr[$zu]['ly'];
		$row['zname'] = $zuarr[$zu]['name'];
        $row['jname'] = $zuarr[$zu]['ctcms_ji'][$ji]['name'];
		//报错链接
		$row['errlink'] = links('error','index',$id.'/'.$zu.'/'.$ji);
		//视频上下集
		$sji = ($ji==0) ? $ji : $ji-1;
		$data['ctcms_slink'] = links('play','index',$id.'/'.$zu.'/'.$sji);
		$xji = ($ji==(count($jiarr)-1)) ? 0 : $ji+1;
		$data['ctcms_xlink'] = links('play','index',$id.'/'.$zu.'/'.$xji);

        //视频播放器
        if($row['vip']>0){
			$bfq = links('play','bfq',0,'id='.$id.'&zu='.$zu.'&ji='.$ji);
			$data['ctcms_player'] = '<script type="text/javascript" src="'.$bfq.'"></script>';
        }else{
	        $rowp = $this->csdb->get_row_arr('player','*',array('bs'=>$zuarr[$zu]['ly']));
			//判断地址加密
			if(strpos(str_decode($rowp['js']),'packs/player/ydisk/') !== FALSE && VOD_JM==1){
				$purl = sys_auth($purl,0,VOD_TIME,USER_TOKEN);
			}
	        $player = str_replace("{url}",$purl,str_decode($rowp['js']));
			$data['ctcms_player'] = $this->parser->parse_string($player,$data,true,false);
        }

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
        $skin = empty($row['skin']) ? 'play.html' : $row['skin'];
        $str = load_file($skin);
		//全局解析
        $str=$this->parser->parse_string($str,$data,true,false);
		//评论
		if(str_decode(Web_Pl) == '{pl}'){ //站内
			$pl = '<div id="ctcms_pl">加载中...</div><script src="'.Web_Path.'packs/js/pl.js"></script><script>var pl_did='.$id.',ctcms_pllink = "'.links('pl','index').'",ctcms_pladdlink = "'.links('pl','add').'";setTimeout(function(){get_pl(1);},1000);</script>';
        	$str=str_replace('{ctcms_pl}',$pl,$str);
		}else{
        	$str=str_replace('{ctcms_pl}',str_replace('{id}',$id,str_decode(Web_Pl)),$str);
		}
		//当前数据
        $str=$this->parser->ctcms_tpl('vod',$str,$str,$row);
		//IF判断解析
        $str = $this->parser->labelif($str);
		//增加人气
		$arr = explode('</body>',$str);
		$jsurl = '<script type="text/javascript" src="'.links('hits','index',$row['id']).'"></script></body>';
		$str = $arr[0].$jsurl.$arr[1];

		//生成路径
		$link = links('play','index',$id.'/'.$zu.'/'.$ji);
        $htmlpath = FCPATH.substr($link,1);

        if(!write_file($htmlpath, $str)) exit('<p style="color:red">生成失败，请检查生成目录权限~!</p>');

        echo '<p>视频:[<font color=red>'.$row['name'].'</font>]第【<font color=red>'.($zu+1).'</font>】组第【<font color=red>'.($ji+1).'</font>】集播放页生成完毕，<a href="'.$link.'" target="_blank">'.$link.'</a></p>';
	}

	//新闻内容页生成开始
	public function news_show()
	{
		$this->load->model('Csdb');
		define('IS_HTML',true);
		$this->load->get_templates();
		$this->load->library('parser');
		echo "<style>p{padding: 4px 30px;margin: 0;}</style>
		<p style='border-top: 1px solid #e5e5e5;padding-bottom:20px;'></p>";

		$cid = (int)$this->input->get_post('cid');
		$ksid = (int)$this->input->get_post('ksid');
		$jsid = (int)$this->input->get_post('jsid');
		$total = (int)$this->input->get_post('total');
		$page = (int)$this->input->get_post('page');
		if($page==0) $page = 1;

		$where = array();
		if($cid > 0) $where[] = 'cid='.$cid;
		if($ksid > 0) $where[] = 'id>'.($ksid-1);
		if($jsid > 0) $where[] = 'id<'.($jsid+1);

		$sql = 'select * from '.CT_SqlPrefix.'comm';
		if(!empty($where)) $sql.=' where '.implode(' and ',$where);
	    if($total == 0){
			//总数量
		    $total = $this->csdb->get_sql_nums($sql);
	    }
		//每页数量
	    $per_page = 300;
		//总页数
	    $pagejs = ceil($total / $per_page);
	    if($pagejs==0) $pagejs = 1;
	    if($page > $pagejs) exit('<p style="color:#0000ff">所有文章内容页全部生成完毕~!</p>');
	    if($total<$per_page && $total>0) $per_page=$total;
		$limit=$per_page*($page-1).','.$per_page;
        //记录数组
		$sql.=' order by id asc limit '.$limit;
	    $array = $this->csdb->get_sql($sql,1);
	    //循环生成
	    foreach ($array as $key => $row) {
	    	$id = $row['id'];
	    	$data = array();
	    	//当前数据
			foreach ($row as $key => $val){
			    $data['vod_'.$key] = $val;
			}
			$row['colllink'] = links('comm','collect');
		    $row['dzlink'] = links('comm','clike');
		    $row['replylink'] = links('comm','reply');
		    $row['replydellink'] = links('comm','replydel');

			$data['ctcms_title'] = $row['title'].' - 交流圈 - '.Web_Name;

		    if(empty($_SESSION['user_id']) || (int)$_SESSION['user_id']==0){
		    	$row['coll'] = 0;
		    	$row['dz'] = 0;
		    }else{
			    $coll = $this->Csdb->get_row_arr('coll','*',array('uid'=>$_SESSION['user_id'],'tid'=>$row['id']));
			    if($coll){$row['coll'] = 1;}else{$row['coll'] = 0;}
			    $dz = $this->Csdb->get_row_arr('dz','*',array('uid'=>$_SESSION['user_id'],'tid'=>$row['id']));
			    if($dz){$row['dz'] = 1;}else{$row['dz'] = 0;}
		    }

	        //获取模板
	        $str2 = load_file('article.html');
            //预先解析分页标签
			preg_match_all('/{ctcms:([\S]+)\s+(.*?page=\"([\S]+)\".*?)}([\s\S]+?){\/ctcms:\1}/',$str2,$page_arr);
	        if(!empty($page_arr) && !empty($page_arr[3])){
				//每页数量
				$per_page = (int)$page_arr[3][0];
				//组装SQL数据
				$sql = "select {field} from ".CT_SqlPrefix."msg where tid=".$id;
				$sqlstr2 = $this->parser->ctcms_sql($page_arr[1][0],$page_arr[2][0],$page_arr[0][0],$page_arr[4][0],$sql);
				//总数量
				$total = $this->csdb->get_sql_nums($sqlstr2);
				//总页数
				$pagejs = ceil($total / $per_page);
				if($pagejs==0) $pagejs=1;
				if($total<$per_page) $per_page=$total;
				//解析分页
				$pagenum = getpagenum($str2);
				echo '<p>文章内容[<font color=red>'.$row['title'].'</font>]开始生成：</p>';
				//生成所有分类
				for($i=1; $i <= $pagejs; $i++) { 

					//生成路径
					$link = links('comm','article',$id,$i);
			        $htmlpath = FCPATH.substr($link,1);

					//解析分页
					$pagearr = get_page($total,$pagejs,$i,$pagenum,'comm','article',$id); 
					$pagearr[] = $per_page;$pagearr[] = $total;$pagearr[] = $pagejs;$pagearr[] = $i;
					$sqlstr = $sqlstr2.' limit '.$per_page*($i-1).','.$per_page;
					$str=$this->parser->ctcms_skins($page_arr[1][0],$page_arr[2][0],$page_arr[0][0],$page_arr[4][0],$str2,$sqlstr);
					$str = getpagetpl($str,$pagearr);
					$str=$this->parser->parse_string($str,$data,true,FALSE);
					$str=$this->parser->ctcms_tpl('article',$str,$str,$row);
					$str=$this->parser->labelif($str);
					$arr = explode('</body>',$str);
					$jsurl = '<script type="text/javascript" src="'.links('hits','article',$id).'"></script></body>';
					$str=$arr[0].$jsurl.$arr[1];

					//生成路径
					$link = links('comm','article',$id,$i);
			        $htmlpath = FCPATH.substr($link,1);

			        if(!write_file($htmlpath, $str)) exit('<p style="color:red">生成失败，请检查生成目录权限~!</p>');

			        echo '<p>&nbsp;&nbsp;第 <font color=red>'.$i.'</font> 页生成完毕，<a href="'.$link.'" target="_blank">'.$link.'</a></p>';
				}

			}else{
				$str=$this->parser->parse_string($str2,$data,true,FALSE);
				$str=$this->parser->ctcms_tpl('article',$str,$str,$row);
				$str=$this->parser->labelif($str);
				$arr = explode('</body>',$str);
				$jsurl = '<script type="text/javascript" src="'.links('hits','article',$id).'"></script></body>';
				$str=$arr[0].$jsurl.$arr[1];

				//生成路径
				$link = links('comm','article',$id,1);
		        $htmlpath = FCPATH.substr($link,1);

		        if(!write_file($htmlpath, $str)) exit('<p style="color:red">生成失败，请检查生成目录权限~!</p>');

		        echo '<p>文章:[<font color=red>'.$row['title'].'</font>]内容页生成完毕，<a href="'.$link.'" target="_blank">'.$link.'</a></p>';
			}
	    }
	    echo '<p><b>视频内容第'.$page.'页全部生成完成，2秒后继续下一页...</b></p>';
		//跳转到下一页
		$xlink = links('html','news_show',0,'total='.$total.'&page='.($page+1));
		echo "<script>
			setTimeout('updatenext();',2000);
			function updatenext(){
				location.href='".str_replace('index.php',SELF,$xlink)."';
			}
			document.getElementsByTagName('BODY')[0].scrollTop=document.getElementsByTagName('BODY')[0].scrollHeight;
			</script>";
	}

	//专题内容页生成开始
	public function topic_show()
	{
		define('IS_HTML',true);
		$this->load->get_templates();
		$this->load->library('parser');
		echo "<style>p{padding: 4px 30px;margin: 0;}</style>
		<p style='border-top: 1px solid #e5e5e5;padding-bottom:20px;'></p>";

		$tid = (int)$this->input->get_post('tid');
		$total = (int)$this->input->get_post('total');
		$page = (int)$this->input->get_post('page');
		$ksid = (int)$this->input->get_post('ksid');
		$jsid = (int)$this->input->get_post('jsid');
		if($page==0) $page = 1;

		$where = array();
		if($tid > 0) $where[] = 'id='.$tid;
		if($ksid > 0) $where[] = 'id>'.($ksid-1);
		if($jsid > 0) $where[] = 'id<'.($jsid+1);

		$sql = 'select * from '.CT_SqlPrefix.'topic';
		if(!empty($where)) $sql.=' where '.implode(' and ',$where);
	    if($total == 0){
			//总数量
		    $total = $this->csdb->get_sql_nums($sql);
	    }
		//每页数量
	    $per_page = 300;
		//总页数
	    $pagejs = ceil($total / $per_page);
	    if($pagejs==0) $pagejs = 1;
	    if($page > $pagejs) exit('<p style="color:#0000ff">所有视频专题内容页全部生成完毕~!</p>');
	    if($total<$per_page && $total>0) $per_page=$total;
		$limit=$per_page*($page-1).','.$per_page;
        //记录数组
		$sql.=' order by id asc limit '.$limit;
	    $array = $this->csdb->get_sql($sql,1);
	    //循环生成
	    foreach ($array as $key => $row) {
	    	$id = $row['id'];
			//当前ID
			$data['ctcms_tid'] = $data['ctcms_cid'] = $row['id'];
			//标题
			$data['ctcms_title'] = $row['name'].' - '.Web_Name;
			//模版
			$skins = empty($row['show']) ? 'topic-show.html' : $row['show'];

			//获取模板
			$str=load_file($skins);
			//全局解析
			$str=$this->parser->parse_string($str,$data,true,false);
			//当前数据
			$str=$this->parser->ctcms_tpl('topic',$str,$str,$row);
			//IF判断解析
			$str=$this->parser->labelif($str);
			//增加人气
			$arr = explode('</body>',$str);
			$jsurl = '<script type="text/javascript" src="'.links('hits','topic',$row['id']).'"></script></body>';
			$str=$arr[0].$jsurl.$arr[1];

			//生成路径
			$link = links('topic','show',$id);
	        $htmlpath = FCPATH.substr($link,1);

	        if(!write_file($htmlpath, $str)) exit('<p style="color:red">生成失败，请检查生成目录权限~!</p>');

	        echo '<p>专题:[<font color=red>'.$row['name'].'</font>]内容页生成完毕，<a href="'.$link.'" target="_blank">'.$link.'</a></p>';
	    }
	    echo '<p><b>视频专题内容第'.$page.'页全部生成完成，2秒后继续下一页...</b></p>';
		//跳转到下一页
		$xlink = links('html','topic_show',0,'total='.$total.'&page='.($page+1));
		echo "<script>
			setTimeout('updatenext();',2000);
			function updatenext(){
				location.href='".str_replace('index.php',SELF,$xlink)."';
			}
			document.getElementsByTagName('BODY')[0].scrollTop=document.getElementsByTagName('BODY')[0].scrollHeight;
			</script>";
	}

	//自定义页生成开始
	public function opt_save()
	{
		define('IS_HTML',true);
		$this->load->get_templates();
		$this->load->library('parser');
		echo "<style>p{padding: 4px 30px;margin: 0;}</style>
		<p style='border-top: 1px solid #e5e5e5;padding-bottom:20px;'></p>";

		$tpl = $this->input->get_post('tpl');
		if(empty($tpl)) exit('<p style="color:#0000ff">请选择要生成的模板~!</p>');
		foreach ($tpl as $bs) {
			$tpl2 = $bs;
			$data = array();
			$bs = str_replace('opt-', '', strtolower($bs));
			$bs = str_replace('.html', '', $bs);
			$bs = str_replace('.xml', '', $bs);
			if($bs=='hotest'){
			    //抓取页面内容，缓存到明天凌晨
			    $cachefile=FCPATH."caches/tpl/".md5("Hotest_157503886");
			    if(file_exists($cachefile) && date('Y-m-d',filemtime($cachefile))==date('Y-m-d')){
			         $hotest = file_get_contents($cachefile);
			    }else{
			         $hotest = $this->get_neir();
				     @file_put_contents($cachefile,$hotest);
			    }
				$data['hotest'] = $hotest;
			}
			//获取模板
			$str = load_file($tpl2);
			//全局解析
			$str = $this->parser->parse_string($str,$data,true);

			//生成路径
			$link = links('opt',$bs);
	        $htmlpath = FCPATH.substr($link,1);

	        if(!write_file($htmlpath, $str)) exit('<p style="color:red">生成失败，请检查生成目录权限~!</p>');

	        echo '<p>模板:[<font color=red>'.$tpl2.'</font>]内容页生成完毕，<a href="'.$link.'" target="_blank">'.$link.'</a></p>';
		}
		echo '<p style="color:#0000ff">所有自定义模板页全部生成完毕~!</p>';
	}

	//专题内容页生成开始
	public function day()
	{
		$ac = $this->input->get('ac');
		$time = strtotime(date('Y-m-d 0:0:0'))-1;
		if($ac=='vod'){
			$row = $this->db->query("select id from ".CT_SqlPrefix."vod where addtime>".$time." order by id asc limit 1")->row_array();
			if(!$row) exit('<p style="color:red">当日没有新数据~!</p>');
			echo "<script>location.href='".links('html','show_save',0,'ksid='.$row['id'])."';</script>";
		}elseif($ac=='vodplay'){
			$row = $this->db->query("select id from ".CT_SqlPrefix."vod where addtime>".$time." order by id asc limit 1")->row_array();
			if(!$row) exit('<p style="color:red">当日没有新数据~!</p>');
			echo "<script>location.href='".links('html','play_save',0,'ksid='.$row['id'])."';</script>";
		}elseif($ac=='news'){
			$row = $this->db->query("select id from ".CT_SqlPrefix."comm where addtime>".$time." order by id asc limit 1")->row_array();
			if(!$row) exit('<p style="color:red">当日没有新数据~!</p>');
			echo "<script>location.href='".links('html','news_show',0,'ksid='.$row['id'])."';</script>";
		}else{
			$row = $this->db->query("select id from ".CT_SqlPrefix."topic where addtime>".$time." order by id asc limit 1")->row_array();
			if(!$row) exit('<p style="color:red">当日没有新数据~!</p>');
			echo "<script>location.href='".links('html','topic_show',0,'ksid='.$row['id'])."';</script>";
		}
	}

    //抓取电影排行
	function get_neir(){
        $neir=file_get_contents('http://www.mtime.com/hotest/index.html');
        preg_match_all('/<div class="mtipmid">([\s\S]+?)<div id="PageNavigator" class="pagenav mt30 pt12">/',$neir,$arr);
        preg_match_all('/<div class="showmtip">([\s\S]+?)<\/div>/',$neir,$arr2);
        $html=$arr[1][0];
        for($i=0;$i<count($arr2[0]);$i++){
            $html=str_replace($arr2[0][$i],"",$html);
        }
		$qian=array("  ","		","	","?",' target="_blank"');$hou=array("","","","","");
        $html=str_replace($qian,$hou,$html);  
        $html=preg_replace('/href=".*?"/i','href="javascript:void(0);"',$html);
        preg_match_all('/<dt><a href="([\s\S]+?)">([\s\S]+?)<\/a><\/dt>/',$html,$arr3);
        for($i=0;$i<count($arr3[2]);$i++){
            $all=explode("&nbsp;",$arr3[2][$i]);
			$nameall=$arr3[0][$i];
			if(!empty($all[0])){
				$row=$this->db->query("SELECT id FROM ".CT_SqlPrefix."vod where name='".$all[0]."'")->row();
				if($row){
                    $nameall=str_replace($arr3[1][$i],links('show','index',$row->id).'" style="color:#0074a9"  target="_blank',$nameall);
				}else{
                    $nameall=strip_tags($nameall,'<dt>');
				}
            }
            $html=str_replace($arr3[0][$i],$nameall,$html);
        }
        return $html;
	}
}