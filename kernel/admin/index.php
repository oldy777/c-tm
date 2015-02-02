<?php
header('Content-type: text/html; charset=utf-8');

include_once(INCLUDE_DIR. '/flush.php'); 

if($kernel['id_user'] <= 0) // not login
{
  $_SESSION['REQUEST_URI'] = $_SERVER['REQUEST_URI'];
  http_redirect('/admin/login.php');
}

function ob_admin($text) { return str_replace('/admin/?', '/admin/index.php?', $text); }

$args = array();
$errors = array();

$args['title'] = 'Стартовая страница';
$args['login'] = $kernel['login'];
$args['version'] = $kernel['version'];
$args['mod'] = '';

$errors['perm'] = false;
/* @var $q query_mysql */
$q = &$kernel['db']->query();

$mod = str_replace(array('/', '\\'), '', trim($_GET['mod'])); // anti hack
$modpath = '';

if($mod!='')
{
  $modpath = MODULES_DIR. '/'. $mod. '/admin.php';
  if(file_exists($modpath))
  {
    // is registred
    $q->query(sprintf("SELECT m.* FROM modules as m WHERE m.name='%s'", $mod));
    $m = $q->get_row();
    $q->free_result();
    if(empty($m)) { trigger_error("Admin module:${mod} not registred!", E_USER_ERROR); }
    $args['title'] = $m['title'];
    $args['descr'] = $m['descr'];
  }
  else
  {
    $modpath = '';
    $errors['notfound'] = true;
//    http_redirect('/admin/');
  }
}

// check perm
if($modpath!='' && $kernel['id_user']!=1) // root full access
{
  // user
  $q->format("SELECT id_user FROM modules_perm WHERE module='%s' AND id_user='%d'", $mod, $kernel['id_user']);
  $r = $q->get_row();
  $q->free_result();
  // group
  if(empty($r))
  {
    $q->format("SELECT p.id_group FROM modules_perm_groups as p,groups_members as m,groups as g WHERE m.id_group=p.id_group AND p.id_group=g.id AND NOT g.blocked AND p.module='%s' AND m.id_user='%d' LIMIT 1", $mod, $kernel['id_user']);
    $r = $q->get_row();
    $q->free_result();
    if(empty($r)) { $errors['perm'] = true; }
  }
}

$args['mod_parents'] = array(); // for breadcrums
// run module
if(!$errors['perm'] && $modpath!='')
{
  ob_start('ob_admin');
  $result = module($mod. '/admin.php');
  if(!isset($result['indep']) && isset($_GET['indep'])) { $result['indep'] = intval((bool)$_GET['indep']); }
  $args['ok'] = $result['ok'];
  $args['error'] = $result['error'];
  $args['subtitle'] = $result['title'];
  $args['commands'] = $result['commands'];
  $args['fullpath'] = $result['fullpath'];
  $args['help'] = $result['help'];
  $args['mod'] = $mod;
  if($m['parent_id']!=0)
  {
      $arr = $out = array();
      $q->query("SELECT m.* FROM modules m");
      $tmp = $q->get_allrows();
      foreach ($tmp as $v) {
          $arr[$v['id']] = $v;
      }
      
      $args['mod_parents'] = getTreeBranch($arr,$m['parent_id'],$out);
      if($args['mod_parents']) $args['mod_parents'] = array_reverse ($args['mod_parents']);
  }
  settype($args['commands'], 'array');
  $args['content'] = ob_get_contents();
  ob_clean();
  if($result['indep']) { echo $args['content']; exit;  }
}

$submod=explode("_",$mod);

if(sizeof($submod)>1){
  $q->query("select * from modules where name='".current($submod)."'");
  $args['submod']=$q->get_row();
}

$q->query("SELECT bg FROM users WHERE id = ".$kernel['id_user']);
$kernel['bg'] = $q->get_cell();

// list modules
if($kernel['id_user']!=1)
{
  $q->format("SELECT m.*,IF(m.name='%s'||m.name='%s',1,0) as current FROM modules as m INNER JOIN modules_perm as p ON (p.module=m.name AND p.id_user='%d') WHERE NOT m.hidden ORDER BY m.position,m.title", $submod[0],$mod, $kernel['id_user']);
  while($r = $q->get_row()) { $args['modules'][ $r['name'] ] = $r; }
  $q->free_result();
  // groups
  $q->format("SELECT m.*,IF(m.name='%s'||m.name='%s',1,0) as current FROM modules as m INNER JOIN groups_members as gm ON (gm.id_user='%d') INNER JOIN modules_perm_groups as pg ON (pg.module=m.name AND gm.id_group=pg.id_group) INNER JOIN groups as g ON (g.id=pg.id_group AND (NOT g.blocked)) WHERE NOT m.hidden ORDER BY m.position,m.title", $submod[0], $mod, $kernel['id_user']);
  while($r = $q->get_row()) { $args['modules'][ $r['name'] ] = $r; }
  if($q->num_rows() > 0)
  {
    usort($args['modules'], create_function('$a,$b','return strcmp($a["title"],$b["title"]);'));
  }
  $q->free_result();
}
else // root full access
{
  $q->query(sprintf("SELECT m.*,IF(m.name='%s'||m.name='%s',1,0) as current FROM modules as m WHERE NOT m.hidden ORDER BY m.position,m.title", $submod[0],$mod));
  $args['modules'] = $q->get_allrows();
  $q->free_result();
}

if(empty($args['modules']) || !is_array($args['modules']))
{
  $args['modules'] = array();
  $errors['perm'] = true;
}

template(ADMIN_DIR. '/templates/index.phpt', $args, $errors);
?>