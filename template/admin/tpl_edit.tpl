<title>模板编辑</title>
</head>
<body>
	<div class="content-page edit">
		<form action="<?=links('tpl','save')?>" method="post" class="form form-horizontal" id="form-article-add">
			<div class="content-page-tab-box">
				<div class="control-page">
					<div id="page_a">
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">路径：</div>
							<div class="submit-select-group-select col-md-8">
								./template/<?=$dir?>/<input type="text" class="submit-long-input" value="<?=$file?>" placeholder="文件名，不能为空" name="file">
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">内容：</div>
							<div class="submit-select-group-select col-md-8">
								<textarea class="submit-textarea" style="width: 100%;height: 450px;" name="html"><?=$html?></textarea>
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-btn col-md-offset-2 col-md-8">
								<input name="dir" type="hidden" value="<?=$dir?>">
								<button class="blue-white-btn submit-btn" type="submit">保存</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</body>
</html>