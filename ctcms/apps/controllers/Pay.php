<?php
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-11
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Pay extends Ctcms_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('user');
        $this->load->library('pays');
	}

    //发起支付
    public function topay($id=0) {
        //判断登陆
		$this->user->login();
		if((int)$id==0) $id = (int)$this->input->get('id');
		if($id==0) msg_url('参数错误，ID不能为空~!',links('user','pay'));

		//获取订单信息
		$row = $this->csdb->get_row_arr('pay','*',$id);
		if(!$row) msg_url('该订单不存在~!',links('user','pay'));
        //手机
        if(defined('MOBILE') || $row['sid']==4){
            $mid = 0;
            $type = 'link';
        }else{
            $mid = 1;
            $type = 'ma';
        }
        $this->pays->to($row,$mid,$type);
    }

	//检测是否支付成功
	public function init($id=0)
	{
		$id = (int)$id;
		if($id==0) $id = (int)$this->input->get('id'); //订单ID
		$msg='no';
		if($id > 0){
            $row=$this->csdb->get_row('pay','pid',array('id'=>$id));
            if($row->pid==1){
                 $msg=links('user','pay/lists');
			}
		}
		echo $msg;
	}

	//同步返回结果
	public function return_url()
	{
        //订单号
        $out_trade_no  = $this->input->get('out_trade_no',TRUE,TRUE);    //定单号
        //支付状态验证
        $pay_sign = $this->pays->get_notify();

        if ($pay_sign){  //验证支付成功

            //获取数据库定单记录
            $row=$this->csdb->get_row('pay','*',array('dingdan'=>$out_trade_no));
            if($row && $row->pid==0){ //定单存在则加金币
				//会员信息
                $rowu=$this->csdb->get_row('user','uid,cion,vip,viptime',array('id'=>$row->uid));
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
		    echo '<html><head><meta http-equiv="content-type" content="text/html;charset=utf-8"/><meta name="viewport" content="width=device-width, initial-scale=1" /><meta http-equiv="refresh" content="3;url='.links('user','pay/lists').'"><title>支付成功</title></head><body style="width:100%;margin:0 auto;margin-top:10px;text-align:center;">';
		    echo "<h3>恭喜您，支付完成</h3>请牢记您的订单号：".$out_trade_no;
		    echo "<br>返回>> <a href='".links('user','pay/lists')."'>我的订单</a>";
		    echo "</body></html>";

		} else {  //验证支付失败
            msg_url('签名验证错误，支付失败~!',links('user','pay'));
		}
	}

	//异步返回结果
	public function notify_url()
	{
        //订单号
        $out_trade_no  = $this->input->post('out_trade_no',TRUE,TRUE);    //定单号
        //支付状态验证
        $pay_sign = $this->pays->get_notify();
        //验证支付成功
        if ($pay_sign){  
            //获取数据库定单记录
            $row=$this->csdb->get_row('pay','*',array('dingdan'=>$out_trade_no));
            if($row && $row->pid==0){ //定单存在则加金币
				//会员信息
                $rowu=$this->csdb->get_row('user','uid,cion,vip,viptime',array('id'=>$row->uid));
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
}