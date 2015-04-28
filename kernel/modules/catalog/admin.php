<?php
include_once(INCLUDE_DIR. '/images.php');
include_once(INCLUDE_DIR. '/valid.php');
include_once($_SERVER['DOCUMENT_ROOT']."/editor/ckeditor.php");

$result = array();
$result['title'] = '';
$result['commands'] = array();
/*@var $q query_mysql*/
$q = $kernel['db']->query();
$action = trim($_GET['act']);
$args = array();
$errors = array();
$template = '';
$npages = 100; 
$args['page'] = (isset($_GET['page']) && $_GET['page']) ? $_GET['page']:1;

$args['mod_name']=Array('','категории','категорию');      # {Название}, lj,добавление {названия}, добавить {название}
$args['mod_table_name']="catalog";       # Имя таблицы
$args['mod_table_name2']='catalog_items';
$args['mod_table_name3']='catalog_items_detail';
$args['mod_table_images']='catalog_images';
$args['mod_pos']=true;                  # Вкл/выкл позиция
$args['mod_pos2']=true;  
$args['mod_pos3']=true;  
$args['mod_pos_reverse']=false;
$args['mod_fields']=Array(              # Поля
  Array('name'=>'title','title'=>'Название категории','type'=>ValuesFnc::VAL_VARCHAR,'view'=>1, 'link'=>1),
  Array('name'=>'alias','title'=>'Алиас','type'=>ValuesFnc::VAL_VARCHAR),
  Array('name'=>'description','title'=>'Описание','type'=>ValuesFnc::VAL_EDITOR),
  Array('name'=>'img','title'=>'Картинка','type'=>ValuesFnc::VAL_IMAGE),
  Array('name'=>'description_seo','title'=>'Description','type'=>ValuesFnc::VAL_TEXTAREA),
  Array('name'=>'keywords','title'=>'Keywords','type'=>ValuesFnc::VAL_TEXTAREA),
  Array('name'=>'act','title'=>'Показывать','type'=>ValuesFnc::VAL_OPTION),
);

$args['mod_fields2']=Array(              # Поля
    Array('name'=>'title','title'=>'Название ','type'=>ValuesFnc::VAL_VARCHAR,'view'=>1, 'link'=>1),
    Array('name'=>'alias','title'=>'Алиас','type'=>ValuesFnc::VAL_VARCHAR),
    Array('name'=>$args['mod_table_name'].'_id','title'=>'Категория','type'=>'option_struct'),
    Array('name'=>'price','title'=>'Цена','type'=>ValuesFnc::VAL_VARCHAR,'view'=>1,),
    Array('name'=>'doc','title'=>'Документация','type'=>ValuesFnc::VAL_FILE),
    Array('name'=>'img','title'=>'Фото','type'=>ValuesFnc::VAL_IMAGE),
    Array('name'=>'anons','title'=>'Кртакое описание','type'=>ValuesFnc::VAL_TEXTAREA),
    Array('name'=>'descr','title'=>'Полное описание','type'=>ValuesFnc::VAL_EDITOR),
    Array('name'=>'act','title'=>'Показывать','type'=>ValuesFnc::VAL_OPTION),
);

$args['mod_fields3']=Array(              # Поля
  Array('name'=>'title','title'=>'Название','type'=>ValuesFnc::VAL_VARCHAR,'view'=>1, 'link'=>1),
  Array('name'=>'text','title'=>'Описание','type'=>ValuesFnc::VAL_EDITOR),
  Array('name'=>'act','title'=>'Показывать','type'=>ValuesFnc::VAL_OPTION),
);

$args['options']['act']['values'][1] = 'Да';
$args['options']['act']['values'][0] = 'Нет';

$args['options']['sale']['values'][0] = 'Нет';
$args['options']['sale']['values'][1] = 'Да';


if (isset($_GET['f_id']) && $_GET['f_id']) {
    $q->format("SELECT id, title, parent_id FROM ".$args['mod_table_name']." WHERE id = %d", (int) $_GET['f_id']);
    $tmp = $q->get_row();
    $args['cats'][2]['name'] = $tmp['title'];
    $args['cats'][2]['id'] = $tmp['id'];
    if ($tmp['parent_id'] != 0) {
        $q->format("SELECT id, title, parent_id FROM ".$args['mod_table_name']." WHERE id = %d", $tmp['parent_id']);
        $tmp = $q->get_row();
        $args['cats'][1]['name'] = $tmp['title'];
        $args['cats'][1]['id'] = $tmp['id'];
        if ($tmp['parent_id'] != 0) {
            $q->format("SELECT id, title, parent_id FROM ".$args['mod_table_name']." WHERE id = %d", $tmp['parent_id']);
            $tmp = $q->get_row();
            $args['cats'][0]['name'] = $tmp['title'];
            $args['cats'][0]['id'] = $tmp['id'];
        }
    }
    ksort($args['cats']);

    foreach ($args['cats'] as $k => $v) {
        $kernel['brums'][$k]['url'] = '/admin/?mod=' . $_GET['mod'] . "&f_id=" . $v['id'];
        $kernel['brums'][$k]['title'] = $v['name'];
    }
    unset($args['cats']);
}

$args['mod_view']=1;

switch($action){
  case "additem":
    if($_SERVER['REQUEST_METHOD']=='POST'){
      foreach($args['mod_fields'] as $f){
          switch ($f['type']) {
              default:
                  $val = ValuesFnc::checkModFields($f, $val);
                  break;
          }
      }
      
      if($args['mod_pos']){
        $where = " parent_id = ".((int)$_GET['f_id'] ? (int)$_GET['f_id']:0);
        $val['pos'] = ValuesFnc::getPosForNewItem($args['mod_table_name'], $args['mod_pos_reverse'], $where);
      }
      
      if($_GET['f_id'])
        $val['parent_id'] = $_GET['f_id'];
      
      $q->format("insert into ".$args['mod_table_name']." set %s",$val);
      http_redirect("?mod=".$_GET['mod'].(isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:''));
    }else{
      $result['commands'][] = array('path'=>'/admin/?mod='.$_GET['mod'].(isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:''), 'title'=>'К списку');
      $result['title']="Добавление ".$args['mod_name'][1];
      $template="item-add.phpt";
    };
  break;
  
  case "edititem":
    if($_SERVER['REQUEST_METHOD']=='POST'){

      foreach($args['mod_fields'] as $f){
          switch ($f['type']) {
              default:
                  $val = ValuesFnc::checkModFields($f, $val);
                  break;
          }
      }

      $q->format("update ".$args['mod_table_name']." set %s where id='".$_GET['id']."'",$val);
      http_redirect("?mod=".$_GET['mod'].(isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:'').(isset($_GET['order']) && $_GET['order'] ? '&order='.$_GET['order'].'&type='.$_GET['type']:'').(isset($_GET['page']) && $_GET['page'] ? '&page='.$_GET['page']:''));
    }else{
      $result['commands'][] = array('path'=>'/admin/?mod='.$_GET['mod'].(isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:'').(isset($_GET['order']) && $_GET['order'] ? '&order='.$_GET['order'].'&type='.$_GET['type']:'').(isset($_GET['page']) && $_GET['page'] ? '&page='.$_GET['page']:''), 'title'=>'К списку');
      $result['title']="Редактирование ".$args['mod_name'][1];

      $q->query("select * from ".$args['mod_table_name']." where id='".$_GET['id']."'");
      $args['item']=$q->get_row();

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
    $q->query("delete from ".$args['mod_table_name2']." where ".$args['mod_table_name']."_id='".$_GET['id']."'");
    $q->query("delete from ".$args['mod_table_name']." where id='".$_GET['id']."'");
    http_redirect("?mod=".$_GET['mod'].(isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:'').(isset($_GET['order']) && $_GET['order'] ? '&order='.$_GET['order'].'&type='.$_GET['type']:'').(isset($_GET['page']) && $_GET['page'] ? '&page='.$_GET['page']:''));
  break;
  
 case "addpr":
    if($_SERVER['REQUEST_METHOD']=='POST'){
      foreach($args['mod_fields2'] as $f){
          switch ($f['type']) {
              default:
                  $val = ValuesFnc::checkModFields($f, $val);
                  break;
          }
      }
      
      if($args['mod_pos2']){
        $where = $args['mod_table_name']."_id = ".(int)$_GET['f_id'];
        $val['pos'] = ValuesFnc::getPosForNewItem($args['mod_table_name2'], $args['mod_pos_reverse'], $where);
      }
      
      if($_GET['f_id'])
        $val[$args['mod_table_name'].'_id'] = $_GET['f_id'];

    $q->format("insert into ".$args['mod_table_name2']." set %s",$val);
      http_redirect("?mod=".$_GET['mod'].(isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:''));
    }else{
      $result['commands'][] = array('path'=>'/admin/?mod='.$_GET['mod'].(isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:''), 'title'=>'К списку');
      $result['title']="Добавление ".$args['mod_name'][1];
      $template="item-add2.phpt";
    };
  break;
  
  case "editpr":
    if($_SERVER['REQUEST_METHOD']=='POST'){

      foreach($args['mod_fields2'] as $f){
          switch ($f['type']) {
              default:
                  $val = ValuesFnc::checkModFields($f, $val);
                  break;
          }
      }

      $q->format("update ".$args['mod_table_name2']." set %s where id='".$_GET['id']."'",$val);
      http_redirect("?mod=".$_GET['mod'].(isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:'').(isset($_GET['order']) && $_GET['order'] ? '&order='.$_GET['order'].'&type='.$_GET['type']:'').(isset($_GET['page']) && $_GET['page'] ? '&page='.$_GET['page']:''));
    }else{
      $result['commands'][] = array('path'=>'/admin/?mod='.$_GET['mod'].(isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:'').(isset($_GET['order']) && $_GET['order'] ? '&order='.$_GET['order'].'&type='.$_GET['type']:'').(isset($_GET['page']) && $_GET['page'] ? '&page='.$_GET['page']:''), 'title'=>'К списку');
      $result['commands'][] = array('path'=>'/admin/?mod='.$_GET['mod'].(isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:'').'&act=details&id='.$_GET['id'].(isset($_GET['order']) && $_GET['order'] ? '&order='.$_GET['order'].'&type='.$_GET['type']:'').(isset($_GET['page']) && $_GET['page'] ? '&page='.$_GET['page']:''), 'title'=>'Детали');
      $result['title']="Редактирование товара";

      $q->query("select * from ".$args['mod_table_name2']." where id='".$_GET['id']."'");
      $args['item']=$q->get_row();
      
      
      foreach($args['mod_fields2'] as $f){
        if($f['type']=="image"){
          $q->query("select * from modules_images where id='".$args['item'][$f['name']]."'");
          $args['item'][$f['name'].'_image']=$q->get_row();
        }
        if($f['type']=="file"){
          $q->query("select * from modules_files where id='".$args['item'][$f['name']]."'");
          $args['item'][$f['name'].'_file']=$q->get_row();
        }
      }
      
      $args['items_img'] = array();
      $q->query("select mi.path, pi.* from modules_images mi LEFT JOIN ".$args['mod_table_images']." pi ON pi.img = mi.id where pi.".$args['mod_table_name2']."_id='".$_GET['id']."'");
      $args['items_img'] = $q->get_allrows();
      
      $args[$args['mod_table_name'].'_id'] = array();
      $q->query("SELECT * FROM ".$args['mod_table_name']." WHERE act = 1 ORDER BY pos");
      $tmp = $q->get_allrows();
      
      foreach ($tmp as $v) {
          $args[$args['mod_table_name'].'_id'][$v['parent_id']][] = $v;
      }
      
      $args['param'] = array();
      $args['postavka'] = array();

      $template="item-edit2.phpt";
    }
  break;
  
  case "delpr":
    $q->query("delete from ".$args['mod_table_name2']." where id='".$_GET['id']."'");
    http_redirect("?mod=".$_GET['mod'].(isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:'').(isset($_GET['order']) && $_GET['order'] ? '&order='.$_GET['order'].'&type='.$_GET['type']:'').(isset($_GET['page']) && $_GET['page'] ? '&page='.$_GET['page']:''));
  break;

  case "setmarkt":
    $val = array();
    $val['market'] = $_POST['val'];
    $q->format("UPDATE ".$args['mod_table_name2']." SET %s WHERE id = %d",$val, $_POST['id']);
    exit;
  break;

case "price":
    $ret['sux'] = 0;
    if(isset($_POST['id']) && $_POST['id'])
    {
        $val = array('price'=>$_POST['title']);
        $q->format("UPDATE ".$args['mod_table_name2']." SET %s WHERE id = %d", $val,$_POST['id']);
        $ret['sux'] = 1;
    }
    echo json_encode($ret);
    exit;
    break;
  
  case "details":
        $result['title']="Детали проекта ";
        $result['commands'][] = array('path'=>'/admin/?mod='.$_GET['mod'].'&act=editpr'.(isset($_GET['id']) && $_GET['id'] ? '&id='.$_GET['id']:'').(isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:''), 'title'=>'Назад ');
        $result['commands'][] = array('path'=>'/admin/?mod='.$_GET['mod'].'&act=adddetail'.(isset($_GET['id']) && $_GET['id'] ? '&id='.$_GET['id']:'').(isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:''), 'title'=>'Добавить детали');
        $args['items'] = array();


        $q->format("select * from ".$args['mod_table_name3']." T "
                ." WHERE ".$args['mod_table_name2']."_id = %d "
                ." order by id", $_GET['id']);
        $args['items'] = $q->get_allrows();

        $template="details.phpt";
        break;
    
  case "adddetail":
    if($_SERVER['REQUEST_METHOD']=='POST'){
      foreach($args['mod_fields3'] as $f){
          switch ($f['type']) {
              default:
                  $val[$f['name']] = ValuesFnc::checkModFields($f);
                  break;
          }
      }
      
      if($args['mod_pos']){
        $where = $args['mod_table_name2']."_id = ".(int)$_GET['id'];
        $val['pos'] = ValuesFnc::getPosForNewItem($args['mod_table_name3'], $args['mod_pos_reverse'], $where);
      }
      
      if($_GET['id'])
        $val[$args['mod_table_name2'].'_id'] = $_GET['id'];

    $q->format("insert into ".$args['mod_table_name3']." set %s",$val);
      http_redirect("?mod=".$_GET['mod'].'&act=details'.(isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:'').(isset($_GET['id']) && $_GET['id'] ? '&id='.$_GET['id']:''));
    }else{
      $result['commands'][] = array('path'=>'/admin/?mod='.$_GET['mod'].'&act=details'.(isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:'').(isset($_GET['id']) && $_GET['id'] ? '&id='.$_GET['id']:''), 'title'=>'К списку');
      $result['title']="Добавление ".$args['mod_name'][1];
      $template="item-adddetail.phpt";
    }
  break;
  
  case "editdetail":
    if($_SERVER['REQUEST_METHOD']=='POST'){

      foreach($args['mod_fields3'] as $f){
          switch ($f['type']) {
              default:
                  $val[$f['name']] = ValuesFnc::checkModFields($f);
                  break;
          }
      }

      $q->format("update ".$args['mod_table_name3']." set %s where id='".$_GET['detail']."'",$val);
      http_redirect("?mod=".$_GET['mod'].'&act=details'.(isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:'').(isset($_GET['id']) && $_GET['id'] ? '&id='.$_GET['id']:''));
    }else{
      $result['commands'][] = array('path'=>'/admin/?mod='.$_GET['mod'].'&act=details'.(isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:'').(isset($_GET['id']) && $_GET['id'] ? '&id='.$_GET['id']:''), 'title'=>'К списку');
      
      $result['title']="Редактирование ";

      $q->query("select * from ".$args['mod_table_name3']." where id='".$_GET['detail']."'");
      $args['item']=$q->get_row();
      
      
      foreach($args['mod_fields3'] as $f){
        if($f['type']=="image"){
          $q->query("select * from modules_images where id='".$args['item'][$f['name']]."'");
          $args['item'][$f['name'].'_image']=$q->get_row();
        }
        if($f['type']=="file"){
          $q->query("select * from modules_files where id='".$args['item'][$f['name']]."'");
          $args['item'][$f['name'].'_file']=$q->get_row();
        }
      }

      $template="item-editdetail.phpt";
    }
  break;
  
  case "deldetail":

    $q->query("delete from ".$args['mod_table_name3']." where id='".$_GET['detail']."'");
    http_redirect("?mod=".$_GET['mod'].'&act=details'.(isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:'').(isset($_GET['id']) && $_GET['id'] ? '&id='.$_GET['id']:''));
  break;      
        
        
  default:
    if(isset($_GET['f_id']) && (int)$_GET['f_id'])
    {
        $q->query("select * from ".$args['mod_table_name']." T WHERE id = ".(int)$_GET['f_id']);
        $args['item'] = $q->get_row();
        if($args['item']['parent_id'] > 0)
        {
            $result['commands'][] = array('path'=>'/admin/?mod='.$_GET['mod'].'&f_id='.$args['item']['parent_id'].(isset($_GET['order']) && $_GET['order'] ? '&order='.$_GET['order'].'&type='.$_GET['type']:'').(isset($_GET['page']) && $_GET['page'] ? '&page='.$_GET['page']:''), 'title'=>'Назад');
        }
        else
        {
            $result['commands'][] = array('path'=>'/admin/?mod='.$_GET['mod'].(isset($_GET['order']) && $_GET['order'] ? '&order='.$_GET['order'].'&type='.$_GET['type']:'').(isset($_GET['page']) && $_GET['page'] ? '&page='.$_GET['page']:''), 'title'=>'Назад');
        }
    }
    $result['commands'][] = array('path'=>'/admin/?mod='.$_GET['mod'].'&act=additem'.(isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:''), 'title'=>'Добавить '.$args['mod_name'][2]);
    if(isset($_GET['f_id']) && (int)$_GET['f_id'])
        $result['commands'][] = array('path'=>'/admin/?mod='.$_GET['mod'].'&act=addpr'.(isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:''), 'title'=>'Добавить товар');
    $args['items'] = array();
    $where = '';
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
    
    if(isset($_GET['f_id']) && (int)$_GET['f_id'])
    {
        $where .= " AND parent_id = ".(int)$_GET['f_id'];
    }
    else
    {
        $where .= " AND parent_id = 0";
    }
    
    $q->query("select * from ".$args['mod_table_name']." T "
            ." WHERE 1=1 ".$where
            ." order by ".(!strstr($order, '.')? 'T.'.$order:$order)." ".$type
            ." LIMIT ".($args['page']-1)*$npages.",".$npages);
    $args['items'] = $q->get_allrows();
      
    
    if(isset($_GET['f_id']) && (int)$_GET['f_id'])
    {
        $q->query("select * from ".$args['mod_table_name2']." T "
                ." WHERE ".$args['mod_table_name']."_id = ".(int)$_GET['f_id']
                ." order by ".(!strstr($order, '.')? 'T.'.$order:$order)." ".$type
                ." LIMIT ".($args['page']-1)*$npages.",".$npages);
        $args['itemspr'] = $q->get_allrows();
        
        $q->query("select count(id) as cnt from ".$args['mod_table_name2']." WHERE ".$args['mod_table_name']."_id = ".(int)$_GET['f_id']);
        $all = $q->get_cell();
        $args['pages'] = ceil($all/$npages);
    }
    $template="items.phpt";
  break;
};

if($template!='') { template(dirname(__FILE__). '/templates/'. $template, $args, $errors); }

return $result;
  
?>