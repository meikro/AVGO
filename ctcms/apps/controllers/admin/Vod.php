<?php
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-11
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Vod extends Ctcms_Controller {

	function __construct(){
	    parent::__construct();
		//加载后台模型
		$this->load->model('admin');
        //当前模版
		$this->load->get_templates('admin');
		//判断是否登陆
		$this->admin->login();
	}

    //视频列表
	public function index()
	{
 	    $page = intval($this->input->get('page'));
 	    $ziduan = $this->input->get_post('ziduan',true);
 	    $key = $this->input->get_post('key',true);
 	    $cid = (int)$this->input->get_post('cid',true);
 	    $yid = (int)$this->input->get_post('yid',true);
 	    $play = $this->input->get_post('play',true);
 	    $lz = (int)$this->input->get_post('lz',true);
 	    $kstime = $this->input->get_post('kstime',true);
 	    $jstime = $this->input->get_post('jstime',true);
 	    $order = $this->input->get_post('order',true);
        if($page==0) $page=1;
        $orarr = array('id','addtime','rhits','zhits','yhits','hits');
        if(empty($order) || !in_array($order, $orarr)) $order = 'addtime';

	    $data['key'] = $key;
	    $data['ziduan'] = $ziduan;
	    $data['kstime'] = $kstime;
	    $data['jstime'] = $jstime;
	    $data['page'] = $page;
	    $data['play'] = $play;
	    $data['cid'] = $cid;
	    $data['yid'] = $yid;
	    $data['lz'] = $lz;
	    $data['order'] = $order;
	    $get = $this->input->post() ? $this->input->post() : $this->input->get();
	    $data['uri'] = http_build_query($get);

		$where=array();
		if(!empty($kstime)) $where[]='addtime>'.strtotime($kstime);
		if(!empty($jstime)) $where[]='addtime<'.strtotime($jstime);
		if(!empty($key)) $where[]=$ziduan." like '%".$key."%'";
		if($cid>0){
			$cids = getcid($cid);
		    if(strpos($cids,',') !== FALSE){
			    $where[] = "cid in(".$cids.")";
		    }else{
			    $where[] = "cid=".$cid;
		    }
		}
		if($yid>0){
			if($yid>6){
				$where[]='vip='.($yid-7);
			}elseif($yid>4){
				$where[]='zid='.($yid-5);
			}elseif($yid>2){
				$where[]='tid='.($yid-3);
			}else{
				$where[]='yid='.($yid-1);
			}
		}
		if($lz==1) $where[]="(state NOT like '%全集%' and state NOT like '%完结%')";
		if($lz==2) $where[]="(state like '%全集%' or state like '%完结%')";
		if(!empty($play)){
			$where[]="url like '%".$play."###%'";
		}
		//组装SQL
		$sql = 'select * from '.CT_SqlPrefix.'vod';
		if(!empty($where)) $sql.=' where '.implode(' and ',$where);
        //总数量
	    $total = $this->csdb->get_sql_nums($sql);
		//每页数量
	    $per_page = 15; 
		//总页数
	    $pagejs = ceil($total / $per_page);
	    if($total<$per_page && $total>0) $per_page=$total;
		$limit=$per_page*($page-1).','.$per_page;
        //记录数组
		$sql.=' order by '.$order.' desc limit '.$limit;
	    $data['array'] = $this->csdb->get_sql($sql);
		//当前链接
		$base_url = links('vod','index',0,'cid='.$cid.'&lz='.$lz.'&yid='.$yid.'&play='.$play.'&ziduan='.$ziduan.'&key='.urlencode($key).'&kstime='.$kstime.'&jstime='.$jstime);
		//分页
	    $data['pages'] = admin_page($base_url,$total,$pagejs,$page);  //获取分页类
	    $data['nums'] = $total;
		//分类
		$data['lists'] = $this->csdb->get_select('class','id,name',array('fid'=>0),'xid ASC',100);
		//获取远程图片
		if(Ftp_Is==2){
		    $picnums = $this->csdb->get_sql_nums("SELECT id FROM ".CT_SqlPrefix."vod where  (Lower(Left(pic,7))='http://' or Lower(Left(pic,8))='https://') && Lower(Right(pic,4))!='#ttk'");
		}else{
		    $picnums = $this->csdb->get_sql_nums("SELECT id FROM ".CT_SqlPrefix."vod where Lower(Left(pic,7))='http://' or  Lower(Left(pic,8))='https://'");
		}
        $data['downpic'] = $picnums==0 ? '' : '&nbsp;&nbsp;<font color=red>发现有 <b>'.$picnums.'</b> 部视频调用外部图片，<a href="'.links('vod','downpic').'" style="color:#060;">同步到本地</a></font>';
		//播放器
		$data['player'] = $this->csdb->get_select('player','id,name,bs','','xid ASC',100);
		$this->load->view('head.tpl',$data);
		$this->load->view('vod_index.tpl');
	}

	//视频编辑
	public function edit()
	{
 	    $id = intval($this->input->get('id'));
        if($id==0){
		    $data['name'] = ''; 
		    $data['cid'] = 0;
		    $data['tid'] = 0;
		    $data['zid'] = 0;
		    $data['kid'] = 0;
		    $data['ztid'] = 0; 
		    $data['yid'] = 0; 
		    $data['daoyan'] = ''; 
		    $data['pic'] = ''; 
		    $data['pic2'] = ''; 
		    $data['zhuyan'] = ''; 
		    $data['type'] = ''; 
		    $data['text'] = '';
		    $data['tags'] = '';
		    $data['url'] = '';
		    $data['down'] = '';
		    $data['diqu'] = '';
		    $data['yuyan'] = '';
		    $data['state'] = '';
		    $data['hits'] = 0;
		    $data['cion'] = 0;
		    $data['vip'] = 0;
		    $data['year'] = date('Y');
		    $data['info'] = '';
		    $data['skin'] = 'play.html';
			$data['id'] = 0; 
			$data['pf'] = 10.0; 
            $data['vpic'] = array();
		}else{
		    $data = $this->csdb->get_row_arr("vod","*",array('id'=>$id));
            $data['vpic'] = $this->csdb->get_select('pic','*',array('did'=>$id),'id ASC',100);
		}
		//分类
		$data['lists'] = $this->csdb->get_select('class','id,name',array('fid'=>0),'xid ASC',100);
		//分类
		$data['topic'] = $this->csdb->get_select('topic','id,name',array(),'id DESC',500);
		//播放器
		$data['player'] = $this->csdb->get_select('player','id,name,bs','','xid ASC',100);
        $this->load->view('head.tpl',$data);
        $this->load->view('vod_edit.tpl',$data);
	}

	//修改入库
	public function save()
	{
		$id = (int)$this->input->post('id');
		$addtime = $this->input->post('addtime');
		$data['name'] = $this->input->post('name',true);
		$data['pic'] = $this->input->post('pic',true);
		$data['pic2'] = $this->input->post('pic2',true);
		$data['tid'] = (int)$this->input->post('tid');
		$data['cid'] = (int)$this->input->post('cid');
		$data['zid'] = (int)$this->input->post('zid');
		$data['kid'] = (int)$this->input->post('kid');
		$data['ztid'] = (int)$this->input->post('ztid');
		$data['yid'] = (int)$this->input->post('yid');
		$data['hits'] = (int)$this->input->post('hits');
		$data['daoyan'] = $this->input->post('daoyan',true);
		$data['zhuyan'] = $this->input->post('zhuyan',true); 
		$data['type'] = $this->input->post('type',true);
		$data['pf'] = (float)$this->input->post('pf');
		$data['skin'] = $this->input->post('skin',true);
		$data['year'] = $this->input->post('year',true);
		$data['info'] = $this->input->post('info',true);
		$data['state'] = $this->input->post('state',true);
		$data['tags'] = $this->input->post('tags',true);
		$data['diqu'] = $this->input->post('diqu',true);
		$data['yuyan'] = $this->input->post('yuyan',true);
		$data['text'] = $this->input->post('text');
		$data['cion'] = (int)$this->input->post('cion');
		$data['vip'] = (int)$this->input->post('vip');
		if(empty($data['skin'])) $data['skin']='play.html';
		//豆瓣图片下载到本地
		if(strpos($data['pic'],'doubanio.1com') !== false){
			$pathpic = FCPATH.'attachment/vod/'.date('Ym').'/'.date('d').'/';
			if(!is_dir($pathpic)) mkdirss($pathpic);
			$pathpic .= time().rand(1111,9999).'.jpg';
			$heads = array('Referer:http://www.douban.com/');
			$this->httpcopy($data['pic'],$pathpic,$heads);
			$data['pic'] = str_replace(FCPATH,Web_Path,$pathpic);
		}

		if(empty($data['name']) || empty($data['cid'])){
             admin_msg('数据不完整~！','javascript:history.back();','no');
		}
		//判断点播
		if($data['vip']==1 || $data['vip']==2 || $data['vip']==4){
			if($data['cion']==0) admin_msg('点播视频金币不能为0~！','javascript:history.back();','no');
		}

		//播放器和播放地址
		$play = $this->input->post('play',true);
		$url = $this->input->post('url');
        $purl=array();
		foreach ($play as $k=>$v) {
			 $ji=array();
			 $arr = explode("\n",$url[$k]);
			 for($i=0;$i<count($arr);$i++){
				 if(!empty($arr[$i])){
                     $arr2 = explode("$",$arr[$i]);
				     if(!empty($arr2[0]) && !empty($arr2[1])){
                         $ji[]=$arr[$i];
				     }elseif(empty($arr2[0])){
                         $ji[]='第'.($i+1).'集$'.$arr2[1];
				     }else{
                         $ji[]='第'.($i+1).'集$'.$arr2[0];
				     }
				 }
			 }
			 if(!empty($ji)){
             	  $purl[] = $v.'###'.implode("\n",$ji);
             }
		}
		$data['url'] = implode("#ctcms#",$purl);
		//下载组
		$xia = $this->input->post('xia',true);
		$down = $this->input->post('down');
        $durl = array();
		foreach ($xia as $k=>$v) {
			 $ji=array();
			 $arr = explode("\n",$down[$k]);
			 for($i=0;$i<count($arr);$i++){
				 if(!empty($arr[$i])){
                     $arr2 = explode("$",$arr[$i]);
				     if(!empty($arr2[0]) && !empty($arr2[1])){
                         $ji[]=$arr[$i];
				     }elseif(empty($arr2[0])){
                         $ji[]='第'.($i+1).'集$'.$arr2[1];
				     }else{
                         $ji[]='第'.($i+1).'集$'.$arr2[0];
				     }
				 }
			 }
			 if(!empty($ji)){
             	$durl[] = $v.'###'.implode("\n",$ji);
			 }
		}
		$data['down'] = implode("#ctcms#",$durl);

		if($id>0){
			if($addtime=='ok') $data['addtime']=time();
            $this->csdb->get_update('vod',$id,$data);
		}else{
			$data['addtime']=time();
            $id = (int)$this->csdb->get_insert('vod',$data);
		}
		//修改视频截图ID
		if(isset($_COOKIE['vpic'])){
			$arr = explode('|', $_COOKIE['vpic']);
			foreach ($arr as $pid) {
				if((int)$pid > 0){
					$this->csdb->get_update('pic',$pid,array('did'=>$id));
				}
			}
		}
		//删除cookie
		setcookie('vpic','',time()-3600,Web_Path);
        echo "<script>
		      parent.layer.msg('恭喜您，操作成功~!');
              setInterval('parent.location.reload()',1000); 
              </script>";
	}

	//批量推荐视频
	public function reco()
	{
 	    $id = $this->input->post('id');
		$edit['tid']=1;
		$this->csdb->get_update('vod',$id,$edit);
		admin_msg('恭喜您，操作完成~！','javascript:history.back();');
	}

    //删除视频
	public function del()
	{
 	    $ac = $this->input->get('ac');
 	    $id = $this->input->post('id');
		$res=$this->csdb->get_del('vod',$id);
		if($ac=='all'){
			//删除报错，评论
			foreach($id as $did){
				$this->csdb->get_del('error',$did,'did');
				$this->csdb->get_del('pl',$did,'did');
			}
			if($res){
				$get = $this->input->get();
				unset($get['c'],$get['m'],$get['ac'],$get['id']);
                admin_msg('恭喜您，删除完成~！',links('vod','index',0,http_build_query($get)));
			}else{
                admin_msg('删除失败，请稍后再试~！','javascript:history.back();','no');
			}
		}else{
			//删除报错
			$this->csdb->get_del('error',$id,'did');
			//删除评论
			$this->csdb->get_del('pl',$id,'did');
		    $data['error']=$res ? 'ok' : '删除失败~!';
		    echo json_encode($data);
		}
	}

    //分类列表
	public function lists()
	{
 	    $page = intval($this->input->get('page'));
        if($page==0) $page=1;

	    $data['page'] = $page;
        //总数量
	    $total = $this->csdb->get_nums('class',array('fid'=>0));
		//每页数量
	    $per_page = 500; 
		//总页数
	    $pagejs = ceil($total / $per_page);
	    if($total<$per_page) $per_page=$total;
	    $limit=array($per_page,$per_page*($page-1));
        //记录数组
	    $data['array'] = $this->csdb->get_select('class','*',array('fid'=>0),'xid ASC',$limit);
		//当前链接
		$base_url = links('vod','lists');
		//分页
	    $data['pages'] = admin_page($base_url,$total,$pagejs,$page);  //获取分页类
	    $data['nums'] = $total;
		$this->load->view('head.tpl',$data);
		$this->load->view('vod_list.tpl');
	}

	//分类增加编辑
	public function lists_edit()
	{
 	    $id = intval($this->input->get('id'));
		if($id==0){
            $data['id'] = 0;
            $data['name'] = '';
            $data['skin'] = 'list.html';
            $data['fid'] = 0;
            $data['xid'] = 0;
            $data['title'] = '';
            $data['keywords'] = '';
            $data['description'] = '';
		}else{
            $data = $this->csdb->get_row_arr("class","*",array('id'=>$id)); 
		}
		//分类
	    $data['array'] = $this->csdb->get_select('class','*',array('fid'=>0),'xid ASC',100);
        $this->load->view('head.tpl',$data);
        $this->load->view('vod_list_edit.tpl',$data);
	}

	//分类修改
	public function lists_save()
	{
		$id = (int)$this->input->post('id');
		$data['name'] = $this->input->post('name',true);
		$data['fid'] = (int)$this->input->post('fid');
		$data['xid'] = (int)$this->input->post('xid');
		$data['skin'] = $this->input->post('skin',true);
		$data['title'] = $this->input->post('title',true);
		$data['keywords'] = $this->input->post('keywords',true);
		$data['description'] = $this->input->post('description',true);
		if(empty($data['name'])){
             admin_msg('名称不能为空~！','javascript:history.back();','no');
		}
		if($id==0){
             $this->csdb->get_insert('class',$data);
		}else{
             $this->csdb->get_update('class',$id,$data);
		}
        echo "<script>
		      parent.layer.msg('恭喜您，操作成功~!');
		      setInterval('parent.location.reload()',1000); 
              </script>";
	}

	//分类批量修改
	public function lists_plpx()
	{
		$ids = $this->input->post('id',true);
		$xids = $this->input->post('xid',true);
		if(empty($ids) || empty($xids)){
             admin_msg('请选择要操作的数据~！','javascript:history.back();','no');
		}
        for($i=0;$i<count($ids);$i++){
             $id=(int)$ids[$i];
			 if($id>0){
                $data['xid']=(int)$xids[$i];
				$this->csdb->get_update('class',$id,$data);
			 }
		}
        admin_msg('恭喜您，排序成功~！',links('vod','lists'));
	}

    //删除分类
	public function lists_del()
	{
 	    $ac = $this->input->get('ac');
 	    $id = $this->input->post('id');
		$res=$this->csdb->get_del('class',$id);
		if($ac=='all'){
			if($res){
                admin_msg('恭喜您，删除完成~！',links('vod','lists'));
			}else{
                admin_msg('删除失败，请稍后再试~！','javascript:history.back();','no');
			}
		}else{
		    $data['error']=$res ? 'ok' : '删除失败~!';
		    echo json_encode($data);
		}
	}

	//批量更新视频缓存
	public function html()
	{
 	    $ids = $this->input->post('id');
        foreach ($ids as $id) {
            $cacheid1 = FCPATH.'caches/tpl/'.md5('play_'.$id.'_0_0');
            $cacheid2 = FCPATH.'caches/tpl/'.md5('show_'.$id);
            unlink($cacheid1);
			unlink($cacheid2);
		}
		admin_msg('恭喜您，全部更新完成~！','javascript:history.back();');
	}

	//视频批量编辑
	public function plcmd()
	{
 	    $id = $this->input->post('id');
		if(!empty($id)) $id = implode(',',$id);
		//分类
		$data['lists'] = $this->csdb->get_select('class','id,name',array('fid'=>0),'xid ASC',100);
		//播放器
		$data['player'] = $this->csdb->get_select('player','id,name,bs','','xid ASC',100);
		$data['ids'] = $id;
        $this->load->view('head.tpl',$data);
        $this->load->view('vod_plcmd.tpl',$data);
	}

	//视频批量修改
	public function pl_save()
	{
         $cid = (int)$this->input->post('cid');
         $play = $this->input->post('play',true);
         $ids = $this->input->post('ids',true);
         $day = (int)$this->input->post('day');
         $zd = $this->input->post('zd',true);
         $neir = $this->input->post('neir',true);
         $cion = (int)$this->input->post('cion');
         if($zd=='vip' && ($neir=='1' || $neir=='2' || $neir=='4') && $cion == 0){
         	admin_msg('点播金币不能为空~！','javascript:history.back();','no');
         }

		 if(empty($zd)) admin_msg('请选择要操作的对象~！','javascript:history.back();','no');
		 //修改
		 if($zd == 'play'){
			 $sql = array();
			 if($cid>0){
				 $sql[] = "cid=".$cid;
			 }
			 if(!empty($ids)){
				 $sql[] = "id in(".$ids.")";
			 }
			 if(!empty($play)){
				 $sql[] = "url like '%".$play."###%'";
			 }
			 if($day>0){
				 $time = strtotime(date('Y-m-d 0:0:0'))-86400*$day;
				 $sql[] = "addtime>".$time;
			 }
			 if(!empty($play)){
				 if(empty($sql)){
				 	 $sql = "UPDATE ".CT_SqlPrefix."vod SET url = replace(url, '".$play."###', '".$neir."###')";
				 }else{
					 $sql = "UPDATE ".CT_SqlPrefix."vod SET url = replace(url, '".$play."###', '".$neir."###') WHERE ".implode(' and ',$sql);
				 }
				 $this->db->query($sql);
			 }else{
				 $sql2 = "SELECT id,url FROM ".CT_SqlPrefix."vod";
				 if(!empty($sql)) $sql2 .= " WHERE ".implode(' and ',$sql);
				 $query = $this->db->query($sql2);
				 foreach ($query->result() as $row) {
					$arr = explode('#ctcms#',$row->url);
					for($i=0;$i<count($arr);$i++){
						$arr2 = explode('###',$arr[$i]);
						$arr[$i] = str_replace($arr2[0]."###", $neir."###",$arr[$i]);
					}
					$edit['url'] = implode('#ctcms#',$arr);
					$this->db->where('id',$row->id)->update('vod',$edit);
				 }
			 }
		 }else{
			 if($cid>0){
				 $this->db->where('cid',$cid);
			 }
			 if(!empty($ids)){
				 $arr = explode(',',$ids);
				 $this->db->where_in('id',$arr);
			 }
			 if(!empty($play)){
				 $this->db->like('url',$play.'###');
			 }
			 if($day>0){
				 $time = strtotime(date('Y-m-d 0:0:0'))-86400*$day;
				 $this->db->where('addtime>',$time);
			 }
			 $updata = array($zd=>$neir);
			 if($zd=='vip' && $cion > 0){
			 	$updata['cion'] = $cion;
			 }
			 $this->db->update('vod',$updata);
		 }
         admin_msg('恭喜您，修改完成~!',links('vod','plcmd'),'ok');  //操作完成
	}

	//视频批量替换
	public function pl_tihuan()
	{
         $cid = (int)$this->input->post('cid');
         $play = $this->input->post('play',true);
         $ids = $this->input->post('ids',true);
         $day = (int)$this->input->post('day');
         $zd = $this->input->post('zd',true);
         $neir1 = $this->input->post('neir1',true);
         $neir2 = $this->input->post('neir2',true);
         $wh = $this->input->post('where',true);

		 if(empty($zd)) admin_msg('请选择要操作的对象~！','javascript:history.back();','no');

         $where = array();
		 if($cid>0){
			 $where[]='cid='.$cid;
		 }
		 if(!empty($ids)){
			 $where[]='id in('.$cid.')';
		 }
		 if(!empty($play)){
			 $where[]="url like '%".$play."%'";
		 }
		 if($day>0){
			 $time = strtotime(date('Y-m-d 0:0:0'))-86400*$day;
			 $where[]="addtime > ".$time;
		 }
		 if(is_numeric($neir1)){
			 $where[]=$zd."=".$neir1;
		 }elseif(!empty($neir1)){
			 $where[]=$zd." like '%".$neir1."%'";
		 }
		 $tj = '';
		 if(!empty($wh)){
		     if(!empty($where)){
				$tj = $wh.' and '.implode(' and ',$where);
			 }else{
				$tj = $wh;
			 }
		 }else{
		     if(!empty($where)) $tj = implode(' and ',$where);
		 }
		 $tj = str_replace("&gt;",">",$tj);
		 $tj = str_replace("&lt;","<",$tj);

		 //修改
		 if(!empty($tj)){
             $sql = "UPDATE ".CT_SqlPrefix."vod SET ".$zd." = replace(".$zd.", '".$neir1."', '".$neir2."') WHERE ".$tj;
		 }else{
             $sql = "UPDATE ".CT_SqlPrefix."vod SET ".$zd." = replace(".$zd.", '".$neir1."', '".$neir2."')";
		 }
		 $this->db->query($sql);

         admin_msg('恭喜您，替换完成~!',links('vod','plcmd'),'ok');  //操作完成
	}

	//视频批量删除
	public function pl_del()
	{
         $cid = (int)$this->input->post('cid');
         $play = $this->input->post('play',true);
         $ids = $this->input->post('ids',true);
         $day = (int)$this->input->post('day');

		 $wh = array();
		 if($cid>0){
			 $wh[] = 'cid='.$cid;
		 }
		 if(!empty($ids)){
			 $wh[] = 'id in('.$ids.')';
		 }
		 if(!empty($play)){
			 $wh[] = "url like '%".$play."###%'";
		 }
		 if($day>0){
			 $time = strtotime(date('Y-m-d 0:0:0'))-86400*$day;
			 $wh[] = 'addtime>'.$time;
		 }
		 if(!empty($wh)){
			$sql = "delete from ".CT_SqlPrefix."vod where ".implode(' and ',$wh);
		 }else{
			$sql = "delete from ".CT_SqlPrefix."vod";
		 }
		 //删除
		 $this->db->query($sql);
         admin_msg('恭喜您，删除完成~!',links('vod','plcmd'),'ok');  //操作完成
	}

    //同步远程图片到本地
	public function downpic()
	{
        $page = intval($this->input->get('page'));
        $pagejs = intval($this->input->get('pagejs'));
        if(Ftp_Is==2){
           $sql_string = "SELECT id,pic FROM ".CT_SqlPrefix."vod where (Lower(Left(pic,7))='http://' or Lower(Left(pic,8))='https://') && Lower(Right(pic,4))!='#ttk' order by addtime desc";
		}else{
           $sql_string = "SELECT id,pic FROM ".CT_SqlPrefix."vod where Lower(Left(pic,7))='http://' or  Lower(Left(pic,8))='https://' order by addtime desc";
		}
        $total = $this->csdb->get_sql_nums($sql_string);
        if($total==0) admin_msg('恭喜您，所有远程图片全部同步完成~!',links('vod'),'ok');  //操作完成

        if($page==0) $page = 1;
        $per_page = 20; 
        $totalPages = ceil($total / $per_page); // 总页数
        if($total<$per_page){
           $per_page=$total;
        }
		if($pagejs==0) $pagejs=$totalPages;
        $sql_string.=' limit 20';
        $query = $this->db->query($sql_string); 

		//保存目录
		$pathpic = FCPATH.'attachment/vod/'.date('Ym').'/'.date('d').'/';
		if(!is_dir($pathpic)) mkdirss($pathpic);

        echo '<link href="'.Base_Path.'admin/css/H-ui.min.css" rel="stylesheet" type="text/css" /><br>';
        echo "<div style='font-size:14px;'>&nbsp;&nbsp;&nbsp;<b>正在开始同步第<font style='color:red; font-size:12px; font-style:italic'>".$page."</font>页，共<font style='color:red; font-size:12px; font-style:italic'>".$pagejs."</font>页，剩<font style='color:red; font-size:12px; font-style:italic'>".$totalPages."</font>页</b><br><br>";
       
        foreach ($query->result() as $row) {
			ob_end_flush();//关闭缓存 
			$up='no';
			if(!empty($row->pic)){
				   $file_ext = strtolower(trim(substr(strrchr($row->pic, '.'), 1)));
                   if($file_ext!='jpg' && $file_ext!='png' && $file_ext!='gif'){
				       $file_ext = 'jpg';
				   }
                   //新文件名
                   $file_name=date("YmdHis") . rand(10000, 99999) . '.' . $file_ext;
		           $file_path=$pathpic.$file_name;
				   //保存图片
				   $res = $this->httpcopy($row->pic,$file_path);
                   if($res){
						$up='ok';
						$filepath = str_replace(FCPATH,Web_Path,$file_path);
						//判断远程FTP
						if(Ftp_Is>0){
							$this->load->library('ftpup');
							$res = $this->ftpup->up($file_path,$file_name);
							if($res){
								 $filepath = $res;
							}else{
								 $up='err';
								 unlink($file_path);
							}
						}
				   }
			}
			ob_flush();flush();
			//成功
			if($up=='ok'){
                   //修改数据库
                   $this->db->query("update ".CT_SqlPrefix."vod set pic='".$filepath."' where id=".$row->id."");
                   echo "&nbsp;&nbsp;&nbsp;&nbsp;同步<font color=red>".$row->pic."</font>&nbsp;图片成功!&nbsp;&nbsp;新图片名：<a href=\"".getpic($filepath)."\" target=_blank>".$file_name."</a></br>";
			}else{
                   //修改数据库
                   $this->db->query("update ".CT_SqlPrefix."vod set pic='' where id=".$row->id."");
				   $title = $up=='no' ? '远程图片不存在!' : '同步到FTP失败~!';
                   echo "&nbsp;&nbsp;&nbsp;&nbsp;<font color=red>".$row->pic."</font>远程图片不存在!</br>";
			}
		}
        echo "&nbsp;&nbsp;&nbsp;&nbsp;第".$page."页图片同步完毕,暂停3秒后继续同步．．．．．．<script language='javascript'>setTimeout('ReadGo();',".(3000).");function ReadGo(){location.href='".links('vod','downpic',0,'page="'.($page+1).'&pagejs='.$pagejs)."';}</script></div>";
	}

	//视频采集豆瓣
	public function caiji()
	{
 	    $id = intval($this->input->post('id'));
		$name = $this->input->post('name',true);
		$key = $this->input->get_post('key',true);
		$url = $this->input->post('url',true);

		if($id>0){
            $url = 'http://api.ctcms.cn/caiji/api.php?ac=show&id='.$id;
		}elseif(!empty($name)){
            $url = 'http://api.ctcms.cn/caiji/api.php?ac=list&key='.$name;
		}elseif(!empty($key)){
            $url = 'http://api.ctcms.cn/caiji/api.php?ac=plist&key='.$key.'&t='.time();
		}elseif(!empty($url)){
            $url = 'http://api.ctcms.cn/caiji/api.php?ac=pshow&url='.$url;
		}
		$json = htmlall($url);
		echo $json;
	}

    //下载图片到本地
	public function httpcopy($url, $file="", $head='') 
	{
	  $url = str_replace(" ","%20",$url);
	  if(function_exists('curl_init')) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		if(!empty($head)){
			curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
		}
		$temp = curl_exec($ch);
		if(@file_put_contents($file, $temp) && !curl_error($ch)) {
		  return $file;
		} else {
		  return false;
		}
	  } else {
		$opts = array(
		  "http"=>array(
		  "method"=>"GET",
		  "header"=>$head,
		  "timeout"=>10)
		);
		$context = stream_context_create($opts);
		if(@copy($url, $file, $context)) {
		  return $file;
		} else {
		  return false;
		}
	  }
	}
	//视频截图删除
	function del_vpic()
	{
		$id = (int)$this->input->post('id');
		if($id == 0){
			echo json_encode(array('msg'=>'ID参数错误'));exit;
		}
		$pic = getzd('pic','url',$id);
		//删除文件
		if(!empty($pic)) unlink(FCPATH.$pic);
		$res = $this->csdb->get_del('pic',$id);
		if(isset($_COOKIE['vpic'])){
			$arr = explode('|', $_COOKIE['vpic']);
			$newarr = array();
			foreach ($arr as $pid) {
				if((int)$pid > 0 && $id != $pid){
					$newarr[] = (int)$pid;
				}
			}
			if(!empty($newarr)){
				setcookie('vpic',implode('|',$newarr),time()+7200,Web_Path);
			}else{
				setcookie('vpic','',time()-3600,Web_Path);
			}
		}
		echo json_encode(array('msg'=>'ok'));
	}
}