<?php
setlocale(LC_ALL, 'ru_RU.UTF-8');

ini_set('session.name', 'sid');
ini_set('session.use_cookies', true);
ini_set('session.use_trans_sid', false);
ini_set('session.use_only_cookies', true);
ini_set('session.cookie_lifetime', 0);
ini_set('session.cookie_path', "/");
ini_set('session.gc_divisor', 10);
ini_set('session.gc_probability', 1); // 1 of 10 request
ini_set('session.gc_maxlifetime', 1800);
session_name('sid');

$config = array();

$config['chmod']    = 0777;
$config['chown']    = 0;

$config['debug']['use']        = true;
$config['debug']['extra']    = true;
$config['debug']['addr']    = array('localhost');

$config['db']['lib']        = 'mysql.php';
$config['db']['class']        = 'db_mysql';
$config['db']['host']        = '91.218.228.116';
$config['db']['user']        = 'base';
$config['db']['passwd']        = '1OLbKjRQ';
$config['db']['name']        = 'base';
$config['db']['charset']    = 'utf8';
$config['db']['debug']        = true;

$config['auth']['forget_expire']    = 3600*48;
$config['auth']['register_expire']    = 3600*48;
$config['auth']['login_expire']        = 3600*24*12;


$config['files']['ext']            = array('doc','xls','docx','xlsx','rar','zip','jpeg','jpg','gif','png','swf','js','pdf','txt','html','htm','css','csv');

$config['session']['remote_addr']    = false;
$config['session']['user_agent']    = false;

$config['tmp']['gc']            = true;
$config['tmp']['gc_maxlifetime']    = 3600*24*7*2;


return $config;
?>
