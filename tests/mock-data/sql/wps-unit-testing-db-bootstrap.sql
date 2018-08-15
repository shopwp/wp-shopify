# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: localhost (MySQL 5.6.14)
# Database: wps_unit_testing
# Generation Time: 2018-03-25 04:17:13 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table wptests_commentmeta
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wptests_commentmeta`;

CREATE TABLE `wptests_commentmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`meta_id`),
  KEY `comment_id` (`comment_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table wptests_comments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wptests_comments`;

CREATE TABLE `wptests_comments` (
  `comment_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_post_ID` bigint(20) unsigned NOT NULL DEFAULT '0',
  `comment_author` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment_author_email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_author_url` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_author_IP` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment_karma` int(11) NOT NULL DEFAULT '0',
  `comment_approved` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `comment_agent` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_ID`),
  KEY `comment_post_ID` (`comment_post_ID`),
  KEY `comment_approved_date_gmt` (`comment_approved`,`comment_date_gmt`),
  KEY `comment_date_gmt` (`comment_date_gmt`),
  KEY `comment_parent` (`comment_parent`),
  KEY `comment_author_email` (`comment_author_email`(10))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table wptests_links
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wptests_links`;

CREATE TABLE `wptests_links` (
  `link_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `link_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_target` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_visible` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Y',
  `link_owner` bigint(20) unsigned NOT NULL DEFAULT '1',
  `link_rating` int(11) NOT NULL DEFAULT '0',
  `link_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `link_rel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_notes` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_rss` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`link_id`),
  KEY `link_visible` (`link_visible`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table wptests_options
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wptests_options`;

CREATE TABLE `wptests_options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `option_value` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `autoload` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `wptests_options` WRITE;
/*!40000 ALTER TABLE `wptests_options` DISABLE KEYS */;

INSERT INTO `wptests_options` (`option_id`, `option_name`, `option_value`, `autoload`)
VALUES
	(2,'_site_transient_timeout_theme_roots','1521953219','no'),
	(3,'_site_transient_theme_roots','a:16:{s:12:\"broken-theme\";s:59:\"/private/tmp/wordpress-tests-lib/includes/../data/themedir1\";s:9:\"camelCase\";s:59:\"/private/tmp/wordpress-tests-lib/includes/../data/themedir1\";s:19:\"child-parent-itself\";s:59:\"/private/tmp/wordpress-tests-lib/includes/../data/themedir1\";s:7:\"default\";s:59:\"/private/tmp/wordpress-tests-lib/includes/../data/themedir1\";s:23:\"internationalized-theme\";s:59:\"/private/tmp/wordpress-tests-lib/includes/../data/themedir1\";s:20:\"page-templates-child\";s:59:\"/private/tmp/wordpress-tests-lib/includes/../data/themedir1\";s:14:\"page-templates\";s:59:\"/private/tmp/wordpress-tests-lib/includes/../data/themedir1\";s:7:\"sandbox\";s:59:\"/private/tmp/wordpress-tests-lib/includes/../data/themedir1\";s:14:\"stylesheetonly\";s:59:\"/private/tmp/wordpress-tests-lib/includes/../data/themedir1\";s:24:\"subdir/theme with spaces\";s:59:\"/private/tmp/wordpress-tests-lib/includes/../data/themedir1\";s:13:\"subdir/theme2\";s:59:\"/private/tmp/wordpress-tests-lib/includes/../data/themedir1\";s:11:\"theme1-dupe\";s:59:\"/private/tmp/wordpress-tests-lib/includes/../data/themedir1\";s:6:\"theme1\";s:59:\"/private/tmp/wordpress-tests-lib/includes/../data/themedir1\";s:13:\"twentyfifteen\";s:7:\"/themes\";s:15:\"twentyseventeen\";s:7:\"/themes\";s:13:\"twentysixteen\";s:7:\"/themes\";}','no'),
	(4,'siteurl','http://example.org','yes'),
	(5,'home','http://example.org','yes'),
	(6,'blogname','Test Blog','yes'),
	(7,'blogdescription','Just another WordPress site','yes'),
	(8,'users_can_register','0','yes'),
	(9,'admin_email','admin@example.org','yes'),
	(10,'start_of_week','1','yes'),
	(11,'use_balanceTags','0','yes'),
	(12,'use_smilies','1','yes'),
	(13,'require_name_email','1','yes'),
	(14,'comments_notify','1','yes'),
	(15,'posts_per_rss','10','yes'),
	(16,'rss_use_excerpt','0','yes'),
	(17,'mailserver_url','mail.example.com','yes'),
	(18,'mailserver_login','login@example.com','yes'),
	(19,'mailserver_pass','password','yes'),
	(20,'mailserver_port','110','yes'),
	(21,'default_category','1','yes'),
	(22,'default_comment_status','open','yes'),
	(23,'default_ping_status','open','yes'),
	(24,'default_pingback_flag','1','yes'),
	(25,'posts_per_page','10','yes'),
	(26,'date_format','F j, Y','yes'),
	(27,'time_format','g:i a','yes'),
	(28,'links_updated_date_format','F j, Y g:i a','yes'),
	(29,'comment_moderation','0','yes'),
	(30,'moderation_notify','1','yes'),
	(31,'rewrite_rules','','yes'),
	(32,'hack_file','0','yes'),
	(33,'blog_charset','UTF-8','yes'),
	(34,'moderation_keys','','no'),
	(35,'active_plugins','a:0:{}','yes'),
	(36,'category_base','','yes'),
	(37,'ping_sites','http://rpc.pingomatic.com/','yes'),
	(38,'comment_max_links','2','yes'),
	(39,'gmt_offset','0','yes'),
	(40,'default_email_category','1','yes'),
	(41,'recently_edited','','no'),
	(42,'template','default','yes'),
	(43,'stylesheet','default','yes'),
	(44,'comment_whitelist','1','yes'),
	(45,'blacklist_keys','','no'),
	(46,'comment_registration','0','yes'),
	(47,'html_type','text/html','yes'),
	(48,'use_trackback','0','yes'),
	(49,'default_role','subscriber','yes'),
	(50,'db_version','38590','yes'),
	(51,'uploads_use_yearmonth_folders','1','yes'),
	(52,'upload_path','','yes'),
	(53,'blog_public','1','yes'),
	(54,'default_link_category','2','yes'),
	(55,'show_on_front','posts','yes'),
	(56,'tag_base','','yes'),
	(57,'show_avatars','1','yes'),
	(58,'avatar_rating','G','yes'),
	(59,'upload_url_path','','yes'),
	(60,'thumbnail_size_w','150','yes'),
	(61,'thumbnail_size_h','150','yes'),
	(62,'thumbnail_crop','1','yes'),
	(63,'medium_size_w','300','yes'),
	(64,'medium_size_h','300','yes'),
	(65,'avatar_default','mystery','yes'),
	(66,'large_size_w','1024','yes'),
	(67,'large_size_h','1024','yes'),
	(68,'image_default_link_type','none','yes'),
	(69,'image_default_size','','yes'),
	(70,'image_default_align','','yes'),
	(71,'close_comments_for_old_posts','0','yes'),
	(72,'close_comments_days_old','14','yes'),
	(73,'thread_comments','1','yes'),
	(74,'thread_comments_depth','5','yes'),
	(75,'page_comments','0','yes'),
	(76,'comments_per_page','50','yes'),
	(77,'default_comments_page','newest','yes'),
	(78,'comment_order','asc','yes'),
	(79,'sticky_posts','a:0:{}','yes'),
	(80,'widget_categories','a:2:{i:2;a:4:{s:5:\"title\";s:0:\"\";s:5:\"count\";i:0;s:12:\"hierarchical\";i:0;s:8:\"dropdown\";i:0;}s:12:\"_multiwidget\";i:1;}','yes'),
	(81,'widget_text','a:0:{}','yes'),
	(82,'widget_rss','a:0:{}','yes'),
	(83,'delete_all_datas','a:0:{}','no'),
	(84,'timezone_string','','yes'),
	(85,'page_for_posts','0','yes'),
	(86,'page_on_front','0','yes'),
	(87,'default_post_format','0','yes'),
	(88,'link_manager_enabled','0','yes'),
	(89,'finished_splitting_shared_terms','1','yes'),
	(90,'site_icon','0','yes'),
	(91,'medium_large_size_w','768','yes'),
	(92,'medium_large_size_h','0','yes'),
	(93,'initial_db_version','38590','yes'),
	(94,'wptests_user_roles','a:5:{s:13:\"administrator\";a:2:{s:4:\"name\";s:13:\"Administrator\";s:12:\"capabilities\";a:61:{s:13:\"switch_themes\";b:1;s:11:\"edit_themes\";b:1;s:16:\"activate_plugins\";b:1;s:12:\"edit_plugins\";b:1;s:10:\"edit_users\";b:1;s:10:\"edit_files\";b:1;s:14:\"manage_options\";b:1;s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:6:\"import\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:8:\"level_10\";b:1;s:7:\"level_9\";b:1;s:7:\"level_8\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;s:12:\"delete_users\";b:1;s:12:\"create_users\";b:1;s:17:\"unfiltered_upload\";b:1;s:14:\"edit_dashboard\";b:1;s:14:\"update_plugins\";b:1;s:14:\"delete_plugins\";b:1;s:15:\"install_plugins\";b:1;s:13:\"update_themes\";b:1;s:14:\"install_themes\";b:1;s:11:\"update_core\";b:1;s:10:\"list_users\";b:1;s:12:\"remove_users\";b:1;s:13:\"promote_users\";b:1;s:18:\"edit_theme_options\";b:1;s:13:\"delete_themes\";b:1;s:6:\"export\";b:1;}}s:6:\"editor\";a:2:{s:4:\"name\";s:6:\"Editor\";s:12:\"capabilities\";a:34:{s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;}}s:6:\"author\";a:2:{s:4:\"name\";s:6:\"Author\";s:12:\"capabilities\";a:10:{s:12:\"upload_files\";b:1;s:10:\"edit_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:4:\"read\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:12:\"delete_posts\";b:1;s:22:\"delete_published_posts\";b:1;}}s:11:\"contributor\";a:2:{s:4:\"name\";s:11:\"Contributor\";s:12:\"capabilities\";a:5:{s:10:\"edit_posts\";b:1;s:4:\"read\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:12:\"delete_posts\";b:1;}}s:10:\"subscriber\";a:2:{s:4:\"name\";s:10:\"Subscriber\";s:12:\"capabilities\";a:2:{s:4:\"read\";b:1;s:7:\"level_0\";b:1;}}}','yes'),
	(95,'fresh_site','1','yes'),
	(96,'widget_search','a:2:{i:2;a:1:{s:5:\"title\";s:0:\"\";}s:12:\"_multiwidget\";i:1;}','yes'),
	(97,'widget_recent-posts','a:2:{i:2;a:2:{s:5:\"title\";s:0:\"\";s:6:\"number\";i:5;}s:12:\"_multiwidget\";i:1;}','yes'),
	(98,'widget_recent-comments','a:2:{i:2;a:2:{s:5:\"title\";s:0:\"\";s:6:\"number\";i:5;}s:12:\"_multiwidget\";i:1;}','yes'),
	(99,'widget_archives','a:2:{i:2;a:3:{s:5:\"title\";s:0:\"\";s:5:\"count\";i:0;s:8:\"dropdown\";i:0;}s:12:\"_multiwidget\";i:1;}','yes'),
	(100,'widget_meta','a:2:{i:2;a:1:{s:5:\"title\";s:0:\"\";}s:12:\"_multiwidget\";i:1;}','yes'),
	(101,'sidebars_widgets','a:5:{s:19:\"wp_inactive_widgets\";a:0:{}s:9:\"sidebar-1\";a:6:{i:0;s:8:\"search-2\";i:1;s:14:\"recent-posts-2\";i:2;s:17:\"recent-comments-2\";i:3;s:10:\"archives-2\";i:4;s:12:\"categories-2\";i:5;s:6:\"meta-2\";}s:9:\"sidebar-2\";a:0:{}s:9:\"sidebar-3\";a:0:{}s:13:\"array_version\";i:3;}','yes'),
	(102,'_transient_wps_table_exists_wptests_wps_settings_connection','1','yes'),
	(103,'_transient_wps_table_exists_wptests_wps_settings_general','1','yes'),
	(104,'_transient_wps_table_exists_wptests_wps_settings_license','1','yes'),
	(105,'widget_pages','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),
	(106,'widget_calendar','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),
	(107,'widget_media_audio','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),
	(108,'widget_media_image','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),
	(109,'widget_media_gallery','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),
	(110,'widget_media_video','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),
	(111,'nonce_key','1O>&QXSS=0LRl(4+75g*wt=!_M`[lHa{cFSfE51zpFi{GqmWU5HJSAW5R3<Lr~@w','no'),
	(112,'nonce_salt','o>TtT2_LOeH|1D?G0EsR,>O:[@vy*BdKL |0g/0C6uJH@_p_x#(0? =3g~h`N$&(','no'),
	(113,'widget_tag_cloud','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),
	(114,'widget_nav_menu','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),
	(115,'widget_custom_html','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),
	(116,'cron','a:2:{i:1521951420;a:3:{s:16:\"wp_version_check\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}s:17:\"wp_update_plugins\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}s:16:\"wp_update_themes\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}s:7:\"version\";i:2;}','yes'),
	(129,'_transient_wps_table_exists_wptests_wps_products','1','yes'),
	(134,'_transient_wps_table_exists_wptests_wps_variants','1','yes');

/*!40000 ALTER TABLE `wptests_options` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table wptests_postmeta
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wptests_postmeta`;

CREATE TABLE `wptests_postmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`meta_id`),
  KEY `post_id` (`post_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table wptests_posts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wptests_posts`;

CREATE TABLE `wptests_posts` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_author` bigint(20) unsigned NOT NULL DEFAULT '0',
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_excerpt` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'publish',
  `comment_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `ping_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `post_password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `post_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `to_ping` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `pinged` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content_filtered` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `guid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `menu_order` int(11) NOT NULL DEFAULT '0',
  `post_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'post',
  `post_mime_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `post_name` (`post_name`(191)),
  KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  KEY `post_parent` (`post_parent`),
  KEY `post_author` (`post_author`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table wptests_term_relationships
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wptests_term_relationships`;

CREATE TABLE `wptests_term_relationships` (
  `object_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_taxonomy_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`object_id`,`term_taxonomy_id`),
  KEY `term_taxonomy_id` (`term_taxonomy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table wptests_term_taxonomy
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wptests_term_taxonomy`;

CREATE TABLE `wptests_term_taxonomy` (
  `term_taxonomy_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `taxonomy` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_taxonomy_id`),
  UNIQUE KEY `term_id_taxonomy` (`term_id`,`taxonomy`),
  KEY `taxonomy` (`taxonomy`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `wptests_term_taxonomy` WRITE;
/*!40000 ALTER TABLE `wptests_term_taxonomy` DISABLE KEYS */;

INSERT INTO `wptests_term_taxonomy` (`term_taxonomy_id`, `term_id`, `taxonomy`, `description`, `parent`, `count`)
VALUES
	(1,1,'category','',0,0);

/*!40000 ALTER TABLE `wptests_term_taxonomy` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table wptests_termmeta
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wptests_termmeta`;

CREATE TABLE `wptests_termmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`meta_id`),
  KEY `term_id` (`term_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table wptests_terms
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wptests_terms`;

CREATE TABLE `wptests_terms` (
  `term_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `slug` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `term_group` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_id`),
  KEY `slug` (`slug`(191)),
  KEY `name` (`name`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `wptests_terms` WRITE;
/*!40000 ALTER TABLE `wptests_terms` DISABLE KEYS */;

INSERT INTO `wptests_terms` (`term_id`, `name`, `slug`, `term_group`)
VALUES
	(1,'Uncategorized','uncategorized',0);

/*!40000 ALTER TABLE `wptests_terms` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table wptests_usermeta
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wptests_usermeta`;

CREATE TABLE `wptests_usermeta` (
  `umeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`umeta_id`),
  KEY `user_id` (`user_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `wptests_usermeta` WRITE;
/*!40000 ALTER TABLE `wptests_usermeta` DISABLE KEYS */;

INSERT INTO `wptests_usermeta` (`umeta_id`, `user_id`, `meta_key`, `meta_value`)
VALUES
	(1,1,'nickname','admin'),
	(2,1,'first_name',''),
	(3,1,'last_name',''),
	(4,1,'description',''),
	(5,1,'rich_editing','true'),
	(6,1,'syntax_highlighting','true'),
	(7,1,'comment_shortcuts','false'),
	(8,1,'admin_color','fresh'),
	(9,1,'use_ssl','0'),
	(10,1,'show_admin_bar_front','true'),
	(11,1,'locale',''),
	(12,1,'wptests_capabilities','a:1:{s:13:\"administrator\";b:1;}'),
	(13,1,'wptests_user_level','10'),
	(14,1,'dismissed_wp_pointers',''),
	(15,1,'show_welcome_panel','1');

/*!40000 ALTER TABLE `wptests_usermeta` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table wptests_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wptests_users`;

CREATE TABLE `wptests_users` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_pass` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_nicename` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_url` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_activation_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_status` int(11) NOT NULL DEFAULT '0',
  `display_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`),
  KEY `user_login_key` (`user_login`),
  KEY `user_nicename` (`user_nicename`),
  KEY `user_email` (`user_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `wptests_users` WRITE;
/*!40000 ALTER TABLE `wptests_users` DISABLE KEYS */;

INSERT INTO `wptests_users` (`ID`, `user_login`, `user_pass`, `user_nicename`, `user_email`, `user_url`, `user_registered`, `user_activation_key`, `user_status`, `display_name`)
VALUES
	(1,'admin','$P$B3l.1P/I/2FqaqhyqADrLl.EvlYGK71','admin','admin@example.org','','2018-03-25 04:16:59','',0,'admin');

/*!40000 ALTER TABLE `wptests_users` ENABLE KEYS */;
UNLOCK TABLES;






# Dump of table wptests_wps_collections_custom
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wptests_wps_collections_custom`;

CREATE TABLE `wptests_wps_collections_custom` (
  `collection_id` bigint(100) unsigned NOT NULL DEFAULT '0',
  `post_id` bigint(100) unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `handle` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `body_html` longtext COLLATE utf8mb4_unicode_520_ci,
  `image` longtext COLLATE utf8mb4_unicode_520_ci,
  `metafield` longtext COLLATE utf8mb4_unicode_520_ci,
  `published` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `published_scope` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `sort_order` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `published_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`collection_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


LOCK TABLES `wptests_wps_collections_custom` WRITE;
/*!40000 ALTER TABLE `wptests_wps_collections_custom` DISABLE KEYS */;

INSERT INTO `wptests_wps_collections_custom` (`collection_id`, `post_id`, `title`, `handle`, `body_html`, `image`, `metafield`, `published`, `published_scope`, `sort_order`, `published_at`, `updated_at`)
VALUES
	(5570953239, 989227, 'Home page', 'frontpage', 'Vivamus magna justo, lacinia eget consectetur sed, convallis at tellus. Sed porttitor lectus nibh. Nulla quis lorem ut libero malesuada feugiat. Proin eget tortor risus. Donec rutrum congue leo eget malesuada. Vivamus suscipit tortor eget felis porttitor volutpat.<br><br>Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui. Vivamus magna justo, lacinia eget consectetur sed, convallis at tellus. Donec sollicitudin molestie malesuada. Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a.', 'https://cdn.shopify.com/s/files/1/2400/7681/collections/hail-damage.jpg?v=1507320202', '', '', 'global', 'best-selling', '2017-09-27 18:08:06', '2018-06-23 03:13:24'),
	(9176350743, 989222, 'All', 'all', 'sdfdf sdfsdfsdfsd fsdfsdfsd', '', '', '', 'global', 'best-selling', '2017-10-16 19:56:32', '2018-04-26 01:56:39'),
	(9197813783, 989231, 'sdfsdfsdf', 'sdfsdfsdf', 'sdfsdfsdfsdfd', '', '', '', 'global', 'best-selling', '2017-10-16 22:13:37', '2018-04-26 01:56:39'),
	(9198862359, 989226, 'ddsdfsdfsdf', 'sdfsdfsdf-1', 'ssssss', '', '', '', 'global', 'best-selling', '2017-10-16 22:18:12', '2018-04-26 01:56:39'),
	(9199222807, 989229, 'newest', 'newest', 'newest', '', '', '', 'global', 'best-selling', '2017-10-16 22:19:57', '2018-06-01 19:21:59'),
	(9211969559, 989225, 'CUSTOMzzzz', 'custom', '<meta charset=\"utf-8\"><span>Mauris </span>', '', '', '', 'global', 'best-selling', '2017-10-16 23:48:58', '2018-04-27 01:58:11'),
	(9212264471, 989228, 'NEW CUSTOM', 'new-custom', 'NEW CUSTOM', '', '', '', 'global', 'best-selling', '2017-10-16 23:49:46', '2018-04-26 01:56:40'),
	(9212657687, 989223, 'BRAND NEW CUSTOM', 'brand-new-custom', 'BRAND NEW CUSTOM', '', '', '', 'global', 'best-selling', '2017-10-16 23:51:53', '2018-06-01 19:18:46'),
	(9561964567, 989224, 'CUSTOM 10', 'custom-10', 'CUSTOM 10', '', '', '', 'global', 'best-selling', '2017-10-17 19:34:04', '2018-04-26 01:56:40'),
	(13729595415, 989232, 'sdsdfsdf', 'sdsdfsdf', 'asdfasdfsdaf', '', '', '', 'global', 'best-selling', '2017-12-10 23:13:34', '2018-04-26 01:56:41'),
	(13729660951, 989230, 'sdfsdf', 'sdfsdf-2', 'sdfsdf', '', '', '', 'global', 'best-selling', '2017-12-10 23:14:35', '2018-04-26 01:56:41'),
	(13729693719, 989221, 'aaaaa', 'aaaaa-1', 'aaaaa', '', '', '', 'global', 'best-selling', '2017-12-10 23:15:50', '2018-04-26 01:56:41'),
	(13729792023, 989233, 'zzzzzMMMMMMzzzzzzz', 'mmmmmm', 'MMMMMM', '', '', '', 'global', 'best-selling', '2017-12-10 23:19:59', '2018-04-26 01:56:41');


/*!40000 ALTER TABLE `wptests_wps_collections_custom` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table wptests_wps_collections_smart
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wptests_wps_collections_smart`;

CREATE TABLE `wptests_wps_collections_smart` (
  `collection_id` bigint(100) unsigned NOT NULL DEFAULT '0',
  `post_id` bigint(100) unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `handle` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `body_html` longtext COLLATE utf8mb4_unicode_520_ci,
  `image` longtext COLLATE utf8mb4_unicode_520_ci,
  `rules` longtext COLLATE utf8mb4_unicode_520_ci,
  `disjunctive` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `sort_order` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `published_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`collection_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


LOCK TABLES `wptests_wps_collections_smart` WRITE;
/*!40000 ALTER TABLE `wptests_wps_collections_smart` DISABLE KEYS */;

INSERT INTO `wptests_wps_collections_smart` (`collection_id`, `post_id`, `title`, `handle`, `body_html`, `image`, `rules`, `disjunctive`, `sort_order`, `published_at`, `updated_at`)
VALUES
	(9195356183, 989260, 'Testingsssssdfsdf', 'testing', 'sdfsdfsdfsdf', '', 'a:1:{i:0;O:8:\"stdClass\":3:{s:6:\"column\";s:4:\"type\";s:8:\"relation\";s:6:\"equals\";s:9:\"condition\";s:5:\"Books\";}}', '', 'best-selling', '2017-10-16 21:52:33', '2018-07-04 01:06:05'),
	(9200500759, 989258, 'SMARTsdsdf', 'smart', 'ssss', '', 'a:1:{i:0;O:8:\"stdClass\":3:{s:6:\"column\";s:6:\"vendor\";s:8:\"relation\";s:6:\"equals\";s:9:\"condition\";s:14:\"Brakus-Corkery\";}}', '', 'best-selling', '2017-10-16 22:28:54', '2018-04-26 01:56:40'),
	(9208954903, 989255, 'SMART 2', 'smart-2', 'SMART 2', '', 'a:1:{i:0;O:8:\"stdClass\":3:{s:6:\"column\";s:3:\"tag\";s:8:\"relation\";s:6:\"equals\";s:9:\"condition\";s:9:\"accusamus\";}}', '', 'best-selling', '2017-10-16 23:27:22', '2018-07-04 01:04:20'),
	(9209577495, 989256, 'SMART 3', 'smart-3', 'SMART 3', '', 'a:1:{i:0;O:8:\"stdClass\":3:{s:6:\"column\";s:6:\"vendor\";s:8:\"relation\";s:6:\"equals\";s:9:\"condition\";s:16:\"Abernathy-Kirlin\";}}', '', 'best-selling', '2017-10-16 23:31:04', '2018-04-26 01:56:40'),
	(9210134551, 989253, 'Really Smart 100', 'really-smart-100', 'Really Smart 100', '', 'a:1:{i:0;O:8:\"stdClass\":3:{s:6:\"column\";s:4:\"type\";s:8:\"relation\";s:6:\"equals\";s:9:\"condition\";s:4:\"Baby\";}}', '', 'best-selling', '2017-10-16 23:35:20', '2018-07-04 01:04:22'),
	(9210265623, 989257, 'Smart Collection 220', 'smart-collection-220', 'Smart Collection 220', '', 'a:1:{i:0;O:8:\"stdClass\":3:{s:6:\"column\";s:3:\"tag\";s:8:\"relation\";s:6:\"equals\";s:9:\"condition\";s:7:\"aliquam\";}}', '', 'best-selling', '2017-10-16 23:36:29', '2018-07-04 01:04:20'),
	(9210494999, 989242, 'HELLO', 'hello', 'HELLO', '', 'a:1:{i:0;O:8:\"stdClass\":3:{s:6:\"column\";s:3:\"tag\";s:8:\"relation\";s:6:\"equals\";s:9:\"condition\";s:9:\"accusamus\";}}', '', 'best-selling', '2017-10-16 23:38:02', '2018-07-04 01:04:20'),
	(9210822679, 989244, 'IIIII', 'iiiii', 'IIIII', '', 'a:1:{i:0;O:8:\"stdClass\":3:{s:6:\"column\";s:6:\"vendor\";s:8:\"relation\";s:6:\"equals\";s:9:\"condition\";s:11:\"Bode-O\'Hara\";}}', '', 'best-selling', '2017-10-16 23:39:55', '2018-04-26 01:56:40'),
	(9210986519, 989262, 'YO', 'yo', 'YO', '', 'a:1:{i:0;O:8:\"stdClass\":3:{s:6:\"column\";s:3:\"tag\";s:8:\"relation\";s:6:\"equals\";s:9:\"condition\";s:8:\"corrupti\";}}', '', 'best-selling', '2017-10-16 23:41:29', '2018-07-04 01:04:20'),
	(10801610775, 989235, '11collection', '11collection', '11collection', '', 'a:1:{i:0;O:8:\"stdClass\":3:{s:6:\"column\";s:6:\"vendor\";s:8:\"relation\";s:6:\"equals\";s:9:\"condition\";s:10:\"Bogan-Wolf\";}}', '', 'best-selling', '2017-10-21 19:50:06', '2018-04-26 01:56:40'),
	(10801971223, 989261, 'THIS IS A NEW COLLECTION', 'this-is-a-new-collection', 'THIS IS A NEW COLLECTION', '', 'a:1:{i:0;O:8:\"stdClass\":3:{s:6:\"column\";s:6:\"vendor\";s:8:\"relation\";s:6:\"equals\";s:9:\"condition\";s:8:\"Orn-Torp\";}}', '', 'best-selling', '2017-10-21 19:52:19', '2018-04-26 01:56:40'),
	(10802593815, 989243, 'HHHHHHee', 'hhhhhh', 'HHHHHH', '', 'a:1:{i:0;O:8:\"stdClass\":3:{s:6:\"column\";s:4:\"type\";s:8:\"relation\";s:6:\"equals\";s:9:\"condition\";s:9:\"Computers\";}}', '', 'best-selling', '2017-10-21 20:01:35', '2018-07-04 01:06:05'),
	(10803904535, 989247, 'KKdfsdfsdfsdf', 'kkdfsdfsdfsdf', 'KKdfsdfsdfsdf', '', 'a:1:{i:0;O:8:\"stdClass\":3:{s:6:\"column\";s:6:\"vendor\";s:8:\"relation\";s:6:\"equals\";s:9:\"condition\";s:12:\"Jacobson Inc\";}}', '', 'best-selling', '2017-10-21 20:08:07', '2018-06-20 04:08:39'),
	(10804461591, 989249, 'LLLLLLLLLLLLLLLaaa', 'lllllllllllllll', 'LLL', '', 'a:1:{i:0;O:8:\"stdClass\":3:{s:6:\"column\";s:6:\"vendor\";s:8:\"relation\";s:6:\"equals\";s:9:\"condition\";s:11:\"Bode-O\'Hara\";}}', '', 'best-selling', '2017-10-21 20:11:24', '2018-04-26 01:56:40'),
	(10804887575, 989246, 'KDFKSFSDKFSD', 'kdfksfsdkfsd', 'KDFKSFSDKFSD', '', 'a:1:{i:0;O:8:\"stdClass\":3:{s:6:\"column\";s:6:\"vendor\";s:8:\"relation\";s:6:\"equals\";s:9:\"condition\";s:16:\"Gislason-Gaylord\";}}', '', 'best-selling', '2017-10-21 20:12:50', '2018-04-26 01:56:40'),
	(10806394903, 989240, 'ASASASASASSAA', 'asasasasassaa', 'ASASASASASSAA', '', 'a:1:{i:0;O:8:\"stdClass\":3:{s:6:\"column\";s:6:\"vendor\";s:8:\"relation\";s:6:\"equals\";s:9:\"condition\";s:13:\"Beatty-Bednar\";}}', '', 'best-selling', '2017-10-21 20:23:04', '2018-04-26 01:56:40'),
	(10808262679, 989254, 'sdflkjsdflskjdflkdjs', 'sdflkjsdflskjdflkdjs', 'sdflkjsdflskjdflkdjs', '', 'a:1:{i:0;O:8:\"stdClass\":3:{s:6:\"column\";s:4:\"type\";s:8:\"relation\";s:6:\"equals\";s:9:\"condition\";s:5:\"Books\";}}', '', 'best-selling', '2017-10-21 20:34:04', '2018-07-04 01:06:05'),
	(10809245719, 989236, '2222jkdjskfjdkfjsf', 'jkdjskfjdkfjsf', '<strong>111</strong>', '', 'a:1:{i:0;O:8:\"stdClass\":3:{s:6:\"column\";s:6:\"vendor\";s:8:\"relation\";s:6:\"equals\";s:9:\"condition\";s:11:\"Bode-O\'Hara\";}}', '', 'best-selling', '2017-10-21 20:41:21', '2018-04-26 01:56:41'),
	(13303054359, 989241, 'Brand new', 'brand-new', 'Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a. Curabitur arcu erat, accumsan id imperdiet et, porttitor tas non nisi. Pellentesque in ipsum id orci porta dapibus. Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui. Sed porttitor lectus nibh. Vivamus magna justo, lacinia eget consectetur sed, convallis at tellus.<br><br>Pellentesque in ipsum id orci porta dapibus. Nulla porttitor accumsan tincidunt. Vivamus magna justo, lacinia eget consectetur sed, convallis at tellus. Pellentesque in ipsum id orci porta dapibus. Sed porttitor lectus nibh. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec velit neque, auctor sit amet aliquam vel, ullamcorper sit amet ligula. Pellentesque in ipsum id orci porta dapibus. Quisque velit nisi, pretium ut lacinia in, elementum id enim. Curabitur aliquet quam id dui posuere blandit. Vivamus suscipit tortor eget felis porttitor volutpat.<br><br>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec velit neque, auctor sit amet aliquam vel, ullamcorper sit amet ligula. Donec rutrum congue leo eget malesuada. Curabitur aliquet quam id dui posuere blandit. Donec rutrum congue leo eget malesuada. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur aliquet quam id dui posuere blandit. Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Donec rutrum congue leo eget malesuada. Nulla quis lorem ut libero malesuada feugiat. Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem.<br><br>Donec sollicitudin molestie malesuada. Donec rutrum congue leo eget malesuada. Donec rutrum congue leo eget malesuada. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec velit neque, auctor sit amet aliquam vel, ullamcorper sit amet ligula. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus magna justo, lacinia eget consectetur sed, convallis at tellus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec velit neque, auctor sit amet aliquam vel, ullamcorper sit amet ligula. Proin eget tortor risus. Quisque velit nisi, pretium ut lacinia in, elementum id enim. Vivamus magna justo, lacinia eget consectetur sed, convallis at tellus.', 'https://cdn.shopify.com/s/files/1/2400/7681/collections/soul-yawning_687ae9aa-9bb5-4260-ac9b-0b02b7c7698a.jpg?v=1511721227', 'a:1:{i:0;O:8:\"stdClass\":3:{s:6:\"column\";s:4:\"type\";s:8:\"relation\";s:6:\"equals\";s:9:\"condition\";s:5:\"Books\";}}', '', 'best-selling', '2017-11-25 17:16:30', '2018-07-04 01:06:05'),
	(13303185431, 989250, 'NEW NEW', 'new-new', 'Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a. Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Vivamus suscipit tortor eget felis porttitor volutpat. Donec rutrum congue leo eget malesuada. Cras ultricies ligula sed magna dictum porta. Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Pellentesque in ipsum id orci porta dapibus. Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui. Sed', 'https://cdn.shopify.com/s/files/1/2400/7681/collections/alien.jpg?v=1511630495', 'a:1:{i:0;O:8:\"stdClass\":3:{s:6:\"column\";s:4:\"type\";s:8:\"relation\";s:6:\"equals\";s:9:\"condition\";s:5:\"Games\";}}', '', 'best-selling', '2017-11-25 17:21:01', '2018-07-04 01:06:05'),
	(13303283735, 989259, 'SUP MANnn', 'sup-man', 'Nulla porttitor accumsan tincidunt. Nulla porttitor accumsan tincidunt. Curabitur aliquet quam id dui posuere blandit. Curabitur non nulla sit amet nisl tempus convallis quis ac lectus. Donec sollicitudin molestie malesuada. Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Sed porttitor lectus nibh. Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Sed porttitor lectus nibh. Cras ultricies ligula sed magna dictum porta.<br><br>Quisque velit nisi, pretium ut lacinia in, elementum id enim. Pellentesque in ipsum id orci porta dapibus. Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a. Proin eget tortor risus. Donec rutrum congue leo eget malesuada. Donec rutrum congue leo eget malesuada. Quisque velit nisi, pretium ut lacinia in, elementum id enim. Vivamus suscipit tortor eget felis porttitor volutpat. Curabitur non nulla sit amet nisl tempus convallis quis ac lectus. Praesent sapien massa, convallis a pellentesque nec, egestas non nisi.<br><br>Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Curabitur aliquet quam id dui posuere blandit. Vivamus magna justo, lacinia eget consectetur sed, convallis at tellus. Donec sollicitudin molestie malesuada. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec velit neque, auctor sit amet aliquam vel, ullamcorper sit amet ligula. Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Nulla porttitor accumsan tincidunt. Sed porttitor lectus nibh. Curabitur aliquet quam id dui posuere blandit. Curabitur non nulla sit amet nisl tempus convallis quis ac lectus.<br><br>Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a. Cras ultricies ligula sed magna dictum porta. Vivamus suscipit tortor eget felis porttitor volutpat. Sed porttitor lectus nibh. Nulla quis lorem ut libero malesuada feugiat. Quisque velit nisi, pretium ut lacinia in, elementum id enim. Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui. Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Donec rutrum congue leo eget malesuada. Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem.', 'https://cdn.shopify.com/s/files/1/2400/7681/collections/soul-yawning.jpg?v=1511631023', 'a:1:{i:0;O:8:\"stdClass\":3:{s:6:\"column\";s:6:\"vendor\";s:8:\"relation\";s:6:\"equals\";s:9:\"condition\";s:14:\"Davis and Sons\";}}', '', 'best-selling', '2017-11-25 17:29:40', '2018-06-23 02:56:41'),
	(13728022551, 989263, 'zZZZZZZsdfsdfsdf', 'zzzzzzzsdfsdfsdf', 'sdfasdfsdf', '', 'a:1:{i:0;O:8:\"stdClass\":3:{s:6:\"column\";s:4:\"type\";s:8:\"relation\";s:6:\"equals\";s:9:\"condition\";s:10:\"Automotive\";}}', '', 'best-selling', '2017-12-10 21:21:32', '2018-07-04 01:06:05'),
	(13728120855, 989252, 'OOOOOO', 'oooooo', 'OOOOOO', '', 'a:1:{i:0;O:8:\"stdClass\":3:{s:6:\"column\";s:6:\"vendor\";s:8:\"relation\";s:6:\"equals\";s:9:\"condition\";s:12:\"Abbott Group\";}}', '', 'best-selling', '2017-12-10 21:23:18', '2018-06-20 04:09:37'),
	(13728251927, 989239, 'AAAAA', 'aaaaa', 'AAAAA', '', 'a:1:{i:0;O:8:\"stdClass\":3:{s:6:\"column\";s:6:\"vendor\";s:8:\"relation\";s:6:\"equals\";s:9:\"condition\";s:12:\"Abbott Group\";}}', '', 'best-selling', '2017-12-10 21:24:36', '2018-06-20 04:09:37'),
	(13728284695, 989245, 'iiiiiiii', 'iiiiiiii', 'iiiiiiii', '', 'a:1:{i:0;O:8:\"stdClass\":3:{s:6:\"column\";s:6:\"vendor\";s:8:\"relation\";s:6:\"equals\";s:9:\"condition\";s:28:\"Abbott, DuBuque and Lindgren\";}}', '', 'best-selling', '2017-12-10 21:27:04', '2018-04-26 01:56:41'),
	(13729005591, 989248, 'LLLLLL', 'llllll', 'LLLLLL', '', 'a:1:{i:0;O:8:\"stdClass\":3:{s:6:\"column\";s:6:\"vendor\";s:8:\"relation\";s:6:\"equals\";s:9:\"condition\";s:12:\"Abbott Group\";}}', '', 'best-selling', '2017-12-10 22:25:08', '2018-06-20 04:09:37'),
	(13729038359, 989251, 'nnnnnn', 'nnnnnn', 'nnnnnn', '', 'a:1:{i:0;O:8:\"stdClass\":3:{s:6:\"column\";s:6:\"vendor\";s:8:\"relation\";s:6:\"equals\";s:9:\"condition\";s:28:\"Abbott, DuBuque and Lindgren\";}}', '', 'best-selling', '2017-12-10 22:28:28', '2018-04-26 01:56:41'),
	(13729267735, 989264, '_Collection', '_collection', '_Collection', '', 'a:1:{i:0;O:8:\"stdClass\":3:{s:6:\"column\";s:6:\"vendor\";s:8:\"relation\";s:6:\"equals\";s:9:\"condition\";s:28:\"Abbott, DuBuque and Lindgren\";}}', '', 'best-selling', '2017-12-10 22:44:22', '2018-04-26 01:56:41'),
	(13729300503, 989265, '___sdfsf', '___sdfsf', '___sdfsf', '', 'a:1:{i:0;O:8:\"stdClass\":3:{s:6:\"column\";s:6:\"vendor\";s:8:\"relation\";s:6:\"equals\";s:9:\"condition\";s:27:\"Anderson, Bednar and Rippin\";}}', '', 'best-selling', '2017-12-10 22:46:20', '2018-04-26 01:56:41'),
	(13729333271, 989238, '===sdfsdzzzzz', 'sdfsd', '===sdfsd', '', 'a:1:{i:0;O:8:\"stdClass\":3:{s:6:\"column\";s:13:\"variant_price\";s:8:\"relation\";s:10:\"not_equals\";s:9:\"condition\";s:2:\"11\";}}', '', 'best-selling', '2017-12-10 22:52:20', '2018-07-04 01:06:06'),
	(13729398807, 989237, '::sdfsdf', 'sdfsdf', '::sdfsdf', '', 'a:1:{i:0;O:8:\"stdClass\":3:{s:6:\"column\";s:6:\"vendor\";s:8:\"relation\";s:6:\"equals\";s:9:\"condition\";s:28:\"Abbott, DuBuque and Lindgren\";}}', '', 'best-selling', '2017-12-10 23:01:59', '2018-04-26 01:56:41'),
	(13729431575, 989234, '..SDFSDf', 'sdfsdf-1', '..SDFSDf', '', 'a:1:{i:0;O:8:\"stdClass\":3:{s:6:\"column\";s:4:\"type\";s:8:\"relation\";s:6:\"equals\";s:9:\"condition\";s:6:\"Beauty\";}}', '', 'best-selling', '2017-12-10 23:03:02', '2018-07-04 01:06:06');


/*!40000 ALTER TABLE `wptests_wps_collections_smart` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table wptests_wps_collects
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wptests_wps_collects`;

CREATE TABLE `wptests_wps_collects` (
  `id` bigint(100) unsigned NOT NULL DEFAULT '0',
  `product_id` bigint(100) DEFAULT NULL,
  `collection_id` bigint(100) DEFAULT NULL,
  `featured` tinyint(1) DEFAULT NULL,
  `position` int(20) DEFAULT NULL,
  `sort_value` int(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

LOCK TABLES `wptests_wps_collects` WRITE;
/*!40000 ALTER TABLE `wptests_wps_collects` DISABLE KEYS */;

INSERT INTO `wptests_wps_collects` (`id`, `product_id`, `collection_id`, `featured`, `position`, `sort_value`, `created_at`, `updated_at`)
VALUES
	(9288615198743, 1345543569431, 5570953239, 0, 1, 1, '2018-06-23 03:12:55', '2018-06-23 03:12:55'),
	(9288615657495, 1345543897111, 13729431575, 0, 1, 1, '2018-06-23 03:13:23', '2018-06-23 03:13:23'),
	(9288615690263, 1345543569431, 13729333271, 0, 1, 1, '2018-06-23 03:13:23', '2018-06-23 03:13:23'),
	(9288615723031, 1345543602199, 13729333271, 0, 2, 2, '2018-06-23 03:13:23', '2018-06-23 03:13:23'),
	(9288615755799, 1345543634967, 13729333271, 0, 3, 3, '2018-06-23 03:13:23', '2018-06-23 03:13:23'),
	(9288615788567, 1345543700503, 13729333271, 0, 4, 4, '2018-06-23 03:13:23', '2018-06-23 03:13:23'),
	(9288615821335, 1345543733271, 13729333271, 0, 5, 5, '2018-06-23 03:13:23', '2018-06-23 03:13:23'),
	(9288615854103, 1345543798807, 13729333271, 0, 6, 6, '2018-06-23 03:13:23', '2018-06-23 03:13:23'),
	(9288615886871, 1345543831575, 13729333271, 0, 7, 7, '2018-06-23 03:13:23', '2018-06-23 03:13:23'),
	(9288615919639, 1345543897111, 13729333271, 0, 8, 8, '2018-06-23 03:13:23', '2018-06-23 03:13:23'),
	(9288615952407, 1345543929879, 13729333271, 0, 9, 9, '2018-06-23 03:13:23', '2018-06-23 03:13:23'),
	(9288615985175, 1345543962647, 13729333271, 0, 10, 10, '2018-06-23 03:13:23', '2018-06-23 03:13:23'),
	(9288616017943, 1345544028183, 13729333271, 0, 11, 11, '2018-06-23 03:13:23', '2018-06-23 03:13:23'),
	(9288616050711, 1345544060951, 13729333271, 0, 12, 12, '2018-06-23 03:13:23', '2018-06-23 03:13:23'),
	(9288616083479, 1345544126487, 13729333271, 0, 13, 13, '2018-06-23 03:13:23', '2018-06-23 03:13:23'),
	(9288616116247, 1345544159255, 13729333271, 0, 14, 14, '2018-06-23 03:13:23', '2018-06-23 03:13:23'),
	(9288616149015, 1345544192023, 13729333271, 0, 15, 15, '2018-06-23 03:13:24', '2018-06-23 03:13:24'),
	(9288616181783, 1345543569431, 9210494999, 0, 1, 1, '2018-06-23 03:13:24', '2018-06-23 03:13:24'),
	(9288616214551, 1345544192023, 13303185431, 0, 1, 1, '2018-06-23 03:13:24', '2018-06-23 03:13:24'),
	(9288616247319, 1345543569431, 9208954903, 0, 1, 1, '2018-06-23 03:13:24', '2018-06-23 03:13:24'),
	(9330027495447, 1355610095639, 13729431575, 0, 2, 2, '2018-07-04 01:04:16', '2018-07-04 01:04:16'),
	(9330027528215, 1355610685463, 13729431575, 0, 3, 3, '2018-07-04 01:04:16', '2018-07-04 01:04:16'),
	(9330027560983, 1355611471895, 13729431575, 0, 4, 4, '2018-07-04 01:04:16', '2018-07-04 01:04:16'),
	(9330027593751, 1355611701271, 13729431575, 0, 5, 5, '2018-07-04 01:04:16', '2018-07-04 01:04:16'),
	(9330027626519, 1355611897879, 13729431575, 0, 6, 6, '2018-07-04 01:04:16', '2018-07-04 01:04:16'),
	(9330027659287, 1355609767959, 13729333271, 0, 16, 16, '2018-07-04 01:04:16', '2018-07-04 01:04:16'),
	(9330027692055, 1355609800727, 13729333271, 0, 17, 17, '2018-07-04 01:04:16', '2018-07-04 01:04:16'),
	(9330027724823, 1355609833495, 13729333271, 0, 18, 18, '2018-07-04 01:04:16', '2018-07-04 01:04:16'),
	(9330027757591, 1355609866263, 13729333271, 0, 19, 19, '2018-07-04 01:04:16', '2018-07-04 01:04:16'),
	(9330027790359, 1355609899031, 13729333271, 0, 20, 20, '2018-07-04 01:04:16', '2018-07-04 01:04:16'),
	(9330027823127, 1355609931799, 13729333271, 0, 21, 21, '2018-07-04 01:04:16', '2018-07-04 01:04:16'),
	(9330027855895, 1355609964567, 13729333271, 0, 22, 22, '2018-07-04 01:04:16', '2018-07-04 01:04:16'),
	(9330027888663, 1355609997335, 13729333271, 0, 23, 23, '2018-07-04 01:04:16', '2018-07-04 01:04:16'),
	(9330027921431, 1355610030103, 13729333271, 0, 24, 24, '2018-07-04 01:04:16', '2018-07-04 01:04:16'),
	(9330027954199, 1355610062871, 13729333271, 0, 25, 25, '2018-07-04 01:04:16', '2018-07-04 01:04:16'),
	(9330027986967, 1355610095639, 13729333271, 0, 26, 26, '2018-07-04 01:04:17', '2018-07-04 01:04:17'),
	(9330028019735, 1355610128407, 13729333271, 0, 27, 27, '2018-07-04 01:04:17', '2018-07-04 01:04:17'),
	(9330028052503, 1355610161175, 13729333271, 0, 28, 28, '2018-07-04 01:04:17', '2018-07-04 01:04:17'),
	(9330028085271, 1355610259479, 13729333271, 0, 29, 29, '2018-07-04 01:04:17', '2018-07-04 01:04:17'),
	(9330028118039, 1355610292247, 13729333271, 0, 30, 30, '2018-07-04 01:04:17', '2018-07-04 01:04:17'),
	(9330028150807, 1355610325015, 13729333271, 0, 31, 31, '2018-07-04 01:04:17', '2018-07-04 01:04:17'),
	(9330028183575, 1355610357783, 13729333271, 0, 32, 32, '2018-07-04 01:04:17', '2018-07-04 01:04:17'),
	(9330028216343, 1355610423319, 13729333271, 0, 33, 33, '2018-07-04 01:04:17', '2018-07-04 01:04:17'),
	(9330028249111, 1355610488855, 13729333271, 0, 34, 34, '2018-07-04 01:04:17', '2018-07-04 01:04:17'),
	(9330028281879, 1355610554391, 13729333271, 0, 35, 35, '2018-07-04 01:04:17', '2018-07-04 01:04:17'),
	(9330028314647, 1355610619927, 13729333271, 0, 36, 36, '2018-07-04 01:04:17', '2018-07-04 01:04:17'),
	(9330028347415, 1355610652695, 13729333271, 0, 37, 37, '2018-07-04 01:04:17', '2018-07-04 01:04:17'),
	(9330028380183, 1355610685463, 13729333271, 0, 38, 38, '2018-07-04 01:04:17', '2018-07-04 01:04:17'),
	(9330028412951, 1355610718231, 13729333271, 0, 39, 39, '2018-07-04 01:04:17', '2018-07-04 01:04:17'),
	(9330028445719, 1355610750999, 13729333271, 0, 40, 40, '2018-07-04 01:04:17', '2018-07-04 01:04:17'),
	(9330028478487, 1355610783767, 13729333271, 0, 41, 41, '2018-07-04 01:04:17', '2018-07-04 01:04:17'),
	(9330028511255, 1355610816535, 13729333271, 0, 42, 42, '2018-07-04 01:04:17', '2018-07-04 01:04:17'),
	(9330028544023, 1355610882071, 13729333271, 0, 43, 43, '2018-07-04 01:04:17', '2018-07-04 01:04:17'),
	(9330028576791, 1355610914839, 13729333271, 0, 44, 44, '2018-07-04 01:04:17', '2018-07-04 01:04:17'),
	(9330028609559, 1355610947607, 13729333271, 0, 45, 45, '2018-07-04 01:04:17', '2018-07-04 01:04:17'),
	(9330028642327, 1355610980375, 13729333271, 0, 46, 46, '2018-07-04 01:04:17', '2018-07-04 01:04:17'),
	(9330028675095, 1355611013143, 13729333271, 0, 47, 47, '2018-07-04 01:04:17', '2018-07-04 01:04:17'),
	(9330028707863, 1355611045911, 13729333271, 0, 48, 48, '2018-07-04 01:04:17', '2018-07-04 01:04:17'),
	(9330028740631, 1355611078679, 13729333271, 0, 49, 49, '2018-07-04 01:04:17', '2018-07-04 01:04:17'),
	(9330028773399, 1355611111447, 13729333271, 0, 50, 50, '2018-07-04 01:04:18', '2018-07-04 01:04:18'),
	(9330028806167, 1355611144215, 13729333271, 0, 51, 51, '2018-07-04 01:04:18', '2018-07-04 01:04:18'),
	(9330028838935, 1355611209751, 13729333271, 0, 52, 52, '2018-07-04 01:04:18', '2018-07-04 01:04:18'),
	(9330028871703, 1355611242519, 13729333271, 0, 53, 53, '2018-07-04 01:04:18', '2018-07-04 01:04:18'),
	(9330028904471, 1355611275287, 13729333271, 0, 54, 54, '2018-07-04 01:04:18', '2018-07-04 01:04:18'),
	(9330028937239, 1355611308055, 13729333271, 0, 55, 55, '2018-07-04 01:04:18', '2018-07-04 01:04:18'),
	(9330028970007, 1355611340823, 13729333271, 0, 56, 56, '2018-07-04 01:04:18', '2018-07-04 01:04:18'),
	(9330029002775, 1355611373591, 13729333271, 0, 57, 57, '2018-07-04 01:04:18', '2018-07-04 01:04:18'),
	(9330029035543, 1355611406359, 13729333271, 0, 58, 58, '2018-07-04 01:04:18', '2018-07-04 01:04:18'),
	(9330029068311, 1355611439127, 13729333271, 0, 59, 59, '2018-07-04 01:04:18', '2018-07-04 01:04:18'),
	(9330029101079, 1355611471895, 13729333271, 0, 60, 60, '2018-07-04 01:04:18', '2018-07-04 01:04:18'),
	(9330029133847, 1355611504663, 13729333271, 0, 61, 61, '2018-07-04 01:04:18', '2018-07-04 01:04:18'),
	(9330029166615, 1355611537431, 13729333271, 0, 62, 62, '2018-07-04 01:04:18', '2018-07-04 01:04:18'),
	(9330029199383, 1355611570199, 13729333271, 0, 63, 63, '2018-07-04 01:04:18', '2018-07-04 01:04:18'),
	(9330029232151, 1355611635735, 13729333271, 0, 64, 64, '2018-07-04 01:04:18', '2018-07-04 01:04:18'),
	(9330029264919, 1355611668503, 13729333271, 0, 65, 65, '2018-07-04 01:04:18', '2018-07-04 01:04:18'),
	(9330029297687, 1355611701271, 13729333271, 0, 66, 66, '2018-07-04 01:04:18', '2018-07-04 01:04:18'),
	(9330029330455, 1355611734039, 13729333271, 0, 67, 67, '2018-07-04 01:04:18', '2018-07-04 01:04:18'),
	(9330029363223, 1355611897879, 13729333271, 0, 68, 68, '2018-07-04 01:04:18', '2018-07-04 01:04:18'),
	(9330029395991, 1355611930647, 13729333271, 0, 69, 69, '2018-07-04 01:04:18', '2018-07-04 01:04:18'),
	(9330029428759, 1355611963415, 13729333271, 0, 70, 70, '2018-07-04 01:04:18', '2018-07-04 01:04:18'),
	(9330029461527, 1355611996183, 13729333271, 0, 71, 71, '2018-07-04 01:04:18', '2018-07-04 01:04:18'),
	(9330029494295, 1355612061719, 13729333271, 0, 72, 72, '2018-07-04 01:04:18', '2018-07-04 01:04:18'),
	(9330029527063, 1355610423319, 9210494999, 0, 2, 2, '2018-07-04 01:04:18', '2018-07-04 01:04:18'),
	(9330029559831, 1355610292247, 10802593815, 0, 1, 1, '2018-07-04 01:04:19', '2018-07-04 01:04:19'),
	(9330029592599, 1355611045911, 10802593815, 0, 2, 2, '2018-07-04 01:04:19', '2018-07-04 01:04:19'),
	(9330029625367, 1355609866263, 13303185431, 0, 2, 2, '2018-07-04 01:04:19', '2018-07-04 01:04:19'),
	(9330029658135, 1355610652695, 13303185431, 0, 3, 3, '2018-07-04 01:04:19', '2018-07-04 01:04:19'),
	(9330029690903, 1355611209751, 13303185431, 0, 4, 4, '2018-07-04 01:04:19', '2018-07-04 01:04:19'),
	(9330029723671, 1355611242519, 13303185431, 0, 5, 5, '2018-07-04 01:04:19', '2018-07-04 01:04:19'),
	(9330029756439, 1355611963415, 13303185431, 0, 6, 6, '2018-07-04 01:04:19', '2018-07-04 01:04:19'),
	(9330029789207, 1355610488855, 9210134551, 0, 1, 1, '2018-07-04 01:04:19', '2018-07-04 01:04:19'),
	(9330029821975, 1355610718231, 9210134551, 0, 2, 2, '2018-07-04 01:04:19', '2018-07-04 01:04:19'),
	(9330029854743, 1355610882071, 9210134551, 0, 3, 3, '2018-07-04 01:04:19', '2018-07-04 01:04:19'),
	(9330029887511, 1355611996183, 9210134551, 0, 4, 4, '2018-07-04 01:04:19', '2018-07-04 01:04:19'),
	(9330029920279, 1355610423319, 9208954903, 0, 2, 2, '2018-07-04 01:04:19', '2018-07-04 01:04:19'),
	(9330029953047, 1355609899031, 9210265623, 0, 1, 1, '2018-07-04 01:04:19', '2018-07-04 01:04:19'),
	(9330029985815, 1355609931799, 9210265623, 0, 2, 2, '2018-07-04 01:04:19', '2018-07-04 01:04:19'),
	(9330030018583, 1355609866263, 9210986519, 0, 1, 1, '2018-07-04 01:04:20', '2018-07-04 01:04:20'),
	(9330030051351, 1355611144215, 13728022551, 0, 1, 1, '2018-07-04 01:04:20', '2018-07-04 01:04:20'),
	(9330030084119, 1355611930647, 13728022551, 0, 2, 2, '2018-07-04 01:04:20', '2018-07-04 01:04:20'),
	(9330030215191, 1355613536279, 13729431575, 0, 7, 7, '2018-07-04 01:06:01', '2018-07-04 01:06:01'),
	(9330030247959, 1355613569047, 13729431575, 0, 8, 8, '2018-07-04 01:06:01', '2018-07-04 01:06:01'),
	(9330030280727, 1355613667351, 13729431575, 0, 9, 9, '2018-07-04 01:06:01', '2018-07-04 01:06:01'),
	(9330030313495, 1355612094487, 13729333271, 0, 73, 73, '2018-07-04 01:06:01', '2018-07-04 01:06:01'),
	(9330030346263, 1355612160023, 13729333271, 0, 74, 74, '2018-07-04 01:06:01', '2018-07-04 01:06:01'),
	(9330030379031, 1355612192791, 13729333271, 0, 75, 75, '2018-07-04 01:06:01', '2018-07-04 01:06:01'),
	(9330030411799, 1355612225559, 13729333271, 0, 76, 76, '2018-07-04 01:06:01', '2018-07-04 01:06:01'),
	(9330030444567, 1355612258327, 13729333271, 0, 77, 77, '2018-07-04 01:06:02', '2018-07-04 01:06:02'),
	(9330030477335, 1355612291095, 13729333271, 0, 78, 78, '2018-07-04 01:06:02', '2018-07-04 01:06:02'),
	(9330030510103, 1355612323863, 13729333271, 0, 79, 79, '2018-07-04 01:06:02', '2018-07-04 01:06:02'),
	(9330030542871, 1355612356631, 13729333271, 0, 80, 80, '2018-07-04 01:06:02', '2018-07-04 01:06:02'),
	(9330030575639, 1355612422167, 13729333271, 0, 81, 81, '2018-07-04 01:06:02', '2018-07-04 01:06:02'),
	(9330030608407, 1355612454935, 13729333271, 0, 82, 82, '2018-07-04 01:06:02', '2018-07-04 01:06:02'),
	(9330030641175, 1355612487703, 13729333271, 0, 83, 83, '2018-07-04 01:06:02', '2018-07-04 01:06:02'),
	(9330030673943, 1355612553239, 13729333271, 0, 84, 84, '2018-07-04 01:06:02', '2018-07-04 01:06:02'),
	(9330030706711, 1355612586007, 13729333271, 0, 85, 85, '2018-07-04 01:06:02', '2018-07-04 01:06:02'),
	(9330030739479, 1355612618775, 13729333271, 0, 86, 86, '2018-07-04 01:06:02', '2018-07-04 01:06:02'),
	(9330030772247, 1355612651543, 13729333271, 0, 87, 87, '2018-07-04 01:06:02', '2018-07-04 01:06:02'),
	(9330030805015, 1355612684311, 13729333271, 0, 88, 88, '2018-07-04 01:06:02', '2018-07-04 01:06:02'),
	(9330030837783, 1355612717079, 13729333271, 0, 89, 89, '2018-07-04 01:06:02', '2018-07-04 01:06:02'),
	(9330030870551, 1355612749847, 13729333271, 0, 90, 90, '2018-07-04 01:06:02', '2018-07-04 01:06:02'),
	(9330030903319, 1355612782615, 13729333271, 0, 91, 91, '2018-07-04 01:06:02', '2018-07-04 01:06:02'),
	(9330030936087, 1355612815383, 13729333271, 0, 92, 92, '2018-07-04 01:06:02', '2018-07-04 01:06:02'),
	(9330030968855, 1355612848151, 13729333271, 0, 93, 93, '2018-07-04 01:06:02', '2018-07-04 01:06:02'),
	(9330031001623, 1355612880919, 13729333271, 0, 94, 94, '2018-07-04 01:06:02', '2018-07-04 01:06:02'),
	(9330031034391, 1355612913687, 13729333271, 0, 95, 95, '2018-07-04 01:06:02', '2018-07-04 01:06:02'),
	(9330031067159, 1355612946455, 13729333271, 0, 96, 96, '2018-07-04 01:06:02', '2018-07-04 01:06:02'),
	(9330031099927, 1355612979223, 13729333271, 0, 97, 97, '2018-07-04 01:06:02', '2018-07-04 01:06:02'),
	(9330031132695, 1355613011991, 13729333271, 0, 98, 98, '2018-07-04 01:06:02', '2018-07-04 01:06:02'),
	(9330031165463, 1355613044759, 13729333271, 0, 99, 99, '2018-07-04 01:06:02', '2018-07-04 01:06:02'),
	(9330031198231, 1355613077527, 13729333271, 0, 100, 100, '2018-07-04 01:06:02', '2018-07-04 01:06:02'),
	(9330031230999, 1355613110295, 13729333271, 0, 101, 101, '2018-07-04 01:06:03', '2018-07-04 01:06:03'),
	(9330031263767, 1355613175831, 13729333271, 0, 102, 102, '2018-07-04 01:06:03', '2018-07-04 01:06:03'),
	(9330031296535, 1355613274135, 13729333271, 0, 103, 103, '2018-07-04 01:06:03', '2018-07-04 01:06:03'),
	(9330031329303, 1355613306903, 13729333271, 0, 104, 104, '2018-07-04 01:06:03', '2018-07-04 01:06:03'),
	(9330031362071, 1355613339671, 13729333271, 0, 105, 105, '2018-07-04 01:06:03', '2018-07-04 01:06:03'),
	(9330031394839, 1355613372439, 13729333271, 0, 106, 106, '2018-07-04 01:06:03', '2018-07-04 01:06:03'),
	(9330031427607, 1355613437975, 13729333271, 0, 107, 107, '2018-07-04 01:06:03', '2018-07-04 01:06:03'),
	(9330031460375, 1355613470743, 13729333271, 0, 108, 108, '2018-07-04 01:06:03', '2018-07-04 01:06:03'),
	(9330031493143, 1355613503511, 13729333271, 0, 109, 109, '2018-07-04 01:06:03', '2018-07-04 01:06:03'),
	(9330031525911, 1355613536279, 13729333271, 0, 110, 110, '2018-07-04 01:06:03', '2018-07-04 01:06:03'),
	(9330031558679, 1355613569047, 13729333271, 0, 111, 111, '2018-07-04 01:06:03', '2018-07-04 01:06:03'),
	(9330031591447, 1355613601815, 13729333271, 0, 112, 112, '2018-07-04 01:06:03', '2018-07-04 01:06:03'),
	(9330031624215, 1355613634583, 13729333271, 0, 113, 113, '2018-07-04 01:06:03', '2018-07-04 01:06:03'),
	(9330031656983, 1355613667351, 13729333271, 0, 114, 114, '2018-07-04 01:06:03', '2018-07-04 01:06:03'),
	(9330031689751, 1355613700119, 13729333271, 0, 115, 115, '2018-07-04 01:06:03', '2018-07-04 01:06:03'),
	(9330031722519, 1355612979223, 13303054359, 0, 1, 1, '2018-07-04 01:06:03', '2018-07-04 01:06:03'),
	(9330031755287, 1355613011991, 13303054359, 0, 2, 2, '2018-07-04 01:06:03', '2018-07-04 01:06:03'),
	(9330031788055, 1355612258327, 10802593815, 0, 3, 3, '2018-07-04 01:06:03', '2018-07-04 01:06:03'),
	(9330031820823, 1355612946455, 10802593815, 0, 4, 4, '2018-07-04 01:06:03', '2018-07-04 01:06:03'),
	(9330031853591, 1355612815383, 13303185431, 0, 7, 7, '2018-07-04 01:06:04', '2018-07-04 01:06:04'),
	(9330031886359, 1355612979223, 10808262679, 0, 1, 1, '2018-07-04 01:06:04', '2018-07-04 01:06:04'),
	(9330031919127, 1355613011991, 10808262679, 0, 2, 2, '2018-07-04 01:06:04', '2018-07-04 01:06:04'),
	(9330031951895, 1355612979223, 9195356183, 0, 1, 1, '2018-07-04 01:06:04', '2018-07-04 01:06:04'),
	(9330031984663, 1355613011991, 9195356183, 0, 2, 2, '2018-07-04 01:06:04', '2018-07-04 01:06:04'),
	(9330032017431, 1355613339671, 13728022551, 0, 3, 3, '2018-07-04 01:06:04', '2018-07-04 01:06:04'),
	(9330032050199, 1355613437975, 13728022551, 0, 4, 4, '2018-07-04 01:06:04', '2018-07-04 01:06:04');


/*!40000 ALTER TABLE `wptests_wps_collects` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table wptests_wps_customers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wptests_wps_customers`;

CREATE TABLE `wptests_wps_customers` (
  `id` bigint(100) unsigned NOT NULL DEFAULT '0',
  `email` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `accepts_marketing` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `orders_count` tinyint(1) DEFAULT '0',
  `state` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `total_spent` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `last_order_id` bigint(100) unsigned DEFAULT NULL,
  `note` longtext COLLATE utf8mb4_unicode_520_ci,
  `verified_email` tinyint(1) DEFAULT '0',
  `multipass_identifier` longtext COLLATE utf8mb4_unicode_520_ci,
  `tax_exempt` tinyint(1) DEFAULT '0',
  `phone` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `tags` longtext COLLATE utf8mb4_unicode_520_ci,
  `last_order_name` longtext COLLATE utf8mb4_unicode_520_ci,
  `default_address` longtext COLLATE utf8mb4_unicode_520_ci,
  `addresses` longtext COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

LOCK TABLES `wptests_wps_customers` WRITE;
/*!40000 ALTER TABLE `wptests_wps_customers` DISABLE KEYS */;

INSERT INTO `wptests_wps_customers` (`id`, `email`, `accepts_marketing`, `created_at`, `updated_at`, `first_name`, `last_name`, `orders_count`, `state`, `total_spent`, `last_order_id`, `note`, `verified_email`, `multipass_identifier`, `tax_exempt`, `phone`, `tags`, `last_order_name`, `default_address`, `addresses`)
VALUES
	(127274778647, 'zzzzzzzarobbins@simpleblend.net', 1, '2017-09-29 18:37:59', '2018-01-06 20:45:04', 'Andrewrrr', 'Robbinszzzzzz', 9, 'invited', '0.00', 177266130967, '1111zzzzddddaaaaaaGGGnnasdsdfsd', 1, NULL, 1, '+16128128561', 'ddd', '#1039', 'a:17:{s:2:\"id\";i:242660147223;s:11:\"customer_id\";i:127274778647;s:10:\"first_name\";s:6:\"sdfsdf\";s:9:\"last_name\";s:6:\"sdfsdf\";s:7:\"company\";N;s:8:\"address1\";s:8:\"12 sdfsd\";s:8:\"address2\";s:2:\"12\";s:4:\"city\";s:11:\"minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";N;s:4:\"name\";s:13:\"sdfsdf sdfsdf\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}', 'a:6:{i:0;a:17:{s:2:\"id\";i:109861994519;s:11:\"customer_id\";i:127274778647;s:10:\"first_name\";s:6:\"Andrew\";s:9:\"last_name\";s:7:\"Robbins\";s:7:\"company\";N;s:8:\"address1\";s:12:\"614 N 1st St\";s:8:\"address2\";s:3:\"606\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";N;s:4:\"name\";s:14:\"Andrew Robbins\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:0;}i:1;a:17:{s:2:\"id\";i:109892632599;s:11:\"customer_id\";i:127274778647;s:10:\"first_name\";s:6:\"Andrew\";s:9:\"last_name\";s:7:\"Robbins\";s:7:\"company\";N;s:8:\"address1\";s:12:\"615 N 1st St\";s:8:\"address2\";s:3:\"606\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";N;s:4:\"name\";s:14:\"Andrew Robbins\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:0;}i:2;a:17:{s:2:\"id\";i:109908099095;s:11:\"customer_id\";i:127274778647;s:10:\"first_name\";s:6:\"Andrew\";s:9:\"last_name\";s:7:\"Robbins\";s:7:\"company\";N;s:8:\"address1\";s:12:\"614 N 1st St\";s:8:\"address2\";s:3:\"606\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55041\";s:5:\"phone\";N;s:4:\"name\";s:14:\"Andrew Robbins\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:0;}i:3;a:17:{s:2:\"id\";i:110255439895;s:11:\"customer_id\";i:127274778647;s:10:\"first_name\";s:6:\"Andrew\";s:9:\"last_name\";s:7:\"Robbins\";s:7:\"company\";N;s:8:\"address1\";s:10:\"123 sdfjsd\";s:8:\"address2\";s:3:\"101\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55041\";s:5:\"phone\";N;s:4:\"name\";s:14:\"Andrew Robbins\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:0;}i:4;a:17:{s:2:\"id\";i:110518140951;s:11:\"customer_id\";i:127274778647;s:10:\"first_name\";s:6:\"Andrew\";s:9:\"last_name\";s:7:\"Robbins\";s:7:\"company\";N;s:8:\"address1\";s:12:\"614 N 1st St\";s:8:\"address2\";s:3:\"101\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";N;s:4:\"name\";s:14:\"Andrew Robbins\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:0;}i:5;a:17:{s:2:\"id\";i:242660147223;s:11:\"customer_id\";i:127274778647;s:10:\"first_name\";s:6:\"sdfsdf\";s:9:\"last_name\";s:6:\"sdfsdf\";s:7:\"company\";N;s:8:\"address1\";s:8:\"12 sdfsd\";s:8:\"address2\";s:2:\"12\";s:4:\"city\";s:11:\"minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";N;s:4:\"name\";s:13:\"sdfsdf sdfsdf\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}}'),
	(186994655255, 'andrew@simpleblend.net', 0, '2017-10-28 01:39:33', '2017-10-31 01:51:03', 'sdjfhskdjh', 'sdjfhskdfh', 6, 'disabled', '0.00', 122149765143, NULL, 1, NULL, 0, NULL, 'ddd', '#1014', 'a:17:{s:2:\"id\";i:161884045335;s:11:\"customer_id\";i:186994655255;s:10:\"first_name\";s:11:\"slkdjfsldkj\";s:9:\"last_name\";s:17:\"lksjflksjdflsdkjf\";s:7:\"company\";N;s:8:\"address1\";s:22:\"123 skjfsdlkfjsd sdlfj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";N;s:4:\"name\";s:29:\"slkdjfsldkj lksjflksjdflsdkjf\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}', 'a:3:{i:0;a:17:{s:2:\"id\";i:161361625111;s:11:\"customer_id\";i:186994655255;s:10:\"first_name\";s:10:\"sdjfhskdjh\";s:9:\"last_name\";s:10:\"sdjfhskdfh\";s:7:\"company\";N;s:8:\"address1\";s:9:\"123 sdfsd\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";N;s:4:\"name\";s:21:\"sdjfhskdjh sdjfhskdfh\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:0;}i:1;a:17:{s:2:\"id\";i:161372536855;s:11:\"customer_id\";i:186994655255;s:10:\"first_name\";s:5:\"jhjkh\";s:9:\"last_name\";s:4:\"jkhj\";s:7:\"company\";N;s:8:\"address1\";s:11:\"khkjhkjhsdf\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";N;s:4:\"name\";s:10:\"jhjkh jkhj\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:0;}i:2;a:17:{s:2:\"id\";i:161884045335;s:11:\"customer_id\";i:186994655255;s:10:\"first_name\";s:11:\"slkdjfsldkj\";s:9:\"last_name\";s:17:\"lksjflksjdflsdkjf\";s:7:\"company\";N;s:8:\"address1\";s:22:\"123 skjfsdlkfjsd sdlfj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";N;s:4:\"name\";s:29:\"slkdjfsldkj lksjflksjdflsdkjf\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}}'),
	(187540406295, 'sfdfsdkfj@sdfsdf.com', 0, '2017-10-28 18:27:14', '2017-11-03 20:28:41', 'sdfsdflkj', 'lksllskdj', 0, 'disabled', '0.00', NULL, NULL, 1, NULL, 0, NULL, '', NULL, 'a:17:{s:2:\"id\";i:161908785175;s:11:\"customer_id\";i:187540406295;s:10:\"first_name\";s:9:\"sdfsdflkj\";s:9:\"last_name\";s:9:\"lksllskdj\";s:7:\"company\";N;s:8:\"address1\";s:13:\"123 sdkfsdkfj\";s:8:\"address2\";s:2:\"10\";s:4:\"city\";s:11:\"minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";N;s:4:\"name\";s:19:\"sdfsdflkj lksllskdj\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}', 'a:1:{i:0;a:17:{s:2:\"id\";i:161908785175;s:11:\"customer_id\";i:187540406295;s:10:\"first_name\";s:9:\"sdfsdflkj\";s:9:\"last_name\";s:9:\"lksllskdj\";s:7:\"company\";N;s:8:\"address1\";s:13:\"123 sdkfsdkfj\";s:8:\"address2\";s:2:\"10\";s:4:\"city\";s:11:\"minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";N;s:4:\"name\";s:19:\"sdfsdflkj lksllskdj\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}}'),
	(187648475159, 'sdfsdjfh@sdfsdf.com', 0, '2017-10-28 20:41:27', '2017-10-28 20:41:44', 'sdfsdkjfh', 'kjsdfkjsh', 1, 'disabled', '0.00', 122266222615, NULL, 1, NULL, 0, NULL, '', '#1017', 'a:17:{s:2:\"id\";i:162011742231;s:11:\"customer_id\";i:187648475159;s:10:\"first_name\";s:9:\"sdfsdkjfh\";s:9:\"last_name\";s:9:\"kjsdfkjsh\";s:7:\"company\";N;s:8:\"address1\";s:12:\"123 ddfhsjhf\";s:8:\"address2\";s:0:\"\";s:4:\"city\";s:11:\"minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";N;s:4:\"name\";s:19:\"sdfsdkjfh kjsdfkjsh\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}', 'a:1:{i:0;a:17:{s:2:\"id\";i:162011742231;s:11:\"customer_id\";i:187648475159;s:10:\"first_name\";s:9:\"sdfsdkjfh\";s:9:\"last_name\";s:9:\"kjsdfkjsh\";s:7:\"company\";N;s:8:\"address1\";s:12:\"123 ddfhsjhf\";s:8:\"address2\";s:0:\"\";s:4:\"city\";s:11:\"minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";N;s:4:\"name\";s:19:\"sdfsdkjfh kjsdfkjsh\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}}'),
	(189139288087, 'sfsdfjhsjdf@sdfdf.com', 0, '2017-10-29 15:13:40', '2017-10-30 17:15:58', 'sdfsjl', 'slkdjf', 1, 'disabled', '0.00', 122664091671, 'sdfsdfsd sdfsdf', 1, NULL, 0, NULL, 'VIP', '#1018', 'a:17:{s:2:\"id\";i:163509698583;s:11:\"customer_id\";i:189139288087;s:10:\"first_name\";s:9:\"sdfsjlddd\";s:9:\"last_name\";s:6:\"slkdjf\";s:7:\"company\";s:0:\"\";s:8:\"address1\";s:10:\"123 sdkfjl\";s:8:\"address2\";s:2:\"80\";s:4:\"city\";s:11:\"minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";s:0:\"\";s:4:\"name\";s:16:\"sdfsjlddd slkdjf\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}', 'a:1:{i:0;a:17:{s:2:\"id\";i:163509698583;s:11:\"customer_id\";i:189139288087;s:10:\"first_name\";s:9:\"sdfsjlddd\";s:9:\"last_name\";s:6:\"slkdjf\";s:7:\"company\";s:0:\"\";s:8:\"address1\";s:10:\"123 sdkfjl\";s:8:\"address2\";s:2:\"80\";s:4:\"city\";s:11:\"minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";s:0:\"\";s:4:\"name\";s:16:\"sdfsjlddd slkdjf\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}}'),
	(240261922839, 'oskodkfo@sds.com', 0, '2017-11-28 20:04:43', '2017-11-29 00:13:05', 'sdflksdj', 'kjsdlkfj', 19, 'invited', '0.00', 161906294807, NULL, 1, NULL, 0, NULL, '', '#1038', 'a:17:{s:2:\"id\";i:221485105175;s:11:\"customer_id\";i:240261922839;s:10:\"first_name\";s:8:\"sdflksdj\";s:9:\"last_name\";s:8:\"kjsdlkfj\";s:7:\"company\";N;s:8:\"address1\";s:13:\"123 sdkfsdkfj\";s:8:\"address2\";s:2:\"12\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";N;s:4:\"name\";s:17:\"sdflksdj kjsdlkfj\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}', 'a:1:{i:0;a:17:{s:2:\"id\";i:221485105175;s:11:\"customer_id\";i:240261922839;s:10:\"first_name\";s:8:\"sdflksdj\";s:9:\"last_name\";s:8:\"kjsdlkfj\";s:7:\"company\";N;s:8:\"address1\";s:13:\"123 sdkfsdkfj\";s:8:\"address2\";s:2:\"12\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";N;s:4:\"name\";s:17:\"sdflksdj kjsdlkfj\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}}'),
	(294370410519, 'zzzzsdfsdfsdfsdf@sdf.com', 0, '2018-01-06 04:32:08', '2018-01-06 20:04:58', 'zzz', 'zzz', 1, 'disabled', '0.00', 197702975511, 'sd11', 1, NULL, 0, NULL, '', '#1040', 'a:17:{s:2:\"id\";i:279895703575;s:11:\"customer_id\";i:294370410519;s:10:\"first_name\";s:3:\"zzz\";s:9:\"last_name\";s:3:\"zzz\";s:7:\"company\";N;s:8:\"address1\";s:9:\"122 sdfsd\";s:8:\"address2\";s:2:\"12\";s:4:\"city\";s:11:\"minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";N;s:4:\"name\";s:7:\"zzz zzz\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}', 'a:1:{i:0;a:17:{s:2:\"id\";i:279895703575;s:11:\"customer_id\";i:294370410519;s:10:\"first_name\";s:3:\"zzz\";s:9:\"last_name\";s:3:\"zzz\";s:7:\"company\";N;s:8:\"address1\";s:9:\"122 sdfsd\";s:8:\"address2\";s:2:\"12\";s:4:\"city\";s:11:\"minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";N;s:4:\"name\";s:7:\"zzz zzz\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}}'),
	(294953549847, 'assad@gmail.com', 1, '2018-01-06 21:09:59', '2018-01-06 21:09:59', 'BOB', 'HAAHA', 0, 'disabled', '0.00', NULL, 'sdsd', 1, NULL, 0, '+16128128563', 'VIP', NULL, 'a:17:{s:2:\"id\";i:280592810007;s:11:\"customer_id\";i:294953549847;s:10:\"first_name\";s:5:\"sdfsd\";s:9:\"last_name\";s:5:\"sdfsd\";s:7:\"company\";s:10:\"ssdfsdfsdf\";s:8:\"address1\";s:9:\"123 sdfsd\";s:8:\"address2\";s:0:\"\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";s:10:\"6128128563\";s:4:\"name\";s:11:\"sdfsd sdfsd\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}', 'a:1:{i:0;a:17:{s:2:\"id\";i:280592810007;s:11:\"customer_id\";i:294953549847;s:10:\"first_name\";s:5:\"sdfsd\";s:9:\"last_name\";s:5:\"sdfsd\";s:7:\"company\";s:10:\"ssdfsdfsdf\";s:8:\"address1\";s:9:\"123 sdfsd\";s:8:\"address2\";s:0:\"\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";s:10:\"6128128563\";s:4:\"name\";s:11:\"sdfsd sdfsd\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}}'),
	(294955483159, 'sokLAK@gmail.com', 0, '2018-01-06 21:11:56', '2018-01-06 21:14:00', 'lkdslfksdf', 'lsdksldkf', 0, 'disabled', '0.00', NULL, '123123dd', 1, NULL, 0, '+16128128567', 'ddd', NULL, 'a:17:{s:2:\"id\";i:280594710551;s:11:\"customer_id\";i:294955483159;s:10:\"first_name\";s:4:\"ssdf\";s:9:\"last_name\";s:5:\"sdfsd\";s:7:\"company\";s:6:\"sdfsdf\";s:8:\"address1\";s:9:\"1223 sdfs\";s:8:\"address2\";s:0:\"\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";s:10:\"6128128567\";s:4:\"name\";s:10:\"ssdf sdfsd\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}', 'a:1:{i:0;a:17:{s:2:\"id\";i:280594710551;s:11:\"customer_id\";i:294955483159;s:10:\"first_name\";s:4:\"ssdf\";s:9:\"last_name\";s:5:\"sdfsd\";s:7:\"company\";s:6:\"sdfsdf\";s:8:\"address1\";s:9:\"1223 sdfs\";s:8:\"address2\";s:0:\"\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";s:10:\"6128128567\";s:4:\"name\";s:10:\"ssdf sdfsd\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}}'),
	(294958071831, 'fast@gmail.com', 1, '2018-01-06 21:15:22', '2018-01-06 21:15:22', 'YONJA', 'FAST', 0, 'disabled', '0.00', NULL, 'sdfsdf', 1, NULL, 1, '+16128128560', 'VIP', NULL, 'a:17:{s:2:\"id\";i:280597463063;s:11:\"customer_id\";i:294958071831;s:10:\"first_name\";s:4:\"sdfs\";s:9:\"last_name\";s:8:\"dfsdfsdf\";s:7:\"company\";s:6:\"sdfsdf\";s:8:\"address1\";s:6:\"sdfsdf\";s:8:\"address2\";s:3:\"sdf\";s:4:\"city\";s:11:\"minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";s:10:\"6128128560\";s:4:\"name\";s:13:\"sdfs dfsdfsdf\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}', 'a:1:{i:0;a:17:{s:2:\"id\";i:280597463063;s:11:\"customer_id\";i:294958071831;s:10:\"first_name\";s:4:\"sdfs\";s:9:\"last_name\";s:8:\"dfsdfsdf\";s:7:\"company\";s:6:\"sdfsdf\";s:8:\"address1\";s:6:\"sdfsdf\";s:8:\"address2\";s:3:\"sdf\";s:4:\"city\";s:11:\"minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";s:10:\"6128128560\";s:4:\"name\";s:13:\"sdfs dfsdfsdf\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}}'),
	(294964396055, 'loper@gmail.com', 1, '2018-01-06 21:23:39', '2018-01-06 21:23:39', 'LOL', 'Okwere', 0, 'disabled', '0.00', NULL, 'dfsdfdsf', 1, NULL, 0, '+16128128562', '', NULL, 'a:17:{s:2:\"id\";i:280604377111;s:11:\"customer_id\";i:294964396055;s:10:\"first_name\";s:5:\"sdfds\";s:9:\"last_name\";s:5:\"sdfsd\";s:7:\"company\";s:6:\"sdfsdf\";s:8:\"address1\";s:11:\"13123 sdfsd\";s:8:\"address2\";s:0:\"\";s:4:\"city\";s:11:\"minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55403\";s:5:\"phone\";s:10:\"6128128562\";s:4:\"name\";s:11:\"sdfds sdfsd\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}', 'a:1:{i:0;a:17:{s:2:\"id\";i:280604377111;s:11:\"customer_id\";i:294964396055;s:10:\"first_name\";s:5:\"sdfds\";s:9:\"last_name\";s:5:\"sdfsd\";s:7:\"company\";s:6:\"sdfsdf\";s:8:\"address1\";s:11:\"13123 sdfsd\";s:8:\"address2\";s:0:\"\";s:4:\"city\";s:11:\"minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55403\";s:5:\"phone\";s:10:\"6128128562\";s:4:\"name\";s:11:\"sdfds sdfsd\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}}'),
	(294968164375, 'jajaja@gmail.com', 0, '2018-01-06 21:27:59', '2018-05-07 21:45:57', 'KOOL', 'Keith', 0, 'disabled', '0.00', NULL, 'one more', 1, NULL, 0, '+16128128569', 'VIP', NULL, 'a:17:{s:2:\"id\";i:280607784983;s:11:\"customer_id\";i:294968164375;s:10:\"first_name\";s:6:\"sdfsdf\";s:9:\"last_name\";s:5:\"dfsdf\";s:7:\"company\";s:6:\"sdfsdf\";s:8:\"address1\";s:6:\"sdfsdf\";s:8:\"address2\";s:5:\"sdfsd\";s:4:\"city\";s:11:\"minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";s:10:\"6128128569\";s:4:\"name\";s:12:\"sdfsdf dfsdf\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}', 'a:1:{i:0;a:17:{s:2:\"id\";i:280607784983;s:11:\"customer_id\";i:294968164375;s:10:\"first_name\";s:6:\"sdfsdf\";s:9:\"last_name\";s:5:\"dfsdf\";s:7:\"company\";s:6:\"sdfsdf\";s:8:\"address1\";s:6:\"sdfsdf\";s:8:\"address2\";s:5:\"sdfsd\";s:4:\"city\";s:11:\"minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";s:10:\"6128128569\";s:4:\"name\";s:12:\"sdfsdf dfsdf\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}}'),
	(311604871191, 'arobbins@simpleblend.net', 0, '2018-01-22 17:35:38', '2018-02-19 23:04:29', 'Andrew', 'Robbins', 4, 'disabled', '0.00', 312739102743, NULL, 1, NULL, 0, NULL, '', '#1049', 'a:17:{s:2:\"id\";i:298064969751;s:11:\"customer_id\";i:311604871191;s:10:\"first_name\";s:6:\"Andrew\";s:9:\"last_name\";s:7:\"Robbins\";s:7:\"company\";N;s:8:\"address1\";s:12:\"614 N 1st St\";s:8:\"address2\";s:4:\"#606\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";N;s:4:\"name\";s:14:\"Andrew Robbins\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}', 'a:3:{i:0;a:17:{s:2:\"id\";i:297724968983;s:11:\"customer_id\";i:311604871191;s:10:\"first_name\";s:5:\"sdfdf\";s:9:\"last_name\";s:6:\"sdfsdf\";s:7:\"company\";N;s:8:\"address1\";s:21:\"1214 West Lake Street\";s:8:\"address2\";s:0:\"\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55408\";s:5:\"phone\";N;s:4:\"name\";s:12:\"sdfdf sdfsdf\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:0;}i:1;a:17:{s:2:\"id\";i:298064969751;s:11:\"customer_id\";i:311604871191;s:10:\"first_name\";s:6:\"Andrew\";s:9:\"last_name\";s:7:\"Robbins\";s:7:\"company\";N;s:8:\"address1\";s:12:\"614 N 1st St\";s:8:\"address2\";s:4:\"#606\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";N;s:4:\"name\";s:14:\"Andrew Robbins\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}i:2;a:17:{s:2:\"id\";i:299941691415;s:11:\"customer_id\";i:311604871191;s:10:\"first_name\";s:6:\"Andrew\";s:9:\"last_name\";s:7:\"Robbins\";s:7:\"company\";N;s:8:\"address1\";s:20:\"614 North 1st Street\";s:8:\"address2\";s:3:\"606\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";N;s:4:\"name\";s:14:\"Andrew Robbins\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:0;}}'),
	(311933206551, 'hello@byzantine.studio', 0, '2018-01-23 00:46:21', '2018-01-23 20:05:06', 'Andrew', 'Robbins', 5, 'disabled', '0.00', 219584856087, NULL, 1, NULL, 0, NULL, '', '#1047', 'a:17:{s:2:\"id\";i:298085777431;s:11:\"customer_id\";i:311933206551;s:10:\"first_name\";s:6:\"Andrew\";s:9:\"last_name\";s:7:\"Robbins\";s:7:\"company\";N;s:8:\"address1\";s:12:\"614 N 1st St\";s:8:\"address2\";s:4:\"#606\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";N;s:4:\"name\";s:14:\"Andrew Robbins\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}', 'a:1:{i:0;a:17:{s:2:\"id\";i:298085777431;s:11:\"customer_id\";i:311933206551;s:10:\"first_name\";s:6:\"Andrew\";s:9:\"last_name\";s:7:\"Robbins\";s:7:\"company\";N;s:8:\"address1\";s:12:\"614 N 1st St\";s:8:\"address2\";s:4:\"#606\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";N;s:4:\"name\";s:14:\"Andrew Robbins\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}}');


/*!40000 ALTER TABLE `wptests_wps_customers` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table wptests_wps_images
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wptests_wps_images`;

CREATE TABLE `wptests_wps_images` (
  `id` bigint(100) unsigned NOT NULL DEFAULT '0',
  `product_id` bigint(100) DEFAULT NULL,
  `variant_ids` longtext COLLATE utf8mb4_unicode_520_ci,
  `src` longtext COLLATE utf8mb4_unicode_520_ci,
  `alt` longtext COLLATE utf8mb4_unicode_520_ci,
  `position` int(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

LOCK TABLES `wptests_wps_images` WRITE;
/*!40000 ALTER TABLE `wptests_wps_images` DISABLE KEYS */;

INSERT INTO `wptests_wps_images` (`id`, `product_id`, `variant_ids`, `src`, `alt`, `position`, `created_at`, `updated_at`)
VALUES
	(3678664523799, 1345543569431, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/modirerumreiciendis.png?v=1529723576', 'Tempore quod laboriosam provident hic.', 1, '2018-06-23 03:12:55', '2018-06-23 03:12:56'),
	(3678664556567, 1345543602199, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/doloremqueculpaet.png?v=1529723577', 'Et aut veritatis quaerat sed dicta incidunt magnam sed.', 1, '2018-06-23 03:12:57', '2018-06-23 03:12:57'),
	(3678664589335, 1345543634967, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/omnisconsequaturvoluptates.png?v=1529723579', 'Nam illo aut earum porro placeat aperiam dicta ut.', 1, '2018-06-23 03:12:59', '2018-06-23 03:12:59'),
	(3678664654871, 1345543700503, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/reiciendisinciduntaspernatur.png?v=1529723581', 'Quam consequuntur temporibus officia est.', 1, '2018-06-23 03:13:01', '2018-06-23 03:13:01'),
	(3678664720407, 1345543733271, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/quisquiaoptio.png?v=1529723583', 'Molestias voluptatem est nesciunt magni ducimus minus.', 1, '2018-06-23 03:13:02', '2018-06-23 03:13:03'),
	(3678664785943, 1345543798807, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/illoexcepturiut.png?v=1529723585', 'Consectetur dolore itaque tempore aut.', 1, '2018-06-23 03:13:05', '2018-06-23 03:13:05'),
	(3678664818711, 1345543831575, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/nemolaboreoccaecati.png?v=1529723588', 'Nisi deleniti est corrupti.', 1, '2018-06-23 03:13:07', '2018-06-23 03:13:08'),
	(3678664917015, 1345543897111, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/etprovidentbeatae.png?v=1529723590', 'Nostrum enim est quod quas qui est.', 1, '2018-06-23 03:13:09', '2018-06-23 03:13:10'),
	(3678665146391, 1345543929879, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/nisiestbeatae.png?v=1529723592', 'Excepturi maxime sit aliquam.', 1, '2018-06-23 03:13:11', '2018-06-23 03:13:12'),
	(3678665179159, 1345543962647, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/illumullamlaboriosam.png?v=1529723594', 'Magnam voluptatibus sed quia fugiat iste maiores natus accusamus.', 1, '2018-06-23 03:13:13', '2018-06-23 03:13:14'),
	(3678665506839, 1345544028183, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/laboreetmagni.png?v=1529723596', 'Id aut enim et ratione alias.', 1, '2018-06-23 03:13:15', '2018-06-23 03:13:16'),
	(3678665539607, 1345544060951, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/occaecatiquiaaut.png?v=1529723597', 'Amet fuga doloremque nam aut.', 1, '2018-06-23 03:13:17', '2018-06-23 03:13:17'),
	(3678665605143, 1345544126487, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/errorteneturab.png?v=1529723599', 'Id error rerum quas cumque voluptates perspiciatis velit.', 1, '2018-06-23 03:13:19', '2018-06-23 03:13:19'),
	(3678665670679, 1345544159255, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/minusidtempore.png?v=1529723601', 'Dicta at et blanditiis.', 1, '2018-06-23 03:13:21', '2018-06-23 03:13:21'),
	(3678665703447, 1345544192023, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/dolorarchitectodolore.png?v=1529723603', 'Voluptas omnis enim est atque non.', 1, '2018-06-23 03:13:22', '2018-06-23 03:13:23'),
	(3715591110679, 1355609767959, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/doloremerroraliquam.png?v=1530666138', 'Eum explicabo quae accusamus itaque.', 1, '2018-07-04 01:02:18', '2018-07-04 01:02:18'),
	(3715591208983, 1355609800727, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/autaperiamsaepe.png?v=1530666141', 'Hic consequuntur facere velit.', 1, '2018-07-04 01:02:21', '2018-07-04 01:02:21'),
	(3715591274519, 1355609833495, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/aperiamreprehenderitsit.png?v=1530666144', 'At et molestias ut.', 1, '2018-07-04 01:02:23', '2018-07-04 01:02:24'),
	(3715591307287, 1355609866263, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/velitsaepepossimus.png?v=1530666147', 'Magni omnis aperiam voluptas nobis fugit id aliquam ad.', 1, '2018-07-04 01:02:26', '2018-07-04 01:02:27'),
	(3715591405591, 1355609899031, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/nesciuntnihilnon.png?v=1530666149', 'Molestiae excepturi cupiditate incidunt quas.', 1, '2018-07-04 01:02:29', '2018-07-04 01:02:29'),
	(3715591471127, 1355609931799, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/providentuteaque.png?v=1530666152', 'Doloremque at ut id illo quis earum.', 1, '2018-07-04 01:02:31', '2018-07-04 01:02:32'),
	(3715591503895, 1355609964567, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/repudiandaeomnismodi.png?v=1530666154', 'Et rerum nihil mollitia.', 1, '2018-07-04 01:02:34', '2018-07-04 01:02:34'),
	(3715591569431, 1355609997335, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/voluptasdignissimosomnis.png?v=1530666156', 'Quidem ut reiciendis a.', 1, '2018-07-04 01:02:36', '2018-07-04 01:02:36'),
	(3715591634967, 1355610030103, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/ullamdelenitiest.png?v=1530666158', 'Nulla cum id enim.', 1, '2018-07-04 01:02:38', '2018-07-04 01:02:38'),
	(3715591733271, 1355610062871, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/autemmaximenumquam.png?v=1530666161', 'Fuga molestiae dolor error.', 1, '2018-07-04 01:02:41', '2018-07-04 01:02:41'),
	(3715591798807, 1355610095639, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/accusantiumautemquod.png?v=1530666163', 'Sit enim et quis voluptatem quaerat culpa.', 1, '2018-07-04 01:02:42', '2018-07-04 01:02:43'),
	(3715591831575, 1355610128407, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/sitvelitet.png?v=1530666165', 'Rerum error possimus alias nihil repellat rerum.', 1, '2018-07-04 01:02:45', '2018-07-04 01:02:45'),
	(3715591929879, 1355610161175, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/tenetursitmaiores.png?v=1530666168', 'Eum nostrum aperiam ut similique accusantium iusto.', 1, '2018-07-04 01:02:47', '2018-07-04 01:02:48'),
	(3715592028183, 1355610259479, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/perferendisdolorumporro.png?v=1530666170', 'Sunt deserunt accusamus aliquid nemo nobis.', 1, '2018-07-04 01:02:50', '2018-07-04 01:02:50'),
	(3715592060951, 1355610292247, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/possimusquodsint.png?v=1530666172', 'Ea quo officia qui itaque dignissimos sit cum laborum.', 1, '2018-07-04 01:02:52', '2018-07-04 01:02:52'),
	(3715592126487, 1355610325015, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/earumnemonatus.png?v=1530666174', 'Iusto cupiditate autem nulla.', 1, '2018-07-04 01:02:53', '2018-07-04 01:02:54'),
	(3715592159255, 1355610357783, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/doloremfacereea.png?v=1530666176', 'Tempore et repellendus voluptatum amet.', 1, '2018-07-04 01:02:55', '2018-07-04 01:02:56'),
	(3715592224791, 1355610423319, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/sitrepellatab.png?v=1530666178', 'Excepturi natus repellat quas atque.', 1, '2018-07-04 01:02:57', '2018-07-04 01:02:58'),
	(3715592355863, 1355610488855, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/omnisquaeut.png?v=1530666180', 'Doloremque deleniti nihil eum quo ducimus iure corporis.', 1, '2018-07-04 01:02:59', '2018-07-04 01:03:00'),
	(3715592454167, 1355610554391, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/atullamearum.png?v=1530666182', 'Animi aut et laboriosam nostrum earum similique.', 1, '2018-07-04 01:03:01', '2018-07-04 01:03:02'),
	(3715592519703, 1355610619927, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/necessitatibushicest.png?v=1530666183', 'Id eos voluptates iusto sit earum.', 1, '2018-07-04 01:03:03', '2018-07-04 01:03:03'),
	(3715592585239, 1355610652695, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/nequedoloreunde.png?v=1530666185', 'Occaecati quidem et et non sed voluptatum dolore rem.', 1, '2018-07-04 01:03:05', '2018-07-04 01:03:05'),
	(3715592650775, 1355610685463, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/culpaomnisquas.png?v=1530666187', 'Distinctio nemo ex ut ea.', 1, '2018-07-04 01:03:07', '2018-07-04 01:03:07'),
	(3715592716311, 1355610718231, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/voluptasmaximeid.png?v=1530666189', 'Non aspernatur dolores voluptatem optio atque beatae sunt similique.', 1, '2018-07-04 01:03:09', '2018-07-04 01:03:09'),
	(3715592781847, 1355610750999, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/veritatisdoloresillo.png?v=1530666191', 'Consequatur iusto ut ut.', 1, '2018-07-04 01:03:11', '2018-07-04 01:03:11'),
	(3715592880151, 1355610783767, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/nonsintitaque.png?v=1530666193', 'Dolorum quam molestiae minus dignissimos.', 1, '2018-07-04 01:03:13', '2018-07-04 01:03:13'),
	(3715592912919, 1355610816535, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/aliquidearumquam.png?v=1530666195', 'Sint voluptatem totam vitae.', 1, '2018-07-04 01:03:15', '2018-07-04 01:03:15'),
	(3715592978455, 1355610882071, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/quoducimusquia.png?v=1530666197', 'Tenetur veniam et qui enim modi fuga.', 1, '2018-07-04 01:03:17', '2018-07-04 01:03:17'),
	(3715593076759, 1355610914839, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/enimoditvel.png?v=1530666199', 'Delectus voluptate omnis saepe aut minima.', 1, '2018-07-04 01:03:19', '2018-07-04 01:03:19'),
	(3715593142295, 1355610947607, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/ineiusvoluptatem.png?v=1530666202', 'Temporibus consequatur quaerat autem delectus beatae.', 1, '2018-07-04 01:03:21', '2018-07-04 01:03:22'),
	(3715593240599, 1355610980375, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/itaquefugitnihil.png?v=1530666204', 'Quam aut voluptatibus ex tenetur sunt omnis.', 1, '2018-07-04 01:03:23', '2018-07-04 01:03:24'),
	(3715593273367, 1355611013143, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/etmolestiaeid.png?v=1530666206', 'Dolores facere sint doloribus et dolorem possimus quibusdam quo.', 1, '2018-07-04 01:03:25', '2018-07-04 01:03:26'),
	(3715593338903, 1355611045911, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/pariatursedsaepe.png?v=1530666208', 'Esse doloremque aut porro omnis alias aut velit in.', 1, '2018-07-04 01:03:27', '2018-07-04 01:03:28'),
	(3715593404439, 1355611078679, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/occaecaticommodinihil.png?v=1530666210', 'Inventore qui velit velit.', 1, '2018-07-04 01:03:29', '2018-07-04 01:03:30'),
	(3715593437207, 1355611111447, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/solutapraesentiumquas.png?v=1530666212', 'Necessitatibus aut optio nihil.', 1, '2018-07-04 01:03:32', '2018-07-04 01:03:32'),
	(3715593535511, 1355611144215, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/remiuresit.png?v=1530666214', 'Debitis doloribus vel illum et et blanditiis quisquam dicta.', 1, '2018-07-04 01:03:34', '2018-07-04 01:03:34'),
	(3715593601047, 1355611209751, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/iustoreiciendisfugit.png?v=1530666216', 'Impedit dignissimos suscipit maiores et quo quod tenetur dolorem.', 1, '2018-07-04 01:03:36', '2018-07-04 01:03:36'),
	(3715593666583, 1355611242519, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/ipsumauttotam.png?v=1530666218', 'Qui atque sint eveniet praesentium.', 1, '2018-07-04 01:03:37', '2018-07-04 01:03:38'),
	(3715593764887, 1355611275287, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/nametut.png?v=1530666220', 'Perferendis hic nisi et nesciunt soluta totam atque magnam.', 1, '2018-07-04 01:03:39', '2018-07-04 01:03:40'),
	(3715593928727, 1355611308055, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/veniamvelitperspiciatis.png?v=1530666221', 'Fugiat aut debitis quisquam.', 1, '2018-07-04 01:03:41', '2018-07-04 01:03:41'),
	(3715594125335, 1355611340823, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/voluptatesnostrumet.png?v=1530666223', 'Hic dolore magnam consectetur sit itaque nulla quasi.', 1, '2018-07-04 01:03:43', '2018-07-04 01:03:43'),
	(3715594190871, 1355611373591, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/quasiatminus.png?v=1530666225', 'Id necessitatibus voluptatem aperiam inventore.', 1, '2018-07-04 01:03:45', '2018-07-04 01:03:45'),
	(3715594289175, 1355611406359, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/repellendustemporaamet.png?v=1530666227', 'Dolorum repellendus exercitationem corrupti et est.', 1, '2018-07-04 01:03:47', '2018-07-04 01:03:47'),
	(3715594354711, 1355611439127, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/atquenobisrerum.png?v=1530666229', 'Velit corporis laboriosam dignissimos veritatis eaque voluptas rerum ipsam.', 1, '2018-07-04 01:03:49', '2018-07-04 01:03:49'),
	(3715594485783, 1355611471895, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/utnemodebitis.png?v=1530666231', 'Laboriosam ullam est rerum ut est voluptate vel.', 1, '2018-07-04 01:03:51', '2018-07-04 01:03:51'),
	(3715594649623, 1355611504663, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/natuserrorsit.png?v=1530666233', 'Nulla vitae beatae quia odit dolores.', 1, '2018-07-04 01:03:53', '2018-07-04 01:03:53'),
	(3715594715159, 1355611537431, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/moditemporavel.png?v=1530666235', 'Esse eligendi quo sint.', 1, '2018-07-04 01:03:55', '2018-07-04 01:03:55'),
	(3715594813463, 1355611570199, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/etesseminima.png?v=1530666237', 'Ab quo sed quasi id adipisci quia.', 1, '2018-07-04 01:03:57', '2018-07-04 01:03:57'),
	(3715594911767, 1355611635735, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/veritatisodiolaborum.png?v=1530666239', 'Mollitia ex sunt tenetur voluptatem laboriosam.', 1, '2018-07-04 01:03:59', '2018-07-04 01:03:59'),
	(3715595075607, 1355611668503, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/praesentiumdoloresab.png?v=1530666241', 'Id recusandae ipsa sunt.', 1, '2018-07-04 01:04:01', '2018-07-04 01:04:01'),
	(3715595141143, 1355611701271, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/sapientequiamolestiae.png?v=1530666243', 'Doloribus culpa ut odit voluptas totam quaerat quia natus.', 1, '2018-07-04 01:04:03', '2018-07-04 01:04:03'),
	(3715595173911, 1355611734039, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/officiarepellendusnesciunt.png?v=1530666246', 'Quia ut excepturi officia rerum.', 1, '2018-07-04 01:04:05', '2018-07-04 01:04:06'),
	(3715595239447, 1355611897879, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/sedaliasnon.png?v=1530666248', 'Rem beatae quo architecto omnis assumenda eligendi quis.', 1, '2018-07-04 01:04:08', '2018-07-04 01:04:08'),
	(3715595337751, 1355611930647, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/fugitpraesentiumconsectetur.png?v=1530666250', 'Et aut quibusdam perferendis.', 1, '2018-07-04 01:04:09', '2018-07-04 01:04:10'),
	(3715595403287, 1355611963415, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/ducimusplaceatomnis.png?v=1530666252', 'Quisquam aliquid quaerat soluta tempora voluptatum molestiae.', 1, '2018-07-04 01:04:11', '2018-07-04 01:04:12'),
	(3715595468823, 1355611996183, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/eosremet.png?v=1530666253', 'Ea veritatis minima tempore voluptatem iste.', 1, '2018-07-04 01:04:13', '2018-07-04 01:04:13'),
	(3715595567127, 1355612061719, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/aspernaturutasperiores.png?v=1530666255', 'Enim ab unde sit dolore architecto error consequatur.', 1, '2018-07-04 01:04:15', '2018-07-04 01:04:15'),
	(3715595632663, 1355612094487, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/autatanimi.png?v=1530666258', 'Aperiam omnis vel voluptatem ducimus necessitatibus et.', 1, '2018-07-04 01:04:17', '2018-07-04 01:04:18'),
	(3715595730967, 1355612160023, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/perspiciatiscumquis.png?v=1530666261', 'Et sit odio voluptates facilis.', 1, '2018-07-04 01:04:20', '2018-07-04 01:04:21'),
	(3715595796503, 1355612192791, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/quiainaperiam.png?v=1530666263', 'Doloribus dicta et qui id.', 1, '2018-07-04 01:04:23', '2018-07-04 01:04:23'),
	(3715595862039, 1355612225559, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/omnisofficiaiste.png?v=1530666265', 'Accusantium occaecati impedit et vero.', 1, '2018-07-04 01:04:25', '2018-07-04 01:04:25'),
	(3715595993111, 1355612258327, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/voluptatemreprehenderitat.png?v=1530666268', 'Hic consequuntur tenetur occaecati ea harum.', 1, '2018-07-04 01:04:27', '2018-07-04 01:04:28'),
	(3715596091415, 1355612291095, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/commodinecessitatibuslaboriosam.png?v=1530666270', 'Itaque et voluptates ex quasi dolore optio perspiciatis voluptatem.', 1, '2018-07-04 01:04:30', '2018-07-04 01:04:30'),
	(3715596189719, 1355612323863, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/velitexplicabohic.png?v=1530666273', 'Atque id voluptatem deserunt aut.', 1, '2018-07-04 01:04:32', '2018-07-04 01:04:33'),
	(3715596288023, 1355612356631, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/velsitea.png?v=1530666275', 'Rem iure et dolorum repellendus.', 1, '2018-07-04 01:04:34', '2018-07-04 01:04:35'),
	(3715596353559, 1355612422167, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/rerumesteos.png?v=1530666278', 'Ea quasi et eligendi necessitatibus molestias.', 1, '2018-07-04 01:04:37', '2018-07-04 01:04:38'),
	(3715596386327, 1355612454935, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/quascumquequo.png?v=1530666280', 'Molestias eum natus aut ipsa praesentium alias.', 1, '2018-07-04 01:04:39', '2018-07-04 01:04:40'),
	(3715596419095, 1355612487703, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/sapienteestcum.png?v=1530666282', 'Blanditiis ea sunt quaerat aut illum vel qui.', 1, '2018-07-04 01:04:42', '2018-07-04 01:04:42'),
	(3715596451863, 1355612553239, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/sitperspiciatisvelit.png?v=1530666284', 'Qui nobis quia qui architecto fugiat.', 1, '2018-07-04 01:04:44', '2018-07-04 01:04:44'),
	(3715596517399, 1355612586007, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/velittemporeamet.png?v=1530666286', 'Dolorem reiciendis doloremque eligendi earum quis nesciunt cupiditate saepe.', 1, '2018-07-04 01:04:45', '2018-07-04 01:04:46'),
	(3715596550167, 1355612618775, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/eumsimiliqueeos.png?v=1530666288', 'Quam cumque explicabo voluptatem sint dolore sunt molestiae.', 1, '2018-07-04 01:04:48', '2018-07-04 01:04:48'),
	(3715596648471, 1355612651543, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/doloresreiciendisautem.png?v=1530666291', 'Aperiam quo eos et.', 1, '2018-07-04 01:04:51', '2018-07-04 01:04:51'),
	(3715596714007, 1355612684311, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/aperiametvoluptate.png?v=1530666293', 'Quidem ex officiis quasi tenetur sunt rerum quibusdam eligendi.', 1, '2018-07-04 01:04:53', '2018-07-04 01:04:53'),
	(3715596746775, 1355612717079, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/doloremcumqueet.png?v=1530666296', 'Et voluptatem nemo eos et et.', 1, '2018-07-04 01:04:55', '2018-07-04 01:04:56'),
	(3715596845079, 1355612749847, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/corruptiipsaqui.png?v=1530666298', 'Saepe dolore magni labore.', 1, '2018-07-04 01:04:57', '2018-07-04 01:04:58'),
	(3715596877847, 1355612782615, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/saepeperspiciatisexercitationem.png?v=1530666300', 'Officiis exercitationem ab odit.', 1, '2018-07-04 01:04:59', '2018-07-04 01:05:00'),
	(3715596943383, 1355612815383, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/idsuscipitlaborum.png?v=1530666302', 'Enim ut repellat et dolores qui hic.', 1, '2018-07-04 01:05:02', '2018-07-04 01:05:02'),
	(3715596976151, 1355612848151, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/verolaboriosamqui.png?v=1530666305', 'Similique aut quia dolores.', 1, '2018-07-04 01:05:04', '2018-07-04 01:05:05'),
	(3715597074455, 1355612880919, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/temporeexcepturilabore.png?v=1530666307', 'Atque sit aut enim qui ut rem quam accusantium.', 1, '2018-07-04 01:05:07', '2018-07-04 01:05:07'),
	(3715597139991, 1355612913687, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/aliasfugitmodi.png?v=1530666309', 'Accusamus odit magnam consectetur aut praesentium qui neque aut.', 1, '2018-07-04 01:05:09', '2018-07-04 01:05:09'),
	(3715597172759, 1355612946455, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/atquequiaat.png?v=1530666312', 'Aut dolor et deserunt eum dolorem.', 1, '2018-07-04 01:05:12', '2018-07-04 01:05:12'),
	(3715597271063, 1355612979223, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/idfaciliseos.png?v=1530666314', 'Ad mollitia adipisci dolor in voluptate quo tempora consectetur.', 1, '2018-07-04 01:05:13', '2018-07-04 01:05:14'),
	(3715597303831, 1355613011991, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/utnoncommodi.png?v=1530666316', 'In voluptatem odio consequatur dolores voluptatem asperiores accusantium.', 1, '2018-07-04 01:05:15', '2018-07-04 01:05:16'),
	(3715597369367, 1355613044759, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/minimadignissimosrerum.png?v=1530666318', 'Deleniti aliquid eos assumenda debitis quis ut.', 1, '2018-07-04 01:05:18', '2018-07-04 01:05:18'),
	(3715597434903, 1355613077527, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/quossedsunt.png?v=1530666320', 'Incidunt ut libero aut et.', 1, '2018-07-04 01:05:20', '2018-07-04 01:05:20'),
	(3715597500439, 1355613110295, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/temporeiuresunt.png?v=1530666323', 'Dolorem animi adipisci ut.', 1, '2018-07-04 01:05:22', '2018-07-04 01:05:23'),
	(3715597598743, 1355613175831, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/numquamvoluptatesquis.png?v=1530666325', 'Sequi quis ipsa tenetur sed reprehenderit sunt id.', 1, '2018-07-04 01:05:25', '2018-07-04 01:05:25'),
	(3715597631511, 1355613274135, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/reiciendisporroeum.png?v=1530666328', 'Commodi labore unde possimus corrupti recusandae.', 1, '2018-07-04 01:05:27', '2018-07-04 01:05:28'),
	(3715597697047, 1355613306903, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/estperspiciatiscum.png?v=1530666330', 'Sit voluptatem molestiae molestiae quidem sapiente necessitatibus nihil.', 1, '2018-07-04 01:05:30', '2018-07-04 01:05:30'),
	(3715597762583, 1355613339671, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/porrositnecessitatibus.png?v=1530666333', 'Minima quo repellat et sed odio in ab.', 1, '2018-07-04 01:05:32', '2018-07-04 01:05:33'),
	(3715597860887, 1355613372439, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/animicumatque.png?v=1530666335', 'Sit voluptas omnis consectetur eos.', 1, '2018-07-04 01:05:34', '2018-07-04 01:05:35'),
	(3715597926423, 1355613437975, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/sedetsint.png?v=1530666336', 'Laboriosam occaecati corrupti aut.', 1, '2018-07-04 01:05:36', '2018-07-04 01:05:36'),
	(3715597991959, 1355613470743, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/laborumetlaboriosam.png?v=1530666339', 'Ipsum quia deserunt error.', 1, '2018-07-04 01:05:38', '2018-07-04 01:05:39'),
	(3715598155799, 1355613503511, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/veleadoloribus.png?v=1530666343', 'Facere fugiat accusantium et ut.', 1, '2018-07-04 01:05:43', '2018-07-04 01:05:43'),
	(3715598254103, 1355613536279, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/doloretculpa.png?v=1530666347', 'Fugiat dolor quo dolorum vitae sunt.', 1, '2018-07-04 01:05:46', '2018-07-04 01:05:47'),
	(3715598286871, 1355613569047, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/etmolestiaesit.png?v=1530666349', 'Totam voluptatem reprehenderit deleniti.', 1, '2018-07-04 01:05:48', '2018-07-04 01:05:49'),
	(3715598352407, 1355613601815, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/quaeratdoloresomnis.png?v=1530666352', 'Corporis blanditiis ad ex sint voluptas saepe.', 1, '2018-07-04 01:05:51', '2018-07-04 01:05:52'),
	(3715598385175, 1355613634583, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/ipsamdoloremillum.png?v=1530666355', 'Dicta nam dolorem omnis placeat.', 1, '2018-07-04 01:05:54', '2018-07-04 01:05:55'),
	(3715598450711, 1355613667351, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/voluptatemquaetemporibus.png?v=1530666358', 'Expedita quo tenetur sint voluptas corporis debitis perferendis corrupti.', 1, '2018-07-04 01:05:57', '2018-07-04 01:05:58'),
	(3715598549015, 1355613700119, 'a:0:{}', 'https://cdn.shopify.com/s/files/1/2400/7681/products/quiestest.png?v=1530666361', 'Temporibus possimus culpa iusto consequuntur.', 1, '2018-07-04 01:06:00', '2018-07-04 01:06:01');


/*!40000 ALTER TABLE `wptests_wps_images` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table wptests_wps_options
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wptests_wps_options`;

CREATE TABLE `wptests_wps_options` (
  `id` bigint(100) unsigned NOT NULL DEFAULT '0',
  `product_id` bigint(100) DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `position` int(20) DEFAULT NULL,
  `values` longtext COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

LOCK TABLES `wptests_wps_options` WRITE;
/*!40000 ALTER TABLE `wptests_wps_options` DISABLE KEYS */;

INSERT INTO `wptests_wps_options` (`id`, `product_id`, `name`, `position`, `values`)
VALUES
	(1795850862615, 1345543569431, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1795850895383, 1345543602199, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1795850928151, 1345543634967, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1795850993687, 1345543700503, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1795851026455, 1345543733271, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1795851124759, 1345543798807, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1795851157527, 1345543831575, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1795851223063, 1345543897111, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1795851255831, 1345543929879, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1795851288599, 1345543962647, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1795851354135, 1345544028183, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1795851386903, 1345544060951, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1795851452439, 1345544126487, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1795851485207, 1345544159255, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1795851517975, 1345544192023, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809407082519, 1355609767959, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809407115287, 1355609800727, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809407148055, 1355609833495, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809407180823, 1355609866263, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809407213591, 1355609899031, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809407246359, 1355609931799, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809407279127, 1355609964567, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809407311895, 1355609997335, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809407344663, 1355610030103, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809407377431, 1355610062871, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809407410199, 1355610095639, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809407442967, 1355610128407, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809407475735, 1355610161175, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809407639575, 1355610259479, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809407672343, 1355610292247, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809407705111, 1355610325015, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809407737879, 1355610357783, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809407836183, 1355610423319, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809407901719, 1355610488855, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809408000023, 1355610554391, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809408065559, 1355610619927, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809408098327, 1355610652695, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809408131095, 1355610685463, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809408163863, 1355610718231, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809408196631, 1355610750999, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809408229399, 1355610783767, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809408262167, 1355610816535, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809408327703, 1355610882071, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809408360471, 1355610914839, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809408393239, 1355610947607, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809408426007, 1355610980375, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809408458775, 1355611013143, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809408491543, 1355611045911, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809408524311, 1355611078679, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809408557079, 1355611111447, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809408589847, 1355611144215, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809408655383, 1355611209751, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809408688151, 1355611242519, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809408720919, 1355611275287, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809408753687, 1355611308055, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809408786455, 1355611340823, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809408819223, 1355611373591, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809408851991, 1355611406359, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809408884759, 1355611439127, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809408917527, 1355611471895, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809408950295, 1355611504663, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809408983063, 1355611537431, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809409015831, 1355611570199, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809409081367, 1355611635735, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809409114135, 1355611668503, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809409146903, 1355611701271, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809409179671, 1355611734039, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809409474583, 1355611897879, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809409507351, 1355611930647, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809409540119, 1355611963415, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809409572887, 1355611996183, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809409671191, 1355612061719, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809409703959, 1355612094487, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809409802263, 1355612160023, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809409835031, 1355612192791, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809409867799, 1355612225559, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809409900567, 1355612258327, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809409933335, 1355612291095, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809409966103, 1355612323863, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809409998871, 1355612356631, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809410064407, 1355612422167, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809410097175, 1355612454935, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809410129943, 1355612487703, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809410195479, 1355612553239, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809410228247, 1355612586007, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809410261015, 1355612618775, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809410293783, 1355612651543, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809410326551, 1355612684311, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809410359319, 1355612717079, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809410392087, 1355612749847, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809410424855, 1355612782615, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809410457623, 1355612815383, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809410490391, 1355612848151, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809410523159, 1355612880919, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809410555927, 1355612913687, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809410588695, 1355612946455, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809410621463, 1355612979223, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809410654231, 1355613011991, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809410686999, 1355613044759, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809410719767, 1355613077527, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809410752535, 1355613110295, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809410850839, 1355613175831, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809411014679, 1355613274135, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809411047447, 1355613306903, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809411080215, 1355613339671, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809411112983, 1355613372439, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809411211287, 1355613437975, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809411244055, 1355613470743, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809411276823, 1355613503511, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809411309591, 1355613536279, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809411342359, 1355613569047, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809411375127, 1355613601815, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809411407895, 1355613634583, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809411440663, 1355613667351, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}'),
	(1809411473431, 1355613700119, 'Title', 1, 'a:4:{i:0;s:11:\"Extra Small\";i:1;s:5:\"Small\";i:2;s:6:\"Medium\";i:3;s:5:\"Large\";}');


/*!40000 ALTER TABLE `wptests_wps_options` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table wptests_wps_orders
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wptests_wps_orders`;

CREATE TABLE `wptests_wps_orders` (
  `id` bigint(100) unsigned NOT NULL,
  `customer_id` bigint(100) unsigned DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `closed_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `number` bigint(100) unsigned DEFAULT NULL,
  `note` longtext COLLATE utf8mb4_unicode_520_ci,
  `token` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `gateway` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `total_price` decimal(12,2) DEFAULT '0.00',
  `subtotal_price` decimal(12,2) DEFAULT '0.00',
  `total_weight` bigint(100) unsigned DEFAULT NULL,
  `total_tax` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `taxes_included` tinyint(1) DEFAULT '0',
  `currency` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `financial_status` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `confirmed` tinyint(1) DEFAULT '0',
  `total_discounts` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `total_line_items_price` decimal(12,2) DEFAULT '0.00',
  `cart_token` longtext COLLATE utf8mb4_unicode_520_ci,
  `buyer_accepts_marketing` tinyint(1) DEFAULT '0',
  `name` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `referring_site` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `landing_site` longtext COLLATE utf8mb4_unicode_520_ci,
  `cancelled_at` datetime DEFAULT NULL,
  `cancel_reason` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `total_price_usd` decimal(12,2) DEFAULT '0.00',
  `checkout_token` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `user_id` bigint(100) unsigned DEFAULT NULL,
  `location_id` bigint(100) unsigned DEFAULT NULL,
  `source_identifier` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `source_url` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `processed_at` datetime DEFAULT NULL,
  `device_id` bigint(100) unsigned DEFAULT NULL,
  `phone` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `customer_locale` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `app_id` bigint(100) unsigned DEFAULT NULL,
  `browser_ip` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `landing_site_ref` longtext COLLATE utf8mb4_unicode_520_ci,
  `order_number` bigint(100) unsigned DEFAULT NULL,
  `discount_codes` longtext COLLATE utf8mb4_unicode_520_ci,
  `note_attributes` longtext COLLATE utf8mb4_unicode_520_ci,
  `payment_gateway_names` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `processing_method` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `checkout_id` bigint(100) unsigned DEFAULT NULL,
  `source_name` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `fulfillment_status` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `tax_lines` longtext COLLATE utf8mb4_unicode_520_ci,
  `tags` longtext COLLATE utf8mb4_unicode_520_ci,
  `contact_email` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `order_status_url` longtext COLLATE utf8mb4_unicode_520_ci,
  `line_items` longtext COLLATE utf8mb4_unicode_520_ci,
  `shipping_lines` longtext COLLATE utf8mb4_unicode_520_ci,
  `billing_address` longtext COLLATE utf8mb4_unicode_520_ci,
  `shipping_address` longtext COLLATE utf8mb4_unicode_520_ci,
  `fulfillments` longtext COLLATE utf8mb4_unicode_520_ci,
  `client_details` longtext COLLATE utf8mb4_unicode_520_ci,
  `refunds` longtext COLLATE utf8mb4_unicode_520_ci,
  `customer` longtext COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

LOCK TABLES `wptests_wps_orders` WRITE;
/*!40000 ALTER TABLE `wptests_wps_orders` DISABLE KEYS */;

INSERT INTO `wptests_wps_orders` (`id`, `customer_id`, `email`, `closed_at`, `created_at`, `updated_at`, `number`, `note`, `token`, `gateway`, `total_price`, `subtotal_price`, `total_weight`, `total_tax`, `taxes_included`, `currency`, `financial_status`, `confirmed`, `total_discounts`, `total_line_items_price`, `cart_token`, `buyer_accepts_marketing`, `name`, `referring_site`, `landing_site`, `cancelled_at`, `cancel_reason`, `total_price_usd`, `checkout_token`, `reference`, `user_id`, `location_id`, `source_identifier`, `source_url`, `processed_at`, `device_id`, `phone`, `customer_locale`, `app_id`, `browser_ip`, `landing_site_ref`, `order_number`, `discount_codes`, `note_attributes`, `payment_gateway_names`, `processing_method`, `checkout_id`, `source_name`, `fulfillment_status`, `tax_lines`, `tags`, `contact_email`, `order_status_url`, `line_items`, `shipping_lines`, `billing_address`, `shipping_address`, `fulfillments`, `client_details`, `refunds`, `customer`)
VALUES
	(96449036311, 0, 'arobbins@simpleblend.net', NULL, '2017-09-29 18:40:34', '2017-09-29 18:40:34', 1, NULL, 'fc3361fea1a1d94732c67af02d8cf884', NULL, 0.00, 0.00, 363, '0.00', 0, 'USD', 'paid', 1, '6.33', 0.00, NULL, 0, '#1001', 'http://wpstest.dev/products/enormous-plastic-table/', '/cart/1102351400983:1?access_token=9596a847f3f4669fa8f4335a13386bd0&_fd=0&_ga=2.60002541.179326096.1506704288-127972611.1506704288', NULL, NULL, 0.00, 'fd842e00e09720f069755473f792caa9', NULL, NULL, NULL, NULL, NULL, '2017-09-29 18:40:34', NULL, NULL, 'en', 88312, NULL, NULL, 1001, 'a:1:{i:0;a:3:{s:4:\"code\";s:4:\"FREE\";s:6:\"amount\";s:4:\"6.33\";s:4:\"type\";s:8:\"shipping\";}}', 'a:0:{}', 'a:0:{}', 'free', 174577418263, 'web', NULL, 'a:0:{}', '', 'arobbins@simpleblend.net', 'https://wpslitetest10.myshopify.com/24007681/orders/fc3361fea1a1d94732c67af02d8cf884/authenticate?key=be21d1ba77ff361216133c3e519280f5', 'a:1:{i:0;a:26:{s:2:\"id\";i:58171293719;s:10:\"variant_id\";i:1102351400983;s:5:\"title\";s:24:\"Enormous Plastic Table11\";s:8:\"quantity\";i:1;s:5:\"price\";s:4:\"0.00\";s:3:\"sku\";s:28:\"enormous-plastic-table-small\";s:13:\"variant_title\";s:5:\"Small\";s:6:\"vendor\";s:14:\"Sanford-Barton\";s:19:\"fulfillment_service\";s:6:\"manual\";s:10:\"product_id\";N;s:17:\"requires_shipping\";b:1;s:7:\"taxable\";b:1;s:9:\"gift_card\";b:0;s:4:\"name\";s:32:\"Enormous Plastic Table11 - Small\";s:28:\"variant_inventory_management\";N;s:10:\"properties\";a:0:{}s:14:\"product_exists\";b:0;s:20:\"fulfillable_quantity\";i:1;s:5:\"grams\";i:362;s:14:\"total_discount\";s:4:\"0.00\";s:18:\"fulfillment_status\";N;s:20:\"discount_allocations\";a:0:{}s:20:\"admin_graphql_api_id\";s:34:\"gid://shopify/LineItem/58171293719\";s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.004;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}s:15:\"origin_location\";a:8:{s:2:\"id\";i:28088926231;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:13:\"wpslitetest10\";s:8:\"address1\";s:11:\"123 fsdfsdj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55408\";}s:20:\"destination_location\";a:8:{s:2:\"id\";i:30342840343;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:14:\"Andrew Robbins\";s:8:\"address1\";s:12:\"614 N 1st St\";s:8:\"address2\";s:3:\"606\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";}}}', 'a:1:{i:0;a:12:{s:2:\"id\";i:72340209687;s:5:\"title\";s:13:\"Priority Mail\";s:5:\"price\";s:4:\"6.33\";s:4:\"code\";s:8:\"Priority\";s:6:\"source\";s:4:\"usps\";s:5:\"phone\";N;s:32:\"requested_fulfillment_service_id\";N;s:17:\"delivery_category\";N;s:18:\"carrier_identifier\";N;s:16:\"discounted_price\";s:4:\"6.33\";s:20:\"discount_allocations\";a:1:{i:0;a:2:{s:6:\"amount\";s:4:\"6.33\";s:26:\"discount_application_index\";i:0;}}s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.004;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}}}', 'a:15:{s:10:\"first_name\";s:6:\"Andrew\";s:8:\"address1\";s:12:\"614 N 1st St\";s:5:\"phone\";N;s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:7:\"Robbins\";s:8:\"address2\";s:3:\"606\";s:7:\"company\";N;s:8:\"latitude\";d:44.989168;s:9:\"longitude\";d:-93.2738601;s:4:\"name\";s:14:\"Andrew Robbins\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:15:{s:10:\"first_name\";s:6:\"Andrew\";s:8:\"address1\";s:12:\"614 N 1st St\";s:5:\"phone\";N;s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:7:\"Robbins\";s:8:\"address2\";s:3:\"606\";s:7:\"company\";N;s:8:\"latitude\";d:44.989168;s:9:\"longitude\";d:-93.2738601;s:4:\"name\";s:14:\"Andrew Robbins\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:0:{}', 'a:6:{s:10:\"browser_ip\";s:13:\"73.37.184.141\";s:15:\"accept_language\";s:32:\"en-US,en;q=0.8,nb;q=0.6,la;q=0.4\";s:10:\"user_agent\";s:121:\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36\";s:12:\"session_hash\";N;s:13:\"browser_width\";i:1144;s:14:\"browser_height\";i:949;}', 'a:0:{}', 'a:20:{s:2:\"id\";i:127274778647;s:5:\"email\";s:31:\"zzzzzzzarobbins@simpleblend.net\";s:17:\"accepts_marketing\";b:0;s:10:\"created_at\";s:25:\"2017-09-29T13:37:59-05:00\";s:10:\"updated_at\";s:25:\"2018-07-21T13:51:35-05:00\";s:10:\"first_name\";s:9:\"Andrewrrr\";s:9:\"last_name\";s:13:\"Robbinszzzzzz\";s:12:\"orders_count\";i:10;s:5:\"state\";s:7:\"invited\";s:11:\"total_spent\";s:4:\"0.00\";s:13:\"last_order_id\";i:532134264855;s:4:\"note\";s:31:\"1111zzzzddddaaaaaaGGGnnasdsdfsd\";s:14:\"verified_email\";b:1;s:20:\"multipass_identifier\";N;s:10:\"tax_exempt\";b:1;s:5:\"phone\";s:12:\"+16128128561\";s:4:\"tags\";s:3:\"ddd\";s:15:\"last_order_name\";s:5:\"#1051\";s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/Customer/127274778647\";s:15:\"default_address\";a:17:{s:2:\"id\";i:722790678551;s:11:\"customer_id\";i:127274778647;s:10:\"first_name\";s:12:\"sdfsdflskdjf\";s:9:\"last_name\";s:12:\"lkjsdklfsjdf\";s:7:\"company\";N;s:8:\"address1\";s:20:\"123 West Lake Street\";s:8:\"address2\";s:0:\"\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55408\";s:5:\"phone\";N;s:4:\"name\";s:25:\"sdfsdflskdjf lkjsdklfsjdf\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}}'),
	(96451133463, 0, 'arobbins@simpleblend.net', NULL, '2017-09-29 18:43:54', '2017-09-29 18:43:55', 2, NULL, '02af47834602b5ec2338edf1ead2d342', NULL, 0.00, 0.00, 2540, '0.00', 0, 'USD', 'paid', 1, '7.29', 0.00, NULL, 0, '#1002', 'http://wpstest.dev/products/enormous-plastic-table/', '/cart/1102351400983:7?access_token=9596a847f3f4669fa8f4335a13386bd0&_fd=0&_ga=2.173569635.179326096.1506704288-127972611.1506704288', NULL, NULL, 0.00, '0a5668416f14e5cadb56c27ecce3e6f6', NULL, NULL, NULL, NULL, NULL, '2017-09-29 18:43:54', NULL, NULL, 'en', 88312, NULL, NULL, 1002, 'a:1:{i:0;a:3:{s:4:\"code\";s:4:\"FREE\";s:6:\"amount\";s:4:\"7.29\";s:4:\"type\";s:8:\"shipping\";}}', 'a:0:{}', 'a:0:{}', 'free', 174585249815, 'web', NULL, 'a:0:{}', '', 'arobbins@simpleblend.net', 'https://wpslitetest10.myshopify.com/24007681/orders/02af47834602b5ec2338edf1ead2d342/authenticate?key=618ead9dbd156180887b3166fb9b0ac0', 'a:1:{i:0;a:26:{s:2:\"id\";i:58174144535;s:10:\"variant_id\";i:1102351400983;s:5:\"title\";s:24:\"Enormous Plastic Table11\";s:8:\"quantity\";i:7;s:5:\"price\";s:4:\"0.00\";s:3:\"sku\";s:28:\"enormous-plastic-table-small\";s:13:\"variant_title\";s:5:\"Small\";s:6:\"vendor\";s:14:\"Sanford-Barton\";s:19:\"fulfillment_service\";s:6:\"manual\";s:10:\"product_id\";N;s:17:\"requires_shipping\";b:1;s:7:\"taxable\";b:1;s:9:\"gift_card\";b:0;s:4:\"name\";s:32:\"Enormous Plastic Table11 - Small\";s:28:\"variant_inventory_management\";N;s:10:\"properties\";a:0:{}s:14:\"product_exists\";b:0;s:20:\"fulfillable_quantity\";i:7;s:5:\"grams\";i:362;s:14:\"total_discount\";s:4:\"0.00\";s:18:\"fulfillment_status\";N;s:20:\"discount_allocations\";a:0:{}s:20:\"admin_graphql_api_id\";s:34:\"gid://shopify/LineItem/58174144535\";s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.004;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}s:15:\"origin_location\";a:8:{s:2:\"id\";i:28088926231;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:13:\"wpslitetest10\";s:8:\"address1\";s:11:\"123 fsdfsdj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55408\";}s:20:\"destination_location\";a:8:{s:2:\"id\";i:30342840343;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:14:\"Andrew Robbins\";s:8:\"address1\";s:12:\"614 N 1st St\";s:8:\"address2\";s:3:\"606\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";}}}', 'a:1:{i:0;a:12:{s:2:\"id\";i:72341520407;s:5:\"title\";s:13:\"Priority Mail\";s:5:\"price\";s:4:\"7.29\";s:4:\"code\";s:8:\"Priority\";s:6:\"source\";s:4:\"usps\";s:5:\"phone\";N;s:32:\"requested_fulfillment_service_id\";N;s:17:\"delivery_category\";N;s:18:\"carrier_identifier\";N;s:16:\"discounted_price\";s:4:\"7.29\";s:20:\"discount_allocations\";a:1:{i:0;a:2:{s:6:\"amount\";s:4:\"7.29\";s:26:\"discount_application_index\";i:0;}}s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.004;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}}}', 'a:15:{s:10:\"first_name\";s:6:\"Andrew\";s:8:\"address1\";s:12:\"614 N 1st St\";s:5:\"phone\";N;s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:7:\"Robbins\";s:8:\"address2\";s:3:\"606\";s:7:\"company\";N;s:8:\"latitude\";d:44.989168;s:9:\"longitude\";d:-93.2738601;s:4:\"name\";s:14:\"Andrew Robbins\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:15:{s:10:\"first_name\";s:6:\"Andrew\";s:8:\"address1\";s:12:\"614 N 1st St\";s:5:\"phone\";N;s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:7:\"Robbins\";s:8:\"address2\";s:3:\"606\";s:7:\"company\";N;s:8:\"latitude\";d:44.989168;s:9:\"longitude\";d:-93.2738601;s:4:\"name\";s:14:\"Andrew Robbins\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:0:{}', 'a:6:{s:10:\"browser_ip\";s:13:\"73.37.184.141\";s:15:\"accept_language\";s:32:\"en-US,en;q=0.8,nb;q=0.6,la;q=0.4\";s:10:\"user_agent\";s:121:\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36\";s:12:\"session_hash\";N;s:13:\"browser_width\";i:1144;s:14:\"browser_height\";i:949;}', 'a:0:{}', 'a:20:{s:2:\"id\";i:127274778647;s:5:\"email\";s:31:\"zzzzzzzarobbins@simpleblend.net\";s:17:\"accepts_marketing\";b:0;s:10:\"created_at\";s:25:\"2017-09-29T13:37:59-05:00\";s:10:\"updated_at\";s:25:\"2018-07-21T13:51:35-05:00\";s:10:\"first_name\";s:9:\"Andrewrrr\";s:9:\"last_name\";s:13:\"Robbinszzzzzz\";s:12:\"orders_count\";i:10;s:5:\"state\";s:7:\"invited\";s:11:\"total_spent\";s:4:\"0.00\";s:13:\"last_order_id\";i:532134264855;s:4:\"note\";s:31:\"1111zzzzddddaaaaaaGGGnnasdsdfsd\";s:14:\"verified_email\";b:1;s:20:\"multipass_identifier\";N;s:10:\"tax_exempt\";b:1;s:5:\"phone\";s:12:\"+16128128561\";s:4:\"tags\";s:3:\"ddd\";s:15:\"last_order_name\";s:5:\"#1051\";s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/Customer/127274778647\";s:15:\"default_address\";a:17:{s:2:\"id\";i:722790678551;s:11:\"customer_id\";i:127274778647;s:10:\"first_name\";s:12:\"sdfsdflskdjf\";s:9:\"last_name\";s:12:\"lkjsdklfsjdf\";s:7:\"company\";N;s:8:\"address1\";s:20:\"123 West Lake Street\";s:8:\"address2\";s:0:\"\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55408\";s:5:\"phone\";N;s:4:\"name\";s:25:\"sdfsdflskdjf lkjsdklfsjdf\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}}'),
	(96491536407, 0, 'arobbins@simpleblend.net', NULL, '2017-09-29 19:16:37', '2017-09-29 19:16:38', 3, NULL, '3dee1bc106ab7f977adba247114050d8', NULL, 0.00, 0.00, 2903, '0.00', 0, 'USD', 'paid', 1, '7.60', 0.00, NULL, 0, '#1003', 'http://wpstest.dev/products/enormous-plastic-table/', '/cart/1102351400983:8?access_token=9596a847f3f4669fa8f4335a13386bd0&_fd=0&_ga=2.138507635.179326096.1506704288-127972611.1506704288', NULL, NULL, 0.00, 'b7df8a6f3d348f4f1014f5f0f6ecb4cc', NULL, NULL, NULL, NULL, NULL, '2017-09-29 19:16:37', NULL, NULL, 'en', 88312, NULL, NULL, 1003, 'a:1:{i:0;a:3:{s:4:\"code\";s:4:\"FREE\";s:6:\"amount\";s:4:\"7.60\";s:4:\"type\";s:8:\"shipping\";}}', 'a:0:{}', 'a:0:{}', 'free', 174632337431, 'web', NULL, 'a:0:{}', '', 'arobbins@simpleblend.net', 'https://wpslitetest10.myshopify.com/24007681/orders/3dee1bc106ab7f977adba247114050d8/authenticate?key=ef140ae7648d08189ff92d8a896ebbc9', 'a:1:{i:0;a:26:{s:2:\"id\";i:58305282071;s:10:\"variant_id\";i:1102351400983;s:5:\"title\";s:24:\"Enormous Plastic Table11\";s:8:\"quantity\";i:8;s:5:\"price\";s:4:\"0.00\";s:3:\"sku\";s:28:\"enormous-plastic-table-small\";s:13:\"variant_title\";s:5:\"Small\";s:6:\"vendor\";s:14:\"Sanford-Barton\";s:19:\"fulfillment_service\";s:6:\"manual\";s:10:\"product_id\";N;s:17:\"requires_shipping\";b:1;s:7:\"taxable\";b:1;s:9:\"gift_card\";b:0;s:4:\"name\";s:32:\"Enormous Plastic Table11 - Small\";s:28:\"variant_inventory_management\";N;s:10:\"properties\";a:0:{}s:14:\"product_exists\";b:0;s:20:\"fulfillable_quantity\";i:8;s:5:\"grams\";i:362;s:14:\"total_discount\";s:4:\"0.00\";s:18:\"fulfillment_status\";N;s:20:\"discount_allocations\";a:0:{}s:20:\"admin_graphql_api_id\";s:34:\"gid://shopify/LineItem/58305282071\";s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.004;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}s:15:\"origin_location\";a:8:{s:2:\"id\";i:28088926231;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:13:\"wpslitetest10\";s:8:\"address1\";s:11:\"123 fsdfsdj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55408\";}s:20:\"destination_location\";a:8:{s:2:\"id\";i:30374461463;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:14:\"Andrew Robbins\";s:8:\"address1\";s:12:\"615 N 1st St\";s:8:\"address2\";s:3:\"606\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";}}}', 'a:1:{i:0;a:12:{s:2:\"id\";i:72357249047;s:5:\"title\";s:13:\"Priority Mail\";s:5:\"price\";s:4:\"7.60\";s:4:\"code\";s:8:\"Priority\";s:6:\"source\";s:4:\"usps\";s:5:\"phone\";N;s:32:\"requested_fulfillment_service_id\";N;s:17:\"delivery_category\";N;s:18:\"carrier_identifier\";N;s:16:\"discounted_price\";s:4:\"7.60\";s:20:\"discount_allocations\";a:1:{i:0;a:2:{s:6:\"amount\";s:4:\"7.60\";s:26:\"discount_application_index\";i:0;}}s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.004;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}}}', 'a:15:{s:10:\"first_name\";s:6:\"Andrew\";s:8:\"address1\";s:12:\"615 N 1st St\";s:5:\"phone\";N;s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:7:\"Robbins\";s:8:\"address2\";s:3:\"606\";s:7:\"company\";N;s:8:\"latitude\";d:44.988382;s:9:\"longitude\";d:-93.274079;s:4:\"name\";s:14:\"Andrew Robbins\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:15:{s:10:\"first_name\";s:6:\"Andrew\";s:8:\"address1\";s:12:\"615 N 1st St\";s:5:\"phone\";N;s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:7:\"Robbins\";s:8:\"address2\";s:3:\"606\";s:7:\"company\";N;s:8:\"latitude\";d:44.988382;s:9:\"longitude\";d:-93.274079;s:4:\"name\";s:14:\"Andrew Robbins\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:0:{}', 'a:6:{s:10:\"browser_ip\";s:13:\"73.37.184.141\";s:15:\"accept_language\";s:32:\"en-US,en;q=0.8,nb;q=0.6,la;q=0.4\";s:10:\"user_agent\";s:121:\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36\";s:12:\"session_hash\";N;s:13:\"browser_width\";i:1144;s:14:\"browser_height\";i:949;}', 'a:0:{}', 'a:20:{s:2:\"id\";i:127274778647;s:5:\"email\";s:31:\"zzzzzzzarobbins@simpleblend.net\";s:17:\"accepts_marketing\";b:0;s:10:\"created_at\";s:25:\"2017-09-29T13:37:59-05:00\";s:10:\"updated_at\";s:25:\"2018-07-21T13:51:35-05:00\";s:10:\"first_name\";s:9:\"Andrewrrr\";s:9:\"last_name\";s:13:\"Robbinszzzzzz\";s:12:\"orders_count\";i:10;s:5:\"state\";s:7:\"invited\";s:11:\"total_spent\";s:4:\"0.00\";s:13:\"last_order_id\";i:532134264855;s:4:\"note\";s:31:\"1111zzzzddddaaaaaaGGGnnasdsdfsd\";s:14:\"verified_email\";b:1;s:20:\"multipass_identifier\";N;s:10:\"tax_exempt\";b:1;s:5:\"phone\";s:12:\"+16128128561\";s:4:\"tags\";s:3:\"ddd\";s:15:\"last_order_name\";s:5:\"#1051\";s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/Customer/127274778647\";s:15:\"default_address\";a:17:{s:2:\"id\";i:722790678551;s:11:\"customer_id\";i:127274778647;s:10:\"first_name\";s:12:\"sdfsdflskdjf\";s:9:\"last_name\";s:12:\"lkjsdklfsjdf\";s:7:\"company\";N;s:8:\"address1\";s:20:\"123 West Lake Street\";s:8:\"address2\";s:0:\"\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55408\";s:5:\"phone\";N;s:4:\"name\";s:25:\"sdfsdflskdjf lkjsdklfsjdf\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}}'),
	(96506118167, 0, 'arobbins@simpleblend.net', NULL, '2017-09-29 19:35:17', '2017-09-29 19:35:18', 4, NULL, 'adc5e65dff133850e9b9eea2dff7ed26', NULL, 0.00, 0.00, 4028, '0.00', 0, 'USD', 'paid', 1, '8.82', 0.00, NULL, 0, '#1004', 'http://wpstest.dev/products/enormous-plastic-table/', '/cart/1102351400983:9,1102351335447:1?access_token=9596a847f3f4669fa8f4335a13386bd0&_fd=0&_ga=2.264419919.179326096.1506704288-127972611.1506704288', NULL, NULL, 0.00, 'bbe44dff980c5e7c56e0150850cf9ec9', NULL, NULL, NULL, NULL, NULL, '2017-09-29 19:35:17', NULL, NULL, 'en', 88312, NULL, NULL, 1004, 'a:1:{i:0;a:3:{s:4:\"code\";s:4:\"FREE\";s:6:\"amount\";s:4:\"8.82\";s:4:\"type\";s:8:\"shipping\";}}', 'a:0:{}', 'a:0:{}', 'free', 174657765399, 'web', NULL, 'a:0:{}', '', 'arobbins@simpleblend.net', 'https://wpslitetest10.myshopify.com/24007681/orders/adc5e65dff133850e9b9eea2dff7ed26/authenticate?key=d98a3e3f6337a976d2b5a9c2d507a3f7', 'a:2:{i:0;a:26:{s:2:\"id\";i:58331398167;s:10:\"variant_id\";i:1102351400983;s:5:\"title\";s:24:\"Enormous Plastic Table11\";s:8:\"quantity\";i:9;s:5:\"price\";s:4:\"0.00\";s:3:\"sku\";s:28:\"enormous-plastic-table-small\";s:13:\"variant_title\";s:5:\"Small\";s:6:\"vendor\";s:14:\"Sanford-Barton\";s:19:\"fulfillment_service\";s:6:\"manual\";s:10:\"product_id\";N;s:17:\"requires_shipping\";b:1;s:7:\"taxable\";b:1;s:9:\"gift_card\";b:0;s:4:\"name\";s:32:\"Enormous Plastic Table11 - Small\";s:28:\"variant_inventory_management\";N;s:10:\"properties\";a:0:{}s:14:\"product_exists\";b:0;s:20:\"fulfillable_quantity\";i:9;s:5:\"grams\";i:362;s:14:\"total_discount\";s:4:\"0.00\";s:18:\"fulfillment_status\";N;s:20:\"discount_allocations\";a:0:{}s:20:\"admin_graphql_api_id\";s:34:\"gid://shopify/LineItem/58331398167\";s:9:\"tax_lines\";a:2:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:18:\"Wabasha County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}s:15:\"origin_location\";a:8:{s:2:\"id\";i:28088926231;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:13:\"wpslitetest10\";s:8:\"address1\";s:11:\"123 fsdfsdj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55408\";}s:20:\"destination_location\";a:8:{s:2:\"id\";i:30390845463;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:14:\"Andrew Robbins\";s:8:\"address1\";s:12:\"614 N 1st St\";s:8:\"address2\";s:3:\"606\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55041\";}}i:1;a:26:{s:2:\"id\";i:58331430935;s:10:\"variant_id\";i:1102351335447;s:5:\"title\";s:24:\"Enormous Plastic Table11\";s:8:\"quantity\";i:1;s:5:\"price\";s:4:\"0.00\";s:3:\"sku\";s:0:\"\";s:13:\"variant_title\";s:11:\"Extra Small\";s:6:\"vendor\";s:14:\"Sanford-Barton\";s:19:\"fulfillment_service\";s:6:\"manual\";s:10:\"product_id\";N;s:17:\"requires_shipping\";b:1;s:7:\"taxable\";b:1;s:9:\"gift_card\";b:0;s:4:\"name\";s:38:\"Enormous Plastic Table11 - Extra Small\";s:28:\"variant_inventory_management\";N;s:10:\"properties\";a:0:{}s:14:\"product_exists\";b:0;s:20:\"fulfillable_quantity\";i:1;s:5:\"grams\";i:764;s:14:\"total_discount\";s:4:\"0.00\";s:18:\"fulfillment_status\";N;s:20:\"discount_allocations\";a:0:{}s:20:\"admin_graphql_api_id\";s:34:\"gid://shopify/LineItem/58331430935\";s:9:\"tax_lines\";a:2:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:18:\"Wabasha County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}s:15:\"origin_location\";a:8:{s:2:\"id\";i:28088926231;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:13:\"wpslitetest10\";s:8:\"address1\";s:11:\"123 fsdfsdj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55408\";}s:20:\"destination_location\";a:8:{s:2:\"id\";i:30390845463;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:14:\"Andrew Robbins\";s:8:\"address1\";s:12:\"614 N 1st St\";s:8:\"address2\";s:3:\"606\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55041\";}}}', 'a:1:{i:0;a:12:{s:2:\"id\";i:72367472663;s:5:\"title\";s:13:\"Priority Mail\";s:5:\"price\";s:4:\"8.82\";s:4:\"code\";s:8:\"Priority\";s:6:\"source\";s:4:\"usps\";s:5:\"phone\";N;s:32:\"requested_fulfillment_service_id\";N;s:17:\"delivery_category\";N;s:18:\"carrier_identifier\";N;s:16:\"discounted_price\";s:4:\"8.82\";s:20:\"discount_allocations\";a:1:{i:0;a:2:{s:6:\"amount\";s:4:\"8.82\";s:26:\"discount_application_index\";i:0;}}s:9:\"tax_lines\";a:2:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:18:\"Wabasha County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}}}', 'a:15:{s:10:\"first_name\";s:6:\"Andrew\";s:8:\"address1\";s:12:\"614 N 1st St\";s:5:\"phone\";N;s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55041\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:7:\"Robbins\";s:8:\"address2\";s:3:\"606\";s:7:\"company\";N;s:8:\"latitude\";N;s:9:\"longitude\";N;s:4:\"name\";s:14:\"Andrew Robbins\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:15:{s:10:\"first_name\";s:6:\"Andrew\";s:8:\"address1\";s:12:\"614 N 1st St\";s:5:\"phone\";N;s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55041\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:7:\"Robbins\";s:8:\"address2\";s:3:\"606\";s:7:\"company\";N;s:8:\"latitude\";N;s:9:\"longitude\";N;s:4:\"name\";s:14:\"Andrew Robbins\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:0:{}', 'a:6:{s:10:\"browser_ip\";s:13:\"73.37.184.141\";s:15:\"accept_language\";s:32:\"en-US,en;q=0.8,nb;q=0.6,la;q=0.4\";s:10:\"user_agent\";s:121:\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36\";s:12:\"session_hash\";N;s:13:\"browser_width\";i:1144;s:14:\"browser_height\";i:949;}', 'a:0:{}', 'a:20:{s:2:\"id\";i:127274778647;s:5:\"email\";s:31:\"zzzzzzzarobbins@simpleblend.net\";s:17:\"accepts_marketing\";b:0;s:10:\"created_at\";s:25:\"2017-09-29T13:37:59-05:00\";s:10:\"updated_at\";s:25:\"2018-07-21T13:51:35-05:00\";s:10:\"first_name\";s:9:\"Andrewrrr\";s:9:\"last_name\";s:13:\"Robbinszzzzzz\";s:12:\"orders_count\";i:10;s:5:\"state\";s:7:\"invited\";s:11:\"total_spent\";s:4:\"0.00\";s:13:\"last_order_id\";i:532134264855;s:4:\"note\";s:31:\"1111zzzzddddaaaaaaGGGnnasdsdfsd\";s:14:\"verified_email\";b:1;s:20:\"multipass_identifier\";N;s:10:\"tax_exempt\";b:1;s:5:\"phone\";s:12:\"+16128128561\";s:4:\"tags\";s:3:\"ddd\";s:15:\"last_order_name\";s:5:\"#1051\";s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/Customer/127274778647\";s:15:\"default_address\";a:17:{s:2:\"id\";i:722790678551;s:11:\"customer_id\";i:127274778647;s:10:\"first_name\";s:12:\"sdfsdflskdjf\";s:9:\"last_name\";s:12:\"lkjsdklfsjdf\";s:7:\"company\";N;s:8:\"address1\";s:20:\"123 West Lake Street\";s:8:\"address2\";s:0:\"\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55408\";s:5:\"phone\";N;s:4:\"name\";s:25:\"sdfsdflskdjf lkjsdklfsjdf\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}}'),
	(96524894231, 0, 'arobbins@simpleblend.net', NULL, '2017-09-29 20:01:15', '2017-09-29 20:01:15', 5, NULL, '387db95a0ab7f9ff1df23f69030b256b', NULL, 0.00, 0.00, 4791, '0.00', 0, 'USD', 'paid', 1, '9.99', 0.00, NULL, 0, '#1005', 'http://wpstest.dev/products/enormous-plastic-table/', '/cart/1102351400983:9,1102351335447:2?access_token=9596a847f3f4669fa8f4335a13386bd0&_fd=0&_ga=2.103520576.179326096.1506704288-127972611.1506704288', NULL, NULL, 0.00, '0ae07398f7191173dfc25db760a4e59b', NULL, NULL, NULL, NULL, NULL, '2017-09-29 20:01:15', NULL, NULL, 'en', 88312, NULL, NULL, 1005, 'a:1:{i:0;a:3:{s:4:\"code\";s:4:\"FREE\";s:6:\"amount\";s:4:\"9.99\";s:4:\"type\";s:8:\"shipping\";}}', 'a:0:{}', 'a:0:{}', 'free', 174691647511, 'web', NULL, 'a:0:{}', '', 'arobbins@simpleblend.net', 'https://wpslitetest10.myshopify.com/24007681/orders/387db95a0ab7f9ff1df23f69030b256b/authenticate?key=9166b668c0fadb63c00320f0781c9af5', 'a:2:{i:0;a:26:{s:2:\"id\";i:58367508503;s:10:\"variant_id\";i:1102351400983;s:5:\"title\";s:24:\"Enormous Plastic Table11\";s:8:\"quantity\";i:9;s:5:\"price\";s:4:\"0.00\";s:3:\"sku\";s:28:\"enormous-plastic-table-small\";s:13:\"variant_title\";s:5:\"Small\";s:6:\"vendor\";s:14:\"Sanford-Barton\";s:19:\"fulfillment_service\";s:6:\"manual\";s:10:\"product_id\";N;s:17:\"requires_shipping\";b:1;s:7:\"taxable\";b:1;s:9:\"gift_card\";b:0;s:4:\"name\";s:32:\"Enormous Plastic Table11 - Small\";s:28:\"variant_inventory_management\";N;s:10:\"properties\";a:0:{}s:14:\"product_exists\";b:0;s:20:\"fulfillable_quantity\";i:9;s:5:\"grams\";i:362;s:14:\"total_discount\";s:4:\"0.00\";s:18:\"fulfillment_status\";N;s:20:\"discount_allocations\";a:0:{}s:20:\"admin_graphql_api_id\";s:34:\"gid://shopify/LineItem/58367508503\";s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.004;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}s:15:\"origin_location\";a:8:{s:2:\"id\";i:28088926231;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:13:\"wpslitetest10\";s:8:\"address1\";s:11:\"123 fsdfsdj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55408\";}s:20:\"destination_location\";a:8:{s:2:\"id\";i:30342840343;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:14:\"Andrew Robbins\";s:8:\"address1\";s:12:\"614 N 1st St\";s:8:\"address2\";s:3:\"606\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";}}i:1;a:26:{s:2:\"id\";i:58367541271;s:10:\"variant_id\";i:1102351335447;s:5:\"title\";s:24:\"Enormous Plastic Table11\";s:8:\"quantity\";i:2;s:5:\"price\";s:4:\"0.00\";s:3:\"sku\";s:0:\"\";s:13:\"variant_title\";s:11:\"Extra Small\";s:6:\"vendor\";s:14:\"Sanford-Barton\";s:19:\"fulfillment_service\";s:6:\"manual\";s:10:\"product_id\";N;s:17:\"requires_shipping\";b:1;s:7:\"taxable\";b:1;s:9:\"gift_card\";b:0;s:4:\"name\";s:38:\"Enormous Plastic Table11 - Extra Small\";s:28:\"variant_inventory_management\";N;s:10:\"properties\";a:0:{}s:14:\"product_exists\";b:0;s:20:\"fulfillable_quantity\";i:2;s:5:\"grams\";i:764;s:14:\"total_discount\";s:4:\"0.00\";s:18:\"fulfillment_status\";N;s:20:\"discount_allocations\";a:0:{}s:20:\"admin_graphql_api_id\";s:34:\"gid://shopify/LineItem/58367541271\";s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.004;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}s:15:\"origin_location\";a:8:{s:2:\"id\";i:28088926231;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:13:\"wpslitetest10\";s:8:\"address1\";s:11:\"123 fsdfsdj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55408\";}s:20:\"destination_location\";a:8:{s:2:\"id\";i:30342840343;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:14:\"Andrew Robbins\";s:8:\"address1\";s:12:\"614 N 1st St\";s:8:\"address2\";s:3:\"606\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";}}}', 'a:1:{i:0;a:12:{s:2:\"id\";i:72380678167;s:5:\"title\";s:13:\"Priority Mail\";s:5:\"price\";s:4:\"9.99\";s:4:\"code\";s:8:\"Priority\";s:6:\"source\";s:4:\"usps\";s:5:\"phone\";N;s:32:\"requested_fulfillment_service_id\";N;s:17:\"delivery_category\";N;s:18:\"carrier_identifier\";N;s:16:\"discounted_price\";s:4:\"9.99\";s:20:\"discount_allocations\";a:1:{i:0;a:2:{s:6:\"amount\";s:4:\"9.99\";s:26:\"discount_application_index\";i:0;}}s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.004;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}}}', 'a:15:{s:10:\"first_name\";s:6:\"Andrew\";s:8:\"address1\";s:12:\"614 N 1st St\";s:5:\"phone\";N;s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:7:\"Robbins\";s:8:\"address2\";s:3:\"606\";s:7:\"company\";N;s:8:\"latitude\";d:44.989168;s:9:\"longitude\";d:-93.2738601;s:4:\"name\";s:14:\"Andrew Robbins\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:15:{s:10:\"first_name\";s:6:\"Andrew\";s:8:\"address1\";s:12:\"614 N 1st St\";s:5:\"phone\";N;s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:7:\"Robbins\";s:8:\"address2\";s:3:\"606\";s:7:\"company\";N;s:8:\"latitude\";d:44.989168;s:9:\"longitude\";d:-93.2738601;s:4:\"name\";s:14:\"Andrew Robbins\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:0:{}', 'a:6:{s:10:\"browser_ip\";s:13:\"73.37.184.141\";s:15:\"accept_language\";s:32:\"en-US,en;q=0.8,nb;q=0.6,la;q=0.4\";s:10:\"user_agent\";s:121:\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36\";s:12:\"session_hash\";N;s:13:\"browser_width\";i:1144;s:14:\"browser_height\";i:949;}', 'a:0:{}', 'a:20:{s:2:\"id\";i:127274778647;s:5:\"email\";s:31:\"zzzzzzzarobbins@simpleblend.net\";s:17:\"accepts_marketing\";b:0;s:10:\"created_at\";s:25:\"2017-09-29T13:37:59-05:00\";s:10:\"updated_at\";s:25:\"2018-07-21T13:51:35-05:00\";s:10:\"first_name\";s:9:\"Andrewrrr\";s:9:\"last_name\";s:13:\"Robbinszzzzzz\";s:12:\"orders_count\";i:10;s:5:\"state\";s:7:\"invited\";s:11:\"total_spent\";s:4:\"0.00\";s:13:\"last_order_id\";i:532134264855;s:4:\"note\";s:31:\"1111zzzzddddaaaaaaGGGnnasdsdfsd\";s:14:\"verified_email\";b:1;s:20:\"multipass_identifier\";N;s:10:\"tax_exempt\";b:1;s:5:\"phone\";s:12:\"+16128128561\";s:4:\"tags\";s:3:\"ddd\";s:15:\"last_order_name\";s:5:\"#1051\";s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/Customer/127274778647\";s:15:\"default_address\";a:17:{s:2:\"id\";i:722790678551;s:11:\"customer_id\";i:127274778647;s:10:\"first_name\";s:12:\"sdfsdflskdjf\";s:9:\"last_name\";s:12:\"lkjsdklfsjdf\";s:7:\"company\";N;s:8:\"address1\";s:20:\"123 West Lake Street\";s:8:\"address2\";s:0:\"\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55408\";s:5:\"phone\";N;s:4:\"name\";s:25:\"sdfsdflskdjf lkjsdklfsjdf\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}}'),
	(96535904279, 0, 'arobbins@simpleblend.net', NULL, '2017-09-29 20:15:49', '2017-09-29 20:15:50', 6, NULL, '9b66acf3d8a3f382aa6ea68357868a33', NULL, 0.00, 0.00, 5593, '0.00', 0, 'USD', 'paid', 1, '11.00', 0.00, NULL, 0, '#1006', 'http://wpstest.dev/products/enormous-plastic-table/', '/cart/1102351400983:9,1102351335447:2,1102351499287:1?access_token=9596a847f3f4669fa8f4335a13386bd0&_fd=0&_ga=2.125885773.179326096.1506704288-127972611.1506704288', NULL, NULL, 0.00, 'f3f4ffcb66f5032c4fcffcc26701a0b9', NULL, NULL, NULL, NULL, NULL, '2017-09-29 20:15:49', NULL, NULL, 'en', 88312, NULL, NULL, 1006, 'a:1:{i:0;a:3:{s:4:\"code\";s:4:\"FREE\";s:6:\"amount\";s:5:\"11.00\";s:4:\"type\";s:8:\"shipping\";}}', 'a:0:{}', 'a:0:{}', 'free', 174714912791, 'web', NULL, 'a:0:{}', '', 'arobbins@simpleblend.net', 'https://wpslitetest10.myshopify.com/24007681/orders/9b66acf3d8a3f382aa6ea68357868a33/authenticate?key=851995da51bc7d5a7db061b86a6e9d0f', 'a:3:{i:0;a:26:{s:2:\"id\";i:58385694743;s:10:\"variant_id\";i:1102351400983;s:5:\"title\";s:24:\"Enormous Plastic Table11\";s:8:\"quantity\";i:9;s:5:\"price\";s:4:\"0.00\";s:3:\"sku\";s:28:\"enormous-plastic-table-small\";s:13:\"variant_title\";s:5:\"Small\";s:6:\"vendor\";s:14:\"Sanford-Barton\";s:19:\"fulfillment_service\";s:6:\"manual\";s:10:\"product_id\";N;s:17:\"requires_shipping\";b:1;s:7:\"taxable\";b:1;s:9:\"gift_card\";b:0;s:4:\"name\";s:32:\"Enormous Plastic Table11 - Small\";s:28:\"variant_inventory_management\";N;s:10:\"properties\";a:0:{}s:14:\"product_exists\";b:0;s:20:\"fulfillable_quantity\";i:9;s:5:\"grams\";i:362;s:14:\"total_discount\";s:4:\"0.00\";s:18:\"fulfillment_status\";N;s:20:\"discount_allocations\";a:0:{}s:20:\"admin_graphql_api_id\";s:34:\"gid://shopify/LineItem/58385694743\";s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.004;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}s:15:\"origin_location\";a:8:{s:2:\"id\";i:28088926231;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:13:\"wpslitetest10\";s:8:\"address1\";s:11:\"123 fsdfsdj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55408\";}s:20:\"destination_location\";a:8:{s:2:\"id\";i:30342840343;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:14:\"Andrew Robbins\";s:8:\"address1\";s:12:\"614 N 1st St\";s:8:\"address2\";s:3:\"606\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";}}i:1;a:26:{s:2:\"id\";i:58385727511;s:10:\"variant_id\";i:1102351335447;s:5:\"title\";s:24:\"Enormous Plastic Table11\";s:8:\"quantity\";i:2;s:5:\"price\";s:4:\"0.00\";s:3:\"sku\";s:0:\"\";s:13:\"variant_title\";s:11:\"Extra Small\";s:6:\"vendor\";s:14:\"Sanford-Barton\";s:19:\"fulfillment_service\";s:6:\"manual\";s:10:\"product_id\";N;s:17:\"requires_shipping\";b:1;s:7:\"taxable\";b:1;s:9:\"gift_card\";b:0;s:4:\"name\";s:38:\"Enormous Plastic Table11 - Extra Small\";s:28:\"variant_inventory_management\";N;s:10:\"properties\";a:0:{}s:14:\"product_exists\";b:0;s:20:\"fulfillable_quantity\";i:2;s:5:\"grams\";i:764;s:14:\"total_discount\";s:4:\"0.00\";s:18:\"fulfillment_status\";N;s:20:\"discount_allocations\";a:0:{}s:20:\"admin_graphql_api_id\";s:34:\"gid://shopify/LineItem/58385727511\";s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.004;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}s:15:\"origin_location\";a:8:{s:2:\"id\";i:28088926231;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:13:\"wpslitetest10\";s:8:\"address1\";s:11:\"123 fsdfsdj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55408\";}s:20:\"destination_location\";a:8:{s:2:\"id\";i:30342840343;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:14:\"Andrew Robbins\";s:8:\"address1\";s:12:\"614 N 1st St\";s:8:\"address2\";s:3:\"606\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";}}i:2;a:26:{s:2:\"id\";i:58385760279;s:10:\"variant_id\";i:1102351499287;s:5:\"title\";s:24:\"Enormous Plastic Table11\";s:8:\"quantity\";i:1;s:5:\"price\";s:4:\"0.00\";s:3:\"sku\";s:29:\"enormous-plastic-table-medium\";s:13:\"variant_title\";s:6:\"Medium\";s:6:\"vendor\";s:14:\"Sanford-Barton\";s:19:\"fulfillment_service\";s:6:\"manual\";s:10:\"product_id\";N;s:17:\"requires_shipping\";b:1;s:7:\"taxable\";b:1;s:9:\"gift_card\";b:0;s:4:\"name\";s:33:\"Enormous Plastic Table11 - Medium\";s:28:\"variant_inventory_management\";N;s:10:\"properties\";a:0:{}s:14:\"product_exists\";b:0;s:20:\"fulfillable_quantity\";i:1;s:5:\"grams\";i:801;s:14:\"total_discount\";s:4:\"0.00\";s:18:\"fulfillment_status\";N;s:20:\"discount_allocations\";a:0:{}s:20:\"admin_graphql_api_id\";s:34:\"gid://shopify/LineItem/58385760279\";s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.004;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}s:15:\"origin_location\";a:8:{s:2:\"id\";i:28088926231;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:13:\"wpslitetest10\";s:8:\"address1\";s:11:\"123 fsdfsdj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55408\";}s:20:\"destination_location\";a:8:{s:2:\"id\";i:30342840343;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:14:\"Andrew Robbins\";s:8:\"address1\";s:12:\"614 N 1st St\";s:8:\"address2\";s:3:\"606\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";}}}', 'a:1:{i:0;a:12:{s:2:\"id\";i:72388214807;s:5:\"title\";s:13:\"Priority Mail\";s:5:\"price\";s:5:\"11.00\";s:4:\"code\";s:8:\"Priority\";s:6:\"source\";s:4:\"usps\";s:5:\"phone\";N;s:32:\"requested_fulfillment_service_id\";N;s:17:\"delivery_category\";N;s:18:\"carrier_identifier\";N;s:16:\"discounted_price\";s:5:\"11.00\";s:20:\"discount_allocations\";a:1:{i:0;a:2:{s:6:\"amount\";s:5:\"11.00\";s:26:\"discount_application_index\";i:0;}}s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.004;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}}}', 'a:15:{s:10:\"first_name\";s:6:\"Andrew\";s:8:\"address1\";s:12:\"614 N 1st St\";s:5:\"phone\";N;s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:7:\"Robbins\";s:8:\"address2\";s:3:\"606\";s:7:\"company\";N;s:8:\"latitude\";d:44.989168;s:9:\"longitude\";d:-93.2738601;s:4:\"name\";s:14:\"Andrew Robbins\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:15:{s:10:\"first_name\";s:6:\"Andrew\";s:8:\"address1\";s:12:\"614 N 1st St\";s:5:\"phone\";N;s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:7:\"Robbins\";s:8:\"address2\";s:3:\"606\";s:7:\"company\";N;s:8:\"latitude\";d:44.989168;s:9:\"longitude\";d:-93.2738601;s:4:\"name\";s:14:\"Andrew Robbins\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:0:{}', 'a:6:{s:10:\"browser_ip\";s:13:\"73.37.184.141\";s:15:\"accept_language\";s:32:\"en-US,en;q=0.8,nb;q=0.6,la;q=0.4\";s:10:\"user_agent\";s:121:\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36\";s:12:\"session_hash\";N;s:13:\"browser_width\";i:1144;s:14:\"browser_height\";i:949;}', 'a:0:{}', 'a:20:{s:2:\"id\";i:127274778647;s:5:\"email\";s:31:\"zzzzzzzarobbins@simpleblend.net\";s:17:\"accepts_marketing\";b:0;s:10:\"created_at\";s:25:\"2017-09-29T13:37:59-05:00\";s:10:\"updated_at\";s:25:\"2018-07-21T13:51:35-05:00\";s:10:\"first_name\";s:9:\"Andrewrrr\";s:9:\"last_name\";s:13:\"Robbinszzzzzz\";s:12:\"orders_count\";i:10;s:5:\"state\";s:7:\"invited\";s:11:\"total_spent\";s:4:\"0.00\";s:13:\"last_order_id\";i:532134264855;s:4:\"note\";s:31:\"1111zzzzddddaaaaaaGGGnnasdsdfsd\";s:14:\"verified_email\";b:1;s:20:\"multipass_identifier\";N;s:10:\"tax_exempt\";b:1;s:5:\"phone\";s:12:\"+16128128561\";s:4:\"tags\";s:3:\"ddd\";s:15:\"last_order_name\";s:5:\"#1051\";s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/Customer/127274778647\";s:15:\"default_address\";a:17:{s:2:\"id\";i:722790678551;s:11:\"customer_id\";i:127274778647;s:10:\"first_name\";s:12:\"sdfsdflskdjf\";s:9:\"last_name\";s:12:\"lkjsdklfsjdf\";s:7:\"company\";N;s:8:\"address1\";s:20:\"123 West Lake Street\";s:8:\"address2\";s:0:\"\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55408\";s:5:\"phone\";N;s:4:\"name\";s:25:\"sdfsdflskdjf lkjsdklfsjdf\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}}'),
	(96571981847, 0, 'arobbins@simpleblend.net', NULL, '2017-09-29 21:05:10', '2017-09-29 21:05:10', 7, NULL, '2a7c4c960fe9d373311885cfb9aa5ae5', NULL, 0.00, 0.00, 763, '0.00', 0, 'USD', 'paid', 1, '6.41', 0.00, NULL, 0, '#1007', 'http://wpstest.dev/products/enormous-plastic-table/', '/cart/1102351335447:1?access_token=9596a847f3f4669fa8f4335a13386bd0&_fd=0&_ga=2.164795903.179326096.1506704288-127972611.1506704288', NULL, NULL, 0.00, '41fb351922a044e07c644567bde7872b', NULL, NULL, NULL, NULL, NULL, '2017-09-29 21:05:10', NULL, NULL, 'en', 88312, NULL, NULL, 1007, 'a:1:{i:0;a:3:{s:4:\"code\";s:4:\"FREE\";s:6:\"amount\";s:4:\"6.41\";s:4:\"type\";s:8:\"shipping\";}}', 'a:0:{}', 'a:0:{}', 'free', 174787756055, 'web', NULL, 'a:0:{}', '', 'arobbins@simpleblend.net', 'https://wpslitetest10.myshopify.com/24007681/orders/2a7c4c960fe9d373311885cfb9aa5ae5/authenticate?key=ce061c413e9820e193428eeb00ea7fed', 'a:1:{i:0;a:26:{s:2:\"id\";i:58449690647;s:10:\"variant_id\";i:1102351335447;s:5:\"title\";s:24:\"Enormous Plastic Table11\";s:8:\"quantity\";i:1;s:5:\"price\";s:4:\"0.00\";s:3:\"sku\";s:0:\"\";s:13:\"variant_title\";s:11:\"Extra Small\";s:6:\"vendor\";s:14:\"Sanford-Barton\";s:19:\"fulfillment_service\";s:6:\"manual\";s:10:\"product_id\";N;s:17:\"requires_shipping\";b:1;s:7:\"taxable\";b:1;s:9:\"gift_card\";b:0;s:4:\"name\";s:38:\"Enormous Plastic Table11 - Extra Small\";s:28:\"variant_inventory_management\";N;s:10:\"properties\";a:0:{}s:14:\"product_exists\";b:0;s:20:\"fulfillable_quantity\";i:1;s:5:\"grams\";i:764;s:14:\"total_discount\";s:4:\"0.00\";s:18:\"fulfillment_status\";N;s:20:\"discount_allocations\";a:0:{}s:20:\"admin_graphql_api_id\";s:34:\"gid://shopify/LineItem/58449690647\";s:9:\"tax_lines\";a:2:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:18:\"Wabasha County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}s:15:\"origin_location\";a:8:{s:2:\"id\";i:28088926231;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:13:\"wpslitetest10\";s:8:\"address1\";s:11:\"123 fsdfsdj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55408\";}s:20:\"destination_location\";a:8:{s:2:\"id\";i:30460542999;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:14:\"Andrew Robbins\";s:8:\"address1\";s:10:\"123 sdfjsd\";s:8:\"address2\";s:3:\"101\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55041\";}}}', 'a:1:{i:0;a:12:{s:2:\"id\";i:72412954647;s:5:\"title\";s:13:\"Priority Mail\";s:5:\"price\";s:4:\"6.41\";s:4:\"code\";s:8:\"Priority\";s:6:\"source\";s:4:\"usps\";s:5:\"phone\";N;s:32:\"requested_fulfillment_service_id\";N;s:17:\"delivery_category\";N;s:18:\"carrier_identifier\";N;s:16:\"discounted_price\";s:4:\"6.41\";s:20:\"discount_allocations\";a:1:{i:0;a:2:{s:6:\"amount\";s:4:\"6.41\";s:26:\"discount_application_index\";i:0;}}s:9:\"tax_lines\";a:2:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:18:\"Wabasha County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}}}', 'a:15:{s:10:\"first_name\";s:6:\"Andrew\";s:8:\"address1\";s:10:\"123 sdfjsd\";s:5:\"phone\";N;s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55041\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:7:\"Robbins\";s:8:\"address2\";s:3:\"101\";s:7:\"company\";N;s:8:\"latitude\";N;s:9:\"longitude\";N;s:4:\"name\";s:14:\"Andrew Robbins\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:15:{s:10:\"first_name\";s:6:\"Andrew\";s:8:\"address1\";s:10:\"123 sdfjsd\";s:5:\"phone\";N;s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55041\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:7:\"Robbins\";s:8:\"address2\";s:3:\"101\";s:7:\"company\";N;s:8:\"latitude\";N;s:9:\"longitude\";N;s:4:\"name\";s:14:\"Andrew Robbins\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:0:{}', 'a:6:{s:10:\"browser_ip\";s:13:\"73.37.184.141\";s:15:\"accept_language\";s:32:\"en-US,en;q=0.8,nb;q=0.6,la;q=0.4\";s:10:\"user_agent\";s:121:\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36\";s:12:\"session_hash\";s:32:\"d7b9e1c53dd3242e80b9b0f726d5c253\";s:13:\"browser_width\";i:1145;s:14:\"browser_height\";i:949;}', 'a:0:{}', 'a:20:{s:2:\"id\";i:127274778647;s:5:\"email\";s:31:\"zzzzzzzarobbins@simpleblend.net\";s:17:\"accepts_marketing\";b:0;s:10:\"created_at\";s:25:\"2017-09-29T13:37:59-05:00\";s:10:\"updated_at\";s:25:\"2018-07-21T13:51:35-05:00\";s:10:\"first_name\";s:9:\"Andrewrrr\";s:9:\"last_name\";s:13:\"Robbinszzzzzz\";s:12:\"orders_count\";i:10;s:5:\"state\";s:7:\"invited\";s:11:\"total_spent\";s:4:\"0.00\";s:13:\"last_order_id\";i:532134264855;s:4:\"note\";s:31:\"1111zzzzddddaaaaaaGGGnnasdsdfsd\";s:14:\"verified_email\";b:1;s:20:\"multipass_identifier\";N;s:10:\"tax_exempt\";b:1;s:5:\"phone\";s:12:\"+16128128561\";s:4:\"tags\";s:3:\"ddd\";s:15:\"last_order_name\";s:5:\"#1051\";s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/Customer/127274778647\";s:15:\"default_address\";a:17:{s:2:\"id\";i:722790678551;s:11:\"customer_id\";i:127274778647;s:10:\"first_name\";s:12:\"sdfsdflskdjf\";s:9:\"last_name\";s:12:\"lkjsdklfsjdf\";s:7:\"company\";N;s:8:\"address1\";s:20:\"123 West Lake Street\";s:8:\"address2\";s:0:\"\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55408\";s:5:\"phone\";N;s:4:\"name\";s:25:\"sdfsdflskdjf lkjsdklfsjdf\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}}'),
	(96578469911, 0, 'arobbins@simpleblend.net', NULL, '2017-09-29 21:12:39', '2017-09-29 21:12:40', 8, NULL, '46cbeea34f2e11397c9729a33d05f494', NULL, 0.00, 0.00, 1491, '0.00', 0, 'USD', 'paid', 1, '6.62', 0.00, NULL, 0, '#1008', 'http://wpstest.dev/products/sleek-paper-pants/', '/cart/1102351335447:1,1102367981591:1?access_token=9596a847f3f4669fa8f4335a13386bd0&_fd=0&_ga=2.264361295.179326096.1506704288-127972611.1506704288', NULL, NULL, 0.00, '9c239f3be98e56b0d7c81104c0116b58', NULL, NULL, NULL, NULL, NULL, '2017-09-29 21:12:39', NULL, NULL, 'en', 88312, NULL, NULL, 1008, 'a:1:{i:0;a:3:{s:4:\"code\";s:4:\"FREE\";s:6:\"amount\";s:4:\"6.62\";s:4:\"type\";s:8:\"shipping\";}}', 'a:0:{}', 'a:0:{}', 'free', 174798962711, 'web', NULL, 'a:0:{}', '', 'arobbins@simpleblend.net', 'https://wpslitetest10.myshopify.com/24007681/orders/46cbeea34f2e11397c9729a33d05f494/authenticate?key=f5791ea47b316884e92a96db0e59280b', 'a:2:{i:0;a:26:{s:2:\"id\";i:58459815959;s:10:\"variant_id\";i:1102351335447;s:5:\"title\";s:24:\"Enormous Plastic Table11\";s:8:\"quantity\";i:1;s:5:\"price\";s:4:\"0.00\";s:3:\"sku\";s:0:\"\";s:13:\"variant_title\";s:11:\"Extra Small\";s:6:\"vendor\";s:14:\"Sanford-Barton\";s:19:\"fulfillment_service\";s:6:\"manual\";s:10:\"product_id\";N;s:17:\"requires_shipping\";b:1;s:7:\"taxable\";b:1;s:9:\"gift_card\";b:0;s:4:\"name\";s:38:\"Enormous Plastic Table11 - Extra Small\";s:28:\"variant_inventory_management\";N;s:10:\"properties\";a:0:{}s:14:\"product_exists\";b:0;s:20:\"fulfillable_quantity\";i:1;s:5:\"grams\";i:764;s:14:\"total_discount\";s:4:\"0.00\";s:18:\"fulfillment_status\";N;s:20:\"discount_allocations\";a:0:{}s:20:\"admin_graphql_api_id\";s:34:\"gid://shopify/LineItem/58459815959\";s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.004;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}s:15:\"origin_location\";a:8:{s:2:\"id\";i:28088926231;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:13:\"wpslitetest10\";s:8:\"address1\";s:11:\"123 fsdfsdj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55408\";}s:20:\"destination_location\";a:8:{s:2:\"id\";i:30467325975;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:14:\"Andrew Robbins\";s:8:\"address1\";s:12:\"614 N 1st St\";s:8:\"address2\";s:3:\"101\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";}}i:1;a:26:{s:2:\"id\";i:58459848727;s:10:\"variant_id\";i:1102367981591;s:5:\"title\";s:17:\"Sleek Paper Pants\";s:8:\"quantity\";i:1;s:5:\"price\";s:4:\"0.00\";s:3:\"sku\";s:24:\"sleek-paper-pants-medium\";s:13:\"variant_title\";s:6:\"Medium\";s:6:\"vendor\";s:12:\"Luettgen Inc\";s:19:\"fulfillment_service\";s:6:\"manual\";s:10:\"product_id\";N;s:17:\"requires_shipping\";b:1;s:7:\"taxable\";b:1;s:9:\"gift_card\";b:0;s:4:\"name\";s:26:\"Sleek Paper Pants - Medium\";s:28:\"variant_inventory_management\";N;s:10:\"properties\";a:0:{}s:14:\"product_exists\";b:0;s:20:\"fulfillable_quantity\";i:1;s:5:\"grams\";i:729;s:14:\"total_discount\";s:4:\"0.00\";s:18:\"fulfillment_status\";N;s:20:\"discount_allocations\";a:0:{}s:20:\"admin_graphql_api_id\";s:34:\"gid://shopify/LineItem/58459848727\";s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.004;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}s:15:\"origin_location\";a:8:{s:2:\"id\";i:28088926231;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:13:\"wpslitetest10\";s:8:\"address1\";s:11:\"123 fsdfsdj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55408\";}s:20:\"destination_location\";a:8:{s:2:\"id\";i:30467325975;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:14:\"Andrew Robbins\";s:8:\"address1\";s:12:\"614 N 1st St\";s:8:\"address2\";s:3:\"101\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";}}}', 'a:1:{i:0;a:12:{s:2:\"id\";i:72417640471;s:5:\"title\";s:13:\"Priority Mail\";s:5:\"price\";s:4:\"6.62\";s:4:\"code\";s:8:\"Priority\";s:6:\"source\";s:4:\"usps\";s:5:\"phone\";N;s:32:\"requested_fulfillment_service_id\";N;s:17:\"delivery_category\";N;s:18:\"carrier_identifier\";N;s:16:\"discounted_price\";s:4:\"6.62\";s:20:\"discount_allocations\";a:1:{i:0;a:2:{s:6:\"amount\";s:4:\"6.62\";s:26:\"discount_application_index\";i:0;}}s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.004;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}}}', 'a:15:{s:10:\"first_name\";s:6:\"Andrew\";s:8:\"address1\";s:12:\"614 N 1st St\";s:5:\"phone\";N;s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:7:\"Robbins\";s:8:\"address2\";s:3:\"101\";s:7:\"company\";N;s:8:\"latitude\";d:44.989168;s:9:\"longitude\";d:-93.2738601;s:4:\"name\";s:14:\"Andrew Robbins\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:15:{s:10:\"first_name\";s:6:\"Andrew\";s:8:\"address1\";s:12:\"614 N 1st St\";s:5:\"phone\";N;s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:7:\"Robbins\";s:8:\"address2\";s:3:\"101\";s:7:\"company\";N;s:8:\"latitude\";d:44.989168;s:9:\"longitude\";d:-93.2738601;s:4:\"name\";s:14:\"Andrew Robbins\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:0:{}', 'a:6:{s:10:\"browser_ip\";s:13:\"73.37.184.141\";s:15:\"accept_language\";s:32:\"en-US,en;q=0.8,nb;q=0.6,la;q=0.4\";s:10:\"user_agent\";s:121:\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36\";s:12:\"session_hash\";s:32:\"d7b9e1c53dd3242e80b9b0f726d5c253\";s:13:\"browser_width\";i:1145;s:14:\"browser_height\";i:949;}', 'a:0:{}', 'a:20:{s:2:\"id\";i:127274778647;s:5:\"email\";s:31:\"zzzzzzzarobbins@simpleblend.net\";s:17:\"accepts_marketing\";b:0;s:10:\"created_at\";s:25:\"2017-09-29T13:37:59-05:00\";s:10:\"updated_at\";s:25:\"2018-07-21T13:51:35-05:00\";s:10:\"first_name\";s:9:\"Andrewrrr\";s:9:\"last_name\";s:13:\"Robbinszzzzzz\";s:12:\"orders_count\";i:10;s:5:\"state\";s:7:\"invited\";s:11:\"total_spent\";s:4:\"0.00\";s:13:\"last_order_id\";i:532134264855;s:4:\"note\";s:31:\"1111zzzzddddaaaaaaGGGnnasdsdfsd\";s:14:\"verified_email\";b:1;s:20:\"multipass_identifier\";N;s:10:\"tax_exempt\";b:1;s:5:\"phone\";s:12:\"+16128128561\";s:4:\"tags\";s:3:\"ddd\";s:15:\"last_order_name\";s:5:\"#1051\";s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/Customer/127274778647\";s:15:\"default_address\";a:17:{s:2:\"id\";i:722790678551;s:11:\"customer_id\";i:127274778647;s:10:\"first_name\";s:12:\"sdfsdflskdjf\";s:9:\"last_name\";s:12:\"lkjsdklfsjdf\";s:7:\"company\";N;s:8:\"address1\";s:20:\"123 West Lake Street\";s:8:\"address2\";s:0:\"\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55408\";s:5:\"phone\";N;s:4:\"name\";s:25:\"sdfsdflskdjf lkjsdklfsjdf\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}}'),
	(121727713303, 0, 'andrew@simpleblend.net', '2017-11-03 20:38:44', '2017-10-28 01:39:51', '2017-11-03 20:38:44', 9, NULL, '32233e441fa11dcd9d5a4ec61d2c808c', NULL, 0.00, 0.00, 856, '0.00', 0, 'USD', 'paid', 1, '6.41', 0.00, NULL, 0, '#1009', 'http://wpstest.dev/products/aerodynamic-bronze-hat/', '/cart/1102357463063:1?access_token=9596a847f3f4669fa8f4335a13386bd0&_fd=0&_ga=2.101955905.1979840902.1509136033-844054230.1509136033', NULL, NULL, 0.00, '37231f859ec67725da7544351f17efc2', NULL, NULL, NULL, NULL, NULL, '2017-10-28 01:39:51', NULL, NULL, 'en', 88312, NULL, NULL, 1009, 'a:1:{i:0;a:3:{s:4:\"code\";s:4:\"FREE\";s:6:\"amount\";s:4:\"6.41\";s:4:\"type\";s:8:\"shipping\";}}', 'a:0:{}', 'a:0:{}', 'free', 230372081687, 'web', NULL, 'a:0:{}', '', 'andrew@simpleblend.net', 'https://wpslitetest10.myshopify.com/24007681/orders/32233e441fa11dcd9d5a4ec61d2c808c/authenticate?key=8108e22098ddf958327a975fcfc9aab3', 'a:1:{i:0;a:26:{s:2:\"id\";i:103410466839;s:10:\"variant_id\";i:1102357463063;s:5:\"title\";s:26:\"Aerodynamic Bronze Hattttt\";s:8:\"quantity\";i:1;s:5:\"price\";s:4:\"0.00\";s:3:\"sku\";s:28:\"aerodynamic-bronze-hat-small\";s:13:\"variant_title\";s:5:\"Small\";s:6:\"vendor\";s:13:\"Beatty-Bednar\";s:19:\"fulfillment_service\";s:6:\"manual\";s:10:\"product_id\";N;s:17:\"requires_shipping\";b:1;s:7:\"taxable\";b:1;s:9:\"gift_card\";b:0;s:4:\"name\";s:34:\"Aerodynamic Bronze Hattttt - Small\";s:28:\"variant_inventory_management\";N;s:10:\"properties\";a:0:{}s:14:\"product_exists\";b:0;s:20:\"fulfillable_quantity\";i:1;s:5:\"grams\";i:857;s:14:\"total_discount\";s:4:\"0.00\";s:18:\"fulfillment_status\";N;s:20:\"discount_allocations\";a:0:{}s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/LineItem/103410466839\";s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.0065;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}s:15:\"origin_location\";a:8:{s:2:\"id\";i:28088926231;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:13:\"wpslitetest10\";s:8:\"address1\";s:11:\"123 fsdfsdj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55408\";}s:20:\"destination_location\";a:8:{s:2:\"id\";i:57305464855;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:21:\"sdjfhskdjh sdjfhskdfh\";s:8:\"address1\";s:9:\"123 sdfsd\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"minneapolis\";s:3:\"zip\";s:5:\"55401\";}}}', 'a:1:{i:0;a:12:{s:2:\"id\";i:91451031575;s:5:\"title\";s:13:\"Priority Mail\";s:5:\"price\";s:4:\"6.41\";s:4:\"code\";s:8:\"Priority\";s:6:\"source\";s:4:\"usps\";s:5:\"phone\";N;s:32:\"requested_fulfillment_service_id\";N;s:17:\"delivery_category\";N;s:18:\"carrier_identifier\";N;s:16:\"discounted_price\";s:4:\"6.41\";s:20:\"discount_allocations\";a:1:{i:0;a:2:{s:6:\"amount\";s:4:\"6.41\";s:26:\"discount_application_index\";i:0;}}s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.0065;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}}}', 'a:15:{s:10:\"first_name\";s:10:\"sdjfhskdjh\";s:8:\"address1\";s:9:\"123 sdfsd\";s:5:\"phone\";N;s:4:\"city\";s:11:\"minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:10:\"sdjfhskdfh\";s:8:\"address2\";s:3:\"123\";s:7:\"company\";N;s:8:\"latitude\";d:44.9836543;s:9:\"longitude\";d:-93.2693572;s:4:\"name\";s:21:\"sdjfhskdjh sdjfhskdfh\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:15:{s:10:\"first_name\";s:10:\"sdjfhskdjh\";s:8:\"address1\";s:9:\"123 sdfsd\";s:5:\"phone\";N;s:4:\"city\";s:11:\"minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:10:\"sdjfhskdfh\";s:8:\"address2\";s:3:\"123\";s:7:\"company\";N;s:8:\"latitude\";d:44.9836543;s:9:\"longitude\";d:-93.2693572;s:4:\"name\";s:21:\"sdjfhskdjh sdjfhskdfh\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:0:{}', 'a:6:{s:10:\"browser_ip\";s:11:\"68.54.24.70\";s:15:\"accept_language\";s:32:\"en-US,en;q=0.8,nb;q=0.6,la;q=0.4\";s:10:\"user_agent\";s:121:\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36\";s:12:\"session_hash\";N;s:13:\"browser_width\";i:1680;s:14:\"browser_height\";i:949;}', 'a:0:{}', 'a:20:{s:2:\"id\";i:186994655255;s:5:\"email\";s:22:\"andrew@simpleblend.net\";s:17:\"accepts_marketing\";b:0;s:10:\"created_at\";s:25:\"2017-10-27T20:39:33-05:00\";s:10:\"updated_at\";s:25:\"2018-08-13T15:43:58-05:00\";s:10:\"first_name\";s:6:\"Andrew\";s:9:\"last_name\";s:7:\"Robbins\";s:12:\"orders_count\";i:10;s:5:\"state\";s:8:\"disabled\";s:11:\"total_spent\";s:4:\"0.00\";s:13:\"last_order_id\";i:585463726103;s:4:\"note\";s:1:\"?\";s:14:\"verified_email\";b:1;s:20:\"multipass_identifier\";N;s:10:\"tax_exempt\";b:0;s:5:\"phone\";N;s:4:\"tags\";s:3:\"ddd\";s:15:\"last_order_name\";s:5:\"#1061\";s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/Customer/186994655255\";s:15:\"default_address\";a:17:{s:2:\"id\";i:783822815255;s:11:\"customer_id\";i:186994655255;s:10:\"first_name\";s:6:\"Andrew\";s:9:\"last_name\";s:7:\"Robbins\";s:7:\"company\";N;s:8:\"address1\";s:21:\"1221 West Lake Street\";s:8:\"address2\";s:0:\"\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55408\";s:5:\"phone\";N;s:4:\"name\";s:14:\"Andrew Robbins\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}}'),
	(121735446551, 0, 'andrew@simpleblend.net', NULL, '2017-10-28 01:55:16', '2017-11-03 20:37:30', 10, NULL, '3a3a1a7b27f940b1c406644805b1e158', NULL, 0.00, 0.00, 856, '0.00', 0, 'USD', 'paid', 1, '6.41', 0.00, NULL, 0, '#1010', 'http://wpstest.dev/products/aerodynamic-bronze-hat/', '/cart/1102357463063:1?access_token=9596a847f3f4669fa8f4335a13386bd0&_fd=0&_ga=2.140038387.1979840902.1509136033-844054230.1509136033', '2017-11-03 20:37:30', 'customer', 0.00, 'ecfb759907662a740302c70c166bd75a', NULL, NULL, NULL, NULL, NULL, '2017-10-28 01:55:16', NULL, NULL, 'en', 88312, NULL, NULL, 1010, 'a:1:{i:0;a:3:{s:4:\"code\";s:4:\"FREE\";s:6:\"amount\";s:4:\"6.41\";s:4:\"type\";s:8:\"shipping\";}}', 'a:0:{}', 'a:0:{}', 'free', 230389252119, 'web', NULL, 'a:0:{}', '', 'andrew@simpleblend.net', 'https://wpslitetest10.myshopify.com/24007681/orders/3a3a1a7b27f940b1c406644805b1e158/authenticate?key=ae142a7a3074a2a9bdde540fa70c87f3', 'a:1:{i:0;a:26:{s:2:\"id\";i:103423967255;s:10:\"variant_id\";i:1102357463063;s:5:\"title\";s:26:\"Aerodynamic Bronze Hattttt\";s:8:\"quantity\";i:1;s:5:\"price\";s:4:\"0.00\";s:3:\"sku\";s:28:\"aerodynamic-bronze-hat-small\";s:13:\"variant_title\";s:5:\"Small\";s:6:\"vendor\";s:13:\"Beatty-Bednar\";s:19:\"fulfillment_service\";s:6:\"manual\";s:10:\"product_id\";N;s:17:\"requires_shipping\";b:1;s:7:\"taxable\";b:1;s:9:\"gift_card\";b:0;s:4:\"name\";s:34:\"Aerodynamic Bronze Hattttt - Small\";s:28:\"variant_inventory_management\";N;s:10:\"properties\";a:0:{}s:14:\"product_exists\";b:0;s:20:\"fulfillable_quantity\";i:0;s:5:\"grams\";i:857;s:14:\"total_discount\";s:4:\"0.00\";s:18:\"fulfillment_status\";N;s:20:\"discount_allocations\";a:0:{}s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/LineItem/103423967255\";s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.0065;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}s:15:\"origin_location\";a:8:{s:2:\"id\";i:28088926231;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:13:\"wpslitetest10\";s:8:\"address1\";s:11:\"123 fsdfsdj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55408\";}s:20:\"destination_location\";a:8:{s:2:\"id\";i:57315033111;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:10:\"jhjkh jkhj\";s:8:\"address1\";s:11:\"khkjhkjhsdf\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"minneapolis\";s:3:\"zip\";s:5:\"55401\";}}}', 'a:1:{i:0;a:12:{s:2:\"id\";i:91457257495;s:5:\"title\";s:13:\"Priority Mail\";s:5:\"price\";s:4:\"6.41\";s:4:\"code\";s:8:\"Priority\";s:6:\"source\";s:4:\"usps\";s:5:\"phone\";N;s:32:\"requested_fulfillment_service_id\";N;s:17:\"delivery_category\";N;s:18:\"carrier_identifier\";N;s:16:\"discounted_price\";s:4:\"6.41\";s:20:\"discount_allocations\";a:1:{i:0;a:2:{s:6:\"amount\";s:4:\"6.41\";s:26:\"discount_application_index\";i:0;}}s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.0065;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}}}', 'a:15:{s:10:\"first_name\";s:5:\"jhjkh\";s:8:\"address1\";s:11:\"khkjhkjhsdf\";s:5:\"phone\";N;s:4:\"city\";s:11:\"minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:4:\"jkhj\";s:8:\"address2\";s:3:\"123\";s:7:\"company\";N;s:8:\"latitude\";d:44.9836543;s:9:\"longitude\";d:-93.2693572;s:4:\"name\";s:10:\"jhjkh jkhj\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:15:{s:10:\"first_name\";s:5:\"jhjkh\";s:8:\"address1\";s:11:\"khkjhkjhsdf\";s:5:\"phone\";N;s:4:\"city\";s:11:\"minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:4:\"jkhj\";s:8:\"address2\";s:3:\"123\";s:7:\"company\";N;s:8:\"latitude\";d:44.9836543;s:9:\"longitude\";d:-93.2693572;s:4:\"name\";s:10:\"jhjkh jkhj\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:0:{}', 'a:6:{s:10:\"browser_ip\";s:11:\"68.54.24.70\";s:15:\"accept_language\";s:32:\"en-US,en;q=0.8,nb;q=0.6,la;q=0.4\";s:10:\"user_agent\";s:121:\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36\";s:12:\"session_hash\";s:32:\"7e5dfbb25d3bddc0239ce96d375e6bf1\";s:13:\"browser_width\";i:1680;s:14:\"browser_height\";i:949;}', 'a:1:{i:0;a:11:{s:2:\"id\";i:5430312983;s:8:\"order_id\";i:121735446551;s:10:\"created_at\";s:25:\"2017-11-03T15:37:30-05:00\";s:4:\"note\";N;s:7:\"user_id\";i:2632482839;s:12:\"processed_at\";s:25:\"2017-11-03T15:37:30-05:00\";s:7:\"restock\";b:0;s:20:\"admin_graphql_api_id\";s:31:\"gid://shopify/Refund/5430312983\";s:17:\"refund_line_items\";a:1:{i:0;a:8:{s:2:\"id\";i:6433210391;s:8:\"quantity\";i:1;s:12:\"line_item_id\";i:103423967255;s:11:\"location_id\";N;s:12:\"restock_type\";s:10:\"no_restock\";s:8:\"subtotal\";i:0;s:9:\"total_tax\";i:0;s:9:\"line_item\";a:26:{s:2:\"id\";i:103423967255;s:10:\"variant_id\";i:1102357463063;s:5:\"title\";s:26:\"Aerodynamic Bronze Hattttt\";s:8:\"quantity\";i:1;s:5:\"price\";s:4:\"0.00\";s:3:\"sku\";s:28:\"aerodynamic-bronze-hat-small\";s:13:\"variant_title\";s:5:\"Small\";s:6:\"vendor\";s:13:\"Beatty-Bednar\";s:19:\"fulfillment_service\";s:6:\"manual\";s:10:\"product_id\";N;s:17:\"requires_shipping\";b:1;s:7:\"taxable\";b:1;s:9:\"gift_card\";b:0;s:4:\"name\";s:34:\"Aerodynamic Bronze Hattttt - Small\";s:28:\"variant_inventory_management\";N;s:10:\"properties\";a:0:{}s:14:\"product_exists\";b:0;s:20:\"fulfillable_quantity\";i:0;s:5:\"grams\";i:857;s:14:\"total_discount\";s:4:\"0.00\";s:18:\"fulfillment_status\";N;s:20:\"discount_allocations\";a:0:{}s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/LineItem/103423967255\";s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.0065;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}s:15:\"origin_location\";a:8:{s:2:\"id\";i:28088926231;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:13:\"wpslitetest10\";s:8:\"address1\";s:11:\"123 fsdfsdj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55408\";}s:20:\"destination_location\";a:8:{s:2:\"id\";i:57315033111;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:10:\"jhjkh jkhj\";s:8:\"address1\";s:11:\"khkjhkjhsdf\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"minneapolis\";s:3:\"zip\";s:5:\"55401\";}}}}s:12:\"transactions\";a:0:{}s:17:\"order_adjustments\";a:0:{}}}', 'a:20:{s:2:\"id\";i:186994655255;s:5:\"email\";s:22:\"andrew@simpleblend.net\";s:17:\"accepts_marketing\";b:0;s:10:\"created_at\";s:25:\"2017-10-27T20:39:33-05:00\";s:10:\"updated_at\";s:25:\"2018-08-13T15:43:58-05:00\";s:10:\"first_name\";s:6:\"Andrew\";s:9:\"last_name\";s:7:\"Robbins\";s:12:\"orders_count\";i:10;s:5:\"state\";s:8:\"disabled\";s:11:\"total_spent\";s:4:\"0.00\";s:13:\"last_order_id\";i:585463726103;s:4:\"note\";s:1:\"?\";s:14:\"verified_email\";b:1;s:20:\"multipass_identifier\";N;s:10:\"tax_exempt\";b:0;s:5:\"phone\";N;s:4:\"tags\";s:3:\"ddd\";s:15:\"last_order_name\";s:5:\"#1061\";s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/Customer/186994655255\";s:15:\"default_address\";a:17:{s:2:\"id\";i:783822815255;s:11:\"customer_id\";i:186994655255;s:10:\"first_name\";s:6:\"Andrew\";s:9:\"last_name\";s:7:\"Robbins\";s:7:\"company\";N;s:8:\"address1\";s:21:\"1221 West Lake Street\";s:8:\"address2\";s:0:\"\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55408\";s:5:\"phone\";N;s:4:\"name\";s:14:\"Andrew Robbins\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}}'),
	(122137870359, 0, 'andrew@simpleblend.net', NULL, '2017-10-28 17:55:26', '2017-10-28 17:55:26', 11, NULL, '7abd68e615c14dcc9970a09387f2fae2', NULL, 0.00, 0.00, 1074, '0.00', 0, 'USD', 'paid', 1, '6.41', 0.00, NULL, 0, '#1011', 'http://wpstest.dev/products/aerodynamic-bronze-hat/', '/cart/1102357463063:1,1102357528599:1?access_token=9596a847f3f4669fa8f4335a13386bd0&_fd=0&_ga=2.138043376.1333689629.1509213067-1065699217.1509213067', NULL, NULL, 0.00, '32284537e1fd3f1d039ff9f49a436a89', NULL, NULL, NULL, NULL, NULL, '2017-10-28 17:55:26', NULL, NULL, 'en', 88312, NULL, NULL, 1011, 'a:1:{i:0;a:3:{s:4:\"code\";s:4:\"FREE\";s:6:\"amount\";s:4:\"6.41\";s:4:\"type\";s:8:\"shipping\";}}', 'a:0:{}', 'a:0:{}', 'free', 231504969751, 'web', NULL, 'a:0:{}', '', 'andrew@simpleblend.net', 'https://wpslitetest10.myshopify.com/24007681/orders/7abd68e615c14dcc9970a09387f2fae2/authenticate?key=1f6638e6be766c384852ca124910e860', 'a:2:{i:0;a:26:{s:2:\"id\";i:104051343383;s:10:\"variant_id\";i:1102357463063;s:5:\"title\";s:26:\"Aerodynamic Bronze Hattttt\";s:8:\"quantity\";i:1;s:5:\"price\";s:4:\"0.00\";s:3:\"sku\";s:28:\"aerodynamic-bronze-hat-small\";s:13:\"variant_title\";s:5:\"Small\";s:6:\"vendor\";s:13:\"Beatty-Bednar\";s:19:\"fulfillment_service\";s:6:\"manual\";s:10:\"product_id\";N;s:17:\"requires_shipping\";b:1;s:7:\"taxable\";b:1;s:9:\"gift_card\";b:0;s:4:\"name\";s:34:\"Aerodynamic Bronze Hattttt - Small\";s:28:\"variant_inventory_management\";N;s:10:\"properties\";a:0:{}s:14:\"product_exists\";b:0;s:20:\"fulfillable_quantity\";i:1;s:5:\"grams\";i:857;s:14:\"total_discount\";s:4:\"0.00\";s:18:\"fulfillment_status\";N;s:20:\"discount_allocations\";a:0:{}s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/LineItem/104051343383\";s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.0065;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}s:15:\"origin_location\";a:8:{s:2:\"id\";i:28088926231;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:13:\"wpslitetest10\";s:8:\"address1\";s:11:\"123 fsdfsdj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55408\";}s:20:\"destination_location\";a:8:{s:2:\"id\";i:57875791895;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:29:\"slkdjfsldkj lksjflksjdflsdkjf\";s:8:\"address1\";s:22:\"123 skjfsdlkfjsd sdlfj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"minneapolis\";s:3:\"zip\";s:5:\"55401\";}}i:1;a:26:{s:2:\"id\";i:104051376151;s:10:\"variant_id\";i:1102357528599;s:5:\"title\";s:26:\"Aerodynamic Bronze Hattttt\";s:8:\"quantity\";i:1;s:5:\"price\";s:4:\"0.00\";s:3:\"sku\";s:29:\"aerodynamic-bronze-hat-medium\";s:13:\"variant_title\";s:6:\"Medium\";s:6:\"vendor\";s:13:\"Beatty-Bednar\";s:19:\"fulfillment_service\";s:6:\"manual\";s:10:\"product_id\";N;s:17:\"requires_shipping\";b:1;s:7:\"taxable\";b:1;s:9:\"gift_card\";b:0;s:4:\"name\";s:35:\"Aerodynamic Bronze Hattttt - Medium\";s:28:\"variant_inventory_management\";N;s:10:\"properties\";a:0:{}s:14:\"product_exists\";b:0;s:20:\"fulfillable_quantity\";i:1;s:5:\"grams\";i:218;s:14:\"total_discount\";s:4:\"0.00\";s:18:\"fulfillment_status\";N;s:20:\"discount_allocations\";a:0:{}s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/LineItem/104051376151\";s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.0065;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}s:15:\"origin_location\";a:8:{s:2:\"id\";i:28088926231;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:13:\"wpslitetest10\";s:8:\"address1\";s:11:\"123 fsdfsdj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55408\";}s:20:\"destination_location\";a:8:{s:2:\"id\";i:57875791895;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:29:\"slkdjfsldkj lksjflksjdflsdkjf\";s:8:\"address1\";s:22:\"123 skjfsdlkfjsd sdlfj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"minneapolis\";s:3:\"zip\";s:5:\"55401\";}}}', 'a:1:{i:0;a:12:{s:2:\"id\";i:91740176407;s:5:\"title\";s:13:\"Priority Mail\";s:5:\"price\";s:4:\"6.41\";s:4:\"code\";s:8:\"Priority\";s:6:\"source\";s:4:\"usps\";s:5:\"phone\";N;s:32:\"requested_fulfillment_service_id\";N;s:17:\"delivery_category\";N;s:18:\"carrier_identifier\";N;s:16:\"discounted_price\";s:4:\"6.41\";s:20:\"discount_allocations\";a:1:{i:0;a:2:{s:6:\"amount\";s:4:\"6.41\";s:26:\"discount_application_index\";i:0;}}s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.0065;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}}}', 'a:15:{s:10:\"first_name\";s:11:\"slkdjfsldkj\";s:8:\"address1\";s:22:\"123 skjfsdlkfjsd sdlfj\";s:5:\"phone\";N;s:4:\"city\";s:11:\"minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:17:\"lksjflksjdflsdkjf\";s:8:\"address2\";s:3:\"123\";s:7:\"company\";N;s:8:\"latitude\";d:44.9836543;s:9:\"longitude\";d:-93.2693572;s:4:\"name\";s:29:\"slkdjfsldkj lksjflksjdflsdkjf\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:15:{s:10:\"first_name\";s:11:\"slkdjfsldkj\";s:8:\"address1\";s:22:\"123 skjfsdlkfjsd sdlfj\";s:5:\"phone\";N;s:4:\"city\";s:11:\"minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:17:\"lksjflksjdflsdkjf\";s:8:\"address2\";s:3:\"123\";s:7:\"company\";N;s:8:\"latitude\";d:44.9836543;s:9:\"longitude\";d:-93.2693572;s:4:\"name\";s:29:\"slkdjfsldkj lksjflksjdflsdkjf\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:0:{}', 'a:6:{s:10:\"browser_ip\";s:14:\"24.118.197.231\";s:15:\"accept_language\";s:32:\"en-US,en;q=0.8,nb;q=0.6,la;q=0.4\";s:10:\"user_agent\";s:121:\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36\";s:12:\"session_hash\";N;s:13:\"browser_width\";i:1682;s:14:\"browser_height\";i:949;}', 'a:0:{}', 'a:20:{s:2:\"id\";i:186994655255;s:5:\"email\";s:22:\"andrew@simpleblend.net\";s:17:\"accepts_marketing\";b:0;s:10:\"created_at\";s:25:\"2017-10-27T20:39:33-05:00\";s:10:\"updated_at\";s:25:\"2018-08-13T15:43:58-05:00\";s:10:\"first_name\";s:6:\"Andrew\";s:9:\"last_name\";s:7:\"Robbins\";s:12:\"orders_count\";i:10;s:5:\"state\";s:8:\"disabled\";s:11:\"total_spent\";s:4:\"0.00\";s:13:\"last_order_id\";i:585463726103;s:4:\"note\";s:1:\"?\";s:14:\"verified_email\";b:1;s:20:\"multipass_identifier\";N;s:10:\"tax_exempt\";b:0;s:5:\"phone\";N;s:4:\"tags\";s:3:\"ddd\";s:15:\"last_order_name\";s:5:\"#1061\";s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/Customer/186994655255\";s:15:\"default_address\";a:17:{s:2:\"id\";i:783822815255;s:11:\"customer_id\";i:186994655255;s:10:\"first_name\";s:6:\"Andrew\";s:9:\"last_name\";s:7:\"Robbins\";s:7:\"company\";N;s:8:\"address1\";s:21:\"1221 West Lake Street\";s:8:\"address2\";s:0:\"\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55408\";s:5:\"phone\";N;s:4:\"name\";s:14:\"Andrew Robbins\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}}'),
	(122141310999, 0, 'andrew@simpleblend.net', NULL, '2017-10-28 17:59:20', '2017-10-28 17:59:20', 12, NULL, '45d6c9afa84fb343719196c5378e07c4', NULL, 0.00, 0.00, 408, '0.00', 0, 'USD', 'paid', 1, '6.33', 0.00, NULL, 0, '#1012', 'http://wpstest.dev/products/aerodynamic-bronze-hat/', '/cart/1102357397527:1?access_token=9596a847f3f4669fa8f4335a13386bd0&_fd=0&_ga=2.130719695.1333689629.1509213067-1065699217.1509213067', NULL, NULL, 0.00, 'fb665bad6d921fce50c55237f9d11b9e', NULL, NULL, NULL, NULL, NULL, '2017-10-28 17:59:20', NULL, NULL, 'en', 88312, NULL, NULL, 1012, 'a:1:{i:0;a:3:{s:4:\"code\";s:4:\"FREE\";s:6:\"amount\";s:4:\"6.33\";s:4:\"type\";s:8:\"shipping\";}}', 'a:0:{}', 'a:0:{}', 'free', 231513751575, 'web', NULL, 'a:0:{}', '', 'andrew@simpleblend.net', 'https://wpslitetest10.myshopify.com/24007681/orders/45d6c9afa84fb343719196c5378e07c4/authenticate?key=24f92d13a7ae4c3b543dee3f0eb4a8d4', 'a:1:{i:0;a:26:{s:2:\"id\";i:104056782871;s:10:\"variant_id\";i:1102357397527;s:5:\"title\";s:26:\"Aerodynamic Bronze Hattttt\";s:8:\"quantity\";i:1;s:5:\"price\";s:4:\"0.00\";s:3:\"sku\";s:0:\"\";s:13:\"variant_title\";s:11:\"Extra Small\";s:6:\"vendor\";s:13:\"Beatty-Bednar\";s:19:\"fulfillment_service\";s:6:\"manual\";s:10:\"product_id\";N;s:17:\"requires_shipping\";b:1;s:7:\"taxable\";b:1;s:9:\"gift_card\";b:0;s:4:\"name\";s:40:\"Aerodynamic Bronze Hattttt - Extra Small\";s:28:\"variant_inventory_management\";N;s:10:\"properties\";a:0:{}s:14:\"product_exists\";b:0;s:20:\"fulfillable_quantity\";i:1;s:5:\"grams\";i:408;s:14:\"total_discount\";s:4:\"0.00\";s:18:\"fulfillment_status\";N;s:20:\"discount_allocations\";a:0:{}s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/LineItem/104056782871\";s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.0065;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}s:15:\"origin_location\";a:8:{s:2:\"id\";i:28088926231;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:13:\"wpslitetest10\";s:8:\"address1\";s:11:\"123 fsdfsdj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55408\";}s:20:\"destination_location\";a:8:{s:2:\"id\";i:57875791895;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:29:\"slkdjfsldkj lksjflksjdflsdkjf\";s:8:\"address1\";s:22:\"123 skjfsdlkfjsd sdlfj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"minneapolis\";s:3:\"zip\";s:5:\"55401\";}}}', 'a:1:{i:0;a:12:{s:2:\"id\";i:91742044183;s:5:\"title\";s:13:\"Priority Mail\";s:5:\"price\";s:4:\"6.33\";s:4:\"code\";s:8:\"Priority\";s:6:\"source\";s:4:\"usps\";s:5:\"phone\";N;s:32:\"requested_fulfillment_service_id\";N;s:17:\"delivery_category\";N;s:18:\"carrier_identifier\";N;s:16:\"discounted_price\";s:4:\"6.33\";s:20:\"discount_allocations\";a:1:{i:0;a:2:{s:6:\"amount\";s:4:\"6.33\";s:26:\"discount_application_index\";i:0;}}s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.0065;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}}}', 'a:15:{s:10:\"first_name\";s:11:\"slkdjfsldkj\";s:8:\"address1\";s:22:\"123 skjfsdlkfjsd sdlfj\";s:5:\"phone\";N;s:4:\"city\";s:11:\"minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:17:\"lksjflksjdflsdkjf\";s:8:\"address2\";s:3:\"123\";s:7:\"company\";N;s:8:\"latitude\";d:44.9836543;s:9:\"longitude\";d:-93.2693572;s:4:\"name\";s:29:\"slkdjfsldkj lksjflksjdflsdkjf\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:15:{s:10:\"first_name\";s:11:\"slkdjfsldkj\";s:8:\"address1\";s:22:\"123 skjfsdlkfjsd sdlfj\";s:5:\"phone\";N;s:4:\"city\";s:11:\"minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:17:\"lksjflksjdflsdkjf\";s:8:\"address2\";s:3:\"123\";s:7:\"company\";N;s:8:\"latitude\";d:44.9836543;s:9:\"longitude\";d:-93.2693572;s:4:\"name\";s:29:\"slkdjfsldkj lksjflksjdflsdkjf\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:0:{}', 'a:6:{s:10:\"browser_ip\";s:13:\"199.66.91.246\";s:15:\"accept_language\";s:32:\"en-US,en;q=0.8,nb;q=0.6,la;q=0.4\";s:10:\"user_agent\";s:121:\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36\";s:12:\"session_hash\";N;s:13:\"browser_width\";i:1682;s:14:\"browser_height\";i:434;}', 'a:0:{}', 'a:20:{s:2:\"id\";i:186994655255;s:5:\"email\";s:22:\"andrew@simpleblend.net\";s:17:\"accepts_marketing\";b:0;s:10:\"created_at\";s:25:\"2017-10-27T20:39:33-05:00\";s:10:\"updated_at\";s:25:\"2018-08-13T15:43:58-05:00\";s:10:\"first_name\";s:6:\"Andrew\";s:9:\"last_name\";s:7:\"Robbins\";s:12:\"orders_count\";i:10;s:5:\"state\";s:8:\"disabled\";s:11:\"total_spent\";s:4:\"0.00\";s:13:\"last_order_id\";i:585463726103;s:4:\"note\";s:1:\"?\";s:14:\"verified_email\";b:1;s:20:\"multipass_identifier\";N;s:10:\"tax_exempt\";b:0;s:5:\"phone\";N;s:4:\"tags\";s:3:\"ddd\";s:15:\"last_order_name\";s:5:\"#1061\";s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/Customer/186994655255\";s:15:\"default_address\";a:17:{s:2:\"id\";i:783822815255;s:11:\"customer_id\";i:186994655255;s:10:\"first_name\";s:6:\"Andrew\";s:9:\"last_name\";s:7:\"Robbins\";s:7:\"company\";N;s:8:\"address1\";s:21:\"1221 West Lake Street\";s:8:\"address2\";s:0:\"\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55408\";s:5:\"phone\";N;s:4:\"name\";s:14:\"Andrew Robbins\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}}'),
	(122147700759, 0, 'andrew@simpleblend.net', NULL, '2017-10-28 18:06:49', '2017-10-28 18:06:50', 13, NULL, 'a345df34013a54636d2b10b1d6682e4b', NULL, 0.00, 0.00, 856, '0.00', 0, 'USD', 'paid', 1, '6.41', 0.00, NULL, 0, '#1013', 'http://wpstest.dev/products/aerodynamic-bronze-hat/', '/cart/1102357463063:1?access_token=9596a847f3f4669fa8f4335a13386bd0&_fd=0&_ga=2.118200521.1333689629.1509213067-1065699217.1509213067', NULL, NULL, 0.00, '1899a1a43b15adf3bf4d350c71f92962', NULL, NULL, NULL, NULL, NULL, '2017-10-28 18:06:49', NULL, NULL, 'en', 88312, NULL, NULL, 1013, 'a:1:{i:0;a:3:{s:4:\"code\";s:4:\"FREE\";s:6:\"amount\";s:4:\"6.41\";s:4:\"type\";s:8:\"shipping\";}}', 'a:0:{}', 'a:0:{}', 'free', 231529906199, 'web', NULL, 'a:0:{}', '', 'andrew@simpleblend.net', 'https://wpslitetest10.myshopify.com/24007681/orders/a345df34013a54636d2b10b1d6682e4b/authenticate?key=28f717c0f21786e9744884e12459e8cb', 'a:1:{i:0;a:26:{s:2:\"id\";i:104066449431;s:10:\"variant_id\";i:1102357463063;s:5:\"title\";s:26:\"Aerodynamic Bronze Hattttt\";s:8:\"quantity\";i:1;s:5:\"price\";s:4:\"0.00\";s:3:\"sku\";s:28:\"aerodynamic-bronze-hat-small\";s:13:\"variant_title\";s:5:\"Small\";s:6:\"vendor\";s:13:\"Beatty-Bednar\";s:19:\"fulfillment_service\";s:6:\"manual\";s:10:\"product_id\";N;s:17:\"requires_shipping\";b:1;s:7:\"taxable\";b:1;s:9:\"gift_card\";b:0;s:4:\"name\";s:34:\"Aerodynamic Bronze Hattttt - Small\";s:28:\"variant_inventory_management\";N;s:10:\"properties\";a:0:{}s:14:\"product_exists\";b:0;s:20:\"fulfillable_quantity\";i:1;s:5:\"grams\";i:857;s:14:\"total_discount\";s:4:\"0.00\";s:18:\"fulfillment_status\";N;s:20:\"discount_allocations\";a:0:{}s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/LineItem/104066449431\";s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.0065;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}s:15:\"origin_location\";a:8:{s:2:\"id\";i:28088926231;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:13:\"wpslitetest10\";s:8:\"address1\";s:11:\"123 fsdfsdj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55408\";}s:20:\"destination_location\";a:8:{s:2:\"id\";i:57875791895;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:29:\"slkdjfsldkj lksjflksjdflsdkjf\";s:8:\"address1\";s:22:\"123 skjfsdlkfjsd sdlfj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"minneapolis\";s:3:\"zip\";s:5:\"55401\";}}}', 'a:1:{i:0;a:12:{s:2:\"id\";i:91746041879;s:5:\"title\";s:13:\"Priority Mail\";s:5:\"price\";s:4:\"6.41\";s:4:\"code\";s:8:\"Priority\";s:6:\"source\";s:4:\"usps\";s:5:\"phone\";N;s:32:\"requested_fulfillment_service_id\";N;s:17:\"delivery_category\";N;s:18:\"carrier_identifier\";N;s:16:\"discounted_price\";s:4:\"6.41\";s:20:\"discount_allocations\";a:1:{i:0;a:2:{s:6:\"amount\";s:4:\"6.41\";s:26:\"discount_application_index\";i:0;}}s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.0065;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}}}', 'a:15:{s:10:\"first_name\";s:11:\"slkdjfsldkj\";s:8:\"address1\";s:22:\"123 skjfsdlkfjsd sdlfj\";s:5:\"phone\";N;s:4:\"city\";s:11:\"minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:17:\"lksjflksjdflsdkjf\";s:8:\"address2\";s:3:\"123\";s:7:\"company\";N;s:8:\"latitude\";d:44.9836543;s:9:\"longitude\";d:-93.2693572;s:4:\"name\";s:29:\"slkdjfsldkj lksjflksjdflsdkjf\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:15:{s:10:\"first_name\";s:11:\"slkdjfsldkj\";s:8:\"address1\";s:22:\"123 skjfsdlkfjsd sdlfj\";s:5:\"phone\";N;s:4:\"city\";s:11:\"minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:17:\"lksjflksjdflsdkjf\";s:8:\"address2\";s:3:\"123\";s:7:\"company\";N;s:8:\"latitude\";d:44.9836543;s:9:\"longitude\";d:-93.2693572;s:4:\"name\";s:29:\"slkdjfsldkj lksjflksjdflsdkjf\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:0:{}', 'a:6:{s:10:\"browser_ip\";s:13:\"199.66.91.246\";s:15:\"accept_language\";s:32:\"en-US,en;q=0.8,nb;q=0.6,la;q=0.4\";s:10:\"user_agent\";s:121:\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36\";s:12:\"session_hash\";N;s:13:\"browser_width\";i:1682;s:14:\"browser_height\";i:434;}', 'a:0:{}', 'a:20:{s:2:\"id\";i:186994655255;s:5:\"email\";s:22:\"andrew@simpleblend.net\";s:17:\"accepts_marketing\";b:0;s:10:\"created_at\";s:25:\"2017-10-27T20:39:33-05:00\";s:10:\"updated_at\";s:25:\"2018-08-13T15:43:58-05:00\";s:10:\"first_name\";s:6:\"Andrew\";s:9:\"last_name\";s:7:\"Robbins\";s:12:\"orders_count\";i:10;s:5:\"state\";s:8:\"disabled\";s:11:\"total_spent\";s:4:\"0.00\";s:13:\"last_order_id\";i:585463726103;s:4:\"note\";s:1:\"?\";s:14:\"verified_email\";b:1;s:20:\"multipass_identifier\";N;s:10:\"tax_exempt\";b:0;s:5:\"phone\";N;s:4:\"tags\";s:3:\"ddd\";s:15:\"last_order_name\";s:5:\"#1061\";s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/Customer/186994655255\";s:15:\"default_address\";a:17:{s:2:\"id\";i:783822815255;s:11:\"customer_id\";i:186994655255;s:10:\"first_name\";s:6:\"Andrew\";s:9:\"last_name\";s:7:\"Robbins\";s:7:\"company\";N;s:8:\"address1\";s:21:\"1221 West Lake Street\";s:8:\"address2\";s:0:\"\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55408\";s:5:\"phone\";N;s:4:\"name\";s:14:\"Andrew Robbins\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}}'),
	(122149765143, 0, 'andrew@simpleblend.net', '2017-11-03 20:36:52', '2017-10-28 18:09:06', '2017-11-03 20:36:52', 14, NULL, '5f186beef5ac6bc19facb9bf6ad8824a', NULL, 0.00, 0.00, 856, '0.00', 0, 'USD', 'paid', 1, '6.41', 0.00, NULL, 0, '#1014', 'http://wpstest.dev/products/aerodynamic-bronze-hat/', '/cart/1102357463063:1?access_token=9596a847f3f4669fa8f4335a13386bd0&_fd=0&_ga=2.37460832.1333689629.1509213067-1065699217.1509213067', NULL, NULL, 0.00, 'a39da529eccdba50e364bf7e78552a79', NULL, NULL, NULL, NULL, NULL, '2017-10-28 18:09:06', NULL, NULL, 'en', 88312, NULL, NULL, 1014, 'a:1:{i:0;a:3:{s:4:\"code\";s:4:\"FREE\";s:6:\"amount\";s:4:\"6.41\";s:4:\"type\";s:8:\"shipping\";}}', 'a:0:{}', 'a:0:{}', 'free', 231535018007, 'web', 'fulfilled', 'a:0:{}', '', 'andrew@simpleblend.net', 'https://wpslitetest10.myshopify.com/24007681/orders/5f186beef5ac6bc19facb9bf6ad8824a/authenticate?key=dd831e686b222adb7613b6ba516b498b', 'a:1:{i:0;a:26:{s:2:\"id\";i:104069857303;s:10:\"variant_id\";i:1102357463063;s:5:\"title\";s:26:\"Aerodynamic Bronze Hattttt\";s:8:\"quantity\";i:1;s:5:\"price\";s:4:\"0.00\";s:3:\"sku\";s:28:\"aerodynamic-bronze-hat-small\";s:13:\"variant_title\";s:5:\"Small\";s:6:\"vendor\";s:13:\"Beatty-Bednar\";s:19:\"fulfillment_service\";s:6:\"manual\";s:10:\"product_id\";N;s:17:\"requires_shipping\";b:1;s:7:\"taxable\";b:1;s:9:\"gift_card\";b:0;s:4:\"name\";s:34:\"Aerodynamic Bronze Hattttt - Small\";s:28:\"variant_inventory_management\";N;s:10:\"properties\";a:0:{}s:14:\"product_exists\";b:0;s:20:\"fulfillable_quantity\";i:0;s:5:\"grams\";i:857;s:14:\"total_discount\";s:4:\"0.00\";s:18:\"fulfillment_status\";s:9:\"fulfilled\";s:20:\"discount_allocations\";a:0:{}s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/LineItem/104069857303\";s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.0065;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}s:15:\"origin_location\";a:8:{s:2:\"id\";i:28088926231;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:13:\"wpslitetest10\";s:8:\"address1\";s:11:\"123 fsdfsdj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55408\";}s:20:\"destination_location\";a:8:{s:2:\"id\";i:57875791895;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:29:\"slkdjfsldkj lksjflksjdflsdkjf\";s:8:\"address1\";s:22:\"123 skjfsdlkfjsd sdlfj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"minneapolis\";s:3:\"zip\";s:5:\"55401\";}}}', 'a:1:{i:0;a:12:{s:2:\"id\";i:91747024919;s:5:\"title\";s:13:\"Priority Mail\";s:5:\"price\";s:4:\"6.41\";s:4:\"code\";s:8:\"Priority\";s:6:\"source\";s:4:\"usps\";s:5:\"phone\";N;s:32:\"requested_fulfillment_service_id\";N;s:17:\"delivery_category\";N;s:18:\"carrier_identifier\";N;s:16:\"discounted_price\";s:4:\"6.41\";s:20:\"discount_allocations\";a:1:{i:0;a:2:{s:6:\"amount\";s:4:\"6.41\";s:26:\"discount_application_index\";i:0;}}s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.0065;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}}}', 'a:15:{s:10:\"first_name\";s:11:\"slkdjfsldkj\";s:8:\"address1\";s:22:\"123 skjfsdlkfjsd sdlfj\";s:5:\"phone\";N;s:4:\"city\";s:11:\"minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:17:\"lksjflksjdflsdkjf\";s:8:\"address2\";s:3:\"123\";s:7:\"company\";N;s:8:\"latitude\";d:44.9836543;s:9:\"longitude\";d:-93.2693572;s:4:\"name\";s:29:\"slkdjfsldkj lksjflksjdflsdkjf\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:15:{s:10:\"first_name\";s:11:\"slkdjfsldkj\";s:8:\"address1\";s:22:\"123 skjfsdlkfjsd sdlfj\";s:5:\"phone\";N;s:4:\"city\";s:11:\"minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:17:\"lksjflksjdflsdkjf\";s:8:\"address2\";s:3:\"123\";s:7:\"company\";N;s:8:\"latitude\";d:44.9836543;s:9:\"longitude\";d:-93.2693572;s:4:\"name\";s:29:\"slkdjfsldkj lksjflksjdflsdkjf\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:1:{i:0;a:17:{s:2:\"id\";i:120446386199;s:8:\"order_id\";i:122149765143;s:6:\"status\";s:7:\"success\";s:10:\"created_at\";s:25:\"2017-11-03T15:36:52-05:00\";s:7:\"service\";s:6:\"manual\";s:10:\"updated_at\";s:25:\"2018-04-18T16:12:44-05:00\";s:16:\"tracking_company\";s:5:\"Other\";s:15:\"shipment_status\";N;s:11:\"location_id\";i:1926594583;s:15:\"tracking_number\";s:8:\"12121212\";s:16:\"tracking_numbers\";a:1:{i:0;s:8:\"12121212\";}s:12:\"tracking_url\";N;s:13:\"tracking_urls\";a:0:{}s:7:\"receipt\";a:0:{}s:4:\"name\";s:7:\"#1014.1\";s:20:\"admin_graphql_api_id\";s:38:\"gid://shopify/Fulfillment/120446386199\";s:10:\"line_items\";a:1:{i:0;a:26:{s:2:\"id\";i:104069857303;s:10:\"variant_id\";i:1102357463063;s:5:\"title\";s:26:\"Aerodynamic Bronze Hattttt\";s:8:\"quantity\";i:1;s:5:\"price\";s:4:\"0.00\";s:3:\"sku\";s:28:\"aerodynamic-bronze-hat-small\";s:13:\"variant_title\";s:5:\"Small\";s:6:\"vendor\";s:13:\"Beatty-Bednar\";s:19:\"fulfillment_service\";s:6:\"manual\";s:10:\"product_id\";N;s:17:\"requires_shipping\";b:1;s:7:\"taxable\";b:1;s:9:\"gift_card\";b:0;s:4:\"name\";s:34:\"Aerodynamic Bronze Hattttt - Small\";s:28:\"variant_inventory_management\";N;s:10:\"properties\";a:0:{}s:14:\"product_exists\";b:0;s:20:\"fulfillable_quantity\";i:0;s:5:\"grams\";i:857;s:14:\"total_discount\";s:4:\"0.00\";s:18:\"fulfillment_status\";s:9:\"fulfilled\";s:20:\"discount_allocations\";a:0:{}s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/LineItem/104069857303\";s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.0065;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}s:15:\"origin_location\";a:8:{s:2:\"id\";i:28088926231;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:13:\"wpslitetest10\";s:8:\"address1\";s:11:\"123 fsdfsdj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55408\";}s:20:\"destination_location\";a:8:{s:2:\"id\";i:57875791895;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:29:\"slkdjfsldkj lksjflksjdflsdkjf\";s:8:\"address1\";s:22:\"123 skjfsdlkfjsd sdlfj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"minneapolis\";s:3:\"zip\";s:5:\"55401\";}}}}}', 'a:6:{s:10:\"browser_ip\";s:13:\"199.66.91.246\";s:15:\"accept_language\";s:32:\"en-US,en;q=0.8,nb;q=0.6,la;q=0.4\";s:10:\"user_agent\";s:121:\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36\";s:12:\"session_hash\";N;s:13:\"browser_width\";i:1682;s:14:\"browser_height\";i:949;}', 'a:0:{}', 'a:20:{s:2:\"id\";i:186994655255;s:5:\"email\";s:22:\"andrew@simpleblend.net\";s:17:\"accepts_marketing\";b:0;s:10:\"created_at\";s:25:\"2017-10-27T20:39:33-05:00\";s:10:\"updated_at\";s:25:\"2018-08-13T15:43:58-05:00\";s:10:\"first_name\";s:6:\"Andrew\";s:9:\"last_name\";s:7:\"Robbins\";s:12:\"orders_count\";i:10;s:5:\"state\";s:8:\"disabled\";s:11:\"total_spent\";s:4:\"0.00\";s:13:\"last_order_id\";i:585463726103;s:4:\"note\";s:1:\"?\";s:14:\"verified_email\";b:1;s:20:\"multipass_identifier\";N;s:10:\"tax_exempt\";b:0;s:5:\"phone\";N;s:4:\"tags\";s:3:\"ddd\";s:15:\"last_order_name\";s:5:\"#1061\";s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/Customer/186994655255\";s:15:\"default_address\";a:17:{s:2:\"id\";i:783822815255;s:11:\"customer_id\";i:186994655255;s:10:\"first_name\";s:6:\"Andrew\";s:9:\"last_name\";s:7:\"Robbins\";s:7:\"company\";N;s:8:\"address1\";s:21:\"1221 West Lake Street\";s:8:\"address2\";s:0:\"\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55408\";s:5:\"phone\";N;s:4:\"name\";s:14:\"Andrew Robbins\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}}'),
	(122266222615, 0, 'sdfsdjfh@sdfsdf.com', NULL, '2017-10-28 20:41:44', '2017-10-31 01:55:20', 17, 'sdfsdfsdfsdf', '75138d2acd5ec1228308f6509ec80425', NULL, 0.00, 0.00, 408, '0.00', 0, 'USD', 'paid', 1, '6.33', 0.00, NULL, 0, '#1017', 'http://wpstest.dev/products/aerodynamic-bronze-hat/', '/cart/1102357397527:1?access_token=9596a847f3f4669fa8f4335a13386bd0&_fd=0&_ga=2.54203944.1039892632.1509214235-1126973387.1509214235', NULL, NULL, 0.00, 'b6101ff46c84b0d26dfbebfeedd6a405', NULL, NULL, NULL, NULL, NULL, '2017-10-28 20:41:44', NULL, NULL, 'en', 88312, NULL, NULL, 1017, 'a:1:{i:0;a:3:{s:4:\"code\";s:4:\"FREE\";s:6:\"amount\";s:4:\"6.33\";s:4:\"type\";s:8:\"shipping\";}}', 'a:0:{}', 'a:0:{}', 'free', 231822131223, 'web', NULL, 'a:0:{}', '', 'sdfsdjfh@sdfsdf.com', 'https://wpslitetest10.myshopify.com/24007681/orders/75138d2acd5ec1228308f6509ec80425/authenticate?key=11b5a7c373b0149acbdb92d717f01c80', 'a:1:{i:0;a:26:{s:2:\"id\";i:104261812247;s:10:\"variant_id\";i:1102357397527;s:5:\"title\";s:26:\"Aerodynamic Bronze Hattttt\";s:8:\"quantity\";i:1;s:5:\"price\";s:4:\"0.00\";s:3:\"sku\";s:0:\"\";s:13:\"variant_title\";s:11:\"Extra Small\";s:6:\"vendor\";s:13:\"Beatty-Bednar\";s:19:\"fulfillment_service\";s:6:\"manual\";s:10:\"product_id\";N;s:17:\"requires_shipping\";b:1;s:7:\"taxable\";b:1;s:9:\"gift_card\";b:0;s:4:\"name\";s:40:\"Aerodynamic Bronze Hattttt - Extra Small\";s:28:\"variant_inventory_management\";N;s:10:\"properties\";a:0:{}s:14:\"product_exists\";b:0;s:20:\"fulfillable_quantity\";i:1;s:5:\"grams\";i:408;s:14:\"total_discount\";s:4:\"0.00\";s:18:\"fulfillment_status\";N;s:20:\"discount_allocations\";a:0:{}s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/LineItem/104261812247\";s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.0065;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}s:15:\"origin_location\";a:8:{s:2:\"id\";i:28088926231;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:13:\"wpslitetest10\";s:8:\"address1\";s:11:\"123 fsdfsdj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55408\";}s:20:\"destination_location\";a:8:{s:2:\"id\";i:58014859287;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:19:\"sdfsdkjfh kjsdfkjsh\";s:8:\"address1\";s:12:\"123 ddfhsjhf\";s:8:\"address2\";s:0:\"\";s:4:\"city\";s:11:\"minneapolis\";s:3:\"zip\";s:5:\"55401\";}}}', 'a:1:{i:0;a:12:{s:2:\"id\";i:91815378967;s:5:\"title\";s:13:\"Priority Mail\";s:5:\"price\";s:4:\"6.33\";s:4:\"code\";s:8:\"Priority\";s:6:\"source\";s:4:\"usps\";s:5:\"phone\";N;s:32:\"requested_fulfillment_service_id\";N;s:17:\"delivery_category\";N;s:18:\"carrier_identifier\";N;s:16:\"discounted_price\";s:4:\"6.33\";s:20:\"discount_allocations\";a:1:{i:0;a:2:{s:6:\"amount\";s:4:\"6.33\";s:26:\"discount_application_index\";i:0;}}s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.0065;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}}}', 'a:15:{s:10:\"first_name\";s:9:\"sdfsdkjfh\";s:8:\"address1\";s:12:\"123 ddfhsjhf\";s:5:\"phone\";N;s:4:\"city\";s:11:\"minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:9:\"kjsdfkjsh\";s:8:\"address2\";s:0:\"\";s:7:\"company\";N;s:8:\"latitude\";d:44.9836543;s:9:\"longitude\";d:-93.2693572;s:4:\"name\";s:19:\"sdfsdkjfh kjsdfkjsh\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:15:{s:10:\"first_name\";s:9:\"sdfsdkjfh\";s:8:\"address1\";s:12:\"123 ddfhsjhf\";s:5:\"phone\";N;s:4:\"city\";s:11:\"minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:9:\"kjsdfkjsh\";s:8:\"address2\";s:0:\"\";s:7:\"company\";N;s:8:\"latitude\";d:44.9836543;s:9:\"longitude\";d:-93.2693572;s:4:\"name\";s:19:\"sdfsdkjfh kjsdfkjsh\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:0:{}', 'a:6:{s:10:\"browser_ip\";s:13:\"199.66.91.246\";s:15:\"accept_language\";s:32:\"en-US,en;q=0.8,nb;q=0.6,la;q=0.4\";s:10:\"user_agent\";s:121:\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36\";s:12:\"session_hash\";N;s:13:\"browser_width\";i:1682;s:14:\"browser_height\";i:574;}', 'a:0:{}', 'a:20:{s:2:\"id\";i:187648475159;s:5:\"email\";s:19:\"sdfsdjfh@sdfsdf.com\";s:17:\"accepts_marketing\";b:0;s:10:\"created_at\";s:25:\"2017-10-28T15:41:27-05:00\";s:10:\"updated_at\";s:25:\"2017-10-28T15:41:44-05:00\";s:10:\"first_name\";s:9:\"sdfsdkjfh\";s:9:\"last_name\";s:9:\"kjsdfkjsh\";s:12:\"orders_count\";i:1;s:5:\"state\";s:8:\"disabled\";s:11:\"total_spent\";s:4:\"0.00\";s:13:\"last_order_id\";i:122266222615;s:4:\"note\";N;s:14:\"verified_email\";b:1;s:20:\"multipass_identifier\";N;s:10:\"tax_exempt\";b:0;s:5:\"phone\";N;s:4:\"tags\";s:0:\"\";s:15:\"last_order_name\";s:5:\"#1017\";s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/Customer/187648475159\";s:15:\"default_address\";a:17:{s:2:\"id\";i:162011742231;s:11:\"customer_id\";i:187648475159;s:10:\"first_name\";s:9:\"sdfsdkjfh\";s:9:\"last_name\";s:9:\"kjsdfkjsh\";s:7:\"company\";N;s:8:\"address1\";s:12:\"123 ddfhsjhf\";s:8:\"address2\";s:0:\"\";s:4:\"city\";s:11:\"minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";N;s:4:\"name\";s:19:\"sdfsdkjfh kjsdfkjsh\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}}'),
	(122664091671, 0, 'sfsdfjhsjdf@sdfdf.com', '2017-11-03 19:42:59', '2017-10-29 15:13:52', '2017-11-03 20:11:35', 18, 'sdsdfsdfsdf', 'cb3c259ccc4947ebb8f59b42325789f4', NULL, 0.00, 0.00, 218, '0.00', 0, 'USD', 'paid', 1, '4.16', 0.00, NULL, 0, '#1018', 'http://wpstest.dev/products/aerodynamic-bronze-hat/', '/cart/1102357528599:1?access_token=9596a847f3f4669fa8f4335a13386bd0&_fd=0&_ga=2.27819196.1039892632.1509214235-1126973387.1509214235&attributes[keyy]=valuee', NULL, NULL, 0.00, '0376a1bdb47b4c6e20f40f5a2a0259da', NULL, NULL, NULL, NULL, NULL, '2017-10-29 15:13:52', NULL, NULL, 'en', 88312, NULL, NULL, 1018, 'a:1:{i:0;a:3:{s:4:\"code\";s:4:\"FREE\";s:6:\"amount\";s:4:\"4.16\";s:4:\"type\";s:8:\"shipping\";}}', 'a:1:{i:0;a:2:{s:4:\"name\";s:4:\"keyy\";s:5:\"value\";s:9:\"valueewew\";}}', 'a:0:{}', 'free', 232977498135, 'web', 'fulfilled', 'a:0:{}', '', 'sfsdfjhsjdf@sdfdf.com', 'https://wpslitetest10.myshopify.com/24007681/orders/cb3c259ccc4947ebb8f59b42325789f4/authenticate?key=b85988ed143ee1600e30c304fda3d101', 'a:1:{i:0;a:26:{s:2:\"id\";i:104879259671;s:10:\"variant_id\";i:1102357528599;s:5:\"title\";s:26:\"Aerodynamic Bronze Hattttt\";s:8:\"quantity\";i:1;s:5:\"price\";s:4:\"0.00\";s:3:\"sku\";s:29:\"aerodynamic-bronze-hat-medium\";s:13:\"variant_title\";s:6:\"Medium\";s:6:\"vendor\";s:13:\"Beatty-Bednar\";s:19:\"fulfillment_service\";s:6:\"manual\";s:10:\"product_id\";N;s:17:\"requires_shipping\";b:1;s:7:\"taxable\";b:1;s:9:\"gift_card\";b:0;s:4:\"name\";s:35:\"Aerodynamic Bronze Hattttt - Medium\";s:28:\"variant_inventory_management\";N;s:10:\"properties\";a:0:{}s:14:\"product_exists\";b:0;s:20:\"fulfillable_quantity\";i:0;s:5:\"grams\";i:218;s:14:\"total_discount\";s:4:\"0.00\";s:18:\"fulfillment_status\";s:9:\"fulfilled\";s:20:\"discount_allocations\";a:0:{}s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/LineItem/104879259671\";s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.0065;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}s:15:\"origin_location\";a:8:{s:2:\"id\";i:28088926231;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:13:\"wpslitetest10\";s:8:\"address1\";s:11:\"123 fsdfsdj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55408\";}s:20:\"destination_location\";a:8:{s:2:\"id\";i:58657472535;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:13:\"sdfsjl slkdjf\";s:8:\"address1\";s:10:\"123 sdkfjl\";s:8:\"address2\";s:2:\"80\";s:4:\"city\";s:11:\"minneapolis\";s:3:\"zip\";s:5:\"55401\";}}}', 'a:1:{i:0;a:12:{s:2:\"id\";i:92125364247;s:5:\"title\";s:19:\"First Class Package\";s:5:\"price\";s:4:\"4.16\";s:4:\"code\";s:12:\"FirstPackage\";s:6:\"source\";s:4:\"usps\";s:5:\"phone\";N;s:32:\"requested_fulfillment_service_id\";N;s:17:\"delivery_category\";N;s:18:\"carrier_identifier\";N;s:16:\"discounted_price\";s:4:\"4.16\";s:20:\"discount_allocations\";a:1:{i:0;a:2:{s:6:\"amount\";s:4:\"4.16\";s:26:\"discount_application_index\";i:0;}}s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.0065;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}}}', 'a:15:{s:10:\"first_name\";s:6:\"sdfsjl\";s:8:\"address1\";s:10:\"123 sdkfjl\";s:5:\"phone\";N;s:4:\"city\";s:11:\"minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:6:\"slkdjf\";s:8:\"address2\";s:2:\"80\";s:7:\"company\";N;s:8:\"latitude\";d:44.9836543;s:9:\"longitude\";d:-93.2693572;s:4:\"name\";s:13:\"sdfsjl slkdjf\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:15:{s:10:\"first_name\";s:6:\"sdfsjl\";s:8:\"address1\";s:10:\"123 sdkfjl\";s:5:\"phone\";N;s:4:\"city\";s:11:\"minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:6:\"slkdjf\";s:8:\"address2\";s:2:\"80\";s:7:\"company\";N;s:8:\"latitude\";d:44.9836543;s:9:\"longitude\";d:-93.2693572;s:4:\"name\";s:13:\"sdfsjl slkdjf\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:1:{i:0;a:17:{s:2:\"id\";i:120415092759;s:8:\"order_id\";i:122664091671;s:6:\"status\";s:7:\"success\";s:10:\"created_at\";s:25:\"2017-11-03T14:42:59-05:00\";s:7:\"service\";s:6:\"manual\";s:10:\"updated_at\";s:25:\"2018-04-18T16:12:32-05:00\";s:16:\"tracking_company\";N;s:15:\"shipment_status\";N;s:11:\"location_id\";i:1926594583;s:15:\"tracking_number\";N;s:16:\"tracking_numbers\";a:0:{}s:12:\"tracking_url\";N;s:13:\"tracking_urls\";a:0:{}s:7:\"receipt\";a:0:{}s:4:\"name\";s:7:\"#1018.1\";s:20:\"admin_graphql_api_id\";s:38:\"gid://shopify/Fulfillment/120415092759\";s:10:\"line_items\";a:1:{i:0;a:26:{s:2:\"id\";i:104879259671;s:10:\"variant_id\";i:1102357528599;s:5:\"title\";s:26:\"Aerodynamic Bronze Hattttt\";s:8:\"quantity\";i:1;s:5:\"price\";s:4:\"0.00\";s:3:\"sku\";s:29:\"aerodynamic-bronze-hat-medium\";s:13:\"variant_title\";s:6:\"Medium\";s:6:\"vendor\";s:13:\"Beatty-Bednar\";s:19:\"fulfillment_service\";s:6:\"manual\";s:10:\"product_id\";N;s:17:\"requires_shipping\";b:1;s:7:\"taxable\";b:1;s:9:\"gift_card\";b:0;s:4:\"name\";s:35:\"Aerodynamic Bronze Hattttt - Medium\";s:28:\"variant_inventory_management\";N;s:10:\"properties\";a:0:{}s:14:\"product_exists\";b:0;s:20:\"fulfillable_quantity\";i:0;s:5:\"grams\";i:218;s:14:\"total_discount\";s:4:\"0.00\";s:18:\"fulfillment_status\";s:9:\"fulfilled\";s:20:\"discount_allocations\";a:0:{}s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/LineItem/104879259671\";s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.0065;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}s:15:\"origin_location\";a:8:{s:2:\"id\";i:28088926231;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:13:\"wpslitetest10\";s:8:\"address1\";s:11:\"123 fsdfsdj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55408\";}s:20:\"destination_location\";a:8:{s:2:\"id\";i:58657472535;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:13:\"sdfsjl slkdjf\";s:8:\"address1\";s:10:\"123 sdkfjl\";s:8:\"address2\";s:2:\"80\";s:4:\"city\";s:11:\"minneapolis\";s:3:\"zip\";s:5:\"55401\";}}}}}', 'a:6:{s:10:\"browser_ip\";s:14:\"69.180.173.224\";s:15:\"accept_language\";s:32:\"en-US,en;q=0.8,nb;q=0.6,la;q=0.4\";s:10:\"user_agent\";s:121:\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36\";s:12:\"session_hash\";N;s:13:\"browser_width\";i:1230;s:14:\"browser_height\";i:949;}', 'a:0:{}', 'a:20:{s:2:\"id\";i:189139288087;s:5:\"email\";s:21:\"sfsdfjhsjdf@sdfdf.com\";s:17:\"accepts_marketing\";b:0;s:10:\"created_at\";s:25:\"2017-10-29T10:13:40-05:00\";s:10:\"updated_at\";s:25:\"2017-10-30T12:15:58-05:00\";s:10:\"first_name\";s:6:\"sdfsjl\";s:9:\"last_name\";s:6:\"slkdjf\";s:12:\"orders_count\";i:1;s:5:\"state\";s:8:\"disabled\";s:11:\"total_spent\";s:4:\"0.00\";s:13:\"last_order_id\";i:122664091671;s:4:\"note\";s:15:\"sdfsdfsd sdfsdf\";s:14:\"verified_email\";b:1;s:20:\"multipass_identifier\";N;s:10:\"tax_exempt\";b:0;s:5:\"phone\";N;s:4:\"tags\";s:3:\"VIP\";s:15:\"last_order_name\";s:5:\"#1018\";s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/Customer/189139288087\";s:15:\"default_address\";a:17:{s:2:\"id\";i:163509698583;s:11:\"customer_id\";i:189139288087;s:10:\"first_name\";s:9:\"sdfsjlddd\";s:9:\"last_name\";s:6:\"slkdjf\";s:7:\"company\";s:0:\"\";s:8:\"address1\";s:10:\"123 sdkfjl\";s:8:\"address2\";s:2:\"80\";s:4:\"city\";s:11:\"minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";s:0:\"\";s:4:\"name\";s:16:\"sdfsjlddd slkdjf\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}}'),
	(161685110807, 0, 'oskodkfo@sds.com', NULL, '2017-11-28 20:05:02', '2017-11-28 20:05:02', 20, NULL, '5d7fd7b98ef1b5bde3bb0b42de51c0d8', NULL, 0.00, 0.00, 0, '0.00', 0, 'USD', 'paid', 1, '2.77', 0.00, NULL, 0, '#1020', 'http://wpstest.dev/products/aaaaaaaa1111/', '/cart/2666408214551:1?access_token=fccd62f405d550a0f0152dc1f9faf9f9&_fd=0&_ga=2.50644649.1446386368.1511750021-833124060.1511750021', NULL, NULL, 0.00, '2e5421cc45d9427d41d8b9e47e4b5631', NULL, NULL, NULL, NULL, NULL, '2017-11-28 20:05:02', NULL, NULL, 'en', 88312, NULL, NULL, 1020, 'a:1:{i:0;a:3:{s:4:\"code\";s:4:\"FREE\";s:6:\"amount\";s:4:\"2.77\";s:4:\"type\";s:8:\"shipping\";}}', 'a:0:{}', 'a:0:{}', 'free', 307631751191, 'web', NULL, 'a:0:{}', '', 'oskodkfo@sds.com', 'https://wpslitetest10.myshopify.com/24007681/orders/5d7fd7b98ef1b5bde3bb0b42de51c0d8/authenticate?key=9845a037a90ecbf3c436bf4add57447e', 'a:1:{i:0;a:26:{s:2:\"id\";i:195322609687;s:10:\"variant_id\";i:2666408214551;s:5:\"title\";s:8:\"aaaaaaaa\";s:8:\"quantity\";i:1;s:5:\"price\";s:4:\"0.00\";s:3:\"sku\";s:0:\"\";s:13:\"variant_title\";s:0:\"\";s:6:\"vendor\";s:13:\"wpslitetest10\";s:19:\"fulfillment_service\";s:6:\"manual\";s:10:\"product_id\";N;s:17:\"requires_shipping\";b:1;s:7:\"taxable\";b:1;s:9:\"gift_card\";b:0;s:4:\"name\";s:8:\"aaaaaaaa\";s:28:\"variant_inventory_management\";N;s:10:\"properties\";a:0:{}s:14:\"product_exists\";b:0;s:20:\"fulfillable_quantity\";i:1;s:5:\"grams\";i:0;s:14:\"total_discount\";s:4:\"0.00\";s:18:\"fulfillment_status\";N;s:20:\"discount_allocations\";a:0:{}s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/LineItem/195322609687\";s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.0065;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}s:15:\"origin_location\";a:8:{s:2:\"id\";i:28088926231;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:13:\"wpslitetest10\";s:8:\"address1\";s:11:\"123 fsdfsdj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55408\";}s:20:\"destination_location\";a:8:{s:2:\"id\";i:102724435991;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:17:\"sdflksdj kjsdlkfj\";s:8:\"address1\";s:13:\"123 sdkfsdkfj\";s:8:\"address2\";s:2:\"12\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";}}}', 'a:1:{i:0;a:12:{s:2:\"id\";i:125951180823;s:5:\"title\";s:19:\"First Class Package\";s:5:\"price\";s:4:\"2.77\";s:4:\"code\";s:12:\"FirstPackage\";s:6:\"source\";s:4:\"usps\";s:5:\"phone\";N;s:32:\"requested_fulfillment_service_id\";N;s:17:\"delivery_category\";N;s:18:\"carrier_identifier\";N;s:16:\"discounted_price\";s:4:\"2.77\";s:20:\"discount_allocations\";a:1:{i:0;a:2:{s:6:\"amount\";s:4:\"2.77\";s:26:\"discount_application_index\";i:0;}}s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.0065;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}}}', 'a:15:{s:10:\"first_name\";s:8:\"sdflksdj\";s:8:\"address1\";s:13:\"123 sdkfsdkfj\";s:5:\"phone\";N;s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:8:\"kjsdlkfj\";s:8:\"address2\";s:2:\"12\";s:7:\"company\";N;s:8:\"latitude\";d:44.9836543;s:9:\"longitude\";d:-93.2693572;s:4:\"name\";s:17:\"sdflksdj kjsdlkfj\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:15:{s:10:\"first_name\";s:8:\"sdflksdj\";s:8:\"address1\";s:13:\"123 sdkfsdkfj\";s:5:\"phone\";N;s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:8:\"kjsdlkfj\";s:8:\"address2\";s:2:\"12\";s:7:\"company\";N;s:8:\"latitude\";d:44.9836543;s:9:\"longitude\";d:-93.2693572;s:4:\"name\";s:17:\"sdflksdj kjsdlkfj\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:0:{}', 'a:6:{s:10:\"browser_ip\";s:14:\"50.203.238.241\";s:15:\"accept_language\";s:32:\"en-US,en;q=0.9,nb;q=0.8,la;q=0.7\";s:10:\"user_agent\";s:120:\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36\";s:12:\"session_hash\";N;s:13:\"browser_width\";i:1680;s:14:\"browser_height\";i:475;}', 'a:0:{}', 'a:20:{s:2:\"id\";i:240261922839;s:5:\"email\";s:16:\"oskodkfo@sds.com\";s:17:\"accepts_marketing\";b:0;s:10:\"created_at\";s:25:\"2017-11-28T14:04:43-06:00\";s:10:\"updated_at\";s:25:\"2017-11-28T18:13:05-06:00\";s:10:\"first_name\";s:8:\"sdflksdj\";s:9:\"last_name\";s:8:\"kjsdlkfj\";s:12:\"orders_count\";i:19;s:5:\"state\";s:7:\"invited\";s:11:\"total_spent\";s:4:\"0.00\";s:13:\"last_order_id\";i:161906294807;s:4:\"note\";N;s:14:\"verified_email\";b:1;s:20:\"multipass_identifier\";N;s:10:\"tax_exempt\";b:0;s:5:\"phone\";N;s:4:\"tags\";s:0:\"\";s:15:\"last_order_name\";s:5:\"#1038\";s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/Customer/240261922839\";s:15:\"default_address\";a:17:{s:2:\"id\";i:221485105175;s:11:\"customer_id\";i:240261922839;s:10:\"first_name\";s:8:\"sdflksdj\";s:9:\"last_name\";s:8:\"kjsdlkfj\";s:7:\"company\";N;s:8:\"address1\";s:13:\"123 sdkfsdkfj\";s:8:\"address2\";s:2:\"12\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";N;s:4:\"name\";s:17:\"sdflksdj kjsdlkfj\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}}'),
	(161695629335, 0, 'oskodkfo@sds.com', NULL, '2017-11-28 20:15:19', '2017-11-28 20:15:20', 21, NULL, 'ca46d4c6d1d987a149a6621c6eaf0ea2', NULL, 0.00, 0.00, 0, '0.00', 0, 'USD', 'paid', 1, '2.77', 0.00, NULL, 0, '#1021', 'http://wpstest.dev/products/', '/cart/2666408214551:1?access_token=fccd62f405d550a0f0152dc1f9faf9f9&_fd=0&_ga=2.119319177.1446386368.1511750021-833124060.1511750021', NULL, NULL, 0.00, '97eb6210372436759ef54f2e48a1f865', NULL, NULL, NULL, NULL, NULL, '2017-11-28 20:15:19', NULL, NULL, 'en', 88312, NULL, NULL, 1021, 'a:1:{i:0;a:3:{s:4:\"code\";s:4:\"FREE\";s:6:\"amount\";s:4:\"2.77\";s:4:\"type\";s:8:\"shipping\";}}', 'a:0:{}', 'a:0:{}', 'free', 307660685335, 'web', NULL, 'a:0:{}', '', 'oskodkfo@sds.com', 'https://wpslitetest10.myshopify.com/24007681/orders/ca46d4c6d1d987a149a6621c6eaf0ea2/authenticate?key=de3c77c4f762ed41de5b7ee5d92fcb2e', 'a:1:{i:0;a:26:{s:2:\"id\";i:195339091991;s:10:\"variant_id\";i:2666408214551;s:5:\"title\";s:8:\"aaaaaaaa\";s:8:\"quantity\";i:1;s:5:\"price\";s:4:\"0.00\";s:3:\"sku\";s:0:\"\";s:13:\"variant_title\";s:0:\"\";s:6:\"vendor\";s:13:\"wpslitetest10\";s:19:\"fulfillment_service\";s:6:\"manual\";s:10:\"product_id\";N;s:17:\"requires_shipping\";b:1;s:7:\"taxable\";b:1;s:9:\"gift_card\";b:0;s:4:\"name\";s:8:\"aaaaaaaa\";s:28:\"variant_inventory_management\";N;s:10:\"properties\";a:0:{}s:14:\"product_exists\";b:0;s:20:\"fulfillable_quantity\";i:1;s:5:\"grams\";i:0;s:14:\"total_discount\";s:4:\"0.00\";s:18:\"fulfillment_status\";N;s:20:\"discount_allocations\";a:0:{}s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/LineItem/195339091991\";s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.0065;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}s:15:\"origin_location\";a:8:{s:2:\"id\";i:28088926231;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:13:\"wpslitetest10\";s:8:\"address1\";s:11:\"123 fsdfsdj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55408\";}s:20:\"destination_location\";a:8:{s:2:\"id\";i:102724435991;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:17:\"sdflksdj kjsdlkfj\";s:8:\"address1\";s:13:\"123 sdkfsdkfj\";s:8:\"address2\";s:2:\"12\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";}}}', 'a:1:{i:0;a:12:{s:2:\"id\";i:125959536663;s:5:\"title\";s:19:\"First Class Package\";s:5:\"price\";s:4:\"2.77\";s:4:\"code\";s:12:\"FirstPackage\";s:6:\"source\";s:4:\"usps\";s:5:\"phone\";N;s:32:\"requested_fulfillment_service_id\";N;s:17:\"delivery_category\";N;s:18:\"carrier_identifier\";N;s:16:\"discounted_price\";s:4:\"2.77\";s:20:\"discount_allocations\";a:1:{i:0;a:2:{s:6:\"amount\";s:4:\"2.77\";s:26:\"discount_application_index\";i:0;}}s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.0065;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}}}', 'a:15:{s:10:\"first_name\";s:8:\"sdflksdj\";s:8:\"address1\";s:13:\"123 sdkfsdkfj\";s:5:\"phone\";N;s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:8:\"kjsdlkfj\";s:8:\"address2\";s:2:\"12\";s:7:\"company\";N;s:8:\"latitude\";d:44.9836543;s:9:\"longitude\";d:-93.2693572;s:4:\"name\";s:17:\"sdflksdj kjsdlkfj\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:15:{s:10:\"first_name\";s:8:\"sdflksdj\";s:8:\"address1\";s:13:\"123 sdkfsdkfj\";s:5:\"phone\";N;s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:8:\"kjsdlkfj\";s:8:\"address2\";s:2:\"12\";s:7:\"company\";N;s:8:\"latitude\";d:44.9836543;s:9:\"longitude\";d:-93.2693572;s:4:\"name\";s:17:\"sdflksdj kjsdlkfj\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:0:{}', 'a:6:{s:10:\"browser_ip\";s:14:\"50.203.238.241\";s:15:\"accept_language\";s:32:\"en-US,en;q=0.9,nb;q=0.8,la;q=0.7\";s:10:\"user_agent\";s:120:\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36\";s:12:\"session_hash\";N;s:13:\"browser_width\";i:1680;s:14:\"browser_height\";i:954;}', 'a:0:{}', 'a:20:{s:2:\"id\";i:240261922839;s:5:\"email\";s:16:\"oskodkfo@sds.com\";s:17:\"accepts_marketing\";b:0;s:10:\"created_at\";s:25:\"2017-11-28T14:04:43-06:00\";s:10:\"updated_at\";s:25:\"2017-11-28T18:13:05-06:00\";s:10:\"first_name\";s:8:\"sdflksdj\";s:9:\"last_name\";s:8:\"kjsdlkfj\";s:12:\"orders_count\";i:19;s:5:\"state\";s:7:\"invited\";s:11:\"total_spent\";s:4:\"0.00\";s:13:\"last_order_id\";i:161906294807;s:4:\"note\";N;s:14:\"verified_email\";b:1;s:20:\"multipass_identifier\";N;s:10:\"tax_exempt\";b:0;s:5:\"phone\";N;s:4:\"tags\";s:0:\"\";s:15:\"last_order_name\";s:5:\"#1038\";s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/Customer/240261922839\";s:15:\"default_address\";a:17:{s:2:\"id\";i:221485105175;s:11:\"customer_id\";i:240261922839;s:10:\"first_name\";s:8:\"sdflksdj\";s:9:\"last_name\";s:8:\"kjsdlkfj\";s:7:\"company\";N;s:8:\"address1\";s:13:\"123 sdkfsdkfj\";s:8:\"address2\";s:2:\"12\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";N;s:4:\"name\";s:17:\"sdflksdj kjsdlkfj\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}}'),
	(161705099287, 0, 'oskodkfo@sds.com', NULL, '2017-11-28 20:24:57', '2017-11-28 20:24:58', 22, NULL, 'ee25dbc650eb5a83138198ba816661b5', NULL, 0.00, 0.00, 0, '0.00', 0, 'USD', 'paid', 1, '2.77', 0.00, NULL, 0, '#1022', 'http://wpstest.dev/products/aaaaaaaa1111/', '/cart/2666408214551:4?access_token=fccd62f405d550a0f0152dc1f9faf9f9&_fd=0&_ga=2.13545143.1446386368.1511750021-833124060.1511750021', NULL, NULL, 0.00, '67ad530dd0aac150e8fafcfb8090997a', NULL, NULL, NULL, NULL, NULL, '2017-11-28 20:24:57', NULL, NULL, 'en', 88312, NULL, NULL, 1022, 'a:1:{i:0;a:3:{s:4:\"code\";s:4:\"FREE\";s:6:\"amount\";s:4:\"2.77\";s:4:\"type\";s:8:\"shipping\";}}', 'a:0:{}', 'a:0:{}', 'free', 307682738199, 'web', NULL, 'a:0:{}', '', 'oskodkfo@sds.com', 'https://wpslitetest10.myshopify.com/24007681/orders/ee25dbc650eb5a83138198ba816661b5/authenticate?key=b697511d52a6962e25c2daa7e8229943', 'a:1:{i:0;a:26:{s:2:\"id\";i:195355705367;s:10:\"variant_id\";i:2666408214551;s:5:\"title\";s:8:\"aaaaaaaa\";s:8:\"quantity\";i:4;s:5:\"price\";s:4:\"0.00\";s:3:\"sku\";s:0:\"\";s:13:\"variant_title\";s:0:\"\";s:6:\"vendor\";s:13:\"wpslitetest10\";s:19:\"fulfillment_service\";s:6:\"manual\";s:10:\"product_id\";N;s:17:\"requires_shipping\";b:1;s:7:\"taxable\";b:1;s:9:\"gift_card\";b:0;s:4:\"name\";s:8:\"aaaaaaaa\";s:28:\"variant_inventory_management\";N;s:10:\"properties\";a:0:{}s:14:\"product_exists\";b:0;s:20:\"fulfillable_quantity\";i:4;s:5:\"grams\";i:0;s:14:\"total_discount\";s:4:\"0.00\";s:18:\"fulfillment_status\";N;s:20:\"discount_allocations\";a:0:{}s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/LineItem/195355705367\";s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.0065;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}s:15:\"origin_location\";a:8:{s:2:\"id\";i:28088926231;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:13:\"wpslitetest10\";s:8:\"address1\";s:11:\"123 fsdfsdj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55408\";}s:20:\"destination_location\";a:8:{s:2:\"id\";i:102724435991;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:17:\"sdflksdj kjsdlkfj\";s:8:\"address1\";s:13:\"123 sdkfsdkfj\";s:8:\"address2\";s:2:\"12\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";}}}', 'a:1:{i:0;a:12:{s:2:\"id\";i:125966778391;s:5:\"title\";s:19:\"First Class Package\";s:5:\"price\";s:4:\"2.77\";s:4:\"code\";s:12:\"FirstPackage\";s:6:\"source\";s:4:\"usps\";s:5:\"phone\";N;s:32:\"requested_fulfillment_service_id\";N;s:17:\"delivery_category\";N;s:18:\"carrier_identifier\";N;s:16:\"discounted_price\";s:4:\"2.77\";s:20:\"discount_allocations\";a:1:{i:0;a:2:{s:6:\"amount\";s:4:\"2.77\";s:26:\"discount_application_index\";i:0;}}s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.0065;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}}}', 'a:15:{s:10:\"first_name\";s:8:\"sdflksdj\";s:8:\"address1\";s:13:\"123 sdkfsdkfj\";s:5:\"phone\";N;s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:8:\"kjsdlkfj\";s:8:\"address2\";s:2:\"12\";s:7:\"company\";N;s:8:\"latitude\";d:44.9836543;s:9:\"longitude\";d:-93.2693572;s:4:\"name\";s:17:\"sdflksdj kjsdlkfj\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:15:{s:10:\"first_name\";s:8:\"sdflksdj\";s:8:\"address1\";s:13:\"123 sdkfsdkfj\";s:5:\"phone\";N;s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:8:\"kjsdlkfj\";s:8:\"address2\";s:2:\"12\";s:7:\"company\";N;s:8:\"latitude\";d:44.9836543;s:9:\"longitude\";d:-93.2693572;s:4:\"name\";s:17:\"sdflksdj kjsdlkfj\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:0:{}', 'a:6:{s:10:\"browser_ip\";s:14:\"50.203.238.241\";s:15:\"accept_language\";s:32:\"en-US,en;q=0.9,nb;q=0.8,la;q=0.7\";s:10:\"user_agent\";s:120:\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36\";s:12:\"session_hash\";N;s:13:\"browser_width\";i:1031;s:14:\"browser_height\";i:954;}', 'a:0:{}', 'a:20:{s:2:\"id\";i:240261922839;s:5:\"email\";s:16:\"oskodkfo@sds.com\";s:17:\"accepts_marketing\";b:0;s:10:\"created_at\";s:25:\"2017-11-28T14:04:43-06:00\";s:10:\"updated_at\";s:25:\"2017-11-28T18:13:05-06:00\";s:10:\"first_name\";s:8:\"sdflksdj\";s:9:\"last_name\";s:8:\"kjsdlkfj\";s:12:\"orders_count\";i:19;s:5:\"state\";s:7:\"invited\";s:11:\"total_spent\";s:4:\"0.00\";s:13:\"last_order_id\";i:161906294807;s:4:\"note\";N;s:14:\"verified_email\";b:1;s:20:\"multipass_identifier\";N;s:10:\"tax_exempt\";b:0;s:5:\"phone\";N;s:4:\"tags\";s:0:\"\";s:15:\"last_order_name\";s:5:\"#1038\";s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/Customer/240261922839\";s:15:\"default_address\";a:17:{s:2:\"id\";i:221485105175;s:11:\"customer_id\";i:240261922839;s:10:\"first_name\";s:8:\"sdflksdj\";s:9:\"last_name\";s:8:\"kjsdlkfj\";s:7:\"company\";N;s:8:\"address1\";s:13:\"123 sdkfsdkfj\";s:8:\"address2\";s:2:\"12\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";N;s:4:\"name\";s:17:\"sdflksdj kjsdlkfj\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}}'),
	(161711259671, 0, 'oskodkfo@sds.com', NULL, '2017-11-28 20:30:23', '2017-11-28 20:30:23', 23, NULL, 'd1679d976a0ff4af47b4b89eaa740b47', NULL, 0.00, 0.00, 0, '0.00', 0, 'USD', 'paid', 1, '2.77', 0.00, NULL, 0, '#1023', 'http://wpstest.dev/products/aaaaaaaa1111/', '/cart/2666408214551:4?access_token=fccd62f405d550a0f0152dc1f9faf9f9&_fd=0&_ga=2.106818051.1446386368.1511750021-833124060.1511750021?attributes[where-from]=came-from-newsletter-2013-02-14&attributes[some-other-key]=some-value', NULL, NULL, 0.00, '25d6638e511531631746cfdfb09f8f74', NULL, NULL, NULL, NULL, NULL, '2017-11-28 20:30:23', NULL, NULL, 'en', 88312, NULL, NULL, 1023, 'a:1:{i:0;a:3:{s:4:\"code\";s:4:\"FREE\";s:6:\"amount\";s:4:\"2.77\";s:4:\"type\";s:8:\"shipping\";}}', 'a:1:{i:0;a:2:{s:4:\"name\";s:14:\"some-other-key\";s:5:\"value\";s:10:\"some-value\";}}', 'a:0:{}', 'free', 307695321111, 'web', NULL, 'a:0:{}', '', 'oskodkfo@sds.com', 'https://wpslitetest10.myshopify.com/24007681/orders/d1679d976a0ff4af47b4b89eaa740b47/authenticate?key=61e2c013227d8f8c761b670e3d4c16ac', 'a:1:{i:0;a:26:{s:2:\"id\";i:195368878103;s:10:\"variant_id\";i:2666408214551;s:5:\"title\";s:8:\"aaaaaaaa\";s:8:\"quantity\";i:4;s:5:\"price\";s:4:\"0.00\";s:3:\"sku\";s:0:\"\";s:13:\"variant_title\";s:0:\"\";s:6:\"vendor\";s:13:\"wpslitetest10\";s:19:\"fulfillment_service\";s:6:\"manual\";s:10:\"product_id\";N;s:17:\"requires_shipping\";b:1;s:7:\"taxable\";b:1;s:9:\"gift_card\";b:0;s:4:\"name\";s:8:\"aaaaaaaa\";s:28:\"variant_inventory_management\";N;s:10:\"properties\";a:0:{}s:14:\"product_exists\";b:0;s:20:\"fulfillable_quantity\";i:4;s:5:\"grams\";i:0;s:14:\"total_discount\";s:4:\"0.00\";s:18:\"fulfillment_status\";N;s:20:\"discount_allocations\";a:0:{}s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/LineItem/195368878103\";s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.0065;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}s:15:\"origin_location\";a:8:{s:2:\"id\";i:28088926231;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:13:\"wpslitetest10\";s:8:\"address1\";s:11:\"123 fsdfsdj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55408\";}s:20:\"destination_location\";a:8:{s:2:\"id\";i:102724435991;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:17:\"sdflksdj kjsdlkfj\";s:8:\"address1\";s:13:\"123 sdkfsdkfj\";s:8:\"address2\";s:2:\"12\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";}}}', 'a:1:{i:0;a:12:{s:2:\"id\";i:125971628055;s:5:\"title\";s:19:\"First Class Package\";s:5:\"price\";s:4:\"2.77\";s:4:\"code\";s:12:\"FirstPackage\";s:6:\"source\";s:4:\"usps\";s:5:\"phone\";N;s:32:\"requested_fulfillment_service_id\";N;s:17:\"delivery_category\";N;s:18:\"carrier_identifier\";N;s:16:\"discounted_price\";s:4:\"2.77\";s:20:\"discount_allocations\";a:1:{i:0;a:2:{s:6:\"amount\";s:4:\"2.77\";s:26:\"discount_application_index\";i:0;}}s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.0065;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}}}', 'a:15:{s:10:\"first_name\";s:8:\"sdflksdj\";s:8:\"address1\";s:13:\"123 sdkfsdkfj\";s:5:\"phone\";N;s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:8:\"kjsdlkfj\";s:8:\"address2\";s:2:\"12\";s:7:\"company\";N;s:8:\"latitude\";d:44.9836543;s:9:\"longitude\";d:-93.2693572;s:4:\"name\";s:17:\"sdflksdj kjsdlkfj\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:15:{s:10:\"first_name\";s:8:\"sdflksdj\";s:8:\"address1\";s:13:\"123 sdkfsdkfj\";s:5:\"phone\";N;s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:8:\"kjsdlkfj\";s:8:\"address2\";s:2:\"12\";s:7:\"company\";N;s:8:\"latitude\";d:44.9836543;s:9:\"longitude\";d:-93.2693572;s:4:\"name\";s:17:\"sdflksdj kjsdlkfj\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:0:{}', 'a:6:{s:10:\"browser_ip\";s:14:\"50.203.238.241\";s:15:\"accept_language\";s:32:\"en-US,en;q=0.9,nb;q=0.8,la;q=0.7\";s:10:\"user_agent\";s:120:\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36\";s:12:\"session_hash\";N;s:13:\"browser_width\";i:1031;s:14:\"browser_height\";i:954;}', 'a:0:{}', 'a:20:{s:2:\"id\";i:240261922839;s:5:\"email\";s:16:\"oskodkfo@sds.com\";s:17:\"accepts_marketing\";b:0;s:10:\"created_at\";s:25:\"2017-11-28T14:04:43-06:00\";s:10:\"updated_at\";s:25:\"2017-11-28T18:13:05-06:00\";s:10:\"first_name\";s:8:\"sdflksdj\";s:9:\"last_name\";s:8:\"kjsdlkfj\";s:12:\"orders_count\";i:19;s:5:\"state\";s:7:\"invited\";s:11:\"total_spent\";s:4:\"0.00\";s:13:\"last_order_id\";i:161906294807;s:4:\"note\";N;s:14:\"verified_email\";b:1;s:20:\"multipass_identifier\";N;s:10:\"tax_exempt\";b:0;s:5:\"phone\";N;s:4:\"tags\";s:0:\"\";s:15:\"last_order_name\";s:5:\"#1038\";s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/Customer/240261922839\";s:15:\"default_address\";a:17:{s:2:\"id\";i:221485105175;s:11:\"customer_id\";i:240261922839;s:10:\"first_name\";s:8:\"sdflksdj\";s:9:\"last_name\";s:8:\"kjsdlkfj\";s:7:\"company\";N;s:8:\"address1\";s:13:\"123 sdkfsdkfj\";s:8:\"address2\";s:2:\"12\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";N;s:4:\"name\";s:17:\"sdflksdj kjsdlkfj\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}}'),
	(161715912727, 0, 'oskodkfo@sds.com', NULL, '2017-11-28 20:35:08', '2017-11-28 20:35:09', 24, NULL, '65137f2a12c8ea47a84f5601b468c7bd', NULL, 0.00, 0.00, 0, '0.00', 0, 'USD', 'paid', 1, '2.77', 0.00, NULL, 0, '#1024', 'http://wpstest.dev/products/', '/cart/2666408214551:4?access_token=fccd62f405d550a0f0152dc1f9faf9f9&_fd=0&_ga=2.80160279.1446386368.1511750021-833124060.1511750021&attributes[cartID]=shopify-buy.1511899246422.2&attributes[testing]=wasup', NULL, NULL, 0.00, '51d715d83178b040d96679482ff89cb5', NULL, NULL, NULL, NULL, NULL, '2017-11-28 20:35:08', NULL, NULL, 'en', 88312, NULL, NULL, 1024, 'a:1:{i:0;a:3:{s:4:\"code\";s:4:\"FREE\";s:6:\"amount\";s:4:\"2.77\";s:4:\"type\";s:8:\"shipping\";}}', 'a:2:{i:0;a:2:{s:4:\"name\";s:6:\"cartID\";s:5:\"value\";s:27:\"shopify-buy.1511899246422.2\";}i:1;a:2:{s:4:\"name\";s:7:\"testing\";s:5:\"value\";s:5:\"wasup\";}}', 'a:0:{}', 'free', 307705446423, 'web', NULL, 'a:0:{}', '', 'oskodkfo@sds.com', 'https://wpslitetest10.myshopify.com/24007681/orders/65137f2a12c8ea47a84f5601b468c7bd/authenticate?key=56e0c6cf3a245f8e84a4e90621184d0c', 'a:1:{i:0;a:26:{s:2:\"id\";i:195376644119;s:10:\"variant_id\";i:2666408214551;s:5:\"title\";s:8:\"aaaaaaaa\";s:8:\"quantity\";i:4;s:5:\"price\";s:4:\"0.00\";s:3:\"sku\";s:0:\"\";s:13:\"variant_title\";s:0:\"\";s:6:\"vendor\";s:13:\"wpslitetest10\";s:19:\"fulfillment_service\";s:6:\"manual\";s:10:\"product_id\";N;s:17:\"requires_shipping\";b:1;s:7:\"taxable\";b:1;s:9:\"gift_card\";b:0;s:4:\"name\";s:8:\"aaaaaaaa\";s:28:\"variant_inventory_management\";N;s:10:\"properties\";a:0:{}s:14:\"product_exists\";b:0;s:20:\"fulfillable_quantity\";i:4;s:5:\"grams\";i:0;s:14:\"total_discount\";s:4:\"0.00\";s:18:\"fulfillment_status\";N;s:20:\"discount_allocations\";a:0:{}s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/LineItem/195376644119\";s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.0065;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}s:15:\"origin_location\";a:8:{s:2:\"id\";i:28088926231;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:13:\"wpslitetest10\";s:8:\"address1\";s:11:\"123 fsdfsdj\";s:8:\"address2\";s:3:\"123\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55408\";}s:20:\"destination_location\";a:8:{s:2:\"id\";i:102724435991;s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";s:4:\"name\";s:17:\"sdflksdj kjsdlkfj\";s:8:\"address1\";s:13:\"123 sdkfsdkfj\";s:8:\"address2\";s:2:\"12\";s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";}}}', 'a:1:{i:0;a:12:{s:2:\"id\";i:125975363607;s:5:\"title\";s:19:\"First Class Package\";s:5:\"price\";s:4:\"2.77\";s:4:\"code\";s:12:\"FirstPackage\";s:6:\"source\";s:4:\"usps\";s:5:\"phone\";N;s:32:\"requested_fulfillment_service_id\";N;s:17:\"delivery_category\";N;s:18:\"carrier_identifier\";N;s:16:\"discounted_price\";s:4:\"2.77\";s:20:\"discount_allocations\";a:1:{i:0;a:2:{s:6:\"amount\";s:4:\"2.77\";s:26:\"discount_application_index\";i:0;}}s:9:\"tax_lines\";a:3:{i:0;a:3:{s:5:\"title\";s:12:\"MN State Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.06875;}i:1;a:3:{s:5:\"title\";s:19:\"Hennepin County Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.0065;}i:2;a:3:{s:5:\"title\";s:25:\"Minneapolis Municipal Tax\";s:5:\"price\";s:4:\"0.00\";s:4:\"rate\";d:0.005;}}}}', 'a:15:{s:10:\"first_name\";s:8:\"sdflksdj\";s:8:\"address1\";s:13:\"123 sdkfsdkfj\";s:5:\"phone\";N;s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:8:\"kjsdlkfj\";s:8:\"address2\";s:2:\"12\";s:7:\"company\";N;s:8:\"latitude\";d:44.9836543;s:9:\"longitude\";d:-93.2693572;s:4:\"name\";s:17:\"sdflksdj kjsdlkfj\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:15:{s:10:\"first_name\";s:8:\"sdflksdj\";s:8:\"address1\";s:13:\"123 sdkfsdkfj\";s:5:\"phone\";N;s:4:\"city\";s:11:\"Minneapolis\";s:3:\"zip\";s:5:\"55401\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:9:\"last_name\";s:8:\"kjsdlkfj\";s:8:\"address2\";s:2:\"12\";s:7:\"company\";N;s:8:\"latitude\";d:44.9836543;s:9:\"longitude\";d:-93.2693572;s:4:\"name\";s:17:\"sdflksdj kjsdlkfj\";s:12:\"country_code\";s:2:\"US\";s:13:\"province_code\";s:2:\"MN\";}', 'a:0:{}', 'a:6:{s:10:\"browser_ip\";s:14:\"50.203.238.241\";s:15:\"accept_language\";s:32:\"en-US,en;q=0.9,nb;q=0.8,la;q=0.7\";s:10:\"user_agent\";s:120:\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36\";s:12:\"session_hash\";N;s:13:\"browser_width\";i:1031;s:14:\"browser_height\";i:954;}', 'a:0:{}', 'a:20:{s:2:\"id\";i:240261922839;s:5:\"email\";s:16:\"oskodkfo@sds.com\";s:17:\"accepts_marketing\";b:0;s:10:\"created_at\";s:25:\"2017-11-28T14:04:43-06:00\";s:10:\"updated_at\";s:25:\"2017-11-28T18:13:05-06:00\";s:10:\"first_name\";s:8:\"sdflksdj\";s:9:\"last_name\";s:8:\"kjsdlkfj\";s:12:\"orders_count\";i:19;s:5:\"state\";s:7:\"invited\";s:11:\"total_spent\";s:4:\"0.00\";s:13:\"last_order_id\";i:161906294807;s:4:\"note\";N;s:14:\"verified_email\";b:1;s:20:\"multipass_identifier\";N;s:10:\"tax_exempt\";b:0;s:5:\"phone\";N;s:4:\"tags\";s:0:\"\";s:15:\"last_order_name\";s:5:\"#1038\";s:20:\"admin_graphql_api_id\";s:35:\"gid://shopify/Customer/240261922839\";s:15:\"default_address\";a:17:{s:2:\"id\";i:221485105175;s:11:\"customer_id\";i:240261922839;s:10:\"first_name\";s:8:\"sdflksdj\";s:9:\"last_name\";s:8:\"kjsdlkfj\";s:7:\"company\";N;s:8:\"address1\";s:13:\"123 sdkfsdkfj\";s:8:\"address2\";s:2:\"12\";s:4:\"city\";s:11:\"Minneapolis\";s:8:\"province\";s:9:\"Minnesota\";s:7:\"country\";s:13:\"United States\";s:3:\"zip\";s:5:\"55401\";s:5:\"phone\";N;s:4:\"name\";s:17:\"sdflksdj kjsdlkfj\";s:13:\"province_code\";s:2:\"MN\";s:12:\"country_code\";s:2:\"US\";s:12:\"country_name\";s:13:\"United States\";s:7:\"default\";b:1;}}');



/*!40000 ALTER TABLE `wptests_wps_orders` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table wptests_wps_products
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wptests_wps_products`;

CREATE TABLE `wptests_wps_products` (
  `product_id` bigint(255) unsigned NOT NULL,
  `post_id` bigint(100) unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `body_html` longtext COLLATE utf8mb4_unicode_520_ci,
  `handle` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `image` longtext COLLATE utf8mb4_unicode_520_ci,
  `images` longtext COLLATE utf8mb4_unicode_520_ci,
  `vendor` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `product_type` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `published_scope` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `published_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `admin_graphql_api_id` longtext COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

LOCK TABLES `wptests_wps_products` WRITE;
/*!40000 ALTER TABLE `wptests_wps_products` DISABLE KEYS */;

INSERT INTO `wptests_wps_products` (`product_id`, `post_id`, `title`, `body_html`, `handle`, `image`, `images`, `vendor`, `product_type`, `published_scope`, `published_at`, `updated_at`, `created_at`, `admin_graphql_api_id`)
VALUES
	(1402551140375, 10841, 'Olen Koepp', '<p>Aliquam ea debitis facilis.</p>', 'olen-koepp', 'https://cdn.shopify.com/s/files/1/2400/7681/products/modirerumreiciendis.png?v=1534140307', 'a:1:{i:0;O:8:\"stdClass\":11:{s:2:\"id\";i:3840272957463;s:10:\"product_id\";i:1402551140375;s:8:\"position\";i:1;s:10:\"created_at\";s:25:\"2018-08-13T01:05:07-05:00\";s:10:\"updated_at\";s:25:\"2018-08-13T01:05:07-05:00\";s:3:\"alt\";s:38:\"Tempore quod laboriosam provident hic.\";s:5:\"width\";i:300;s:6:\"height\";i:300;s:3:\"src\";s:89:\"https://cdn.shopify.com/s/files/1/2400/7681/products/modirerumreiciendis.png?v=1534140307\";s:11:\"variant_ids\";a:0:{}s:20:\"admin_graphql_api_id\";s:40:\"gid://shopify/ProductImage/3840272957463\";}}', 'Padberg and Sons', 'Outdoors', 'web', '2018-08-13 06:05:05', '2018-08-13 06:10:29', '2018-08-13 06:05:07', 'gid://shopify/Product/1402551140375'),
	(1402551173143, 10551, 'Maryse Keeling', '<p>Distinctio totam accusantium exercitationem omnis. Illo aut recusandae dolore impedit et. Dolorem veniam nobis vero quis.</p>', 'maryse-keeling', 'https://cdn.shopify.com/s/files/1/2400/7681/products/doloremqueculpaet.png?v=1534140310', 'a:1:{i:0;O:8:\"stdClass\":11:{s:2:\"id\";i:3840272990231;s:10:\"product_id\";i:1402551173143;s:8:\"position\";i:1;s:10:\"created_at\";s:25:\"2018-08-13T01:05:09-05:00\";s:10:\"updated_at\";s:25:\"2018-08-13T01:05:10-05:00\";s:3:\"alt\";s:55:\"Et aut veritatis quaerat sed dicta incidunt magnam sed.\";s:5:\"width\";i:300;s:6:\"height\";i:300;s:3:\"src\";s:87:\"https://cdn.shopify.com/s/files/1/2400/7681/products/doloremqueculpaet.png?v=1534140310\";s:11:\"variant_ids\";a:0:{}s:20:\"admin_graphql_api_id\";s:40:\"gid://shopify/ProductImage/3840272990231\";}}', 'Tillman-Kling', 'Jewelry', 'web', '2018-08-13 06:05:08', '2018-08-13 06:10:27', '2018-08-13 06:05:09', 'gid://shopify/Product/1402551173143'),
	(1402551205911, 12242, 'Tyrel Ryan DVM', '<p>Reprehenderit rerum ut accusantium non.</p>', 'tyrel-ryan-dvm', 'https://cdn.shopify.com/s/files/1/2400/7681/products/omnisconsequaturvoluptates.png?v=1534140312', 'a:1:{i:0;O:8:\"stdClass\":11:{s:2:\"id\";i:3840273022999;s:10:\"product_id\";i:1402551205911;s:8:\"position\";i:1;s:10:\"created_at\";s:25:\"2018-08-13T01:05:11-05:00\";s:10:\"updated_at\";s:25:\"2018-08-13T01:05:12-05:00\";s:3:\"alt\";s:50:\"Nam illo aut earum porro placeat aperiam dicta ut.\";s:5:\"width\";i:300;s:6:\"height\";i:300;s:3:\"src\";s:96:\"https://cdn.shopify.com/s/files/1/2400/7681/products/omnisconsequaturvoluptates.png?v=1534140312\";s:11:\"variant_ids\";a:0:{}s:20:\"admin_graphql_api_id\";s:40:\"gid://shopify/ProductImage/3840273022999\";}}', 'Funk, Collier and McKenzie', 'Shoes', 'web', '2018-08-13 06:05:10', '2018-08-13 20:12:16', '2018-08-13 06:05:11', 'gid://shopify/Product/1402551205911'),
	(1402551238679, 10839, 'Mona Trantow', '<p>Soluta ex ea amet. Sed quia quam accusantium temporibus asperiores. Accusamus aut itaque architecto.</p>', 'mona-trantow', 'https://cdn.shopify.com/s/files/1/2400/7681/products/reiciendisinciduntaspernatur.png?v=1534140314', 'a:1:{i:0;O:8:\"stdClass\":11:{s:2:\"id\";i:3840273055767;s:10:\"product_id\";i:1402551238679;s:8:\"position\";i:1;s:10:\"created_at\";s:25:\"2018-08-13T01:05:14-05:00\";s:10:\"updated_at\";s:25:\"2018-08-13T01:05:14-05:00\";s:3:\"alt\";s:41:\"Quam consequuntur temporibus officia est.\";s:5:\"width\";i:300;s:6:\"height\";i:300;s:3:\"src\";s:98:\"https://cdn.shopify.com/s/files/1/2400/7681/products/reiciendisinciduntaspernatur.png?v=1534140314\";s:11:\"variant_ids\";a:0:{}s:20:\"admin_graphql_api_id\";s:40:\"gid://shopify/ProductImage/3840273055767\";}}', 'Wiegand Inc', 'Tools', 'web', '2018-08-13 06:05:12', '2018-08-13 06:10:28', '2018-08-13 06:05:14', 'gid://shopify/Product/1402551238679'),
	(1402551271447, 8331, 'Emmalee Kovacek', '<p>Explicabo reiciendis numquam necessitatibus rerum magnam. Et consequatur atque voluptatem reiciendis.</p>', 'emmalee-kovacek', 'https://cdn.shopify.com/s/files/1/2400/7681/products/quisquiaoptio.png?v=1534140316', 'a:1:{i:0;O:8:\"stdClass\":11:{s:2:\"id\";i:3840273088535;s:10:\"product_id\";i:1402551271447;s:8:\"position\";i:1;s:10:\"created_at\";s:25:\"2018-08-13T01:05:16-05:00\";s:10:\"updated_at\";s:25:\"2018-08-13T01:05:16-05:00\";s:3:\"alt\";s:54:\"Molestias voluptatem est nesciunt magni ducimus minus.\";s:5:\"width\";i:300;s:6:\"height\";i:300;s:3:\"src\";s:83:\"https://cdn.shopify.com/s/files/1/2400/7681/products/quisquiaoptio.png?v=1534140316\";s:11:\"variant_ids\";a:0:{}s:20:\"admin_graphql_api_id\";s:40:\"gid://shopify/ProductImage/3840273088535\";}}', 'Sawayn-Christiansen', 'Shoes', 'web', '2018-08-13 06:05:14', '2018-08-13 06:10:25', '2018-08-13 06:05:16', 'gid://shopify/Product/1402551271447'),
	(1402551304215, 10552, 'Maryse Ward', '<p>Voluptatem possimus totam sapiente qui fuga sit voluptatum.</p>', 'maryse-ward', 'https://cdn.shopify.com/s/files/1/2400/7681/products/illoexcepturiut.png?v=1534140318', 'a:1:{i:0;O:8:\"stdClass\":11:{s:2:\"id\";i:3840273121303;s:10:\"product_id\";i:1402551304215;s:8:\"position\";i:1;s:10:\"created_at\";s:25:\"2018-08-13T01:05:18-05:00\";s:10:\"updated_at\";s:25:\"2018-08-13T01:05:18-05:00\";s:3:\"alt\";s:38:\"Consectetur dolore itaque tempore aut.\";s:5:\"width\";i:300;s:6:\"height\";i:300;s:3:\"src\";s:85:\"https://cdn.shopify.com/s/files/1/2400/7681/products/illoexcepturiut.png?v=1534140318\";s:11:\"variant_ids\";a:0:{}s:20:\"admin_graphql_api_id\";s:40:\"gid://shopify/ProductImage/3840273121303\";}}', 'Satterfield-Blanda', 'Tools', 'web', '2018-08-13 06:05:16', '2018-08-13 06:10:27', '2018-08-13 06:05:17', 'gid://shopify/Product/1402551304215'),
	(1402551369751, 11128, 'Rashawn Bahringer III', '<p>Et totam tenetur dolor aut laboriosam dolor. Labore beatae aut.</p>', 'rashawn-bahringer-iii', 'https://cdn.shopify.com/s/files/1/2400/7681/products/nemolaboreoccaecati.png?v=1534140320', 'a:1:{i:0;O:8:\"stdClass\":11:{s:2:\"id\";i:3840273154071;s:10:\"product_id\";i:1402551369751;s:8:\"position\";i:1;s:10:\"created_at\";s:25:\"2018-08-13T01:05:20-05:00\";s:10:\"updated_at\";s:25:\"2018-08-13T01:05:20-05:00\";s:3:\"alt\";s:27:\"Nisi deleniti est corrupti.\";s:5:\"width\";i:300;s:6:\"height\";i:300;s:3:\"src\";s:89:\"https://cdn.shopify.com/s/files/1/2400/7681/products/nemolaboreoccaecati.png?v=1534140320\";s:11:\"variant_ids\";a:0:{}s:20:\"admin_graphql_api_id\";s:40:\"gid://shopify/ProductImage/3840273154071\";}}', 'Kuhlman LLC', 'Electronics', 'web', '2018-08-13 06:05:18', '2018-08-13 06:10:30', '2018-08-13 06:05:20', 'gid://shopify/Product/1402551369751'),
	(1402551402519, 10287, 'Jamar Kirlin', '<p>Dolorum quam qui alias dolore ut. Numquam voluptatibus et voluptatem adipisci sequi ipsum.</p>', 'jamar-kirlin', 'https://cdn.shopify.com/s/files/1/2400/7681/products/etprovidentbeatae.png?v=1534140322', 'a:1:{i:0;O:8:\"stdClass\":11:{s:2:\"id\";i:3840273186839;s:10:\"product_id\";i:1402551402519;s:8:\"position\";i:1;s:10:\"created_at\";s:25:\"2018-08-13T01:05:22-05:00\";s:10:\"updated_at\";s:25:\"2018-08-13T01:05:22-05:00\";s:3:\"alt\";s:35:\"Nostrum enim est quod quas qui est.\";s:5:\"width\";i:300;s:6:\"height\";i:300;s:3:\"src\";s:87:\"https://cdn.shopify.com/s/files/1/2400/7681/products/etprovidentbeatae.png?v=1534140322\";s:11:\"variant_ids\";a:0:{}s:20:\"admin_graphql_api_id\";s:40:\"gid://shopify/ProductImage/3840273186839\";}}', 'Kulas Inc', 'Beauty', 'web', '2018-08-13 06:05:21', '2018-08-13 06:10:26', '2018-08-13 06:05:22', 'gid://shopify/Product/1402551402519'),
	(1402551435287, 10838, 'Miss Judson Bruen', '<p>Ab quae recusandae expedita.</p>', 'miss-judson-bruen', 'https://cdn.shopify.com/s/files/1/2400/7681/products/nisiestbeatae.png?v=1534140326', 'a:1:{i:0;O:8:\"stdClass\":11:{s:2:\"id\";i:3840273219607;s:10:\"product_id\";i:1402551435287;s:8:\"position\";i:1;s:10:\"created_at\";s:25:\"2018-08-13T01:05:26-05:00\";s:10:\"updated_at\";s:25:\"2018-08-13T01:05:26-05:00\";s:3:\"alt\";s:29:\"Excepturi maxime sit aliquam.\";s:5:\"width\";i:300;s:6:\"height\";i:300;s:3:\"src\";s:83:\"https://cdn.shopify.com/s/files/1/2400/7681/products/nisiestbeatae.png?v=1534140326\";s:11:\"variant_ids\";a:0:{}s:20:\"admin_graphql_api_id\";s:40:\"gid://shopify/ProductImage/3840273219607\";}}', 'Batz, Marquardt and Hoppe', 'Jewelry', 'web', '2018-08-13 06:05:23', '2018-08-13 06:10:28', '2018-08-13 06:05:26', 'gid://shopify/Product/1402551435287'),
	(1402551468055, 8063, 'Carlotta Bauch', '<p>Consequuntur pariatur id harum sit et. Et provident nemo quasi vitae ut veritatis.</p>', 'carlotta-bauch', 'https://cdn.shopify.com/s/files/1/2400/7681/products/illumullamlaboriosam.png?v=1534140328', 'a:1:{i:0;O:8:\"stdClass\":11:{s:2:\"id\";i:3840273252375;s:10:\"product_id\";i:1402551468055;s:8:\"position\";i:1;s:10:\"created_at\";s:25:\"2018-08-13T01:05:28-05:00\";s:10:\"updated_at\";s:25:\"2018-08-13T01:05:28-05:00\";s:3:\"alt\";s:65:\"Magnam voluptatibus sed quia fugiat iste maiores natus accusamus.\";s:5:\"width\";i:300;s:6:\"height\";i:300;s:3:\"src\";s:90:\"https://cdn.shopify.com/s/files/1/2400/7681/products/illumullamlaboriosam.png?v=1534140328\";s:11:\"variant_ids\";a:0:{}s:20:\"admin_graphql_api_id\";s:40:\"gid://shopify/ProductImage/3840273252375\";}}', 'Wyman LLC', 'Shoes', 'web', '2018-08-13 06:05:26', '2018-08-13 06:10:24', '2018-08-13 06:05:27', 'gid://shopify/Product/1402551468055');


/*!40000 ALTER TABLE `wptests_wps_products` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table wptests_wps_settings_connection
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wptests_wps_settings_connection`;

CREATE TABLE `wptests_wps_settings_connection` (
  `id` bigint(100) unsigned NOT NULL AUTO_INCREMENT,
  `domain` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `js_access_token` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `access_token` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `app_id` int(20) unsigned DEFAULT NULL,
  `webhook_id` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `nonce` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `api_key` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `shared_secret` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

LOCK TABLES `wptests_wps_settings_connection` WRITE;
/*!40000 ALTER TABLE `wptests_wps_settings_connection` DISABLE KEYS */;

INSERT INTO `wptests_wps_settings_connection` (`id`, `domain`, `js_access_token`, `access_token`, `app_id`, `webhook_id`, `nonce`, `api_key`, `password`, `shared_secret`)
VALUES
	(1, 'wpslitetest10.myshopify.com', '4f49b8cbc1efad6cac1ed2803ca5f342', '', 6, '', 'a0f1c2f52d', 'f44101a71ab5fb3cdf838f9f5e695595', 'bb63cfa64231d0d513d895c19f0f54d8', '76ddc3ae6c55f28f7c5a6dc48937e329');


/*!40000 ALTER TABLE `wptests_wps_settings_connection` ENABLE KEYS */;
UNLOCK TABLES;

# Dump of table wptests_wps_settings_general
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wptests_wps_settings_general`;

CREATE TABLE `wptests_wps_settings_general` (
  `id` bigint(100) NOT NULL AUTO_INCREMENT,
  `url_products` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'products',
  `url_collections` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'collections',
  `url_webhooks` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'https://wpstest.test/wp',
  `num_posts` bigint(100) DEFAULT NULL,
  `styles_all` tinyint(1) DEFAULT '1',
  `styles_core` tinyint(1) DEFAULT '0',
  `styles_grid` tinyint(1) DEFAULT '0',
  `plugin_name` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'WP Shopify',
  `plugin_textdomain` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'wps',
  `plugin_version` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '1.2.0',
  `plugin_author` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'WP Shopify',
  `price_with_currency` tinyint(1) DEFAULT '0',
  `cart_loaded` tinyint(1) DEFAULT '1',
  `title_as_alt` tinyint(1) DEFAULT '0',
  `selective_sync_all` tinyint(1) DEFAULT '1',
  `selective_sync_products` tinyint(1) DEFAULT '0',
  `sync_by_collections` longtext COLLATE utf8mb4_unicode_520_ci,
  `selective_sync_collections` tinyint(1) DEFAULT '0',
  `selective_sync_customers` tinyint(1) DEFAULT '0',
  `selective_sync_orders` tinyint(1) DEFAULT '0',
  `selective_sync_shop` tinyint(1) DEFAULT '1',
  `products_link_to_shopify` tinyint(1) DEFAULT '0',
  `show_breadcrumbs` tinyint(1) DEFAULT '0',
  `hide_pagination` tinyint(1) DEFAULT '0',
  `is_free` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_pro` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `related_products_show` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `related_products_sort` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'random',
  `related_products_amount` tinyint(1) unsigned NOT NULL DEFAULT '4',
  `allow_insecure_webhooks` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `save_connection_only` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `app_uninstalled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

LOCK TABLES `wptests_wps_settings_general` WRITE;
/*!40000 ALTER TABLE `wptests_wps_settings_general` DISABLE KEYS */;

INSERT INTO `wptests_wps_settings_general` (`id`, `url_products`, `url_collections`, `url_webhooks`, `num_posts`, `styles_all`, `styles_core`, `styles_grid`, `plugin_name`, `plugin_textdomain`, `plugin_version`, `plugin_author`, `price_with_currency`, `cart_loaded`, `title_as_alt`, `selective_sync_all`, `selective_sync_products`, `sync_by_collections`, `selective_sync_collections`, `selective_sync_customers`, `selective_sync_orders`, `selective_sync_shop`, `products_link_to_shopify`, `show_breadcrumbs`, `hide_pagination`, `is_free`, `is_pro`, `related_products_show`, `related_products_sort`, `related_products_amount`, `allow_insecure_webhooks`, `save_connection_only`, `app_uninstalled`)
VALUES
	(1, 'products', 'collections', 'https://wpstest.test/wp', 12, 1, 0, 0, 'WP Shopify', 'wps', '15.52334.0', 'WP Shopify', 0, 1, 0, 1, 0, '', 0, 0, 0, 1, 0, 1, 0, 1, 0, 1, 'random', 3, 0, 0, 0);



/*!40000 ALTER TABLE `wptests_wps_settings_general` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table wptests_wps_settings_license
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wptests_wps_settings_license`;

CREATE TABLE `wptests_wps_settings_license` (
  `license_key` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `is_local` tinyint(1) unsigned DEFAULT NULL,
  `expires` datetime DEFAULT NULL,
  `site_count` int(20) unsigned DEFAULT NULL,
  `checksum` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `customer_email` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `customer_name` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `item_name` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `license` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `license_limit` int(20) DEFAULT NULL,
  `payment_id` int(20) DEFAULT NULL,
  `success` tinyint(1) DEFAULT NULL,
  `nonce` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `activations_left` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `is_free` tinyint(1) unsigned DEFAULT NULL,
  `is_pro` tinyint(1) unsigned DEFAULT NULL,
  `beta_access` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`license_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

LOCK TABLES `wptests_wps_settings_license` WRITE;
/*!40000 ALTER TABLE `wptests_wps_settings_license` DISABLE KEYS */;

INSERT INTO `wptests_wps_settings_license` (`license_key`, `is_local`, `expires`, `site_count`, `checksum`, `customer_email`, `customer_name`, `item_name`, `license`, `license_limit`, `payment_id`, `success`, `nonce`, `activations_left`, `is_free`, `is_pro`, `beta_access`)
VALUES
	('82800d32c9514256e9564462e1728553', 0, '2018-08-13 07:58:56', 11, 'ae1b81a3b5a3616207b0a7c9dc099fb0', 'hello@wpshop.io', 'Trial License', 'WP Shopify', 'valid', 20, 193540, 1, '', '9', 0, 0, 0);

/*!40000 ALTER TABLE `wptests_wps_settings_license` ENABLE KEYS */;
UNLOCK TABLES;


DROP TABLE IF EXISTS `wptests_wps_settings_syncing`;

CREATE TABLE `wptests_wps_settings_syncing` (
  `id` bigint(100) unsigned NOT NULL AUTO_INCREMENT,
  `is_syncing` tinyint(1) DEFAULT '0',
  `syncing_totals_shop` bigint(100) unsigned DEFAULT NULL,
  `syncing_totals_smart_collections` bigint(100) unsigned DEFAULT NULL,
  `syncing_totals_custom_collections` bigint(100) unsigned DEFAULT NULL,
  `syncing_totals_products` bigint(100) unsigned DEFAULT NULL,
  `syncing_totals_collects` bigint(100) unsigned DEFAULT NULL,
  `syncing_totals_orders` bigint(100) unsigned DEFAULT NULL,
  `syncing_totals_customers` bigint(100) unsigned DEFAULT NULL,
  `syncing_totals_webhooks` bigint(100) unsigned DEFAULT NULL,
  `syncing_step_total` bigint(100) unsigned DEFAULT '0',
  `syncing_step_current` bigint(100) unsigned DEFAULT '0',
  `syncing_current_amounts_shop` bigint(100) unsigned DEFAULT NULL,
  `syncing_current_amounts_smart_collections` bigint(100) unsigned DEFAULT NULL,
  `syncing_current_amounts_custom_collections` bigint(100) unsigned DEFAULT NULL,
  `syncing_current_amounts_products` bigint(100) unsigned DEFAULT NULL,
  `syncing_current_amounts_collects` bigint(100) unsigned DEFAULT NULL,
  `syncing_current_amounts_orders` bigint(100) unsigned DEFAULT NULL,
  `syncing_current_amounts_customers` bigint(100) unsigned DEFAULT NULL,
  `syncing_current_amounts_webhooks` bigint(100) unsigned DEFAULT NULL,
  `syncing_start_time` bigint(100) unsigned DEFAULT NULL,
  `syncing_end_time` bigint(100) unsigned DEFAULT NULL,
  `syncing_errors` longtext COLLATE utf8mb4_unicode_520_ci,
  `syncing_warnings` longtext COLLATE utf8mb4_unicode_520_ci,
  `finished_webhooks_deletions` tinyint(1) DEFAULT '0',
  `finished_product_posts_relationships` tinyint(1) DEFAULT '0',
  `finished_collection_posts_relationships` tinyint(1) DEFAULT '0',
  `finished_data_deletions` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;


LOCK TABLES `wptests_wps_settings_syncing` WRITE;
/*!40000 ALTER TABLE `wptests_wps_settings_syncing` DISABLE KEYS */;

INSERT INTO `wptests_wps_settings_syncing` (`id`, `is_syncing`, `syncing_totals_shop`, `syncing_totals_smart_collections`, `syncing_totals_custom_collections`, `syncing_totals_products`, `syncing_totals_collects`, `syncing_totals_orders`, `syncing_totals_customers`, `syncing_totals_webhooks`, `syncing_step_total`, `syncing_step_current`, `syncing_current_amounts_shop`, `syncing_current_amounts_smart_collections`, `syncing_current_amounts_custom_collections`, `syncing_current_amounts_products`, `syncing_current_amounts_collects`, `syncing_current_amounts_orders`, `syncing_current_amounts_customers`, `syncing_current_amounts_webhooks`, `syncing_start_time`, `syncing_end_time`, `syncing_errors`, `syncing_warnings`, `finished_webhooks_deletions`, `finished_product_posts_relationships`, `finished_collection_posts_relationships`, `finished_data_deletions`)
VALUES
	(1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 0, 1, 0, 0);

/*!40000 ALTER TABLE `wptests_wps_settings_syncing` ENABLE KEYS */;
UNLOCK TABLES;



# Dump of table wptests_wps_shop
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wptests_wps_shop`;

CREATE TABLE `wptests_wps_shop` (
  `id` bigint(100) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  `myshopify_domain` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  `shop_owner` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  `phone` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  `email` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  `address1` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  `address2` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  `city` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  `zip` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  `country` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  `country_code` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  `country_name` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  `currency` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  `latitude` smallint(20) DEFAULT '0',
  `longitude` smallint(20) DEFAULT '0',
  `money_format` varchar(200) COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  `money_with_currency_format` varchar(200) COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  `weight_unit` varchar(20) COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  `primary_locale` varchar(20) COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  `province` varchar(20) COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  `province_code` varchar(20) COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  `timezone` varchar(200) COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `domain` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  `source` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  `customer_email` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  `iana_timezone` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  `taxes_included` tinyint(1) DEFAULT '0',
  `tax_shipping` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  `county_taxes` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  `plan_display_name` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  `plan_name` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  `has_discounts` tinyint(1) DEFAULT '0',
  `has_gift_cards` tinyint(1) DEFAULT '0',
  `google_apps_domain` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  `google_apps_login_enabled` tinyint(1) DEFAULT '0',
  `money_in_emails_format` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  `money_with_currency_in_emails_format` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  `eligible_for_payments` tinyint(1) DEFAULT '0',
  `requires_extra_payments_agreement` tinyint(1) DEFAULT '0',
  `password_enabled` tinyint(1) DEFAULT '0',
  `has_storefront` tinyint(1) DEFAULT '0',
  `eligible_for_card_reader_giveaway` tinyint(1) DEFAULT '0',
  `finances` tinyint(1) DEFAULT '0',
  `primary_location_id` tinyint(1) DEFAULT '0',
  `checkout_api_supported` tinyint(1) DEFAULT '0',
  `multi_location_enabled` tinyint(1) DEFAULT '0',
  `setup_required` tinyint(1) DEFAULT '0',
  `force_ssl` tinyint(1) DEFAULT '0',
  `pre_launch_enabled` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24007682 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

LOCK TABLES `wptests_wps_shop` WRITE;
/*!40000 ALTER TABLE `wptests_wps_shop` DISABLE KEYS */;

INSERT INTO `wptests_wps_shop` (`id`, `name`, `myshopify_domain`, `shop_owner`, `phone`, `email`, `address1`, `address2`, `city`, `zip`, `country`, `country_code`, `country_name`, `currency`, `latitude`, `longitude`, `money_format`, `money_with_currency_format`, `weight_unit`, `primary_locale`, `province`, `province_code`, `timezone`, `created_at`, `updated_at`, `domain`, `source`, `customer_email`, `iana_timezone`, `taxes_included`, `tax_shipping`, `county_taxes`, `plan_display_name`, `plan_name`, `has_discounts`, `has_gift_cards`, `google_apps_domain`, `google_apps_login_enabled`, `money_in_emails_format`, `money_with_currency_in_emails_format`, `eligible_for_payments`, `requires_extra_payments_agreement`, `password_enabled`, `has_storefront`, `eligible_for_card_reader_giveaway`, `finances`, `primary_location_id`, `checkout_api_supported`, `multi_location_enabled`, `setup_required`, `force_ssl`, `pre_launch_enabled`)
VALUES
	(24007681, 'wpslitetest10 😃', 'wpslitetest10.myshopify.com', 'Andrew Robbins', '6128128561', 'andrew@simpleblend.net', '614 N 1st N ', '#606', 'Minneapolis', '55402', 'US', 'US', 'United States', 'USD', 45, -93, '${{amount}}', '${{amount}} USD', 'lb', 'en', 'Minnesota', 'MN', '(GMT-06:00) America/Chicago', '2017-09-27 18:08:03', '2018-08-03 22:31:08', 'wpslitetest10.myshopify.com', '', 'andrew@simpleblend.net', 'America/Chicago', 0, '0', '0', 'Basic Shopify', 'basic', 0, 0, '', 0, '${{amount}}', '${{amount}} USD', 0, 0, 0, 0, 0, 0, 127, 0, 0, 0, 0, 0);



/*!40000 ALTER TABLE `wptests_wps_shop` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table wptests_wps_tags
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wptests_wps_tags`;

CREATE TABLE `wptests_wps_tags` (
  `tag_id` bigint(100) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(100) DEFAULT NULL,
  `post_id` bigint(100) DEFAULT NULL,
  `tag` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14215 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

LOCK TABLES `wptests_wps_tags` WRITE;
/*!40000 ALTER TABLE `wptests_wps_tags` DISABLE KEYS */;

INSERT INTO `wptests_wps_tags` (`tag_id`, `product_id`, `post_id`, `tag`)
VALUES
	(1, 1355610488855, 0, 'dolorem'),
	(2, 1355610488855, 0, 'doloremque'),
	(3, 1355610488855, 0, 'provident'),
	(4, 1355611570199, 0, 'quo'),
	(5, 1355611570199, 0, 'quod'),
	(6, 1355611570199, 0, 'voluptatem'),
	(7, 1355613044759, 0, 'iure'),
	(8, 1355613044759, 0, 'tempore'),
	(9, 1355613044759, 0, 'temporibus'),
	(10, 1355609997335, 0, 'beatae'),
	(11, 1355609997335, 0, 'distinctio'),
	(12, 1355609997335, 0, 'reprehenderit'),
	(13, 1345544192023, 0, 'atque'),
	(14, 1345544192023, 0, 'commodi'),
	(15, 1345544192023, 0, 'natus'),
	(16, 1355611275287, 0, 'expedita'),
	(17, 1355611275287, 0, 'non'),
	(18, 1355611275287, 0, 'occaecati'),
	(19, 1355610095639, 0, 'consequuntur'),
	(20, 1355610095639, 0, 'ullam'),
	(21, 1355610095639, 0, 'unde'),
	(22, 1355610062871, 0, 'amet'),
	(23, 1355610062871, 0, 'explicabo'),
	(24, 1355610062871, 0, 'qui'),
	(25, 1355609833495, 0, 'sunt'),
	(26, 1355609833495, 0, 'veniam'),
	(27, 1355609833495, 0, 'voluptatibus'),
	(28, 1355612913687, 0, 'excepturi'),
	(29, 1355612913687, 0, 'nisi'),
	(30, 1355612913687, 0, 'quo'),
	(31, 1345544060951, 0, 'dolor'),
	(32, 1345544060951, 0, 'ratione'),
	(33, 1345544060951, 0, 'velit'),
	(34, 1355612782615, 0, 'culpa'),
	(35, 1355612782615, 0, 'cupiditate'),
	(36, 1355612782615, 0, 'et'),
	(37, 1355610030103, 0, 'corporis'),
	(38, 1355610030103, 0, 'ipsa'),
	(39, 1355610030103, 0, 'soluta'),
	(40, 1355613110295, 0, 'commodi'),
	(41, 1355613110295, 0, 'tenetur'),
	(42, 1355613110295, 0, 'totam'),
	(43, 1345544028183, 0, 'a'),
	(44, 1345544028183, 0, 'ea'),
	(45, 1345544028183, 0, 'quia'),
	(46, 1355611406359, 0, 'enim'),
	(47, 1355611406359, 0, 'quia'),
	(48, 1355611406359, 0, 'totam'),
	(49, 1355613175831, 0, 'architecto'),
	(50, 1355613175831, 0, 'dignissimos'),
	(51, 1355613175831, 0, 'minus'),
	(52, 1355611897879, 0, 'dolorem'),
	(53, 1355611897879, 0, 'ipsam'),
	(54, 1355611897879, 0, 'nesciunt'),
	(55, 1345543962647, 0, 'aut'),
	(56, 1345543962647, 0, 'exercitationem'),
	(57, 1345543962647, 0, 'sed'),
	(58, 1355613437975, 0, 'doloremque'),
	(59, 1355613437975, 0, 'neque'),
	(60, 1355613437975, 0, 'ratione'),
	(61, 1355610325015, 0, 'dolor'),
	(62, 1355610325015, 0, 'et'),
	(63, 1355610325015, 0, 'ex'),
	(64, 1355612323863, 0, 'ea'),
	(65, 1355612323863, 0, 'molestias'),
	(66, 1355612323863, 0, 'rerum'),
	(67, 1355612487703, 0, 'consectetur'),
	(68, 1355612487703, 0, 'repellat'),
	(69, 1355612487703, 0, 'ut'),
	(70, 1355609931799, 0, 'aliquam'),
	(71, 1355609931799, 0, 'ut'),
	(72, 1355609931799, 0, 'vel'),
	(73, 1355612356631, 0, 'magni'),
	(74, 1355612356631, 0, 'nihil'),
	(75, 1355612356631, 0, 'voluptatibus'),
	(76, 1355611996183, 0, 'distinctio'),
	(77, 1355611996183, 0, 'ut'),
	(78, 1355611996183, 0, 'voluptas'),
	(79, 1355612717079, 0, 'dolorum'),
	(80, 1355612717079, 0, 'ea'),
	(81, 1355612717079, 0, 'laudantium'),
	(82, 1345544126487, 0, 'enim'),
	(83, 1345544126487, 0, 'explicabo'),
	(84, 1345544126487, 0, 'quidem'),
	(85, 1355612979223, 0, 'cupiditate'),
	(86, 1355612979223, 0, 'fugiat'),
	(87, 1355612979223, 0, 'modi'),
	(88, 1355610882071, 0, 'deserunt'),
	(89, 1355610882071, 0, 'dolorem'),
	(90, 1355610882071, 0, 'laborum'),
	(91, 1355611734039, 0, 'nihil'),
	(92, 1355611734039, 0, 'quis'),
	(93, 1355611734039, 0, 'sed'),
	(94, 1355612946455, 0, 'ipsum'),
	(95, 1355612946455, 0, 'qui'),
	(96, 1355612946455, 0, 'velit'),
	(97, 1355611701271, 0, 'fugit'),
	(98, 1355611701271, 0, 'qui'),
	(99, 1355611701271, 0, 'unde'),
	(100, 1355612258327, 0, 'id'),
	(101, 1355612258327, 0, 'nesciunt'),
	(102, 1355612258327, 0, 'tempore'),
	(103, 1355611963415, 0, 'cupiditate'),
	(104, 1355611963415, 0, 'incidunt'),
	(105, 1355611963415, 0, 'tempora'),
	(106, 1355612291095, 0, 'dolor'),
	(107, 1355612291095, 0, 'exercitationem'),
	(108, 1355612291095, 0, 'vitae'),
	(109, 1345543733271, 0, 'ipsum'),
	(110, 1345543733271, 0, 'laboriosam'),
	(111, 1345543733271, 0, 'qui'),
	(112, 1355612422167, 0, 'alias'),
	(113, 1355612422167, 0, 'exercitationem'),
	(114, 1355612422167, 0, 'voluptatem'),
	(115, 1355610128407, 0, 'aliquid'),
	(116, 1355610128407, 0, 'et'),
	(117, 1355610128407, 0, 'minima'),
	(118, 1355612553239, 0, 'aut'),
	(119, 1355612553239, 0, 'libero'),
	(120, 1355612553239, 0, 'repellat'),
	(121, 1355612192791, 0, 'rerum'),
	(122, 1355612192791, 0, 'sunt'),
	(123, 1355612192791, 0, 'ut'),
	(124, 1355611930647, 0, 'quisquam'),
	(125, 1355611930647, 0, 'saepe'),
	(126, 1355611930647, 0, 'sit'),
	(127, 1355613667351, 0, 'ipsam'),
	(128, 1355613667351, 0, 'molestiae'),
	(129, 1355613667351, 0, 'quia'),
	(130, 1355610816535, 0, 'dolorem'),
	(131, 1355610816535, 0, 'dolorum'),
	(132, 1355610816535, 0, 'quibusdam'),
	(133, 1355611340823, 0, 'consequatur'),
	(134, 1355611340823, 0, 'et'),
	(135, 1355611340823, 0, 'maxime'),
	(136, 1355613503511, 0, 'et'),
	(137, 1355613503511, 0, 'eum'),
	(138, 1355613503511, 0, 'voluptatem'),
	(139, 1355611078679, 0, 'enim'),
	(140, 1355611078679, 0, 'in'),
	(141, 1355611078679, 0, 'qui'),
	(142, 1355609964567, 0, 'et'),
	(143, 1355609964567, 0, 'maxime'),
	(144, 1355609964567, 0, 'sed'),
	(145, 1355609767959, 0, 'facere'),
	(146, 1355609767959, 0, 'harum'),
	(147, 1355609767959, 0, 'quis'),
	(148, 1355613634583, 0, 'earum'),
	(149, 1355613634583, 0, 'tempore'),
	(150, 1355613634583, 0, 'unde'),
	(151, 1345543897111, 0, 'consequatur'),
	(152, 1345543897111, 0, 'eum'),
	(153, 1345543897111, 0, 'voluptatem'),
	(154, 1355610750999, 0, 'optio'),
	(155, 1355610750999, 0, 'qui'),
	(156, 1355610750999, 0, 'quos'),
	(157, 1355610652695, 0, 'sequi'),
	(158, 1355610652695, 0, 'soluta'),
	(159, 1355610652695, 0, 'velit'),
	(160, 1355610619927, 0, 'maiores'),
	(161, 1355610619927, 0, 'tempore'),
	(162, 1355610619927, 0, 'ut'),
	(163, 1355613306903, 0, 'enim'),
	(164, 1355613306903, 0, 'labore'),
	(165, 1355613306903, 0, 'tenetur'),
	(166, 1355609866263, 0, 'corrupti'),
	(167, 1355609866263, 0, 'dolorem'),
	(168, 1355609866263, 0, 'quae'),
	(169, 1355611209751, 0, 'aliquid'),
	(170, 1355611209751, 0, 'impedit'),
	(171, 1355611209751, 0, 'voluptatibus'),
	(172, 1355613372439, 0, 'doloremque'),
	(173, 1355613372439, 0, 'libero'),
	(174, 1355613372439, 0, 'minus'),
	(175, 1355610423319, 0, 'accusamus'),
	(176, 1355610423319, 0, 'aut'),
	(177, 1355610423319, 0, 'tempora'),
	(178, 1355610685463, 0, 'in'),
	(179, 1355610685463, 0, 'labore'),
	(180, 1355610685463, 0, 'voluptates'),
	(181, 1355610357783, 0, 'ea'),
	(182, 1355610357783, 0, 'reiciendis'),
	(183, 1355610357783, 0, 'saepe'),
	(184, 1355612454935, 0, 'quia'),
	(185, 1355612454935, 0, 'veniam'),
	(186, 1355611471895, 0, 'impedit'),
	(187, 1355611471895, 0, 'omnis'),
	(188, 1355611471895, 0, 'repudiandae'),
	(189, 1355612749847, 0, 'ipsum'),
	(190, 1355612749847, 0, 'ut'),
	(191, 1355612749847, 0, 'veniam'),
	(192, 1355610914839, 0, 'occaecati'),
	(193, 1355610914839, 0, 'quidem'),
	(194, 1355610914839, 0, 'velit'),
	(195, 1355612586007, 0, 'blanditiis'),
	(196, 1355612586007, 0, 'deleniti'),
	(197, 1355612586007, 0, 'recusandae'),
	(198, 1355611242519, 0, 'illo'),
	(199, 1355611242519, 0, 'quia'),
	(200, 1355611242519, 0, 'repellendus'),
	(201, 1355610783767, 0, 'debitis'),
	(202, 1355610783767, 0, 'est'),
	(203, 1355610783767, 0, 'impedit'),
	(204, 1355610292247, 0, 'modi'),
	(205, 1355610292247, 0, 'qui'),
	(206, 1355610292247, 0, 'sit'),
	(207, 1355610980375, 0, 'fugiat'),
	(208, 1355610980375, 0, 'quae'),
	(209, 1355610980375, 0, 'quia'),
	(210, 1355613274135, 0, 'itaque'),
	(211, 1355613274135, 0, 'qui'),
	(212, 1355613274135, 0, 'quia'),
	(213, 1355611045911, 0, 'dolores'),
	(214, 1355611045911, 0, 'est'),
	(215, 1355611045911, 0, 'vitae'),
	(216, 1355610947607, 0, 'et'),
	(217, 1355610947607, 0, 'magni'),
	(218, 1355610947607, 0, 'possimus'),
	(219, 1355613077527, 0, 'ab'),
	(220, 1355613077527, 0, 'quam'),
	(221, 1355613077527, 0, 'quis'),
	(222, 1355611144215, 0, 'aut'),
	(223, 1355611144215, 0, 'blanditiis'),
	(224, 1355611144215, 0, 'dolores'),
	(225, 1345543602199, 0, 'alias'),
	(226, 1345543602199, 0, 'sed'),
	(227, 1345543602199, 0, 'voluptate'),
	(228, 1345543798807, 0, 'deleniti'),
	(229, 1345543798807, 0, 'modi'),
	(230, 1345543798807, 0, 'quis'),
	(231, 1355612651543, 0, 'adipisci'),
	(232, 1355612651543, 0, 'eius'),
	(233, 1355612651543, 0, 'excepturi'),
	(234, 1355609800727, 0, 'in'),
	(235, 1355609800727, 0, 'itaque'),
	(236, 1355609800727, 0, 'vel'),
	(237, 1355611668503, 0, 'fugit'),
	(238, 1355611668503, 0, 'iusto'),
	(239, 1355611668503, 0, 'qui'),
	(240, 1355611537431, 0, 'architecto'),
	(241, 1355611537431, 0, 'aut'),
	(242, 1355611537431, 0, 'soluta'),
	(243, 1355613700119, 0, 'et'),
	(244, 1355613700119, 0, 'ipsum'),
	(245, 1355613700119, 0, 'soluta'),
	(246, 1355612684311, 0, 'est'),
	(247, 1355612684311, 0, 'et'),
	(248, 1355612684311, 0, 'temporibus'),
	(249, 1355611111447, 0, 'nemo'),
	(250, 1355611111447, 0, 'omnis'),
	(251, 1355611111447, 0, 'quia'),
	(252, 1345543929879, 0, 'facilis'),
	(253, 1345543929879, 0, 'optio'),
	(254, 1345543929879, 0, 'porro'),
	(255, 1355613536279, 0, 'aut'),
	(256, 1355613536279, 0, 'deleniti'),
	(257, 1355613536279, 0, 'voluptas'),
	(258, 1345543700503, 0, 'at'),
	(259, 1345543700503, 0, 'nesciunt'),
	(260, 1345543700503, 0, 'rerum'),
	(261, 1355611504663, 0, 'earum'),
	(262, 1355611504663, 0, 'placeat'),
	(263, 1355611504663, 0, 'voluptates'),
	(264, 1355609899031, 0, 'alias'),
	(265, 1355609899031, 0, 'aliquam'),
	(266, 1355609899031, 0, 'autem'),
	(267, 1355613601815, 0, 'adipisci'),
	(268, 1355613601815, 0, 'iste'),
	(269, 1355613601815, 0, 'sed'),
	(270, 1355613470743, 0, 'expedita'),
	(271, 1355613470743, 0, 'quisquam'),
	(272, 1355613470743, 0, 'voluptatem'),
	(273, 1355612848151, 0, 'doloribus'),
	(274, 1355612848151, 0, 'sapiente'),
	(275, 1355612848151, 0, 'sit'),
	(276, 1355612094487, 0, 'id'),
	(277, 1355612094487, 0, 'praesentium'),
	(278, 1355612094487, 0, 'sed'),
	(279, 1355611013143, 0, 'minus'),
	(280, 1355611013143, 0, 'necessitatibus'),
	(281, 1355611013143, 0, 'voluptatum'),
	(282, 1345544159255, 0, 'magnam'),
	(283, 1345544159255, 0, 'quaerat'),
	(284, 1345544159255, 0, 'rerum'),
	(285, 1345543569431, 0, 'accusamus'),
	(286, 1345543569431, 0, 'blanditiis'),
	(287, 1345543569431, 0, 'quam'),
	(288, 1355611439127, 0, 'dicta'),
	(289, 1355611439127, 0, 'excepturi'),
	(290, 1355611439127, 0, 'nihil'),
	(291, 1355611308055, 0, 'et'),
	(292, 1355611308055, 0, 'facere'),
	(293, 1355611308055, 0, 'iusto'),
	(294, 1355613569047, 0, 'id'),
	(295, 1355613569047, 0, 'provident'),
	(296, 1355613569047, 0, 'voluptatum'),
	(297, 1355613339671, 0, 'aut'),
	(298, 1355613339671, 0, 'distinctio'),
	(299, 1355613339671, 0, 'dolor'),
	(300, 1355612061719, 0, 'odio'),
	(301, 1355612061719, 0, 'rerum'),
	(302, 1355612061719, 0, 'sapiente'),
	(303, 1345543831575, 0, 'iusto'),
	(304, 1345543831575, 0, 'quas'),
	(305, 1345543831575, 0, 'rem'),
	(306, 1355612880919, 0, 'doloremque'),
	(307, 1355612880919, 0, 'et'),
	(308, 1355612880919, 0, 'numquam'),
	(309, 1355612160023, 0, 'officia'),
	(310, 1355612160023, 0, 'qui'),
	(311, 1355612160023, 0, 'velit'),
	(312, 1355612225559, 0, 'doloremque'),
	(313, 1355612225559, 0, 'excepturi'),
	(314, 1355612225559, 0, 'maiores'),
	(315, 1355612618775, 0, 'aliquid'),
	(316, 1355612618775, 0, 'libero'),
	(317, 1355612618775, 0, 'similique'),
	(318, 1355613011991, 0, 'quis'),
	(319, 1355613011991, 0, 'reprehenderit'),
	(320, 1355613011991, 0, 'ullam'),
	(321, 1355612815383, 0, 'eos'),
	(322, 1355612815383, 0, 'vitae'),
	(323, 1355612815383, 0, 'voluptas'),
	(324, 1355611635735, 0, 'eius'),
	(325, 1355611635735, 0, 'et'),
	(326, 1355611635735, 0, 'recusandae'),
	(327, 1345543634967, 0, 'a'),
	(328, 1345543634967, 0, 'doloremque'),
	(329, 1345543634967, 0, 'magnam'),
	(330, 1355610718231, 0, 'non'),
	(331, 1355610718231, 0, 'sed'),
	(332, 1355610718231, 0, 'voluptas'),
	(333, 1355611373591, 0, 'porro'),
	(334, 1355611373591, 0, 'quod'),
	(335, 1355611373591, 0, 'repellat'),
	(336, 1355610554391, 0, 'debitis'),
	(337, 1355610554391, 0, 'eum'),
	(338, 1355610554391, 0, 'voluptatem'),
	(339, 1355610259479, 0, 'in'),
	(340, 1355610259479, 0, 'libero'),
	(341, 1355610259479, 0, 'quos'),
	(342, 1355610161175, 0, 'ab'),
	(343, 1355610161175, 0, 'iure'),
	(344, 1355610161175, 0, 'minus');


/*!40000 ALTER TABLE `wptests_wps_tags` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table wptests_wps_variants
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wptests_wps_variants`;

CREATE TABLE `wptests_wps_variants` (
  `id` bigint(100) unsigned NOT NULL DEFAULT '0',
  `product_id` bigint(100) DEFAULT NULL,
  `image_id` bigint(100) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `price` decimal(12,2) DEFAULT '0.00',
  `compare_at_price` decimal(12,2) DEFAULT '0.00',
  `position` int(20) DEFAULT NULL,
  `option1` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `option2` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `option3` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `taxable` tinyint(1) DEFAULT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `inventory_policy` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `inventory_quantity` bigint(20) DEFAULT NULL,
  `old_inventory_quantity` bigint(20) DEFAULT NULL,
  `inventory_management` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `fulfillment_service` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `barcode` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `weight` int(20) DEFAULT NULL,
  `weight_unit` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `requires_shipping` tinyint(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `admin_graphql_api_id` longtext COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

LOCK TABLES `wptests_wps_variants` WRITE;
/*!40000 ALTER TABLE `wptests_wps_variants` DISABLE KEYS */;

INSERT INTO `wptests_wps_variants` (`id`, `product_id`, `image_id`, `title`, `price`, `compare_at_price`, `position`, `option1`, `option2`, `option3`, `taxable`, `sku`, `inventory_policy`, `inventory_quantity`, `old_inventory_quantity`, `inventory_management`, `fulfillment_service`, `barcode`, `weight`, `weight_unit`, `requires_shipping`, `created_at`, `updated_at`, `admin_graphql_api_id`)
VALUES
	(12518343901207, 1402551140375, NULL, 'Extra Small', 5.47, NULL, 1, 'Extra Small', NULL, NULL, 1, '', 'deny', 5, 5, NULL, 'manual', '', 1, 'lb', 1, '2018-08-13 06:05:07', '2018-08-13 06:05:07', 'gid://shopify/ProductVariant/12518343901207'),
	(12518343933975, 1402551140375, NULL, 'Small', 83.54, 5.47, 2, 'Small', NULL, NULL, 1, 'olen-koepp-small', 'deny', 4, 4, NULL, 'manual', '', 1, 'lb', 1, '2018-08-13 06:05:07', '2018-08-13 06:05:07', 'gid://shopify/ProductVariant/12518343933975'),
	(12518343966743, 1402551140375, NULL, 'Medium', 96.71, 5.47, 3, 'Medium', NULL, NULL, 1, 'olen-koepp-medium', 'deny', 2, 2, NULL, 'manual', '', 1, 'lb', 1, '2018-08-13 06:05:07', '2018-08-13 06:05:07', 'gid://shopify/ProductVariant/12518343966743'),
	(12518343999511, 1402551140375, NULL, 'Large', 6.74, 5.47, 4, 'Large', NULL, NULL, 1, 'olen-koepp-large', 'deny', 1, 1, NULL, 'manual', '', 0, 'lb', 1, '2018-08-13 06:05:07', '2018-08-13 06:05:07', 'gid://shopify/ProductVariant/12518343999511'),
	(12518344392727, 1402551173143, NULL, 'Extra Small', 40.86, NULL, 1, 'Extra Small', NULL, NULL, 1, '', 'deny', 0, 0, NULL, 'manual', '', 2, 'lb', 1, '2018-08-13 06:05:10', '2018-08-13 06:05:10', 'gid://shopify/ProductVariant/12518344392727'),
	(12518344425495, 1402551173143, NULL, 'Small', 66.45, 40.86, 2, 'Small', NULL, NULL, 1, 'maryse-keeling-small', 'deny', 2, 2, NULL, 'manual', '', 0, 'lb', 1, '2018-08-13 06:05:10', '2018-08-13 06:05:10', 'gid://shopify/ProductVariant/12518344425495'),
	(12518344458263, 1402551173143, NULL, 'Medium', 79.50, 40.86, 3, 'Medium', NULL, NULL, 1, 'maryse-keeling-medium', 'deny', 0, 0, NULL, 'manual', '', 1, 'lb', 1, '2018-08-13 06:05:10', '2018-08-13 06:05:10', 'gid://shopify/ProductVariant/12518344458263'),
	(12518344491031, 1402551173143, NULL, 'Large', 66.63, 40.86, 4, 'Large', NULL, NULL, 1, 'maryse-keeling-large', 'deny', 3, 3, NULL, 'manual', '', 1, 'lb', 1, '2018-08-13 06:05:10', '2018-08-13 06:05:10', 'gid://shopify/ProductVariant/12518344491031'),
	(12518344556567, 1402551205911, NULL, 'Extra Small', 0.00, NULL, 1, 'Extra Small', NULL, NULL, 1, '', 'deny', 0, 0, NULL, 'manual', '', 1, 'lb', 1, '2018-08-13 06:05:11', '2018-08-13 20:46:21', 'gid://shopify/ProductVariant/12518344556567'),
	(12518344589335, 1402551205911, NULL, 'Small', 32.34, 46.60, 2, 'Small', NULL, NULL, 1, 'tyrel-ryan-dvm-small', 'deny', 9, 9, NULL, 'manual', '', 1, 'lb', 1, '2018-08-13 06:05:11', '2018-08-13 06:05:11', 'gid://shopify/ProductVariant/12518344589335');


/*!40000 ALTER TABLE `wptests_wps_variants` ENABLE KEYS */;
UNLOCK TABLES;


/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
