<?php

//
include_once(INCLUDE_DIR. '/valid.php');

// INIT
$result = array();
$result['title'] = '';
$result['commands'] = array();
$q = &$kernel['db']->query();
$action = trim($_GET['act']);
$args = array();
$errors = array();
$template = '';

// ACTIONS
switch($action)
{
// ADD USER
case 'add';
  // title
  $result['title'] = $config['msg']['add'];
  // request
  if($_SERVER['REQUEST_METHOD']=='POST')
  {
    // request
    $args['login'] = trim($_POST['login']);
    $args['email'] = trim($_POST['email']);
    $args['name'] = trim($_POST['name']);
    $args['passwd'] = $_POST['passwd'];
    $args['passwd2'] = $_POST['passwd2'];
    $args['blocked'] = (bool)$_POST['blocked'];
    // check
    if($args['login']=='') { $errors['login'] = true; }
    if($args['email']=='' || !is_email($args['email'])) { $errors['email'] = true; }
    if($args['name']=='') { $errors['name'] = true; }
    if(strlen($args['passwd']) < 6) { $errors['passwd'] = true; }
    if($args['passwd']!=$args['passwd2']) { $errors['passwd2'] = true; }
    if(!$errors['login'] && is_login($args['login'])) { $errors['unique'] = true; }
    // ok
    if(empty($errors))
    {
      // save
      $values = array();
      $values['id'] = $kernel['db']->next_id('users');
      $values['login'] = $args['login'];
      $values['passwd'] = md5($args['passwd']);
      $values['email'] = $args['email'];
      $values['name'] = $args['name'];
      $values['blocked'] = $args['blocked'];
      $values['created'] = time();
      $values['updated'] = 0;
      $q->format("INSERT INTO users SET %s", $values);
      // redirect
      http_redirect($kernel['db']->affected_rows()? "/admin/?mod=users&ok" : "/admin/?mod=users");
    }
  }
  // commands
  $result['commands'][] = array('path'=>'/admin/?mod=users', 'title'=>$config['msg']['list']);
  // template
  $template = 'add.phpt';
break;
// EDIT USER
case 'edit';
  // title
  $result['title'] = $config['msg']['edit'];
  // request
  $args['id'] = intval($_GET['id']);
  if($_SERVER['REQUEST_METHOD']=='POST' && $args['id'] > 0)
  {
    // request
    $args['email'] = trim($_POST['email']);
    $args['name'] = trim($_POST['name']);
    $args['passwd'] = $_POST['passwd'];
    $args['passwd2'] = $_POST['passwd2'];
    $args['blocked'] = (bool)$_POST['blocked'];
    // check
    if($args['email']=='' || !is_email($args['email'])) { $errors['email'] = true; }
    if($args['name']=='') { $errors['name'] = true; }
    if($args['passwd']!='')
    {
      if(strlen($args['passwd']) < 6) { $errors['passwd'] = true; }
      if($args['passwd']!=$args['passwd2']) { $errors['passwd2'] = true; }
    }
    if($args['id']==1) { $args['blocked'] =  false; } // not root
    // ok
    if(empty($errors))
    {
      // save
      $values = array();
      if($args['passwd']) { $values['passwd'] = md5($args['passwd']); }
      $values['email'] = $args['email'];
      $values['name'] = $args['name'];
      $values['blocked'] = $args['blocked'];
      $values['updated'] = time();
      $q->format("UPDATE users SET %s WHERE id='%d'", $values, $args['id']);
      // redirect
sleep(1);
      http_redirect($kernel['db']->affected_rows()? "/admin/?mod=users&ok" : "/admin/?mod=users");
    }
    // login by id
    $q->format("SELECT login FROM users WHERE id='%d'", $args['id']);
    $r = $q->get_row();
    $q->free_result();
    // not exists
    if(empty($args)) { http_redirect("/admin/?mod=users&error"); }
    $args['login'] = $r['login'];
  }
  else
  {
    // load data
    $q->format("SELECT * FROM users WHERE id='%d'", $args['id']);
    $args = $q->get_row();
    $q->free_result();
    // not exists
    if(empty($args)) { http_redirect("/admin/?mod=users&error"); }
  }
  // not root
  if($args['id']==1) { $args['blocked'] = false; }
  // commnads
  $result['commands'][] = array('path'=>'/admin/?mod=users', 'title'=>$config['msg']['list']);
  // template
  $template = 'edit.phpt';
break;

// DELETE USER
case 'delete';
  $args['id'] = intval($_GET['id']);
  // ok
  if($args['id'] > 1) // not root
  {
    $q->format("DELETE FROM sessions WHERE id_user='%d' AND id_user > 1", $args['id']);
    $q->format("DELETE FROM users_forget WHERE id_user='%d' AND id_user > 1", $args['id']);
    $q->format("DELETE FROM users_activate WHERE id_user='%d' AND id_user > 1", $args['id']);
    $q->format("DELETE FROM groups_members WHERE id_user='%d' AND id_user > 1", $args['id']);
    $q->format("DELETE FROM modules_perm WHERE id_user='%d' AND id_user > 1", $args['id']);
    $q->format("DELETE FROM users WHERE id='%d' AND id > 1", $args['id']);
    // ok redirect
    if($kernel['db']->affected_rows()) { http_redirect("/admin/?mod=users&ok"); }
  }
  // redirect
  http_redirect("/admin/?mod=users&error");
break;

// Список юзеров
default:
  // заголовок окна
  $result['title'] = $config['msg']['list'];
  // сортировка
  $args['order'] = trim($_GET['order']);
  $args['desc'] = intval((bool)$_GET['desc']);
  if(!$args['order'])
  {
    $args['order'] = $_SESSION['users']['order'];
    $args['desc'] = $_SESSION['users']['desc'];
  }
  if(!in_array($args['order'], array('id', 'login', 'email', 'name', 'blocked','created', 'updated')))
  {
    $args['order'] = 'id';
    $args['desc'] = true;
  }
  $_SESSION['users']['order'] = $args['order'];
  $_SESSION['users']['desc'] = $args['desc'];

  // list
  $q->format("SELECT u.* FROM users as u ORDER BY %s %s", $args['order'], ($args['desc']? 'DESC' : 'ASC'));
  $args['items'] = $q->get_allrows();
  $q->free_result();
  // commands
  $result['commands'][] = array('path'=>'/admin/?mod=users&act=add', 'title'=>$config['msg']['add']);
  // template
  $template = 'main.phpt';
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