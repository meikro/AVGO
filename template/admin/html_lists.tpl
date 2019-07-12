<title>列表生成</title>
</head>
<body>
  <div class="content-page">
      <div class="content-page-nav">
          <a class="nav-list"><i class="icon index-icon pull-left"></i>首页</a><a class="nav-list"> > 静态生成 </a><a class="nav-list"> > 列表生成 </a>
      </div>
      <form action="<?=links('html','lists_save')?>" method="post" target="msg">
      <div class="content-page-select-group row">
        <div class="col-md-1" style="text-align:center">
          视频栏目：
        </div>
        <div class="col-md-1">
            <select class="content-page-select" name="cid" >
              <option value="0">全部分类</option>
      				<?php
      				$lists = $this->csdb->get_select('class','id,name',array('fid'=>0),'xid ASC',100);
      				foreach ($lists as $row2) {
      					echo '<option value="'.$row2->id.'">├&nbsp;'.$row2->name.'</option>';
      					$array2 = $this->csdb->get_select('class','id,name',array('fid'=>$row2->id),'xid ASC');
      				        foreach ($array2 as $row3) {
      					    echo '<option value="'.$row3->id.'">&nbsp;&nbsp;├&nbsp;'.$row3->name.'</option>';
      					}
      				}
      				?>
            </select>
        </div>
        <div class="col-md-1" style="text-align:center">开始页数：
        </div>
        <div class="rili-input col-md-1">
            <input type="text" class="time" name="kspage">
        </div>
        <div class="col-md-1" style="text-align:center">结束页数：
        </div>
        <div class="rili-input col-md-1">
            <input type="text" class="time" name="jspage">
        </div>
        <div class="col-md-1" style="position: relative;top:-3px;">
            <button  name="so" class="content-page-btn blue-white-btn">生成所选</button>
        </div>
      </div>
      </form>
      <hr/>
      <form action="<?=links('html','news_lists')?>" method="post" target="msg">
      <div class="content-page-select-group row">
        <div class="col-md-1" style="text-align:center">
          文章栏目：
        </div>
        <div class="col-md-1">
            <select class="content-page-select" name="cid" >
              <option value="0">全部分类</option>
      				<?php
      				$lists = $this->csdb->get_select('circle','id,name',array(),'xid ASC',100);
      				foreach ($lists as $row2) {
      					echo '<option value="'.$row2->id.'">├&nbsp;'.$row2->name.'</option>';
      					$array2 = $this->csdb->get_select('class','id,name',array('fid'=>$row2->id),'xid ASC');
      				        foreach ($array2 as $row3) {
      					    echo '<option value="'.$row3->id.'">&nbsp;&nbsp;├&nbsp;'.$row3->name.'</option>';
      					}
      				}
      				?>
            </select>
        </div>
        <div class="col-md-1" style="text-align:center">开始页数：
        </div>
        <div class="rili-input col-md-1">
            <input type="text" class="time" name="kspage">
        </div>
        <div class="col-md-1" style="text-align:center">结束页数：
        </div>
        <div class="rili-input col-md-1">
            <input type="text" class="time" name="jspage">
        </div>
        <div class="col-md-1" style="position: relative;top:-3px;">
            <button name="so" class="content-page-btn blue-white-btn">生成所选</button>
        </div>
      </div>
      </form>
      <hr/>
      <form action="<?=links('html','topic')?>" method="post" target="msg">
      <div class="content-page-select-group row">
        <div class="col-md-1" style="text-align:center">
          视频专题：
        </div>
        <div class="col-md-1">
        </div>
        <div class="col-md-1" style="text-align:center">开始页数：
        </div>
        <div class="rili-input col-md-1">
            <input type="text" class="time" name="kspage">
        </div>
        <div class="col-md-1" style="text-align:center">结束页数：
        </div>
        <div class="rili-input col-md-1">
            <input type="text" class="time" name="jspage">
        </div>
        <div class="col-md-1" style="position: relative;top:-3px;">
            <button name="so" class="content-page-btn blue-white-btn">生成所选</button>
        </div>
      </div>
      </form>
  </div>
  <iframe style="Z-INDEX: 1; VISIBILITY: inherit; WIDTH: 100%; HEIGHT: 400px;" name="msg"  frameBorder=0 scrolling=yes></iframe>
</body>
</html>
