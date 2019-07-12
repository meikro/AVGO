<?php
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-11
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Wx extends Ctcms_Controller {

    function __construct() {
        parent::__construct();        
	    $this->load->model('wxs');
    }

    public function code() {
		        $echostr = $this->input->get_post('echostr');
		        $neir = @file_get_contents('php://input');	
                $this->wxs->valid();

		        $MsgType = 'event';
		        $msg = $event ='';
		        if(!empty($neir)){
	                $xml = @simplexml_load_string($neir);
			        $ToUserName = (string) $xml->ToUserName; //开发者微信号ID
                    $OpenID = (string) $xml->FromUserName; //发送者账号ID
                    $MsgType = (string) $xml->MsgType; //消息类型
                    $msg = (string) $xml->Content; //消息内容
                    $event = (string) $xml->Event; //关注状态
					$msg = str_checkhtml($msg,1);
		        }

                //响应关注消息
		        if(!empty($event)){
                    $webname=Web_Name;
					$neir="感谢您关注".Web_Name."！\r\n查电影请发送：电影名或者演员名\r\n查询最新电影：最新电影或者最新电视\r\n随机推荐请发：随机推荐\r\n更多电影尽在http://".Web_Url."";
					$neir="<Content><![CDATA[".$neir."]]></Content>";
					echo "<xml><ToUserName><![CDATA[".$OpenID."]]></ToUserName><FromUserName><![CDATA[".$ToUserName."]]></FromUserName><CreateTime>".time()."</CreateTime><MsgType><![CDATA[text]]></MsgType>".$neir."</xml>";
				}
         		//相应回复消息
		 		if(!empty($msg)){
					 $type="text";
					 $ok=0;
					 if(strpos($msg,'帮助') !== FALSE){
					      $neir="查电影请发送：@电影名\r\n查询最新电影请发：最新电影或者最新电视\r\n随机推荐请发：随机推荐\r\n忘记指令了发：帮助\r\n更多电影尽在http://".Web_Url."";
					 }elseif(strpos($msg,'最新') !== FALSE){
                          //最新电影
						  $neir="以下是最新更新视频：\r\n";
						  if(strpos($msg,'电视') !== FALSE){
						      $query=$this->db->query("select id,name from ".CT_SqlPrefix."vod where cid=2 order by addtime desc limit 10");
						  }else{
						      $query=$this->db->query("select id,name from ".CT_SqlPrefix."vod where cid=1 order by addtime desc limit 10");
						  }
						  $i=1;
						  foreach ($query->result() as $row) { 
						       $neir.=$i."、".$row->name."\r\nhttp://".Web_Url.links('show','index',$row->id)."\r\n";
							   $i++;
							   $ok++;
						  }
						  if($ok>0){
						       $neir.="查看更多：http://".Web_Url.links('opt','index','new')."\r\n";
						  }
					 }elseif(strpos($msg,'随机') !== FALSE){
                          //随机推荐
						  $neir="以下是随机推荐视频：\r\n";
						  $query=$this->db->query("select id,name from ".CT_SqlPrefix."vod where tid=1 order by rand() desc limit 10");
						  $i=1;
						  foreach ($query->result() as $row) { 
						       $neir.=$i."、".$row->name."\r\nhttp://".Web_Url.links('show','index',$row->id)."\r\n";
							   $i++;
							   $ok++;
						  }
					 }else{
                          //搜索电影
						  $neir="以下是《".$msg."》的相关视频：\r\n";
						  $msgs=str_replace("@","",$msg);
						  $query=$this->db->query("select id,name from ".CT_SqlPrefix."vod where name like '%".$msgs."%' or zhuyan like '%".$msgs."%' or type like '%".$msgs."%' order by id desc limit 10");
						  //$query=$this->db->query("select id,name from ".CT_SqlPrefix."vod where name like '%".$msgs."%' order by id desc limit 10");
						  $i=1;
						  foreach ($query->result() as $row) { 
						       $neir.=$i."、".$row->name."\r\nhttp://".Web_Url.links('show','index',$row->id)."\r\n";
							   $i++;
							   $ok++;
						  }
						  if($ok>9){
						       $neir.="查看更多：http://".Web_Url.links('search','?wd='.rawurlencode($msg))."\r\n";
						  }
					 }
					 if($ok==0){
					      $neir="抱歉：没有找到《".$msg."》的相关视频，搜索尽量不要输入全文\r\n如果实在没有，请访问主页：\r\nhttp://".Web_Url.Web_Path."";
					 }
					 echo "<xml><ToUserName><![CDATA[".$OpenID."]]></ToUserName><FromUserName><![CDATA[".$ToUserName."]]></FromUserName><CreateTime>".time()."</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[".$neir."]]></Content></xml>";
		 		}
				echo $echostr;
    }
}