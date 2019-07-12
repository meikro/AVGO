<?php
/**
 * @Ctcms open source management system
 * @copyright 2016-2019 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2018-03-19
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class AJax extends Ctcms_Controller {

	function __construct(){
		parent::__construct();
        //当前模版
		$this->load->get_templates();
		$this->load->library('parser');
	}

	//判断会员登陆
    public function ulog() {
    	$this->load->model('user');
		$log = $this->user->login(1);
		$row = array();
		$data['code'] = 1;
		if($log){
			//当前会员数据
			$row = $this->csdb->get_row_arr('user','name,nichen,pic,vip,cion,viptime',array('id'=>$_SESSION['user_id']));
			$data['code'] = 0;
			$row['pic'] = getpic($row['pic']);
			if(empty($row['nichen'])) $row['nichen'] = $row['name'];
			$row['viptime'] = date('Y-m-d H:i:s',$row['viptime']);
		}
		$data['user'] = $row;
		echo json_encode($data);
	}

    //打赏框架
	public function ds($id = 0){
		if($id == 0) $id = $this->input->get('id');
		//获取模板
		$str = load_file('gift.html');
		//加载会员模型
		$this->load->model('user');
		$log = $this->user->login(1);
		$data['cion'] = $log ? getzd('user','cion',$_SESSION['user_id']) : 0 ;
		//全局解析
		$str = $this->parser->parse_string($str,$data,true);
		echo $str;
	}

    //打赏记录
	public function dslist(){
		$id = $this->input->get_post('id');
		//获取模板
		$str = load_file('gift-list.html');
		//全局解析
		$data['ctcms_id'] = $id;
		$str = $this->parser->parse_string($str,$data,true);
		echo $str;
	}

	//打赏入库
	public function dsto(){
		//加载会员模型
		$this->load->model('user');
		if(!$this->user->login(1)){
			echo json_encode(array('code'=>1,'msg'=>'登录超时,请重新登录'));
			exit;	
		}
		$vid = (int)$this->input->post('vid');
		$did = (int)$this->input->post('did');
		$num = (int)$this->input->post('num');
		if($vid == 0 || $did == 0 || $num == 0){
			echo json_encode(array('code'=>1,'msg'=>'参数不完整'));
			exit;
		}
		$row = $this->csdb->get_row('vod','id',array('id'=>$vid));
		if(!$row){
			echo json_encode(array('code'=>1,'msg'=>'视频不存在'));
			exit;	
		}
		$row = $this->csdb->get_row('liwu','cion',array('id'=>$did));
		if(!$row){
			echo json_encode(array('code'=>1,'msg'=>'礼物不存在'));
			exit;	
		}
		//计算金额
		$zcion = $row->cion * $num;
		//会员金额
		$ucion = getzd('user','cion',$_SESSION['user_id']);
		//判断金额是否足够支付
		if($ucion < $zcion){
			echo json_encode(array('code'=>1,'msg'=>'你的金币不足支付，请先充值~!'));
			exit;	
		}
		//增加打赏记录
		$add['did'] = $vid;
		$add['lid'] = $did;
		$add['uid'] = $_SESSION['user_id'];
		$add['num'] = $num;
		$add['cion'] = $zcion;
		$add['addtime'] = time();
		$this->csdb->get_insert('liwu_list',$add);
		//减去金币
		$xcion = $ucion - $zcion;
		$this->db->query("update ".CT_SqlPrefix."user set cion=".$xcion." where id=".$_SESSION['user_id']);
		echo json_encode(array('code'=>0,'msg'=>'恭喜你，打赏成功!~'));
	}

    //视频关键字搜索
	public function search(){
		$key = $this->input->get('wd',true,true);
		$cb = $this->input->get('cb',true);
		$vod = array();
		if(!empty($key)){
			$sql = "select name from ".CT_SqlPrefix."vod where name like '%".$key."%' and yid=0 order by rhits desc limit 15";
			$rs = $this->csdb->get_sql($sql,1);
			foreach($rs as $k=>$v){
				$vod[] = $v['name'];
			}
		}
		$data['name'] = $vod;
		header('Content-Type:application/json;Charset=utf-8');
		echo $cb.'('.json_encode($data).');';
	}

	//评分
	public function pf()
	{
		$did = (int)$this->input->get_post('did');
		$pf = (int)$this->input->get_post('pf');
		if($did == 0 || $pf == 0) {
			echo json_encode(array('code'=>1,'msg'=>'参数不完整~!'));
			exit;
		}
		if(isset($_COOKIE['pf_'.$did])){
			echo json_encode(array('code'=>1,'msg'=>'你已经评过分了~!'));
			exit;
		}
		$row = $this->csdb->get_row_arr('vod','pf,pf1,pf2,pf3,pf4,pf5',array('id'=>$did));
		if(!$row){
			echo json_encode(array('code'=>1,'msg'=>'视频不存在'));
			exit;	
		}
		if($pf == 2) $row['pf1']++;
		if($pf == 4) $row['pf2']++;
		if($pf == 6) $row['pf3']++;
		if($pf == 8) $row['pf4']++;
		if($pf == 10) $row['pf5']++;
		$pf_num = $row['pf1']+$row['pf2']+$row['pf3']+$row['pf4']+$row['pf5'];
		$pf_fen = $row['pf1']*2+$row['pf2']*4+$row['pf3']*6+$row['pf4']*8+$row['pf5']*10;
		$pf = round(($pf_fen/$pf_num),1);
		$edit['pf'] = $pf;
		$edit['pf1'] = $row['pf1'];
		$edit['pf2'] = $row['pf2'];
		$edit['pf3'] = $row['pf3'];
		$edit['pf4'] = $row['pf4'];
		$edit['pf5'] = $row['pf5'];
		if(!strstr($pf,'.')) $pf.='.0';
		$this->csdb->get_update('vod',$did,$edit);
        $pf1_bi = round($pf_num / $row['pf1']);
        $pf2_bi = round($pf_num / $row['pf2']);
        $pf3_bi = round($pf_num / $row['pf3']);
        $pf4_bi = round($pf_num / $row['pf4']);
        $pf5_bi = round($pf_num / $row['pf5']);
        //记录COOKIE
        setcookie('pf_'.$did,'ok',time()+86400*30,Web_Path);
		echo json_encode(array('code'=>0,'msg'=>'评分成功','pf'=>$pf,'pfnum'=>$pf_num,'pf1_bi'=>$pf1_bi,'pf2_bi'=>$pf2_bi,'pf3_bi'=>$pf3_bi,'pf4_bi'=>$pf4_bi,'pf5_bi'=>$pf5_bi));
	}

	//视频点赞
	public function zan()
	{
		$did = (int)$this->input->get_post('did');
		if($did == 0) {
			echo json_encode(array('code'=>1,'msg'=>'参数不完整~!'));
			exit;
		}
		//加载会员模型
		$this->load->model('user');
		if(!$this->user->login(1)){
			echo json_encode(array('code'=>1,'msg'=>'登录超时,请重新登录'));
			exit;	
		}
		$row = $this->csdb->get_row('vod','dhits',array('id'=>$did));
		if(!$row){
			echo json_encode(array('code'=>1,'msg'=>'视频不存在'));
			exit;	
		}
		//判断是否赞过
		$row2 = $this->csdb->get_row('zan','id',array('did'=>$did,'uid'=>$_SESSION['user_id']));
		if($row2){
			echo json_encode(array('code'=>1,'msg'=>'你已经赞过了'));
			exit;	
		}
		//入点赞记录库
		$add['did'] = $did;
		$add['uid'] = $_SESSION['user_id'];
		$add['addtime'] = time();
		$this->csdb->get_insert('zan',$add);
		//修改点赞次数
		$this->csdb->get_update('vod',$did,array('dhits'=>$row->dhits+1));
		echo json_encode(array('code'=>0,'msg'=>'点赞成功'));
	}

	//收藏视频
    public function fav() {
		$did = (int)$this->input->get_post('did');
		if($did == 0) {
			echo json_encode(array('code'=>1,'msg'=>'参数不完整~!'));
			exit;
		}
		//加载会员模型
		$this->load->model('user');
		if(!$this->user->login(1)){
			echo json_encode(array('code'=>1,'msg'=>'登录超时,请重新登录'));
			exit;	
		}
		$row = $this->csdb->get_row('vod','cid',$did);
		if(!$row){
			echo json_encode(array('code'=>1,'msg'=>'视频不存在'));
			exit;	
		}
		//判断是否收藏
		$rows = $this->csdb->get_row('fav','id',array('uid'=>$_SESSION['user_id'],'did'=>$did));
		if($rows){
			echo json_encode(array('code'=>1,'msg'=>'您已经收藏了该视频'));
			exit;
		}
		//入库收藏
		$add['did'] = $did;
		$add['cid'] = $row->cid;
		$add['uid'] = $_SESSION['user_id'];
		$add['addtime'] = time();
		$res = $this->csdb->get_insert('fav',$add);
		if($res){
			echo json_encode(array('code'=>0,'msg'=>'收藏成功'));
		}else{
			echo json_encode(array('code'=>1,'msg'=>'收藏失败'));
		}
	}

	//视频报错
	public function err(){
	    $id = (int)$this->input->get_post('did');
	    $zu = (int)$this->input->get_post('zu');
	    $ji = (int)$this->input->get_post('ji');
	    $err = $this->input->get_post('err',true);
		if($id==0){
			echo json_encode(array('code'=>1,'msg'=>'参数不完整!!'));
			exit;
		}
		$zu = (int)$zu;
		$ji = (int)$ji;
		//判断上次报错时间
		if(isset($_SESSION['error_time']) && $_SESSION['error_time']>time()){
			echo json_encode(array('code'=>1,'msg'=>'你提交报错频繁，视为灌水!!'));
			exit;
		}
		//判断视频是否存在
        $row = $this->csdb->get_row_arr('vod','id,name',$id);
		if(!$row || $row['yid']==1){
			echo json_encode(array('code'=>1,'msg'=>'视频不存在!!'));
			exit;
		}
        //判断报错数据是否存在
        $rows = $this->csdb->get_row_arr('error','id,addtime',array('did'=>$id,'zu'=>$zu,'ji'=>$ji));
		$daytime = strtotime(date('Y-m-d'));
		if(!$rows || $rows['addtime'] < $daytime){
			//记录报错
			$add['did'] = $id;
			$add['name'] = $row['name'];
			if(!empty($err)) $add['name'] .= '-->'.$err;
			$add['zu'] = $zu;
			$add['ji'] = $ji;
			$add['addtime'] = time();
			$this->csdb->get_insert('error',$add);
		}
		$_SESSION['error_time'] = time()+300;
		echo json_encode(array('code'=>0,'msg'=>'报错已收到，谢谢!!'));
	}

	//榜单AJAX
	public function hot(){
		$cid = (int)$this->input->get_post('cid');
		$sid = (int)$this->input->get_post('sid');
		$size = (int)$this->input->get_post('size');
		$page = (int)$this->input->get_post('page');
		$where = array();
		if($cid>0) $where['cid'] = getcid($cid); //获取分类下所有ID
		$sort_arr = array('hits','rhits','zhits','yhits','dhits');
		$sort = $sort_arr[$sid];

		//总数量
	    $total = $this->csdb->get_nums('vod',$where);
		//总页数
	    $pagejs = ceil($total / $size);
	    if($total<$size) $size=$total;
		$limit=array($size,$size*($page-1));
        //记录数组
	    $array = $this->csdb->get_select('vod','*',$where,$sort.' DESC',$limit);
	    $html = '';
	    foreach ($array as $k=>$row) {
	    	$kk = ($page-1) * $size + $k + 1;
	    	$html.='<div class="data"><div class="index left">'.$kk.'</div><div class="vod-img left"><a href="'.links('show','index',$row->id).'" style="background:url('.getpic($row->pic).') center/cover"></a></div><div class="vod-info left"><div class="vod-name">'.$row->name.'</div><div class="info-group"><div class="mind"><div class="group">主演：<a>'.$row->zhuyan.'</a></div><div class="group">类型：<a>'.$row->type.'</a></div><div class="group">地区：<a>'.$row->diqu.'</a></div><div class="group">播放量：'.$row->hits.'</div></div></div></div></div>';
	    }
	    $arr['html'] = $html;
	    $arr['code'] = empty($html) ? 0 : 1;
	    echo json_encode($arr);
	}
}