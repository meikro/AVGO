<title>礼物编辑</title>
</head>
<body>
	<div class="content-page edit">
		<form action="<?=links('liwu','save')?>" method="post" class="form form-horizontal" id="form-article-add">
			<div class="content-page-tab-box">
				<div class="control-page">
					<div id="page_a">
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">名称：</div>
							<div class="submit-select-group-select col-md-8">
								<input type="text" class="submit-long-input" value="<?=$name?>" placeholder="名称" name="name">
								<label class="label-tip text-gray">名称，不能为空</label>
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">金币：</div>
							<div class="submit-select-group-select col-md-8">
								<input type="text" class="submit-long-input" value="<?=$cion?>" placeholder="金币" name="cion">
								<label class="label-tip text-gray">金币不能为空</label>
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">图片：</div>
							<div class="submit-select-group-select col-md-8">
								<input id="pic" type="text" class="submit-long-input" value="<?=$pic?>" placeholder="链接图片" name="pic">
								<input class="btn" type="button" onClick="upload();" value="上传图片">
								<label class="label-tip text-gray">链接图片</label>
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">介绍：</div>
							<div class="submit-select-group-select col-md-8">
								<input type="text" class="submit-long-input" value="<?=$txt?>" placeholder="礼物介绍" name="txt">
								<label class="label-tip text-gray">礼物介绍</label>
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
			</div>
		</form>
	</div>
<script type="text/javascript">
/*上传图片*/
function upload(){
    layer_show('上传图片','<?=links('upload','',0,'ac=liwu')?>',400,200);
}
</script>
</body>
</html>