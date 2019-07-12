<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
				<link rel="stylesheet" type="text/css" href="<?=Base_Path?>admin/assets/css/main.css">
<link rel="stylesheet" type="text/css" href="<?=Base_Path?>admin/assets/css/style.css">
<script src="http://libs.baidu.com/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript" src="<?=Base_Path?>admin/assets/tool/bootstrap.min/bootstrap.min.js"></script>
<link  rel="stylesheet" type="text/css" href="<?=Base_Path?>admin/assets/tool/bootstrap.min/bootstrap.min.css">
<script src="<?=Base_Path?>admin/assets/tool/jq/getUrlParam.js"></script>
<script type="text/javascript" src="<?=Base_Path?>admin/assets/tool/jq/prefixfree.min.js"></script>
<script type="text/javascript" src="<?=Base_Path?>admin/assets/js/commen.js"></script>
<!--[if IE]>
<script src="<?=Base_Path?>admin/assets/tool/jq/html5shiv.js"></script>
<script src="<?=Base_Path?>admin/assets/tool/jq/respond.min.js"></script>
<![endif]-->
<title>视频批量操作</title>
<script type="text/javascript" src="<?=Base_Path?>jquery/jquery.min.js"></script>
<script type="text/javascript" src="<?=Base_Path?>layer/layer.js"></script>
</head>
<body>
<div class="content-page">
    <div class="content-page-nav">
        <a class="nav-list"><i class="icon index-icon pull-left"></i>首页</a><a class="nav-list"> > 视频库管理 </a><a class="nav-list"> > 视频批量操作 </a>
    </div>
    <div class="content-page-tab-box">
        <div class="tab-group">
            <div class="tab active" title="page_a">批量修改</div>
            <div class="tab" title="page_b">批量替换</div>
            <div class="tab" title="page_c">批量删除</div>
        </div>
        <div class="control-page">
            <div id="page_a">
							<form action="<?=links('vod','pl_save')?>" method="post" id="form-admin-add1">
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">操作方式：</div>
                    <div class="submit-select-group-select col-md-8">
                        <select class="submit-select" name="cid">
													<option value="0">按分类</option>
													<?php
													   $cid = '';
													   foreach ($lists as $row) {
														$cid.='<option value="'.$row->id.'">├&nbsp;'.$row->name.'</option>';
														$array2 = $this->csdb->get_select('class','id,name',array('fid'=>$row->id),'xid ASC');
									                    foreach ($array2 as $row3) {
									                        $cid.='<option value="'.$row3->id.'">&nbsp;&nbsp;├&nbsp;'.$row3->name.'</option>';
									                    }
													   }
													   echo $cid;
													?>
												</select>
                        <select class="submit-select" name="play">
													<option value="">按播放器</option>
													<?php
														$play='';
														foreach ($player as $row) {
																$play.='<option value="'.$row->bs.'">'.$row->name.'</option>';
														}
														echo $play;
													?>
												</select>

                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">操作对象：</div>
                    <div class="submit-select-group-select col-md-8">
                        <select class="submit-select" id="zd" name="zd" onchange="get_zd();">
													<option value="0">选择修改对象</option>
											    <option value="vip">观看级别</option>
											    <option value="cion">观看金币</option>
											    <option value="cid">分类ID</option>
											    <option value="ztid">专题ID</option>
											    <option value="zid">幻灯ID</option>
											    <option value="kid">更新周期</option>
											    <option value="tid">是否推荐</option>
											    <option value="yid">是否隐藏</option>
											    <option value="info">清晰度</option>
											    <option value="state">状态</option>
											    <option value="year">年份</option>
											    <option value="skin">模板</option>
											    <option value="play">播放器</option>
												</select>
												<span id="select"></span>
												<span id="text" style="display:none;"></span>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">选择时间：</div>
                    <div class="submit-select-group-select col-md-8">
                        <select class="submit-select" name="day">
													<option value="">不限制</option>
													<option value="1">1天内</option>
											    <option value="2">2天内</option>
											    <option value="3">3天内</option>
											    <option value="4">4天内</option>
											    <option value="5">5天内</option>
											    <option value="7">7天内</option>
											    <option value="10">10天内</option>
											    <option value="15">15天内</option>
											    <option value="30">30天内</option>
											    <option value="60">60天内</option>
											    <option value="90">90天内</option>
											    <option value="180">180天内</option>
											    <option value="365">365天内</option>
												</select>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">按视频ID：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input type="text" class="submit-input" placeholder="视频ID，多个ID用逗号隔开" name="ids" value="<?=$ids?>">
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-btn col-md-offset-2 col-md-8">
                        <button class="blue-white-btn submit-btn" type="button" id="sub1">提交</button>
                    </div>
                </div>
                <div class="page-tip">
                    <div class="title">友情提示：</div>
                    <div class="content text-red">
                        <div>1. 操作方式，可按分类、播放器、视频ID，如都不选则修改全部视频。</div>
                        <div>2. 选择操作对象后，可选择或输入修改的值。</div>
                    </div>
                </div>
						</form>
          </div>
          <div id="page_b" style="display: none">
						<form action="<?=links('vod','pl_tihuan')?>" method="post" id="form-admin-add2">
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">操作方式：</div>
                    <div class="submit-select-group-select col-md-8">
                        <select class="submit-select" name="cid">
													<option value="0">按分类</option>
													<?php
													   $cid = '';
													   foreach ($lists as $row) {
														$cid.='<option value="'.$row->id.'">├&nbsp;'.$row->name.'</option>';
														$array2 = $this->csdb->get_select('class','id,name',array('fid'=>$row->id),'xid ASC');
									                                        foreach ($array2 as $row3) {
										                                    $cid.='<option value="'.$row3->id.'">&nbsp;&nbsp;├&nbsp;'.$row3->name.'</option>';
										                                }
													   }
													   echo $cid;
													?>
												</select>
                        <select class="submit-select" name="play">
													<option value="">按播放器</option>
													<?php
													$play='';
													foreach ($player as $row) {
													    $play.='<option value="'.$row->bs.'">'.$row->name.'</option>';
													}
													echo $play;
													?>
												</select>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">操作对象：</div>
                    <div class="submit-select-group-select col-md-8">
                        <select class="submit-select" name="zd">
													<option value="0">选择修改对象</option>
											    <option value="name">视频标题</option>
											    <option value="pic">视频图片</option>
											    <option value="pic2">幻灯图片</option>
											    <option value="vip">观看级别</option>
											    <option value="cion">观看金币</option>
											    <option value="zid">幻灯ID</option>
											    <option value="cid">分类ID</option>
											    <option value="kid">更新周期</option>
											    <option value="ztid">专题ID</option>
											    <option value="tid">推荐ID</option>
											    <option value="yid">隐藏ID</option>
											    <option value="daoyan">导演</option>
											    <option value="zhuyan">主演</option>
											    <option value="type">类型</option>
											    <option value="diqu">地区</option>
											    <option value="yuyan">语言</option>
											    <option value="info">清晰度</option>
											    <option value="state">状态</option>
											    <option value="year">年份</option>
											    <option value="skin">模板</option>
											    <option value="url">播放地址</option>
											    <option value="text">介绍</option>
												</select>
                        <select class="submit-select" name="day">
													<option value="0">选择时间条件</option>
											    <option value="1">1天内</option>
											    <option value="2">2天内</option>
											    <option value="3">3天内</option>
											    <option value="4">4天内</option>
											    <option value="5">5天内</option>
											    <option value="7">7天内</option>
											    <option value="10">10天内</option>
											    <option value="15">15天内</option>
											    <option value="30">30天内</option>
											    <option value="60">60天内</option>
											    <option value="90">90天内</option>
											    <option value="180">180天内</option>
											    <option value="365">365天内</option>
												</select>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">按视频ID：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input type="text" class="submit-input" placeholder="视频ID，多个ID用逗号隔开" value="<?=$ids?>" name="ids">
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">要更换的内容：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input type="text" class="submit-input" value="" name="neir1">
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">修改后的内容：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input type="text" class="submit-input" value="" name="neir2">
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">替换条件：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input type="text" class="submit-input" value="" name="where">
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-btn col-md-offset-2 col-md-8">
                        <button class="blue-white-btn submit-btn" type="button" id="sub2">提交</button>
                    </div>
                </div>
                <div class="page-tip">
                    <div class="title">友情提示：</div>
                    <div class="content text-red">
                        <div>1. 操作方式，可按分类、播放器、视频ID，如都不选则修改全部视频。</div>
                        <div>2. 选择操作对象后，可选择或输入修改的值。</div>
                        <div>3.替换条件SQL语句，如：tid=1 （解释：只替换推荐的视频），不懂请留空</div>
                    </div>
                </div>
            </form>
					</div>
					<div id="page_c" style="display: none">
						<form action="<?=links('vod','pl_del')?>" method="post" id="form-admin-add3">
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">操作方式：</div>
                    <div class="submit-select-group-select col-md-8">
                        <select class="submit-select" name="cid">
													<option value="0">按分类</option>
													<?php
													   $cid = '';
													   foreach ($lists as $row) {
														$cid.='<option value="'.$row->id.'">├&nbsp;'.$row->name.'</option>';
														$array2 = $this->csdb->get_select('class','id,name',array('fid'=>$row->id),'xid ASC');
									                                        foreach ($array2 as $row3) {
										                                    $cid.='<option value="'.$row3->id.'">&nbsp;&nbsp;├&nbsp;'.$row3->name.'</option>';
										                                }
													   }
													   echo $cid;
													?>
												</select>
                        <select class="submit-select" name="play">
												<option value="">按播放器</option>
												<?php
												$play='';
												foreach ($player as $row) {
												    $play.='<option value="'.$row->bs.'">'.$row->name.'</option>';
												}
												echo $play;
												?>
												</select>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">操作条件：</div>
                    <div class="submit-select-group-select col-md-8">
                        <select class="submit-select"  name="day">
													<option>选择时间条件</option>
													<option value="1">1天内</option>
											    <option value="2">2天内</option>
											    <option value="3">3天内</option>
											    <option value="4">4天内</option>
											    <option value="5">5天内</option>
											    <option value="7">7天内</option>
											    <option value="10">10天内</option>
											    <option value="15">15天内</option>
											    <option value="30">30天内</option>
											    <option value="60">60天内</option>
											    <option value="90">90天内</option>
											    <option value="180">180天内</option>
											    <option value="365">365天内</option>
												</select>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">按视频ID：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input type="text" class="submit-input" placeholder="视频ID，多个ID用逗号隔开" value="<?=$ids?>" name="ids">
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-btn col-md-offset-2 col-md-8">
                        <button class="blue-white-btn submit-btn" type="button" id="sub3">提交</button>
                    </div>
                </div>
                <div class="page-tip">
                    <div class="title">友情提示：</div>
                    <div class="content text-red">
                        <div>1. 操作方式，可按分类、播放器、视频ID，如都不选则修改全部视频。</div>
                        <div>2. 选择操作对象后，可选择或输入修改的值。</div>
                        <div>3.替换条件SQL语句，如：tid=1 （解释：只替换推荐的视频），不懂请留空</div>
                    </div>
                </div>
            </form>
					</div>
        </div>
    </div>
</div>
<script>
		$(function(){
		    $('#sub1').click(function(){
			    layer.confirm('操作后不能恢复，确认要操作吗？',function(){
				$('#form-admin-add1').submit();
			    });
		    });
		    $('#sub2').click(function(){
			    layer.confirm('操作后不能恢复，确认要操作吗？',function(){
				$('#form-admin-add2').submit();
			    });
		    });
		    $('#sub3').click(function(){
			    layer.confirm('操作后不能恢复，确认要操作吗？',function(){
				$('#form-admin-add3').submit();
			    });
		    });
	        $(".tab").click(function () {
	            tab(this);
	        });
	    });
		function get_zd(){
		    var zd = $('#zd').val();
		    $('#text').html('').hide();
		    $('#select').html('').hide();
		    if(zd == 'vip'){
		        $('#select').html('<select class="select submit-select" name="neir" id="neir" size="1" onchange="get_vip();"><option value="0">免费播放</option><option value="1">会员点播</option><option value="2">点播vip会员5折</option><option value="3">VIP会员免费</option><option value="4">会员点播vip免费</option></select>').show();
		    } else if(zd == 'play'){
		        $('#select').html('<select class="select submit-select" name="neir" size="1"><?=$play?></select>').show();
		    } else if(zd == 'yid'){
		        $('#select').html('<select class="select submit-select" name="neir" size="1"><option value="0">显示</option><option value="1">隐藏</option></select>').show();
		    } else if(zd == 'kid'){
		        $('#select').html('<select class="select submit-select" name="neir" size="1"><option value="1">周一</option><option value="2">周二</option><option value="3">周三</option><option value="4">周四</option><option value="5">周五</option><option value="6">周六</option><option value="7">周日</option></select>').show();
		    } else if(zd == 'tid'){
		        $('#select').html('<select class="select submit-select" name="neir" size="1"><option value="0">不推荐</option><option value="1">推荐</option></select>').show();
		    } else if(zd == 'cid'){
		        $('#select').html('<select class="select submit-select" name="neir" size="1"><?=$cid?></select>').show();
		    } else {
		        var text = $('#zd option:selected').text();
		        $('#text').html('<input placeholder="请输入'+text+'" type="text" style="width:300px;" class="submit-long-input" value="" name="neir">').show();
		    }
		}
		function get_vip(){
		    var vip = $('#neir').val();
		    $('#text').html('').hide();
		    if(vip==1 || vip==2 || vip==4){
		        $('#text').html('<input placeholder="请输入点播金币数量" type="text" class="submit-input" value="" name="cion">').show();
		    }
		}
</script>
</body>
</html>
