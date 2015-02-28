<?php
include_once(INCLUDE_DIR. '/images.php');
include_once(INCLUDE_DIR. '/valid.php');
include_once($_SERVER['DOCUMENT_ROOT']."/editor/ckeditor.php");

$result = array();
$result['title'] = '';
$result['commands'] = array();
/* @var $q query_mysql */
$q = &$kernel['db']->query();
$action = trim($_GET['act']);
$args = array();
$errors = array();
$template = '';
$npages = 50; 
$args['page'] = (isset($_GET['page']) && $_GET['page']) ? $_GET['page']:1;

$args['mod_name']=Array('','вакансии','вакансию');      # {Название}, lj,добавление {названия}, добавить {название}
$args['mod_table_name']="vacancy";             # Имя таблицы
$args['mod_pos']=true;                  # Вкл/выкл позиция
$args['mod_pos_reverse']=false;
$args['mod_fields']=Array(              # Поля
  Array('name'=>'title','title'=>'Название','type'=>'varchar','view'=>1, 'link'=>1),
  Array('name'=>'responsibility','title'=>'Обязанности','type'=>'editor','view'=>0, 'alt'=>''),//alt-имя поля для сортировки из другой связанной таблицы
  Array('name'=>'requirements','title'=>'Требования','type'=>'editor','view'=>0),
  Array('name'=>'terms','title'=>'Условия','type'=>'editor','view'=>0),
  Array('name'=>'act','title'=>'Показывать','type'=>'option','view'=>0),
);

$args['options']['act']['values'][1] = 'Да';
$args['options']['act']['values'][0] = 'Нет';

$args['mod_view']=1;

switch($action){
  case "additem":
    if($_SERVER['REQUEST_METHOD']=='POST'){
      foreach($args['mod_fields'] as $f){
          switch ($f['type']) {
              default:
                  $val[$f['name']] = checkModFields($f);
                  break;
          }
      }
      
      if($args['mod_pos']){
        $val['pos'] = getPosForNewItem($args['mod_table_name'], $args['mod_pos_reverse']);
      }

      $q->format("insert into ".$args['mod_table_name']." set %s",$val);
      setFlush('Запись успешно добавлена'); 
      http_redirect("?mod=".$_GET['mod']);
    }else{
      $result['commands'][] = array('path'=>'/admin/?mod='.$_GET['mod'], 'title'=>'К списку');
      $result['title']="Добавление ".$args['mod_name'][1];
      $template="item-add.phpt";
    };
  break;
  
  case "edititem":
    if($_SERVER['REQUEST_METHOD']=='POST'){

        foreach($args['mod_fields'] as $f){
          switch ($f['type']) {
              default:
                  $val[$f['name']] = checkModFields($f);
                  break;
          }
      }

      $q->format("update ".$args['mod_table_name']." set %s where id='".$_GET['id']."'",$val);
      setFlush('Изменения успешно сохранены');
      http_redirect("?mod=".$_GET['mod'].(isset($_GET['page'])?('&page='.$_GET['page']):''));
    }else{
      $result['commands'][] = array('path'=>'/admin/?mod='.$_GET['mod'].(isset($_GET['page'])?('&page='.$_GET['page']):''), 'title'=>'К списку');
      $result['title']="Редактирование ".$args['mod_name'][1];

      $args['item']=$q->z_getRowById($args['mod_table_name'], $_GET['id']);

      foreach($args['mod_fields'] as $f){
        if($f['type']=="image"){
          $q->query("select * from modules_images where id='".$args['item'][$f['name']]."'");
          $args['item'][$f['name'].'_image']=$q->get_row();
        }
        if($f['type']=="file"){
          $q->query("select * from modules_files where id='".$args['item'][$f['name']]."'");
          $args['item'][$f['name'].'_file']=$q->get_row();
        }
      }

      $template="item-edit.phpt";
    };
  break;
  
  case "delitem":
    if($args['mod_pos']){
      $q->query("select pos from ".$args['mod_table_name']." where id='".$_GET['id']."'");
      $pos=$q->get_cell();
      $q->query("select * from ".$args['mod_table_name']." where pos>".$pos);
      $all=$q->get_allrows();
      foreach($all as $c){
        $pos=$c['pos']-1;
        $q->query("update ".$args['mod_table_name']." set pos='".$pos."' where id='".$c['id']."'");
      }
    }
    $q->query("delete from ".$args['mod_table_name']." where id='".$_GET['id']."'");
    setFlush('Запись удалена');
    http_redirect("?mod=".$_GET['mod'].(isset($_GET['page'])?('&page='.$_GET['page']):''));
  break;
  
  default:
    $result['commands'][] = array('path'=>'/admin/?mod='.$_GET['mod'].'&act=additem', 'title'=>'Добавить '.$args['mod_name'][2]);
    $args['items'] = array();
    
    $order = 'id';
    $type = 'DESC';
    if(isset($_GET['order']) && $_GET['order'])
    {
        $order = $_GET['order'];
        $type = $_GET['type'] ? $_GET['type']:'ASC';
    }
    else 
    {
        if($args['mod_pos'])
        {
            $order = 'pos';
            $type = 'ASC';
        }
        
    }
    
    $q->query("select * from ".$args['mod_table_name']." T "
            ."order by ".(!strstr($order, '.')? 'T.'.$order:$order)." ".$type
            ." LIMIT ".($args['page']-1)*$npages.",".$npages);
    $args['items'] = $q->get_allrows();
    
    $q->query("select count(id) as cnt from ".$args['mod_table_name']." WHERE 1=1");
    $all = $q->get_cell();
    $args['pages'] = ceil($all/$npages);
    
    $template="items.phpt";
  break;
};

if($template!='') { template(dirname(__FILE__). '/templates/'. $template, $args, $errors); }

return $result;
  
?>