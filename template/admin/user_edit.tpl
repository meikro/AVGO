<title>添加会员</title>
<script type="text/javascript" src="<?=Base_Path?>datepicker/wdatepicker.js"></script>
</head>
<body>
	<div class="content-page edit">
		<form action="<?=links('user','save')?>" method="post" class="form form-horizontal" id="form-article-add">
			<div class="content-page-tab-box">
				<div class="control-page">
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">账号：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" value="<?=$name?>" placeholder="会员账号" name="name">
							<label class="label-tip text-gray">会员登录账号</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">密码：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" value="" placeholder="<?=$id==0?'密码':'不修改，请留空';?>" name="pass">
							<label class="label-tip text-gray">会员登录密码</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">邮箱：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" value="<?=$email?>" placeholder="联系邮箱" name="email">
							<label class="label-tip text-gray">联系邮箱</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">手机：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" value="<?=$tel?>" placeholder="手机号码" name="tel">
							<label class="label-tip text-gray">手机号码</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">佣金：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" value="<?=$rmb?>" placeholder="剩余佣金" name="rmb">
							<label class="label-tip text-gray">剩余佣金</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">金币：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" value="<?=$cion?>" placeholder="剩余金币" name="cion">
							<label class="label-tip text-gray">剩余金币</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">级别：</div>
						<div class="submit-select-group-select col-md-8">
							<select class="submit-select" name="vip">
								<option value="0"<?php if($vip==0) echo ' selected';?>>普通会员</option>
								<option value="1"<?php if($vip==1) echo ' selected';?>>VIP会员</option>
							</select>
							<label class="label-tip text-gray">会员级别</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">VIP到期时间：</div>
						<div class="submit-select-group-select col-md-8">
							<input onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" type="text" class="submit-long-input" value="<?=!empty($viptime)?date('Y-m-d H:i:s',$viptime):'';?>" placeholder="VIP到期时间" name="viptime">
							<label class="label-tip text-gray">VIP到期时间</label>
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