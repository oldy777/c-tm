<?php
include_once($_SERVER['DOCUMENT_ROOT']. "/kernel.php");
$kernel['mode'] = 'file';
include_once(KERNEL_DIR. '/kernel.php');

$q = $kernel['db']->query();
$q->format("SELECT * FROM modules_files WHERE id='%d'", intval($_GET['id']));
$r = $q->get_row();
$q->free_result();

if(empty($r) || !is_file($_SERVER["DOCUMENT_ROOT"]. $r['path']))
{
  header('HTTP/1.1 404');
  exit(0);
}

$filename = str_replace('"', '', $r['name']);
if(!preg_match('~'. preg_quote($r['ext']). '$~', $r['name'])) { $filename.= '.'. $r['ext']; }

$fullpath = $_SERVER["DOCUMENT_ROOT"]. $r['path'];

if(!is_file($fullpath))
{
  header('HTTP/1.1 404');
  exit(0);
}

//http_redirect($r['path']);

header("Content-Type: ". $r['mime']);
header("Content-Disposition: attachment; filename=\"$filename\"");
readfile($fullpath);
flush();

?>