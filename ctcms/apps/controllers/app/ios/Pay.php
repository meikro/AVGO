<?php
/** * 
@Ctcms open source management system * 
@copyright 2008-2016 chshcms.com. All rights reserved. * 
@Author:Cheng Kai Jie * 
@Dtime:2015-12-11 
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Pay extends Ctcms_Controller {
	
	function __construct(){	    
		parent::__construct();
	}

	//充值记录
	public function index(){
		$uid = (int)$this->input->get_post('uid',true);
		$token = $this->input->get_post('token',true);
		$log = $this->islog($uid,$token,1);
		if(!$log){
			echo json_encode(array('code'=>1,'msg'=>'未登录'));
			exit;
		}
		$size = (int)$this->input->get_post('size'); //每页数量
		$page = (int)$this->input->get_post('page'); //当前页数
		$cid = (int)$this->input->get_post('cid'); //类型，1充值金币，2充值VIP
		if($size==0) $size = 12;
		if($page==0) $page = 1;

		if($cid>0){
			$cid = $cid-1;
			$sql = 'select * from '.CT_SqlPrefix.'pay where uid='.$uid.' and cid='.$cid.' order by id desc';
		}else{
			$sql = 'select * from '.CT_SqlPrefix.'pay where uid='.$uid.' order by id desc';
		}
		$total = $this->csdb->get_sql_nums($sql);
		$pagejs = ceil($total / $size);
	    $sql .= ' limit '.$size*($page-1).','.$size;
	    $data = $this->csdb->get_sql($sql,1);
	    foreach ($data as $k=>$row) {
	    	if($row['sid']==1){
	    		$data[$k]['type'] = '微信';	
	    	}elseif($row['sid']==2){
	    		$data[$k]['type'] = 'QQ钱包';	
	    	}elseif($row['sid']==3){
	    		$data[$k]['type'] = '网银';	
	    	}else{
				$data[$k]['type'] = '支付宝';
	    	}
	    	$data[$k]['cid'] = $data[$k]['cid']+1;
	    	$data[$k]['zt'] = $row['pid']==1 ? '成功' : '未完成';
	    	$data[$k]['addtime'] = date('Y-m-d H:i:s',$data[$k]['addtime']);
	    	unset($data[$k]['uid'],$data[$k]['sid'],$data[$k]['pid']);
	    }
		//输出
		$arr['code'] = 0;
		$arr['data'] = $data;
		echo json_encode($arr);
	}

	//获取VIP价格
	public function init(){
		$arr = array(
			'code'=>0,
			'cardlink'=>CT_App_Kalink,
			'rmbtocion'=>CT_Rmb_To_Cion,
			'cion'=>array(
				5,
				10,
				50,
				100
			),
			'vip'=>array(
				CT_Vip1_Rmb,
				CT_Vip2_Rmb,
				CT_Vip3_Rmb,
				CT_Vip4_Rmb
			)
		);
		$pay = array(
			'alipay'=>'支付宝',
			'wxpay'=>'微信',
			'qqpay'=>'QQ钱包',
			'wypay'=>'网银'
		);
		$parr = explode('|', CT_App_Paytype);
		foreach ($parr as $k => $v) {
			if(isset($pay[$v])){
				$arr['type'][$v] = $pay[$v];
			}
		}
		echo json_encode($arr);
	}

	//获取WEB充值接口
	public function add(){
		$uid = (int)$this->input->get_post('uid',true);
		$token = $this->input->get_post('token',true);
		$rowu = $this->islog($uid,$token,1);
		if(!$rowu){
			echo json_encode(array('code'=>1,'msg'=>'未登录'));
			exit;
		}
		$type = $this->input->get_post('type',true); //cion or vip
		$rmb = (int)$this->input->get_post('rmb');
		$day = (int)$this->input->get_post('day');
		//支付方式 'alipay'=>'支付宝','wxpay'=>'微信','qqpay'=>'QQ钱包','wypay'=>'网银'
		$tarr = array(
			'alipay'=>0,
			'wxpay'=>1,
			'qqpay'=>2,
			'wypay'=>3
		);
		$pay = $this->input->get_post('pay',true);
		$sid = isset($tarr[$pay]) ? $tarr[$pay] : 1;
		if($type=='vip'){
			if($day!=1 && $day!=30 && $day!=180 && $day!=365) $day=30;
			if($day==1) $rmb = CT_Vip1_Rmb;
			if($day==30) $rmb = CT_Vip2_Rmb;
			if($day==180) $rmb = CT_Vip3_Rmb;
			if($day==365) $rmb = CT_Vip4_Rmb;

			//金币购买VIP
			if($pay=='cionpay'){
				$cion = $rmb*CT_Rmb_To_Cion;
				//判断金币是否足够
				if($rowu['cion'] < $cion){
					echo json_encode(array('code'=>1,'msg'=>'您当前的金币不够支付'.$day.'天的VIP会员~!'));
					exit;
				}
				//增加VIP
				$editu['cion'] = $rowu['cion']-$cion;
				$editu['vip'] = 1;
				$editu['viptime'] = $rowu['vip']==1 ? $rowu['viptime']+$day*86400 : time()+$day*86400;
				$this->csdb->get_update('user',$uid,$editu);
				echo json_encode(array('code'=>0,'msg'=>'恭喜您，已成功升级'.$day.'天VIP会员'));
				exit;
			}
		}else{
			if($rmb==0){
				echo json_encode(array('code'=>1,'msg'=>'充值金额不能为空~！'));exit;
			}
		}
		//记录订单
		$add['dingdan'] = date('YmdHis').rand(1111,9999);
		$add['rmb'] = $rmb;
		$add['sid'] = $sid;
		$add['cid'] = $type=='vip' ? 1 : 0;
		$add['day'] = $type=='vip' ? $day : 0;
		$add['uid'] = $uid;
		$add['addtime'] = time();
		$res = $this->csdb->get_insert('pay',$add);
		if($res){
			$arr['code'] = 0;
			$this->load->library('pays_app');
			$arr['data']['payurl'] = $this->pays_app->to($add);
			echo json_encode($arr);
		}else{
			echo json_encode(array('code'=>1,'msg'=>'记录订单失败！'));
		}
	}

	//点卡充值
	public function card(){
		$uid = (int)$this->input->get_post('uid',true);
		$token = $this->input->get_post('token',true);
		$rowu = $this->islog($uid,$token,1);
		if(!$rowu){
			echo json_encode(array('code'=>1,'msg'=>'未登录'));exit;
		}
		$kh = safe_replace($this->input->get_post('kh',true));
		$pass = $this->input->get_post('pass',true);
		if(empty($kh) || empty($pass)){
			echo json_encode(array('code'=>1,'msg'=>'卡号和卡密不能为空'));exit;
		}
		$row = $this->csdb->get_row_arr('card','*',array('kh'=>$kh,'pass'=>$pass));
		if(!$row){
			echo json_encode(array('code'=>1,'msg'=>'卡片不存在或者密码错误'));exit;
		}
		if($row['uid']>0){
			echo json_encode(array('code'=>1,'msg'=>'该卡片已经被使用'));exit;	
		}
		//增加金币或者VIP
		if($row['cid']==1){ //VIP卡
			$editu['vip'] = 1;
			$editu['viptime'] = $rowu['vip']==1 ? $rowu['viptime']+$row['day']*86400 : time()+$row['day']*86400;
		}else{  //金币卡
			$editu['cion'] = $rowu['cion']+$row['cion'];
		}
		$res = $this->csdb->get_update('user',$uid,$editu);
		if($res){
			//修改卡片状态
			$edit['uid'] = $uid;
			$edit['totime'] = time();
			$this->csdb->get_update('card',$row['id'],$edit);
			echo json_encode(array('code'=>0,'msg'=>'恭喜您，充值成功'));exit;	
		}else{
			echo json_encode(array('code'=>1,'msg'=>'充值失败，请稍后再试'));exit;	
		}
	}

	//同步返回结果
	public function return_url(){
		$this->load->library('pays_app');
        //订单号
        $out_trade_no  = safe_replace($this->input->get('out_trade_no',TRUE));    //定单号
        $pid  = (int)$this->input->get('cspay_pid',TRUE);
        //支付状态验证
        $pay_sign = $this->pays_app->get_notify(1);

        if ($pay_sign){  //验证支付成功

            //获取数据库定单记录
            $row=$this->csdb->get_row('pay','*',array('dingdan'=>$out_trade_no));
            if($row && $row->pid==0 && $pid == 1){ //定单存在则加金币
				//会员信息
                $rowu=$this->csdb->get_row('user','id,uid,cion,vip,viptime',array('id'=>$row->uid));
                if($row->cid==0){ //充值金币
					$cion = $rowu->cion+$row->rmb*CT_Rmb_To_Cion;
                    $res = $this->csdb->get_update('user',$row->uid,array('cion'=>$cion));
				}else{  //充值VIP
                      if($rowu->vip==1){
                           $viptime = $rowu->viptime+$row->day*86400;
					  }else{
                           $viptime = time()+$row->day*86400;
					  }
                      $res = $this->csdb->get_update('user',$row->uid,array('vip'=>1,'viptime'=>$viptime));
				}

				//改变订单状态
				if($res){
					//一级分销
					if(User_Fc_Off==1 && $rowu->uid > 0){
						$rowu = $this->csdb->get_row('user','id,uid,rmb',array('id'=>$rowu->uid));
						$fcrmb = $row->rmb*(User_Fc_1/100);
						$xrmb = $fcrmb + $rowu->rmb;
						$this->csdb->get_update('user',$rowu->id,array('rmb'=>$xrmb));
						//一级分销记录
						$add1['uida'] = $rowu->id;
						$add1['uidb'] = $row->uid;
						$add1['rmb'] = $row->rmb;
						$add1['fcrmb'] = $fcrmb;
						$add1['addtime'] = time();
						$this->csdb->get_insert('fxlist',$add1);
						//二级分销
						if($rowu->uid > 0){
							$rowu = $this->csdb->get_row('user','id,uid,rmb',array('id'=>$rowu->uid));
							$fcrmb = $row->rmb*(User_Fc_2/100);
							$xrmb = $fcrmb + $rowu->rmb;
							$this->csdb->get_update('user',$rowu->id,array('rmb'=>$xrmb));
							//二级分销记录
							$add2['uida'] = $rowu->id;
							$add2['uidb'] = $row->uid;
							$add2['rmb'] = $row->rmb;
							$add2['fcrmb'] = $fcrmb;
							$add2['addtime'] = time();
							$this->csdb->get_insert('fxlist',$add2);
							//三级分销
							if($rowu->uid > 0){
								$rowu = $this->csdb->get_row('user','id,uid,rmb',array('id'=>$rowu->uid));
								$fcrmb = $row->rmb*(User_Fc_3/100);
								$xrmb = $fcrmb + $rowu->rmb;
								$this->csdb->get_update('user',$rowu->id,array('rmb'=>$xrmb));
								//三级分销记录
								$add3['uida'] = $rowu->id;
								$add3['uidb'] = $row->uid;
								$add3['rmb'] = $row->rmb;
								$add3['fcrmb'] = $fcrmb;
								$add3['addtime'] = time();
								$this->csdb->get_insert('fxlist',$add3);
							}
						}
					}
                    $this->csdb->get_update('pay',$row->id,array('pid'=>1));
				}
		    }
		    $ios = strpos($_SERVER['REQUEST_URI'],'/ios/') !== FALSE;
		    $msg = ($row->pid == 1 || $pid == 1) ? "<h3>恭喜您，支付完成</h3>请牢记您的订单号：".$out_trade_no : '抱歉，付款失败或者订单未支付';
		    echo '<html><head><meta http-equiv="content-type" content="text/html;charset=utf-8"/><meta name="viewport" content="width=device-width, initial-scale=1" /><title>支付成功</title></head><body style="width:100%;margin:0 auto;margin-top:10px;text-align:center;">';
		    echo $msg;
		    echo '
		    <script type="text/javascript">
		    if('.$ios.'){
				testApp(\'openxmtv://\');
		    }else{
		    	contact.getcode(\'ok\');
		    }
		    function testApp(url) {
          		var ifr = document.createElement("iframe");
          		ifr.setAttribute(\'src\', url);
          		ifr.setAttribute(\'style\', \'display:none\');
          		document.body.appendChild(ifr);
        	} 
		    </script>';
		    echo "</body></html>";

		} else {  //验证支付失败
		    echo '<html><head><meta http-equiv="content-type" content="text/html;charset=utf-8"/><meta name="viewport" content="width=device-width, initial-scale=1" /><title>支付成功</title></head><body style="width:100%;margin:0 auto;margin-top:10px;text-align:center;">';
		    echo "<h3>抱歉，验证签名失败</h3>订单号：".$out_trade_no;
		    echo '<script type="text/javascript">contact.getcode(\'no\');</script>';
		    echo "</body></html>";
		}
	}

	//异步返回结果
	public function notify_url(){
		$this->load->library('pays_app');
        //订单号
        $out_trade_no  = safe_replace($this->input->post('out_trade_no',TRUE));    //定单号
        //支付状态验证
        $pay_sign = $this->pays_app->get_notify();
        //验证支付成功
        if ($pay_sign){  
            //获取数据库定单记录
            $row=$this->csdb->get_row('pay','*',array('dingdan'=>$out_trade_no));
            if($row && $row->pid==0){ //定单存在则加金币
				//会员信息
                $rowu=$this->csdb->get_row('user','id,uid,cion,vip,viptime',array('id'=>$row->uid));
                if($row->cid==0){ //充值金币
					  $cion = $rowu->cion+$row->rmb*CT_Rmb_To_Cion;
                      $res = $this->csdb->get_update('user',$row->uid,array('cion'=>$cion));
				}else{  //充值VIP
                      if($rowu->vip==1){
                           $viptime = $rowu->viptime+$row->day*86400;
					  }else{
                           $viptime = time()+$row->day*86400;
					  }
                      $res = $this->csdb->get_update('user',$row->uid,array('vip'=>1,'viptime'=>$viptime));
				}

				//改变订单状态
				if($res){
					//一级分销
					if(User_Fc_Off==1 && $rowu->uid > 0){
						$rowu = $this->csdb->get_row('user','id,uid,rmb',array('id'=>$rowu->uid));
						$fcrmb = $row->rmb*(User_Fc_1/100);
						$xrmb = $fcrmb + $rowu->rmb;
						$this->csdb->get_update('user',$rowu->id,array('rmb'=>$xrmb));
						//一级分销记录
						$add1['uida'] = $rowu->id;
						$add1['uidb'] = $row->uid;
						$add1['rmb'] = $row->rmb;
						$add1['fcrmb'] = $fcrmb;
						$add1['addtime'] = time();
						$this->csdb->get_insert('fxlist',$add1);
						//二级分销
						if($rowu->uid > 0){
							$rowu = $this->csdb->get_row('user','id,uid,rmb',array('id'=>$rowu->uid));
							$fcrmb = $row->rmb*(User_Fc_2/100);
							$xrmb = $fcrmb + $rowu->rmb;
							$this->csdb->get_update('user',$rowu->id,array('rmb'=>$xrmb));
							//二级分销记录
							$add2['uida'] = $rowu->id;
							$add2['uidb'] = $row->uid;
							$add2['rmb'] = $row->rmb;
							$add2['fcrmb'] = $fcrmb;
							$add2['addtime'] = time();
							$this->csdb->get_insert('fxlist',$add2);
							//三级分销
							if($rowu->uid > 0){
								$rowu = $this->csdb->get_row('user','id,uid,rmb',array('id'=>$rowu->uid));
								$fcrmb = $row->rmb*(User_Fc_3/100);
								$xrmb = $fcrmb + $rowu->rmb;
								$this->csdb->get_update('user',$rowu->id,array('rmb'=>$xrmb));
								//三级分销记录
								$add3['uida'] = $rowu->id;
								$add3['uidb'] = $row->uid;
								$add3['rmb'] = $row->rmb;
								$add3['fcrmb'] = $fcrmb;
								$add3['addtime'] = time();
								$this->csdb->get_insert('fxlist',$add3);
							}
						}
					}
                    $this->csdb->get_update('pay',$row->id,array('pid'=>1));
				}
		    }
			echo "YES";
		}else{
			echo "NO";
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