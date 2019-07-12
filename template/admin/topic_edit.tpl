<title>专题编辑</title>
</head>
<body>
<div class="content-page edit">
	<form action="<?=links('topic','save')?>" method="post" class="form form-horizontal" id="form-article-add">
	    <div class="content-page-tab-box">
	        <div class="control-page">
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">标题：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input placeholder="标题" type="text" class="submit-long-input" value="<?=$name?>" name="name">
						<label class="label-tip text-gray">专题标题</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">缩略图：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input type="text" class="submit-long-input" id="pic1" name="pic" value="<?=$pic?>">
                        <input class="btn" type="button" onClick="upload(1);" value="上传图片">
                        <label class="label-tip text-gray">专题缩略图</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">banner图：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input type="text" class="submit-long-input" id="pic2" name="tpic" value="<?=$tpic?>">
                        <input class="btn" type="button" onClick="upload(2);" value="上传图片">
                        <label class="label-tip text-gray">专题banner图片</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">模板：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input type="text" class="submit-long-input" name="skin" value="<?=$skin?>">
                        <label class="label-tip text-gray">专题模板</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">介绍：</div>
                    <div class="submit-select-group-select col-md-8">
                        <textarea class="submit-textarea pull-left" id="text" name="text" style="width:100%;height:280px;margin-right: 4px;color:#fff;"><?=$text?></textarea>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-btn col-md-offset-2 col-md-8">
                    	<input name="id" type="hidden" value="<?=$id?>">
                        <button class="blue-white-btn submit-btn" type="submit">保存</button>
                    </div>
                </div>
	        </div>
	    </div>
	</form>
</div>
<link rel="stylesheet" href="<?=Base_Path?>layui/css/layui.css">
<script src="<?=Base_Path?>layui/layui.all.js"></script>
<script type="text/javascript">
var layedit;
$(function(){
    //富文本编辑器
    layedit = layui.layedit;
    layedit.set({
        uploadImage: {
            url: '<?=links('upload','save',0,'dir=editor')?>'
        }
    });
    layedit.build('text', {
        hideTool: ['face', '|']
    });
});
/*上传图片*/
function upload(n){
    layer_show('上传图片','<?=links('upload','',0,'ac=topic&sid=')?>'+n,400,260);
}
</script>
</body>
</html>
