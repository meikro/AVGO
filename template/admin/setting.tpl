<title>系统配置</title>
</head>
<body>
<div class="content-page">
    <div class="content-page-nav">
        <a class="nav-list"><i class="icon index-icon pull-left"></i>首页</a><a class="nav-list"> > 系统配置 </a><a class="nav-list"> > 基本配置 </a>
    </div>
		<form action="<?=links('setting','save')?>" method="post" class="form form-horizontal" id="form-article-add">
    <div class="content-page-tab-box">
        <div class="tab-group">
            <div class="tab active" title="page_a">基本配置</div>
            <div class="tab" title="page_b">运行模式</div>
            <div class="tab" title="page_c">安全配置</div>
            <div class="tab" title="page_d">会员配置</div>
            <div class="tab" title="page_e">留言配置</div>
            <div class="tab" title="page_f">附件配置</div>
            <div class="tab" title="page_g">其它配置</div>
            <div class="tab" title="page_h">手机配置</div>
            <div class="tab" title="page_i">三级分销</div>
            <div class="tab" title="page_j">QQ微信登录</div>
            <div class="tab" title="page_k">采集入库配置</div>
        </div>
        <div class="control-page">
            <div id="page_a">
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2"><label class="text-red">*</label>网站名称：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input type="text" class="submit-long-input" name="Web_Name" value="<?=Web_Name?>">
                        <label class="label-tip text-gray">网站名称</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2"><label class="text-red">*</label>网站域名：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input type="text" class="submit-long-input" name="Web_Url" value="<?=Web_Url?>">
                        <label class="label-tip text-gray">网站域名，不要带http://</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2"><label class="text-red">*</label>网站目录：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input type="text" class="submit-long-input" name="Web_Path" value="<?=Web_Path?>">
                        <label class="label-tip text-gray">网站目录</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">页面缓存开关：</div>
                    <div class="submit-select-group-select col-md-8">
                        <select class="submit-select" name="Cache_Is">
							<option value="0"<?php if(Cache_Is==0) echo ' selected="selected"';?>>关闭</option>
                          	<option value="1"<?php if(Cache_Is==1) echo ' selected="selected"';?>>开启</option>
						</select>
                        <label class="label-tip text-gray">此功能可以达到生成静态网页功能，需要更多储存空间</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">缓存有效期：</div>
                    <div class="submit-select-group-select col-md-8">
                        <select class="submit-select" name="Cache_Time">
							<option value="600">600</option>
							<option value="1800">1800</option>
							<option value="3600">3600</option>
							<option value="4800">4800</option>
						</select>
                        <label class="label-tip text-gray">更新缓存间隔时间，单位-秒</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">视频地区：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input type="text" class="submit-long-input" name="Web_Diqu" value="<?=Web_Diqu?>">
                        <label class="label-tip text-gray">多个用|来隔开</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">视频语言：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input type="text" class="submit-long-input" name="Web_Yuyan" value="<?=Web_Yuyan?>">
                        <label class="label-tip text-gray">多个用|来隔开</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">视频年份：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input type="text" class="submit-long-input" name="Web_Year" value="<?=Web_Year?>">
                        <label class="label-tip text-gray">多个用|来隔开</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">视频分类：</div>
                    <div class="submit-select-group-select col-md-8">
                        <textarea name="Web_Type" class="submit-textarea" style="margin-right: 4px"><?=Web_Type?></textarea>
                        <label class="label-tip text-gray">多个用|来隔开</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-btn col-md-offset-2 col-md-8">
                        <button class="blue-white-btn submit-btn" type="submit">提交</button>
                    </div>
                </div>
            </div>
            <div id="page_b" style="display: none">
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">网站运行模式：</div>
                    <div class="submit-select-group-select col-md-8">
                        <select class="submit-select"  name="Web_Mode" style="width:190px;">
													<option value="1"<?php if(Web_Mode==1) echo ' selected="selected"';?>>PATHINFO模式</option>
                         	<option value="2"<?php if(Web_Mode==2) echo ' selected="selected"';?>>QUERY_STRING模式</option>
												</select>
                        <label class="label-tip text-gray">Nginx 环境下建议不要使用PATHINFO模式</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">静态生成：</div>
                    <div class="submit-select-group-select col-md-8">
                        <select id="htmls" name="Html_Off" class="submit-select" onchange="get_html();">
													<option value="1"<?php if(Html_Off==1) echo ' selected="selected"';?>>开启</option>
                         	<option value="0"<?php if(Html_Off==0) echo ' selected="selected"';?>>关闭</option>
												</select>
                        <label class="label-tip text-gray">是否使用生成纯静态</label>
                    </div>
                </div>
                <div id='html' class='content-page-submit-select-group row' <?php if(Html_Off==0) echo 'style="display:none;"';?>>
					<div class="content-page-submit-select-group row">
	                    <div class="submit-select-group-tab-title col-md-2">生成目录：</div>
	                    <div class="submit-select-group-select col-md-8">
	                        <input type="text" class="submit-long-input" name="Html_Dir" value="<?=Html_Dir?>">
	                        <label class="label-tip text-gray">如：留空是网站根目录，或者如：D:/wap/</label>
	                    </div>
	                </div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">访问地址：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" name="Html_Url" value="<?=Html_Url?>">
							<label class="label-tip text-gray">生成目录不为空时则必填，如：http://m.ctcms.cn/</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">网站主页URL：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" name="Html_Index" value="<?=Html_Index?>">
							<!-- <label class="label-tip text-gray"></label> -->
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">视频列表页URL：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" name="Html_List" value="<?=Html_List?>">
							<label class="label-tip text-gray">可用标签：分类ID：[id]、分页ID：[page]</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">视频内容页URL：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" name="Html_Show" value="<?=Html_Show?>">
							<label class="label-tip text-gray">可用标签：视频ID：[id]</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">视频播放页URL：</div>
						<div class="submit-select-group-select col-md-4">
							<input type="text" class="submit-long-input" name="Html_Play" value="<?=Html_Play?>">
						</div>
						<div class="submit-select-group-select col-md-2">
        					<select class="submit-select" name="Html_Play_Off">
									<option value="1"<?php if(Html_Play_Off==1) echo ' selected="selected"';?>>开启静态生成</option>
									<option value="0"<?php if(Html_Play_Off==0) echo ' selected="selected"';?>>关闭静态生成</option>
							</select>
    					</div>
                  		<label class="label-tip text-gray" style="padding-left: 280px;">
	                    可用标签：视频ID：[id]、视频组：[zu]、视频集数：[ji],播放页生成静态会大幅增加生成时间和磁盘占用，除非访问量过大的站点、否则不建议开启
	                  	</label>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">专题列表页URL：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" name="Html_Topic" value="<?=Html_Topic?>">
							<label class="label-tip text-gray">可用标签：分页ID：[page]</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">专题内容页URL：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" name="Html_Topic_Show" value="<?=Html_Topic_Show?>">
							<label class="label-tip text-gray">可用标签：专题ID：[id]</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">文章列表页URL：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" name="Html_News_List" value="<?=Html_News_List?>">
							<label class="label-tip text-gray">可用标签：分类ID：[id]、分页ID：[page]</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">文章内容页URL：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" name="Html_News_Show" value="<?=Html_News_Show?>">
							<label class="label-tip text-gray">可用标签：文章ID：[id]、分页ID：[page]</label>
						</div>
					</div>
                </div>
				<div id="url" <?php if(Html_Off==1) echo ' style="display:none;"';?>>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">伪静态开关：</div>
						<div class="submit-select-group-select col-md-8">
							<select class="submit-select" id="uris" name="Uri_Mode" onchange="get_uri();">
								<option value="0"<?php if(Uri_Mode==0) echo ' selected="selected"';?>>关闭</option>
								<option value="1"<?php if(Uri_Mode==1) echo ' selected="selected"';?>>开启</option>
							</select>
							<label class="label-tip text-gray">伪静态URL路由，开启后可以美化URL地址，需要配合伪静态规则</label>
						</div>
					</div>
					<div id="uri"<?php if(Uri_Mode==0) echo ' style="display:none;"';?>>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">视频分类路由规则：</div>
							<div class="submit-select-group-select col-md-8">
								<input type="text" class="submit-long-input" name="Uri_List" value="<?=Uri_List?>">
								<label class="label-tip text-gray">可以用标签，[cid]分类ID，[page]分页ID</label>
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">视频内容路由规则：</div>
							<div class="submit-select-group-select col-md-8">
								<input type="text" class="submit-long-input" name="Uri_Show" value="<?=Uri_Show?>">
								<label class="label-tip text-gray"> 可以用标签，[id]视频ID</label>
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">视频播放路由规则：</div>
							<div class="submit-select-group-select col-md-8">
								<input type="text" class="submit-long-input" name="Uri_Play" value="<?=Uri_Play?>">
								<label class="label-tip text-gray">可以用标签，[id]视频ID，[zu]组ID，[ji]集数ID</label>
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">视频专题列表规则：</div>
							<div class="submit-select-group-select col-md-8">
								<input type="text" class="submit-long-input" name="Uri_Topic" value="<?=Uri_Topic?>">
								<label class="label-tip text-gray">可以用标签，[page]分页ID</label>
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">视频专题内容规则：</div>
							<div class="submit-select-group-select col-md-8">
								<input type="text" class="submit-long-input" name="Uri_Topic_Show" value="<?=Uri_Topic_Show?>">
								<label class="label-tip text-gray">可以用标签，[id]视频ID</label>
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">圈子分类路由规则：</div>
							<div class="submit-select-group-select col-md-8">
								<input type="text" class="submit-long-input" name="Uri_Comm" value="<?=Uri_Comm?>">
								<label class="label-tip text-gray">可以用标签，[cid]分类ID，[page]分页ID</label>
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">圈子内容路由规则：</div>
							<div class="submit-select-group-select col-md-8">
								<input type="text" class="submit-long-input" name="Uri_Article" value="<?=Uri_Article?>">
								<label class="label-tip text-gray">可以用标签，[id]视频ID，[page]分页ID</label>
							</div>
						</div>
						<div class="content-page-submit-select-group row">
							<div class="submit-select-group-tab-title col-md-2">伪静态规则：</div>
							<div class="submit-select-group-select col-md-8">
		                        <div class="submit-div pull-left">
		                            <div class="text-red">1.开启URL路由后，您的主机必须支持伪静态，否则无法访问。</div>
		                            <div class="text-red">2.伪静态规则，请访问：http://www.ctcms.cn/ 获取。</div>
		                        </div>
                    		</div>
						</div>
					</div>
				</div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-btn col-md-offset-2 col-md-8">
                        <button class="blue-white-btn submit-btn" type="submit">提交</button>
                    </div>
                </div>
            </div>
            <div id="page_c" style="display: none">
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">网站开关：</div>
                    <div class="submit-select-group-select col-md-8">
                        <select class="submit-select" name="Web_Off">
							<option value="0"<?php if(Web_Off==0) echo ' selected="selected"';?>>开启</option>
                         	<option value="1"<?php if(Web_Off==1) echo ' selected="selected"';?>>关闭</option>
						</select>
                        <label class="label-tip text-gray">站点关闭后不能访问</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">网站关闭提示：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input type="text" class="submit-long-input" name="Web_Onneir">
                        <label class="label-tip text-gray">5个左右,8汉字以内,用英文,隔开</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2"><a class="text-red">*</a>后台验证码：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input type="text" class="submit-input" name="Admin_Code" value="<?=Admin_Code?>">
                        <label class="label-tip text-gray">可后台登陆认证码</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2"><a class="text-red">*</a>日志保存天数：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input type="text" class="submit-input" name="Admin_Log_Day" value="<?=Admin_Log_Day?>">
                        <label class="label-tip text-gray">后台登陆数日志保存天数</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">允许访问后台的IP列表：</div>
                    <div class="submit-select-group-select col-md-8">
                        <textarea class="submit-textarea pull-left" style="height: 74px;margin-right: 4px" name="Admin_Log_Ip"><?=Admin_Log_Ip?></textarea>
                        <label class="label-tip text-gray">多个用|来分割</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">备案号：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input type="text" class="submit-long-input" name="Web_Icp" value="<?=Web_Icp?>">
                        <label class="label-tip text-gray">网站底部显示的备案号</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">客服QQ：</div>
                    <div class="submit-select-group-select col-md-8" >
                        <input type="text" class="submit-long-input" name="Admin_QQ" value="<?=Admin_QQ?>">
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">客服邮箱：</div>
                    <div class="submit-select-group-select col-md-8" >
                        <input type="text" class="submit-long-input" name="Admin_Mail" value="<?=Admin_Mail?>">
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-btn col-md-offset-2 col-md-8">
                        <button class="blue-white-btn submit-btn" type="submit">提交</button>
                    </div>
                </div>
            </div>
            <div id="page_d" style="display: none">
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">会员开关：</div>
                    <div class="submit-select-group-select col-md-8">
                        <select class="submit-select" name="User_Off">
							<option value="0"<?php if(User_Off==0) echo ' selected="selected"';?>>开启</option>
                         	<option value="1"<?php if(User_Off==1) echo ' selected="selected"';?>>关闭</option>
						</select>
                        <label class="label-tip text-gray">关闭后不能注册登录</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">会员关闭提示：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input type="text" class="submit-long-input" name="User_Onneir" value="<?=User_Onneir?>">
                        <label class="label-tip text-gray">5个左右,8汉字以内,用英文,隔开</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2"><a class="text-red">*</a>注册赠送金币：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input type="text" class="submit-input" name="User_Reg_Cion" value="<?=User_Reg_Cion?>">
                        <label class="label-tip text-gray">注册赠送金币</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2"><a class="text-red">*</a>登录赠送金币：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input type="text" class="submit-input" name="User_Log_Cion" value="<?=User_Log_Cion?>">
                        <label class="label-tip text-gray">每天登陆赠送金币</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2"><a class="text-red">*</a>签到赠送金币：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input class="submit-input" type="text" name="User_Qd_Cion" value="<?=User_Qd_Cion?>">
                        <label class="label-tip text-gray"> 每天签到赠送金币</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">会员默认模板：</div>
                    <div class="submit-select-group-select col-md-8">
                        <select class="submit-select" name="User_Skin">
							<?php
							foreach ($userskin as $dir) {
							    $sel = (User_Skin==$dir) ? ' selected="selected"' : '';
							    echo '<option value="'.$dir.'"'.$sel.'>'.$dir.'</option>';
							}
							?>
						</select>
                        <label class="label-tip text-gray">前台默认模板，模板位于 template/user/ 目录下</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-btn col-md-offset-2 col-md-8">
                        <button class="blue-white-btn submit-btn" type="submit">提交</button>
                    </div>
                </div>
            </div>
            <div id="page_e" style="display: none">
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">留言开关：</div>
                    <div class="submit-select-group-select col-md-8">
                        <select class="submit-select" name="Gbook_Is">
							<option value="1"<?php if(Gbook_Is==1) echo ' selected="selected"';?>>开启</option>
                         	<option value="0"<?php if(Gbook_Is==0) echo ' selected="selected"';?>>关闭</option>
						</select>
                        <label class="label-tip text-gray">关闭后不能打开留言页</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">留言登录：</div>
                    <div class="submit-select-group-select col-md-8" name="Gbook_Log">
                        <select class="submit-select">
							<option value="1"<?php if(Gbook_Log==1) echo ' selected="selected"';?>>需要</option>
                         	<option value="0"<?php if(Gbook_Log==0) echo ' selected="selected"';?>>不需要</option>
						</select>
                        <label class="label-tip text-gray">需要后留言需要登陆</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">留言审核：</div>
                    <div class="submit-select-group-select col-md-8">
                        <select class="submit-select" name="Gbook_Sh">
							<option value="1"<?php if(Gbook_Sh==1) echo ' selected="selected"';?>>需要</option>
                         	<option value="0"<?php if(Gbook_Sh==0) echo ' selected="selected"';?>>不需要</option>
						</select>
                        <label class="label-tip text-gray">需要后留言审核通过才会显示</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">留言过滤字：</div>
                    <div class="submit-select-group-select col-md-8">
                        <textarea class="submit-textarea pull-left" style="height: 74px;margin-right: 4px" name="Gbook_Str"><?=Gbook_Str?></textarea>
                        <label class="label-tip text-gray">多个用|来分割</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-btn col-md-offset-2 col-md-8">
                        <button class="blue-white-btn submit-btn" type="submit" >提交</button>
                    </div>
                </div>
            </div>
            <div id="page_f" style="display: none">
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">附件存储方式：</div>
                    <div class="submit-select-group-select col-md-8">
                        <select class="submit-select" id="ups" name="Ftp_Is" onchange="get_up();">
							<option value="0"<?php if(Ftp_Is==0) echo ' selected="selected"';?>>本地</option>
                         	<option value="1"<?php if(Ftp_Is==1) echo ' selected="selected"';?>>FTP</option>
                         	<option value="2"<?php if(Ftp_Is==2) echo ' selected="selected"';?>>贴图库</option>
						</select>
                        <label class="label-tip text-gray">附件存储方式</label>
                    </div>
                </div>
				<!-- 贴图库配置 -->
				<div id="ttk" <?php if(Ftp_Is!=2) echo ' style="display:none;"';?>>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">贴图库相册ID：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" name="Ftp_Pid" value="<?=Ftp_Pid?>">
							<label class="label-tip text-gray">贴图库相册ID,获取地址：http://www.tietuku.com/manager/album</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">贴图库Token：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" name="Ftp_Token" value="<?=Ftp_Token?>">
							<label class="label-tip text-gray">贴图库Token,获取地址：http://www.tietuku.com/manager/createtoken</label>
						</div>
					</div>
				</div>
				<!-- ftp 配置  -->
				<div id="ftp"<?php if(Ftp_Is!=1) echo ' style="display:none;"';?>>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">Ftp访问地址：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" name="Ftp_Url" value="<?=Ftp_Url?>">
							<label class="label-tip text-gray">FTP访问地址，如：http://img.abc.com/</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">Ftp连接Ip：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" name="Ftp_Server" value="<?=Ftp_Server?>">
							<label class="label-tip text-gray">FTP连接IP，如：22.58.63.24</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">Ftp连接端口：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" name="Ftp_Port" value="<?=Ftp_Port?>">
							<label class="label-tip text-gray">FTP连接端口，一般为21</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">Ftp连接账号：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" name="Ftp_User" value="<?=Ftp_User?>">
							<label class="label-tip text-gray">FTP连接账号</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">Ftp连接密码：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" name="Ftp_Pass" value="<?=Ftp_Pass?>">
							<label class="label-tip text-gray">FTP连接密码</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">Ftp上传目录：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" name="Ftp_Dir" value="<?=Ftp_Dir?>">
							<label class="label-tip text-gray">Ftp上传目录，留空为根目录</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">Ftp被动模式：</div>
						<div class="submit-select-group-select col-md-8">
							<select class="submit-select" name="Ftp_Ive">
								<option value="1"<?php if(Ftp_Ive==TRUE) echo ' selected="selected"';?>>开启</option>
 								<option value="0"<?php if(Ftp_Ive==FALSE) echo ' selected="selected"';?>>关闭</option>
							</select>
							<label class="label-tip text-gray">Ftp连接是否开启被动模式</label>
						</div>
					</div>
				</div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-btn col-md-offset-2 col-md-8">
                        <button class="blue-white-btn submit-btn" type="submit">提交</button>
                    </div>
                </div>
            </div>
            <div id="page_g" style="display: none">
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">电脑访问开关：</div>
					<?php $Web_Pc = defined('Web_Pc')? Web_Pc : 1;?>
                    <div class="submit-select-group-select col-md-8">
                        <select class="submit-select" name="Web_Pc">
							<option value="1"<?php if($Web_Pc==1) echo ' selected="selected"';?>>开启</option>
                         	<option value="0"<?php if($Web_Pc==0) echo ' selected="selected"';?>>关闭</option>
						</select>
                        <label class="label-tip text-gray">关闭电脑后不能访问站点</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">VIP会员广告开关：</div>
                    <div class="submit-select-group-select col-md-8">
                        <select class="submit-select" name="Web_Isad">
							<?php $Web_Isad = defined('Web_Isad')? Web_Isad : 0;?>
	                        <option value="0"<?php if($Web_Isad==0) echo ' selected="selected"';?>>开启</option>
	                        <option value="1"<?php if($Web_Isad==1) echo ' selected="selected"';?>>关闭</option>
						</select>
                        <label class="label-tip text-gray">关闭后VIP会员不显示广告</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">API资源开关：</div>
                    <div class="submit-select-group-select col-md-8">
                        <select class="submit-select" name="IS_Api">
							<?php $IS_Api = defined('IS_Api')? IS_Api : 0;?>
                          	<option value="1"<?php if($IS_Api==1) echo ' selected="selected"';?>>开启</option>
                          	<option value="0"<?php if($IS_Api==0) echo ' selected="selected"';?>>关闭</option>
						</select>
                        <label class="label-tip text-gray">开启后可以作为资源站,提供给别人采集，api采集地址：http://<?=Web_Url.str_replace(SELF,'index.php',links('api'))?></label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">默认模板目录：</div>
                    <div class="submit-select-group-select col-md-8">
                        <select class="submit-select" name="Web_Skin">
							<?php
							foreach ($skin as $dir) {
							    $sel = (Web_Skin==$dir) ? ' selected="selected"' : '';
							    echo '<option value="'.$dir.'"'.$sel.'>'.$dir.'</option>';
							}
							?>
						</select>
                        <label class="label-tip text-gray">前台默认模板，模板位于 template 目录下</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">插件路径：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input type="text" class="submit-long-input" name="Base_Path" value="<?=Base_Path?>">
                        <label class="label-tip text-gray">默认为/packs/，如：http://cdn.abc.com/</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">微信公众号：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input type="text" class="submit-long-input" name="Weixin" value="<?=Weixin?>">
                        <label class="label-tip text-gray">微信公众号</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">微信公众号链接：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input type="text" class="submit-long-input" name="Weixin_Url" value="<?=Weixin_Url?>">
                        <label class="label-tip text-gray">微信公众号链接</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">微信自动回复Token：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input type="text" class="submit-long-input" name="Weixin_Token" value="<?=Weixin_Token?>">
                        <label class="label-tip text-gray"> 微信自动回复Token，配置URL地址：http://<?=Web_Url.str_replace(SELF,'index.php',links('wx','code'))?></label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">网站标题：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input type="text" class="submit-long-input" name="Web_Title" value="<?=Web_Title?>">
                        <label class="label-tip text-gray">网站标题前台显示标题</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">网站关键词：</div>
                    <div class="submit-select-group-select col-md-8">
                        <textarea class="submit-textarea pull-left" style="height: 74px;margin-right: 4px" name="Web_Keywords"><?=Web_Keywords?></textarea>
                        <label class="label-tip text-gray">5个左右,8汉字以内,用英文,隔开</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">网站描述：</div>
                    <div class="submit-select-group-select col-md-8">
                        <textarea class="submit-textarea pull-left" style="height: 74px;margin-right: 4px" name="Web_Description"><?=Web_Description?></textarea>
                        <label class="label-tip text-gray">空制在80个汉字，160个字符以内</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">统计代码：</div>
                    <div class="submit-select-group-select col-md-8">
                        <textarea class="submit-textarea pull-left" style="height: 74px;margin-right: 4px"  name="Web_Count"><?=Web_Count?></textarea>
                        <label class="label-tip text-gray">第三方统计代码</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">评论代码：</div>
                    <div class="submit-select-group-select col-md-8">
                        <textarea class="submit-textarea pull-left" style="height: 74px;margin-right: 4px" name="Web_Pl"><?=Web_Pl?></textarea>
                        <label class="label-tip text-gray">第三方评论代码，{id}代表视频ID，站内评论代码：{pl}</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-btn col-md-offset-2 col-md-8">
                        <button class="blue-white-btn submit-btn" type="submit">提交</button>
                    </div>
                </div>
            </div>
            <div id="page_h" style="display: none">
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">手机版开关：</div>
                    <div class="submit-select-group-select col-md-8">
                        <select class="submit-select" name="Wap_Is">
							<option value="0"<?php if(Wap_Is==0) echo ' selected="selected"';?>>关闭</option>
						 	<option value="1"<?php if(Wap_Is==1) echo ' selected="selected"';?>>开启</option>
						</select>
                        <label class="label-tip text-gray">手机版关闭后，手机访问则是电脑版</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">手机模板目录：</div>
                    <div class="submit-select-group-select col-md-8">
                        <select class="submit-select" name="Wap_Skin">
							<?php
							foreach ($wapskin as $dir) {
							    $sel = (Wap_Skin==$dir) ? ' selected="selected"' : '';
							    echo '<option value="'.$dir.'"'.$sel.'>'.$dir.'</option>';
							}
							?>
						</select>
                        <label class="label-tip text-gray">手机默认模板，模板位于 template/mobile/ 目录下</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">会员模板目录：</div>
                    <div class="submit-select-group-select col-md-8">
                        <select class="submit-select" name="Wap_User_Skin">
							<?php
							foreach ($wapuserskin as $dir) {
							    $sel = (Wap_User_Skin==$dir) ? ' selected="selected"' : '';
							    echo '<option value="'.$dir.'"'.$sel.'>'.$dir.'</option>';
							}
							?>
						</select>
                        <label class="label-tip text-gray">会员手机默认模板，模板位于 template/mobile_user/ 目录下</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">手机版域名：</div>
                    <div class="submit-select-group-select col-md-8">
                        <input type="text" class="submit-long-input" name="Wap_Url" value="<?=Wap_Url?>">
                        <label class="label-tip text-gray">留空则不区分，自适应</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-btn col-md-offset-2 col-md-8">
                        <button class="blue-white-btn submit-btn" type="submit">提交</button>
                    </div>
                </div>
            </div>
            <div id="page_i" style="display: none">
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">三级分销开关：</div>
                    <div class="submit-select-group-select col-md-8">
                        <select class="submit-select" id="fx" name="User_Fc_Off" onchange="get_fx();">
							<option value="0"<?php if(User_Fc_Off==0) echo ' selected="selected"';?>>关闭</option>
                          	<option value="1"<?php if(User_Fc_Off==1) echo ' selected="selected"';?>>开启</option>
						</select>
                        <label class="label-tip text-gray">三级分销开关</label>
                    </div>
                </div>
				<div id="Fx_Info"<?php if(User_Fc_Off == 0) echo ' style="display:none;"';?>>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">一级分销分成：</div>
						<div class="submit-select-group-select col-md-8">
							<input name="User_Fc_1" value="<?=User_Fc_1?>" type="text" class="submit-input">
							<label class="label-tip text-gray">%</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">二级分销分成：</div>
						<div class="submit-select-group-select col-md-8">
							<input name="User_Fc_2" value="<?=User_Fc_2?>" type="text" class="submit-input">
							<label class="label-tip text-gray">%</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">三级分销分成：</div>
						<div class="submit-select-group-select col-md-8">
							<input name="User_Fc_3" value="<?=User_Fc_3?>" type="text" class="submit-input">
							<label class="label-tip text-gray">%</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">体现最小额度：</div>
						<div class="submit-select-group-select col-md-8">
							<input name="User_Fc_Tx" value="<?=User_Fc_Tx?>" type="text" class="submit-input">
							<label class="label-tip text-gray">%</label>
						</div>
					</div>
				</div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-btn col-md-offset-2 col-md-8">
                        <button class="blue-white-btn submit-btn" type="submit">提交</button>
                    </div>
                </div>
            </div>
            <div id="page_j" style="display: none">
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">QQ登录开关：</div>
                    <div class="submit-select-group-select col-md-8">
                        <select class="submit-select" id="qq" name="Qq_Log" onchange="get_qq();">
							<option value="0"<?php if(Qq_Log==0) echo ' selected="selected"';?>>关闭</option>
                          	<option value="1"<?php if(Qq_Log==1) echo ' selected="selected"';?>>开启</option>
						</select>
                        <label class="label-tip text-gray">QQ登录开关，<a class="text-blue" target="_blank" href="http://connect.qq.com/" target="_blank">点我申请</a></label>
                    </div>
                </div>
				<!-- qq login configure -->
				<div id="qqid" <?php if(Qq_Log==0) echo ' style="display:none;"';?>>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">APP_ID：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" name="Qq_Appid" value="<?=Qq_Appid?>">
							<label class="label-tip text-gray">QQ登录的 APP_ID</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">APP_KEY：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" name="Qq_Appkey" value="<?=Qq_Appkey?>">
							<label class="label-tip text-gray">QQ登录的 APP_KEY</label>
						</div>
					</div>
				</div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">微信登录开关：</div>
                    <div class="submit-select-group-select col-md-8">
                        <select class="submit-select" id="wx" name="Wx_Log" onchange="get_weixin();">
							<option value="0"<?php if(Wx_Log==0) echo ' selected="selected"';?>>关闭</option>
                          	<option value="1"<?php if(Wx_Log==1) echo ' selected="selected"';?>>开启</option>
						</select>
                        <label class="label-tip text-gray">微信登录开关，<a class="text-blue" href="https://open.weixin.qq.com/" target="_blank">点我申请</a></label>
                    </div>
                </div>
				<div id="wxid"<?php if(Wx_Log==0) echo ' style="display:none;"';?>>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">APP_ID：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" name="Wx_Appid" value="<?=Wx_Appid?>">
							<label class="label-tip text-gray">微信登录的 APP_KEY</label>
						</div>
					</div>
					<div class="content-page-submit-select-group row">
						<div class="submit-select-group-tab-title col-md-2">APP_KEY：</div>
						<div class="submit-select-group-select col-md-8">
							<input type="text" class="submit-long-input" name="Wx_Appkey" value="<?=Wx_Appkey?>">
							<label class="label-tip text-gray">微信登录的 APP_KEY</label>
						</div>
					</div>
				</div>
				<div class="content-page-submit-select-group row">
					<div class="submit-select-group-btn col-md-offset-2 col-md-8">
						<button class="blue-white-btn submit-btn" type="submit">提交</button>
					</div>
				</div>
            </div>
            <div id="page_k" style="display: none">
            	<style type="text/css">
            		.caiji label{
	            		padding-top: 10px;
	    				padding-right: 10px;
            		}
            	</style>
            	<?php
            	$Cj_Add = defined('Cj_Add') ? Cj_Add : 'cid';
            	$Cj_Edit = defined('Cj_Edit') ? Cj_Edit : 'url,state,addtime';
            	$cjarr1 = explode(',',$Cj_Add);
            	$cjarr2 = explode(',',$Cj_Edit);
				?>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">入库重复规则：</div>
                    <div class="submit-select-group-select col-md-8 caiji">
                        <label><input type="checkbox" name="cjadd[]" value="name" checked disabled> 视频标题+</label>
                        <label><input type="checkbox" name="cjadd[]" value="cid"<?php if(in_array('cid',$cjarr1)) echo ' checked';?>> 分类</label>
                        <label><input type="checkbox" name="cjadd[]" value="year"<?php if(in_array('year',$cjarr1)) echo ' checked';?>> 年代</label>
                        <label><input type="checkbox" name="cjadd[]" value="diqu"<?php if(in_array('diqu',$cjarr1)) echo ' checked';?>> 地区</label>
                        <label><input type="checkbox" name="cjadd[]" value="yuyan"<?php if(in_array('yuyan',$cjarr1)) echo ' checked';?>> 语言</label>
                        <label><input type="checkbox" name="cjadd[]" value="zhuyan"<?php if(in_array('zhuyan',$cjarr1)) echo ' checked';?>> 主演</label>
                        <label><input type="checkbox" name="cjadd[]" value="daoyan"<?php if(in_array('daoyan',$cjarr1)) echo ' checked';?>> 导演</label>
                    </div>
                </div>
                <div class="content-page-submit-select-group row">
                    <div class="submit-select-group-tab-title col-md-2">二次更新规则：</div>
                    <div class="submit-select-group-select col-md-8 caiji">
                        <label><input type="checkbox" name="cjedit[]" value="addtime"<?php if(in_array('addtime',$cjarr2)) echo ' checked';?>> 更新时间</label>
                        <label><input type="checkbox" name="cjedit[]" value="url"<?php if(in_array('url',$cjarr2)) echo ' checked';?>> 播放地址</label>
                        <label><input type="checkbox" name="cjedit[]" value="down"<?php if(in_array('down',$cjarr2)) echo ' checked';?>> 下载地址</label>
                        <label><input type="checkbox" name="cjedit[]" value="pic"<?php if(in_array('pic',$cjarr2)) echo ' checked';?>> 图片</label>
                        <label><input type="checkbox" name="cjedit[]" value="text"<?php if(in_array('text',$cjarr2)) echo ' checked';?>> 简介</label>
                        <label><input type="checkbox" name="cjedit[]" value="state"<?php if(in_array('state',$cjarr2)) echo ' checked';?>> 状态</label>
                        <label><input type="checkbox" name="cjedit[]" value="info"<?php if(in_array('info',$cjarr2)) echo ' checked';?>> 备注</label>
                        <label><input type="checkbox" name="cjedit[]" value="year"<?php if(in_array('year',$cjarr2)) echo ' checked';?>> 年份</label>
                        <label><input type="checkbox" name="cjedit[]" value="diqu"<?php if(in_array('diqu',$cjarr2)) echo ' checked';?>> 地区</label>
                        <label><input type="checkbox" name="cjedit[]" value="yuyan"<?php if(in_array('yuyan',$cjarr2)) echo ' checked';?>> 语言</label>
                        <label><input type="checkbox" name="cjedit[]" value="zhuyan"<?php if(in_array('zhuyan',$cjarr2)) echo ' checked';?>> 主演</label>
                        <label><input type="checkbox" name="cjedit[]" value="daoyan"<?php if(in_array('daoyan',$cjarr2)) echo ' checked';?>> 导演</label>
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
<script>
    $(function () {
        $(".tab").click(function () {
            tab(this);
        });
    });
		function get_up(){
		    var up = $('#ups').val();
		    if(up==1){
			$('#ftp').show();
			$('#ttk').hide();
		    } else if(up==2){
			$('#ftp').hide();
			$('#ttk').show();
		    } else {
			$('#ftp').hide();
			$('#ttk').hide();
		    }
		}
		function get_uri(){
		    var uri = $('#uris').val();
		    if(uri==1){
				$('#uri').show();
		    } else {
				$('#uri').hide();
		    }
		}
		function get_html(){
		    var html = $('#htmls').val();
		    if(html==1){
				$('#html').show();
				$('#url').hide();
		    } else {
				$('#html').hide();
				$('#url').show();
		    }
		}
		function get_qq(){
		    var qq = $('#qq').val();
		    if(qq==1){
				$('#qqid').show();
		    } else {
				$('#qqid').hide();
		    }
		}
		function get_weixin(){
		    var wx = $('#wx').val();
		    if(wx==1){
				$('#wxid').show();
		    } else {
				$('#wxid').hide();
		    }
		}
		function get_fx() {
			var fx = $('#fx').val();
			if(fx==1){
			$('#Fx_Info').show();
			} else {
			$('#Fx_Info').hide();
			}
		}
		function get_show(){
			layer.open({
				type: 1,
				shade: false,
				area: ['700px', '420px'],
				title: '详细介绍', //不显示标题
				content: $('.fanxiao')
			});
		}
</script>
</html>
