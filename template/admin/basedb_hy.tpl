<title>备份还原</title>
</head>
<body>
	<div class="content-page">
      <div class="content-page-nav">
          <a class="nav-list"><i class="icon index-icon pull-left">
          </i>首页</a><a class="nav-list"> > 管理员管理 </a><a class="nav-list"> > 备份还原 </a>
      </div>
      <form action="<?=links('basedb','del')?>" method="post" id="myform" name="myform">
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
                      <th>文件名</th>
                      <th class="small-hide">文件大小</th>
                      <th class="small-hide">备份时间</th>
                      <th>卷数量</th>
            			    <th class="table-last-group">操作</th>
                  </tr>
                  </thead>
                  <tbody>
										<?php
										if(empty($map)) echo '<tr><td colspan="6" class="text-c">没有备份文件~</td></tr>';
										foreach ($map as $dir) {
										   if (is_dir(FCPATH.'attachment/backup/'.$dir) && substr($dir,0,6)=='Ctcms_') {
										        $dirs = directory_map(FCPATH.'attachment/backup/'.$dir, 1);
											$this->load->helper('file');
											$fine=get_file_info(FCPATH.'attachment/backup/'.$dir, $file_information='date');
											$dir = str_replace("\\","",$dir);
											$dir = str_replace("/","",$dir);
										                    echo '
													<tr>
														<td  class="tab-checkbox">
															<input type="checkbox" value="'.$dir.'" name="id[]"></td>
														<td>'.$dir.'</td>
														<td class="small-hide">'.formatsize(getdirsize('./attachment/backup/'.$dir)).'</td>
														<td class="small-hide">'.date('Y-m-d H:i:s',$fine['date']).'</td>
														<td>'.count($dirs).'</td>
														<td class="table-last-group">
														   <a title="打包下载" href="'.links('basedb','zip',0,'dir='.$dir).'" class=" cmd">下载</a>
														   <a title="还原数据库" href="javascript:;" onclick="cmd(\''.links('basedb','restore_save',0,'dir='.$dir).'\');" class="cmd">还原</a>
														</td>
													</tr>';
										   }
										}
										?>
                  </tbody>
              </table>
							<div class="cl pd-5 bg-1 bk-gray">
							  <span class="l">
							    <a sid="1" id="checkbox" class="btn radius" href="javascript:;" style="margin-left:20px">全选</a>
							    <a class="btn radius" href="javascript:;" onclick="pl_cmd();">批量删除</a>
							  </span>
							</div>
          </div>
      </div>
		</form>
  </div>
<script>
function pl_cmd(){
  var xuan=0;
  $("td input:checkbox").each(function(){
      if($(this).prop("checked")){
          xuan++;
      }
  });
  if(xuan>0){
      layer.confirm('确认要操作吗？',function(){
	  $('#myform').submit();
      });
  }else{
      layer.msg('请选择要删除的数据~!');
  }
}
function cmd(links){
  layer.confirm('数据不可逆转，您确定要还原吗？',function(){
	get_msg('数据还原中，请稍后...',1000*60*60*60,16);
	setTimeout("location.href='"+links+"'",1000);
  });
}
</script>
</body>
</html>
