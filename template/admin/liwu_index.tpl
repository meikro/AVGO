<title>礼物管理</title>
</head>
<body>
  <div class="content-page">
      <div class="content-page-nav">
          <a class="nav-list"><i class="icon index-icon pull-left">
          </i>首页</a><a class="nav-list"> > 礼物管理 </a><a class="nav-list"> > 礼物列表 </a>
      </div>
      <form action="<?=links('liwu','del',0,'ac=all')?>" method="post" id="myform" name="myform">
      <div class="content-page-table">
          <div class="content-page-table">
              <div class="content-page-table-top">
                  <div class="pull-left">
										<a class="gop" href="javascript:;" onclick="cmd('添加礼物','<?=links('liwu','edit')?>')">
											<i class="icon table-add-all-icon pull-left"></i>添加礼物</a>
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
                      <th class="small-hide">ID</th>
                      <th class="tab-img small-hide">图片</th>
                      <th>标题</th>
                      <th>金币</th>
                      <th class="small-hide">介绍</th>
            			    <th class="table-last-group">操作</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                    if(empty($array)) echo '<tr><td colspan="7" style="text-align:center">没有记录~</td></tr>';
										foreach ($array as $row) {
										        $pic = empty($row->pic) ? '--' : '<img src="'.getpic($row->pic).'">';
										      echo '
													<tr>
										        <td class="tab-checkbox"><input type="checkbox" value="'.$row->id.'" name="id[]"></td>
														<td class="small-hide">'.$row->id.'</td>
														<td class="tab-img small-hide">'.$pic.'</td>
														<td>'.$row->name.'</td>
														<td>'.$row->cion.'</td>
														<td class="small-hide">'.$row->txt.'</td>
                    				<td class="table-last-group">
															<a style="text-decoration:none" onClick="cmd(\'编辑修改\',\''.links('liwu','edit',0,'id='.$row->id).'\')" href="javascript:;" title="编辑">
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
/*修改*/
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
        $.post('<?=links('liwu','del')?>',{id: id},function(data) {
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
