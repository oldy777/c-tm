<?php

// библиотеки
include_once(INCLUDE_DIR. '/httree.php');
include_once(INCLUDE_DIR. '/controls.php');
include_once(INCLUDE_DIR. '/editor.php');
include_once($_SERVER['DOCUMENT_ROOT']."/editor/ckeditor.php");

// инита
$result = array();
$result['title'] = '';
$result['commands'] = array();
$q = &$kernel['db']->query();
$tree = new httree();
$action = trim($_GET['act']);
$args = array();
$errors = array();
$template = '';

switch($action)
{
// Добавляем узел
case 'addnode':
  // заголовок
  $result['title'] = $config['msg']['addnode'];
  // идентификатор ветки
  $args['id'] = intval(max($_GET['id'], $_POST['id']));
  // получаем инфу о родительской ветке
  $args['parent'] = $tree->getnode($args['id']);
  // если нету такой выдаем ошибку
  if(empty($args['parent'])) { $errors['id'] = true; }
  // если была послана форма
  if($_SERVER['REQUEST_METHOD']=='POST')
  {
    // сохраняем данные формы
    $args['title']  = trim($_POST['title']);
    $args['path']   = strtolower(trim($_POST['path']));
    $args['hidden'] = intval((bool)$_POST['hidden']);
    // заполняем пропущеные поля
    $args['id_maket'] = (!isset($_POST['id_maket']) || $_POST['id_maket']=="NULL"? $args['parent']['id_maket'] : intval($_POST['id_maket']));
    $args['keywords'] = (!isset($_POST['keywords']) || $_POST['keywords']=="NULL"? $args['parent']['keywords'] : trim($_POST['keywords']));
    $args['description'] = (!isset($_POST['description']) || $_POST['description']=="NULL"? $args['parent']['description'] : trim($_POST['description']));
    // проверка введеных значении
    if($args['title']=='') { $errors['title'] = true; }
    if($args['path']=='')  { $errors['path']['empty'] = true; }
    //проверяем путь вирт папки
    elseif(!preg_match("~^[a-zA-Z0-9_\-]+$~s", $args['path'])) { $errors['path']['valid'] = true; }
    //проверяем на уникальность пути
    elseif($tree->findnode($args['path'])) { $errors['path']['unique'] = true; }
    // все без ошибок - добавляем в базу
    if(empty($errors))
    {
      $data = array();
      $data['title']       = $args['title'];
      $data['path']        = $args['path'];
      $data['id_maket']    = $args['id_maket'];
      $data['hidden']      = $args['hidden'];
      $data['keywords']    = $args['keywords'];
      $data['description'] = $args['description'];
      $target = '?mod=htdocs';
      if($id_node = $tree->addnode($args['parent']['id'], $data))
      {
        $id_doc = $tree->insertdoc($id_node, array('path'=>'index', 'hidden'=>true, 'title'=>''));
        if(isset($_POST['next'])) { $target.= '&act=docs&id='. $id_node; }
        $target.= '&ok';
      }
      http_redirect($target);
    }
  }
  //если просто открыли страничку добавления выдаем шаблон
  else
  {
    $q->format("SELECT MAX(id) as id FROM {$tree->table} WHERE id_parent='%d'", $args['parent']['id']);
    $r = $q->get_row();
    $q->free_result();
    $args['path'] = (empty($r)? '' : ($r['id']+1));
  }
  // макеты
  $q->format("SELECT id,title,(CASE WHEN id='%d' THEN 1 ELSE 0 END) as current FROM makets ORDER BY id", $args['id_maket']);
  $args['makets'] = $q->get_allrows();
  $q->free_result();
  // команды
  $result['commands'][] = array('path'=>'?mod=htdocs', 'title'=>$config['msg']['tree']);
  $template = 'addnode.phpt';
break;

// редактирование узла
case 'editnode':
  $args['id'] = intval($_GET['id']);
  $result['title'] = $config['msg']['editnode'];
  $args = $tree->getnode($args['id']);
  if(empty($args))  // not exists
  {
    $errors['id'] = true;
    http_redirect('?mod=htdocs');
  }
  $args['parent'] = $tree->getparent($args['id']);
  $args['isroot'] = empty($args['parent']);

  //если отправили форму
  if($_SERVER['REQUEST_METHOD']=='POST')
  {
    $args['title'] = trim($_POST['title']);
    $_POST['path'] = strtolower(trim($_POST['path']));
    $args['hidden'] = intval((bool)$_POST['hidden']);
    // убираем пустоту
    $args['id_maket'] = (!isset($_POST['id_maket']) || $_POST['id_maket']=="NULL"? NULL : intval($_POST['id_maket']));
    $args['keywords'] = (!isset($_POST['keywords']) || $_POST['keywords']=="NULL"? NULL : trim($_POST['keywords']));
    $args['description'] = (!isset($_POST['description']) || $_POST['description']=="NULL"? NULL : trim($_POST['description']));
    $args['eval'] = trim($_POST['eval']);
    // проверка названия
    if($args['title']=='') { $errors['title'] = true; }
    // проверка пути на пустоту и уникальность
    if(!$args['isroot'] && isset($_POST['path']))
    {
      if($_POST['path']=='')  { $errors['path']['empty'] = true; }
      elseif(!preg_match("~^[a-zA-Z0-9_\-]+$~s", $_POST['path'])) { $errors['path']['valid'] = true; }
      elseif($node = $tree->checkpath($args['parent']['fullpath']. $_POST['path'])) { $errors['path']['unique'] = true; }
    }
    //если небыло ошибок обновляем базу
    if(empty($errors))
    {
      $data = array();
      if(!$args['isroot']) // not root
      {
        $parent = $tree->getparent($args['id']);
        if(isset($_POST['path']))
         { $data['path']   = $_POST['path']; }
        $data['id_maket']  = $args['id_maket'];
        $data['hidden']    = $args['hidden'];
        $data['fullpath']  = $parent['fullpath'].$_POST['path']."/";
      }
      $data['title']       = $args['title'];
      $data['id_maket']    = $args['id_maket'];
      $data['keywords']    = $args['keywords'];
      $data['description'] = $args['description'];
      $target = '?mod=htdocs';
      if($tree->update($args['id'], $data))
      {
        if(isset($_POST['next']))
        {
          $doc = $tree->getdoc('index', $args['id'], array('id'));
          if($doc) { $target.= '&act=editdoc&id='. $doc['id']; }
        }
        $target.= '&ok';
      }
      http_redirect($target);
    }
  }
  // заполняем пустоту
  $args['hidden'] = ($args['hidden']===NULL? NULL : intval((bool)$args['hidden']));
  $args['id_maket'] = ($args['id_maket']===NULL? NULL : intval($args['id_maket']));

  // макеты
  $q->format("SELECT id,title,(CASE WHEN id='%d' THEN 1 ELSE 0 END) as current FROM makets ORDER BY id", $args['id_maket']);
  $args['makets'] = $q->get_allrows('id');
  $q->free_result();
  // команды
  $result['commands'][] = array('path'=>'?mod=htdocs', 'title'=>$config['msg']['tree']);
  if(!$args['isroot'])
  {
    $result['commands'][] = array('path'=>'?mod=htdocs&act=movenode&id='. $args['id'], 'title'=>$config['msg']['movenode']);
  }
  $result['commands'][] = array('path'=>'?mod=htdocs&act=docs&id='. $args['id'], 'title'=>$config['msg']['docs']);
  $template = 'editnode.phpt';
break;

// перемещение узла
case 'movenode':
  $args['id'] = intval($_GET['id']);
  $result['title'] = $config['msg']['movenode'];
  $args = $tree->getnode($args['id']);
  // not exists
  if(empty($args) || !$args['id_parent']) { $errors['id'] = true; }

  //получаем список всех узлов кроме текущего
  $args['neighbours'] = $tree->returnall($args['id']);

  //если была отправка формы то модифицируем базу
  if($_SERVER['REQUEST_METHOD']=='POST')
  {
    $_POST['id_parent'] = intval($_POST['id_parent']);
    print_r($args['neighbours']);
    if($_POST['id_parent']<=0 || !isset($args['neighbours'][ $_POST['id_parent'] ])) { $errors['id_parent'] = true; }
    elseif($_POST['id_parent']==$args['id_parent']) { $errors['id_parent']['equal'] = true; }

    if(empty($errors))
    {
      $target = '?mod=htdocs';
      if($tree->setparent($args['id'], $_POST['id_parent'])) { $target.= '&ok'; }
      http_redirect($target);
    }
    $args['id_parent'] = $_POST['id_parent'];
  }
  // команды
  $result['commands'][] = array('path'=>'?mod=htdocs', 'title'=>$config['msg']['tree']);
  $result['commands'][] = array('path'=>'?mod=htdocs&act=editnode&id='. $args['id'], 'title'=>$config['msg']['editnode']);
  $result['commands'][] = array('path'=>'?mod=htdocs&act=docs&id='. $args['id'], 'title'=>$config['msg']['docs']);
  $template = 'movenode.phpt';
break;

// удаляем ветку
case 'delnode':
  $args['id'] = intval($_GET['id']);
  $root = $tree->getroot();
  $root = $root[0];
  $node = $tree->getnode($args['id']);
  // проверка что не корень, нет потомков и удалили нормально
  $target = '?mod=htdocs';

  if(!empty($node) && ($root['id']!=$node['id']) && !$node['isparent'] && ($tree->del($node['id'])))
  {
    $target.= '&ok';
  }
  http_redirect($target);
break;

// двигаем вверх
case 'nodeup':
  $args['id'] = intval($_GET['id']);
  $target = '?mod=htdocs';
  if($tree->upnode($args['id'])) { $target.= '&ok'; }
  http_redirect($target);
break;

// двигаем вниз
case 'nodedown':
  $args['id'] = intval($_GET['id']);
  $target = '?mod=htdocs';
  if($tree->downnode($args['id'])) { $target.= '&ok'; }
  http_redirect($target);
break;

// список документов
case 'docs':
  $args['id'] = intval($_GET['id']);
  $args['root'] = $tree->getroot();
  $result['title'] = $config['msg']['docs'];
  // инфа по предку
  $args['parent'] = $tree->getnode($args['id']);
  if(empty($args['parent'])) { $errors['id'] = true; }
  // сортировка
  $args['order'] = trim($_GET['order']);
  $args['desc'] = intval((bool)$_GET['desc']);
  if($args['order']=='')
  {
    $args['order'] = $_SESSION['htdocsorder'][ $args['id'] ];
    $args['desc'] = $_SESSION['htdocsdesc'][ $args['id'] ];
  }
  if(!in_array($args['order'], array('id', 'path', 'pos', 'eval', 'title', 'created', 'updated')))
  {
    $args['order'] = 'id';
    $args['desc'] = true;
  }
  $_SESSION['htdocsorder'][ $args['id'] ] = $args['order'];
  $_SESSION['htdocsdesc'][ $args['id'] ] = $args['desc'];

  $q->format("SELECT id,title,path,hidden,eval,created,updated,published,(path='index') isindex FROM {$tree->doctable} WHERE id_node='%d' ORDER BY isindex DESC,%s %s", $args['id'], $args['order'], ($args['desc']? 'DESC' : 'ASC'));
  $args['items'] = $q->get_allrows();
  $q->free_result();

  // команды
  $result['commands'][] = array('path'=>'?mod=htdocs', 'title'=>$config['msg']['tree']);
  $result['commands'][] = array('path'=>'?mod=htdocs&act=movenode&id='. $args['id'], 'title'=>$config['msg']['movenode']);
  $result['commands'][] = array('path'=>'?mod=htdocs&act=editnode&id='. $args['id'], 'title'=>$config['msg']['editnode']);
  $result['commands'][] = array('path'=>'?mod=htdocs&act=adddoc&id='. $args['id'], 'title'=>$config['msg']['adddoc']);

  $template = 'docs.phpt';
break;

// добавление документа
case 'adddoc':
  $args['id'] = intval($_GET['id']);
  $result['title'] = $config['msg']['adddoc'];
  // get node
  $args['parent'] = $tree->getnode($args['id']);
  if(empty($args['parent'])) { $errors['id'] = true; };

  if($_SERVER['REQUEST_METHOD']=='POST')
  {
    $args['title']       = trim($_POST['title']);
    $args['notice']      = trim($_POST['notice']);
    $args['path']        = strtolower(trim($_POST['path']));
    $args['hidden']      = intval((bool)$_POST['hidden']);
    $args['published']   = datebox_parse($_POST['published']);
    $args['eval']        = intval((bool)$_POST['eval']);
    $args['php']         = trim($_POST['php']);
    $args['pos']         = trim($_POST['pos']);
    $args['html']        = trim($_POST['html']);
    $args['html']=str_replace("\\\"","\"",$args['html']);
    $args['html']=str_replace("\\'","'",$args['html']);
    $args['php']=str_replace("\\\"","\"",$args['php']);
    $args['php']=str_replace("\\'","'",$args['php']);
    // inherit
    $args['id_maket']    = (!isset($_POST['id_maket']) || $_POST['id_maket']=="NULL"? NULL : intval($_POST['id_maket']));
    $args['keywords']    = (!isset($_POST['keywords']) || $_POST['keywords']=="NULL"? NULL : trim($_POST['keywords']));
    $args['description'] = (!isset($_POST['description']) || $_POST['description']=="NULL"? NULL : trim($_POST['description']));
    
    $args['seo1']    = (!isset($_POST['seo1']) || !$_POST['seo1']? '' : trim($_POST['seo1']));
    $args['seo2'] = (!isset($_POST['seo2']) || !$_POST['seo2']? '' : trim($_POST['seo2']));
    
    // check
    if($args['title']=='') { $errors['title'] = true; }
    if($args['path']=='')  { $errors['path']['empty'] = true; }
    elseif(!preg_match("~^[a-zA-Z0-9_\-]+$~s", $args['path'])) { $errors['path']['valid'] = true; }
    elseif($tree->checkdoc($args['path'], $args['parent']['id'])) { $errors['path']['unique'] = true; }

    if(empty($errors))
    {
      $data = array();
      $data['title']       = $args['title'];
      $data['notice']      = $args['notice'];
      $data['path']        = $args['path'];
      $data['eval']        = $args['eval'];
      $data['pos']         = $args['pos'];
      $data['id_maket']    = $args['id_maket'];
      $data['content']     = ($args['eval']? $args['php'] : editor_encode($args['html']));
      $data['published']   = $args['published'];
      $data['hidden']      = $args['hidden'];
      $data['keywords']    = $args['keywords'];
      $data['description'] = $args['description'];
      $data['seo1']        = $args['seo1'];
      $data['seo2']        = $args['seo2'];
      $target = "?mod=htdocs&act=docs&id=". $args['parent']['id'];
      if($id_doc = $tree->insertdoc($args['parent']['id'], $data)){
        $target.= '&ok';
      }
      http_redirect($target);
    }
  }
  else
  {
    $q->format("SELECT MAX(id) as id FROM {$tree->doctable} WHERE id_node='%d'", $args['parent']['id']);
    $r = $q->get_row();
    $q->free_result();
    $args['path'] = (empty($r)? '' : ($r['id']+1));
  }
  // коректировка
  $args['php'] = '';
  $args['html'] = '';
  $args[ ($args['eval']? 'php' : 'html') ] = $args['content'];
  unset($args['content']);
  // макеты
  $q->format("SELECT id,title,(CASE WHEN id='%d' THEN 1 ELSE 0 END) as current FROM makets ORDER BY id", $args['id_maket']);
  $args['makets'] = $q->get_allrows('id');
  $q->free_result();
  // команды
  $result['commands'][] = array('path'=>'?mod=htdocs', 'title'=>$config['msg']['tree']);
  $result['commands'][] = array('path'=>'?mod=htdocs&act=docs&id='. $args['id'], 'title'=>$config['msg']['docs']);
  $template = 'adddoc.phpt';
break;

// редактирование документа
case 'editdoc':
  $result['title'] = $config['msg']['editdoc'];

  $q->query("select * from htdocs where id='".$_GET['id']."'");
  $args=$q->get_row();

  //$args = $tree->getdoc(intval($_GET['id']), 0);

  if(empty($args))  // not exists
  {
    $errors['id'] = true;
    http_redirect('?mod=htdocs');
  }
  else
  {
    $args['parent'] = $tree->getnode($args['id_node']);
  }
  if(empty($args['parent'])) { $errors['parent'] = true; };
  $args['isindex'] = ($args['path']=='index');

  //если была послана форма
  if($_SERVER['REQUEST_METHOD']=='POST')
  {
    $args['title']       = trim($_POST['title']);
    $args['notice']      = trim($_POST['notice']);
    if(!$args['isindex'])
      { $args['path']    = strtolower(trim($_POST['path'])); }
    $args['hidden']      = intval((bool)$_POST['hidden']);
    $args['published']   = datebox_parse($_POST['published']);
    $args['eval']        = intval((bool)$_POST['eval']);
    $args['php']         = trim($_POST['php']);
    $args['pos']         = trim($_POST['pos']);
    $args['html']        = trim($_POST['html']);
    $args['html']=str_replace("\\\"","\"",$args['html']);
    $args['html']=str_replace("\\'","'",$args['html']);
    $args['php']=str_replace("\\\"","\"",$args['php']);
    $args['php']=str_replace("\\'","'",$args['php']);

    if($args['isindex'])
      { $args['hidden']  = true; }
    // забиваем пустоту
    $args['id_maket']    = (!isset($_POST['id_maket']) || $_POST['id_maket']=="NULL"? NULL : intval($_POST['id_maket']));
    $args['keywords']    = (!isset($_POST['keywords']) || $_POST['keywords']=="NULL"? NULL : trim($_POST['keywords']));
    $args['description'] = (!isset($_POST['description']) || $_POST['description']=="NULL"? NULL : trim($_POST['description']));
    
    $args['seo1']    = (!isset($_POST['seo1']) || !$_POST['seo1']? '' : trim($_POST['seo1']));
    $args['seo2'] = (!isset($_POST['seo2']) || !$_POST['seo2']? '' : trim($_POST['seo2']));
    
    // еслипустой заголовок то ошибка
    if($args['title']=='' && !$args['isindex']) { $errors['title'] = true; }
    if(!$args['isindex'])
    {
      if($args['path']=='')  { $errors['path']['empty'] = true; }
      elseif(!preg_match("~^[a-zA-Z0-9_\-]+$~s", $args['path'])) { $errors['path']['valid'] = true; }
      elseif($tree->checkdoc($args['path'], $args['parent']['id'],$args['id'])){ $errors['path']['unique'] = true; }
    }
    //если прошли все проверки можно модифицировать базу
    if(empty($errors))
    {
      $data = array();
      $data['title']       = $args['title'];
      $data['notice']      = $args['notice'];
      if(!$args['isindex'])
        { $data['path']    = $args['path']; }
      $data['id_maket']    = $args['id_maket'];
      $data['hidden']      = $args['hidden'];
      $data['keywords']    = $args['keywords'];
      $data['seo1']        = $args['seo1'];
      $data['seo2']        = $args['seo2'];
      $data['description'] = $args['description'];
      $data['eval']        = $args['eval'];
      $data['pos']         = $args['pos'];
      $data['content']     = ($args['eval']? $args['php'] : editor_encode($args['html']));
      $data['published']   = $args['published'];
//      print_r($data);exit;
      $target = "?mod=htdocs&act=docs&id=". $args['parent']['id'];
      if($tree->updatedoc($args['id'], $data))
       { $target.= '&ok'; }
      http_redirect($target);
    }
  }
  // коректировка
  $args['php'] = '';
  $args['html'] = '';
  $args[ ($args['eval']? 'php' : 'html') ] = $args['content'];
  unset($args['content']);
  // макеты
  $q->format("SELECT id,title,(CASE WHEN id='%d' THEN 1 ELSE 0 END) as current FROM makets ORDER BY id", $args['id_maket']);
  $args['makets'] = $q->get_allrows();
  $q->free_result();
  // команды
  $result['commands'][] = array('path'=>'?mod=htdocs', 'title'=>$config['msg']['tree']);
  $result['commands'][] = array('path'=>'?mod=htdocs&act=docs&id='. $args['parent']['id'], 'title'=>$config['msg']['docs']);
  $template = 'editdoc.phpt';
break;

// удаление документа
case 'deldoc':
  $args['id'] = intval($_GET['id']);
  $args = $tree->getdoc($args['id']);
  if(empty($args)) { http_redirect("?mod=htdocs"); }
  else
  {
    $target = "?mod=htdocs&act=docs&id=". $args['id_node'];
    if($args['path']!='index' && $tree->deldoc($args['id'])){
      $target.= '&ok';
    }
    http_redirect($target);
  }
break;

// смена ветки документа
case 'movedoc':

  $result['title'] = $config['msg']['movedoc'];
  $args['id'] = intval($_GET['id']);
  $args = $tree->getdoc($args['id']);
  if(empty($args)) { $errors['id'] = true; }
  $args['isindex'] = ($args['path']=='index');
  $args['tree'] = $tree->returnall(0);
  if($_SERVER['REQUEST_METHOD']=='POST')
  {
    $_POST['id_node'] = intval($_POST['id_node']);
    if($_POST['id_node']){
        if(!$tree->getnode($_POST['id_node'])) { $errors['id_node'] = true;}
        elseif($args['id_node']==$_POST['id_node']) { $errors['id_node']['equal'] = true; }
    }else{
        $errors['id_node'] = true;

    }
    if($args['isindex']) { $errors['id_node']['isindex'] = true; }
    if(empty($errors)){
      if($tree->getdoc($args['path'], $_POST['id_node']))
       { $errors['id_node']['unique'] = true; }
    }
    if(empty($errors)){
      $target = "?mod=htdocs&act=docs&id=". $args['id_node'];
      if($tree->updatedoc($args['id'], array('id_node'=>$_POST['id_node'])))
       { $target.= '&ok'; }
      http_redirect($target);
    }
  }
  $node=$tree->getnode($args['id_node']);
  $args['fullpath'] = $node['fullpath']. $args['path'];
  // шаблон
  $template = 'movedoc.phpt';
break;

// вывод дерева
default:
  // title
  $result['title'] = $config['msg']['tree'];
  $args['root'] =   $tree->getroot();
  $args['root']=$args['root'][0];

  // комманды
  $result['commands'][] = array('path'=>'?mod=htdocs&act=addnode&id='.$args['root']['id'], 'title'=>$config['msg']['addnode']);
  $result['commands'][] = array('path'=>'javascript:httreeall(0)', 'title'=>$config['msg']['hidenodes']);
  $result['commands'][] = array('path'=>'javascript:httreeall(1)', 'title'=>$config['msg']['shownodes']);
  // шаблон
  $template = 'tree.phpt';
break;
}

// Успешное завершение
$args['ok'] = $result['ok'] = isset($_GET['ok']);

// Вывод шаблона
if($template!='') { template(dirname(__FILE__). '/templates/admin/'. $template, $args, $errors); }

// Возврат
return $result;
?>