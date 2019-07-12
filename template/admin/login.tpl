<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
<link rel="stylesheet" type="text/css" href="<?=Base_Path?>admin/assets/css/main.css">
<link rel="stylesheet" type="text/css" href="<?=Base_Path?>admin/assets/css/style.css">
<script src="http://libs.baidu.com/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript" src="<?=Base_Path?>admin/assets/tool/bootstrap.min/bootstrap.min.js"></script>
<link  rel="stylesheet" type="text/css" href="<?=Base_Path?>admin/assets/tool/bootstrap.min/bootstrap.min.css">
<script src="<?=Base_Path?>admin/assets/tool/jq/getUrlParam.js"></script>
<script type="text/javascript" src="<?=Base_Path?>admin/assets/tool/jq/prefixfree.min.js"></script>
<script type="text/javascript" src="<?=Base_Path?>admin/assets/js/commen.js"></script>
<!--[if IE]>
<script src="assets/tool/jq/html5shiv.js"></script>
<script src="assets/tool/jq/respond.min.js"></script>
<![endif]-->
<title>CTCMS管理系统</title>
<script type="text/javascript" src="<?=Base_Path?>layer/layer.js"></script>
<script type="text/javascript" src="<?=Base_Path?>admin/js/H-ui.js"></script>
<script type="text/javascript" src="<?=Base_Path?>admin/js/H-ui.admin.js"></script>
</head>
<body style="overflow: hidden;">
<div class="bg-img">
    <div class="login-box">
        <form action="" method="get">
            <div class="title"><label class="text-yellow">CTCMS</label><label class="text-white">管理系统</label></div>
            <div class="login-box-group">
              <div class="login-input">
                <i class="icon login-number-icon pull-left"></i>
                <input class="pull-left" type="text" name="name" id="name" placeholder="账号">
              </div>
            </div>
            <div class="login-box-group">
              <div class="login-input">
                <i class="icon login-password-icon pull-left"></i>
                <input class="pull-left" type="password" name="pass"  id="pass" placeholder="密码">
              </div>
            </div>
            <div class="login-box-group" style="margin-bottom: 50px">
              <div class="login-input">
                <i class="icon login-yzm-icon pull-left"></i>
                <input class="pull-left" type="password" name="code" id="code" placeholder="验证码">
              </div>
            </div>
            <div class="login-box-group">
              <button class="login-btn pull-left yellow-black-btn" id="login" type="button">登录</button>
              <button class="login-btn pull-right gray-white-btn" id="nologin" type="button">取消</button>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
var postlink='<?=links('login','save')?>';
$('#login').click(function(){
    var name=$('#name').val();
    var pass=$('#pass').val();
    var code=$('#code').val();
    if(name==''){
	get_tips('#name','账号不能为空');
	$('#name').focus();
    } else if(pass==''){
        get_tips('#pass','密码不能为空');
	$('#pass').focus();
    } else if(code==''){
        get_tips('#code','认证码不能为空');
	$('#code').focus();
    } else {
        layer.load();
        $.post(postlink,{name: name,pass: pass,code: code},function(data) {
	   layer.closeAll('loading');
           var msg=data['error'];
	   if(msg == "ok"){//登陆成功
	       get_msg('恭喜您，登陆成功，页面跳转中...',1000,1);
               window.location.href="<?=links('index')?>";
	   }else{
	       get_msg(msg);
	   }
        },"json");
    }
});
</script>
</body>
</html>
