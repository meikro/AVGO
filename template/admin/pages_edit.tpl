<title>添加文章</title>
</head>
<body>
	<div class="content-page edit">
		<form action="<?=links('pages','save')?>" method="post" class="form form-horizontal" id="form-article-add">
			<div class="content-page-tab-box">
				<div class="control-page">
					<div id="page_a">
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">标题：</div>
							<div class="submit-select-group-select col-md-8">
								<input type="text" class="submit-long-input" value="<?=$name?>" placeholder="页面标题" name="name">
								<label class="label-tip text-gray">页面标题，不能为空</label>
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">标示：</div>
							<div class="submit-select-group-select col-md-8">
								<input type="text" class="submit-long-input" value="<?=$bs?>" placeholder="页面标示,只能为2-16位字母" name="bs">
								<label class="label-tip text-gray">页面标示，2-16位字母或数字</label>
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">状态：</div>
							<div class="submit-select-group-select col-md-8">
								<select class="submit-select" name="yid">
									<option value="0"<?php if($yid==0) echo ' selected';?>>显示</option>
									<option value="1"<?php if($yid==1) echo ' selected';?>>隐藏</option>
								</select>
								<label class="label-tip text-gray">页面状态</label>
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">内容：</div>
							<div class="submit-select-group-select col-md-8">
								<textarea style="width: 100%;height: 500px;" id="text" name="text"><?=$text?></textarea>
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
$(function(){
	KindEditor.ready(function(K) {
		window.editor = K.create('#text');
	});
});
</script>
</body>
</html>