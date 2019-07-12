<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Xyz {
    function __construct() {
        set_time_limit(0);
        $xt = php_uname();
        if(strpos($xt, 'Windows') !== false){
        	$this->sypath = 'packs/ffmpeg/logo.png';
            $this->ffmpeg = FCPATH.'packs/ffmpeg/ffmpeg.exe';
            $this->ffprobe = FCPATH.'packs/ffmpeg/ffprobe.exe';
        }else{
        	$this->sypath = FCPATH.'packs/ffmpeg/logo.png';
            $this->ffmpeg = FCPATH.'packs/ffmpeg/ffmpeg';
            $this->ffprobe = FCPATH.'packs/ffmpeg/ffprobe';
        }
    }

    public function transcode($src_path,$m3u8_path='',$jpg_path=''){
		$src_path = str_replace("//","/",str_replace("\\","/",$src_path));
        //获取格式命令
        $format = $this->format($src_path);
		$m3u8_path = str_replace("//","/",str_replace("\\","/",$m3u8_path));
		//M3U8保存目录
        $obj_path = dirname($m3u8_path);
        //先创建目录
        mkdirss($obj_path);
        //jpg
        $jpg = $this->vodtojpg($src_path,$jpg_path);
        //水印
        $watermark = $this->watermark();
        //缩放
        $change = Zhuan_Size !== '' ? '-s '.Zhuan_Size : '';
        //码率
        $Zhuan_Kbps = (int)Zhuan_Kbps;
        $bit_rate = (int)$format['bit_rate'];
        $kbps = '';
        if($bit_rate > 0){
        	$bit = $bit_rate / 1000;
        	if($Zhuan_Kbps > 0 && $bit > $Zhuan_Kbps){
                $kbps = '-b:v '.$Zhuan_Kbps.'k';
        	}
        }
        //速度 -preset:v ultrafast,superfast,veryfast,faster,fast,medium,slow,slower
        $preset = '-preset:v fast';
        //执行转换
        if($format['audio']=='aac' && $format['video'] == 'h264'){
            $make_command = $this->ffmpeg.' -y -i '.$src_path.' '.$watermark.' '.$change.' '.$kbps.' '.$preset.' -hls_time '.Zhuan_Time.' -hls_segment_filename '.$obj_path.'/%04d.ts -hls_list_size 0 '.$m3u8_path;
        }else{
            $make_command = $this->ffmpeg.' -y -i '.$src_path.' '.$watermark.' '.$change.' '.$kbps.' '.$preset.' -c:v libx264 -c:a aac -strict -2 -f hls -hls_list_size 0 -hls_time '.Zhuan_Time.' -hls_segment_filename '.$obj_path.'/%04d.ts '.$m3u8_path;
        }
        $result = exec($make_command,$arr,$log);
        if($log == 0){
            return 'ok';
        }else{
            return 'no';
        }
    }
    //获取视频格式信息
    public function format($src_path){
		$src_path = str_replace("//","/",str_replace("\\","/",$src_path));
        $arr = array(
            'video' => '',
            'audio' => '',
            'duration' => 0,
            'width' => 0,
            'height' => 0,
            'dis_ratio' => '',
            'size' => 0
        );
        if(empty($src_path)) return $arr;
        $format_command = $this->ffprobe.' -v quiet -print_format json -show_format -show_streams '.$src_path;
        $format = shell_exec($format_command);
        $json = json_decode($format);
        $audio = '';$video = '';
        foreach($json->streams as $row){
            if($row->codec_type=='video'){
                $arr['video'] = $row->codec_name;
                $arr['duration'] = $row->duration;
                $arr['width'] = $row->width;
                $arr['height'] = $row->height;
                $arr['dis_ratio']= $row->display_aspect_ratio;
            }
            if($row->codec_type=='audio'){
                $arr['audio'] = $row->codec_name;
            }
        }
		if(empty($arr['duration'])) $arr['duration'] = $json->format->duration;
        $arr['size'] = $json->format->size;
        $arr['bit_rate'] = $json->format->bit_rate;
        return $arr;
    }
    //视频截图JPG
    function vodtojpg($src_path,$jpg_path){
        $size = Zhuan_Jpg_Size != '' ? '-s '.Jpg_Size : '';
        $jpg_command = $this->ffmpeg.' -y -i '.$src_path.' -y -f image2 -ss '.Zhuan_Jpg_Time.' '.$size.' -t 0.001 '.$jpg_path;
        $jpg = exec($jpg_command,$arr,$log);
        for($i=1; $i<=Zhuan_Jpg_Num;$i++){
        	$jpg_path2 = str_replace('.jpg', '_'.$i.'.jpg', $jpg_path);
        	$Zhuan_Jpg_Time = Zhuan_Jpg_Time*$i;
        	$jpg_command = $this->ffmpeg.' -y -i '.$src_path.' -y -f image2 -ss '.$Zhuan_Jpg_Time.' '.$size.' -t 0.001 '.$jpg_path2;
        	$jpg = exec($jpg_command,$arr,$log2);
        }
        if($log==0){
            return 'ok';
        }else{
            return 'no';
        }
    }
    //水印
    function watermark(){
		if(Zhuan_Sy_Is == 0) return '';
    	$cmd = '';
        $pos_sign = Zhuan_Sy_Pos;//方位
        $pos_value = Zhuan_Sy_Lt;//距离
        $mar1 = $mar2 = 10;
        if(!empty($pos_value)){
            $mar_arr = explode(':', $pos_value);
            $mar1 = intval($mar_arr[0]);
            $mar2 = intval($mar_arr[1]);
        }
        $wm = 'overlay='.$mar1.':'.$mar2;
        if($pos_sign==1) $wm = 'overlay=main_w-overlay_w-'.$mar1.':'.$mar2;
        if($pos_sign==2) $wm = 'overlay='.$mar1.':main_h-overlay_h-'.$mar2;
        if($pos_sign==3) $wm = 'overlay=main_w-overlay_w-'.$mar1.':main_h-overlay_h-'.$mar2;
        $cmd = '-vf "movie='.$this->sypath.'[awm];[in][awm] '.$wm.' [out]"';
        return $cmd;
    }
}
