DROP TABLE IF EXISTS `{Prefix}admin`;#ctcms#
CREATE TABLE `{Prefix}admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT '',
  `nichen` varchar(64) DEFAULT '',
  `pass` varchar(64) NOT NULL DEFAULT '',
  `logip` varchar(20) DEFAULT '',
  `lognum` int(10) DEFAULT '0',
  `logtime` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#ctcms#

DROP TABLE IF EXISTS `{Prefix}admin_log`;#ctcms#
CREATE TABLE `{Prefix}admin_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned DEFAULT '0',
  `ip` varchar(20) DEFAULT '',
  `ua` varchar(255) DEFAULT '',
  `logtime` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#ctcms#

DROP TABLE IF EXISTS `{Prefix}ads`;#ctcms#
CREATE TABLE `{Prefix}ads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT '',
  `yid` tinyint(1) DEFAULT '0',
  `bs` varchar(64) DEFAULT '',
  `neir` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#ctcms#

DROP TABLE IF EXISTS `{Prefix}buy`;#ctcms#
CREATE TABLE `{Prefix}buy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned DEFAULT '0',
  `cid` int(8) unsigned DEFAULT '0',
  `did` int(10) unsigned DEFAULT '0',
  `cion` int(10) unsigned DEFAULT '0',
  `addtime` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#ctcms#

DROP TABLE IF EXISTS `{Prefix}card`;#ctcms#
CREATE TABLE `{Prefix}card` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kh` varchar(64) DEFAULT '',
  `pass` varchar(64) DEFAULT '',
  `cid` tinyint(1) DEFAULT '0',
  `cion` int(10) unsigned DEFAULT '0',
  `day` int(10) unsigned DEFAULT '0',
  `uid` int(10) unsigned DEFAULT '0',
  `totime` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#ctcms#

DROP TABLE IF EXISTS `{Prefix}circle`;#ctcms#
CREATE TABLE `{Prefix}circle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT '',
  `xid` int(5) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#ctcms#

DROP TABLE IF EXISTS `{Prefix}class`;#ctcms#
CREATE TABLE `{Prefix}class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT '',
  `xid` int(10) unsigned DEFAULT '0',
  `fid` int(10) unsigned DEFAULT '0',
  `skin` varchar(64) DEFAULT 'list.html',
  `title` varchar(128) DEFAULT '',
  `keywords` varchar(128) DEFAULT '',
  `description` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#ctcms#

DROP TABLE IF EXISTS `{Prefix}coll`;#ctcms#
CREATE TABLE `{Prefix}coll` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '收藏id',
  `uid` int(11) DEFAULT NULL COMMENT '用户id',
  `tid` int(11) unsigned DEFAULT NULL COMMENT '文章id',
  `addtime` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#ctcms#

DROP TABLE IF EXISTS `{Prefix}comm`;#ctcms#
CREATE TABLE `{Prefix}comm` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned DEFAULT '0' COMMENT '用户id',
  `cid` int(11) unsigned DEFAULT '0' COMMENT '类型',
  `title` varchar(255) DEFAULT '' COMMENT '文章标题',
  `content` text COMMENT '文章内容',
  `pvnum` int(11) unsigned DEFAULT '0' COMMENT '浏览量',
  `dznum` int(11) unsigned DEFAULT '0' COMMENT '点赞数量',
  `collnum` int(11) DEFAULT '0' COMMENT '收藏数量',
  `addtime` int(10) unsigned DEFAULT '0' COMMENT '发表时间',
  PRIMARY KEY (`id`),
  KEY `addtime` (`addtime`) USING BTREE,
  KEY `cid` (`cid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#ctcms#

DROP TABLE IF EXISTS `{Prefix}danmu`;#ctcms#
CREATE TABLE `{Prefix}danmu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0',
  `did` int(11) DEFAULT '0',
  `text` varchar(255) DEFAULT '',
  `addtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `did` (`did`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#ctcms#

DROP TABLE IF EXISTS `{Prefix}dz`;#ctcms#
CREATE TABLE `{Prefix}dz` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '点赞id',
  `uid` int(11) unsigned DEFAULT NULL COMMENT '点赞用户',
  `tid` int(11) DEFAULT NULL COMMENT '文章id',
  `addtime` int(10) DEFAULT NULL COMMENT '点赞时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#ctcms#

DROP TABLE IF EXISTS `{Prefix}error`;#ctcms#
CREATE TABLE `{Prefix}error` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  `did` int(10) unsigned DEFAULT '0',
  `zu` int(10) unsigned DEFAULT '0',
  `ji` int(10) unsigned DEFAULT '0',
  `addtime` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `did` (`did`) USING BTREE,
  KEY `zu` (`zu`) USING BTREE,
  KEY `ji` (`ji`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#ctcms#

DROP TABLE IF EXISTS `{Prefix}fav`;#ctcms#
CREATE TABLE `{Prefix}fav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `did` int(10) DEFAULT '0',
  `cid` int(10) DEFAULT '0',
  `uid` int(10) DEFAULT '0',
  `addtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#ctcms#

DROP TABLE IF EXISTS `{Prefix}fxlist`;#ctcms#
CREATE TABLE `{Prefix}fxlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uida` int(10) unsigned DEFAULT '0',
  `uidb` int(10) unsigned DEFAULT '0',
  `rmb` decimal(10,2) DEFAULT NULL,
  `fcrmb` decimal(10,2) unsigned DEFAULT '0.00',
  `addtime` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#ctcms#

DROP TABLE IF EXISTS `{Prefix}gbook`;#ctcms#
CREATE TABLE `{Prefix}gbook` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `yid` tinyint(1) DEFAULT '0',
  `name` varchar(64) DEFAULT '',
  `content` text,
  `hfcontent` text,
  `addtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#ctcms#

DROP TABLE IF EXISTS `{Prefix}link`;#ctcms#
CREATE TABLE `{Prefix}link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned DEFAULT '0',
  `name` varchar(64) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `pic` varchar(64) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#ctcms#

DROP TABLE IF EXISTS `{Prefix}liwu`;#ctcms#
CREATE TABLE `{Prefix}liwu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT '',
  `txt` varchar(255) DEFAULT '',
  `pic` varchar(255) DEFAULT '',
  `cion` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#ctcms#

DROP TABLE IF EXISTS `{Prefix}liwu_list`;#ctcms#
CREATE TABLE `{Prefix}liwu_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lid` int(10) unsigned DEFAULT '0',
  `did` int(10) unsigned DEFAULT '0',
  `uid` int(10) unsigned DEFAULT '0',
  `num` int(10) unsigned DEFAULT '0',
  `cion` int(10) unsigned DEFAULT '0',
  `addtime` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#ctcms#

DROP TABLE IF EXISTS `{Prefix}msg`;#ctcms#
CREATE TABLE `{Prefix}msg` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '留言id',
  `tid` int(11) unsigned DEFAULT '0' COMMENT '文章id',
  `uid` int(11) unsigned DEFAULT '0' COMMENT '用户id',
  `ruid` int(11) unsigned DEFAULT '0' COMMENT '上家id',
  `rid` int(11) unsigned DEFAULT '0' COMMENT '回复id,直接对文章回复为0',
  `content` varchar(255) DEFAULT NULL,
  `addtime` int(10) DEFAULT '0' COMMENT '留言时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#ctcms#

DROP TABLE IF EXISTS `{Prefix}pages`;#ctcms#
CREATE TABLE `{Prefix}pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bs` varchar(30) DEFAULT '',
  `name` varchar(64) DEFAULT '',
  `yid` tinyint(1) DEFAULT '0',
  `text` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#ctcms#

DROP TABLE IF EXISTS `{Prefix}pay`;#ctcms#
CREATE TABLE `{Prefix}pay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` tinyint(1) DEFAULT '0',
  `dingdan` varchar(64) DEFAULT '',
  `uid` int(10) unsigned DEFAULT '0',
  `rmb` decimal(10,2) unsigned DEFAULT '0.00',
  `day` int(3) unsigned DEFAULT '0',
  `pid` tinyint(1) DEFAULT '0',
  `sid` tinyint(1) DEFAULT '0',
  `type` varchar(20) DEFAULT '',
  `addtime` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#ctcms#

DROP TABLE IF EXISTS `{Prefix}pic`;#ctcms#
CREATE TABLE `{Prefix}pic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `did` int(11) DEFAULT '0',
  `url` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `did` (`did`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#ctcms#

DROP TABLE IF EXISTS `{Prefix}pl`;#ctcms#
CREATE TABLE `{Prefix}pl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned DEFAULT '0',
  `did` int(11) DEFAULT '0',
  `text` varchar(255) DEFAULT '',
  `fid` int(11) unsigned DEFAULT '0',
  `ding` int(11) DEFAULT '0',
  `addtime` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `addtime` (`addtime`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#ctcms#

DROP TABLE IF EXISTS `{Prefix}player`;#ctcms#
CREATE TABLE `{Prefix}player` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT '',
  `text` varchar(255) DEFAULT '',
  `bs` varchar(64) DEFAULT '',
  `js` text,
  `xid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `bs` (`bs`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#ctcms#

DROP TABLE IF EXISTS `{Prefix}pl_zan`;#ctcms#
CREATE TABLE `{Prefix}pl_zan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned DEFAULT '0' COMMENT '点赞用户',
  `did` int(11) DEFAULT '0' COMMENT '评论id',
  `addtime` int(10) DEFAULT '0' COMMENT '点赞时间',
  PRIMARY KEY (`id`),
  KEY `uid_did` (`uid`,`did`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#ctcms#

DROP TABLE IF EXISTS `{Prefix}shikan`;#ctcms#
CREATE TABLE `{Prefix}shikan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `num` int(11) unsigned DEFAULT '0',
  `ip` varchar(20) DEFAULT '',
  `day` varchar(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#ctcms#

DROP TABLE IF EXISTS `{Prefix}tixian`;#ctcms#
CREATE TABLE `{Prefix}tixian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned DEFAULT '0',
  `rmb` decimal(10,2) unsigned DEFAULT '0.00',
  `pid` tinyint(1) DEFAULT '0',
  `pay` varchar(255) DEFAULT '',
  `err` varchar(128) DEFAULT '',
  `addtime` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#ctcms#

DROP TABLE IF EXISTS `{Prefix}topic`;#ctcms#
CREATE TABLE `{Prefix}topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT '',
  `pic` varchar(255) DEFAULT '',
  `tpic` varchar(255) DEFAULT '',
  `skin` varchar(64) DEFAULT '',
  `hits` int(10) unsigned DEFAULT '0',
  `text` text,
  `addtime` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `hits` (`hits`) USING BTREE,
  KEY `addtime` (`addtime`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#ctcms#

DROP TABLE IF EXISTS `{Prefix}user`;#ctcms#
CREATE TABLE `{Prefix}user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT '',
  `pass` varchar(35) DEFAULT '',
  `nichen` varchar(64) DEFAULT '',
  `sex` tinyint(1) DEFAULT '0',
  `email` varchar(64) DEFAULT '',
  `tel` varchar(20) DEFAULT '',
  `qq` varchar(30) DEFAULT '',
  `pic` varchar(255) DEFAULT '',
  `rmb` decimal(10,2) DEFAULT '0.00',
  `cion` int(10) DEFAULT '0',
  `vip` tinyint(1) DEFAULT '0',
  `viptime` int(10) DEFAULT '0',
  `regtime` int(10) DEFAULT '0',
  `logtime` int(10) DEFAULT '0',
  `lognum` int(10) DEFAULT '0',
  `logip` varchar(20) DEFAULT '',
  `qdtime` int(10) DEFAULT '0',
  `qdday` int(10) DEFAULT '0',
  `qdnum` int(10) DEFAULT '0',
  `uid` int(10) DEFAULT '0',
  `qqid` varchar(128) DEFAULT '',
  `wxid` varchar(128) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#ctcms#

DROP TABLE IF EXISTS `{Prefix}vod`;#ctcms#
CREATE TABLE `{Prefix}vod` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '',
  `pic` varchar(255) DEFAULT '',
  `pic2` varchar(255) DEFAULT '',
  `uid` int(10) unsigned DEFAULT '0',
  `cid` int(10) unsigned DEFAULT '0',
  `tid` tinyint(1) DEFAULT '0',
  `zid` tinyint(1) DEFAULT '0',
  `yid` tinyint(1) DEFAULT '0',
  `kid` tinyint(1) DEFAULT '0',
  `ztid` int(10) unsigned DEFAULT '0',
  `tags` varchar(255) DEFAULT '' COMMENT '关键字',
  `state` varchar(64) DEFAULT '',
  `daoyan` varchar(128) DEFAULT '',
  `zhuyan` varchar(128) DEFAULT '',
  `type` varchar(128) DEFAULT '',
  `diqu` varchar(128) DEFAULT '',
  `yuyan` varchar(128) DEFAULT '',
  `year` varchar(64) DEFAULT '',
  `info` varchar(64) DEFAULT '',
  `hits` int(10) unsigned DEFAULT '0',
  `yhits` int(10) unsigned DEFAULT '0',
  `zhits` int(10) unsigned DEFAULT '0',
  `rhits` int(10) unsigned DEFAULT '0',
  `dhits` int(11) DEFAULT '0',
  `cion` int(10) unsigned DEFAULT '0',
  `vip` tinyint(1) DEFAULT '0',
  `text` text,
  `skin` varchar(64) DEFAULT 'play.html',
  `url` mediumtext,
  `down` mediumtext,
  `pf` float(4,1) DEFAULT '10.0',
  `pf1` int(10) DEFAULT '0',
  `pf2` int(10) DEFAULT '0',
  `pf3` int(10) DEFAULT '0',
  `pf4` int(10) DEFAULT '0',
  `pf5` int(10) DEFAULT '0',
  `addtime` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `addtime` (`addtime`) USING BTREE,
  KEY `cid` (`cid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `ztid` (`ztid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#ctcms#

DROP TABLE IF EXISTS `{Prefix}zan`;#ctcms#
CREATE TABLE `{Prefix}zan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned DEFAULT '0' COMMENT '点赞用户',
  `did` int(11) DEFAULT '0' COMMENT '视频id',
  `addtime` int(10) DEFAULT '0' COMMENT '点赞时间',
  PRIMARY KEY (`id`),
  KEY `uid_did` (`uid`,`did`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#ctcms#

DROP TABLE IF EXISTS `{Prefix}zhuanma`;#ctcms#
CREATE TABLE `{Prefix}zhuanma` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sid` tinyint(1) DEFAULT '0',
  `duration` int(11) DEFAULT '0',
  `path` varchar(255) DEFAULT '',
  `m3u8_dir` varchar(255) DEFAULT '',
  `m3u8_path` varchar(255) DEFAULT '',
  `pic_path` varchar(255) DEFAULT '',
  `addtime` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `m3u8` (`m3u8_path`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;#ctcms#

INSERT INTO `{Prefix}link` (`id`, `cid`, `name`, `link`, `pic`) VALUES (1, 0, 'Ctcms官网', 'http://www.ctcms.cn/', '');#ctcms#
INSERT INTO `{Prefix}class` (`id`, `name`, `fid`, `xid`) VALUES (1, '电影', 0, 1);#ctcms#
INSERT INTO `{Prefix}class` (`id`, `name`, `fid`, `xid`) VALUES (2, '电视剧', 0, 2);#ctcms#
INSERT INTO `{Prefix}class` (`id`, `name`, `fid`, `xid`) VALUES (3, '动漫', 0, 3);#ctcms#
INSERT INTO `{Prefix}class` (`id`, `name`, `fid`, `xid`) VALUES (4, '综艺', 0, 4);#ctcms#
INSERT INTO `{Prefix}player` (`id`, `name`, `text`, `bs`, `js`, `xid`) VALUES (1, '视频云',  '云解析各大视频站',  'ydisk', '&lt;iframe src=&quot;{ctcms_path}packs/player/ydisk/?url={url}&quot; marginwidth=&quot;0&quot; marginheight=&quot;0&quot; border=&quot;0&quot; scrolling=&quot;no&quot; frameborder=&quot;0&quot; topmargin=&quot;0&quot; width=&quot;100%&quot; height=&quot;100%&quot;&gt;&lt;/iframe&gt;', 1);#ctcms#
INSERT INTO `{Prefix}player` (`id`, `name`, `text`, `bs`, `js`, `xid`) VALUES (2, 'CK播放器',  '支持播放flv、mp4、m3u8视频',  'ck', '&lt;div id=&quot;a1&quot; style=&quot;width:100%;height:100%;&quot;&gt;&lt;/div&gt;
&lt;script type=&quot;text/javascript&quot; src=&quot;{ctcms_path}packs/player/ckplayer/ckplayer.js&quot; charset=&quot;utf-8&quot;&gt;&lt;/script&gt;
&lt;script type=&quot;text/javascript&quot;&gt;
var purl = &#039;{url}&#039;;
if(purl.indexOf(&#039;.m3u8&#039;) &gt; -1){
var flashvars = {f: &#039;{ctcms_path}packs/player/ckplayer/m3u8.swf&#039;,a: purl,c: 0,s: 4,lv: 0,p: 1}
}else{
var flashvars={f:purl,c:0,p:1};
}
var isiPad = navigator.userAgent.match(/iPad|iPhone|Linux|Android|iPod/i) != null;
if(isiPad){
document.getElementById(&quot;a1&quot;).innerHTML=&#039;&lt;video src=&quot;{url}&quot; controls=&quot;controls&quot; autoplay=&quot;autoplay&quot; width=&quot;100%&quot; height=&quot;100%&quot;&gt;&lt;/video&gt;&#039;;
}else{
var params={bgcolor:&#039;#000000&#039;,allowFullScreen:true,allowScriptAccess:&#039;always&#039;,wmode:&#039;transparent&#039;};
CKobject.embedSWF(&#039;{ctcms_path}packs/player/ckplayer/ckplayer.swf&#039;,&#039;a1&#039;,&#039;ckplayer_a1&#039;,&#039;100%&#039;,&#039;100%&#039;,flashvars,params);
}
&lt;/script&gt;', 2);