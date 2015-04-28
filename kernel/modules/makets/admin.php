<?php

// INIT
$result = array();
$result['title'] = '';
$result['commands'] = array();
$args = array();
$errors = array();
$q = $kernel['db']->query();
$action = trim($_GET['act']);
$template = '';

// ACTIONS
switch($action)
{
// ADD
case 'add';
  // title
  $result['title'] = $config['msg']['add'];
  // request
  if($_SERVER['REQUEST_METHOD']=='POST')
  {
    // save
    $args['title'] = trim($_POST['title']);
    $args['content'] = trim($_POST['content']);
    $args['file'] = $_POST['file'] == '0' ? '':trim($_POST['file']);;
    // check
    if($args['title']=='') { $errors['title'] = true; }
//    if($args['content']=='') { $errors['content'] = true; }
    // ok
    if(empty($errors))
    {
      $q->format("INSERT INTO makets SET id='%d',title='%s',content='%s',created='%d',updated='%d',file='%s'", $kernel['db']->next_id('makets'), $args['title'], $args['content'], time(), time(), $args['file']);
      // redirect ok
      http_redirect($kernel['db']->affected_rows()? "/admin/?mod=makets&ok" : "/admin/?mod=makets");
    }
  }
  // commands
  $result['commands'][] = array('path'=>'/admin/?mod=makets', 'title'=>$config['msg']['list']);
  // template
  $template = 'addedit.phpt';
break;
// EDIT
case 'edit';
  // title
  $result['title'] = $config['msg']['edit'];
  // request
  $args['id'] = (int)$_GET['id'];
  if($_SERVER['REQUEST_METHOD']=='POST')
  {
    // save
    $args['title'] = trim($_POST['title']);
    $args['content'] = trim($_POST['content']);
    $args['content']=str_replace("\\\"","\"",$args['content']);
    $args['content']=str_replace("\\'","'",$args['content']);
    if($args['title']=='') { $errors['title'] = true; }
//    if($args['content']=='') { $errors['content'] = true; }
    
    $args['file'] = $_POST['file'] == '0' ? '':trim($_POST['file']);
//    print_r($args);exit;
    // ok
    if(empty($errors))
    {
      $q->format("UPDATE makets SET title='%s',content='%s',updated='%d',file='%s' WHERE id='%d'", $args['title'], $args['content'], time(), $args['file'], $args['id']);
      // redirect ok
      http_redirect($kernel['db']->affected_rows()? "/admin/?mod=makets&ok" : "/admin/?mod=makets");
    }
  }
  else
  {
    // load
    $q->format("SELECT * FROM makets WHERE id='%d'", $args['id']);
    $args = $q->get_row();
    $q->free_result();
    // not exists
    if(empty($args)) { http_redirect("/admin/?mod=makets&error"); }
  }
  // commands
  $result['commands'][] = array('path'=>'/admin/?mod=makets', 'title'=>$config['msg']['list']);
  $result['commands'][] = array('path'=>'/admin/?mod=makets&act=copy&id='. $args['id'], 'title'=>$config['msg']['copy']);
  // template
  $template = 'addedit.phpt';
break;
// COPY MAKET
case 'copy';
  // title
  $result['title'] = 'Копирование';
  // load
  $q->format("SELECT id,title,content FROM makets WHERE id='%d'", (int)$_GET['id']);
  $args = $q->get_row();
  $q->free_result();
  // not exists
  if(empty($args)) { http_redirect("/admin/?mod=makets&error"); }
  // title
  $args['title'] = $args['title']. '(copy)';
  // form action
  $args['action'] = '/admin/?mod=makets&act=add';
  // commnads
  $result['commands'][] = array('path'=>'/admin/?mod=makets', 'title'=>'Список макетов');
  $result['commands'][] = array('path'=>'/admin/?mod=makets&act=edit&id='. $args['id'], 'title'=>$config['msg']['edit']);
  // template
  $template = 'addedit.phpt';
break;
// DELETE MAKET
case 'delete';
  $args['id'] = intval($_GET['id']);
  if($args['id'] > 0)
  {
    $q->format("UPDATE htdocs SET id_maket=0 WHERE id_maket='%d'", $args['id']);
    $q->format("UPDATE ptree SET id_maket=0 WHERE id_maket='%d'", $args['id']);
    $q->format("DELETE FROM makets WHERE id='%d'", $args['id']);
    // ok redirect
    if($kernel['db']->affected_rows()) { http_redirect("/admin/?mod=makets&ok"); }
  }
  // redirect
  http_redirect("/admin/?mod=makets&error");
break;
// VIEW MAKET SYNTAX
case 'view':
  // no design
//  $result['indep'] = true;
  // load
  $q->format("SELECT id,title,content FROM makets WHERE id='%d'", (int)$_GET['id']);
  $args = $q->get_row();
  $q->free_result();
  // not exists
  if(empty($args)) { http_redirect("/admin/?mod=makets&error"); }
  // syntax
  echo '<nobr>';
  highlight_string($args['content']);
  echo '</nobr>';
  // template
  $template = '';
break;
// LIST
default:
  // title
  $result['title'] = $config['msg']['list'];
  // order
  $args['order'] = trim($_GET['order']);
  $args['desc'] = intval((bool)$_GET['desc']);
  // session order
  if($args['order']=='')
  {
    $args['order'] = $_SESSION['makets']['order'];
    $args['desc'] = $_SESSION['makets']['desc'];
  }
  // check order
  if(!in_array($args['order'], array('id', 'title', 'created', 'updated', 'docs')))
  {
    $args['order'] = 'id';
    $args['desc'] = true;
  }
  // memorize order
  $_SESSION['makets']['order'] = $args['order'];
  $_SESSION['makets']['desc'] = $args['desc'];
  // list
  $q->format("SELECT m.id,m.title,m.created,m.updated,(COUNT(d.id)+COUNT(t.id)) as `docs` FROM makets as m LEFT JOIN htdocs as d ON (d.id_maket=m.id) LEFT JOIN ptree as t ON (t.id_maket=m.id) GROUP BY m.id ORDER BY %s %s", $args['order'], ($args['desc']? 'DESC' : 'ASC'));
  $args['items'] = $q->get_allrows();
  $q->free_result();
  // commands
  $result['commands'][] = array('path'=>'/admin/?mod=makets&act=add', 'title'=>$config['msg']['add']);
  // template
  $template = 'list.phpt';
}

// SUCCESSFULL
$args['ok'] = $result['ok'] = isset($_GET['ok']);

// ERROR
$args['error'] = $result['error'] = isset($_GET['error']);

$files = scandir($_SERVER['DOCUMENT_ROOT'].'/kernel/templates/' );
$args['option']['files']['values'] = array();
$args['option']['files']['values'][0] = 'Не используется';
foreach($files as $file ) {
    if ( $file == '.' or $file == '..' ) continue;
    $args['option']['files']['values'][$file] = $file;
}

// TEMPLATE
if($template!='') { template(dirname(__FILE__). '/templates/'. $template, $args, $errors); }

// FINNALY
return $result;
?>