<title>圈子管理</title>
<script type="text/javascript" src="<?=Base_Path?>datepicker/wdatepicker.js"></script>
</head>
<body>
  <div class="content-page">
      <div class="content-page-nav">
          <a class="nav-list"><i class="icon index-icon pull-left"></i>首页</a><a class="nav-list"> > 文章库管理 </a><a class="nav-list"> > 圈子列表 </a>
      </div>
      <form action="<?=links('circle','index')?>" method="post">
      <div class="content-page-select-group row">
            <div class="col-md-3" style="text-align:right">
              日期范围：
            </div>
            <div class="col-md-3">
                <div class="rili-input col-md-5">
                    <input type="text" class="time" name="kstime" value="<?=$kstime?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})" >
                    <i class="icon rili-icon"></i>
                </div>
                <div class="col-md-2" style="text-align: center">TO</div>
                <div class="rili-input col-md-5">
                    <input type="text" class="time" name="jstime" value="<?=$jstime?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})">
                    <i class="icon rili-icon"></i>
                </div>
            </div>
        <div class="col-md-1">
            <select class="content-page-select" name="ziduan" >
                <option value="name"<?php if($ziduan=='name') echo ' selected';?>>圈子名称</option>
		            <option value="id"<?php if($ziduan=='id') echo ' selected';?>>圈子ID</option>
            </select>
        </div>
        <div class="col-md-1">
            <input type="text" class="key content-page-input" placeholder="搜索内容" value="<?=$key?>" name="key">
        </div>
        <div class="col-md-1">
            <button class="content-page-btn blue-white-btn">搜圈子</button>
        </div>
      </div>
      </form>
      <form action="<?=links('circle','del',0,'ac=all')?>" method="post" id="myform" name="myform">
      <div class="content-page-table">
          <div class="content-page-table">
              <div class="content-page-table-top">
                  <div class="pull-left">
                      <a class="gop" href="javascript:;" onclick="cmd('添加圈子','<?=links('circle','edit')?>')">
                        <i class="icon table-add-all-icon pull-left"></i>添加圈子</a>
                  </div>
                  <a class="gop pull-right" href="javascript:;" onclick="pl_del()">
                    <i class="icon table-delete-all-icon pull-left"></i>批量删除</a>
              </div>
              <table class="table table-striped no-img-table">
                  <thead>
                  <tr>
                      <th class="tab-checkbox">
                        <input type="checkbox" name="" value="">
                      </th>
                      <th class="small-hide">ID</th>
                      <th>排序</th>
                      <th>名称</th>
            			    <th>文章数量</th>
            			    <th class="table-last-group">操作</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  if(empty($array)) echo '<tr><td colspan="6" style="text-align:center" >没有记录~</td></tr>';
                  foreach ($array as $row) {
                      echo '
                  			<tr>
                          <td class="tab-checkbox"><input type="checkbox" value="'.$row->id.'" name="id[]"></td>
                  				<td class="small-hide">'.$row->id.'</td>
                  				<td>'.$row->xid.'</td>
                  				<td>'.$row->name.'</td>
                  				<td>'.$row->commnum.'</td>
                  				<td class="table-last-group">
                  				    <a style="text-decoration:none" onClick="cmd(\'圈子编辑\',\''.links('circle','edit',0,'id='.$row->id).'\')" href="javascript:;" title="编辑">
                                <i class="icon table-update-icon"></i></a>
                  				    <a style="text-decoration:none" onClick="del(this,'.$row->id.')" href="javascript:;" title="删除">
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
<script type="text/javascript">
/*修改、查看*/
function cmd(title,url){
    layer_show(title,url,700,450);
}
/*删除*/
function del(obj,id){
    layer.confirm('删除后不能恢复，确认要删除吗？',function(index){
        $.post('<?=links('circle','del')?>',{id: id},function(data) {
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
function pl_del(obj,id){
  var xuan=0;
  $("td input:checkbox").each(function(){
      if($(this).prop("checked")){
          xuan++;
      }
  });
  if(xuan>0){
      layer.confirm('确认要删除吗？',function(){
	  $('#myform').submit();
      });
  }else{
      get_msg('请选择要删除的数据~!');
  }
}
</script>
</body>
</html>
