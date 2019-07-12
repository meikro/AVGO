<title>采集</title>
</head>
<body>
<body>
<div class="content-page">
    <div class="content-page-nav">
        <a class="nav-list"><i class="icon index-icon pull-left"></i>首页</a><a class="nav-list"> > 视频库管理 </a><a class="nav-list"> > 资源库 </a>
    </div>
    <div class="content-page-table">
        <div class="content-page-table-top">
            <div class="pull-left">
                <a class="gop" href="javascript:;" onclick="cmd('添加资源站点','<?=links('caiji','edit','add')?>')"><i class="icon table-add-all-icon pull-left"></i>添加资源站点</a>
            </div>
        </div>
        <table class="table table-striped no-img-table">
            <thead>
                <tr>
                    <th class="tab-checkbox small-hide w10">序号</th>
                    <th>资源名称</th>
                    <th class="small-hide">资源介绍</th>
                    <th class="table-last-group">操作</th>
                </tr>
            </thead>
        <tbody>
        <?php
        if(empty($api_gf) && empty($api)) echo '<tr><td colspan="4" class="text-c">没有记录~</td></tr>';
        foreach ($api_gf as $kk=>$row) {
            echo '
              <tr>
                <td class="tab-checkbox small-hide w10">'.($kk+1).'</td>
                <td><a href="'.links('caiji','type',0,'ac='.$row['ac'].'&op=gf&k='.$kk).'">'.$row['name'].'</a></td>
                <td class="small-hide"><a href="'.links('caiji','type',0,'ac='.$row['ac'].'&op=gf&k='.$kk).'">'.$row['info'].'</a></td>
                <td class="table-last-group">--</td>
              </tr>
            ';
        }
        foreach ($api as $k=>$row) {
            echo '
              <tr>
                <td class="tab-checkbox small-hide">'.($k+1+$kk+1).'</td>
                <td><a href="'.links('caiji','type',0,'ac='.$row['ac'].'&k='.$k).'">'.$row['name'].'</a></td>
                <td class="small-hide"><a href="'.links('caiji','type',0,'ac='.$row['ac'].'&k='.$k).'">'.$row['info'].'</a></td>
                <td class="table-last-group">
                    <a onClick="cmd(\'编辑修改\',\''.links('caiji','edit',0,'k='.$k).'\')" href="javascript:;" title="编辑"><i class="icon table-update-icon"></i></a> 
                    <a onClick="del(this,'.$k.')" href="javascript:;" title="删除"><i class="icon table-delete-icon"></i></a>
                </td>
              </tr>
            ';
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
function del(obj,id){
    layer.confirm('删除后不能恢复，确认要删除吗？',function(index){
        $.post('<?=links('caiji','del')?>',{id: id},function(data) {
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
