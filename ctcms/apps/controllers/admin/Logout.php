<?php 
/**
 * @Ctcms open source management system
 * @copyright 2008-2019 ctcms.cn. All rights reserved.
 * @Author:Cheng Kai Jie
 * @Dtime:2015-12-11
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Logout extends Ctcms_Controller {
	function __construct() {
	    parent::__construct();
		//加载后台模型
		$this->load->model('admin');
	}

	public function index()
	{
		unset(
            $_SESSION['admin_id'],
            $_SESSION['admin_nichen'],
            $_SESSION['admin_login']
        );
		header("location:".links('login'));exit;
	}
}