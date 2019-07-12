<title>添加播放器</title>
</head>
<body>
	<div class="content-page edit">
		<form action="<?=links('player','save')?>" method="post" class="form form-horizontal" id="form-article-add">
			<div class="content-page-tab-box">
				<div class="control-page">
					<div id="page_a">
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">名称：</div>
							<div class="submit-select-group-select col-md-8">
								<input type="text" class="submit-long-input" name="name" value="<?=$name?>" placeholder="分类名称">
								<label class="label-tip text-gray">播放器名字</label>
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">标示：</div>
							<div class="submit-select-group-select col-md-8">
								<input type="text" class="submit-long-input" name="bs" placeholder="只能字母、数字、下划线" value="<?=$bs?>">
								<label class="label-tip text-gray">只能字母、数字、下划线</label>
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">排序：</div>
							<div class="submit-select-group-select col-md-8">
								<input type="text" class="submit-long-input" name="xid" placeholder="越小越靠前" value="<?=$xid?>">
								<label class="label-tip text-gray">排序，越小越前</label>
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">介绍：</div>
							<div class="submit-select-group-select col-md-8">
								<input type="text" class="submit-long-input" name="text" placeholder="简单介绍" value="<?=$text?>">
								<label class="label-tip text-gray">简单介绍</label>
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">代码：</div>
							<div class="submit-select-group-select col-md-8">
								<textarea class="submit-textarea pull-left" style="height:100px;" name="js"><?=$js?></textarea>
								<label class="label-tip text-gray">可用标签：视频地址 {url}，网站路径 {ctcms_path}</label>
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
</body>
</html>
