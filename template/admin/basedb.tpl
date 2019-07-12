<title>备份还原</title>
</head>
<body>
  <div class="content-page">
      <div class="content-page-nav">
          <a class="nav-list"><i class="icon index-icon pull-left">
          </i>首页</a><a class="nav-list"> > 管理员管理 </a><a class="nav-list"> > 备份还原 </a>
      </div>
      <form action="<?=site_url('basedb/optimize')?>" method="post" id="myform" name="myform">
      <div class="content-page-table">
          <div class="content-page-table">
							<div class="content-page-table-top">
								<div>
									<a class="gop" href="<?=links('basedb')?>">
										<i class="icon table-ybackup-icon pull-left"></i>数据备份</a>
									<a class="gop" href="<?=links('basedb','restore')?>">
										<i class="icon table-ybackup-icon pull-left"></i>数据还原</a>
								</div>
							</div>
              <table class="table table-striped no-img-table">
                  <thead>
                  <tr>
                      <th class="tab-checkbox">
                        <input type="checkbox" name="" value="">
                      </th>
                      <th class="small-hide">ID</th>
                      <th>表名</th>
                      <th class="small-hide">类型</th>
                      <th class="small-hide">编码</th>
                      <th>记录数</th>
						<th class="small-hide">使用空间</th>
						<th class="small-hide">碎片</th>
            			    <th class="table-last-group">操作</th>
                  </tr>
                  </thead>
                  <tbody>
										<?php
										$i=1;
										foreach ($tables as $row) {
										        if(strpos($row['Name'],CT_SqlPrefix) !== FALSE){
										                    echo '
													<tr>
														<td class="tab-checkbox">
															<input type="checkbox" value="'.$row['Name'].'" name="id[]"></td>
														<td class="small-hide">'.$i.'</td>
														<td>'.$row['Name'].'</td>
														<td class="small-hide">'.$row['Engine'].'</td>
														<td class="small-hide">'.$row['Collation'].'</td>
														<td>'.$row['Rows'].'</td>
														<td class="small-hide">'.formatsize($row['Data_length']).'</td>
														<td class="small-hide">'.formatsize($row['Data_free']).'</td>
														<td class="table-last-group">
														   <a title="优化表" href="javascript:;" onclick="cmd(\''.links('basedb','optimize',0,'table='.$row['Name']).'\');" class="cmd">优化</a>
														   <a title="修复表" href="javascript:;" onclick="cmd(\''.links('basedb','repair',0,'table='.$row['Name']).'\');" class="cmd">修复</a>
														</td>
													</tr>';
										             $i++;
											 }
										}
										?>
                  </tbody>
              </table>
							<div class="cl pd-5 bg-1 bk-gray">
							  <span class="l">
							    <a sid="1" id="checkbox" class="btn radius" href="javascript:;" style="margin-left:20px">全选</a>
							    <a class="btn radius" href="javascript:;" onclick="pl_cmd('<?=links('basedb','optimize')?>');">批量优化</a>
							    <a class="btn radius" href="javascript:;" onclick="pl_cmd('<?=links('basedb','repair')?>');">批量修复</a>
							    <a class="btn radius" href="javascript:;" onclick="pl_cmd('<?=links('basedb','backup')?>');">开始备份数据库</a>
							  </span>
							</div>
          </div>
      </div>
		</form>
  </div>
<script>
function pl_cmd(links){
  var xuan=0;
  $("td input:checkbox").each(function(){
      if($(this).prop("checked")){
          xuan++;
      }
  });
  if(xuan>0){
      layer.confirm('确认要操作吗？',function(){
	  $('#myform').attr('action',links);
	  $('#myform').submit();
      });
  }else{
      layer.msg('请选择要操作的数据表~!');
  }
}
function cmd(links){
  layer.confirm('确认要操作吗？',function(){
	window.location.href=links;
  });
}
</script>
</body>
</html>
