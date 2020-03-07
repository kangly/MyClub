# ************************************************************
# Sequel Pro SQL dump
# Version 4499
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.16)
# Database: club
# Generation Time: 2018-10-11 01:43:30 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table member
# ------------------------------------------------------------

DROP TABLE IF EXISTS `member`;

CREATE TABLE `member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '会员表id',
  `openid` varchar(30) NOT NULL DEFAULT '' COMMENT '微信openid',
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '会员名',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '邮箱',
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号',
  `nickname` varchar(50) DEFAULT NULL COMMENT '昵称',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '密码',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `last_login_ip` char(15) NOT NULL DEFAULT '' COMMENT '最后登录ip',
  `last_login_time` datetime DEFAULT NULL COMMENT '最后登录时间',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


# Dump of table member_openid
# ------------------------------------------------------------

DROP TABLE IF EXISTS `member_openid`;

CREATE TABLE `member_openid` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户与openid关系表id',
  `member_id` int(10) unsigned NOT NULL COMMENT '用户id',
  `openid` varchar(30) NOT NULL DEFAULT '' COMMENT '微信openid',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`),
  KEY `openid` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


# Dump of table article
# ------------------------------------------------------------

DROP TABLE IF EXISTS `article`;

CREATE TABLE `article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文章表id',
  `column_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '栏目id',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '文章标题',
  `summary` varchar(300) NOT NULL DEFAULT '' COMMENT '文章简介',
  `content` text COMMENT '文章内容',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建人id',
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '创建人姓名',
  `source` varchar(50) NOT NULL DEFAULT '' COMMENT '文章来源',
  `source_link` varchar(100) NOT NULL DEFAULT '' COMMENT '文章来源链接',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '栏目内排序',
  `is_publish` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否发布',
  `is_recommend` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否首页推荐',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `column_id` (`column_id`),
  KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `article` WRITE;
/*!40000 ALTER TABLE `article` DISABLE KEYS */;

INSERT INTO `article` (`id`, `column_id`, `title`, `content`, `user_id`, `username`, `source`, `source_link`, `sort`, `is_publish`, `create_time`)
VALUES
  (1,1,'测试','<p>这是一篇测试文章。</p><p>如果你想了解更多，请关注后续更新内容。</p>',1,'admin',NULL,NULL,0,1,'2017-12-27 15:36:20'),
  (2,1,'欢迎博客','<p>这是本博客的欢迎页面。</p>',1,'admin','','',0,1,'2017-12-27 16:34:53');

/*!40000 ALTER TABLE `article` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table article_column
# ------------------------------------------------------------

DROP TABLE IF EXISTS `article_column`;

CREATE TABLE `article_column` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文章栏目表id',
  `pid` int(10) unsigned NOT NULL COMMENT '父级栏目id',
  `title` varchar(50) NOT NULL COMMENT '栏目标题',
  `intro` varchar(100) NOT NULL COMMENT '栏目介绍',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建人id',
  `username` varchar(50) DEFAULT NULL COMMENT '创建人姓名',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `article_column` WRITE;
/*!40000 ALTER TABLE `article_column` DISABLE KEYS */;

INSERT INTO `article_column` (`id`, `pid`, `title`, `intro`, `user_id`, `username`, `create_time`)
VALUES
  (1,0,'未分类','这是一条未分类栏目',1,'admin','2018-01-08 14:11:50');

/*!40000 ALTER TABLE `article_column` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table article_store
# ------------------------------------------------------------

DROP TABLE IF EXISTS `article_store`;

CREATE TABLE `article_store` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文章收藏表id',
  `article_id` int(10) unsigned NOT NULL default '0' COMMENT '文章id',
  `member_id` int(10) unsigned NOT NULL default '0' COMMENT '用户id',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `article_id` (`article_id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


# Dump of table article_visit
# ------------------------------------------------------------

DROP TABLE IF EXISTS `article_visit`;

CREATE TABLE `article_visit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文章浏览表id',
  `article_id` int(10) unsigned NOT NULL default '0' COMMENT '文章id',
  `member_id` int(10) unsigned NOT NULL default '0' COMMENT '用户id',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `article_id` (`article_id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


# Dump of table auth_group
# ------------------------------------------------------------

DROP TABLE IF EXISTS `auth_group`;

CREATE TABLE `auth_group` (
  `id` mediumint(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户组表id',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '用户组名称',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：为1正常，为0禁用',
  `rules` text COMMENT '用户组拥有的规则id，多个规则","隔开',
  `undetermined_rules` char(50) DEFAULT NULL COMMENT '待定规则id，多个规则","隔开，jstree的checkbox待定状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `auth_group` WRITE;
/*!40000 ALTER TABLE `auth_group` DISABLE KEYS */;

INSERT INTO `auth_group` (`id`, `title`, `status`, `rules`, `undetermined_rules`)
VALUES
  (1,'超级管理员',1,'1,31,3,4,2,34,35,43,33,44,45,46,47,36,32,8,9,10,11,12,13,14,15,6,16,17,18,21,48','');

/*!40000 ALTER TABLE `auth_group` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table auth_group_access
# ------------------------------------------------------------

DROP TABLE IF EXISTS `auth_group_access`;

CREATE TABLE `auth_group_access` (
  `uid` mediumint(10) unsigned NOT NULL COMMENT '用户id',
  `group_id` mediumint(10) unsigned NOT NULL COMMENT '用户组id',
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `auth_group_access` WRITE;
/*!40000 ALTER TABLE `auth_group_access` DISABLE KEYS */;

INSERT INTO `auth_group_access` (`uid`, `group_id`)
VALUES
  (1,1);

/*!40000 ALTER TABLE `auth_group_access` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table auth_rule
# ------------------------------------------------------------

DROP TABLE IF EXISTS `auth_rule`;

CREATE TABLE `auth_rule` (
  `id` mediumint(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则表id',
  `pid` int(10) unsigned DEFAULT '0' COMMENT '父级id',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '规则唯一标识',
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '规则名称',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'type字段，目前暂时理解为规则类型，例如，1为后台管理类型，2为前台用户类型',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：为1正常，为0禁用',
  `icon` varchar(20) DEFAULT NULL COMMENT '图标class名称',
  `order_num` smallint(4) DEFAULT '100' COMMENT '排序',
  `is_menu` tinyint(1) DEFAULT '0' COMMENT '是否菜单项',
  `condition` varchar(100) NOT NULL DEFAULT '' COMMENT '规则表达式，为空表示存在就验证，不为空表示按照条件验证',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `auth_rule` WRITE;
/*!40000 ALTER TABLE `auth_rule` DISABLE KEYS */;

INSERT INTO `auth_rule` (`id`, `pid`, `name`, `title`, `type`, `status`, `icon`, `order_num`, `is_menu`, `condition`)
VALUES
  (1,0,'/admin/index/index','控制台',1,1,'mdi-view-dashboard',100,1,''),
  (2,0,'/admin/system','系统管理',1,1,'icon-cog',100,1,''),
  (3,2,'/admin/system/users','用户',1,1,NULL,100,1,''),
  (4,2,'/admin/system/groups','用户组',1,1,NULL,100,1,''),
  (6,2,'/admin/system/rules','规则',1,1,NULL,100,1,''),
  (8,3,'/admin/system/user_list','用户列表',1,1,NULL,100,0,''),
  (9,3,'/admin/system/add_user','添加用户',1,1,NULL,100,0,''),
  (10,3,'/admin/system/save_user','保存用户',1,1,NULL,100,0,''),
  (11,3,'/admin/system/delete_user','删除用户',1,1,NULL,100,0,''),
  (12,4,'/admin/system/group_list','用户组列表',1,1,NULL,100,0,''),
  (13,4,'/admin/system/add_group','添加用户组',1,1,NULL,100,0,''),
  (14,4,'/admin/system/save_group','保存用户组',1,1,NULL,100,0,''),
  (15,4,'/admin/system/delete_group','删除用户组',1,1,NULL,100,0,''),
  (16,6,'/admin/system/rules_list','规则列表',1,1,NULL,100,0,''),
  (17,6,'/admin/system/add_rule','添加规则',1,1,NULL,100,0,''),
  (18,6,'/admin/system/save_rule','保存规则',1,1,NULL,100,0,''),
  (21,6,'/admin/system/delete_rule','删除规则',1,1,NULL,100,0,''),
  (31,3,'/admin/system/save_editable_user','保存用户点击编辑',1,1,'',100,0,''),
  (32,0,'/admin/article','文章管理',1,1,'icon-book',100,1,''),
  (33,32,'/admin/article/index','文章列表',1,1,'',100,1,''),
  (34,33,'/admin/article/add_article','新增文章',1,1,'',100,0,''),
  (35,33,'/admin/article/delete_article','删除文章',1,1,'',100,0,''),
  (36,32,'/admin/article/column','文章栏目',1,1,'',100,1,''),
  (43,33,'/admin/article/articles_list','文章列表数据',1,1,'',100,0,''),
  (44,36,'/admin/article/columns_list','文章栏目数据',1,1,'',100,0,''),
  (45,36,'/admin/article/add_column','新增文章栏目',1,1,'',100,0,''),
  (46,36,'/admin/article/save_column','保存文章栏目',1,1,'',100,0,''),
  (47,36,'/admin/article/delete_cloumn','删除文章栏目',1,1,'',100,0,''),
  (48,33,'/admin/article/save_article','保存文章',1,1,'',100,0,'');

/*!40000 ALTER TABLE `auth_rule` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户表id',
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '邮箱',
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号',
  `nickname` varchar(50) DEFAULT NULL COMMENT '昵称',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '密码',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `last_login_ip` char(15) NOT NULL DEFAULT '' COMMENT '最后登录ip',
  `last_login_time` datetime DEFAULT NULL COMMENT '最后登录时间',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;

INSERT INTO `user` (`id`, `username`, `email`, `mobile`, `nickname`, `password`, `status`, `last_login_ip`, `last_login_time`, `create_time`)
VALUES
  (1,'kangly','371976974@qq.com','15227188888','小康','e19d5cd5af0378da05f63f891c7467af',1,'127.0.0.1','2018-10-10 09:23:47','2018-09-07 17:40:00'),
  (2,'test','614797580@qq.com','15227166666','测试','e19d5cd5af0378da05f63f891c7467af',0,'',NULL,'2018-09-19 17:40:00');

/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
