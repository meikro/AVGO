<title>友情链接编辑</title>
</head>
<body>
	<div class="content-page edit">
		<form action="<?=links('links','save')?>" method="post" class="form form-horizontal" id="form-article-add">
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
							<div class="submit-select-group-tab-title col-md-2">链接：</div>
							<div class="submit-select-group-select col-md-8">
								<input type="text" class="submit-long-input" value="<?=$link?>" placeholder="链接地址" name="link">
								<label class="label-tip text-gray">链接地址，不能为空</label>
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">类型：</div>
							<div class="submit-select-group-select col-md-8">
								<select class="submit-select" name="cid">
									<option value="0"<?php if($cid==0) echo ' selected';?>>文字</option>
									<option value="1"<?php if($cid==1) echo ' selected';?>>图片</option>
								</select>
								<label class="label-tip text-gray">广告类型</label>
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
    layer_show('上传图片','<?=links('upload','',0,'ac=link')?>',400,260);
}
</script>
</body>
</html>