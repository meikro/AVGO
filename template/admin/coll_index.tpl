<title>收藏管理</title>
<script type="text/javascript" src="<?=Base_Path?>datepicker/wdatepicker.js"></script>
</head>
<body>
  <div class="content-page">
      <div class="content-page-nav">
          <a class="nav-list"><i class="icon index-icon pull-left"></i>首页</a><a class="nav-list"> > 文章库管理 </a><a class="nav-list"> > 收藏列表 </a>
      </div>
      <form action="<?=links('coll','index')?>" method="post">
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
              <option value="tid"<?php if($ziduan=='tid') echo ' selected';?>>文章ID</option>
        			<option value="title"<?php if($ziduan=='title') echo ' selected';?>>文章标题</option>
        			<option value="id"<?php if($ziduan=='id') echo ' selected';?>>收藏ID</option>
        			<option value="uid"<?php if($ziduan=='uid') echo ' selected';?>>用户ID</option>
            </select>
        </div>
        <div class="col-md-1">
            <input type="text" class="key content-page-input" placeholder="搜索内容" value="<?=$key?>" name="key">
        </div>
        <div class="col-md-1">
            <button class="content-page-btn blue-white-btn">搜收藏</button>
        </div>
      </div>
      </form>
      <form action="<?=links('coll','del',0,'ac=all')?>" method="post" id="myform" name="myform">
      <div class="content-page-table">
          <div class="content-page-table">
              <div class="content-page-table-top">
                  <div class="pull-left">
                      <a class="gop pull-right" href="javascript:;" onclick="pl_del()">
                    <i class="icon table-delete-all-icon pull-left"></i>批量删除</a>
                  </div>
              </div>
              <table class="table table-striped img-table">
                  <thead>
                  <tr>
                      <th class="tab-checkbox">
                        <input type="checkbox" name="" value="">
                      </th>
                      <th class="small-hide">ID</th>
                      <th class="w35">文章标题</th>
                      <th>用户ID</th>
                      <th class="small-hide">文章ID</th>
            			    <th class="small-hide">用户昵称</th>
                      <th class="w10 small-hide">收藏时间</th>
            			    <th class="table-last-group">操作</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                    if(empty($array)) echo '<tr><td colspan="10" style="text-align:center">没有记录~</td></tr>';
                    foreach ($array as $row) {
                        echo '
                    			<tr>
                            <td class="tab-checkbox"><input type="checkbox" value="'.$row->id.'" name="id[]"></td>
                    				<td class="small-hide">'.$row->id.'</td>
                            <td class="w35">'.$row->title.'</td>
                    				<td>'.$row->uid.'</td>
                    				<td class="small-hide">'.$row->tid.'</td>
                    				<td class="small-hide">'.$row->nichen.'</td>
                    				<td class="w10 small-hide">'.date('Y-m-d H:i:s',$row->addtime).'</td>
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
  </div>
<script type="text/javascript">
/*修改、查看*/
function cmd(title,url){
    layer_show(title,url,700,450);
}
/*删除*/
function del(obj,id){
    layer.confirm('删除后不能恢复，确认要删除吗？',function(index){
        $.post('<?=links('coll','del')?>',{id: id},function(data) {
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
