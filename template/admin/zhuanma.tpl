<title>转码设置</title>
</head>
<body>
	<div class="content-page">
			<div class="content-page-nav">
					<a class="nav-list"><i class="icon index-icon pull-left"></i>首页</a><a class="nav-list"> > 系统配置 </a><a class="nav-list"> > 转码设置 </a>
			</div>
			<form action="<?=links('zhuanma','save')?>" method="post" class="form form-horizontal" id="form-article-add">
					<div class="content-page-tab-box">
							<div class="tab-group">
									<div class="tab active" title="page_a">转码配置</div>
									<div class="tab" title="page_b">试看配置</div>
							</div>
							<div class="control-page">
									<div id="page_a">
											<div class="content-page-submit-select-group row">
													<div class="submit-select-group-tab-title col-md-2">转码开关：</div>
													<div class="submit-select-group-select col-md-8">
															<select id="zm" name="Zhuan_Is" class="submit-select" onchange="get_zm();">
															<option value="0"<?php if(Zhuan_Is==0) echo ' selected="selected"';?>>关闭</option>
															<option value="1"<?php if(Zhuan_Is==1) echo ' selected="selected"';?>>开启</option>
															</select>
															<label class="label-tip text-gray">转码开关，是否开启</label>
													</div>
											</div>
											<div id="zmid"<?php if(Zhuan_Is==0) echo ' style="display:none;"';?>>
												<div class="content-page-submit-select-group row">
														<div class="submit-select-group-tab-title col-md-2">转码码率：</div>
														<div class="submit-select-group-select col-md-8">
															<select name="Zhuan_Kbps" class="submit-select">
															  <option value="">原画</option>
															  <option value="120k"<?php if(Zhuan_Kbps=='120k') echo ' selected="selected"';?>>120k</option>
															  <option value="240k"<?php if(Zhuan_Kbps=='240k') echo ' selected="selected"';?>>240k</option>
															  <option value="360k"<?php if(Zhuan_Kbps=='360k') echo ' selected="selected"';?>>360k</option>
															  <option value="520k"<?php if(Zhuan_Kbps=='520k') echo ' selected="selected"';?>>520k</option>
															  <option value="720k"<?php if(Zhuan_Kbps=='720k') echo ' selected="selected"';?>>720k</option>
															  <option value="1000k"<?php if(Zhuan_Kbps=='1000k') echo ' selected="selected"';?>>1000k</option>
															  <option value="1800k"<?php if(Zhuan_Kbps=='1800k') echo ' selected="selected"';?>>1800k</option>
															</select>
														</div>
												</div>
												<div class="content-page-submit-select-group row">
														<div class="submit-select-group-tab-title col-md-2">画面尺寸：</div>
														<div class="submit-select-group-select col-md-8">
															<input type="text" class="submit-long-input" name="Zhuan_Size" value="<?=Zhuan_Size?>">
															<label class="label-tip text-gray">如：320x180，留空则是原大小</label>
														</div>
												</div>
												<div class="content-page-submit-select-group row">
														<div class="submit-select-group-tab-title col-md-2">转码时长：</div>
														<div class="submit-select-group-select col-md-8">
															<input type="text" class="submit-long-input" name="Zhuan_Time" value="<?=Zhuan_Time?>">
															<label class="label-tip text-gray">秒数,单个TS的时长，建议设置为10左右</label>
														</div>
												</div>
												<div class="content-page-submit-select-group row">
														<div class="submit-select-group-tab-title col-md-2">保存路径：</div>
														<div class="submit-select-group-select col-md-8">
															<input type="text" class="submit-long-input" name="Zhuan_Path" value="<?=Zhuan_Path?>">
															<label class="label-tip text-gray">m3u8保存路径，如：e:/m3u8/，网站根目录下用./开头</label>
														</div>
												</div>
												<div class="content-page-submit-select-group row">
														<div class="submit-select-group-tab-title col-md-2">转码域名：</div>
														<div class="submit-select-group-select col-md-8">
															<input type="text" class="submit-long-input" name="Zhuan_Url" value="<?=Zhuan_Url?>">
															<label class="label-tip text-gray">转码域名，如：zm.xxx.com 域名绑定到网站根目录</label>
														</div>
												</div>
												<div class="content-page-submit-select-group row">
														<div class="submit-select-group-tab-title col-md-2">访问域名：</div>
														<div class="submit-select-group-select col-md-8">
															<input type="text" class="submit-long-input" name="Zhuan_M3u8_Url" value="<?=Zhuan_M3u8_Url?>">
															<label class="label-tip text-gray">M3U8访问域名，站内路径域名则绑定到网站根目录</label>
														</div>
												</div>
												<div class="content-page-submit-select-group row">
														<div class="submit-select-group-tab-title col-md-2">视频水印：</div>
														<div class="submit-select-group-select col-md-8">
																<select class="submit-select" id="pic1" name="Zhuan_Sy_Is" onchange="get_pic();">
																	<option value="0"<?php if(Zhuan_Sy_Is==0) echo ' selected="selected"';?>>关闭</option>
							  										<option value="1"<?php if(Zhuan_Sy_Is==1) echo ' selected="selected"';?>>开启</option>
																</select>
																<label class="label-tip text-gray">视频水印开关</label>
														</div>
												</div>
											</div>
											<div id="pic2"<?php if(Zhuan_Sy_Is==0) echo ' style="display:none;"';?>>
												<div class="content-page-submit-select-group row">
														<div class="submit-select-group-tab-title col-md-2">水印位置：</div>
														<div class="submit-select-group-select col-md-8">
															<select name="Zhuan_Sy_Pos" class="submit-select">
																<option value="0"<?php if(Zhuan_Sy_Pos==0) echo ' selected="selected"';?>>左上角</option>
																<option value="1"<?php if(Zhuan_Sy_Pos==1) echo ' selected="selected"';?>>右上角</option>
																<option value="2"<?php if(Zhuan_Sy_Pos==2) echo ' selected="selected"';?>>左下角</option>
																<option value="3"<?php if(Zhuan_Sy_Pos==3) echo ' selected="selected"';?>>右下角</option>
															</select>
															<label class="label-tip text-gray">水印位置</label>
														</div>
												</div>
												<div class="content-page-submit-select-group row">
														<div class="submit-select-group-tab-title col-md-2">水印间距：</div>
														<div class="submit-select-group-select col-md-8">
															<input type="text" class="submit-long-input" name="Zhuan_Sy_Lt" value="<?=Zhuan_Sy_Lt?>">
															<label class="label-tip text-gray">水印间距，如：10:10 ，前面为左边距，右边为上边距</label>
														</div>
												</div>
												<div class="content-page-submit-select-group row">
														<div class="submit-select-group-tab-title col-md-2">水印图片：</div>
														<div class="submit-select-group-select col-md-8">
															<input type="text" class="submit-long-input" name="Zhuan_Sy_Pic" value="<?=Zhuan_Sy_Pic?>">
															<label class="label-tip text-gray">水印图片路径</label>
														</div>
												</div>
											</div>
											<div class="content-page-submit-select-group row">
													<div class="submit-select-group-tab-title col-md-2">截图尺寸：</div>
													<div class="submit-select-group-select col-md-8">
														<input type="text" class="submit-long-input" name="Zhuan_Jpg_Size" value="<?=Zhuan_Jpg_Size?>">
														<label class="label-tip text-gray">如：320x180，留空则跟视频保持一致</label>
													</div>
											</div>
											<div class="content-page-submit-select-group row">
													<div class="submit-select-group-tab-title col-md-2">截图张数：</div>
													<div class="submit-select-group-select col-md-8">
														<input type="text" class="submit-long-input" name="Zhuan_Jpg_Num" value="<?=Zhuan_Jpg_Num?>">
														<label class="label-tip text-gray">如：10</label>
													</div>
											</div>
											<div class="content-page-submit-select-group row">
													<div class="submit-select-group-tab-title col-md-2">截图时间：</div>
													<div class="submit-select-group-select col-md-8">
														<input type="text" class="submit-long-input" name="Zhuan_Jpg_Time" value="<?=Zhuan_Jpg_Time?>">
														<label class="label-tip text-gray">秒数，间隔几秒种的画面截取为图片</label>
													</div>
											</div>
											<div class="content-page-submit-select-group row">
												<div class="submit-select-group-btn col-md-offset-2 col-md-8">
														<button class="blue-white-btn submit-btn" type="submit">保存</button>
												</div>
											</div>
									</div>
									<div id="page_b" style="display: none;">
											<div class="content-page-submit-select-group row">
													<div class="submit-select-group-tab-title col-md-2">试看开关：</div>
													<div class="submit-select-group-select col-md-8">
															<select id="sk" name="Zhuan_Sk" class="submit-select" onchange="get_sk();">
															<option value="0"<?php if(Zhuan_Sk==0) echo ' selected="selected"';?>>关闭</option>
															<option value="1"<?php if(Zhuan_Sk==1) echo ' selected="selected"';?>>开启</option>
															</select>
															<label class="label-tip text-gray">收费视频试看开关，是否开启</label>
													</div>
											</div>
											<div id="skid"<?php if(Zhuan_Sk==0) echo ' style="display:none;"';?>>
												<div class="content-page-submit-select-group row">
														<div class="submit-select-group-tab-title col-md-2">试看方式：</div>
														<div class="submit-select-group-select col-md-8">
																<select id="sk2" name="Zhuan_Sk_Type" class="submit-select" onchange="get_sk2();">
																<option value="0"<?php if(Zhuan_Sk_Type==0) echo ' selected="selected"';?>>按时长</option>
																<option value="1"<?php if(Zhuan_Sk_Type==1) echo ' selected="selected"';?>>按次数</option>
																</select>
																<label class="label-tip text-gray">按时长则是每部试看多少秒，按数量是试看多少次</label>
														</div>
												</div>
												<div class="content-page-submit-select-group row" id="skid2"<?php if(Zhuan_Sk_Type==1) echo ' style="display:none;"';?>>
														<div class="submit-select-group-tab-title col-md-2">试看时长：</div>
														<div class="submit-select-group-select col-md-8">
															<input type="text" class="submit-long-input" name="Zhuan_Sk_Time" value="<?=Zhuan_Sk_Time?>">
															<label class="label-tip text-gray">如：30，试看时长，秒数，（只支持本地转码视频）</label>
														</div>
												</div>
												<div class="content-page-submit-select-group row" id="skid3"<?php if(Zhuan_Sk_Type==0) echo ' style="display:none;"';?>>
														<div class="submit-select-group-tab-title col-md-2">试看次数：</div>
														<div class="submit-select-group-select col-md-8">
															<input type="text" class="submit-long-input" name="Zhuan_Sk_Nums" value="<?=Zhuan_Sk_Nums?>">
															<label class="label-tip text-gray">如：3，试看次数</label>
														</div>
												</div>
											</div>
											<div class="content-page-submit-select-group row">
												<div class="submit-select-group-btn col-md-offset-2 col-md-8">
														<button class="blue-white-btn submit-btn" type="submit">保存</button>
												</div>
											</div>
									</div>
							</div>
					</div>
				</form>
	</div>
<script type="text/javascript">
$(function () {
    $(".tab").click(function () {
        tab(this);
    });
});
function get_pic(){
    var pic = $('#pic1').val();
    if(pic==1){
		$('#pic2').show();
    } else { 
		$('#pic2').hide();
    }
}
function get_zm(){
    var zm = $('#zm').val();
    if(zm==1){
		$('#zmid').show();
    } else { 
		$('#zmid').hide();
    }
}
function get_sk(){
    var sk = $('#sk').val();
    if(sk==1){
		$('#skid').show();
    } else { 
		$('#skid').hide();
    }
}
function get_sk2(){
    var sk = $('#sk2').val();
    if(sk==1){
		$('#skid2').hide();
		$('#skid3').show();
    } else { 
    	$('#skid2').show();
		$('#skid3').hide();
    }
}
</script>
</body>
</html>
