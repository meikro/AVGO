<script src="<?=Base_Path?>admin/assets/tool/jq/getUrlParam.js"></script>
<!--[if IE]>
<script src="<?=Base_Path?>admin/assets/tool/jq/html5shiv.js"></script>
<script src="<?=Base_Path?>admin/assets/tool/jq/respond.min.js"></script>
<![endif]-->
<title>后台首页</title>
</head>
<body class="bg-gray">
<div class="in-page-container bg-gray">
    <div class="row">
        <div class="col-md-6">
            <div class="content-box">
                <div class="title">登陆信息</div>
                <div class="content">
                    <div class="content-group"><?=$admin->nichen?> ,上次登录时间:<?=$_SESSION['admin_logtime']?> 上次登录IP:<?=$_SESSION['admin_logip']?></div>
                </div>
            </div>
            <div class="content-box">
                <div class="title">视频统计</div>
                <div class="content">
                    <div class="content-group">今日数量:<?=$count[0];?>个  昨日数量:<?=$count[1];?>个  本月数量:<?=$count[2];?>个  上月数量:<?=$count[3];?>个  总数量:<?=$count[4];?>个  </div>
                </div>
            </div>
            <div class="content-box">
                <div class="title">系统信息</div>
                <div class="content">
                    <div class="content-group">系统名称：Ctcms video system (简称ctcms)   官网：<a href="http://www.ctcms.cn/" target="_blank">www.ctcms.cn</a></div>
                    <div class="content-group">当前版本: Ctcms <span id="yver">v<?=Ct_Version?></span><span id="xver"></span></div>
                    <div class="content-group">当前域名：<?=$_SERVER["HTTP_HOST"]?><span id="stcms_sq"></span></div>
                    <div class="content-group">操作系统：<?php $os = explode(" ", php_uname()); echo $os[0];?></div>
                    <div class="content-group">内核版本：<?php if('/'==DIRECTORY_SEPARATOR){echo $os[2];}else{echo $os[1];} ?> /   <?php echo $_SERVER['SERVER_SOFTWARE'];?></div>
                    <div class="content-group">系统时间：<?=date('Y-m-d H:i:s')?></div>
                    <div class="content-group">服务器IP：<?=GetHostByName($_SERVER['SERVER_NAME'])?></div>
                    <div class="content-group">PHP版本：<?=PHP_VERSION?></div>
                    <div class="content-group">Mysql版本：<?=$this->db->version()?></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="content-box">
                <div class="title">系统简介</div>
                <div class="content">
                    <div class="content-group" id="gg">Ctcms(Ctcms video system) 是一套PHP+Mysql开发的视频管理系统,采用CI框架核心开发，体积小、运行快，强大缓存处理，自主研发的模板引擎标签简单易用，
                        后台支持一键增加视频， 附带官方资源库，支持会员点播、包月观看，点卡、广告、在线支付、留言求片、友情链接，只要略懂HTML的就可搭建一个强大的视频网站， 了解更多可到 论坛与大家交流</div>
                </div>
            </div>
            <div class="content-box">
                <div class="title">最新动态</div>
                <div class="content" id="news">
                    <div class="content-group"><a href="http://www.ctcms.cn/show/483.html" target="msg">Ctcms 原生安卓APP在线生成，点击生成APP</a></div>
                    <div class="content-group"><a href="http://www.ctcms.cn/show/552.html" target="msg">Ctcms V2盛大发布了</a></div>
                    <div class="content-group"><a href="http://www.ctcms.cn/show/4.html" target="msg">Ctcms v1.0.0正式开源发布了</a></div>
                    <div class="content-group"><a href="http://www.ctcms.cn/lists.html" target="msg">Ctcms v1论坛交流</a></div>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
var VER = '<?=Ct_Version?>';
var WEB_MODE = <?=Web_Mode?>;
var SELF_PATH='<?=Web_Path.SELF?>';
</script>
</body>
</html>
