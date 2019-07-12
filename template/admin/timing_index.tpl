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
<title>视频挂机定时采集</title>
</head>
<body>
<div class="content-page">
	<div class="content-page-nav">
			<label style="margin-right: 40px">
					<a class="nav-list"><i class="icon index-icon pull-left"></i>首页</a><a class="nav-list"> > 视频库管理 </a><a class="nav-list"> > 视频挂机定时采集 </a>
			</label>
			<label class="content-page-nav-tip text-red">友情提示：采集前需要先去资源库绑定好分类，采集时间最好选择凌晨。</label>
	</div>
	<form action="<?=links('timing','save')?>" method="post">
	<div class="content-page-submit-box">
			<div class="content-page-submit-select-group row">
					<div class="submit-select-group-title col-md-2">资源库选择：</div>
					<div class="submit-select-group-select col-md-8" id="x0">
						<?php
							for($i=0;$i<count($type);$i++){
								$check = in_array(($i+1),$ting['t']) ? ' checked="checked"' : '';
								echo '<span class="select-option"><input class="box" name="type[]" type="checkbox" value="'.($i+1).'"'.$check.'>&nbsp;'.$type[$i].'</span>';
							}
						?>
						<span class="select-option" onclick="xuans(0);"><i class="icon table-select-icon" ></i>全选</span>
					</div>
			</div>
			<div class="content-page-submit-select-group row">
					<div class="submit-select-group-title col-md-2">选择采集日期：</div>
					<div class="submit-select-group-select col-md-8" id="x1">
						<?php
						for($i=1;$i<32;$i++){
					            $k = $i<10 ? '0'.$i:$i;
						    $ckeck = in_array($k,$ting['d']) ? ' checked="checked"':'';
						    echo '<span class="select-option"><input class="box" name="d[]" type="checkbox" value="'.$k.'"'.$ckeck.'>&nbsp;'.$k.'</span>';
						    if($i==18) echo '<br>';
						}
						?>
							<span class="select-option" onclick="xuans(1);"><i class="icon table-select-icon" ></i>全选</span>
					</div>
			</div>
			<div class="content-page-submit-select-group row">
					<div class="submit-select-group-title col-md-2">选择采集时间：</div>
					<div class="submit-select-group-select col-md-8" id="x2">
						<?php
						for($i=1;$i<25;$i++){
					            $k = $i<10 ? '0'.$i:$i;
						    $ckeck = in_array($k,$ting['h']) ? ' checked="checked"':'';
						    echo '<span class="select-option"><input class="box" name="h[]" type="checkbox" value="'.$k.'"'.$ckeck.'>&nbsp;'.$k.'</span>';
						    if($i==18) echo '<br>';
						}
						?>
							<span class="select-option" onclick="xuans(2);"><i class="icon table-select-icon"></i>全选</span>
					</div>
			</div>
			<div class="content-page-submit-select-group row">
					<div class="submit-select-group-title col-md-2">采集多久的数据：</div>
					<div class="submit-select-group-select col-md-8" >
							<span class="select-option"><input name="day" type="radio" value="24"<?php if($ting['day']==24) echo ' checked';?>>每日更新</span>
							<span class="select-option"><input name="day" type="radio" value="72"<?php if($ting['day']==72) echo ' checked';?>>72小时内</span>
							<span class="select-option"><input name="day" type="radio" value="0"<?php if($ting['day']==0) echo ' checked';?>>所有资源</span>
					</div>
			</div>
			<div class="content-page-submit-select-group row">
					<div class="submit-select-group-btn col-md-offset-2 col-md-8">
							<button class="blue-white-btn submit-btn">提交开始</button>
					</div>
			</div>
			<div style="width:88%;height:300px;padding:6%;padding-top:20px;">
						<iframe style="width:100%;height:100%;" id="caiji" scrolling="yes" frameborder="0" src="<?=links('timing','zt')?>"></iframe>
			</div>
	</div>
</form>
</div>

<script type="text/javascript" src="<?=Base_Path?>validform/validform.min.js"></script>
<script type="text/javascript" src="<?=Base_Path?>layer/layer.js"></script>
<script type="text/javascript" src="<?=Base_Path?>admin/js/H-ui.js"></script>
<script type="text/javascript" src="<?=Base_Path?>admin/js/H-ui.admin.js"></script>
<script type="text/javascript">
var day='<?=implode('|',$ting['d'])?>';
var time='<?=implode('|',$ting['h'])?>';
var myDate = new Date();
var h = '-1';
function open(ha){
    var d = myDate.getDate();
    if(d<10) d='0'+d;
    var hb = myDate.getHours();
    if(hb<10) hb='0'+hb;
    var ri = day.indexOf(d);
    var xs = time.indexOf(hb);
    if(ri>-1 && xs>-1 && ha!=hb){
	open2();
	h = hb;
    }
}
function open2(){
    $('#caiji').attr('src','<?=links('timing','caiji')?>');
}
setInterval(function(){
   open(h);
},500);
function xuans(n) {
   $("#x"+n+" input:checkbox").each(function () {
       if ($(this).prop("checked")) {
           $(this).prop('checked',false);
       }else {
           $(this).prop('checked',true);
       }
   });
}
</script>
</body>
</html>
