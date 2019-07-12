<title>模版管理</title>
</head>
<body>
<div class="content-page">
	<div class="content-page-nav">
		<a class="nav-list"><i class="icon index-icon pull-left"></i>首页</a><a class="nav-list"> > 模版管理 </a><a class="nav-list"> > 模版列表 </a>
	</div>
	<div class="content-page-table">
		<?php if($one !==1){ ?>
		<div class="content-page-table">
			<div class="content-page-table-top">
				<span style="position:relative;left:-30px;">模版管理</span>
			</div>
		<?php } ?>
		<?php if($one==1){ ?>
		<div class="content-page-table-top">
			<div class="pull-left">
			<a class="gop" href="javascript:;" onclick="edit('新增模版','<?=links('tpl','add',0,'path='.$path)?>');" style="position:relative;left:-30px;"><i class="icon table-add-all-icon pull-left"></i>新增模版</a>
			</div>
			<a class="gop pull-right" href="javascript:;" onclick="history.go(-1)">返回上一页</a>
		</div>
		<?php } ?>
		<table class="table table-striped no-img-table">
			<thead>
				<tr>
					<th>文件名</th>
					<th class="small-hide">类型</th>
					<th class="small-hide">大小</th>
					<?php if($one !==1){ ?>
						<th>默认</th>
					<?php } ?>
					<th class="small-hide">修改时间</th>
					<th class="table-last-group">操作</th>
				</tr>
			</thead>
			<tbody>
			<?php
			foreach ($dir as $row) {
				$mr = $row['mr'] == 1 ? '<div style="position: absolute;top:-25px;right:-50px;"><span class="label label-danger radius">默认</span></div>' : '';
				if(!empty($row['mrlink'])) $row['mrlink'] = '<a title="设为默认模板" href="javascript:;" onclick="cmd(\''.$row['mrlink'].'\');" class="label label-danger radius">默认</a>';
				$mrlink = '';
				if($one !==1){
					$mrlink = '<td>'.$row['mrlink'].'</td>';
				}
				echo '
					<tr>
						<td><a style="position:relative;" title="打开" href="'.$row['link'].'"><b>'.$row['name'].$mr.'</b></a></td>
						<td class="small-hide">目录</td>
						<td class="small-hide">'.$row['size'].'</td>
						'.$mrlink.'
						<td class="small-hide">'.$row['date'].'</td>
						<td class="table-last-group">
							<a title="打开" href="'.$row['link'].'" class="cmd">
								<i class="icon table-update-icon"></i>
							</a>
							<a title="删除" href="javascript:;" onclick="cmd(\''.$row['dellink'].'\');" class="cmd">
								<i class="icon table-delete-icon"></i>
							</a>
						</td>
					</tr>';
			}
			foreach ($list as $row) {
				$lool = $row['type']==1 ? '查看':'修改';
				if($row['type']==1){
					$link = 'href="'.$row['link'].'" target="_blank"';
				}else{
					$link = 'onClick="edit(\'模版编辑\',\''.$row['link'].'\')" href="javascript:;"';
				}
				echo '
					<tr>
						<td><a title="'.$lool.'" '.$link.'>'.$row['name'].'</a></td>
						<td class="small-hide">'.$row['title'].'</td>
						<td class="small-hide">'.$row['size'].'</td>
						<td class="small-hide">'.$row['date'].'</td>
						<td class="table-last-group">
							<a title="'.$lool.'" '.$link.' class="cmd">
							<i class="icon table-update-icon"></i>
							</a>
							<a title="备份" href="'.$row['blink'].'" class="cmd">
							<i class="icon table-bf-icon"></i>
							</a>
							<a title="删除" href="javascript:;" onclick="cmd(\''.$row['dellink'].'\');" class="cmd">
							<i class="icon table-delete-icon"></i>
							</a>
						</td>
					</tr>';
			}
			?>
			</tbody>
		</table>
		</div>
	</div>
</div>
<script>
function cmd(links){
	layer.confirm('确认要操作吗？',function(){
		window.location.href=links;
	});
}
//修改
function edit(title,url){
	var index = layer.open({
		type: 2,
		title: title,
		content: url
	});
	layer.full(index);
}
</script>
</body>
</html>
