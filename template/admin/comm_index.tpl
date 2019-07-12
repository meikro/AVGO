<title>文章管理</title>
<script type="text/javascript" src="<?=Base_Path?>datepicker/wdatepicker.js"></script>
</head>
<body>
  <div class="content-page">
      <div class="content-page-nav">
          <a class="nav-list"><i class="icon index-icon pull-left"></i>首页</a><a class="nav-list"> > 文章库管理 </a><a class="nav-list"> > 文章列表 </a>
      </div>
      <form action="<?=links('comm','index')?>" method="post">
      <div class="content-page-select-group row">
            <div class="col-md-2">
            </div>
            <div class="col-md-1">
                <select class="content-page-select" name="cid" >
                  <option value="0">所属圈子</option>
              		<?php foreach($circle as $row):?>
              		<option value="<?=$row->id?>"<?php if($cid==$row->id) echo ' selected';?>><?=$row->name?></option>
              		<?php endforeach;?>
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
              <option value="title"<?php if($ziduan=='title') echo ' selected';?>>文章标题</option>
	            <option value="id"<?php if($ziduan=='id') echo ' selected';?>>文章ID</option>
	            <option value="content"<?php if($ziduan=='content') echo ' selected';?>>文章内容</option>
	            <option value="uid"<?php if($ziduan=='uid') echo ' selected';?>>作者ID</option>
            </select>
        </div>
        <div class="col-md-1">
            <input type="text" class="key content-page-input" placeholder="搜索内容" value="<?=$key?>" name="key">
        </div>
        <div class="col-md-1">
            <button class="content-page-btn blue-white-btn">搜文章</button>
        </div>
      </div>
      </form>
      <form action="<?=links('comm','del',0,'ac=all')?>" method="post" id="myform" name="myform">
      <div class="content-page-table">
          <div class="content-page-table">
              <div class="content-page-table-top">
                  <div class="pull-left">
                      <a class="gop" href="javascript:;" onclick="cmd('添加文章','<?=links('comm','edit')?>')">
                        <i class="icon table-add-all-icon pull-left"></i>添加文章</a>
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
                      <th class="w35">标题</th>
                      <th class="small-hide">UID</th>
                      <th class="small-hide">作者</th>
            			    <th class="small-hide">圈子</th>
            			    <th class="small-hide">收藏</th>
            			    <th class="small-hide">点赞</th>
            			    <th class="small-hide">回复</th>
            			    <th class="small-hide">浏览</th>
            			    <th class="w10 small-hide">发布时间</th>
            			    <th class="table-last-group">操作</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                    if(empty($array)) echo '<tr><td colspan="12" style="text-align:center">没有记录~</td></tr>';
                    foreach ($array as $row) {
                        $zt='<span class="label label-success radius" >'.$row->circle.'</span>';
                        $time = date('Y-m-d H:i:s',$row->addtime);
                        if(date('Y-m-d')==date('Y-m-d',$row->addtime)) $time = '<font color=red>'.$time.'</font>';
                        echo '
                    			<tr>
                            <td class="tab-checkbox"><input type="checkbox" value="'.$row->id.'" name="id[]"></td>
                    				<td class="small-hide">'.$row->id.'</td>
                            <td class="w35">
                              <a href="//'.Web_Url.links('comm','article',$row->id,0,1).'" target="_blank">
                              '.$row->title.'
                            </a>
                            </td>
                    				<td class="small-hide">'.$row->uid.'</td>
                    				<td class="small-hide">'.$row->nichen.'</td>
                    				<td class="small-hide">'.$zt.'</td>
                    				<td class="small-hide">'.$row->collnum.'</td>
                    				<td class="small-hide">'.$row->dznum.'</td>
                    				<td class="small-hide">'.$row->msg.'</td>
                    				<td class="small-hide">'.$row->pvnum.'</td>
                    				<td class="w10 small-hide">'.$time.'</td>
                    				<td class="table-last-group">
                    				    <a style="text-decoration:none" onClick="cmd(\'文章编辑\',\''.links('comm','edit',0,'id='.$row->id).'\')" href="javascript:;" title="编辑">
                                  <i class="icon table-update-icon"></i></a>
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
  </div>
<script type="text/javascript">
/*修改、查看*/
function cmd(title,url){
    //layer_show(title,url,1200,650);
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
        $.post('<?=links('comm','del')?>',{id: id},function(data) {
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
