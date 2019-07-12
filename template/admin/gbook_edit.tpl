<title>留言回复</title>
</head>
<body>
	<div class="content-page edit">
		<form action="<?=links('gbook','save')?>" method="post" class="form form-horizontal" id="form-article-add">
			<div class="content-page-tab-box">
				<div class="control-page">
					<div id="page_a">
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">留言者：</div>
							<div class="submit-select-group-select col-md-8">
								<input type="text" class="submit-long-input" value="<?=$name?>" placeholder="留言者，不能为空" name="name">
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">留言内容：</div>
							<div class="submit-select-group-select col-md-8">
								<textarea class="submit-textarea" style="height: 100px;" name="content" placeholder="留言内容不能为空"><?=$content?></textarea>
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">回复内容：</div>
							<div class="submit-select-group-select col-md-8">
								<textarea class="submit-textarea" style="height: 100px;" name="hfcontent" placeholder="回复内容"><?=$hfcontent?></textarea>
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">状态：</div>
							<div class="submit-select-group-select col-md-8">
								<select class="submit-select" name="yid">
									<option value="1"<?php if($yid==1) echo ' selected';?>>待审核</option>
									<option value="0"<?php if($yid==0) echo ' selected';?>>已审核</option>
								</select>
								<label class="label-tip text-gray">页面状态</label>
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