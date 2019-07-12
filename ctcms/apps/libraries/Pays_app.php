<?php
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-11
 */


if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 在线支付类
 */
class Pays_app {
    public function __construct (){
		if(CT_Pay==0) msg_url('在线支付功能已关闭~!','javascript:history.back();');
		$this->api_url = 'http://pay.manyiba.net/newpay';
		if(Web_Mode==2){
			$this->return_url = 'http://'.Web_Url.Web_Path.'index.php?d=app&c=pay&m=return_url';
			$this->notify_url = 'http://'.Web_Url.Web_Path.'index.php?d=app&c=pay&m=notify_url';
		}else{
			$this->return_url = 'http://'.Web_Url.Web_Path.'index.php/app/pay/return_url';
			$this->notify_url = 'http://'.Web_Url.Web_Path.'index.php/app/pay/notify_url';
		}
	}

    //提交订单
	public function to($row = array(),$mid=0){
        //订单介绍
		$body = $row['cid']==0 ? '会员在线购买金币' : '会员在线购买Vip';
      	/* 数字签名 */
        $sign_arr = array(
            'pay_sid'      => $row['sid']+1,
            'out_trade_no' => $row['dingdan'],
            'total_fee'    => (float)$row['rmb'],
            'partner'      => CT_Pay_ID,
            'return_url'   => $this->return_url,
            'notify_url'   => $this->notify_url
        );
      	$sign = $this->md5_sign($sign_arr,CT_Pay_Key);

      	/* 交易参数 */
        $parameter = $sign_arr;
    	$parameter['charset'] = 'utf-8'; 
        $parameter['body'] = urlencode($body); 
        $parameter['sign'] = $sign; 
        $parameter['mid'] = $mid;
        $parameter['app'] = 1;
        return $this->api_url.'?'.$this->arr_url($parameter);
    }

    //支付返回验证
    public function get_notify(){
    	$this->ci = &get_instance();
	    /*取返回参数*/
        $cspay_id      = intval($this->ci->input->get_post('cspay_id',TRUE)); 
        $cspay_pid     = intval($this->ci->input->get_post('cspay_pid',TRUE));
        $pay_sid       = intval($this->ci->input->get_post('pay_sid',TRUE));
        $out_trade_no  = $this->ci->input->get_post('out_trade_no',TRUE,TRUE);
        $total_fee     = $this->ci->input->get_post('total_fee',TRUE,TRUE);
        $remark	 	   = $this->ci->input->get_post('remark',TRUE,TRUE);
        $sign          = $this->ci->input->get_post('sign',TRUE,TRUE);
        $partner	   = CT_Pay_ID;                    	
        $key		   = CT_Pay_Key;			    
        $return_url	   = $this->return_url;
        $notify_url	   = $this->notify_url;
		
        /* 检查数字签名是否正确 */
        $sign_arr = array(
            'pay_sid'      => $pay_sid,
            'cspay_id'     => $cspay_id,
            'cspay_pid'    => $cspay_pid,
            'out_trade_no' => $out_trade_no,
            'total_fee'    => $total_fee,
            'return_url'   => $return_url,
            'notify_url'   => $notify_url
        );
        $sign_md5 = $this->md5_sign($sign_arr,$key);

        //支付状态验证
        if ($sign_md5 == $sign && $cspay_pid==1){  //验证支付成功
            return true;
		} else {  //验证支付失败
            return false;
		}
    }

    //数组转URL地址
    public function arr_url($arr) {
        $arg  = "";
        foreach($arr as $key=>$val){
            $arg.=$key."=".urlencode($val)."&";
        }
        $arg = substr($arg,0,count($arg)-2);
        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
        return $arg;
    }

    //生成签名
    public function md5_sign($arr,$skey) {
		$arr_filter = array();
		foreach($arr as $key=>$val){
			if($key == "sign" || $val == "") continue;
			$arr_filter[$key] = $arr[$key];
		}
		//对数组排序
		ksort($arr_filter);
		reset($arr_filter);
		$arg  = "";
		foreach($arr_filter as $key=>$val){
			$arg.=$key."=".urlencode($val)."&";
		}
		$arg = substr($arg,0,count($arg)-2);
		//如果存在转义字符，那么去掉转义
		if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
		//MD5加密
		$sign =  strtoupper(md5($arg.$skey));
		return $sign;
    }
}