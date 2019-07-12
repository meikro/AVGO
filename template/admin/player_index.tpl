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
<title>播放器管理</title>
</head>
<body>
<div class="content-page">
<div class="content-page-nav">
		<a class="nav-list"><i class="icon index-icon pull-left"></i>首页</a><a class="nav-list"> > 视频库管理 </a><a class="nav-list"> > 视频播放器管理 </a>
</div>
<div class="content-page-table">
		<div class="content-page-table">
				<div class="content-page-table-top">
						<div class="pull-left">
								<a class="gop" href="javascript:;" onclick="cmd('添加播放器','<?=links('player','edit')?>')">
									<i class="icon table-add-all-icon pull-left"></i>添加播放器</a>
								<label class="gop big-hide">共有数据：<a class="text-red"><?=$nums?></a>条</label>
						</div>
						<!-- <a class="gop pull-right"><i class="icon table-delete-all-icon pull-left"></i>批量删除</a> -->
				</div>
				<table class="table table-striped no-img-table">
						<thead>
						<tr>
								<th class="small-hide">ID</th>
								<th>标示</th>
								<th>名称</th>
								<th class="small-hide">介绍</th>
								<th class="table-last-group">操作</th>
						</tr>
						</thead>
						<tbody>
							<?php
							if(empty($array)) echo '<tr><td colspan="5" class="text-c">没有记录~</td></tr>';
							foreach ($array as $row) {
							                  echo '
										<tr>
											<td class="small-hide">'.$row->id.'</td>
											<td>'.$row->bs.'</td>
											<td>'.$row->name.'</td>
											<td class="small-hide">'.$row->text.'</td>
											<td class="table-last-group">
												<a title="编辑" href="javascript:;" onclick="cmd(\'播放器编辑\',\''.links('player','edit',0,'id='.$row->id).'\')" style="text-decoration:none">
													<i class="icon table-update-icon"></i>
												<a title="删除" href="javascript:;" onclick="del(this,'.$row->id.')" style="text-decoration:none">
													<i class="icon table-delete-icon"></i></a>
												</td>
										</tr>';
							}
							?>
						</tbody>
				</table>
				<div class="content-page-table-fy">
					<?=$pages?>
				</div>
		</div>

</div>
</div>
<script type="text/javascript" src="<?=Base_Path?>layer/layer.js"></script>
<script type="text/javascript" src="<?=Base_Path?>admin/js/H-ui.js"></script>
<script type="text/javascript" src="<?=Base_Path?>admin/js/H-ui.admin.js"></script>
<script type="text/javascript">
/*播放器-添加*/
function cmd(title,url){
    var index = layer.open({
    	type: 2,
    	title: title,
    	content: url
    });
    layer.full(index);
}
/*播放器-删除*/
function del(obj,id){
    layer.confirm('删除后不能恢复，确认要删除吗？',function(index){
        $.post('<?=links('player','del')?>',{id: id},function(data) {
           var msg=data['error'];
	   if(msg == "ok"){//成功
		$(obj).parents("tr").remove();
		get_msg('已删除!',2000,1);
	   }else{
	        get_msg(msg);
	   }
        },"json");
    });
}
</script>
</body>
</html>
