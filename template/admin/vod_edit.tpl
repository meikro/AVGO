<title>视频操作</title>
<style type="text/css">#vpic .submit-long-input{height: 30px;line-height: 30px;}#vpic .btn{padding: 4px 12px;}</style>
</head>
<body>
<div class="content-page edit">
	<form action="<?=links('vod','save')?>" method="post" class="form form-horizontal" id="form-article-add">
	    <div class="content-page-tab-box table">
	        <div class="control-page">
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">分类：</div>
                    <div class="submit-select-group-select col-md-8">
                        <select class="submit-select" name="cid" size="1">
						<?php
						   	foreach ($lists as $row) {
							    $cls = $row->id == $cid ? ' selected="selected"' : '';
								echo '<option value="'.$row->id.'"'.$cls.'>├&nbsp;'.$row->name.'</option>';
								$array2 = $this->csdb->get_select('class','id,name',array('fid'=>$row->id),'xid ASC');
			                    foreach ($array2 as $row3) {
			                        $cls2 = $row3->id == $cid ? ' selected="selected"' : '';
			                        echo '<option value="'.$row3->id.'"'.$cls2.'>&nbsp;&nbsp;├&nbsp;'.$row3->name.'</option>';
			                    }
						   	}
						?>
						</select>
						<select class="submit-select" name="tid" size="1">
							<option value="1"<?php if($tid==1) echo ' selected';?>>推荐</option>
							<option value="0"<?php if($tid==0) echo ' selected';?>>不推荐</option>
						</select>
						<select class="submit-select" name="zid" size="1">
							<option value="1"<?php if($zid==1) echo ' selected';?>>主页幻灯</option>
							<option value="0"<?php if($zid==0) echo ' selected';?>>不上幻灯</option>
						</select>
						<select class="submit-select" name="yid" size="1">
							<option value="0"<?php if($yid==0) echo ' selected';?>>显示</option>
							<option value="1"<?php if($yid==1) echo ' selected';?>>隐藏</option>
						</select>
						<label><input class="box" name="addtime" type="checkbox" value="ok" checked="checked">&nbsp;更新时间</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">专题：</div>
                    <div class="submit-select-group-select col-md-8">
                        <select class="submit-select" name="ztid" size="1">
							<option value="0">不加入专题</option>
							<?php
							   foreach ($topic as $row) {
							        $cls = $row->id == $ztid ? ' selected="selected"' : '';
								echo '<option value="'.$row->id.'"'.$cls.'>'.$row->name.'</option>';
							   }
							?>
						</select>
                        <select class="submit-select" name="kid" size="1">
                            <option value="0">更新周期</option>
                            <option value="1"<?php if($kid==1) echo ' selected';?>>周一</option>
                            <option value="2"<?php if($kid==2) echo ' selected';?>>周二</option>
                            <option value="3"<?php if($kid==3) echo ' selected';?>>周三</option>
                            <option value="4"<?php if($kid==4) echo ' selected';?>>周四</option>
                            <option value="5"<?php if($kid==5) echo ' selected';?>>周五</option>
                            <option value="6"<?php if($kid==6) echo ' selected';?>>周六</option>
                            <option value="7"<?php if($kid==7) echo ' selected';?>>周日</option>
                        </select>
						<select class="submit-select" id="vip" name="vip" size="1" onchange="getvip();">
				            <option value="0">视频观看级别</option>
						    <option value="1"<?php if($vip==1) echo ' selected';?>>会员点播观看</option>
						    <option value="2"<?php if($vip==2) echo ' selected';?>>点播vip会员5折</option>
						    <option value="3"<?php if($vip==3) echo ' selected';?>>vip会员免费</option>
						    <option value="4"<?php if($vip==4) echo ' selected';?>>会员点播vip免费</option>
				        </select>
				        <span id="cions"<?php if($vip==0 || $vip==3) echo ' style="display:none;"';?>>
							点播金币：<input id="cion" class="submit-long-input" style="width:70px;" placeholder="点播金币" type="text" class="submit-long-input" value="<?=$cion?>" name="cion">
						</span>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">名称：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input placeholder="视频名称" type="text" class="submit-long-input" value="<?=$name?>" name="name">
						<input class="btn" type="button" onClick="caiji_db(this);" value="一键获取视频信息">
						<span id="caiji" style="display:none;">
							<select class="submit-select" id="douban" onchange="$('#daoru').click();" size="1"></select>
						</span>
						<input id="daoru" class="btn" style="display:none;" type="button" onClick="daoru_db(this);" value="导入">
						<label class="label-tip text-gray">来源于豆瓣</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">缩略图：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input type="text" class="submit-long-input" id="pic1" name="pic" value="<?=$pic?>">
                        <input class="btn" type="button" onClick="upload(1);" value="上传图片">
                        <label class="label-tip text-gray">视频缩略图</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">幻灯图：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input type="text" class="submit-long-input" id="pic2" name="pic2" value="<?=$pic2?>">
                        <input class="btn" type="button" onClick="upload(2);" value="上传图片">
                        <label class="label-tip text-gray">视频封面大图片</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">关键字：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input placeholder="多个用逗号来隔开" type="text" class="submit-long-input w35" name="tags" value="<?=$tags?>">
                        评分：<input type="text" class="submit-long-input w35" value="<?=$pf?>" name="pf">
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">导演：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input type="text" class="submit-long-input w35" name="daoyan" value="<?=$daoyan?>">
                        主演：<input type="text" class="submit-long-input w35" value="<?=$zhuyan?>" name="zhuyan">
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">清晰度：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input type="text" class="submit-long-input w35" name="info" value="<?=$info?>">
                        状态：<input type="text" class="submit-long-input w35" value="<?=$state?>" name="state">
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">地区：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input type="text" class="submit-long-input w35" name="diqu" value="<?=$diqu?>">
                        类型：<input type="text" class="submit-long-input w35" value="<?=$type?>" name="type">
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">语言：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input type="text" class="submit-long-input w35" name="yuyan" value="<?=$yuyan?>">
                        年份：<input type="text" class="submit-long-input w35" value="<?=$year?>" name="year">
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">模板：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input type="text" class="submit-long-input w35" name="skin" value="<?=$skin?>">
                        人气：<input type="text" class="submit-long-input w35" value="<?=$hits?>" name="hits">
                    </div>
                </div>
                <div class="content-page-submit-select-group row" id="cjpian" style="display:none;">
                    <div class="submit-select-group-tab-title col-md-2">片源采集：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input onClick="playurl(this);" type="button" class="btn" value="一键获取片源">
						<span id="caiji">
							<select class="submit-select" id="pian" onchange="$('#urls').click();" size="1">
								<option value="">-- 相关数据 --</option>
							</select>
						</span>
						<input id="urls" type="button" class="btn" onClick="daoru_url(this);" value="导入">
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">
                        视频截图：<br>
                        <input style="width:90px;" type="button" class="btn addvpic" value="+上传图片">
                    </div>
                    <div class="submit-select-group-select col-md-8">
                        <div id="vpic" style="border: 1px solid #e7e7eb;min-height:80px;max-height:187px;padding:5px;padding-bottom:0px;overflow:auto;">
                    <?php
                    foreach($vpic as $rowp) {
                        echo '<div id="vpic_'.$rowp->id.'" style="width:100%;margin-bottom:5px;">
                                <input placeholder="图片地址" type="text" class="submit-long-input" value="'.$rowp->url.'" name="vpic[]">
                                <input did="'.$rowp->id.'" type="button" class="btn delvpic" value="-删除">
                            </div>';
                    }
                    ?>
                        </div>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">
                    	播放地址：<br>
						<input style="width:70px;" type="button" class="btn addzu" value="+增加组">
					</div>
                    <div class="submit-select-group-select col-md-8" id="player">
					<?php
					$play='';
					foreach ($player as $rowp) {
					    $play.='<option value="'.$rowp->bs.'">'.$rowp->name.'</option>';
					}
					$arr = explode('#ctcms#',$url);
					for($i=0;$i<count($arr);$i++){
					    $parr = explode('###',$arr[$i]);
					    $bs = $parr[0];
					    $url = !empty($parr[1]) ? $parr[1] : '';
					    if(!empty($bs)){
					        $pname='<option value="'.$bs.'">'.getzd('player','name',$bs,'bs').'</option>';
					    }else{
					        $pname='';
					    }
		                echo '
						<div id="player_'.($i+1).'">
						    <div style="width:100%;margin-bottom:10px;">
						        <select class="submit-select" name="play[]" size="1">
						            '.$pname.str_replace($pname,'',$play).'
						        </select>
						        <input zid="'.($i+1).'" type="button" class="btn xiao" value="校正格式">
						        <input zid="'.($i+1).'" type="button" class="btn delzu" value="-删除">
						        <input zid="'.($i+1).'" type="button" class="btn delqz" value="去除前缀">
						        <input zid="'.($i+1).'" style="background-color: #f30;color: #fff;" type="button" class="btn upzu" value="上传视频">
						    </div>
						    <textarea name="url[]" class="submit-textarea pull-left" style="width:100%;height:100px;" placeholder="视频地址">'.$url.'</textarea>
						</div>';
					}
					?>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">
                    	下载地址：<br>
						<input style="width:70px;" type="button" class="btn adddown" value="+增加组">
					</div>
                    <div class="submit-select-group-select col-md-8" id="down">
					<?php
					$xia = '';
					$CT_Down = unserialize(CT_Down);
					foreach ($CT_Down as $k=>$v) {
					   $xia.='<option value="'.$k.'">'.$v.'</option>';
					}
					$arr = explode('#ctcms#',$down);
					for($d=0;$d<count($arr);$d++){
					    $darr = explode('###',$arr[$d]);
					    $dly = $darr[0];
					    $down = !empty($darr[1])?$darr[1]:$darr[0];
					    if(!empty($CT_Down[$dly])){
							$dname='<option value="'.$dly.'">'.$CT_Down[$dly].'</option>';
					    }else{
							$dname='';
					    }
		                           echo '
						<div id="down_'.($d+1).'">
						    <div style="width:100%;margin-bottom:10px;">
						        <select class="submit-select" name="xia[]" size="1">
						            '.$dname.str_replace($dname,'',$xia).'
						        </select>
						        <input xid="'.($d+1).'" type="button" class="btn xiao2" value="校正格式">
						        <input xid="'.($d+1).'" type="button" class="btn deldown" value="-删除">
						        <input xid="'.($d+1).'" type="button" class="btn delqz2" value="去除前缀">
						    </div>
						    <textarea name="down[]" class="submit-textarea pull-left" style="width:100%;height:100px;" placeholder="下载地址，每行一集">'.$down.'</textarea>
						</div>';
					}
					?>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">介绍：</div>
                    <div class="submit-select-group-select col-md-8">
                        <textarea class="submit-textarea pull-left" id="text" name="text" style="width:100%;height:280px;margin-right: 4px;color:#fff;"><?=$text?></textarea>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-btn col-md-offset-2 col-md-8">
                    	<input name="id" type="hidden" value="<?=$id?>">
                        <button class="blue-white-btn submit-btn" type="submit">保存</button>
                    </div>
                </div>
	        </div>
	    </div>
	</form>
</div>
<link rel="stylesheet" href="<?=Base_Path?>layui/css/layui.css">
<script src="<?=Base_Path?>layui/layui.all.js"></script>
<script type="text/javascript">
var layedit;
$(function(){
    var zid=<?=$i?>;
    var xid=<?=$d?>;
    //增加播放组
    $('.addzu').click(function(){
        zid++;
	 	var html = '<div id="player_'+zid+'"><div style="width:100%;margin:10px 0;"><select class="submit-select" name="play[]" size="1"><?=$play?></select> <input zid="'+zid+'" style="margin:0 2px;" type="button" class="btn xiao" value="校正格式"> <input zid="'+zid+'" style="margin:0 2px;" type="button" class="delzu btn" value="-删除"><input zid="'+zid+'" style="margin:0 2px;" type="button" class="btn delqz" value="去除前缀"><input zid="'+zid+'" style="margin:0 2px;background-color: #f30;color: #fff;" type="button" class="btn upzu" value="上传视频"></div><textarea name="url[]" class="submit-textarea pull-left" style="width:100%;height:100px;" placeholder="视频地址"></textarea></div>';
        $("#player").append(html);
    });
    //删除播放组
    $(document).on('click', '.delzu', function(e){
        var sid = $(this).attr('zid');
        $("#player_"+sid).remove();
    });
    //格式校正
    $(document).on('click', '.xiao', function(e){
        var sid = $(this).attr('zid');
        repairUrl(sid,'player');
    });
    //去除前缀
    $(document).on('click', '.delqz', function(e){
        var sid = $(this).attr('zid');
        delUrlqz(sid,'player');
    });
    //增加下载组
    $('.adddown').click(function(){
        xid++;
		var html = '<div id="down_'+xid+'"><div style="width:100%;margin:10px 0;"><select class="submit-select" name="xia[]" size="1"><?=$xia?></select><input xid="'+xid+'" style="margin:0 2px;" type="button" class="btn xiao2" value="校正格式"><input xid="'+xid+'" style="margin:0 2px;" type="button" class="deldown btn" value="-删除"><input xid="'+xid+'" style="margin:0 2px;" type="button" class="btn delqz2" value="去除前缀"></div><textarea name="down[]" class="submit-textarea pull-left" style="width:100%;height:100px;" placeholder="视频下载地址，每行一条"></textarea></div>';
        $("#down").append(html);
    });
    //删除下载组
    $(document).on('click', '.deldown', function(e){
        var sid = $(this).attr('xid');
        $("#down_"+sid).remove();
    });
    //下载组格式校正
    $(document).on('click', '.xiao2', function(e){
        var sid = $(this).attr('xid');
        repairUrl(sid,'down');
    });
    //下载组去除前缀
    $(document).on('click', '.delqz2', function(e){
        var sid = $(this).attr('xid');
        delUrlqz(sid,'down');
    });
    //上传视频
    $(document).on('click', '.upzu', function(e){
        var sid = $(this).attr('zid');
        uploadvod(sid);
    });
    //上传视频截图
    $(document).on('click', '.addvpic', function(e){
        layer_show('上传图片','<?=links('upload','',0,'ac=vpic&len=10')?>',400,'90%');
    });
    //视频截图删除
    $(document).on('click', '.delvpic', function(e){
        var did = $(this).attr('did');
        $.post('<?=links('vod','del_vpic')?>',{id: did},function(arr) {
            var msg = arr['msg'];
            if(msg == "ok"){//成功
                $('#vpic_'+did).remove();
            }else{
                get_msg(msg);
            }
        },'json');
    });
	//富文本编辑器
    layedit = layui.layedit;
    layedit.set({
        uploadImage: {
            url: '<?=links('upload','save',0,'dir=editor')?>'
        }
    });
    layedit.build('text', {
        hideTool: ['face', '|']
    });
});
function repairUrl(i,ac){
    var urlStr=$("#"+ac+"_"+i+" textarea").val();
    if (urlStr.length==0){get_msg('请填写地址');return false;}
    var urlArray=urlStr.split("\n");
    var newStr="";
    for(j=0;j<urlArray.length;j++){
		if(urlArray[j].length>0){
			t=urlArray[j].split('$'),flagCount=t.length-1
			switch(flagCount){
				case 0:
					urlArray[j]='第'+(j<9 ? '0' : '')+(j+1)+'集$'+urlArray[j];
				break;
				case 1:
					if(t[0]==''){
					    urlArray[j]=t[0]+'$'+urlArray[j];
					}else{
				        urlArray[j]=t[0]+'$'+t[1];
	                }
				break;
			}
			newStr+=urlArray[j]+"\n";
		}
    }
    $("#"+ac+"_"+i+" textarea").val(trimOuterStr(newStr,"\n"));
}
function delUrlqz(i,ac){
    var urlStr = $("#"+ac+"_"+i+" textarea").val();
    if (urlStr.length==0){get_msg('请填写地址');return false;}
    var urlArray=urlStr.split("\n");
    var newStr="";
    for(j=0;j<urlArray.length;j++){
		if(urlArray[j].length > 0){
			if(urlArray[j].indexOf('$') > -1){
				var t = urlArray[j].split('$');
				newStr+= t[1]+"\n";
			}else{
				newStr+=urlArray[j]+"\n";
			}
		}
    }
    $("#"+ac+"_"+i+" textarea").val(trimOuterStr(newStr,"\n"));
}
function trimOuterStr(str,outerstr){
	var len1
	len1=outerstr.length;
	if(str.substr(0,len1)==outerstr){str=str.substr(len1)}
	if(str.substr(str.length-len1)==outerstr){str=str.substr(0,str.length-len1)}
	return str
}
/*上传图片*/
function upload(n){
    layer_show('上传图片','<?=links('upload','',0,'ac=vod&sid=')?>'+n,400,260);
}
/*上传视频*/
var upindex = null;
function uploadvod(n){
	upindex = layer.open({
		type: 2,
		area: ['400px', '400px'],
		maxmin: true,
		shade:0.4,
		title: '上传视频',
		content: '<?=links('upload','vod',0,'sid=')?>'+n
	});
}
/*采集视频信息*/
var caiji = [];
function caiji_db(th){
    $(th).val("...");
    var name=$("input[name='name']").val();
    if(name==''){
        get_msg('请先输入视频名称');
		$(th).val("一键获取视频信息");
    }else{
		$.post('<?=links('vod','caiji')?>',{name: name},function(arr) {
		    var msg = arr['msg'];
		    var num = arr['num'];
		    var data = arr['str'];
		    if(msg == "ok"){//成功
	            $('#douban').html(data);
				 $('#caiji').show();
				 $('#daoru').show();
				 $('#cjpian').show();
				 get_msg('共获取到'+num+'个资源',2000,1);
				 $(th).val("一键获取视频信息");
		    }else{
		        get_msg('没有获取到相关数据~!');
				$(th).val("一键获取视频信息");
		    }
		},'json');
    }
}
/*采集导入视频信息*/
function daoru_db(th){
    $(th).val("...");
    var vid=$("#douban").val();
    if(vid==''){
        get_msg('请选择要导入的视频');
		$(th).val("导入");
    }else{
		$.post('<?=links('vod','caiji')?>',{id: vid},function(data) {
		    if(data){//成功
		        $.each(data, function (k, v) {
	            	$("input[name='"+k+"']").val(v);
				});
				$(document.getElementsByTagName('iframe')[0].contentWindow.document.body).html(data['text']);
				$(th).val("导入");
		    }else{
		        get_msg('没有获取到相关数据~!');
				$(th).val("导入");
		    }
		},'json');
    }
}
/*采集播放片源列表*/
function playurl(th){
    $(th).val("...");
    var name=$("input[name='name']").val();
    if(name==''){
        get_msg('请先输入视频名称');
		$(th).val("一键获取片源");
    }else{
		$.post('<?=links('vod','caiji')?>',{key: name},function(arr) {
			console.log(arr);
		    if(arr['msg'] == "ok"){//成功
		    	caiji = arr['str'];
		    	var data = '';
		    	for (var i = 0; i < caiji.length; i++) {
		    		data+='<option value="'+i+'">'+caiji[i].ly+'</option>';
		    	}
	            $('#pian').html(data);
			 	get_msg('共获取到'+caiji.length+'个片源',2000,1);
			 	$(th).val("一键获取片源");
		    }else{
		        get_msg('没有获取到相关数据~!');
				$(th).val("一键获取片源");
		    }
		},'json');
    }
}
/*片源地址导入*/
function daoru_url(th){
    $(th).val("...");
    var zu = $("#pian").val();
    if(zu == ''){
        get_msg('请选择要导入的片源');
		$(th).val("导入");
    }else{
	    if(caiji[zu]['vod']){//成功
			$("#player").html('');
			var ly = caiji[zu]['ly'];
			var newurl = caiji[zu]['vod'];
	        var urlarr2 = newurl.split("#");
		    var str = '';
		    for(k=0;k<urlarr2.length;k++){
		        if(urlarr2[k]){
	                var urlarr3 = urlarr2[k].split("$");
				    if(k == urlarr2.length-1){
				        if(urlarr3[1].indexOf("http://")==-1){
	                        str+=urlarr3[0]+"$"+urlarr3[1]+"&type="+ly;
						}else{
	                       str+=urlarr3[0]+"$"+urlarr3[1];
						}
				    }else{
				        if(urlarr3[1].indexOf("http://")==-1 && urlarr3[1].indexOf("https://")==-1){
	                        str+=urlarr3[0]+"$"+urlarr3[1]+"&type="+ly+"\n";
						}else{
	                        str+=urlarr3[0]+"$"+urlarr3[1]+"\n";
						}
				    }
				}
			}
			zid = 1;
	        var html = '<div id="player_'+zid+'"><div style="width:100%;margin:10px 0;"><select class="submit-select" name="play[]" size="1"><?=$play?></select> <input zid="'+zid+'" style="margin:0 2px;" type="button" class="btn xiao" value="校正格式"> <input zid="'+zid+'" style="margin:0 2px;" type="button" class="delzu btn" value="-删除"><input xid="'+zid+'" style="margin:0 2px;" type="button" class="btn delqz" value="去除前缀"></div><textarea name="url[]" class="submit-textarea pull-left" style="width:100%;height:100px;" placeholder="视频地址">'+str+'</textarea></div>';
            $("#player").append(html);
			$(th).val("导入");
	    }else{
	        get_msg('没有获取到相关数据~!');
			$(th).val("导入");
	    }
    }
}
function getvip(){
    var vip = $('#vip').val();
    if(vip==1 || vip==2 || vip==4){
		$('#cions').show();
    }else{
		$('#cions').hide();
		$('#cion').val('0');
    }
}
</script>
</body>
</html>
