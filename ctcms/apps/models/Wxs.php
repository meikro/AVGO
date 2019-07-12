<?php 
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-06
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Wxs extends CI_Model
{
    function __construct (){
		parent:: __construct ();
		//微信token
		$this->token     =  Weixin_Token;

		$this->echostr   =  $this->input->get_post('echostr');
		$this->signature =  $this->input->get_post('signature');
		$this->timestamp =  $this->input->get_post('timestamp');
		$this->nonce     =  $this->input->get_post('nonce');
    }

    //验证接口
    public function valid()
    {
        if($this->checkSignature()){
        	return true;
        }else{
			exit();
		}
    }

    //判断是否为微信过来
    private function checkSignature()
    {
		$tmpArr = array($this->token, $this->timestamp, $this->nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $this->signature ){
			return true;
		}else{
			return false;
		}
	}
}