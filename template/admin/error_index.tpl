<title>报错管理</title>
</head>
<script type="text/javascript" src="<?=Base_Path?>datepicker/wdatepicker.js"></script>
</head>
<body>
  <div class="content-page">
      <div class="content-page-nav">
          <a class="nav-list"><i class="icon index-icon pull-left"></i>首页</a><a class="nav-list"> > 视频库管理 </a><a class="nav-list"> > 报错列表 </a>
      </div>
      <form action="<?=links('error','index')?>" method="post">
      <div class="content-page-select-group row">
            <div class="col-md-3" style="text-align: right;">
              日期：
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
              <option value="name"<?php if($ziduan=='name') echo ' selected';?>>视频名称</option>
              <option value="did"<?php if($ziduan=='did') echo ' selected';?>>视频ID</option>
            </select>
        </div>
        <div class="col-md-1">
            <input type="text" class="key content-page-input" placeholder="搜索内容" value="<?=$key?>" name="key">
        </div>
        <div class="col-md-1">
            <button class="content-page-btn blue-white-btn">搜报错</button>
        </div>
      </div>
      </form>
      <form action="<?=links('error','del',0,'ac=all')?>" method="post" id="myform" name="myform">
      <div class="content-page-table">
          <div class="content-page-table">
              <div class="content-page-table-top">
                  <div class="pull-left">
                    <a class="gop" href="javascript:;" onclick="pl_del()">
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
                      <th class="tab-img small-hide">图片</th>
                      <th class="w30">名称</th>
                      <th class="w30 small-hide">地址</th>
                      <th class="small-hide">时间</th>
                      <th class="table-last-group">操作</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                    if(empty($array)) echo '<tr><td colspan="7" style="text-align:center">没有记录~</td></tr>';
                    foreach ($array as $row) {
                        $pic = getzd('vod','pic',$row->did);
                        if(!empty($pic)){
                            $pic='<img src="'.getpic($pic).'" style="height:40px;">';
                        }else{
                            $pic='---';
                        }
                        $time = date('Y-m-d',$row->addtime);
                        if(date('Y-m-d')==$time) $time = '<font color=red>'.$time.'</font>';
                                      echo '
                    			<tr>
                            <td class="tab-checkbox"><input type="checkbox" value="'.$row->id.'" name="id[]"></td>
                    				<td class="small-hide">'.$row->id.'</td>
                    				<td class="tab-img small-hide">'.$pic.'</td>
                    				<td class="w30"><a href="http://'.Web_Url.links('play','index',$row->did.'/'.$row->zu.'/'.$row->ji,'',1).'" target="_blank"><u>'.$row->name.'</u></a></td>
                    				<td class="w30 small-hide"><a href="http://'.Web_Url.links('play','index',$row->did.'/'.$row->zu.'/'.$row->ji,'',1).'" target="_blank">http://'.Web_Url.links('play','index',$row->did.'/'.$row->zu.'/'.$row->ji,'',1).'</a></td>
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
  </div>
<script type="text/javascript">
/*删除*/
function del(obj,id){
    layer.confirm('删除后不能恢复，确认要删除吗？',function(index){
        $.post('<?=links('error','del')?>',{id: id},function(data) {
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
