<title>留言管理</title>
<script type="text/javascript" src="<?=Base_Path?>datepicker/wdatepicker.js"></script>
</head>
<body>
  <div class="content-page">
      <div class="content-page-nav">
          <a class="nav-list"><i class="icon index-icon pull-left">
          </i>首页</a><a class="nav-list"> > 运营管理 </a><a class="nav-list"> > 留言列表 </a>
      </div>
      <form action="<?=links('gbook','index')?>" method="post">
      <div class="content-page-select-group row">
            <div class="col-md-2">
            </div>
            <div class="col-md-1">
                <select class="content-page-select" name="yid" >
                  <option value="0">留言状态</option>
              		<option value="1"<?php if($yid==1) echo ' selected';?>>已审核</option>
              		<option value="2"<?php if($yid==2) echo ' selected';?>>未审核</option>
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
              <option value="name"<?php if($ziduan=='name') echo ' selected';?>>留言名字</option>
          		<option value="content"<?php if($ziduan=='content') echo ' selected';?>>留言内容</option>
          		<option value="hfcontent"<?php if($ziduan=='hfcontent') echo ' selected';?>>回复内容</option>
            </select>
        </div>
        <div class="col-md-1">
            <input type="text" class="key content-page-input" placeholder="搜索内容" value="<?=$key?>" name="key">
        </div>
        <div class="col-md-1">
            <button class="content-page-btn blue-white-btn">搜留言</button>
        </div>
      </div>
      </form>
      <form action="<?=links('gbook','del',0,'ac=all')?>" method="post" id="myform" name="myform">
      <div class="content-page-table">
          <div class="content-page-table">
              <div class="content-page-table-top">
                  <div class="pull-left">
                      <a class="gop" href="javascript:;" onclick="pl_cmd(1);">
                        <i class="icon table-add-all-icon pull-left"></i>批量审核</a>
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
                      <th class="w50">内容</th>
                      <th class="small-hide">留言者</th>
                      <th class="small-hide">状态</th>
                      <th class="w10 small-hide">时间</th>
            			    <th class="table-last-group">操作</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                    if(empty($array)) echo '<tr><td colspan="7" style="text-align:center">没有记录~</td></tr>';
                    foreach ($array as $row) {
                        if($row->yid==1){
                            $zt='<span class="label label-danger radius">待审核</span>';
                        }else{
                            if(!empty($row->hfcontent)){
                                $zt='<span class="label label-warning radius">已回复</span>';
                    	}else{
                                $zt='<span class="label label-success radius">已审核</span>';
                    	}
                        }
                        $time = date('Y-m-d H:i:s',$row->addtime);
                        if(date('Y-m-d')==date('Y-m-d',$row->addtime)) $time = '<font color=red>'.$time.'</font>';

                                      echo '
                    			<tr>
                            <td class="tab-checkbox"><input type="checkbox" value="'.$row->id.'" name="id[]"></td>
                    				<td class="small-hide">'.$row->id.'</td>
                            <td class="w50">'.$row->content.'</td>
                            <td class="small-hide">'.$row->name.'</td>
                    				<td class="small-hide">'.$zt.'</td>
                    				<td class="w10 small-hide">'.$time.'</td>
                    				<td class="table-last-group">
                                <a style="text-decoration:none" onClick="cmd(\'留言回复\',\''.links('gbook','edit',0,'id='.$row->id).'\')" href="javascript:;" title="编辑回复">回复</a>
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
    layer_show(title,url,700,450);
}
/*删除*/
function del(obj,id){
    layer.confirm('删除后不能恢复，确认要删除吗？',function(index){
        $.post('<?=links('gbook','del')?>',{id: id},function(data) {
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
function pl_cmd(id){
  var xuan=0;
  $("td input:checkbox").each(function(){
      if($(this).prop("checked")){
          xuan++;
      }
  });
  if(xuan>0){
      layer.confirm('确认要操作吗？',function(){
          if(id==1){
              $('#myform').attr('action','<?=links('gbook','init')?>');
	  }
	  $('#myform').submit();
      });
  }else{
      get_msg('请选择要操作的数据~!');
  }
}
</script>
</body>
</html>
