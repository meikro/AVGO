<title>添加资源站点</title>
</head>
<body>
	<div class="content-page edit">
		<form action="<?=links('caiji','type_save',$op)?>" method="post" class="form form-horizontal" id="form-article-add">
			<div class="content-page-tab-box">
				<div class="control-page">
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">资源名称：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" value="<?=$name?>" placeholder="资源库名称" name="name">
							<label class="label-tip text-gray">资源名称</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">资源地址：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" value="<?=$apiurl?>" placeholder="留空则继承上级地址" name="apiurl">
							<label class="label-tip text-gray">资源地址,留空则继承上级地址</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">资源rid：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" value="<?=$rid?>" placeholder="资源ID,标示rid" name="rid">
							<label class="label-tip text-gray">资源ID,标示rid</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-btn col-md-offset-2 col-md-8">
					        <input name="k" type="hidden" value="<?=$k?>">
					        <input name="n" type="hidden" value="<?=$n?>">
							<button class="blue-white-btn submit-btn" type="submit">保存</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</body>
</html>