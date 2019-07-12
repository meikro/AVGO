<title>采集</title>
</head>
<body>
<?php
if(!empty($jumpurl)){
	$jumpurl = '<a href="'.$jumpurl.'" style="color:#FF0000;font-weight:bold">上次有采集任务没有完成，是否接着采集?</a>';
}else{
	$jumpurl = '';
}
?>
<div class="content-page">
    <div class="content-page-nav">
        <a class="nav-list"><i class="icon index-icon pull-left"></i>首页</a><a class="nav-list"> > 视频库管理 </a><a class="nav-list"> > 资源库 </a>
    </div>
    <div class="content-page-table">
        <div class="content-page-table-top">
            <div class="pull-left">
                <a class="gop" href="javascript:;" onclick="cmd('添加资源站点','<?=links('caiji','type_edit','add','k='.$k)?>')"><i class="icon table-add-all-icon pull-left"></i>新增<?=$api['name']?>资源</a>
            </div>
            <a class="gop pull-right" href="<?=links('caiji','index')?>">返回资源库</a>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="tab-checkbox small-hide">编号</th>
                    <th class="w20"><?=$api['name']?></th>
                    <th colspan="4" class="small-hide"></th>
                    <th class="w25 small-hide"><?=$jumpurl?></th>
                    <th class="table-last-group">操作</th>
                </tr>
            </thead>
        <tbody>
<?php
$apiurl = $api['apiurl'];
$gf = $op == 'gf' ? 1 : 0;
if(empty($api['list'])){
		$ctapiurl = links('caiji','index',0,'api='.urlencode($apiurl).'&ac='.$api['ac'].'&rid='.$row['rid'].'&type='.$api['type'].'&form='.$api['form'].'&k='.$k.'&gf='.$gf);
	    echo '<tr>
	    <td class="tab-checkbox small-hide">01、</td>
	    <td class="w20"><a href="'.$ctapiurl.'">'.$api['name'].'全站资源</a></td>
	    <td class="small-hide"><a href="'.$ctapiurl.'">进入查看</a></td>
	    <td class="small-hide"><a href="'.$ctapiurl.'&op=day&do=caiji" style="color:red">采集当天</a></td>
	    <td class="small-hide"><a href="'.$ctapiurl.'&op=week&do=caiji" style="color:green">采集本周</a></td>
	    <td class="small-hide"><a href="'.$ctapiurl.'&op=all&do=caiji" style="color:#ff6600">采集全部</a></td>
	    <td class="w25 small-hide">&nbsp;'.$api['info'].'</td>
		<td class="table-last-group">--</td>
	    </tr>';
}else{
	foreach ($api['list'] as $n=>$row) {
		$xu = $n+1;
	    if($xu<10) $xu = '0'.$xu;
	    if(!empty($row['apiurl'])) $apiurl = $row['apiurl'];
		$ctapiurl = links('caiji','index',0,'api='.urlencode($apiurl).'&ac='.$api['ac'].'&rid='.$row['rid'].'&type='.$api['type'].'&form='.$api['form'].'&k='.$k.'&gf='.$gf);
        $cmd = '<a onClick="cmd(\'编辑修改\',\''.links('caiji','type_edit','','k='.$k.'&n='.$n).'\')" href="javascript:;" title="编辑"><i class="icon table-update-icon"></i></a> <a onClick="del(this,'.$k.','.$n.')" href="javascript:;" title="删除"><i class="icon table-delete-icon"></i></a>';
        if($op == 'gf') $cmd = ' -- ';
	    echo '<tr>
	    <td class="tab-checkbox small-hide">'.$xu.'、</td>
	    <td class="w20"><a href="'.$ctapiurl.'">'.$row['name'].'</a></td>
	    <td class="small-hide"><a href="'.$ctapiurl.'">进入查看</a></td>
	    <td class="small-hide"><a href="'.$ctapiurl.'&op=day&do=caiji" style="color:red">采集当天</a></td>
	    <td class="small-hide"><a href="'.$ctapiurl.'&op=week&do=caiji" style="color:green">采集本周</a></td>
	    <td class="small-hide"><a href="'.$ctapiurl.'&op=all&do=caiji" style="color:#ff6600">采集全部</a></td>
	    <td class="w25 small-hide">&nbsp;'.$api['info'].'</td>
		<td class="table-last-group">
		    '.$cmd.'
		</td>
	    </tr>';
	}
}
?>
        </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
/*修改*/
function cmd(title,url){
    layer_show(title,url,700,460);
}
/*删除*/
function del(obj,k,n){
    layer.confirm('删除后不能恢复，确认要删除吗？',function(index){
        $.post('<?=links('caiji','type_del')?>',{id:k,n:n},function(data) {
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