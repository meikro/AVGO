<title>管理员管理</title>
<script type="text/javascript" src="<?=Base_Path?>datepicker/wdatepicker.js"></script>
</head>
<body>
  <div class="content-page">
      <div class="content-page-nav">
          <a class="nav-list"><i class="icon index-icon pull-left"></i>首页</a><a class="nav-list"> > 管理员管理 </a><a class="nav-list"> > 日志列表 </a>
      </div>
      <form action="<?=links('sys','log')?>" method="post">
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
            <input type="text" class="key content-page-input" placeholder="管理员名称" value="<?=$user?>" name="user">
        </div>
        <div class="col-md-1">
            <button class="content-page-btn blue-white-btn">搜日志</button>
        </div>
      </div>
      </form>
      <form action="<?=links('sys','log_del',0,'ac=all')?>" method="post" id="myform" name="myform">
      <div class="content-page-table">
          <div class="content-page-table">
              <div class="content-page-table-top">
                  <div class="pull-left">
                    <a class="gop" href="javascript:;" onclick="pl_del()">
                      <i class="icon table-delete-all-icon pull-left"></i>批量删除</a>
                  </div>
                  <span class="gop pull-right">共有数据：<strong><?=$nums?></strong> 条</span>
              </div>
              <table class="table table-striped no-img-table">
                  <thead>
                  <tr>
                  	  <th class="tab-checkbox">
                        <input type="checkbox" name="" value="">
                      </th>
                      <th class="small-hide" style="padding-left:40px">ID</th>
                      <th>账号</th>
                      <th class="small-hide">客户端信息</th>
            			    <th>登陆IP</th>
                      <th class="small-hide">登陆时间</th>
            			    <th class="table-last-group" style="padding-left:80px">操作</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                    if(empty($array)) echo '<tr><td colspan="7" class="text-c">没有记录~</td></tr>';
                    foreach ($array as $row) {
                                      echo '
                    			<tr>
                    				<td class="tab-checkbox">
										<input type="checkbox" value="'.$row->id.'" name="id[]">
									</td>
                    				<td style="padding-left:40px">'.$row->id.'</td>
                    				<td>'.getzd('admin','name',$row->uid).'</td>
                    				<td class="small-hide">'.$row->ua.'</td>
                    				<td>'.$row->ip.'</td>
                    				<td class="small-hide">'.date('Y-m-d H:i:s',$row->logtime).'</td>
                    				<td class="table-last-group" style="padding-left:80px">
                              <a title="删除" href="javascript:;" onclick="del(this,'.$row->id.')" style="text-decoration:none">
                                <i class="icon table-delete-icon"></i>
                              </a>
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
/*删除*/
function del(obj,id){
    layer.confirm('删除后不能恢复，确认要删除吗？',function(index){
        $.post('<?=links('sys','del')?>',{id: id},function(data) {
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
