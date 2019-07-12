<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends Ctcms_Controller {

	public function __construct(){
		parent::__construct();
		//加载会员模型
		$this->load->model('user');
	}

	//验证TOKEN
    public function validate()
    {
        $token = $this->input->post('token', true);

        $response = [
            'message' => 'TOKEN 失效',
            'code' => 400,
        ];

        do {
            //login to ag now and get username.
            $result = $this->validateToken($token);
            if (!isset($result['data']['userName'])) {
                $response = [
                    'message' => 'TOKEN 失效',
                    'code' => 400,
                ];
                break;
            }

            $response = [
                'message' => '登录成功',
                'code' => 200,
            ];
        } while (false);

        echo json_encode($response);
        exit;
    }

    //付款回调
    public function callback()
    {
        do {
            $secret_key = 'ywyzxcx94dsss88axcz4h32keqhmliwyd5pjz';

            $LoginName = $this->input->post('LoginName', true);
            $billNo = $this->input->post('billNo', true);
            $depositDate = $this->input->post('depositDate', true);
            $depositAmount = $this->input->post('depositAmount', true);
            $sign = $this->input->post('sign', true);

            $my_sign = md5($LoginName . $billNo . $depositDate . $depositAmount . $secret_key);

            if ($my_sign != $sign) {
                echo 'fail';
                exit;
            }

            //创建支付
            $add['login_name'] = $LoginName;
            $add['bill_no'] = $billNo;
            $add['deposit_date'] = $depositDate;
            $add['deposit_amount'] = $depositAmount;
            $add['updated_at'] = date('Y-m-d H:i:s');
            $add['created_at'] = date('Y-m-d H:i:s');
            //入库
            $row = $this->csdb->get_insert('transaction', $add);
            if (!$row) {
                echo 'fail';
                exit;
            }

            $row = $this->csdb->get_row('user', '*', array('name' => $LoginName));
            if (!$row) {
                echo 'fail';
                exit;
            }

            //记录登陆IP、时间、次数
            $edit['vip'] = 1;
            $edit['viptime'] = time() + 365 * 86400;
            $this->csdb->get_update('user', $row->id, $edit);

            echo 'ok';
            exit;
        } while (false);
    }

    //TOKEN登录
    public function token()
    {
        $token = $this->input->post('token', true);

        $response = [
            'message' => '登录失败',
            'code' => 400,
        ];

        do {
            //login to ag now and get username.
            $result = $this->validateToken($token);
            if(!isset($result['data']['userName'])) {
                $response = [
                    'message' => '账户密码有误',
                    'code' => 400,
                ];
                break;
            }

            $name = $result['data']['userName'];

            //获取用户名
            $row = $this->csdb->get_row('user','*',array('name'=>$name));
            if(!$row) {
                //创建用户
                $add['name'] = $name;
                $add['pass'] = md5($name);
                $add['email'] = '';
                $add['cion'] = User_Reg_Cion+User_Log_Cion;
                $add['regtime'] = time();
                $add['logtime'] = time();
                $add['lognum'] = 1;
                $add['logip'] = getip();
                //入库
                $res = $this->csdb->get_insert('user',$add);
                if(!$res){
                    $response = [
                        'message' => '登录异常，请联系客服',
                        'code' => 400,
                    ];
                    break;
                }

                $row = $this->csdb->get_row('user','*',array('name'=>$name));
                if(!$row) {
                    $response = [
                        'message' => '登录异常，请联系客服',
                        'code' => 400,
                    ];
                    break;
                }
            }

            //记录登陆IP、时间、次数
            $edit['logip'] = getip();
            $edit['lognum'] = $row->lognum+1;
            $edit['logtime'] = time();
            //每天登录送金币
            if(date('Y-m-d',$row->logtime) != date('Y-m-d')){
                $edit['cion'] = $row->cion+User_Log_Cion;
            }
            //判断VIP
            if($row->vip==1 && $row->viptime<time()){
                $edit['vip'] = 0;
                $row->vip = 0;
            }
            $this->csdb->get_update('user',$row->id,$edit);

            //登陆
            $this->session->set_tempdata('user_id', $row->id, 86400);
            $this->session->set_tempdata('user_name', $row->name, 86400);
            $this->session->set_tempdata('user_login', md5($row->id.$row->name.$row->pass.CT_Encryption_Key), 86400);

            //保存VIP COOKIE
            if($row->vip>0){
                setcookie('ctcms_vip','ok',86400+time(),'/');
            }else{
                setcookie('ctcms_vip','no',time()-86400,'/');
            }
            //记住登陆
            setcookie('ctcms_uid',$row->id,86400*10+time(),'/');
            setcookie('ctcms_log',md5($row->id.$row->name.$row->pass.CT_Encryption_Key),86400*10+time(),'/');

            $response = [
                'message' => '登录成功',
                'code' => 200,
            ];

        } while (false);

        echo json_encode($response);
        exit;
    }

    private function validateToken($token)
    {
        $api_url = 'http://uat.ag288.com/api/customer';

        $ch = curl_init($api_url);

        $header[] = 'Content-type: application/json';
        $header[] = 'Authorization: ' . $token;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // execute!
        $response = curl_exec($ch);

        // convert json string to array
        $json = json_decode($response, true);
        if(!isset($json['successful'])) {
            return false;
        }

        return $json;
    }

    //登陆
    public function login() {
		$name = safe_replace($this->input->post('name',true));
		$pass = $this->input->post('pass',true);
		if(empty($name) || empty($pass)) exit('账号、密码不能为空~!');

		$row = $this->csdb->get_row('user','*',array('name'=>$name));
		if(!$row) $row = $this->csdb->get_row('user','*',array('email'=>$name));
		if(!$row) $row = $this->csdb->get_row('user','*',array('tel'=>$name));
		if(!$row || $row->pass!=md5($pass)) exit('账号、密码错误~!');

		//记录登陆IP、时间、次数
		$edit['logip'] = getip();
		$edit['lognum'] = $row->lognum+1;
		$edit['logtime'] = time();
		//每天登录送金币
		if(date('Y-m-d',$row->logtime) != date('Y-m-d')){
			$edit['cion'] = $row->cion+User_Log_Cion;
		}
		//判断VIP
		if($row->vip==1 && $row->viptime<time()){
		 $edit['vip'] = 0;
		 $row->vip = 0;
		}
		$this->csdb->get_update('user',$row->id,$edit);

		//登陆
		$this->session->set_tempdata('user_id', $row->id, 86400);
		$this->session->set_tempdata('user_name', $row->name, 86400);
		$this->session->set_tempdata('user_login', md5($row->id.$row->name.$row->pass.CT_Encryption_Key), 86400);

		//保存VIP COOKIE
		if($row->vip>0){
		  setcookie('ctcms_vip','ok',86400+time(),'/');
		}else{
		  setcookie('ctcms_vip','no',time()-86400,'/');
		}
		//记住登陆
		setcookie('ctcms_uid',$row->id,86400*10+time(),'/');
		setcookie('ctcms_log',md5($row->id.$row->name.$row->pass.CT_Encryption_Key),86400*10+time(),'/');
		exit('ok');
	}

	//判断会员登陆
    public function ulog() {
		//当前模版目录
		$dir = $this->input->get('dir',true);
		if($dir!='user' && $dir!='mobile') $dir='';
		$log = $this->user->login(1);
		//获取模板
		$skin = $log ? 'uinfo.html' : 'ulogin.html';
		$str=load_file($skin,$dir);
		//全局解析
		$str=$this->parser->parse_string($str,'',true,false);
		//解析会员
		if($log){
			//当前会员数据
			$row = $this->csdb->get_row_arr('user','*',array('id'=>$_SESSION['user_id']));
			$str=$this->parser->ctcms_tpl('user',$str,$str,$row);
		}
		//IF判断解析
		$str=$this->parser->labelif($str);
		//转JS输出
		echo htmltojs($str);
	}

	//收藏视频
    public function fav($id) {
		//判断登陆
		if(!isset($_SESSION['user_id'])) exit('请先登陆~');
		if((int)$id==0) $id = (int)$this->input->get('id');
		if($id==0) exit('参数错误');
		$row = $this->csdb->get_row('vod','cid',$id);
		if(!$row) exit('视频不存~');

		//判断是否收藏
		$rows = $this->csdb->get_row('fav','id',array('uid'=>$_SESSION['user_id'],'did'=>$id));
		if($rows) exit('您已经收藏了该视频~');

		$add['did'] = $id;
		$add['cid'] = $row->cid;
		$add['uid'] = $_SESSION['user_id'];
		$add['addtime'] = time();
		$res = $this->csdb->get_insert('fav',$add);
		if($res){
			exit('ok');
		}else{
			exit('收藏失败~!');
		}
	}

	//签到
    public function qd(){
        $str = '系统异常，请稍后再试';
        $zt = 'no';
        if(isset($_SESSION['user_id'])){
        	$row = $this->csdb->get_row('user','cion,qdtime,qdday,qdnum',array('id'=>$_SESSION['user_id']));
			if($row){
				if($row->qdtime>0 && date('Y-m-d',$row->qdtime) == date('Y-m-d')){
					$str = '今日已签到';
					$zt = 'yes';
				}else{
					$edit['qdday'] = $row->qdday+1;
					$time1 = time()-86400;
					if(date('Y-m-d',$row->qdtime) != date('Y-m-d',$time1)){
						$edit['qdday'] = 1;
					}
					$edit['qdtime'] = time();
					$edit['qdnum'] = $row->qdnum+1;
					$edit['cion'] = $row->cion+User_Qd_Cion;
					$this->csdb->get_update('user',$_SESSION['user_id'],$edit);
					$str = '签到成功';
					if(User_Qd_Cion > 0){
						$str .= '，系统已赠送'.User_Qd_Cion.'个金币';
						$zt = 'ok';
					}
				}
			}else{
        		$str = '非法操作';
			}
        }else{
        	$str = '登录超时';
        }
        echo json_encode(array('zt'=>$zt,'str'=>$str));
	}
}
