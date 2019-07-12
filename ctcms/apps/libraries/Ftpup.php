<?php
/**
 * @Ctcms open source management system
 * @copyright 2016-2017 ctcms.cn. All rights reserved.
 * @Author:Chi Tu
 * @Dtime:2016-08-11
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * FTP上传操作类
 */
class Ftpup {

    public function __construct ()
	{
		set_time_limit(0); //超时时间
	}

    //FTP上传
	public function up($file_path,$file_name){
		if(Ftp_Is==2){ //贴图库

		    $data['Token']=Ftp_Token;
		    $data['file']='@'.$file_path;
			$data['deadline']=time()+60;
			$data['aid']=Ftp_Pid;
			$data['from']='file';
			$json=$this->uppost('http://up.tietuku.com/',$data);
			$arr=json_decode($json,true);
			if(!empty($arr['linkurl'])){
                unlink($file_path);
                return $arr['linkurl'].'#ttk';
			}else{
                return false;
			}

		}else{
			$ci = &get_instance();
			$ci->load->library('ftp');
			if ($ci->ftp->connect(array(
				'port' => Ftp_Port,
				'debug' => false,
				'passive' => Ftp_Ive,
				'hostname' => Ftp_Server,
				'username' => Ftp_User,
				'password' => Ftp_Pass,
			))) { // 连接ftp成功
				$Dirs=Ftp_Dir;
				if(substr($Dirs,-1)=='/') $Dirs=substr($Dirs,0,-1);
				$dir = $Dirs.'/'.date('Ymd').'/';
				$ci->ftp->mkdir($dir);
				if($ci->ftp->upload($file_path, $dir.$file_name, SITE_ATTACH_MODE, 0775)) {
				     unlink($file_path);
				}else{
                     return false;
				}
				$ci->ftp->close();
		        return str_replace($Dirs,'',$dir).$file_name;
			}
			return false;
		}
    }

    //删除远程FTP文件
    public function del($file_path){
		$ci = &get_instance();
		$ci->load->library('ftp');
		if(substr($file_path,0,1)=='/') $file_path=substr($file_path,1);
		if ($ci->ftp->connect(array(
				'port' => Ftp_Port,
				'debug' => FALSE,
				'passive' => Ftp_Ive,
				'hostname' => Ftp_Server,
				'username' => Ftp_User,
				'password' => Ftp_Pass,
		))) { // 连接ftp成功
				$Dirs=Ftp_Dir;
				if(substr($Dirs,-1)=='/') $Dirs=substr($Dirs,0,-1);
				$path = $Dirs.'/'.$file_path;
				$res=$ci->ftp->delete_file(".".$path);
				$ci->ftp->close();
                if (!$res) {  //失败
                    return FALSE;
                } else {  //成功
                    return TRUE;
                }
		}
    }

    public function uppost($url,$post_data){
		    $ch = curl_init();
		    curl_setopt($ch, CURLOPT_URL, $url);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($ch, CURLOPT_TIMEOUT,30);  
		    curl_setopt($ch, CURLOPT_POST, 1);
		    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		    $output = curl_exec($ch);
		    curl_close($ch);
		    return $output;
	}
}

