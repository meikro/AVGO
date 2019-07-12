<title>视频分类编辑</title>
</head>
<body>
	<div class="content-page edit">
		<form action="<?=links('vod','lists_save')?>" method="post" class="form form-horizontal" id="form-article-add">
			<div class="content-page-tab-box">
				<div class="control-page">
					<div id="page_a">
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">上级栏目：</div>
							<div class="submit-select-group-select col-md-8">
								<select class="submit-select" name="fid">
									<option value="0">顶级分类</option>
									<?php
									foreach ($array as $row) {
									    $clas = ($fid==$row->id) ? ' selected="selected"' : '';
									    echo '<option value="'.$row->id.'"'.$clas.'>'.$row->name.'</option>';
									}
									?>
								</select>
								<label class="label-tip text-gray">所属大类</label>
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">分类名称：</div>
							<div class="submit-select-group-select col-md-8">
								<input type="text" class="submit-long-input" name="name" value="<?=$name?>" placeholder="分类名称">
								<label class="label-tip text-gray">分类名字</label>
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">模板：</div>
							<div class="submit-select-group-select col-md-8">
								<input type="text" class="submit-long-input" name="skin" placeholder="列表模板" value="<?=$skin?>">
								<label class="label-tip text-gray">列表页模板</label>
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">排序：</div>
							<div class="submit-select-group-select col-md-8">
								<input type="text" class="submit-long-input" name="xid" placeholder="越小越靠前" value="<?=$xid?>">
								<label class="label-tip text-gray">分类排序，越小越前</label>
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">SEO标题：</div>
							<div class="submit-select-group-select col-md-8">
								<input type="text" class="submit-long-input" name="title" placeholder="SEO标题，可留空" value="<?=$title?>">
								<label class="label-tip text-gray">列表页SEO标题</label>
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">SEO关键词：</div>
							<div class="submit-select-group-select col-md-8">
								<input type="text" class="submit-long-input" name="keywords" placeholder="SEO关键词，可留空" value="<?=$keywords?>">
								<label class="label-tip text-gray">列表页SEO关键词</label>
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">SEO描述：</div>
							<div class="submit-select-group-select col-md-8">
								<input type="text" class="submit-long-input" name="description" placeholder="SEO描述，可留空" value="<?=$description?>">
								<label class="label-tip text-gray">列表页SEO描述</label>
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
