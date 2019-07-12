<title>自定义页生成</title>
</head>
<body>
  <div class="content-page">
      <div class="content-page-nav">
          <a class="nav-list"><i class="icon index-icon pull-left"></i>首页</a><a class="nav-list"> > 静态生成 </a><a class="nav-list"> > 自定义页生成 </a>
      </div>
      <form action="<?=links('html','opt_save')?>" method="post" target="msg">
      <div class="content-page-select-group row">
        <div class="col-md-1" style="text-align:center">
          自定义页面：
        </div>
        <div class="col-md-1" style="width: 300px;">
            <select name="tpl[]" class="content-page-select" multiple="multiple" style="height: 200px;">
            <?php
            foreach ($skins as $tpl) {
                echo '<option value="'.$tpl.'">├&nbsp;'.$tpl.'</option>';
            }
            ?>
            </select>
        </div>
        <div class="col-md-1" style="position: relative;top:-3px;">
            <button  name="so" class="content-page-btn blue-white-btn">生成所选</button>
        </div>
      </div>
      </form>
  </div>
  <iframe style="Z-INDEX: 1; VISIBILITY: inherit; WIDTH: 100%; HEIGHT: 400px;" name="msg"  frameBorder=0 scrolling=yes></iframe>
</body>
</html>