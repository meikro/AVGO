<title>视频管理</title>
<script type="text/javascript" src="<?=Base_Path?>datepicker/wdatepicker.js"></script>
</head>
<body>
  <div class="content-page">
      <div class="content-page-nav">
          <a class="nav-list"><i class="icon index-icon pull-left">
          </i>首页</a><a class="nav-list"> > 会员管理 </a><a class="nav-list"> > 收藏列表 </a>
      </div>
      <form action="<?=links('fav','index')?>" method="post">
      <div class="content-page-select-group row">
            <div class="col-md-2">
            </div>
            <div class="col-md-1">
                <select class="content-page-select" name="cid" >
                  <option value="0">全部分类</option>
                  <?php
                  foreach ($lists as $row2) {
                  	$cls = $row2->id == $cid ? ' selected="selected"' : '';
                  	echo '<option value="'.$row2->id.'"'.$cls.'>├&nbsp;'.$row2->name.'</option>';
                  	$array2 = $this->csdb->get_select('class','id,name',array('fid'=>$row2->id),'xid ASC');
                          foreach ($array2 as $row3) {
                  	    $cls2 = $row3->id == $cid ? ' selected="selected"' : '';
                  	    echo '<option value="'.$row3->id.'"'.$cls2.'>&nbsp;&nbsp;├&nbsp;'.$row3->name.'</option>';
                  	}
                  }
                  ?>
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
              <option value="name"<?php if($ziduan=='name') echo ' selected';?>>视频名称</option>
          		<option value="id"<?php if($ziduan=='id') echo ' selected';?>>视频ID</option>
          		<option value="user"<?php if($ziduan=='user') echo ' selected';?>>会员名称</option>
          		<option value="uid"<?php if($ziduan=='uid') echo ' selected';?>>会员ID</option>
            </select>
        </div>
        <div class="col-md-1">
            <input type="text" class="key content-page-input" placeholder="搜索内容" value="<?=$key?>" name="key">
        </div>
        <div class="col-md-1">
            <button class="content-page-btn blue-white-btn">搜内容</button>
        </div>
      </div>
      </form>
      <form action="<?=links('fav','del',0,'ac=all')?>" method="post" id="myform" name="myform">
      <div class="content-page-table">
          <div class="content-page-table">
              <div class="content-page-table-top">
                  <div class="pull-left">
                    <a class="gop" href="javascript:;" onclick="pl_del()">
                      <i class="icon table-delete-all-icon pull-left"></i>批量删除</a>
                  </div>
              </div>
              <table class="table table-striped no-img-table">
                  <thead>
                  <tr>
                      <th class="tab-checkbox">
                        <input type="checkbox" name="" value="">
                      </th>
                      <th class="small-hide">ID</th>
                      <th class="tab-img small-hide">图片</th>
                      <th>名称</th>
                      <th class="small-hide">分类</th>
                      <th>会员</th>
                      <th class="small-hide">时间</th>
            			    <th class="table-last-group">操作</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                    if(empty($array)) echo '<tr><td colspan="8" style="text-align:center">没有记录~</td></tr>';
                    foreach ($array as $rows) {
                        $row = $this->csdb->get_row('vod','id,name,pic',$rows->did);
                        if(!$row){
                            $id=0;
                            $name='数据已删除';
                            $pic='';
                        }else{
                            $id=$row->id;
                            $name=$row->name;
                            $pic=$row->pic;
                        }
                        if(!empty($pic)){
                            $pic='<img src="'.$pic.'" style="height:40px;">';
                        }else{
                            $pic='---';
                        }
                        $time = date('Y-m-d',$rows->addtime);
                        if(date('Y-m-d')==$time) $time = '<font color=red>'.$time.'</font>';
                        $cname = getzd('class','name',$rows->cid);
                        $user = getzd('user','name',$rows->uid);

                                      echo '
                    			<tr>
                            <td class="tab-checkbox"><input type="checkbox" value="'.$rows->id.'" name="id[]"></td>
                    				<td class="small-hide">'.$rows->id.'</td>
                    				<td class="tab-img small-hide">'.$pic.'</td>
                    				<td><a href="'.links('show','index',$id,0,1).'" target="_blank"><u class="text-primary">'.$name.'</u></a></td>
                    				<td class="small-hide">'.$cname.'</td>
                    				<td>'.$user.'</td>
                    				<td class="small-hide">'.$time.'</td>
                    				<td class="table-last-group">
                    				    <a style="text-decoration:none" onClick="del(this,'.$rows->id.')" href="javascript:;" title="删除">
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
        $.post('<?=links('fav','del')?>',{id: id},function(data) {
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
