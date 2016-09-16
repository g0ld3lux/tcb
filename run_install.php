<?php
error_reporting(0);
include 'include/mysql-wrapper.php';
session_start();
include('include/language.php');
include('library.php');

$_SESSION['lang'] = $lang;

if($_POST['install']) {
    $connection = mysql_connect("{$_POST['DB_HOST']}", "{$_POST['DB_USER']}", "{$_POST['DB_PASS']}") or noDbConnection();
    mysql_select_db("{$_POST['DB_NAME']}", $connection) or noDbConnection();

  	$baseUrl = rtrim(getBaseUrl(),"/");
  	
  	$content = '<?php
  	error_reporting(0);
  	include \'include/mysql-wrapper.php\';
  	session_start();

  	define("DB_HOST", \''.$_POST['DB_HOST'].'\');
  	define("DB_USER", \''.$_POST['DB_USER'].'\');
  	define("DB_PASS", \''.$_POST['DB_PASS'].'\');
  	define("DB_NAME", \''.$_POST['DB_NAME'].'\');
  	define("SITE_URL", \''.$baseUrl.'\');
  	define("UPLOADS_PATH", getcwd()."/uploads");
  	define("WEB_TITLE", "Turbo Course Builder | ");
  	define("DATE_FORMAT", "m/d/Y");
  	define("DATETIME_FORMAT", "m/d/Y h:i a");
  	define("DEFAULT_TIMEZONE", "America/Chicago");

  	date_default_timezone_set(DEFAULT_TIMEZONE);

  	if(DB_NAME) {
  		$connection = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die(mysql_error());
  		mysql_select_db(DB_NAME, $connection) or die(mysql_error());
  	}
  	include(\'include/language.php\');
  	include(\'library.php\');
    include(\'functions.php\');
    ?>';

  	$myfile = fopen("config.php", "wr+") or die("Unable to write in config.php file!");
  	fwrite($myfile, $content);
  	fclose($myfile);

  	createTables();
  	$admin_id = createAdminAccount($_POST);
    setDefaultSettings($admin_id, $baseUrl);

  	header("Location:login.php");
  	exit();
} else {
  	$_SESSION['error_message'] = $_SESSION['lang']['nodbconnection_error_message'];
  	header("Location:install.php");
  	exit();
}

function noDbConnection() {
  	$_SESSION['error_message'] = $_SESSION['lang']['nodbconnection_error_message'];
  	header("Location:install.php");
  	exit();
}

function createTables() {
	$sql = 'CREATE TABLE IF NOT EXISTS `cb_admin` (
  `admin_pid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`admin_pid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

  mysql_query($sql) or die(mysql_error());

	$sql = 'CREATE TABLE IF NOT EXISTS `cb_domains` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';

  mysql_query($sql) or die(mysql_error());

	$sql = 'CREATE TABLE IF NOT EXISTS `cb_embed_master` (
  `embed_pid` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL,
  `embed_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `lock_code_status` tinyint(4) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`embed_pid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;';

  mysql_query($sql) or die(mysql_error());

  $sql = 'CREATE TABLE IF NOT EXISTS `cb_lesson_master` (
  `lesson_pid` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL,
  `lesson_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `lesson_text` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `lesson_status` tinyint(1) NOT NULL,
  `display_links` tinyint(1) NOT NULL COMMENT "0 = No, 1 = Yes",
  `display_files` tinyint(1) NOT NULL COMMENT "0 = No, 1 = Yes",
  `status` tinyint(1) NOT NULL COMMENT "0 = No online view, 1 = available online view",
  `sort_order` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`lesson_pid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;';

  mysql_query($sql) or die(mysql_error());

	$sql = 'CREATE TABLE IF NOT EXISTS `cb_lesson_files` (
  `file_pid` int(11) NOT NULL AUTO_INCREMENT,
  `lesson_id` int(11) NOT NULL,
  `file_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `file_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `uploaded_file` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `file_type` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT "0 = No online view, 1 = available online view",
  `sort_order` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`file_pid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;';

  mysql_query($sql) or die(mysql_error());

	$sql = 'CREATE TABLE IF NOT EXISTS `cb_lesson_links` (
  `link_pid` int(11) NOT NULL AUTO_INCREMENT,
  `lesson_id` int(11) NOT NULL,
  `link_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `link_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT "0 = No online view, 1 = available online view",
  `sort_order` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`link_pid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;';

  mysql_query($sql) or die(mysql_error());

	$sql = 'CREATE TABLE IF NOT EXISTS `cb_settings` (
  `settings_pid` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL,
  `lesson_title_color` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `lesson_title_size` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `lesson_title_font` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `lesson_text_color` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `lesson_text_size` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `lesson_text_font` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `lesson_text_aligment` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`settings_pid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;';

  mysql_query($sql) or die(mysql_error());

  $sql = 'CREATE TABLE IF NOT EXISTS `cb_visits` (
 `visit_pid` int(11) NOT NULL AUTO_INCREMENT,
 `entity_id` int(11) NOT NULL,
 `entity_type` tinyint(1) NOT NULL COMMENT "1 = Lesson, 2 = Link, 3 = File",
 `ip_address` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
 `date_created` datetime NOT NULL,
 PRIMARY KEY (`visit_pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

  mysql_query($sql) or die(mysql_error());
}

function createAdminAccount($data) {
    $sql = "insert into cb_admin set username='".addslashes($data['Username'])."',email='".addslashes($data['Email'])."',password='".md5(addslashes($data['Password']))."',date_created='".date("Y-m-d H:i:s")."'";
	  mysql_query($sql) or die(mysql_error());
    return mysql_insert_id();
}

function setDefaultSettings($admin_id, $baseUrl) {
    $sql = "insert into cb_settings set admin_id='".$admin_id."',lesson_title_color='#000000',lesson_title_size='10px',lesson_title_font='".$_SESSION['lang']['font_arial']."',lesson_text_color='#000000',lesson_text_size='10px',lesson_text_font='".$_SESSION['lang']['font_arial']."',lesson_text_aligment='".$_SESSION['lang']['text_left']."'";
    mysql_query($sql) or die(mysql_error());
    $sql = "insert into cb_domains set domain='".$baseUrl."'";
    mysql_query($sql) or die(mysql_error());
}
?>