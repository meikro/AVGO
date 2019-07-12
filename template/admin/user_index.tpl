<title>会员管理</title>
<script type="text/javascript" src="<?=Base_Path?>datepicker/wdatepicker.js"></script>
</head>
<body>
  <div class="content-page">
      <div class="content-page-nav">
          <a class="nav-list"><i class="icon index-icon pull-left"></i>首页</a><a class="nav-list"> > 会员管理 </a><a class="nav-list"> > 会员列表 </a>
      </div>
      <form action="<?=links('user','index')?>" method="post">
      <div class="content-page-select-group row">
            <div class="col-md-2">
            </div>
            <div class="col-md-1">
                <select class="content-page-select" name="vip" >
                  <option value="0">会员级别</option>
              		<option value="1"<?php if($vip==1) echo ' selected';?>>普通会员</option>
              		<option value="2"<?php if($vip==2) echo ' selected';?>>VIP会员</option>
                </select>
            </div>
            <div class="col-md-1" style="text-align:center">
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
              <option value="name"<?php if($ziduan=='name') echo ' selected';?>>会员名称</option>
          		<option value="id"<?php if($ziduan=='id') echo ' selected';?>>会员ID</option>
          		<option value="email"<?php if($ziduan=='email') echo ' selected';?>>会员邮箱</option>
          		<option value="tel"<?php if($ziduan=='tel') echo ' selected';?>>会员手机</option>
            </select>
        </div>
        <div class="col-md-1">
            <input type="text" class="key content-page-input" placeholder="搜索内容" value="<?=$key?>" name="key">
        </div>
        <div class="col-md-1">
            <button class="content-page-btn blue-white-btn">搜会员</button>
        </div>
      </div>
      </form>
      <form action="<?=links('user','del',0,'ac=all')?>" method="post" id="myform" name="myform">
      <div class="content-page-table">
          <div class="content-page-table">
              <div class="content-page-table-top">
                  <div class="pull-left">
                      <a class="gop" href="javascript:;" onclick="cmd('添加会员','<?=links('user','edit')?>')">
                        <i class="icon table-add-all-icon pull-left"></i>添加会员</a>
                  </div>
                  <a class="gop pull-right" href="javascript:;" onclick="pl_del()">
                    <i class="icon table-delete-all-icon pull-left"></i>批量删除</a>
              </div>
              <table class="table table-striped img-table">
                  <thead>
                  <tr>
                      <th class="tab-checkbox">
                        <input type="checkbox" name="" value="">
                      </th>
                      <th class="w5 small-hide">ID</th>
                      <th class="tab-img small-hide">头像</th>
                      <th class="w15">账号</th>
                      <th class="w15 small-hide">邮箱</th>
                      <th class="small-hide" class="small-hide">金额</th>
                      <th>金币</th>
                      <th>级别</th>
                      <th class="small-hide">登陆次数</th>
                      <th class="small-hide">登陆IP</th>
                      <th class="small-hide">加入时间</th>
            			    <th class="table-last-group">操作</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                    if(empty($array)) echo '<tr><td colspan="12" style="text-align:center">没有记录~</td></tr>';
                    foreach ($array as $row) {
                        if($row->vip==1){
                            $zt='<span class="label label-danger radius" title="到期时间：'.date('Y-m-d',$row->viptime).'">VIP</span>';
                        }else{
                            $zt='<span class="label label-success radius">普通</span>';
                        }
                        $time = date('Y-m-d',$row->regtime);
                        if(date('Y-m-d')==$time) $time = '<font color=red>'.$time.'</font>';

                          echo '
                    			<tr>
                            <td class="tab-checkbox"><input type="checkbox" value="'.$row->id.'" name="id[]"></td>
                    				<td class="w5 small-hide">'.$row->id.'</td>
                    				<td class="tab-img small-hide"><img src="'.getpic($row->pic).'"></td>
                    				<td class="w15">'.$row->name.'</td>
                    				<td class="w15 small-hide">'.$row->email.'</td>
                    				<td class="small-hide">'.$row->rmb.'</td>
                    				<td>'.$row->cion.'</td>
                    				<td>'.$zt.'</td>
                    				<td class="small-hide">'.$row->lognum.'</td>
                    				<td class="small-hide">'.$row->logip.'</td>
                    				<td class="small-hide">'.$time.'</td>
                    				<td class="table-last-group">
                    				    <a style="text-decoration:none" onClick="cmd(\'会员编辑\',\''.links('user','edit',0,'id='.$row->id).'\')" href="javascript:;" title="编辑">
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
    </form>
  </div>
<script type="text/javascript">
/*修改、查看*/
function cmd(title,url){
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
        $.post('<?=links('user','del')?>',{id: id},function(data) {
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
