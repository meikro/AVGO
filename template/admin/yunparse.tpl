<title>解析设置</title>
</head>
<body>
	<div class="content-page">
		<div class="content-page-nav">
			<a class="nav-list"><i class="icon index-icon pull-left"></i>首页</a><a class="nav-list"> > 系统配置 </a><a class="nav-list"> > 解析配置 </a>
		</div>
		<form action="<?=links('yunparse','save')?>" method="post" class="form form-horizontal" id="form-article-add">
			<div class="content-page-tab-box">
				<div class="tab-group">
					<div class="tab active" title="page_a">解析配置</div>
				</div>
				<div class="control-page">
					<div id="page_a">
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">会员UID：</div>
							<div class="submit-select-group-select col-md-8">
								<input type="text" class="submit-long-input" name="USER_ID" value="<?=USER_ID?>">
								<label class="label-tip text-gray">
									获取地址：<a href="http://120.27.155.106/user" target="_blank">http://120.27.155.106/user</a>
								</label>
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">会员token：</div>
							<div class="submit-select-group-select col-md-8">
								<input type="text" class="submit-long-input" name="USER_TOKEN" value="<?=USER_TOKEN?>">
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">防盗链域名：</div>
							<div class="submit-select-group-select col-md-8">
								<input type="text" class="submit-long-input" name="REFERER_URL" value="<?=REFERER_URL?>">
								<label class="label-tip text-gray">多个用“|”来隔开，留空为关闭</label>
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">默认清晰度：</div>
							<div class="submit-select-group-select col-md-8">
								<select class="submit-select" name="VOD_HD">
									<option value="1"<?php if(VOD_HD==1) echo ' selected="selected"';?>>标清</option>
									<option value="2"<?php if(VOD_HD==2) echo ' selected="selected"';?>>高清</option>
									<option value="3"<?php if(VOD_HD==3) echo ' selected="selected"';?>>超清</option>
									<option value="4"<?php if(VOD_HD==4) echo ' selected="selected"';?>>原画</option>
								</select>
								<label class="label-tip text-gray">默认清晰度，没有会自动下降一级</label>
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">默认线路：</div>
							<div class="submit-select-group-select col-md-8">
								<select class="submit-select" name="VOD_API">
									<option value="1"<?php if(VOD_API==1) echo ' selected="selected"';?>>线路一</option>
									<option value="2"<?php if(VOD_API==2) echo ' selected="selected"';?>>线路二</option>
									<option value="3"<?php if(VOD_API==3) echo ' selected="selected"';?>>线路三</option>
									<option value="4"<?php if(VOD_API==4) echo ' selected="selected"';?>>线路四</option>
									<option value="5"<?php if(VOD_API==5) echo ' selected="selected"';?>>线路五</option>
								</select>
								<label class="label-tip text-gray">解析失败或者慢的时候可以尝试更换线路</label>
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">是否加密：</div>
							<div class="submit-select-group-select col-md-8">
								<select class="submit-select" name="VOD_JM">
									<option value="1"<?php if(VOD_JM==1) echo ' selected="selected"';?>>加密</option>
		 						 	<option value="0"<?php if(VOD_JM==0) echo ' selected="selected"';?>>不加密</option>
								</select>
								<label class="label-tip text-gray">视频地址是否加密，对其他解析插件无效</label>
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">加密有效期：</div>
							<div class="submit-select-group-select col-md-8">
								<input type="text" class="submit-long-input" name="VOD_TIME" value="<?=VOD_TIME?>"> 秒，
								<label class="label-tip text-gray">建议不小于网站缓存时间，域名有CDN的建议根据你的CDN有效期设置，0为永久有效。</label>
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-btn col-md-offset-2 col-md-8">
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
