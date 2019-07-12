<?php
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-11
 */
//M3U8地址转换
function get_m3u8_url($time,$path,$type='m3u8'){
	if(substr(Zhuan_Path,0,2) == './'){
		$M3u8_Path = Web_Path.substr(Zhuan_Path,2);
	}else{
		$M3u8_Path = '/';
	}
	return $M3u8_Path.'m3u8/'.date('Ymd').'/'.md5($path).'/vod.'.$type;
}
//智能检索地址替换
function whole_url($key,$val,$url=''){
     $arr = explode('_',$url); //分割,1分类ID、2地区、3类型、4语言、5时间、6清晰度、7状态、8收费、9排序
     for($i=0;$i<10;$i++){
		 if(!empty($arr[$i])){
			$v = $arr[$i];
		 }else{
			if($i==8){
				$v = 'id';
			}else{
				$v = ($i==0 || $i==7) ? 0 : '';
			}
		 }
		 $arrs[$i] = $v;
     }
     switch($key){
		case 'cid' : $arrs[0]=$val;break;
		case 'diqu' : $arrs[1]=$val;break;
		case 'type' : $arrs[2]=$val;break;
		case 'yuyan' : $arrs[3]=$val;break;
		case 'year' : $arrs[4]=$val;break;
		case 'info' : $arrs[5]=$val;break;
		case 'state' : $arrs[6]=$val;break;
		case 'buy' : $arrs[7]=$val;break;
		case 'sort' : $arrs[8]=$val;break;
     }
     $arrs[10] = 1;
	 return implode('_', $arrs);
}

//给字符加链接
function taglink($Key,$type='',$ac='whole'){
	if(empty($Key)) return '';
     $List=$Key1="";
     $Str=" @,@，@|@/@_";
     $StrArr=explode('@',$Str);
     for($i=0;$i<=5;$i++){
        if(stristr($Key,$StrArr[$i])){
             $Key1=explode($StrArr[$i],$Key);
        }
	 }
     if(is_array($Key1)){
        for($j=0;$j<count($Key1);$j++){
			$Key1[$j] = trimall($Key1[$j]);
			$skey = ($ac=='whole') ? whole_url($type,$Key1[$j]) : $type.'='.$Key1[$j];
			$List.="<a target=\"tags\" href=\"".links($ac,'',0,$skey)."\">".$Key1[$j]."</a> ";
		}
	 }else{
	    $Key = trimall($Key);
		$skey = ($ac=='whole') ? whole_url($type,$Key) : $type.'='.$Key;
        $List="<a target=\"tags\" href=\"".links($ac,'',0,$skey)."\">".$Key."</a> ";
	 }
     return $List;
}

//给字符分割数组
function get_str_arr($str){
	 $Key = $str;
     $ext=",@，@|@/@_@ ";
     $StrArr=explode('@',$ext);
     for($i=0;$i<=5;$i++){
		if(stristr($str,$StrArr[$i])){
			$Key=explode($StrArr[$i],$str);
			break;
		}
	 }
     if(is_array($Key)){
		$arr = array();
        for($j=0;$j<count($Key);$j++){
			$arr[] = trimall($Key[$j]);
		}
		return $arr;
	 }
     return $Key;
}

//获取分页数目	
function getpagenum($str){
	preg_match('/\{ctcms_pagenum_([\d]*)\}/',$str,$pagearr);
	if(!empty($pagearr)){
		if(!empty($pagearr[1]) && (int)$pagearr[1]>0){
			$pagenum=$pagearr[1];
		}else{
			$pagenum=10;
		}	
	}else{
		$pagenum=10;
	}
	unset($pagearr);
	return $pagenum;
}

//分页标签替换
function getpagetpl($str,$pagearr=array()){
	$str = preg_replace('/\{ctcms_pagenum_([\d]*)\}/',$pagearr[4],$str);
	$str = str_replace('{ctcms_pagefirst}',$pagearr[0],$str);  //首页
	$str = str_replace('{ctcms_pagelast}',$pagearr[1],$str);  //尾页
	$str = str_replace('{ctcms_pageup}',$pagearr[2],$str);  //上页
	$str = str_replace('{ctcms_pagedown}',$pagearr[3],$str);  //下页
	$str = str_replace('{ctcms_pagelist}',$pagearr[5],$str);  //翻页
	$str = str_replace('{ctcms_pagesize}',$pagearr[6],$str); //每页数量
	$str = str_replace('{ctcms_pagenum}',$pagearr[7],$str); //总数量
	$str = str_replace('{ctcms_pagejs}',$pagearr[8],$str); //总页数
	$str = str_replace('{ctcms_page}',$pagearr[9],$str);  //当前页
	return $str;
}

//获取连接URL
function links($ac,$op='',$id=0,$where='',$html=0){ 
   //搜索链接
   if($ac=='search'){
		if(empty($where)) $where = $op;
		if(Web_Mode==2){
			$url=Web_Path.'index.php?c=search';
			$where=str_replace("?","&",$where);
			if(is_numeric($where)) $where='page='.$where;
			if(!empty($where)) $url.=(substr($where,0,1)!='&') ? '&'.$where : $where;
			if(Uri_Mode==1 && Html_Off == 0) $url=str_replace("index.php","",$url);
		}else{
			$url=site_url('search');
			$where=str_replace("?","&",$where);
			if(is_numeric($where)) $where='page='.$where;
			if(!empty($where)) $url.=(substr($where,0,1)!='?') ? '?'.$where : $where;
			if(Uri_Mode==1) $url=str_replace("index.php/","",$url);
		}
		$url=str_replace("?&","?",$url);
		return $url;
   }
   //生成静态
   $HTMLARR	= array('index','lists','show','play','comm','topic','opt');
   $ishtml = 1;
   if($ac=='comm' && $op!='index' && $op!='article' && !empty($op)) $ishtml = 0;
   if($ac=='play' && (Html_Play_Off == 0 || $op!='index')) $ishtml = 0;
   if($ishtml==1 && (!defined('IS_ADMIN') || defined('IS_HTML')) && Html_Off == 1 && in_array($ac, $HTMLARR)){
   		if($ac == 'index'){
			$page = (int)$where==0 ? 1 : $where;
   			if($page > 1){
   				$url = 'index_'.$page.'.html';
   			}else{
   				$url = 'index.html';
   			}
   		}elseif($ac == 'lists'){
   			$url = Html_List;
			$page = (int)$where==0 ? 1 : $where;
			$url = str_replace("[id]",$id,$url);
			$url = str_replace("[page]",$page,$url);
   		}elseif($ac == 'show'){
   			$url = Html_Show;
			$url = str_replace("[id]",$id,$url);
   		}elseif($ac == 'play'){
   			$url = Html_Play;
			$arr = explode("/",$id);
			$id = (int)$arr[0];
			$zu = empty($arr[1]) ? 0 :(int)$arr[1];
			$ji = empty($arr[2]) ? 0 :(int)$arr[2];
			$url = str_replace("[id]",$id,$url);
			$url = str_replace("[zu]",$zu,$url);
			$url = str_replace("[ji]",$ji,$url);
   		}elseif($ac == 'comm'){
   			if($op == 'article'){
   				$url = Html_News_Show;
   			}else{
	   			$url = Html_News_List;
   			}
			$page = (int)$where==0 ? 1 : $where;
			$url = str_replace("[page]",$page,$url);
			$url = str_replace("[id]",$id,$url);
   		}elseif($ac == 'topic'){
   			if($op == 'show'){
   				$url = Html_Topic_Show;
   			}else{
	   			$url = Html_Topic;
				$page = (int)$where==0 ? 1 : $where;
				$url = str_replace("[page]",$page,$url);
   			}
			$url = str_replace("[id]",$id,$url);
   		}elseif($ac == 'opt'){
   			$url = 'opt/'.str_replace('index/','',$op).'.html';
   		}
		return Web_Path.$url;
   }

   $WJTARR = array('user','pay','code','opt','hot','pages','gbook','hits');
   if(Uri_Mode==1 && !in_array($ac, $WJTARR) && $op!='bfq' && $op!='buy' && (!defined('IS_ADMIN') || defined('IS_HTML')) && $html==0){
	   if($ac=='whole'){
			$url = 'whole/'.$where.'.html';
	   }elseif($ac=='lists'){
			$page = (int)$where==0 ? 1 : $where;
			$url = Uri_List;
			$url=str_replace("[cid]",$id,$url);
			$url=str_replace("[page]",$page,$url);
	   }elseif($ac=='show'){
			$url = Uri_Show;
			$url=str_replace("[id]",$id,$url);
	   }elseif($ac=='play'){
			$url = Uri_Play;
			$arr = explode("/",$id);
			$id = (int)$arr[0];
			$zu = empty($arr[1]) ? 0 :(int)$arr[1];
			$ji = empty($arr[2]) ? 0 :(int)$arr[2];
			$url=str_replace("[id]",$id,$url);
			$url=str_replace("[zu]",$zu,$url);
			$url=str_replace("[ji]",$ji,$url);
	   }elseif($ac=='comm'){
			if($op=='article'){
				$page = (int)$where==0 ? 1 : $where;
				$url = Uri_Article;
				$url=str_replace("[id]",$id,$url);
				$url=str_replace("[page]",$page,$url);
			}elseif($op=='index' || $op==''){
				if($op=='') $id='all';
				$page = (int)$where==0 ? 1 : $where;
				$url = Uri_Comm;
				$url=str_replace("[cid]",$id,$url);
				$url=str_replace("[page]",$page,$url);
			}else{
				if(empty($id)){
					if(Web_Mode==2){
						$url='?c='.$ac.'&m='.$op;
					}else{
						$url=site_url($ac.'/'.$op);
					}
				}else{
					if(Web_Mode==2){
						$url='?c='.$ac.'&m='.$op.'&id='.$id;
					}else{
						$url=site_url($ac.'/'.$op.'/'.$id);
					}
				}
			}
	   }elseif($ac=='topic'){
			if($op=='show'){
				$url = Uri_Topic_Show;
				$url=str_replace("[id]",$id,$url);
			}else{
				$page = (int)$where==0 ? 1 : $where;
				$url = Uri_Topic;
				$url=str_replace("[page]",$page,$url);
			}
	   }else{
			if(Web_Mode==2){
				$url='?c='.$ac.'&m='.$op;
				if($ac == 'hot' && strstr($id,'/')){
					$arr = explode('/', $id);
					$url.='&id='.$arr[0].'&sid='.$arr[1];
				}else{
					if((int)$id>0 || $id!='') $url.='&id='.$id;
				}
				$where=str_replace("?","&",$where);
				if(is_numeric($where)) $where='page='.$where;
				if(!empty($where)) $url.=(substr($where,0,1)!='&') ? '&'.$where : $where;
			}else{
				if(empty($id)){
					$url=site_url($ac.'/'.$op);
				}else{
					if($ac == 'hot' && strstr($id,'/')){
						$url=site_url($ac.'/'.$op.'/'.(int)$arr[0].'/'.(int)$arr[1]);
					}else{
						$url=site_url($ac.'/'.$op.'/'.$id);
					}
				}
			}
	   }
	   $url=str_replace('//','/',Web_Path.$url);
	   $url=str_replace("index.php/","",$url);
   }elseif(Web_Mode==2){ //Url传参模式
	   if($ac=='whole') $where='key='.$where;
	   if($ac=='user' && (!defined('IS_ADMIN') || defined('IS_HTML'))){
		   $url=Web_Path.SELF.'?d='.$ac;
		   if(!empty($op)){
			   $arr = explode("/",$op);
			   if($arr[0]!='index') $url.='&c='.$arr[0];
			   if(!empty($arr[1]) && $arr[1]!='index') $url.='&m='.$arr[1];
			   if(!empty($arr[2])) $url.='&id='.$arr[2];
		   }
	   }else{
		   $url=Web_Path.SELF.'?c='.$ac;
		   if(!empty($op) && $op!='index'){
			   $arr = explode("/",$op);
			   if($arr[0]!='index') $url.='&m='.$arr[0];
			   if(!empty($arr[1])) $url.='&id='.$arr[1];
		   }
	   }
	   if((int)$id>0 || $id!=''){
		   if(strpos($id,'/') !== FALSE){
			   $arr = explode("/",$id);
			   $url.='&id='.$arr[0];
			   if(!empty($arr[1])){
			   		if($ac == 'hot'){
			   			$url.='&sid='.$arr[1];
			   		}else{
			   			$url.='&zu='.$arr[1];
			   		}
			   }
			   if(!empty($arr[2])) $url.='&ji='.$arr[2];
		   }else{
			   $url.='&id='.$id;
		   }
	   }
	   $where=str_replace("?","&",$where);
	   if(is_numeric($where)) $where='page='.$where;
	   if(!empty($where)) $url.=(substr($where,0,1)!='&') ? '&'.$where : $where;
	   //下面是去掉index.php ，需要支持伪静态规则
	   if(Uri_Mode==1) $url=str_replace("index.php","",$url);
   }else{ //Path_info 模式
	   if(!empty($op) && $ac!='search') $ac.='/'.$op;
	   if(empty($where)){
		   if(empty($id)){
				$url=site_url($ac);
		   }else{
				$url=site_url($ac.'/'.$id);
		   }
	   }else{
		   if($ac=='whole/index'){
				$url = site_url($ac.'/'.$where);
		   }elseif(empty($id)){
				$url=is_numeric($where) ? site_url($ac.'/'.$where) : site_url($ac).'?'.$where;
		   }else{
				$url=is_numeric($where) ? site_url($ac.'/'.$id.'/'.$where) : site_url($ac.'/'.$id).'?'.$where;
		   }
	   }
	   //下面是去掉index.php ，需要支持伪静态规则
	   if(Uri_Mode==1) $url=str_replace("index.php/","",$url);
   }
   $url=str_replace("?&","?",$url);
   if(defined('IS_HTML') || $html==1) $url=str_replace(SELF,"index.php",$url);
   return $url;
}

//获取图片
function getpic($pic){ 
   if(empty($pic)){
		$url = 'http://'.Web_Url.Web_Path.'attachment/nopic.png';
   }else{
		$url = $pic;
		if(substr($pic,0,7)!='http://' && substr($pic,0,8)!='https://'){
			if(Ftp_Is == 1){
				$url = Ftp_Url.$url;
			}
		}
   }
   return $url; 
}

//前台分页
function get_page($nums=1,$pagejs,$pages=1,$pagenum=10,$ac,$op='',$id=0,$where=''){
   if($where!='' && $ac!='whole') $where.='&page=';
   if($pagejs==0) $pagejs=1;
   if($pages>$pagejs){
	   $pages=$pagejs;
   }
   $pagefirst=links($ac,$op,$id,$where.'1');
   $pagelast=links($ac,$op,$id,$where.$pagejs);
   if($pages>1){
	   $pageup= links($ac,$op,$id,$where.($pages-1));
   }else{
	   $pageup= links($ac,$op,$id,$where.'1');
   }
   if($pagejs>$pages){
	   $pagenext=links($ac,$op,$id,$where.($pages+1));
   }else{
	   $pagenext=links($ac,$op,$id,$where.$pagejs);
   }
   $str='';
   if($pagejs<=$pagenum){
		for($i=1;$i<=$pagejs;$i++){
			   if($i==$pages){
					$str.="<li class='am-active'><a href='".links($ac,$op,$id,$where.$i)."'>".$i."</a></li>";
			   }else{
					$str.="<li><a href='".links($ac,$op,$id,$where.$i)."'>".$i."</a></li>";
			   }
		}
   }else{
		if($pages>=$pagenum){
			for($i=$pages-intval($pagenum/2);$i<=$pages+(intval($pagenum/2));$i++){
				if($i<=$pagejs){
					  if($i==$pages){
							$str.="<li class='am-active'><a href='".links($ac,$op,$id,$where.$i)."'>".$i."</a></li>";
					  }else{
							$str.="<li><a href='".links($ac,$op,$id,$where.$i)."'>".$i."</a></li>";
					  }
				}
			}
			if($i<=$pagejs){ 
					$str.="<li><a href='".links($ac,$op,$id,$where.$pagejs)."'>".$pagejs."</a></li>";
			}
		}else{
			for($i=1;$i<=$pagenum;$i++){
					  if($i==$pages){
							$str.="<li class='am-active'><a href='".links($ac,$op,$id,$where.$i)."'>".$i."</a></li>";
					  }else{
							$str.="<li><a href='".links($ac,$op,$id,$where.$i)."'>".$i."</a></li>";
					  }
			} 
			if($i<=$pagejs){ 
				$str.="<li><a href='".links($ac,$op,$id,$where.$pagejs)."'>".$pagejs."</a></li>";
			}
		}
   }
   $pagelist="<select onchange=javascript:window.location=this.options[this.selectedIndex].value;>";
   for($k=1;$k<=$pagejs;$k++){
	   $cls = ($k==$pages)?' selected':'';
	   $pagelist.="<option value='".links($ac,$op,$id,$where.$k)."'".$cls.">第".$k."页</option>";
   }
   $pagelist.="</select>";	
   return array($pagefirst,$pagelast,$pageup,$pagenext,$str,$pagelist);
}

//后台分页
function admin_page($url,$num,$pagejs,$pages=1){
   if($pagejs < 2) return '';
   if(strpos($url,'?') === FALSE){
	   $url.='?';
   }else{
	   $url.='&';
   }
   if($pages>$pagejs){
	   $pages=$pagejs;
   }
   // 显示右箭头
   $str='<div class="tip pull-left small-hide">显示 '.$pages.' 到 '.$pagejs.' ，共 '.$num.' 条</div>';
   $str.='<div class="fy-group pull-right">';
   // 判断当前页位置是不是在中间
   if($pages>1){
	   $str.= "<a class='fy-btn fy-left-icon pull-left' href='".$url."page=".($pages-1)."'></a>";
   }else{
	   $str.= "<a class='fy-btn fy-left-icon pull-left' href='".$url."page=1'></a>";
   }
   if($pagejs<=10){
	   for($i=1;$i<=$pagejs;$i++){
		   if($pages==$i){
				  $str.= "<a class='fy-btn fy-link pull-left active' href='".$url."page=".$i."'>".$i."</a>";
		   }else{
				  $str.= "<a class='fy-btn fy-link pull-left' href='".$url."page=".$i."'>".$i."</a>";
		   }
	  }
   }else{
		if($pages>=10){
    			for($i=$pages-4;$i<=$pages+4;$i++){
    				if($i<=$pagejs){
    				   if($pages==$i){
    						  $str.= "<a class='fy-btn fy-link pull-left active' href='".$url."page=".$i."'>".$i."</a>";
    				   }else{
    						  $str.= "<a class='fy-btn fy-link pull-left' href='".$url."page=".$i."'>".$i."</a>";
    				   }
    				}
    			}
    			if($i<=$pagejs){
    				   if($pages==$pagejs){
    						 $str.= "<a class='fy-btn fy-link pull-left active' href='".$url."page=".$pagejs."'>".$pagejs."</a>";
    				   }else{
    						 $str.= "<a class='fy-btn fy-link pull-left' href='".$url."page=".$pagejs."'>".$pagejs."</a>";
    				   }
    			}
	   }else{
    			for($i=1;$i<=10;$i++){
    				   if($pages==$i){
    						 $str.= "<a class='fy-btn fy-link pull-left active' href='".$url."page=".$i."'>".$i."</a>";
    				   }else{
    						 $str.= "<a class='fy-btn fy-link pull-left' href='".$url."page=".$i."'>".$i."</a>";
    				   }
    			}
    			if($i<=$pagejs){
    				   if($pages==$pagejs){
    						 $str.= "<a class='fy-btn fy-link pull-left active' href='".$url."page=".$pagejs."'>".$pagejs."</a>";
    				   }else{
    						 $str.= "<a class='fy-btn fy-link pull-left' href='".$url."page=".$pagejs."'>".$pagejs."</a>";
    				   }
    			}
	   }
   }
   if($pages<$pagejs){
	   $str.= "<a class='fy-btn fy-right-icon pull-left' href='".$url."page=".($pages+1)."'></a>";
   }else{
	   $str.= "<a class='fy-btn fy-right-icon pull-left' href='".$url."page=".$pagejs."'></a>";
   }
   $str.="<input id='gopage' class='fy-input-page pull-left' type='text' value='".$pages."'/>
   <a onclick=\"window.location='".$url."page='+gopage.value+'';\" class='fy-jump-btn pull-left'>跳转</a></div>";
   return $str;
}