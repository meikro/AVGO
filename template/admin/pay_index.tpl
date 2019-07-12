<title>充值管理</title>
<script type="text/javascript" src="<?=Base_Path?>datepicker/wdatepicker.js"></script>
</head>
<body>
  <div class="content-page">
      <div class="content-page-nav">
          <a class="nav-list"><i class="icon index-icon pull-left">
          </i>首页</a><a class="nav-list"> > 会员管理 </a><a class="nav-list"> > 充值列表 </a>
      </div>
      <form action="<?=links('pay','index')?>" method="post">
      <div class="content-page-select-group row">
            <div class="col-md-1">
            </div>
            <div class="col-md-1">
              <select class="content-page-select" name="cid" >
                <option value="">充值类型</option>
                <option value="1"<?php if($cid==1) echo ' selected';?>>金币</option>
                <option value="2"<?php if($cid==2) echo ' selected';?>>Vip</option>
              </select>
            </div>
            <div class="col-md-1">
              <select class="content-page-select" name="pid" >
                <option value="">付款状态</option>
            		<option value="2"<?php if($pid==2) echo ' selected';?>>已完成</option>
            		<option value="1"<?php if($pid==1) echo ' selected';?>>未完成</option>
              </select>
            </div>
            <div class="col-md-1">
                <select class="content-page-select" name="sid" >
                  <option value="">付款方式</option>
              		<option value="1"<?php if($sid==1) echo ' selected';?>>支付宝</option>
              		<option value="2"<?php if($sid==2) echo ' selected';?>>微信支付</option>
              		<option value="3"<?php if($sid==3) echo ' selected';?>>QQ支付</option>
              		<option value="4"<?php if($sid==4) echo ' selected';?>>网银支付</option>
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
              <option value="dingdan"<?php if($ziduan=='dingdan') echo ' selected';?>>订单号</option>
          		<option value="user"<?php if($ziduan=='user') echo ' selected';?>>会员名称</option>
          		<option value="uid"<?php if($ziduan=='uid') echo ' selected';?>>会员ID</option>
          		<option value="rmb"<?php if($ziduan=='rmb') echo ' selected';?>>充值金额</option>
          		<option value="day"<?php if($ziduan=='day') echo ' selected';?>>vip天数</option>
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
      <form action="<?=links('pay','del',0,'ac=all')?>" method="post" id="myform" name="myform">
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
                      <th class="w20">订单号</th>
                      <th class="w20 small-hide">充值类型</th>
                      <th class="small-hide">会员</th>
                      <th>金额</th>
                      <th>状态</th>
                      <th class="small-hide">付款方式</th>
                      <th class="w10 small-hide">时间</th>
            			    <th class="table-last-group">操作</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                    if(empty($array)) echo '<tr><td colspan="10" style="text-align:center">没有记录~</td></tr>';
                    foreach ($array as $row) {
                        if($row->sid==4){
                            $lx='<span class="label label-danger radius">网银支付</span>';
                        }elseif($row->sid==3){
                            $lx='<span class="label label-success radius">QQ支付</span>';
                        }elseif($row->sid==2){
                            $lx='<span class="label label-danger radius">微信支付</span>';
                        }else{
                            $lx='<span class="label label-success radius">支付宝</span>';
                        }
                        if($row->pid==0){
                            $zt='<span class="label label-danger radius">失败</span>';
                        }else{
                            $zt='<span class="label label-success radius">成功</span>';
                        }
                        if($row->cid==0){
                    	$name = '购买 '.$row->rmb*CT_Rmb_To_Cion.' 个金币';
                        }else{
                    	$name = '购买 '.$row->day.' 天VIP';
                        }
                        $user = '--';
                        if($row->uid>0) $user = getzd('user','name',$row->uid);
                        $time = date('Y-m-d H:i:s',$row->addtime);
                        if(date('Y-m-d')==date('Y-m-d',$row->addtime)) $time = '<font color=red>'.$time.'</font>';

                          echo '
                    			<tr>
                            <td class="tab-checkbox"><input type="checkbox" value="'.$row->id.'" name="id[]"></td>
                    				<td class="small-hide">'.$row->id.'</td>
                    				<td class="w20">'.$row->dingdan.'</td>
                    				<td class="w20 small-hide">'.$name.'</td>
                    				<td class="small-hide">'.$user.'</td>
                    				<td>'.$row->rmb.'</td>
                    				<td>'.$zt.'</td>
                    				<td class="small-hide">'.$lx.'</td>
                    				<td class="w10 small-hide">'.$time.'</td>
                    				<td class="f-14 td-manage">
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
        $.post('<?=links('pay','del')?>',{id: id},function(data) {
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
