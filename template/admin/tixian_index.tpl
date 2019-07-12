<title>提现管理</title>
<script type="text/javascript" src="<?=Base_Path?>datepicker/wdatepicker.js"></script>
</head>
<body>
  <div class="content-page">
      <div class="content-page-nav">
          <a class="nav-list"><i class="icon index-icon pull-left">
          </i>首页</a><a class="nav-list"> > 会员管理 </a><a class="nav-list"> > 提现管理 </a>
      </div>
      <form action="<?=links('tixian','index')?>" method="post">
      <div class="content-page-select-group row">
            <div class="col-md-2">
            </div>
            <div class="col-md-1" style="text-align:center">
              提现日期：
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
				<option value="user"<?php if($ziduan=='user') echo ' selected';?>>会员名称</option>
				<option value="uid"<?php if($ziduan=='uid') echo ' selected';?>>会员UID</option>
				<option value="pay"<?php if($ziduan=='pay') echo ' selected';?>>银行信息</option>
            </select>
        </div>
        <div class="col-md-1">
            <input type="text" class="key content-page-input" placeholder="搜索内容" value="<?=$key?>" name="key">
        </div>
        <div class="col-md-1">
            <select class="content-page-select" name="pid" >
				<option value="0">提现状态</option>
				<option value="1"<?php if($pid==1) echo ' selected';?>>等待打款</option>
				<option value="2"<?php if($pid==2) echo ' selected';?>>打款成功</option>
				<option value="3"<?php if($pid==3) echo ' selected';?>>打款失败</option>
            </select>
        </div>
        <div class="col-md-1">
            <button class="content-page-btn blue-white-btn">搜记录</button>
        </div>
      </div>
      </form>
      <form action="<?=links('tixian','del',0,'ac=all')?>" method="post" id="myform" name="myform">
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
                      	<th class="w5 small-hide">ID</th>
                      	<th class="w50 small-hide">银行信息</th>
                      	<th>会员</th>
                      	<th>金额</th>
                      	<th>状态</th>
                      	<th class="w10 small-hide">时间</th>
            			<th class="table-last-group">操作</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                    if(empty($array)) echo '<tr><td colspan="8" style="text-align:center">没有记录~</td></tr>';
                    foreach ($array as $row) {
					    $user = '--';
					    if($row->uid>0) $user = getzd('user','name',$row->uid);
					    $time = date('Y-m-d H:i:s',$row->addtime);
					    if(date('Y-m-d')==date('Y-m-d',$row->addtime)) $time = '<font color=red>'.$time.'</font>';
					    $cmd = '';
						if($row->pid==2){
							$zt = '<span class="label label-danger radius" title="'.$row->err.'">失败</span>';
						}elseif($row->pid==1){
							$zt = '<span class="label label-success radius">完成</span>';
						}else{
							$zt = '<span class="label label-primary radius">待付款</span>';
							$cmd = '<a class="label label-primary radius" href="javascript:;" onClick="fkok('.$row->id.')">打款确定</a> ';
						}
                        echo '
                    		<tr>
                            	<td class="tab-checkbox"><input type="checkbox" value="'.$row->id.'" name="id[]"></td>
                				<td class="w5 small-hide">'.$row->id.'</td>
                				<td class="w50 small-hide" title="'.str_replace('<br>',"\r\n",$row->pay).'">'.str_replace('<br>',' ',$row->pay).'</td>
                				<td>'.$user.'</td>
								<td>'.$row->rmb.'元</td>
								<td>'.$zt.'</td>
								<td class="w10 small-hide">'.$time.'</td>
                				<td class="table-last-group">
                				    '.$cmd.'
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
<div class="tixian" style="display: none;">
	<div style="padding: 20px;text-align: center;">
		<b style="font-size: 16px;">确定已打款了吗，打款成功还是失败呢？</b>
		<p style="margin-top: 20px;">
			<span class="btn btn-success radius" style="margin-right: 15px;cursor: pointer;" onclick="fkok2(1);">成功</span>
			<span class="btn btn-danger radius" style="margin-right: 15px;cursor: pointer;" onclick="fkok2(2);">失败</span>
			<span class="btn btn-primary radius" style="cursor: pointer;" onclick="fkok2(0);">关闭</span>
		</p>
	</div>
</div>
<script type="text/javascript">
/*删除*/
function del(obj,id){
    layer.confirm('删除后不能恢复，确认要删除吗？',function(index){
        $.post('<?=links('tixian','del')?>',{id: id},function(data) {
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
var ss = null,did=0;
function fkok(id){
	did = id;
	ss = layer.open({
		type: 1,
		shade: 0.1,
		title: '提现确定', //不显示标题
		content: $('.tixian')
	});
}
function fkok2(pid){
	if(pid==0){
		layer.close(ss);
	} else if(pid==2){
		layer.prompt({
		  formType: 2,
		  value: '',
		  title: '请输入失败原因'
		}, function(value, index, elem){
			$.post('<?=links('tixian','init')?>',{id: did,pid: pid,err: value},function(data) {
			    var msg=data['error'];
			   	if(msg == "ok"){//成功
			   		location.reload();
			   	}else{
			        get_msg(msg);
			   	}
			   	layer.close(index);
			},"json");
		});
	}else{
	    $.post('<?=links('tixian','init')?>',{id: did,pid: pid},function(data) {
	    	var msg=data['error'];
	   		if(msg == "ok"){//成功
	   			location.reload();
	   		}else{
	        	get_msg(msg);
	   		}
	    },"json");
	}	
}
</script>
</body>
</html>
