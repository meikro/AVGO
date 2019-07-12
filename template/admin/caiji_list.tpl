<title>采集</title>
</head>
<body>
<!--背景灰色变暗-->
<div id="showbg" style="position:absolute;left:0px;top:0px;filter:Alpha(Opacity=20);opacity:0.2;background-color:#fff;z-index:8;"></div>
<!--绑定分类表单框-->
<div id="setbind" style="position:absolute;display:none;background:#ddd;border:1px solid #999;padding:4px 5px 5px 5px;z-index:9;"></div>
<div class="content-page">
    <div class="content-page-nav">
        <a class="nav-list"><i class="icon index-icon pull-left"></i>首页</a><a class="nav-list"> > 视频库管理 </a><a class="nav-list"> > 资源库 </a>
    </div>
    <div class="content-page-table">
        <div class="content-page-table-top">
            <div class="pull-left">
                <a class="gop" href="javascript:;" onclick="cmd('添加资源站点','<?=links('caiji','type_edit','add','k='.$k)?>')"><i class="icon table-add-all-icon pull-left"></i>新增<?=$api['name']?>资源</a>
            </div>
            <a class="gop pull-right" href="<?=links('caiji','index')?>">返回资源库</a>
        </div>
        <table class="table table-striped no-img-table">
            <thead>
                <tr>
                    <th align="center" colspan="7">
                    	分类绑定&nbsp;&nbsp;(点击<font color="#ff0033">×</font>可绑定分类)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="logs" href="<?=links('caiji','jie_bind',0,$api_url)?>"><font color=red>解除分类全部绑定</font></a>
						<span style="float:right"><a class="logs" href="<?=links('caiji','type','','ac='.$ac.'&op='.$gf.'&k='.$k)?>">&laquo;&nbsp;返回资源站列表&nbsp;&nbsp;</a></span>
					</th>
                </tr>
            </thead>
        	<tbody>
				<tr>
				<?php
				$count=count($vod_list)>76?76:count($vod_list);
				for ($i=0; $i<$count; $i++) {
				    $val=arr_key_value($LIST,$ac.'_'.$vod_list[$i]['id']);
					if($val){
				        $zt="&nbsp;&nbsp;√";
				    }else{
				        $zt="&nbsp;&nbsp;<font color='#ff0033'>×</font>";
				    }
					echo '<td height="25" align="center"><a class="logs" href="'.links('caiji','index',0,$api_url.'&cid='.$vod_list[$i]['id']).'">'.$vod_list[$i]['name'].'</a><a href="javascript:void(0)" onClick="setbind(event,\''.$ac.'\',\''.$vod_list[$i]['id'].'\');" id="bind_'.$vod_list[$i]['id'].'">'.$zt.'</a></td>';
				    if($i==6||$i==13||$i==20||$i==27||$i==34||$i==41||$i==48||$i==55||$i==62||$i==69||$i==76) echo '</tr><tr>';
				}
				?>
				<td align="center"><a href="<?=links('caiji','index',0,$api_url)?>"><font color=red>全部视频</font></a></td>
				</tr>
			</tbody>
		</table>
        <table class="table table-striped no-img-table">
                <tr>
                	<th colspan='5'>
						<span class="pull-left"> 
					    	<a sid="1" id="checkbox" class="btn radius" href="javascript:;">全选</a>
					    	<a class="btn radius" href="javascript:;" onClick="xuan('<?=links('caiji','index',0,'api='.$api.'&ac='.$ac.'&rid='.$rid.'&cid='.$cid.'&op=all&do=caiji&type='.$type.'&form='.$form.'')?>');">采集所选</a> 
					    	<a class="btn radius" href="<?=links('caiji','index',0,'api='.$api.'&ac='.$ac.'&rid='.$rid.'&cid='.$cid.'&op=day&do=caiji&type='.$type.'&form='.$form.'')?>">采集今日更新</a> 
					    	<a class="btn radius" href="<?=links('caiji','index',0,'api='.$api.'&ac='.$ac.'&rid='.$rid.'&cid='.$cid.'&op=all&do=caiji&type='.$type.'&form='.$form.'')?>">采集当前分类</a> 
						</span>  
						<span class="pull-right submit-select-group-select">
					        <input type="text" id="key" value="<?=$key?>" name="key" placeholder="搜索内容" style="width:200px" class="submit-long-input">
							<button name="so" class="btn btn-success" type="button" onClick="sos('<?=links('caiji','index',0,$api_url)?>')">搜视频</button>
						</span> 
			        </th>
				</tr>
		</table>
		<form action="" method="post" id="myform" name="myform">
        <table class="table table-striped no-img-table">
            <thead>
				<tr>
					<th class="tab-checkbox w10">选</th>
					<th class="w35">名称</th>
					<th align="center">来源</th>
					<th align="center">分类</th>
					<th align="center">更新时间</th>
				</tr>
            </thead>
        	<tbody>
<?php
if(empty($vod) || count($vod)==0){
       echo " <tr><td colspan='5' align='center'>暂无记录！</td></tr>";
}else{
       for ($j=0; $j<count($vod); $j++) {
	      $times=(date('Y-m-d',strtotime($vod[$j]['addtime']))==date('Y-m-d'))?'<font color=red>'.$vod[$j]['addtime'].'</font>':$vod[$j]['addtime'];
              echo '
		        <tr>
				<td class="tab-checkbox w10"><input type="checkbox" value="'.$vod[$j]['id'].'" name="id[]">'.$vod[$j]['id'].'</td>
				<td class="w35">'.$vod[$j]['name'].'</td>
				<td align="center">'.$vod[$j]['laiy'].'</td>
				<td align="center">'.$vod[$j]['cname'].'</td>
				<td align="center">'.$times.'</td>
			</tr>';

       }
}
?>
			<tr>
				<td colspan='5'>
				  <span class="pull-left"> 
				    <a sid="1" id="checkbox2" class="btn radius" href="javascript:;">全选</a>
				    <a class="btn radius" href="javascript:;" onClick="xuan('<?=links('caiji','index',0,'api='.$api.'&ac='.$ac.'&rid='.$rid.'&cid='.$cid.'&op=all&do=caiji&type='.$type.'&form='.$form.'')?>');">采集所选</a> 
				    <a class="btn radius" href="<?=links('caiji','index',0,'api='.$api.'&ac='.$ac.'&rid='.$rid.'&cid='.$cid.'&op=day&do=caiji&type='.$type.'&form='.$form.'')?>">采集今日更新</a> 
				    <a class="btn radius" href="<?=links('caiji','index',0,'api='.$api.'&ac='.$ac.'&rid='.$rid.'&cid='.$cid.'&op=all&do=caiji&type='.$type.'&form='.$form.'')?>">采集当前分类</a> 
				  </span>  
	        	</td>
			</tr>
			</tbody>
			</form>
		</table>
    <?=$pages?>
	</div>
</div>
<script> 
<?php if(Web_Mode==1){ ?>
var fh = '?';
<?php }else{ ?>
var fh = '&';
<?php } ?>
function xuan(links){
  var xuan=0;
  var t=[];
  $("td input:checkbox").each(function(){
      if($(this).prop("checked")){
          xuan++;
	  t.push($(this).val());
      }
  });
  if(xuan>0){
      var ids=t.join(',');
      location.href=links+'&ids='+ids;
  }else{
      layer.msg('请选择要入库的数据~!');
  }
}
//绑定分类
function setbind(event,ac,csid){
	$('#showbg').css({width:$(window).width(),height:$(window).height()});	
	var left = event.clientX+document.body.scrollLeft-70;
	var top = event.clientY+document.body.scrollTop+5;
	$.ajax({
		url: '<?=links('caiji','bind')?>'+fh+'ac='+ac+'&csid='+csid+'&random='+Math.random(),
		cache: false,
		async: false,
		success: function(res){
		    $("#setbind").css({left:left,top:top,display:""});			
		    $("#setbind").html(res);
	            $(".select").uedSelect({width : 160,clas:'uew-select2'});
		}
	});
}
//取消绑定
function hidebind(){
	$('#showbg').css({width:0,height:0});
	$('#setbind').hide();
}
//提交绑定分类
function submitbind(ac,csid){
	var cid=$('#cid').val();
	//alert(ac+csid+cid);
	$.ajax({
		url: '<?=links('caiji','bind_save')?>'+fh+'ac='+ac+'&cid='+cid+'&csid='+csid+'&random='+Math.random(),
		success: function(res){
		    if(res=='ok'){
			 $("#bind_"+csid).html("&nbsp;&nbsp;√");
		    }else{
			 $("#bind_"+csid).html("&nbsp;&nbsp;<font color='#ff0033'>×</font>");
		    }
		    hidebind();
		}
	});	
}
//搜索
function sos(link){
    var key=$('#key').val();
    if(key){
         location.href=link+'&key='+key;
    }else{
         layer.msg('请输入关键词~!');
    }
}
</script>
</body>
</html>