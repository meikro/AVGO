<title>管理员管理</title>
</head>
<body>
  <div class="content-page">
      <div class="content-page-nav">
          <a class="nav-list"><i class="icon index-icon pull-left">
          </i>首页</a><a class="nav-list"> > 管理员管理 </a><a class="nav-list"> > 管理员列表 </a>
      </div>
      <div class="content-page-table">
          <div class="content-page-table">
              <div class="content-page-table-top">
                  <div class="pull-left">
										<a class="gop" href="javascript:;" onclick="admin_role_add('添加管理员','<?=links('sys','edit')?>','800','450')">
											<i class="icon table-add-all-icon pull-left"></i>添加管理员</a>
                  </div>
									<span class="gop pull-right">共有数据：<strong><?=$nums?></strong> 条</span>
              </div>
              <table class="table table-striped img-table">
                  <thead>
                  <tr>
                      <th style="padding-left:40px">ID</th>
                      <th>账号</th>
                      <th class="small-hide">昵称</th>
                      <th>登陆次数</th>
                      <th class="small-hide">最后登陆IP</th>
											<th class="small-hide">最后登陆时间</th>
            			    <th class="table-last-group">操作</th>
                  </tr>
                  </thead>
                  <tbody>
										<?php
										if(empty($array)) echo '<tr><td colspan="7" class="text-c">没有记录~</td></tr>';
										foreach ($array as $row) {
										    $logtime=$row->logtime==0?'未登陆':date('Y-m-d H:i:s',$row->logtime);
										     echo '
													<tr>
														<td style="padding-left:40px">'.$row->id.'</td>
														<td>'.$row->name.'</td>
														<td class="small-hide">'.$row->nichen.'</td>
														<td>'.$row->lognum.'</td>
														<td class="small-hide">'.$row->logip.'</td>
														<td class="small-hide">'.$logtime.'</td>
														<td class="table-last-group">
															<a title="编辑" href="javascript:;" onclick="admin_role_edit(\'管理员编辑\',\''.links('sys','edit',0,'id='.$row->id).'\','.$row->id.',800,450)" style="text-decoration:none">
																<i class="icon table-update-icon"></i>
															</a>
															<a title="删除" href="javascript:;" onclick="admin_role_del(this,'.$row->id.')" style="text-decoration:none">
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
  </div>
<script type="text/javascript">
/*管理员-添加*/
function admin_role_add(title,url,w,h){
    layer_show(title,url,w,h);
}
/*管理员-编辑*/
function admin_role_edit(title,url,id,w,h){
    layer_show(title,url,w,h);
}
/*管理员-删除*/
function admin_role_del(obj,id){
    layer.confirm('删除后不能恢复，确认要删除吗？',function(index){
        $.post('<?=links('sys','del')?>',{id: id},function(data) {
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
</script>
</body>
</html>
