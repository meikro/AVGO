<?php
/** * 
@Ctcms open source management system * 
@copyright 2008-2016 chshcms.com. All rights reserved. * 
@Author:Cheng Kai Jie * 
@Dtime:2015-12-11 
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Vod extends Ctcms_Controller {
	
	function __construct(){	    
		parent::__construct();        
	}    
	
	//视频列表
	public function index()	{
		$params = $this->input->get_post('params');
		if(!empty($params)){
			$arr = json_decode($params,1);
			$size = isset($arr['size']) ? (int)$arr['size'] : 0; //每页数量
			$page = isset($arr['page']) ? (int)$arr['page'] : 0; //当前页数
			$cid = isset($arr['cid']) ? (int)$arr['cid'] : 0;
			$zid = isset($arr['zid']) ? (int)$arr['zid'] : 0;
			$tid = isset($arr['tid']) ? (int)$arr['tid'] : 0;
			$ztid = isset($arr['ztid']) ? (int)$arr['ztid'] : 0;
			$type = isset($arr['type']) ? $arr['type'] : '';
			$yuyan = isset($arr['yuyan']) ? $arr['yuyan'] : '';
			$diqu = isset($arr['diqu']) ? $arr['diqu'] : '';
			$zhuyan = isset($arr['zhuyan']) ? $arr['zhuyan'] : '';
			$year = isset($arr['year']) ? $arr['year'] : '';
			$desc = isset($arr['desc']) ? $arr['desc'] : '';
			$key = isset($arr['key']) ? $arr['key'] : '';
			if($cid == 0)  $cid = isset($arr['id']) ? (int)$arr['id'] : 0;
		}else{
			$size = (int)$this->input->get_post('size'); //每页数量
			$page = (int)$this->input->get_post('page'); //当前页数
			$cid = (int)$this->input->get_post('cid');
			if($cid == 0)  $cid = (int)$this->input->get_post('id');
			$zid = (int)$this->input->get_post('zid');
			$tid = (int)$this->input->get_post('tid');
			$ztid = (int)$this->input->get_post('ztid');
			$type = $this->input->get_post('type',true);
			$yuyan = $this->input->get_post('yuyan',true);
			$diqu = $this->input->get_post('diqu',true);
			$zhuyan = $this->input->get_post('zhuyan',true);
			$year = $this->input->get_post('year',true);
			$desc = $this->input->get_post('desc',true);
			$key = $this->input->get_post('key',true,true);
		}

		$desc_arr = array('id','addtime','hits','yhits','zhits','rhits','pf','dhits');
		if($size==0) $size = 24;
		if($page==0) $page = 1;
		if(!in_array($desc, $desc_arr)) $desc = 'addtime';

		$wh = array();
		$wh[] = 'yid=0';
		//分类
		if($cid>0){
			$cids = getcid($cid); //获取分类下所有ID
			if(strpos($cids,',') !== FALSE){
				$wh[] = "cid in(".$cids.")";
			}else{
				$wh[] = "cid=".(int)$cid;
			}
		}
		//主页幻灯
		if($zid>0){
			$wh[] = 'zid=1';
			if($size > 10) $size = 10;
		}
		//推荐视频
		if($tid > 0){
			$wh[] = 'tid=1';
		}
		//专题视频
		if($ztid > 0){
			$wh[] = 'ztid='.$ztid;
		}
		//类型
		if(!empty($type)) $wh[] = "type like '%".$type."%'";
		//地区
		if(!empty($diqu)) $wh[] = "diqu like '%".$diqu."%'";
		//语言
		if(!empty($yuyan)) $wh[] = "yuyan like '%".$yuyan."%'";
		//关键字
		if(!empty($key)) $wh[] = "name like '%".$key."%'";
		//年份
		if(!empty($year)) $wh[] = "year='".$year."'";
		//主演
		if(!empty($zhuyan)){
			$str = str_replace("'","",$zhuyan);
			$arr2 = get_str_arr($str);
			if(is_array($arr2)){
				$stror = array();
				for($k=0;$k<count($arr2);$k++){
					$stror[] = "zhuyan like '%".$arr2[$k]."%'";
				}
				$wh[] = '('.implode(' or ', $stror).')';
			}else{
				$wh[] = "zhuyan like '%".$str."%'";
			}
		}
		//播放页同导演显示数量
		if($this->input->get_post('zhuyan')) $size = 5;

		$sql = 'select id,cid,name,pic,pic2,info,state,type,hits,pf,vip,cion from '.CT_SqlPrefix.'vod';
		if(!empty($wh)){
			$sql.=" where ".implode(" and ",$wh);
		}
		$sql .= ' order by '.$desc.' desc';
		$total = $this->csdb->get_sql_nums($sql);
		$pagejs = ceil($total / $size);
	    $sql .= ' limit '.$size*($page-1).','.$size;
		//输出
		$array['code'] = 0;
		$data = $this->csdb->get_sql($sql,1);
		foreach ($data as $key => $value) {
			$data[$key]['pic'] = getpic($data[$key]['pic']);
			if(substr($data[$key]['pic'],0,12)=='/attachment/') $data[$key]['pic'] = 'http://'.Web_Url.$data[$key]['pic'];
			$data[$key]['pic2'] = getpic($data[$key]['pic2']);
			if(substr($data[$key]['pic2'],0,12)=='/attachment/') $data[$key]['pic2'] = 'http://'.Web_Url.$data[$key]['pic2'];
			$data[$key]['hits'] = format_wan($data[$key]['hits']);
			$data[$key]['fid'] = getzd('class','fid',$data[$key]['cid']);
		}
		$array['data'] = $data;
		echo json_encode($array);
	}

	//视频内容
	public function show(){
		$uid = (int)$this->input->get_post('uid');
		$token = $this->input->get_post('token');
		$id = (int)$this->input->get_post('id');
		$ip = $this->input->get_post('device_token'); //手机唯一码
		if(empty($ip)) $ip = getip();
		if($id==0){
			echo json_encode(array('code'=>1,'msg'=>'视频ID错误'));
			exit;
		}
		$row = $this->csdb->get_row_arr('vod','*',array('id'=>$id));
		if(!$row){
			echo json_encode(array('code'=>1,'msg'=>'视频不存在~!'));
			exit;
		}
		//分类名
		$rowc = $this->csdb->get_row_arr('class','name',array('id'=>$row['cid']));
		$row['cname'] = $rowc['name'];
		$row['text'] = str_checkhtml($row['text']);
		//判断试看
		$look = 0;
		$looktime = -1;
		$Zhuan_Sk = defined('Zhuan_Sk') ? Zhuan_Sk : 0;
		$Zhuan_Sk = 0; //测试安卓匹配以前的
		$Zhuan_Sk_Type = defined('Zhuan_Sk') ? Zhuan_Sk_Type : 0;
		if($row['vip'] > 0){
			$user = $this->islog($uid,$token,1);
			if($user){
				if($row['vip'] == 3){ //VIP会员专享
					if($user['vip']==1 && $user['viptime'] > time()) $look = 1;
				}else{
					//判断是否购买过
	            	$rowp = $this->csdb->get_row_arr('buy','id',array('uid'=>$uid,'did'=>$id));
	            	if($rowp){
            			$look = 1;
            		}else{
            			//VIP会员免费
						if($row['vip'] == 4 && $user['vip']==1 && $user['viptime'] > time()) $look = 1;
            		}
				}
			}
			//判断试看
			if($look == 0){
				if($Zhuan_Sk == 1){
					if($Zhuan_Sk_Type == 1){
						$num = (int)getzd('shikan','num',$ip,'ip');
						$Zhuan_Sk_Nums = defined('Zhuan_Sk_Nums') ? Zhuan_Sk_Nums : 0;
						if($num < $Zhuan_Sk_Nums){
							$look = 1;
						}
					}else{
						$look = 1;
						$looktime = defined('Zhuan_Sk_Time') ? (int)Zhuan_Sk_Time : (int)CT_App_Sktime*60;
					}
				}else{
					$look = 1;
					$looktime = (int)CT_App_Sktime*60;
				}
			}
		}else{
			$look = 1;
		}
		$row['vip'] = $row['vip'];
		$row['cion'] = $row['cion'];
		$row['look'] = $look;
		$row['looktime'] = $looktime;
		$row['fid'] = getzd('class','fid',$row['cid']);
		//评论数
		$row['comment_count'] = $this->csdb->get_nums('pl',array('did'=>$row['id']));
		//组集数
		$arr = $zuarr = array();
		if(!empty($row['url'])) $arr = explode("#ctcms#",$row['url']);
        for($i=0;$i<count($arr);$i++){
			$arr2 = explode("###",$arr[$i]);
			$zuarr[$i]['id'] = $i;
			$zuarr[$i]['ly'] = $arr2[0];
			$zuarr[$i]['name'] = getzd('player','name',$arr2[0],'bs');
			$arr3 = explode("\n",$arr2[1]);
			$zuarr[$i]['count'] = count($arr3);
			if($zuarr[$i]['name']=='视频云'){
				if(strpos($arr3[0], 'youku')) $zuarr[$i]['name'] = '优酷';
				if(strpos($arr3[0], 'tudou')) $zuarr[$i]['name'] = '土豆';
				if(strpos($arr3[0], 'letv')) $zuarr[$i]['name'] = '乐视';
				if(strpos($arr3[0], 'sohu')) $zuarr[$i]['name'] = '搜狐';
				if(strpos($arr3[0], 'qiyi')) $zuarr[$i]['name'] = '奇艺';
				if(strpos($arr3[0], 'mgtv')) $zuarr[$i]['name'] = '芒果';
				if(strpos($arr3[0], 'qq')) $zuarr[$i]['name'] = '腾讯';
				if(strpos($arr3[0], 'wasu')) $zuarr[$i]['name'] = '华数';
				if(strpos($arr3[0], 'fun')) $zuarr[$i]['name'] = '风行';
				if(strpos($arr3[0], 'pptv')) $zuarr[$i]['name'] = 'PPTV';
			}
			$jiarr=array();
			for($k=0;$k<count($arr3);$k++){
			    if(!empty($arr3[$k])){
					$arr4 = explode("$",$arr3[$k]);
					$arr5 = explode('&type=',trim($arr4[1]));
					$jiarr[$k]['id'] = $k;
					$jiarr[$k]['name'] = $arr4[0];
					$jiarr[$k]['ext'] = strpos($arr5[0],'.m3u8') !== false ? 'm3u8' : 'mp4';
					$jiarr[$k]['purl'] = $arr5[0];
			    }
			}
			$zuarr[$i]['ji']=$jiarr;
        }
        $row['zu'] = array_merge($zuarr);
		//图片
		$row['pic'] = getpic($row['pic']);
		if(substr($row['pic'],0,12)=='/attachment/') $row['pic'] = 'http://'.Web_Url.$row['pic'];
		//删除不显示数组
		unset($row['url'],$row['durl'],$row['skin'],$row['pic2'],$row['zid'],$row['yid'],$row['down'],$row['pid'],$row['ztid'],$row['tid'],$row['yhits'],$row['zhits'],$row['rhits'],$row['pf1'],$row['pf2'],$row['pf3'],$row['pf4'],$row['pf5']);
		//格式化时间
		$row['addtime'] = date('Y-m-d H:i:s',$row['addtime']);
		//格式化人气
		$row['hits'] = format_wan($row['hits']);
		$row['dhits'] = format_wan($row['dhits']);
		$row['tags'] = !empty($row['tags']) ? explode(',', str_replace(array('|','/'), ',', $row['tags'])) : array();
		//分享地址
		if($uid > 0){
			$row['shareurl'] = 'http://'.Web_Url.Web_Path.'index.php/app/share/index/'.$row['id'].'?uid='.sys_auth($uid);
		}else{
			$row['shareurl'] = 'http://'.Web_Url.Web_Path.'index.php/app/share/index/'.$row['id'];
		}
		//输出
		$array['code'] = 0;
		$array['data'] = $row;
		echo json_encode($array);
	}

	//增加播放人气
	public function hits(){
		$id = (int)$this->input->get_post('id');
		if($id>0){
		   	//清空月人气
			$month=file_get_contents(FCPATH."caches/month.txt");
			if($month!=date('m')){
			    $this->db->query("update ".CT_SqlPrefix."vod set yhits=0");
			    write_file(FCPATH."caches/month.txt",date('m'));
			}

			//清空周人气
			$week=file_get_contents(FCPATH."caches/week.txt");
			if($week!=date('W',time())){
			    $this->db->query("update ".CT_SqlPrefix."vod set zhits=0");
			    write_file(FCPATH."caches/week.txt",date('W',time()));
			}

			//清空日人气
			$day=file_get_contents(FCPATH."caches/day.txt");
			if($day!=date('d')){
			    $this->db->query("update ".CT_SqlPrefix."vod set rhits=0");
			    write_file(FCPATH."caches/day.txt",date('d'));
			}
			//增加播放人气
			$sql = "update ".CT_SqlPrefix."vod set hits=hits+1,yhits=yhits+1,zhits=zhits+1,rhits=rhits+1 where id=".$id;
			$this->db->query($sql);
			echo json_encode(array('code'=>0,'msg'=>'增加人气成功'));
		}else{
			echo json_encode(array('code'=>1,'msg'=>'缺少参数ID'));
		}
	}

	//写入试看记录
	public function skadd(){
		$id = (int)$this->input->get_post('id');
		$Zhuan_Sk = defined('Zhuan_Sk') ? Zhuan_Sk : 0;
		$Zhuan_Sk_Type = defined('Zhuan_Sk') ? Zhuan_Sk_Type : 0;
		if($id > 0){
			if($Zhuan_Sk == 1 && $Zhuan_Sk_Type == 1){
				$ip = $this->input->get_post('device_token'); //手机唯一码
				if(empty($ip)) $ip = getip();
				$row = $this->csdb->get_row('shikan','*',array('ip'=>$ip));
				if(!$row){
				    $add['num'] = 1;
				    $add['ip'] = $ip;
				    $add['day'] = date('d');
					$this->csdb->get_insert('shikan',$add);
				}else{
					$num = $row->num+1;
					$this->db->query("update ".CT_SqlPrefix."shikan set num=".$num.",day='".date('d')."' where id=".$row->id);
				}
			}
			echo json_encode(array('code'=>0,'msg'=>'新增成功'));
		}else{
			echo json_encode(array('code'=>1,'msg'=>'缺少参数ID'));
		}
	}

	//判断是否登陆
	private function islog($uid,$token,$sign=0){
		if($uid==0 || empty($token)) return 0;
		$row = $this->csdb->get_row_arr('user','*',array('id'=>$uid));
		if(!$row || md5($row['id'].$row['name'].$row['pass'].CT_Encryption_Key) != $token){
			return 0;
		}else{
			if($sign==0){
				return 1;
			}else{
				unset($row['pass']);
				return $row;
			}
		}
	}
}