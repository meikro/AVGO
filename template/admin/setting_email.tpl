<title>邮件设置</title>
</head>
<body>
	<div class="content-page">
	    <div class="content-page-nav">
	        <a class="nav-list"><i class="icon index-icon pull-left"></i>首页</a><a class="nav-list"> > 系统配置 </a><a class="nav-list"> > 邮件设置 </a>
	    </div>
			<form action="<?=links('setting','email_save')?>" method="post" class="form form-horizontal" id="form-article-add">
			    <div class="content-page-tab-box">
			        <div class="tab-group">
			            <div class="tab active" title="page_a">邮件配置</div>
			        </div>
			        <div class="control-page">
			            <div id="page_a">
			                <div class="content-page-submit-select-group row">
			                    <div class="submit-select-group-tab-title col-md-2">SMTP服务器：</div>
			                    <div class="submit-select-group-select col-md-8">
			                        <input type="text" class="submit-long-input" name="CT_Smtphost" value="<?=CT_Smtphost?>">
			                    </div>
			                </div>
			                <div class="content-page-submit-select-group row">
			                    <div class="submit-select-group-tab-title col-md-2">SMTP端口：</div>
			                    <div class="submit-select-group-select col-md-8">
			                        <input type="text" class="submit-long-input" name="CT_Smtpport" value="<?=CT_Smtpport?>">
			                    </div>
			                </div>
			                <div class="content-page-submit-select-group row">
			                    <div class="submit-select-group-tab-title col-md-2">SMTP帐号：</div>
			                    <div class="submit-select-group-select col-md-8">
			                        <input type="text" class="submit-long-input" name="CT_Smtpuser" value="<?=CT_Smtpuser?>">
			                    </div>
			                </div>
			                <div class="content-page-submit-select-group row">
			                    <div class="submit-select-group-tab-title col-md-2">SMTP密码：</div>
			                    <div class="submit-select-group-select col-md-8">
			                        <input type="text" class="submit-long-input" name="CT_Smtppass" value="<?=CT_Smtppass?>">
			                        <!-- <label class="label-tip text-gray">留空则调用程序自带yunparse解析</label> -->
			                    </div>
			                </div>
			                <div class="content-page-submit-select-group row">
			                    <div class="submit-select-group-tab-title col-md-2">发送EMAIL：</div>
			                    <div class="submit-select-group-select col-md-8">
			                        <input type="text" class="submit-long-input" name="CT_Smtpmail" value="<?=CT_Smtpmail?>">
			                        <!-- <label class="label-tip text-gray">APP未开启评论可以留空</label> -->
			                    </div>
			                </div>
			                <div class="content-page-submit-select-group row">
			                    <div class="submit-select-group-tab-title col-md-2">发送者名称：</div>
			                    <div class="submit-select-group-select col-md-8">
			                        <input type="text" class="submit-long-input" name="CT_Smtpname" value="<?=CT_Smtpname?>">
			                        <!-- <label class="label-tip text-gray">APP未开启评论可以留空</label> -->
			                    </div>
			                </div>
			                <div class="content-page-submit-select-group row">
			                    <div class="submit-select-group-btn col-md-offset-2 col-md-8">
			                        <button class="blue-white-btn submit-btn" type="submit">提交</button>
			                    </div>
			                </div>
			            </div>
			        </div>
			    </div>
				</form>
	</div>
</body>
</html>
