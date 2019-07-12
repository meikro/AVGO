<title>APP配置</title>
</head>
<body>
<div class="content-page">
    <div class="content-page-nav">
        <a class="nav-list"><i class="icon index-icon pull-left"></i>首页</a><a class="nav-list"> > 系统配置 </a><a class="nav-list"> > APP配置 </a>
    </div>
		<form action="<?=links('app','save')?>" method="post" class="form form-horizontal" id="form-article-add">
		    <div class="content-page-tab-box">
		        <div class="tab-group">
		            <div class="tab active" title="page_a">APP配置</div>
		        </div>
		        <div class="control-page">
		            <div id="page_a">
		                <div class="content-page-submit-select-group row">
		                    <div class="submit-select-group-tab-title col-md-2">APP版本号：</div>
		                    <div class="submit-select-group-select col-md-8">
		                        <input type="text" class="submit-long-input" name="CT_App_Ver" value="<?=CT_App_Ver?>">
		                    </div>
		                </div>
		                <div class="content-page-submit-select-group row">
		                    <div class="submit-select-group-tab-title col-md-2">新版本升级地址：</div>
		                    <div class="submit-select-group-select col-md-8">
		                        <input type="text" class="submit-long-input" name="CT_App_Uplink" value="<?=CT_App_Uplink?>">
		                    </div>
		                </div>
		                <div class="content-page-submit-select-group row">
		                    <div class="submit-select-group-tab-title col-md-2">点卡购买地址：</div>
		                    <div class="submit-select-group-select col-md-8">
		                        <input type="text" class="submit-long-input" name="CT_App_Kalink" value="<?=CT_App_Kalink?>">
		                    </div>
		                </div>
		                <div class="content-page-submit-select-group row">
		                    <div class="submit-select-group-tab-title col-md-2">解析地址：</div>
		                    <div class="submit-select-group-select col-md-8">
		                        <input type="text" class="submit-long-input" name="CT_App_Jxurl" value="<?=CT_App_Jxurl?>">
		                        <label class="label-tip text-gray">留空则调用程序自带yunparse解析</label>
		                    </div>
		                </div>
		                <div class="content-page-submit-select-group row">
		                    <div class="submit-select-group-tab-title col-md-2">畅言APP：</div>
		                    <div class="submit-select-group-select col-md-8">
		                        <input type="text" class="submit-long-input" name="CT_App_Cyid" value="<?=CT_App_Cyid?>">
		                        <label class="label-tip text-gray">APP未开启评论可以留空</label>
		                    </div>
		                </div>
		                <div class="content-page-submit-select-group row">
		                    <div class="submit-select-group-tab-title col-md-2">畅言APPKEY：</div>
		                    <div class="submit-select-group-select col-md-8">
		                        <input type="text" class="submit-long-input" name="CT_App_Cykey" value="<?=CT_App_Cykey?>">
		                        <label class="label-tip text-gray">APP未开启评论可以留空</label>
		                    </div>
		                </div>
		                <div class="content-page-submit-select-group row">
		                    <div class="submit-select-group-tab-title col-md-2">收费试看时间：</div>
		                    <div class="submit-select-group-select col-md-8">
		                        <input type="text" class="submit-long-input" name="CT_App_Sktime" value="<?=CT_App_Sktime?>">
		                        <label class="label-tip text-gray">分钟</label>
		                    </div>
		                </div>
						<?php  $parr = explode('|', CT_App_Paytype);?>
		                <div class="content-page-submit-select-group row">
		                    <div class="submit-select-group-tab-title col-md-2">APP支付方式：</div>
		                    <div class="submit-select-group-select col-md-8">
		                      <span class="select-tab-option">
                              <label><input class="box" name="CT_App_Paytype[]" type="checkbox" value="alipay"<?php if(in_array('alipay', $parr)) echo ' checked';?>> 支付宝</label>
                            </span>
		                      <span class="select-tab-option">
                              <label><input class="box" name="CT_App_Paytype[]" type="checkbox" value="wxpay"<?php if(in_array('wxpay', $parr)) echo ' checked';?>> 微信</label>
                            </span>
		                      <span class="select-tab-option">
                              <label><input class="box" name="CT_App_Paytype[]" type="checkbox" value="qqpay"<?php if(in_array('qqpay', $parr)) echo ' checked';?>> QQ</label>
                            </span>
		                      <span class="select-tab-option">
                              <label><input class="box" name="CT_App_Paytype[]" type="checkbox" value="wypay"<?php if(in_array('wypay', $parr)) echo ' checked';?>> 网银</label>
                            </span>
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
