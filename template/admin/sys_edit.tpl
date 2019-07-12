<title>管理员编辑</title>
</head>
<body>
	<div class="content-page edit">
		<form action="<?=links('sys','save')?>" method="post" class="form form-horizontal" id="form-article-add">
			<div class="content-page-tab-box">
				<div class="control-page">
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">账号：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" value="<?=$name?>" placeholder="会员账号" name="name">
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">密码：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" value="" placeholder="<?=$id==0?'密码':'不修改，请留空';?>" name="pass">
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">昵称：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" value="<?=$nichen?>" placeholder="昵称" name="nichen">
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