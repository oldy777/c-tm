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
$config['db']['host']        = 'localhost';
$config['db']['user']        = 'root';
$config['db']['passwd']        = '';
$config['db']['name']        = 'baseEngine';
$config['db']['charset']    = 'utf8';
$config['db']['debug']        = true;

$config['auth']['forget_expire']    = 3600*48;
$config['auth']['register_expire']    = 3600*48;
$config['auth']['login_expire']        = 3600*24*12;

$config['editor']['tidy']    = false;
$config['editor']['toolbar']    = array('formatblock','separator','cut','copy','paste','delete','word','removeformat','separator','bold','italic','underline','strike','sub','sup','separator','left','center','right','justify','separator','orderedlist','unorderedlist','hr','indent','outdent','separator','link','color','bgcolor','entity','img_ins','img_prop');
$config['editor']['toolbarex']    = array('table_create','separator','table_cell_head','table_col_del','table_row_del','separator','table_prop','table_cell_prop','separator','table_row_ins','table_col_ins','table_cell_merge_down','table_cell_merge_right','table_cell_split_horz','table_cell_split_vert');
$config['editor']['css']    = '/style.css';

$config['files']['ext']            = array('doc','xls','rar','zip','jpeg','jpg','gif','png','swf','js','pdf','txt','html','htm','css','csv');

$config['session']['remote_addr']    = false;
$config['session']['user_agent']    = false;

$config['tmp']['gc']            = true;
$config['tmp']['gc_maxlifetime']    = 3600*24*7*2;


return $config;
?>
