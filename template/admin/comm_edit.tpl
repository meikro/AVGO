<title>添加文章</title>
</head>
<body>
	<div class="content-page edit">
		<form action="<?=links('comm','save')?>" method="post" class="form form-horizontal" id="form-article-add">
			<div class="content-page-tab-box">
				<div class="control-page">
					<div id="page_a">
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">标题：</div>
							<div class="submit-select-group-select col-md-8">
								<input type="text" class="submit-long-input" value="<?=$title?>" placeholder="标题" name="title">
								<label class="label-tip text-gray">文章标题，不能为空</label>
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">内容：</div>
							<div class="submit-select-group-select col-md-8">
								<textarea class="submit-textarea pull-left" id="text" name="content" style="width: 100%;height: 540px;color:#fff;"><?=remove_xss(str_decode($content))?></textarea>
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">圈子：</div>
							<div class="submit-select-group-select col-md-8">
								<select class="submit-select" name="cid">
						      	<?php foreach($circle as $row):?>
									<option value="<?=$row['id']?>"<?php if($cid==$row['id']) echo ' selected';?>><?=$row['name']?></option>
								<?php endforeach;?>
								</select>
								<label class="label-tip text-gray">所属圈子分类</label>
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
<link rel="stylesheet" href="<?=Base_Path?>layui/css/layui.css">
<script src="<?=Base_Path?>layui/layui.all.js"></script>
</body>
</html>
