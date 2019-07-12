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
class Pays {
    public function __construct (){
		if(CT_Pay==0) msg_url('在线支付功能已关闭~!','javascript:history.back();');
		$this->api_url = 'http://pay.manyiba.net/newpay';
		$this->return_url = 'http://'.Web_Url.links('pay','return_url');
		$this->notify_url = 'http://'.Web_Url.links('pay','notify_url');
	}

    //提交订单
	public function to($row = array(),$mid=1,$type='ma'){
		$mid = 0;
		$type = 'link';
        //订单介绍
		$body = $row['cid']==0 ? '会员在线购买金币' : '会员在线购买Vip';
      	/* 数字签名 */
        $sign_arr = array(
            'pay_sid'      => $row['sid'],
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
        if($type=='link'){ //跳转支付

            //跳转支付
            $payurl = $this->api_url.'?'.$this->arr_url($parameter);
            header("location:".$payurl);exit;

        }else{ //扫码支付

        	$title = $row['sid']==1 ? '支付宝' : '微信';
			if($row['sid']==3) $title='QQ钱包';
            $this->get_ma($parameter,$row['id'],$title);
        }
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

    //生成二维码付款
    public function get_ma($post,$id,$title='微信') {
    	$code_url = '';
    	$msg = '提交订单失败，请扫后再试~!';
        if(defined('MOBILE')){
        	$title = '请长按二维码,识别二维码用'.$title.'付款';
        }else{
        	$title = '用手机打开'.$title.',扫描二维码付款';
        }
    	//CURL获取二维码地址
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $json = curl_exec($ch);
        curl_close($ch);
        //返回数据
        if(substr($json,0,1)=='{'){
            $arr = json_decode($json,1);
            $code_url = $arr['code_url'];
            if(!empty($arr['msg'])) $msg = $arr['msg'];
        }else{
            $msg = $json;
        }

        if(empty($code_url)){
        	$html = '<p style="color:#556B2F;">'.$msg.'</p>';
        	$js = '';
        }else{
        	$js = 'setInterval("wxpay();",2000);function wxpay(){$.get("'.links('pay','init',$id).'",function(data) {if(data!="no"){ top.location.href=data; }});}';
        	$html = '<p style="color:#556B2F;">'.$title.'</p><img alt="扫码支付" src="'.$code_url.'" style="width:150px;height:150px;"/>';
        }

        echo '<html><head><meta http-equiv="content-type" content="text/html;charset=utf-8"/><meta name="viewport" content="width=device-width, initial-scale=1" /> <title>'.$title.'支付 - '.Web_Name.'</title><script type="text/javascript" src="'.Web_Path.'packs/jquery/jquery.min.js"></script></head><body style="width:100%;margin:0 auto;margin-top:10px;text-align:center;">'.$html.'<script type="text/javascript">window.parent.$("#pay-iframe").show();'.$js.'</script></body></html>';
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