<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>文件上传</title>
    <link rel="stylesheet" type="text/css" href="<?=Base_Path?>plupload/css/webuploader.css">
    <script type="text/javascript" src="<?=Base_Path?>jquery/jquery.min.js"></script>
<style>
    #upload_box{width:96%;margin:2% auto;border: 1px dashed #999;box-sizing: border-box;}
    h5,p{margin: 10px 5px;}
    .btns{padding: 10px 20px;position: relative;}
    .bord{border : 1px solid #3bb4f2;border-radius: 5px;}
    #picker{width:100px;height:48px;}
    #ctlBtn{position: absolute;top:10px;right:20px;width: 86px;height:42px;cursor: pointer;line-height: 42px;font-size: 14px;border-radius: 3px;background-color: #e6e6e6;color: #444;text-align: center;background-color: #e6e6e6;}
    #thelist{margin: 10px 20px;padding: 5px;}
    .item{border-bottom: 1px dashed #999;}
    .progress-bar{height:10px;margin-bottom:6px;}
</style>
</head>
<body>
<div id="upload_box">
    <div id="uploader" class="wu-example">
        <!--用来存放文件信息-->
        <div class="btns">
            <div id="picker">选择文件</div>
            <div id="ctlBtn">开始上传</div>
        </div>
        <div id="thelist"></div>
    </div> 
</div>
<script type="text/javascript" src="<?=Base_Path?>plupload/js/webuploader.js"></script>
<script type="text/javascript">
    var num = ok = 0;
    var uploader = WebUploader.create({
        swf: '<?=Base_Path?>plupload/js/Uploader.swf',
        server: '<?=$upsave?>',
        pick: '#picker',
        fileVal: 'Filedata',
        formData: {
            key: "<?=$key?>", 
            dir: "<?=$dir?>"
        },
        accept: {
            title: '图片文件上传',
            extensions: 'gif,jpg,jpeg,bmp,png',
            mimeTypes: 'image/*'
        },
        fileNumLimit: <?=$len == 0 ? 1 : $len?>
    });
    uploader.on('fileQueued', function( file ) {
        num++;
        $('#thelist').append( '<div id="' + file.id + '" class="item">' +
            '<h5 class="info">' + file.name + '</h5>' +
            '<p class="state" style="color:#f90">等待上传...</p>' +
        '</div>' );
        $('#thelist').addClass('bord');
    });
    $('#ctlBtn').click(function(event) {
        uploader.upload();
        uploader.on( 'uploadProgress', function( file, percentage ) {
            var $li = $( '#'+file.id ),
                $percent = $li.find('.progress .progress-bar');
            if ( !$percent.length ) {
                $percent = $('<div class="progress progress-striped active">' +
                  '<div class="progress-bar" role="progressbar" style="width: 0%;background-color:#666;">' +
                  '</div>' +
                '</div>').appendTo( $li ).find('.progress-bar');
            }
            $li.find('p.state').html('<font color=blue>上传中...</font>');
            $percent.css( 'width', percentage * 100 + '%' );
        });
        uploader.on( 'uploadSuccess', function(file,json) {
            ok++;
            if(json.code == 0){
                <?php if($dir == 'vpic'){ ?>
                var html = '<div id="vpic_'+json.did+'" style="width:100%;margin-bottom:5px;"><input placeholder="图片地址" type="text" class="submit-long-input" value="'+json.str+'" name="vpic[]"> <input did="'+json.did+'" type="button" class="btn delvpic" value="-删除"></div>';
                parent.$('#vpic').append(html);
                <?php }else{ ?>
                parent.$('#pic<?=$sid?>').val(json.str);
                <?php } ?>
                if(ok >= num){
                    setTimeout(function(){
                        parent.layer.close(parent.upindex);
                    },200);
                }
            }else{
                $('#'+file.id ).find('p.state').html('<font color=red>上传失败：'+json.str+'</font>');
            }
        });
        uploader.on( 'uploadError', function( file ) {
            $( '#'+file.id ).find('p.state').html('<font color=red>上传出错</font>');
        });
        uploader.on( 'uploadComplete', function( file ) {
            $( '#'+file.id ).find('.progress').fadeOut();
        });
    });
</script>
</body>
</html>