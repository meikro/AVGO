<?php
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-11
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 第三方登录类
 */
class Denglu {

    function __construct (){
		//返回地址
		$this->redirect_uri = 'http://'.Web_Url.links("user","open/callback");
	 	$this->ci = &get_instance();
	}

    //登录
	public function login($ac,$log_state=''){
        $mode = $ac.'_login';
        $this->$mode($log_state);
    }

    //返回
	public function callback($ac,$log_state=''){
        $mode = $ac.'_callback';
        return $this->$mode($log_state);
    }

	//QQ登录
    public  function qq_login($log_state='') {
        if(Qq_Log==0) exit('QQ登录为关闭状态~');
        $scope= "get_user_info,add_share,list_album,add_album,upload_pic,add_topic,add_one_blog,add_weibo";
        $login_url='https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id='.Qq_Appid.'&redirect_uri='.$this->redirect_uri.'&state='.$log_state.'&scope='.$scope;
        header("Location:$login_url");
    }

    //微信登录
    public  function weixin_login($log_state='') {
        if(Wx_Log==0) exit('微信登录为关闭状态~');
        $login_url = "https://open.weixin.qq.com/connect/qrconnect?appid=".Wx_Appid."&redirect_uri=".urlencode($this->redirect_uri)."&response_type=code&scope=snsapi_login&state=".$log_state."#wechat_redirect"; 
        header("Location:$login_url");
    }

	//QQ登录返回
    public  function qq_callback($log_state='') {
        $state = $this->ci->input->get_post('state', TRUE, TRUE);
        $code = $this->ci->input->get('code', TRUE);

        if(empty($state) || empty($code)){
            msg_url('登录失败，返回参数错误~!',links('user','login')); 
        }

        if($state!=$log_state){
            msg_url('非法登录~!',links('user','login')); 
        }

        //获取ACCSEE_TOTEN
        $token_url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&"
                  . "client_id=" . Qq_Appid. "&redirect_uri=" . urlencode($this->redirect_uri)
                  . "&client_secret=" . Qq_Appkey. "&code=" . $code;
        $response = $this->get_url_contents($token_url);

        if (strpos($response, "callback") !== false){
           msg_url('登入失败，没获取到access_token！',links('user','login')); 
        }
        $params = array();
        parse_str($response, $params);
        $data['access_token']=$params['access_token'];
        $data['refresh_token']=$params['refresh_token'];
        $data['expire_in']=$params['expires_in'];

        //获取OPENID
        $graph_url = "https://graph.qq.com/oauth2.0/me?access_token=".$data['access_token'];
        $str  = $this->get_url_contents($graph_url);
        if (strpos($str, "callback") !== false){
           $lpos = strpos($str, "(");
           $rpos = strrpos($str, ")");
           $str  = substr($str, $lpos + 1, $rpos - $lpos -1);
        }
        $user = json_decode($str);
        if (isset($user->error)){
          msg_url('获取openid失败！',links('user','login')); 
        }
        $qqid = $user->openid;
        //获取用户信息
        $get_user_info = "https://graph.qq.com/user/get_user_info?"
               . "access_token=" . $data['access_token']
               . "&oauth_consumer_key=" . CS_Qqid
               . "&openid=" . $qqid
               . "&format=json";
        $info=$this->get_url_contents($get_user_info);
        $arr = json_decode($info, true);

        $data['nichen'] = $arr['nickname'];
        $data['pic'] = $arr['figureurl_2'];
        $data['uid'] = $qqid;
        return $data;
    }

    //微信登录返回
    public  function weixin_callback($log_state='') {
        $state = $this->ci->input->get_post('state', TRUE, TRUE);
        $code = $this->ci->input->get('code', TRUE);

        if(empty($state) || empty($code)){
            msg_url('登录失败，返回参数错误~!',links('user','login')); 
        }

        if($state!=$log_state){
            msg_url('非法登录~!',links('user','login')); 
        }

        //通过code换取token  
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".Wx_Appid."&secret=".Wx_Appkey."&code=".$code."&grant_type=authorization_code";  
        $json = $this->get_url_contents($url);  
        $arr = json_decode($json,true);  
        $token = $arr['access_token'];  
        $openid = $arr['openid'];  
        //拿到token后就可以获取用户基本信息了  
        if(empty($openid)){
           exit('获取用户信息失败！');
        }
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=$token&openid=$openid";  
        $json = $this->get_url_contents($url);//获取微信用户基本信息  
        $arr = json_decode($json,true);  

        $data['nichen'] = $arr['nickname'];//昵称  
        $data['pic'] = $arr['headimgurl'];//头像地址  
        $data['uid'] = $openid;
        return $data;
    }

    //POST 、 GET
    public  function get_url_contents($url,$post='',$type='get'){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
        if($type=='post') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post); 
        }else{
            curl_setopt($ch, CURLOPT_POST, 0);
        }
        curl_setopt($ch, CURLOPT_USERAGENT, 'cscms');
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}