<?php
// INIT
$result = array();
$result['title'] = '';
$result['commands'] = array();
$q = $kernel['db']->query();
$action = trim($_GET['act']);
$args = array();
$errors = array();
$template = '';

// ACTIONS
switch($action)
{
// USERS
case 'users':
  // title
  $result['title'] = $config['msg']['users'];
  // module
  $q->format("SELECT name as id,title FROM modules WHERE name='%s'", trim($_GET['id']));
  $args = $q->get_row();
  $q->free_result();
  // not exists
  if(empty($args)) { http_redirect("/admin/?mod=perm"); }
  // request
  if($_SERVER['REQUEST_METHOD']=='POST')
  {
    // save
    $args['inner'] = (is_array($_POST['inner'])? $_POST['inner'] : array());
    $q->format("DELETE FROM modules_perm WHERE module='%s'", $args['id']);
    foreach($args['inner'] as $i) if($i > 0)
     { $q->format("INSERT INTO modules_perm SET module='%s',id_user='%d'", $args['id'], $i); }
    // redirect ok
    http_redirect($kernel['db']->affected_rows()? "/admin/?mod=perm&ok" : "/admin/?mod=perm");
  }
  else
  {
    // load
    $args['inner'] = array();
    $q->format("SELECT id_user FROM modules_perm WHERE module='%s'", $args['id']);
    while($r = $q->get_row()) { $args['inner'][] = $r['id_user']; }
    $q->free_result();
  }
  // users
  $q->format("SELECT u.id,u.login,u.blocked FROM users as u ORDER BY login");
  $args['users'] = $q->get_allrows();
  $q->free_result();
  // commands
  $result['commands'][] = array('path'=>'/admin/?mod=perm', 'title'=>$config['msg']['list']);
  // template
  $template = 'users.phpt';
break;
// GROUPS
case 'groups':
  // title
  $result['title'] = $config['msg']['groups'];
  // module
  $q->format("SELECT name as id,title FROM modules WHERE name='%s'", trim($_GET['id']));
  $args = $q->get_row();
  $q->free_result();
  // not exists
  if(empty($args)) { http_redirect("/admin/?mod=perm"); }
  // request
  if($_SERVER['REQUEST_METHOD']=='POST')
  {
    // save
    $args['inner'] = (is_array($_POST['inner'])? $_POST['inner'] : array());
    $q->format("DELETE FROM modules_perm_groups WHERE module='%s'", $args['id']);
    foreach($args['inner'] as $i) if($i > 0)
     { $q->format("INSERT INTO modules_perm_groups SET module='%s',id_group='%d'", $args['id'], $i); }
    // redirect ok
    http_redirect($kernel['db']->affected_rows()? "/admin/?mod=perm&ok" : "/admin/?mod=perm");
  }
  else
  {
    // load
    $args['inner'] = array();
    $q->format("SELECT id_group FROM modules_perm_groups WHERE module='%s'", $args['id']);
    while($r = $q->get_row()) { $args['inner'][] = $r['id_group']; }
    $q->free_result();
  }
  // groups
  $q->format("SELECT g.id,g.title,g.blocked FROM groups as g ORDER BY title");
  $args['groups'] = $q->get_allrows();
  $q->free_result();
  // commands
  $result['commands'][] = array('path'=>'/admin/?mod=perm', 'title'=>$config['msg']['list']);
  // template
  $template = 'groups.phpt';
break;
// LIST
default:
  // title
  $result['title'] = $config['msg']['list'];
  // users list
  $q->format("SELECT u.login as name,p.module FROM modules_perm as p INNER JOIN users as u ON (p.id_user=u.id)");
  $args['users'] = $q->get_allrows();
  $q->free_result();
  // groups list
  $q->format("SELECT g.title as name,p.module FROM modules_perm_groups as p INNER JOIN groups as g ON (p.id_group=g.id)");
  $args['groups'] = $q->get_allrows();
  $q->free_result();
  // modules list
  $args['items'] = array();
  $q->format("SELECT name,title,hidden,descr FROM modules ORDER BY title");
  while($r = $q->get_row())
  {
    $r['users'] = array();
    foreach($args['users'] as $i)
     if($r['name']==$i['module'])
      { $r['users'][] = $i['name']; }
    $r['groups'] = array();
    foreach($args['groups'] as $i)
     if($r['name']==$i['module'])
      { $r['groups'][] = $i['name']; }
    $args['items'][] = $r;
  }
  $q->free_result();
  // template
  $template = 'main.phpt';
}

// SUCCESSFULL
$args['ok'] = $result['ok'] = isset($_GET['ok']);

// TEMPLATE
if($template!='') { template(dirname(__FILE__). '/templates/'. $template, $args, $errors); }

// FINALY
return $result;
?>