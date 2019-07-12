<title>添加资源库</title>
</head>
<body>
	<div class="content-page edit">
		<form action="<?=links('caiji','save',$op)?>" method="post" class="form form-horizontal" id="form-article-add">
			<div class="content-page-tab-box">
				<div class="control-page">
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">资源库名称：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" value="<?=$name?>" placeholder="资源库名称" name="name">
							<label class="label-tip text-gray">资源库名称</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">资源库地址：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" value="<?=$apiurl?>" placeholder="也支持base64加密后的地址" name="apiurl">
							<label class="label-tip text-gray">资源库地址</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">资源库标示：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" value="<?=$ac?>" placeholder="唯一标示、只能是数字或者字母" name="ac">
							<label class="label-tip text-gray">资源库标示</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">资源库类型：</div>
						<div class="submit-select-group-select col-md-8">
							<select class="submit-select" name="type">
								<option value="play"<?php if($type=='play') echo ' selected';?>>播放资源</option>
								<option value="down"<?php if($type=='down') echo ' selected';?>>下载资源</option>
							</select>
							<label class="label-tip text-gray">资源库类型</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">资源库结构：</div>
						<div class="submit-select-group-select col-md-8">
							<select class="submit-select" name="form">
								<option value="xml"<?php if($form=='xml') echo ' selected';?>>xml</option>
								<option value="json"<?php if($form=='json') echo ' selected';?>>json</option>
							</select>
							<label class="label-tip text-gray">资源库结构，一般为xml</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">资源库介绍：</div>
						<div class="submit-select-group-select col-md-8">
							<textarea name="info" class="submit-textarea" placeholder="一句话描述资源库" style="height:80px;"><?=$info?></textarea>
							<label class="label-tip text-gray">一句话简单描述</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-btn col-md-offset-2 col-md-8">
							<input name="k" type="hidden" value="<?=$k?>">
							<button class="blue-white-btn submit-btn" type="submit">保存</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</body>
</html>