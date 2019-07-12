<title>视频分类管理</title>
</head>
<body>
<div class="content-page">
	<div class="content-page-nav">
		<a class="nav-list"><i class="icon index-icon pull-left"></i>首页</a><a class="nav-list"> > 视频库管理 </a><a class="nav-list"> > 视频分类管理 </a>
	</div>
	<form action="<?=links('vod','lists_del')?>?ac=all" method="post" class="form form-horizontal" id="myform" name="myform">
	<div class="content-page-table">
		<div class="content-page-table">
			<div class="content-page-table-top">
				<div class="pull-left">
					<a class="gop" href="javascript:;" onclick="pl_cmd(1)"><i class="icon table-paixu-all-icon pull-left"></i>批量排序</a>
					<a class="gop" href="javascript:;" onclick="edit('添加分类','<?=links('vod','lists_edit')?>')"><i class="icon table-add-all-icon pull-left"></i>添加分类</a>
					<label class="gop big-hide">共有数据：<a class="text-red"><?=$nums?></a>条</label>
				</div>
				<a class="gop pull-right" href="javascript:;" onclick="pl_cmd(0)"><i class="icon table-delete-all-icon pull-left"></i>批量删除</a>
			</div>
			<table class="table table-striped no-img-table">
				<thead>
				<tr>
					<th class="tab-checkbox w10"><input type="checkbox" name="" value=""></th>
					<th class="w10 small-hide">ID</th>
					<th class="table_paixu w20">排序</th>
					<th>分类名称</th>
					<th class="table-last-group">操作</th>
				</tr>
				</thead>
				<tbody>
					<?php
					if(empty($array)) echo '<tr><td colspan="5" class="text-c">没有记录~</td></tr>';
					foreach ($array as $row) {
					    echo '<tr>
					        	<td class="tab-checkbox w10"><input type="checkbox" value="'.$row->id.'" name="id[]"></td>
								<td class="w10 small-hide">'.$row->id.'</td>
								<td class="table_paixu w20"><input type="text" value="'.$row->xid.'" name="xid[]"></td>
								<td>├&nbsp;'.$row->name.'</td>
								<td class="table-last-group">
									<a title="编辑" href="javascript:;" onclick="edit(\'分类编辑\',\''.links('vod','lists_edit',0,'id='.$row->id).'\','.$row->id.')" style="text-decoration:none"><i class="icon table-update-icon"></i></a>
									<a title="删除" href="javascript:;" onclick="del(this,'.$row->id.')" style="text-decoration:none"><i class="icon table-delete-icon"></i></a>
								</td>
							</tr>';
			              	$array2 = $this->csdb->get_select('class','*',array('fid'=>$row->id),'xid ASC');
			              	foreach ($array2 as $row2) {
			                	echo '
								<tr>
					      			<td class="tab-checkbox"><input type="checkbox" value="'.$row2->id.'" name="id[]"></td>
									<td class="small-hide">'.$row2->id.'</td>
									<td class="table_paixu"><input type="text" value="'.$row2->xid.'" name="xid[]"></td>
									<td>&nbsp;&nbsp;&nbsp;&nbsp;|——&nbsp;'.$row2->name.'</td>
									<td class="table-last-group">
										<a title="编辑" href="javascript:;" onclick="edit(\'分类编辑\',\''.links('vod','lists_edit',0,'id='.$row2->id).'\')" style="text-decoration:none">
											<i class="icon table-update-icon"></i>
										</a>
										<a title="删除" href="javascript:;" onclick="del(this,'.$row2->id.')" style="text-decoration:none">
											<i class="icon table-delete-icon"></i>
										</a>
									</td>
								</tr>';
							}
					}
					?>
				</tbody>
			</table>
			<div class="content-page-table-fy">
				<?=$pages?>
			</div>
		</div>
	</div>
	</form>
</div>
<script type="text/javascript" src="<?=Base_Path?>layer/layer.js"></script>
<script type="text/javascript" src="<?=Base_Path?>datepicker/wdatepicker.js"></script>
<script type="text/javascript" src="<?=Base_Path?>admin/js/H-ui.js"></script>
<script type="text/javascript" src="<?=Base_Path?>admin/js/H-ui.admin.js"></script>
<script type="text/javascript">
/*新增-编辑*/
function edit(title,url){
    var index = layer.open({
    	type: 2,
    	title: title,
    	content: url
    });
    layer.full(index);
}
/*删除*/
function del(obj,id){
    layer.confirm('删除后不能恢复，确认要删除吗？',function(index){
        $.post('<?=links('vod','lists_del')?>',{id: id},function(data) {
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
function pl_cmd(sid){
  var xuan=0;
  $("td input:checkbox").each(function(){
      if($(this).prop("checked")){
          xuan++;
      }
  });
  if(xuan>0){
      if(sid==0){
          layer.confirm('删除后不能恢复，确认要删除吗？',function(){
	      $('#myform').submit();
          });
      }else{
          $('#myform').attr('action','<?=links('vod','lists_plpx')?>');
	  $('#myform').submit();
      }
  }else{
      get_msg('请选择要操作的数据~!');
  }
}
</script>
</body>
</html>
