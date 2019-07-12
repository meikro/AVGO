<title>添加会员</title>
<script type="text/javascript" src="<?=Base_Path?>datepicker/wdatepicker.js"></script>
</head>
<body>
	<div class="content-page edit">
		<form action="<?=links('card','save')?>" method="post" class="form form-horizontal" id="form-article-add">
			<div class="content-page-tab-box">
				<div class="control-page">
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">生成张数：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" value="10" placeholder="卡密生成张数" name="nums">
							<label class="label-tip text-gray">卡密生成张数</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">类型：</div>
						<div class="submit-select-group-select col-md-8">
							<select class="submit-select" id="cid" name="cid" onchange="get_type();">
								<option value="0">金币卡</option>
								<option value="1">VIP卡</option>
							</select>
							<label class="label-tip text-gray">卡密类型</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row" id="cion">
						<div class="submit-select-group-tab-title col-md-2">金币数量：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" value="100" placeholder="卡密金币数量" name="cion">
							<label class="label-tip text-gray">金币数量</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row" id="day" style="display: none;">
						<div class="submit-select-group-tab-title col-md-2">VIP天数：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" value="30" placeholder="卡密VIP天数" name="day">
							<label class="label-tip text-gray">卡密VIP天数</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-btn col-md-offset-2 col-md-8">
							<button class="blue-white-btn submit-btn" type="submit">保存</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
<script type="text/javascript">
function get_type(){
    var cid = $('#cid');
    if(cid==0){
        $('#cion').show();
        $('#day').hide();
    }else{
        $('#cion').hide();
        $('#day').show();
    }
}
</script>
</body>
</html>