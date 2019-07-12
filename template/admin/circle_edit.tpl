<title>添加圈子</title>
</head>
<body>
	<div class="content-page edit">
		<form action="<?=links('circle','save')?>" method="post" class="form form-horizontal" id="form-article-add">
			<div class="content-page-tab-box">
				<div class="control-page">
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">名称：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" value="<?=$name?>" placeholder="圈子名称" name="name">
							<label class="label-tip text-gray">圈子名称，不能为空</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">排序：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" value="<?=$xid?>" placeholder="序号" name="xid">
							<label class="label-tip text-gray">圈子分类排序，越小越前</label>
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
</body>
</html>
