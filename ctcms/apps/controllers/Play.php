<?php
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-11
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Play extends Ctcms_Controller {

	function __construct(){
	    parent::__construct();
        //当前模版
		$this->load->get_templates();
		$this->load->library('parser');
	}

	public function index($id=0,$zu=0,$ji=0)
	{
		if((int)$id==0){
		    $id=(int)$this->input->get('id');
		    $zu=(int)$this->input->get('zu');
		    $ji=(int)$this->input->get('ji');
		}
		if($id==0) msg_url('参数错误',Web_Path);
		$zu = (int)$zu;
		$ji = (int)$ji;

        $row=$this->csdb->get_row_arr('vod','*',$id);
		if(!$row || $row['yid']==1) msg_url('视频不存在~!',Web_Path);
		//判断收费视频
		$pay = $row['vip']>0 ? 1 : 0;
	    $cache_id ="play_".$id."_".$zu."_".$ji."_".$pay;
	    if(!($this->cache->start($cache_id))){
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

				//判断正确集数
				if($zuarr[$zu]['count'] <= $ji){
					msg_url('集数不存在~!',links('play','index',$row['id']));
				}

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
				if($pay==1){
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
		        //评分
		        $zpf = $row['pf1']+$row['pf2']+$row['pf3']+$row['pf4']+$row['pf5'];
		        $row['pf_num'] = $zpf;
		        $row['pf1_bi'] = round($zpf / $row['pf1']);
		        $row['pf2_bi'] = round($zpf / $row['pf2']);
		        $row['pf3_bi'] = round($zpf / $row['pf3']);
		        $row['pf4_bi'] = round($zpf / $row['pf4']);
		        $row['pf5_bi'] = round($zpf / $row['pf5']);

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
		        $str=$this->parser->labelif($str);
				//增加人气
				$arr = explode('</body>',$str);
				$jsurl = '<script type="text/javascript" src="'.links('hits','index',$row['id']).'"></script></body>';
				echo $arr[0].$jsurl.$arr[1];
		        $this->cache->end();
		}
	}

	//购买视频
	public function buy()
	{
		$id=(int)$this->input->get('id');
		if($id==0) $id=(int)$this->uri->segment(3);
		if($id==0) msg_url('ID参数错误~!',Web_Path);

        $row=$this->csdb->get_row_arr('vod','cid,vip,cion',$id);
		if(!$row) msg_url('视频不存在~!',Web_Path);
        //判断登陆
		define('IS_LOG', true);
		$this->load->model('user');//加载会员模型
		if(!$this->user->login(1)) msg_url('您已经登陆超时~!',links('user','login'));
		//判断是否购买过
        $rowp = $this->csdb->get_row_arr('buy','id',array('uid'=>$_SESSION['user_id'],'did'=>$id));
		if(!$rowp){
            $rowu = $this->csdb->get_row_arr('user','vip,cion',$_SESSION['user_id']);
			$cion = $row['cion'];
			if($row['vip']==2){ //Vip会员5折
				if($rowu['vip']>0){
					$cion = ceil($cion * 0.5);
				}
			}

		    if($rowu['cion']<$cion) msg_url('抱歉，您的金币不足观看该视频，请先充值~!',links('user','pay'));
            //扣除金币
			$this->db->query("update ".CT_SqlPrefix."user set cion=cion-".$cion." where id=".$_SESSION['user_id']."");
			//写入购买记录
			$add['uid'] = $_SESSION['user_id'];
			$add['did'] = $id;
			$add['cid'] = $row['cid'];
			$add['cion'] = $cion;
			$add['addtime'] = time();
			$this->csdb->get_insert('buy',$add);
		}
		//转到视频播放页
        $url = links('play','index',$id);
		header("location:".$url);exit;
	}

	//视频播放器
	public function bfq()
	{
		//强制不缓存
		header("Pragma: no-cache");
		header("Expires: 0");
		header("Cache-Control: no-cache");
		header("Cache-Control: no-store");
		header("Cache-Control: must-revalidate");
		$id = (int)$this->input->get('id');
		$zu = (int)$this->input->get('zu');
		$ji = (int)$this->input->get('ji');
		$ip = getip();
		if($id==0){
			$str = 'id';
		}else{
			$row=$this->csdb->get_row_arr('vod','vip,cion,url',$id);
			if(!$row){
				$str = 'no';
			}else{
				//判断登陆
				define('IS_LOG', true);
				$this->load->model('user');//加载会员模型
				if(!$this->user->login(1)){
					$str = 'login';
				}else{
					//判断是否购买过
			        $rowp = $this->csdb->get_row_arr('buy','id',array('uid'=>$_SESSION['user_id'],'did'=>$id));
					if(!$rowp){
						$rowu = $this->csdb->get_row_arr('user','vip,cion',$_SESSION['user_id']);
						$cion = $row['cion'];
						if($row['vip']==1){ //点播视频
						 	if($rowu['cion']<$cion){
						    	$str = 'cion';
						 	}else{
						 		$str = 'pay';
						 	}
						}elseif($row['vip']==2){ //Vip会员5折
							if($rowu['vip']>0) $cion = ceil($row['cion']*0.5);
						 	if($rowu['cion']<$cion){
						    	$str = 'cion';
						 	}else{
						    	$str = 'pay';
						 	}
						}elseif($row['vip']==3){ //VIP会员免费
							if($rowu['vip']==0){
								$str = 'vip';
							}else{
								$str = 'ok';
							}
						}else{ //VIP会员免费，普通会员全额
						 	if($rowu['vip']==0){
								if($rowu['cion']<$cion){
									$str = 'cion';
							 	}else{
									$str = 'pay';
							 	}
						 	}else{
						 		$str = 'ok';
						 	}
						}
					}else{
						$str = 'ok';
					}
				}
			}
		}
		$look = $sk = 0;
		//显示
		if($str == 'id'){
			$html = '<p>ID参数错误~！</p>';
		}elseif($str == 'no'){
			$html = '<p>视频不存在或者已经下架~！</p>';
		}elseif($str == 'login'){
			$html = '<p>观看该视频需要先登录，<a href="'.links('user','login').'" target="_blank">登陆</a> OR <a href="'.links('user','reg').'" target="_blank">注册</a> </p>';
			$sk = 1;
		}elseif($str == 'cion'){
			$html = '<p>观看该视频需要 <b>'.$cion.'</b> 个金币，您的金币不够，请先 <a href="'.links('user','pay').'" target="_blank">充值</a></p>';
			$sk = 1;
		}elseif($str == 'vip'){
			$html = '<p>该视频为Vip会员专享， <a href="'.links('user','pay').'" target="_blank">我要升级Vip</a> 观看</p>';
			$sk = 1;
		}elseif($str == 'pay'){
			$html = '<p>观看该视频需要 <b>'.$cion.'</b> 金币，<a href="'.links('play','buy',$id).'">确定购买</a></p>';
			$sk = 1;
		}else{
			$look = 2;
		}
		//判断试看
		if($sk == 1){
			//开启试看
			if(Zhuan_Sk == 1){
				if(Zhuan_Sk_Type == 1){
					$num = (int)getzd('shikan','num',$ip,'ip');
					if($num < Zhuan_Sk_Nums){
						$look = 3;
					}else{
						$look = 0;
					}
				}else{
					$look = 1;
				}
			}
		}
		if($look > 0){
            //播放组
            $purl = '';
            $bs = 'ck';
			if(!empty($row['url'])){
				$arr = explode("#ctcms#",$row['url']);
				if(isset($arr[$zu]) && !empty($arr[$zu])){
					$arr2 = explode("###",$arr[$zu]);
					$bs = $arr2[0];
					$arr3 = explode("\n",$arr2[1]);
					$jiarr=array();
			          for($k=0;$k<count($arr3);$k++){
						if(!empty($arr3[$k])){
							$arr4 = explode("$",$arr3[$k]);
							$jiarr[$k] = trim($arr4[1]);
						}
			        }
					if(isset($jiarr[$ji]) && !empty($jiarr[$ji])){
						$purl = $jiarr[$ji];
					}
				}
			}
			$rowp = $this->csdb->get_row_arr('player','js',array('bs'=>$bs));
			//判断地址加密
			if($look > 1 && strpos(str_decode($rowp['js']),'packs/player/ydisk/') !== FALSE && VOD_JM==1){
				$purl = sys_auth($purl,0,VOD_TIME,USER_TOKEN);
			}
	        $player = str_replace("{url}",$purl,str_decode($rowp['js']));
			$html = $this->parser->parse_string($player,$data,true,false);
			//试看
			if($look == 1){
				$purl = Web_Path.'index.php/play/m3u8/'.sys_auth($purl).'.m3u8';
				$html = '<iframe src="'.Web_Path.'packs/js/shikan.html?url='.$purl.'&code='.$str.'&cion='.$cion.'&paylink='.urlencode(links('user','pay')).'&buylink='.urlencode(links('play','buy',$id)).'&loglink='.urlencode(links('user','login')).'&reglink='.urlencode(links('user','reg')).'" width="100%" height="100%" frameborder="0" border="0" marginwidth="0" marginheight="0" allowfullscreen="true" scrolling="no"></iframe>';
			}
			//写入试看记录
			if($look == 3){
				if($num == 0){
				    $add['num'] = 1;
				    $add['ip'] = $ip;
				    $add['day'] = date('d');
					$this->csdb->get_insert('shikan',$add);
				}else{
					$num = $num + 1;
					$this->db->query("update ".CT_SqlPrefix."shikan set num=".$num.",day='".date('d')."' where ip='".$ip."'");
				}
			}
		}
		$html = '<style>.ct_player{width: 100%;height: 100%;text-align: center;position: relative;background-color: #000;color: #fff;}.ct_player p{padding-top: 90px;font-size: 16px;}.ct_player a,.ct_player b{color:#f90;}</style><div class="ct_player">'.$html.'</div>';
		echo htmltojs($html);
	}
	//M3U8
	public function m3u8($url='')
	{
		//强制不缓存
		header("Pragma: no-cache");
		header("Expires: 0");
		header("Cache-Control: no-cache");
		header("Cache-Control: no-store");
		header("Cache-Control: must-revalidate");
		//TS
		if(strpos($url, '.ts') !== false){
			$ts = str_replace('.ts','',$url);
			$ts_path = sys_auth($ts,1);
			$filesize = sprintf("%u", filesize($ts_path));
			header('Content-type: video/mp2t');
			header('Content-length: '.$filesize);
			header('Content-disposition: attachment; filename='.$url);
			$data = file_get_contents($ts_path);
			echo $data;
		}else{
			$url = str_replace('.m3u8','',$url);
			$m3u8path = sys_auth($url,1);
			$m3u8path = str_replace('http://'.Zhuan_M3u8_Url,'',$m3u8path);
			$row = $this->csdb->get_row('zhuanma','m3u8_dir',array('m3u8_path'=>$m3u8path));
			if($row){
				$m3u8file = $row->m3u8_dir.$m3u8path;
			}else{
				$m3u8file = '.'.$m3u8path;
			}
			if(!file_exists($m3u8file)){
				exit('no file');
			}
			$m3u8_neir = file_get_contents($m3u8file);
			preg_match_all('/#EXTINF:([0-9\.]+),/',$m3u8_neir,$arr);
			$ts = 0;
			$miao = 0;
			foreach ($arr[1] as $k => $v) {
				$miao = $miao + $v;
				if($miao > Zhuan_Sk_Time){
					$ts = $v;
					break;
				}
			}
			$m3u8 = current(explode('#EXTINF:'.$ts.',', $m3u8_neir));
			if(strpos($m3u8, '0000.ts') === false){
				$m3u8 .= "#EXTINF:{$ts},\r\n0000.ts\r\n#EXT-X-ENDLIST";
			}else{
				$m3u8 .= "\r\n#EXT-X-ENDLIST";
			}
			//替换TS
			preg_match_all('/([0-9\.]+).ts/',$m3u8,$arr);
			foreach ($arr[0] as $v) {
				$ts = sys_auth(str_replace('vod.m3u8', '', $m3u8file).$v).'.ts';
				$m3u8 = str_replace($v, $ts, $m3u8);
			}
			header('Content-type: application/vnd.apple.mpegURL');
			header('Content-disposition: attachment; filename=vod.m3u8');
			exit($m3u8);
		}
	}
}
