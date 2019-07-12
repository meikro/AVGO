<title>点卡管理</title>
<script type="text/javascript" src="<?=Base_Path?>datepicker/wdatepicker.js"></script>
</head>
<body>
  <div class="content-page">
      <div class="content-page-nav">
          <a class="nav-list"><i class="icon index-icon pull-left">
          </i>首页</a><a class="nav-list"> > 会员管理 </a><a class="nav-list"> > 点卡列表 </a>
      </div>
      <form action="<?=links('card','index')?>" method="post">
      <div class="content-page-select-group row">
            <div class="col-md-2">
            </div>
            <div class="col-md-1">
                <select class="content-page-select" name="vip" >
                  <option value="0">点卡级别</option>
              		<option value="1"<?php if($cid==1) echo ' selected';?>>金币卡</option>
              		<option value="2"<?php if($cid==2) echo ' selected';?>>VIP卡</option>
                </select>
            </div>
            <div class="col-md-1" style="text-align:center">
              使用日期：
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
              <option value="kh"<?php if($ziduan=='kh') echo ' selected';?>>卡号</option>
          		<option value="user"<?php if($ziduan=='user') echo ' selected';?>>使用会员</option>
          		<option value="cion"<?php if($ziduan=='cion') echo ' selected';?>>金币</option>
          		<option value="day"<?php if($ziduan=='day') echo ' selected';?>>天数</option>
            </select>
        </div>
        <div class="col-md-1">
            <input type="text" class="key content-page-input" placeholder="搜索内容" value="<?=$key?>" name="key">
        </div>
        <div class="col-md-1">
            <button class="content-page-btn blue-white-btn">搜点卡</button>
        </div>
      </div>
      </form>
      <form action="<?=links('card','del',0,'ac=all')?>" method="post" id="myform" name="myform">
      <div class="content-page-table">
          <div class="content-page-table">
              <div class="content-page-table-top">
                  <div class="pull-left">
                      <a class="gop" href="javascript:;" onclick="cmd('添加点卡','<?=links('card','edit')?>')">
                        <i class="icon table-add-all-icon pull-left"></i>添加点卡</a>
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
                      <th class="w20">卡号</th>
                      <th class="w20 small-hide">卡密</th>
                      <th>类别</th>
                      <th class="small-hide">金币</th>
                      <th class="small-hide">VIP时间</th>
                      <th class="small-hide">所属会员</th>
                      <th class="small-hide">使用时间</th>
            			    <th class="table-last-group">操作</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                    if(empty($array)) echo '<tr><td colspan="10" style="text-align:center">没有记录~</td></tr>';
                    foreach ($array as $row) {
                        $cion=$day='--';
                        if($row->cid==1){
                            $lb='<span class="label label-danger radius">VIP卡</span>';
                    	$day = $row->day.'天';
                        }else{
                            $lb='<span class="label label-success radius">金币卡</span>';
                    	$cion = $row->cion;
                        }
                        $user = '--';
                        if($row->uid>0) $user = getzd('user','name',$row->uid);
                        $time = ($row->totime==0) ? '未使用' : date('Y-m-d H:i:s',$row->totime);

                                      echo '
                    			<tr>
                            <td class="tab-checkbox"><input type="checkbox" value="'.$row->id.'" name="id[]"></td>
                    				<td class="small-hide">'.$row->id.'</td>
                    				<td class="w20">'.$row->kh.'</td>
                    				<td class="w20 small-hide">'.$row->pass.'</td>
                    				<td>'.$lb.'</td>
                    				<td class="small-hide">'.$cion.'</td>
                    				<td class="small-hide">'.$day.'</td>
                    				<td class="small-hide">'.$user.'</td>
                    				<td class="small-hide">'.$time.'</td>
                    				<td class="table-last-group">
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
    </form>
  </div>
<script type="text/javascript">
/*修改、查看*/
function cmd(title,url){
    layer_show(title,url,1000,350);
}
/*删除*/
function del(obj,id){
    layer.confirm('删除后不能恢复，确认要删除吗？',function(index){
        $.post('<?=links('card','del')?>',{id: id},function(data) {
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
