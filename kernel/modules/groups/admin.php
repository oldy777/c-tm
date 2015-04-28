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
// ADD GROUP
case 'add':
  $result['title'] = $config['msg']['add'];
  $args['inner'] = array();
  // request
  if($_SERVER['REQUEST_METHOD']=='POST')
  {
    // save
    $args['title'] = trim($_POST['title']);
    $args['content'] = trim($_POST['content']);
    if($args['title']=='') { $errors['title'] = true; }
    $args['inner'] = (is_array($_POST['inner'])? $_POST['inner'] : array());
    $args['blocked'] = (bool)$_POST['blocked'];
    // ok
    if(empty($errors))
    {
      $q->format("INSERT INTO groups SET id='%d',title='%s',content='%s',blocked='%d',created='%d',updated='%d'", $kernel['db']->next_id('groups'), $args['title'], $args['content'], $args['blocked'], time(), time());
      $args['id'] = $kernel['db']->last_id('groups');
      foreach($args['inner'] as $i) if($i > 0)
       { $q->format("INSERT INTO groups_members SET id_group='%d',id_user='%d'", $args['id'], $i); }
      // redirect ok
      http_redirect($kernel['db']->affected_rows()? "/admin/?mod=groups&ok" : "/admin/?mod=groups");
    }
  }
  // users list
  $q->format("SELECT u.id,u.login,u.blocked FROM users as u ORDER BY login");
  $args['users'] = $q->get_allrows();
  $q->free_result();
  // template
  $args['action'] = '/admin/?mod=groups&act=add';
  // commands
  $result['commands'][] = array('path'=>'/admin/?mod=groups', 'title'=>$config['msg']['list']);
  // template
  $template = 'addedit.phpt';
break;
// EDIT GROUP
case 'edit':
  $result['title'] = 'Редактирование';
  $args['id'] = (int)$_GET['id'];
  // request
  if($_SERVER['REQUEST_METHOD']=='POST')
  {
    // save
    $args['title'] = trim($_POST['title']);
    $args['content'] = trim($_POST['content']);
    if($args['title']=='') { $errors['title'] = true; }
    $args['inner'] = (is_array($_POST['inner'])? $_POST['inner'] : array());
    $args['blocked'] = (bool)$_POST['blocked'];
    // ok
    if(empty($errors))
    {
      $q->format("UPDATE groups SET title='%s',content='%s',blocked='%d',updated='%d' WHERE id='%d'", $args['title'], $args['content'], $args['blocked'], time(), $args['id']);
      $q->format("DELETE FROM groups_members WHERE id_group='%d'", $args['id']);
      foreach($args['inner'] as $i) if($i > 0)
       { $q->format("INSERT INTO groups_members SET id_group='%d',id_user='%d'", $args['id'], $i); }
      // ok redirect
      http_redirect($kernel['db']->affected_rows()? "/admin/?mod=groups&ok" : "/admin/?mod=groups");
    }
  }
  else
  {
    // load
    $q->format("SELECT id,title,content,blocked FROM groups WHERE id='%d'", $args['id']);
    $args = $q->get_row();
    $q->free_result();
    $args['inner'] = array();
    $q->format("SELECT id_user as id FROM groups_members WHERE id_group='%d'", $args['id']);
    while($r = $q->get_row()) { $args['inner'][] = $r['id']; }
    $q->free_result();
    // not exists
    if(empty($args)) { http_redirect("/admin/?mod=groups&error"); }
  }
  // users
  $q->format("SELECT u.id,u.login,u.blocked FROM users as u ORDER BY login");
  $args['users'] = $q->get_allrows();
  $q->free_result();
  // form action
  $args['action'] = "/admin/?mod=groups&act=edit&id=". $args['id'];
  // commands
  $result['commands'][] = array('path'=>'/admin/?mod=groups', 'title'=>$config['msg']['list']);
  $result['commands'][] = array('path'=>'/admin/?mod=groups&act=add', 'title'=>$config['msg']['add']);
  // template
  $template = 'addedit.phpt';
break;
// DELETE GROUP
case 'delete':
  $args['id'] = (int)$_GET['id'];
  // ok
  if($args['id'] > 0)
  {
    $q->format("DELETE FROM groups_members WHERE id_group='%d'", $args['id']);
    $q->format("DELETE FROM modules_perm_groups WHERE id_group='%d'", $args['id']);
    $q->format("DELETE FROM groups WHERE id='%d'", $args['id']);
    // ok redirect
    if($kernel['db']->affected_rows()) { http_redirect("/admin/?mod=groups&ok"); }
  }
  // redirect
  http_redirect('/admin/?mod=groups&error');
break;
// GROUPS LIST
default:
  // title
  $result['title'] = $config['msg']['list'];
  // order
  $args['order'] = trim($_GET['order']);
  $args['desc'] = intval((bool)$_GET['desc']);
  // order session
  if($args['order']=='')
  {
    $args['order'] = $_SESSION['groups']['order'];
    $args['desc'] = $_SESSION['groups']['desc'];
  }
  // check order
  if(!in_array($args['order'], array('id', 'title', 'members', 'created', 'updated')))
  {
    $args['order'] = 'id';
    $args['desc'] = true;
  }
  // memorize order
  $_SESSION['groups']['order'] = $args['order'];
  $_SESSION['groups']['desc'] = $args['desc'];
  // list
  $q->format("SELECT g.id,g.title,g.created,g.updated,g.blocked,COUNT(m.id_user) as members FROM groups as g LEFT JOIN groups_members as m ON (m.id_group=g.id) GROUP BY g.id ORDER BY %s %s", $args['order'], ($args['desc']? 'DESC' : 'ASC'));
  $args['items'] = $q->get_allrows();
  $q->free_result();
  // commands
  $result['commands'][] = array('path'=>'/admin/?mod=groups&act=add', 'title'=>$config['msg']['add']);
  // template
  $template = 'list.phpt';
}

// SUCCESSFULL
$args['ok'] = $result['ok'] = isset($_GET['ok']);

// ERROR
$args['error'] = $result['error'] = isset($_GET['error']);

// TEMPLATE
if($template!='') { template(dirname(__FILE__). '/templates/'. $template, $args, $errors); }

// FINNALY
return $result;
?>