<title>CTCMS管理系统</title>
</head>
<body style="overflow: hidden">
  <div class="top-logo-login row">
      <div class="top_logo pull-left"><a class="block"><label class="text-yellow" style="font-size:20px">CTCMS</label><label class="text-white" style="font-size:20px">管理系统</label></a></div>
      <div class="top-return pull-right">
          <i class="icon show-list-icon pull-left" id="showLeftList"></i>
          <a href="<?=Web_Path?>" target="_blank" class="update small-hide pull-left text-white">网站首页</a>
          <a class="update small-hide pull-left text-white" onClick="cache();">更新缓存</a>
          <a class="return small-hide pull-right text-white" href="<?=links('logout')?>"><i class="icon close-icon pull-left"></i>退出</a>
      </div>
  </div>
  <!--顶部导航-->
  <div class="top_nav row">
      <div class="solid">
          <div class="solid-list">
              <a class="first_li pull-left active" tarName="main_page" href="<?=links('main')?>"  target="rightCont">
                  <span>后台首页</span>
                  <i class="icon yello-sanjiao-icon"></i>
              </a>
              <a class="first_li pull-left " tarName="vod_management" href="<?=links('vod')?>"  target="rightCont">
                  <span>视频库管理</span>
                  <i class="icon yello-sanjiao-icon"></i>
              </a>
              <a class="first_li pull-left " tarName="news_management" href="<?=links('comm')?>" target="rightCont">
                  <span>文章库管理</span>
                  <i class="icon yello-sanjiao-icon"></i>
              </a>
              <a class="first_li pull-left " tarName="employee_management" href="<?=links('user')?>"  target="rightCont">
                  <span>会员管理</span>
                  <i class="icon yello-sanjiao-icon"></i>
              </a>
              <a class="first_li pull-left " tarName="yunying_management" href="<?=links('ads')?>"  target="rightCont">
                  <span>运营管理</span>
                  <i class="icon yello-sanjiao-icon"></i>
              </a>
              <a class="first_li pull-left " tarName="template_management" href="<?=links('tpl','index')?>"  target="rightCont">
                  <span>模板管理</span>
                  <i class="icon yello-sanjiao-icon"></i>
              </a>
              <a class="first_li pull-left" tarName="system_setting" href="<?=links('setting')?>"  target="rightCont">
                  <span>系统配置</span>
                  <i class="icon yello-sanjiao-icon"></i>
              </a>
              <a class="first_li pull-left" tarName="manager_management" href="<?=links('sys')?>"  target="rightCont">
                  <span>管理员管理</span>
                  <i class="icon yello-sanjiao-icon"></i>
              </a>
              <a class="first_li pull-left" tarName="static_generation" href="<?=links('html')?>"  target="rightCont">
                  <span>静态生成</span>
                  <i class="icon yello-sanjiao-icon"></i>
              </a>
          </div>

      </div>
      <div class="solid-btn" id="solidBtn"></div>
  </div>
  <!--左侧导航-->
  <div class="left_nav active">
      <div class="menu" tarName="main_page">
          <a class="first_menu index-menu block" tarName="vod_management" href="<?=links('vod')?>"  target="rightCont"><i class="icon pull-left"></i>视频库管理</a>
          <a class="first_menu index-menu block" tarName="news_management" href="<?=links('comm')?>"  target="rightCont"><i class="icon pull-left"></i>文章库管理</a>
          <a class="first_menu index-menu block" tarName="employee_management" href="<?=links('user')?>"  target="rightCont"><i class="icon pull-left"></i>会员管理</a>
          <a class="first_menu index-menu block" tarName="yunying_management" href="<?=links('ads')?>"  target="rightCont"><i class="icon pull-left"></i>运营管理</a>
          <a class="first_menu index-menu block" tarName="template_management" href="<?=links('tpl','index')?>"  target="rightCont"><i class="icon pull-left"></i>模板管理</a>
          <a class="first_menu index-menu block" tarName="system_setting" href="<?=links('setting')?>"  target="rightCont"><i class="icon pull-left"></i>系统配置</a>
          <a class="first_menu index-menu block" tarName="manager_management" href="<?=links('sys')?>"  target="rightCont"><i class="icon pull-left"></i>管理员管理</a>
          <a class="first_menu index-menu block" tarName="static_generation" href="<?=links('html')?>"  target="rightCont"><i class="icon pull-left"></i>静态生成</a>
      </div>

      <div class="menu" tarName="vod_management" style="display: none">
          <a class="first_menu block active" href="<?=links('vod')?>"  target="rightCont"><i class="icon vod-spgl-icon pull-left active"></i>视频管理</a>
          <a class="first_menu block" href="<?=links('vod','lists')?>"  target="rightCont"><i class="icon vod-flgl-icon pull-left"></i>分类管理</a>
          <a class="first_menu block" href="<?=links('caiji')?>"  target="rightCont"><i class="icon vod-spcj-icon pull-left"></i>视频采集</a>
          <a class="first_menu block" href="<?=links('player')?>"  target="rightCont"><i class="icon vod-bfq-icon pull-left"></i>播放器管理</a>
          <a class="first_menu block" href="<?=links('topic')?>"  target="rightCont"><i class="icon vod-spzt-icon pull-left"></i>视频专题管理</a>
          <a class="first_menu block" href="<?=links('fav')?>"  target="rightCont"><i class="icon hf_scgl_icon pull-left"></i>视频收藏管理</a>
          <a class="first_menu block" href="<?=links('vod','plcmd')?>"  target="rightCont"><i class="icon vod-spcz-icon pull-left"></i>视频批量操作</a>
          <a class="first_menu block" href="<?=links('error')?>"  target="rightCont"><i class="icon vod-spbc-icon pull-left"></i>视频报错管理</a>
          <a class="first_menu block" href="<?=links('timing')?>"  target="rightCont"><i class="icon vod-dscj-icon pull-left"></i>视频挂机定时采集</a>
      </div>
      <div class="menu" tarName="news_management" style="display: none">
          <a class="first_menu block active" href="<?=links('comm')?>"  target="rightCont"><i class="icon news_manage_icon pull-left active"></i>文章管理</a>
          <a class="first_menu block" href="<?=links('circle')?>"  target="rightCont"><i class="icon news_qzgl_icon pull-left"></i>圈子管理</a>
          <a class="first_menu block" href="<?=links('dz')?>"  target="rightCont"><i class="icon news_dzgl_icon pull-left"></i>点赞管理</a>
          <a class="first_menu block" href="<?=links('coll')?>"  target="rightCont"><i class="icon news_scgl_icon pull-left"></i>收藏管理</a>
          <a class="first_menu block" href="<?=links('msg')?>"  target="rightCont"><i class="icon news_hfgl_icon pull-left"></i>回复管理</a>
      </div>
      <div class="menu" tarName="employee_management" style="display: none">
          <a class="first_menu block active" href="<?=links('user')?>"  target="rightCont"><i class="icon hy_hygl_icon pull-left active"></i>会员管理</a>
          <a class="first_menu block" href="<?=links('card')?>"  target="rightCont"><i class="icon hy_dkgl_icon pull-left"></i>点卡管理</a>
          <a class="first_menu block" href="<?=links('pay')?>"  target="rightCont"><i class="icon hy_czjl_icon pull-left"></i>充值记录</a>
          <a class="first_menu block" href="<?=links('buy')?>"  target="rightCont"><i class="icon hy_xfij_icon pull-left"></i>消费记录</a>
          <a class="first_menu block" href="<?=links('fenxiao')?>"  target="rightCont"><i class="icon hy_fxjl_icon pull-left"></i>分销记录</a>
          <a class="first_menu block" href="<?=links('tixian')?>"  target="rightCont"><i class="icon hf_txjl_icon pull-left"></i>提现记录</a>
          <a class="first_menu block" href="<?=links('liwu')?>"  target="rightCont"><i class="icon hy_czjl_icon pull-left"></i>礼物管理</a>
          <a class="first_menu block" href="<?=links('buy','liwu')?>"  target="rightCont"><i class="icon hy_czjl_icon pull-left"></i>打赏记录</a>
      </div>
      <div class="menu" tarName="yunying_management" style="display: none">
          <a class="first_menu block active" href="<?=links('ads')?>"  target="rightCont"><i class="icon yy_gggl_icon pull-left active"></i>广告管理</a>
          <a class="first_menu block" href="<?=links('pages')?>"  target="rightCont"><i class="icon yy_ymgl_icon pull-left"></i>页面管理</a>
          <a class="first_menu block" href="<?=links('pl')?>"  target="rightCont"><i class="icon yy_plgl_icon pull-left"></i>评论管理</a>
          <a class="first_menu block" href="<?=links('gbook')?>"  target="rightCont"><i class="icon yy_lygl_icon pull-left"></i>留言管理</a>
          <a class="first_menu block" href="<?=links('links')?>"  target="rightCont"><i class="icon yy_yqlj_icon pull-left"></i>友情链接</a>
      </div>
      <div class="menu" tarName="template_management" style="display: none">
          <a class="first_menu block active" href="<?=links('tpl','index')?>"  target="rightCont"><i class="icon temp_pc_icon pull-left active"></i>PC主页模板</a>
          <a class="first_menu block" href="<?=links('tpl','index','user')?>"  target="rightCont"><i class="icon temp_hypc_icon pull-left"></i>PC会员模板</a>
          <a class="first_menu block" href="<?=links('tpl','index','mobile')?>"  target="rightCont"><i class="icon temp_phone_icon pull-left"></i>手机主页模板</a>
          <a class="first_menu block" href="<?=links('tpl','index','mobile_user')?>"  target="rightCont"><i class="icon temp_hyphone_icon pull-left"></i>手机会员模板</a>
      </div>
      <div class="menu" tarName="system_setting" style="display: none">
          <a class="first_menu block active" href="<?=links('setting')?>"  target="rightCont"><i class="icon system_setting_icon pull-left active"></i>系统配置</a>
          <a class="first_menu block" href="<?=links('setting','pay')?>"  target="rightCont"><i class="icon system_pay_icon pull-left"></i>支付配置</a>
          <a class="first_menu block" href="<?=links('setting','email')?>"  target="rightCont"><i class="icon system_email_icon pull-left"></i>邮件配置</a>
          <a class="first_menu block" href="<?=links('yunparse')?>"  target="rightCont"><i class="icon system_jx_icon pull-left"></i>解析配置</a>
          <a class="first_menu block" href="<?=links('app')?>"  target="rightCont"><i class="icon system_app_icon pull-left"></i>APP配置</a>
          <a class="first_menu block" href="<?=links('zhuanma')?>"  target="rightCont"><i class="icon static_content_icon pull-left"></i>转码配置</a>
      </div>
      <div class="menu" tarName="manager_management" style="display: none">
          <a class="first_menu block active" href="<?=links('sys')?>"  target="rightCont"><i class="icon manage_manager_icon pull-left active"></i>管理员列表</a>
          <a class="first_menu block" href="<?=links('sys','log')?>"  target="rightCont"><i class="icon manage_log_icon pull-left"></i>登录日志</a>
          <a class="first_menu block" href="<?=links('basedb')?>"  target="rightCont"><i class="icon manage_bf_icon pull-left"></i>备份还原</a>
      </div>
      <div class="menu" tarName="static_generation" style="display: none">
          <a class="first_menu block active" href="<?=links('html')?>"  target="rightCont"><i class="icon static_zy_icon pull-left active"></i>主页生成</a>
          <a class="first_menu block" href="<?=links('html','lists')?>"  target="rightCont"><i class="icon static_list_icon pull-left"></i>列表生成</a>
          <a class="first_menu block" href="<?=links('html','show')?>"  target="rightCont"><i class="icon static_content_icon pull-left"></i>内容生成</a>
          <a class="first_menu block" href="<?=links('html','opt')?>"  target="rightCont"><i class="icon static_list_icon pull-left"></i>自定义生成</a>
      </div>
  </div>
  <!--内容页-->
  <div class="index-in-container" id="indexContent">
      <iframe class="mainFrame" frameborder="0" id="mainFrame" scrolling="yes" name="rightCont" src="<?=links('main');?>"></iframe>
  </div>
  <!--遮罩层-->
  <div class="mask" style="display: none"></div>
  <!--弹窗-->
  <div class="delete-pop pop-box" style="display: none">
      <div class="title"><i class="icon pop-close-icon" onclick="$('.delete-pop').hide();$('.mask').hide()"></i></div>
      <div class="content">删除后不能恢复，是否确定删除？</div>
      <div class="pop-btn-group">
          <button class="pop-btn yellow-white-btn delete-yes">确定</button>
          <button class="pop-btn gray-white-btn delete-yes" onclick="$('.delete-pop').hide();$('.mask').hide()">取消</button>
      </div>
  </div>
  </body>
  <script>


      $(function () {
          //页面高度控制
          indexHeight();
          //导航栏设置
          indexNav();
          //    子页跳转
          $(".first_menu,.first_li").click(function () {
              var url = $(this).attr("href");
              $("#mainFrame").attr("src",url);
          })
      });

      function cache(){
          $.get('<?=links('cache')?>',function(data) {
                 var msg=data;
      	   if(msg == "ok"){//成功
      		get_msg('缓存更新成功!',2000,1);
      	   }else{
      	        get_msg(msg);
      	   }
          });
      }

  </script>
</html>
