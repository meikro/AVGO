<title>分销记录</title>
<script type="text/javascript" src="<?=Base_Path?>datepicker/wdatepicker.js"></script>
</head>
<body>
  <div class="content-page">
      <div class="content-page-nav">
          <a class="nav-list"><i class="icon index-icon pull-left">
          </i>首页</a><a class="nav-list"> > 会员管理 </a><a class="nav-list"> > 分销记录 </a>
      </div>
      <form action="<?=links('fenxiao','index')?>" method="post">
      <div class="content-page-select-group row">
            <div class="col-md-2">
            </div>
            <div class="col-md-1" style="text-align:center">
              分成日期：
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
				<option value="usera"<?php if($ziduan=='usera') echo ' selected';?>>获得者名称</option>
				<option value="uida"<?php if($ziduan=='uida') echo ' selected';?>>获得者ID</option>
				<option value="userb"<?php if($ziduan=='userb') echo ' selected';?>>充值者名称</option>
				<option value="uidb"<?php if($ziduan=='uidb') echo ' selected';?>>充值者ID</option>
            </select>
        </div>
        <div class="col-md-1">
            <input type="text" class="key content-page-input" placeholder="搜索内容" value="<?=$key?>" name="key">
        </div>
        <div class="col-md-1">
            <button class="content-page-btn blue-white-btn">搜记录</button>
        </div>
      </div>
      </form>
      <form action="<?=links('fenxiao','del',0,'ac=all')?>" method="post" id="myform" name="myform">
      <div class="content-page-table">
          <div class="content-page-table">
              <div class="content-page-table-top">
                  <div class="pull-left">
                  	<a class="gop pull-right" href="javascript:;" onclick="pl_del()"><i class="icon table-delete-all-icon pull-left"></i>批量删除</a>
                  </div>
              </div>
              <table class="table table-striped no-img-table">
                  <thead>
                  <tr>
                      	<th class="tab-checkbox"><input type="checkbox" name="" value=""></th>
                      	<th class="small-hide">ID</th>
                      	<th>获得者</th>
                      	<th class="small-hide">消费者</th>
                      	<th class="small-hide">消费金额</th>
                      	<th>分成金额</th>
                      	<th class="small-hide">分成时间</th>
            			<th class="table-last-group">操作</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                    if(empty($array)) echo '<tr><td colspan="8" style="text-align:center">没有记录~</td></tr>';
                    foreach ($array as $row) {
					    $usera = '--';
					    if($row->uida>0) $usera = getzd('user','name',$row->uida);
					    $userb = '--';
					    if($row->uidb>0) $userb = getzd('user','name',$row->uidb);
					    $time = date('Y-m-d H:i:s',$row->addtime);
					    if(date('Y-m-d')==date('Y-m-d',$row->addtime)) $time = '<font color=red>'.$time.'</font>';
                        echo '
                    		<tr>
                            	<td class="tab-checkbox"><input type="checkbox" value="'.$row->id.'" name="id[]"></td>
                				<td class="small-hide">'.$row->id.'</td>
                				<td>'.$usera.'</td>
                				<td class="small-hide">'.$userb.'</td>
								<td class="small-hide">'.$row->rmb.'元</td>
								<td>'.$row->fcrmb.'元</td>
								<td class="small-hide">'.$time.'</td>
                				<td class="table-last-group">
                				    <a style="text-decoration:none" class="ml-5" onClick="del(this,'.$row->id.')" href="javascript:;" title="删除">
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
    </form>
  </div>
<script type="text/javascript">
/*删除*/
function del(obj,id){
    layer.confirm('删除后不能恢复，确认要删除吗？',function(index){
        $.post('<?=links('fenxiao','del')?>',{id: id},function(data) {
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
