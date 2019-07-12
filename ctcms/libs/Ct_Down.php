<?php
//新增方法，'标示'=>'名称', 每行一条
$ct_down = array(
	'xunlei' => '迅雷',
	'pan'    => '网盘',
	'magent' => '磁力',
	'tb'     => 'TB种子',
);
//下载组常量
define('CT_Down',serialize($ct_down));  