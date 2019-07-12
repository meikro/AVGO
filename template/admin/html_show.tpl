<title>列表生成</title>
</head>
<body>
  <div class="content-page">
      <div class="content-page-nav">
          <a class="nav-list"><i class="icon index-icon pull-left"></i>首页</a><a class="nav-list"> > 静态生成 </a><a class="nav-list"> > 内容生成 </a>
      </div>
      <form action="<?=links('html','show_save')?>" method="post" target="msg">
      <div class="content-page-select-group row">
        <div class="col-md-1" style="text-align:center">
          视频内容页：
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
        <div class="col-md-1" style="text-align:center">开始ID：
        </div>
        <div class="rili-input col-md-1">
            <input type="text" class="time" name="ksid">
        </div>
        <div class="col-md-1" style="text-align:center">结束ID：
        </div>
        <div class="rili-input col-md-1">
            <input type="text" class="time" name="jsid">
        </div>
        <div class="col-md-1" style="position: relative;top:-3px;">
            <button  name="so" class="content-page-btn blue-white-btn">生成所选</button>
        </div>
        <div class="col-md-1" style="position: relative;top:-3px;">
            <a href="<?=links('html','day',0,'ac=vod')?>" target="msg">
              <button  name="so" class="content-page-btn blue-white-btn" type="button">一键生成当天视频</button>
            </a>
        </div>
      </div>
      </form>
      <hr/>
      <?php if(Html_Play_Off == 1){ ?>
      <form action="<?=links('html','play_save')?>" method="post" target="msg">
      <div class="content-page-select-group row">
        <div class="col-md-1" style="text-align:center">
          视频播放页：
        </div>
        <div class="col-md-1">
            <select class="content-page-select" name="cid" >
              <option value="0">全部分类</option>
      				<?php
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
        <div class="col-md-1" style="text-align:center">开始ID：
        </div>
        <div class="rili-input col-md-1">
            <input type="text" class="time" name="ksid">
        </div>
        <div class="col-md-1" style="text-align:center">结束ID：
        </div>
        <div class="rili-input col-md-1">
            <input type="text" class="time" name="jsid">
        </div>
        <div class="col-md-1" style="position: relative;top:-3px;">
            <button name="so" class="content-page-btn blue-white-btn">生成所选</button>
        </div>
        <div class="col-md-1" style="position: relative;top:-3px;">
            <a href="<?=links('html','day',0,'ac=vodplay')?>" target="msg">
              <button  name="so" class="content-page-btn blue-white-btn" type="button">一键生成当天视频</button>
            </a>
        </div>
      </div>
      </form>
      <?php } ?>
      <hr/>
      <form action="<?=links('html','news_show')?>" method="post" target="msg">
      <div class="content-page-select-group row">
        <div class="col-md-1" style="text-align:center">
          文章内容页：
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
        <div class="col-md-1" style="text-align:center">开始ID：
        </div>
        <div class="rili-input col-md-1">
            <input type="text" class="time" name="ksid">
        </div>
        <div class="col-md-1" style="text-align:center">结束ID：
        </div>
        <div class="rili-input col-md-1">
            <input type="text" class="time" name="jsid">
        </div>
        <div class="col-md-1" style="position: relative;top:-3px;">
            <button name="so" class="content-page-btn blue-white-btn">生成所选</button>
        </div>
        <div class="col-md-1" style="position: relative;top:-3px;">
            <a href="<?=links('html','day',0,'ac=news')?>" target="msg">
              <button  name="so" class="content-page-btn blue-white-btn" type="button">一键生成当天文章</button>
            </a>
        </div>
      </div>
      </form>
      <hr/>
      <form action="<?=links('html','topic_show')?>" method="post" target="msg">
      <div class="content-page-select-group row">
        <div class="col-md-1" style="text-align:center">
          专题内容：
        </div>
        <div class="col-md-1">
            <select class="content-page-select" name="cid" >
              <option value="0">全部分类</option>
              <?php
      				$lists = $this->csdb->get_select('topic','id,name',array(),'id DESC',1000);
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
        <div class="col-md-1" style="position: relative;top:-3px;">
            <button name="so" class="content-page-btn blue-white-btn">生成所选</button>
        </div>
        <div class="col-md-1" style="position: relative;top:-3px;">
            <a href="<?=links('html','day',0,'ac=topic')?>" target="msg">
              <button  name="so" class="content-page-btn blue-white-btn" type="button">一键生成当天专题</button>
            </a>
        </div>
      </div>
      </form>
  </div>
  <iframe style="Z-INDEX: 1; VISIBILITY: inherit; WIDTH: 100%; HEIGHT: 400px;" name="msg"  frameBorder=0 scrolling=yes></iframe>
</body>
</html>
