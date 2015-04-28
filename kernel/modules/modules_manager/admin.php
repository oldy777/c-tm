<?php
include_once(INCLUDE_DIR. '/images.php');
include_once(INCLUDE_DIR. '/valid.php');
include_once($_SERVER['DOCUMENT_ROOT']."/editor/ckeditor.php");

$result = array();
$result['title'] = '';
$result['commands'] = array();
$q = $kernel['db']->query();
$action = trim($_GET['act']);
$args = array();
$errors = array();
$template = '';

$args['mod_name']=Array('','модуля','модуль');      # {Название}, lj,добавление {названия}, добавить {название}
$args['mod_table_name']="modules";             # Имя таблицы
$args['mod_pos']=false;                  # Вкл/выкл позиция
$args['mod_pos_reverse']=false;
$args['mod_fields']=Array(              # Поля
  Array('name'=>'title','title'=>'Название','type'=>'varchar','view'=>1, 'link'=>1),
  Array('name'=>'name','title'=>'Название(EN)<br /><small>Только буквы, цифры и _</small>','type'=>'varchar','view'=>1, 'link'=>1),
  Array('name'=>'section','title'=>'Секция','type'=>'option','view'=>0),
  Array('name'=>'descr','title'=>'Описание','type'=>'text','view'=>0),
  Array('name'=>'parent_id','title'=>'Родительский модуль','type'=>'option','view'=>0),
  Array('name'=>'public','title'=>'Создать public часть','type'=>'option','view'=>0),
  Array('name'=>'table','title'=>'Создать таблицу','type'=>'option','view'=>0)
);

$args['options']['act']['values'][1] = 'Да';
$args['options']['act']['values'][0] = 'Нет';

$args['mod_view']=1;

switch($action){
  case "additem":
    if($_SERVER['REQUEST_METHOD']=='POST'){
      foreach($args['mod_fields'] as $f){
        if($f['type']=="image"){
          if($_FILES[$f['name']]['error']==0){
            image_module_add($_FILES[$f['name']]['tmp_name'],$args['mod_table_name'], $_FILES[$f['name']]['name']);
            $q->query("select * from modules_images");
            $images=$q->get_allrows();
            $val[$f['name']]=$images[($q->num_rows()-1)]['id'];
          };
        }
        if($f['type']=="varchar"){
          $val[$f['name']]=$_POST[$f['name']];
        }
        if($f['type']=="text"){
          $val[$f['name']]=$_POST[$f['name']];
        }
        if($f['type']=="editor"){
          $val[$f['name']]=$_POST[$f['name']];
        }
        if($f['type']=="pass"){
          $val[$f['name']]=$_POST[$f['name']] == $_POST['passwd2'] ? md5($_POST[$f['name']]):'';
        }
        if($f['type']=="option"){
          $val[$f['name']]=$_POST[$f['name']];
        }
        if($f['type']=="date"){
          $val[$f['name']]=parse_date(trim($_POST[$f['name']]));
        }

      }
      
      if($args['mod_pos']){
        if($args['mod_pos_reverse']){
          $val['pos']=0;
          $q->query("select * from ".$args['mod_table_name']);
          $args['items']=$q->get_allrows();
          foreach($args['items'] as $i){
            $pos=$i['pos']+1;
            $q->query("update ".$args['mod_table_name']." set pos='".$pos."' ");
          }
        }else{
          $q->query("select max(pos) from ".$args['mod_table_name']);
          $tmp = $q->get_cell();
          if($tmp)
            $val['pos']=$tmp + 1;
        }
      }

      $q->format("insert into ".$args['mod_table_name']." set %s",$val);
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
        if($f['type']=="image"){
          if($_POST[$f['name'].'_del']==1){
            $val[$f['name']]=0;
          }
          if($_FILES[$f['name']]['error']==0){
            image_module_add($_FILES[$f['name']]['tmp_name'],$args['mod_table_name'], $_FILES[$f['name']]['name']);
            $q->query("select * from modules_images");
            $images=$q->get_allrows();
            $val[$f['name']]=$images[($q->num_rows()-1)]['id'];
          };
        }

        if($f['type']=="varchar"){
          $val[$f['name']]=$_POST[$f['name']];
        }
        if($f['type']=="text"){
          $val[$f['name']]=$_POST[$f['name']];
        }
        if($f['type']=="editor"){
          $val[$f['name']]=$_POST[$f['name']];
        }
        if($f['type']=="option"){
          $val[$f['name']]=$_POST[$f['name']];
        }
        if($f['type']=="date"){
          $val[$f['name']]=parse_date(trim($_POST[$f['name']]));
        }
      }

      $q->format("update ".$args['mod_table_name']." set %s where id='".$_GET['id']."'",$val);
      http_redirect("?mod=".$_GET['mod']);
    }else{
      $result['commands'][] = array('path'=>'/admin/?mod='.$_GET['mod'].'&f_id='.$_GET['f_id'], 'title'=>'К списку');
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
    $q->query("delete from ".$args['mod_table_name']." where id='".$_GET['id']."'");
    http_redirect("?mod=".$_GET['mod']);
  break;
  case 'upitem':
    $id = intval($_GET['id']);
    $q->query("select pos from ".$args['mod_table_name']." where id='".$id."'");
    $num=$q->get_cell();
    $numi=$num-1;
    $q->query("update ".$args['mod_table_name']." set pos='".$num."' where pos='".$numi."'");
    $q->query("update ".$args['mod_table_name']." set pos='".$numi."' where id='".$id."'");
    http_redirect("?mod=".$_GET['mod']);
  break;
  case 'downitem':
    $id = intval($_GET['id']);
    $q->query("select pos from ".$args['mod_table_name']." where id='".$id."'");
    $num=$q->get_cell();
    $numi=$num+1;
    $q->query("update ".$args['mod_table_name']." set pos='".$num."' where pos='".$numi."'");
    $q->query("update ".$args['mod_table_name']." set pos='".$numi."' where id='".$id."'");
    http_redirect("?mod=".$_GET['mod']);
  break;
  default:
    $result['commands'][] = array('path'=>'/admin/?mod='.$_GET['mod'].'&act=additem', 'title'=>'Добавить '.$args['mod_name'][2]);
    $args['items'] = array();
    $q->query("select * from ".$args['mod_table_name']." order by ".(($args['mod_pos'])?'pos':'id DESC'));
    $args['items'] = $q->get_allrows();
    $template="items.phpt";
  break;
};

if($template!='') { template(dirname(__FILE__). '/templates/'. $template, $args, $errors); }

return $result;

?>