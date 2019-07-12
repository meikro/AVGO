<title>视频管理</title>
<script type="text/javascript" src="<?=Base_Path?>datepicker/wdatepicker.js"></script>
</head>
<body>
  <div class="content-page">
      <div class="content-page-nav">
          <a class="nav-list"><i class="icon index-icon pull-left"></i>首页</a><a class="nav-list"> > 视频库管理 </a><a class="nav-list"> > 视频管理 </a>
      </div>
      <form action="<?=links('vod','index')?>" method="post">
          <div class="content-page-select-group row">
              <div class="col-md-1">
                  <select name="cid" class="content-page-select">
                      <option>全部分类</option>
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
              <div class="col-md-1">
                  <select name="play" class="content-page-select">
                      <option value="0">播放来源</option>
                      <?php
                      			foreach ($player as $rowp) {
                      				$sel = $rowp->bs == $play ? ' selected' : '';
                      				echo '<option value="'.$rowp->bs.'"'.$sel.'>'.$rowp->name.'</option>';
                      			}
                      ?>
                  </select>
              </div>
              <div class="col-md-1 w10">
                  <select name="yid" class="content-page-select">
                      <option value="0">隐藏/推荐/幻灯</option>
                      <optgroup label="是否隐藏">
                  				<option value="1"<?php if($yid==1) echo ' selected';?>>未隐藏</option>
                  				<option value="2"<?php if($yid==2) echo ' selected';?>>已隐藏</option>
                  		</optgroup>
                  		<optgroup label="是否推荐">
                  				<option value="3"<?php if($yid==3) echo ' selected';?>>未推荐</option>
                  				<option value="4"<?php if($yid==4) echo ' selected';?>>已推荐</option>
                  		</optgroup>
                  		<optgroup label="是否幻灯">
                  			<option value="5"<?php if($yid==5) echo ' selected';?>>未幻灯</option>
                  			<option value="6"<?php if($yid==6) echo ' selected';?>>已幻灯</option>
                  		</optgroup>
                      <optgroup label="是否收费">
                        <option value="7"<?php if($yid==7) echo ' selected';?>>会员点播</option>
                        <option value="8"<?php if($yid==8) echo ' selected';?>>点播vip5折</option>
                        <option value="9"<?php if($yid==9) echo ' selected';?>>vip免费</option>
                        <option value="10"<?php if($yid==10) echo ' selected';?>>点播vip免费</option>
                      </optgroup>
                  </select>
              </div>
              <div class="col-md-1">
                  <select name="lz" class="content-page-select">
                      <option value="0">是否连载</option>
                      <option value="1"<?php if($lz==1) echo ' selected';?>>连载中</option>
    			            <option value="2"<?php if($lz==2) echo ' selected';?>>已完结</option>
                  </select>
              </div>
              <div class="col-md-1">
                  <select name="order" class="content-page-select">
                    <option value="">排序方式</option>
            				<option value="id"<?php if($order=='id') echo ' selected';?>>ID降序</option>
            				<option value="addtime"<?php if($order=='addtime') echo ' selected';?>>更新时间</option>
            				<option value="rhits"<?php if($order=='rhits') echo ' selected';?>>播放日人气</option>
            				<option value="zhits"<?php if($order=='zhits') echo ' selected';?>>播放周人气</option>
            				<option value="yhits"<?php if($order=='yhits') echo ' selected';?>>播放月人气</option>
            				<option value="hits"<?php if($order=='hits') echo ' selected';?>>播放总人气</option>
                  </select>
              </div>
              <div class="col-md-3">
                  <div class="rili-input col-md-5">
                      <input name="kstime" value="<?=$kstime?>" type="text" onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})" class="time"><i class="icon rili-icon"></i>
                  </div>
                  <div class="col-md-2" style="text-align: center">TO</div>
                  <div class="rili-input col-md-5">
                      <input name="jstime" value="<?=$jstime?>" type="text" onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})" class="time"><i class="icon rili-icon"></i>
                  </div>
              </div>
              <div class="col-md-1">
                  <select name="ziduan" class="content-page-select">
                    <option value="name"<?php if($ziduan=='name') echo ' selected';?>>视频名称</option>
                    <option value="id"<?php if($ziduan=='id') echo ' selected';?>>视频ID</option>
                    <option value="ztid"<?php if($ziduan=='ztid') echo ' selected';?>>专题ID</option>
                    <option value="daoyan"<?php if($ziduan=='daoyan') echo ' selected';?>>视频导演</option>
                    <option value="zhuyan"<?php if($ziduan=='zhuyan') echo ' selected';?>>视频主演</option>
                    <option value="type"<?php if($ziduan=='type') echo ' selected';?>>视频类型</option>
                  </select>
              </div>
              <div class="col-md-1">
                <input type="text" value="<?=$key?>"  name="key" placeholder="搜索内容"  class="key content-page-input" >
              </div>
              <div class="col-md-1">
                  <button name="so" class="content-page-btn blue-white-btn" type="submit">搜视频</button>
              </div>
          </div>
      </form>
      <form action="<?=links('vod','del',0,'ac=all&'.$uri)?>" method="post" id="myform" name="myform">
          <div class="content-page-table">
              <div class="content-page-table">
                  <div class="content-page-table-top">
                      <div class="pull-left">
                        <?php if(Cache_Is==1){ ?>
                    			<a class="gop" href="javascript:;" onclick="pl_cmd(1)"><i class="icon table-tj-all-icon pull-left"></i> 批量更新</a>
                    		<?php } ?>
                          <a class="gop" href="javascript:;" onclick="pl_cmd(2)"><i class="icon table-tj-all-icon pull-left"></i>批量推荐</a>
                          <a class="gop" href="javascript:;" onclick="cmd('添加视频','<?=links('vod','edit')?>')"><i class="icon table-add-all-icon pull-left"></i>添加视频</a>
                          <a class="gop" href="javascript:;" onclick="pl_cmd(3)"><i class="icon table-update-all-icon pull-left"></i>批量修改</a>
                          <?=$downpic?>
                      </div>
                      <a class="gop pull-right" href="javascript:;" onclick="pl_del()"><i class="icon table-delete-all-icon pull-left"></i>批量删除</a>
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
                          <th class="small-hide">导演</th>
                          <th class="small-hide">类型</th>
                          <th class="small-hide">人气</th>
                          <th class="small-hide">金币</th>
                          <th class="small-hide">推荐</th>
                          <th class="small-hide">状态</th>
                          <th class="small-hide">时间</th>
                          <th class="table-last-group">操作</th>
                      </tr>
                      </thead>
                      <tbody>
                    <?php
            					if(empty($array))
                        echo '<tr><td colspan="12" class="text-c">没有记录~</td></tr>';
            					foreach ($array as $row) {
            						if(!empty($row->pic)){
            							$pic='<img src="'.getpic($row->pic).'">';
            						}else{
            							$pic='---';
            						}
            						if($row->tid==0){  //未推荐
            							$tj='<span class="label label-danger radius">未推荐</span>';
            						}else{
            							$tj='<span class="label label-success radius">已推荐</span>';
            						}
            						if($row->yid==0){  //未隐藏
            							$zt='<span class="label label-success radius">正常</span>';
            						}else{
            							$zt='<span class="label label-danger radius">隐藏</span>';
            						}
            						$bz = !empty($row->state) ? ' ['.$row->state.']' : '';
            						$time = date('Y-m-d',$row->addtime);
            						if(date('Y-m-d')==$time) $time = '<font color=red>'.$time.'</font>';
            						      echo '
        								<tr>
        									<td class="tab-checkbox"><input type="checkbox" value="'.$row->id.'" name="id[]"></td>
        									<td class="small-hide">'.$row->id.'</td>
        									<td class="tab-img small-hide">'.$pic.'</td>
        									<td class="w30">
                            <a href="//'.Web_Url.links('show','index',$row->id,0,1).'" target="_blank">
                              '.$row->name.$bz.'
                            </a>
                          </td>
        									<td class="small-hide">'.$row->daoyan.'</td>
        									<td class="small-hide">'.$row->type.'</td>
        									<td class="small-hide">'.$row->hits.'</td>
        									<td class="small-hide">'.$row->cion.'</td>
        									<td class="text-green small-hide">'.$tj.'</td>
        									<td class="text-green small-hide">'.$zt.'</td>
        									<td class="small-hide">'.$time.'</td>
        									<td class="table-last-group">
                            <a style="text-decoration:none" onClick="cmd(\'视频编辑\',\''.links('vod','edit',0,'id='.$row->id).'\')" href="javascript:;" title="编辑">
                              <i class="icon table-update-icon"></i>
                            </a>
        										<a style="text-decoration:none" onClick="del(this,'.$row->id.')" href="javascript:;" title="删除">
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
      </table>
  </div>
<script type="text/javascript">
/*修改、查看*/
function cmd(title,links){
    var index = layer.open({
    	type: 2,
    	title: title,
    	content: links
    });
    layer.full(index);
}
/*删除*/
function del(obj,id){
    layer.confirm('删除后不能恢复，确认要删除吗？',function(index){
        $.post('<?=links('vod','del')?>',{id: id},function(data) {
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
function pl_cmd(sid){
  var xuan=0;
  $("td input:checkbox").each(function(){
      if($(this).prop("checked")){
          xuan++;
      }
  });
  if(xuan>0){
      if(sid==3){
          $('#myform').attr('action','<?=links('vod','plcmd')?>');
	  $('#myform').submit();
      } else if(sid==1){
          $('#myform').attr('action','<?=links('vod','html')?>');
	  $('#myform').submit();
      } else {
          $('#myform').attr('action','<?=links('vod','reco')?>');
	  $('#myform').submit();
      }
  }else{
      get_msg('请选择要操作的数据~!');
  }
}
</script>
</body>
</html>
