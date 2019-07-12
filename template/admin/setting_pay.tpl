<title>支付配置</title>
</head>
<body>
  <div class="content-page">
      <div class="content-page-nav">
          <a class="nav-list"><i class="icon index-icon pull-left"></i>首页</a><a class="nav-list"> > 系统配置 </a><a class="nav-list"> > 支付配置 </a>
      </div>
      <div class="content-page-tab-box">
          <div class="tab-group">
              <div class="tab active" title="page_a">默认配置</div>
              <div class="tab" title="page_b">收款配置</div>
          </div>
          <form action="<?=links('setting','pay_save')?>" method="post" id="form-article-add">
          <div class="control-page">
              <div id="page_a">
                  <div class="content-page-submit-select-group row">
                      <div class="submit-select-group-tab-title col-md-2">申请地址：</div>
                      <div class="submit-select-group-select col-md-8">
                          <a href="http://www.atfpay.net/" target="_blank">
                            <label class="label-tip">http://www.atfpay.net/</label>
                          </a>
                      </div>
                  </div>
                  <div class="content-page-submit-select-group row">
                      <div class="submit-select-group-tab-title col-md-2">支付开关：</div>
                      <div class="submit-select-group-select col-md-8">
                          <select class="submit-select" id="CT_Pay" name="CT_Pay" onchange="get_pay();">
                            <option value="0"<?php if(CT_Pay==0) echo ' selected="selected"';?>>关闭</option>
                            <option value="1"<?php if(CT_Pay==1) echo ' selected="selected"';?>>开启</option>
                          </select>
                          <label class="label-tip text-gray">在线支付是否启用</label>
                      </div>
                  </div>
                  <div id="payid" <?php if(CT_Pay==0) echo ' style="display:none;"';?>>
                    <div class="content-page-submit-select-group row">
                        <div class="submit-select-group-tab-title col-md-2">商户ID：</div>
                        <div class="submit-select-group-select col-md-8">
                            <input type="text" class="submit-long-input" name="CT_Pay_ID" value="<?=CT_Pay_ID?>">
                            <label class="label-tip text-gray">公共参数</label>
                        </div>
                    </div>
                    <div class="content-page-submit-select-group row">
                        <div class="submit-select-group-tab-title col-md-2">商户秘钥：</div>
                        <div class="submit-select-group-select col-md-8">
                            <input type="text" class="submit-long-input" name="CT_Pay_Key" value="<?=CT_Pay_Key?>">
                            <label class="label-tip text-gray">公共参数</label>
                        </div>
                    </div>
                  </div>
                  <div class="content-page-submit-select-group row">
                      <div class="submit-select-group-btn col-md-offset-2 col-md-8">
                          <button class="blue-white-btn submit-btn">保存</button>
                      </div>
                  </div>
              </div>
              <div id="page_b" style="display: none">
                  <div class="content-page-submit-select-group row">
                      <div class="submit-select-group-tab-title col-md-2">金币比例：</div>
                      <div class="submit-select-group-select col-md-8">
                          <input type="text" class="submit-short-input" name="CT_Rmb_To_Cion" value="<?=CT_Rmb_To_Cion?>">
                          <label class="label-tip text-gray">1元=多少金币</label>
                      </div>
                  </div>
                  <div class="content-page-submit-select-group row">
                      <div>
                          <div class="submit-select-group-tab-title col-md-2" style="font-weight: bold">VIP价格：</div>
                      </div>
                      <div style="margin-bottom: 20px">
                          <div class="submit-select-group-tab-title col-md-2">1天：</div>
                          <div class="submit-select-group-select col-md-3">
                              <input type="text" class="submit-short-input" name="CT_Vip1_Rmb" value="<?=CT_Vip1_Rmb?>">
                              <label class="label-tip text-gray">元</label>
                          </div>
                          <div class="submit-select-group-tab-title col-md-1">30天：</div>
                          <div class="submit-select-group-select col-md-3">
                              <input type="text" class="submit-short-input" name="CT_Vip2_Rmb" value="<?=CT_Vip2_Rmb?>">
                              <label class="label-tip text-gray">元</label>
                          </div>
                      </div>
                      <div>
                          <div class="submit-select-group-tab-title col-md-2">180天：</div>
                          <div class="submit-select-group-select col-md-3">
                              <input type="text" class="submit-short-input" name="CT_Vip3_Rmb" value="<?=CT_Vip3_Rmb?>">
                              <label class="label-tip text-gray">元</label>
                          </div>
                          <div class="submit-select-group-tab-title col-md-1">365天：</div>
                          <div class="submit-select-group-select col-md-3">
                              <input type="text" class="submit-short-input" name="CT_Vip4_Rmb" value="<?=CT_Vip4_Rmb?>">
                              <label class="label-tip text-gray">元</label>
                          </div>
                      </div>
                  </div>
                  <div class="content-page-submit-select-group row">
                      <div class="submit-select-group-btn col-md-offset-2 col-md-8">
                          <button class="blue-white-btn submit-btn">保存</button>
                      </div>
                  </div>
              </div>
          </div>
        </form>
      </div>
  </div>
</body>
<script>
    $(function () {
        $(".tab").click(function () {
            tab(this);
        });
    });
    function get_pay(){
        var paytoken = $('#CT_Pay').val();
        console.log(paytoken);
        if(paytoken==1){
        $('#payid').show();
        } else {
        $('#payid').hide();
        }
    }
</script>
</html>
