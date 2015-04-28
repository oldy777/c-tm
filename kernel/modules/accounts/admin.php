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

$args['mod_name']=Array('','пользователя','пользователя');      # {Название}, lj,добавление {названия}, добавить {название}
$args['mod_table_name']="accounts";             # Имя таблицы
$args['mod_pos']=false;                  # Вкл/выкл позиция
$args['mod_pos_reverse']=true;
$args['mod_fields']=Array(              # Поля
  Array('name'=>'login','title'=>'Логин','type'=>'varchar','view'=>1),
  Array('name'=>'city','title'=>'Город','type'=>'varchar','view'=>1),
  Array('name'=>'partner','title'=>'Партнер','type'=>'option','view'=>0),
  Array('name'=>'passwd','title'=>'Пароль','type'=>'pass','view'=>0),
);
$args['options']['partner']['values'][0]="Нет";
$args['options']['partner']['values'][1]="Да";

$args['mod_view']=1;

switch($action){
  case "additem":
    if($_SERVER['REQUEST_METHOD']=='POST'){
      if(isset($_POST['login']) && $_POST['login']){
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
            $q->query("update ".$args['mod_table_name']." set pos='".$pos."' where id='".$i['id']."'");
          }
        }else{
          $q->query("select * from ".$args['mod_table_name']);
          $val['pos']=$q->num_rows();
        }
      }
      $q->format("insert into ".$args['mod_table_name']." set %s",$val);
      http_redirect("?mod=".$_GET['mod']);
    }
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
        if($f['type']=="file"){
          if($_POST[$f['name'].'_del']==1){
            $val[$f['name']]=0;
          }
          if($_FILES[$f['name']]['error']===0 && is_readable($_FILES[$f['name']]['tmp_name']))
          {
            $args['ext'] = strtolower(end(explode('.',$_FILES[$f['name']]['name'])));
            if(!in_array($args['ext'], $kernel['config']['files']['ext'])) { $errors['ext'] = true; }
            if(empty($errors))
            {

              $args['name'] = preg_replace('~(\.[a-zA-Z0-9]+)$~','',$_FILES[$f['name']]['name']);
              $q->format("INSERT INTO modules_files SET id='%d',section='%s',name='%s',path='%s',ext='%s',mime='%s',size='%d',info='".$args['info']."',created='%d',updated='%d'",
              $kernel['db']->next_id('files'), $args['mod_table_name'], $args['name']. ".". $args['ext'], '', $args['ext'], $_FILES[$f['name']]['type'], $_FILES[$f['name']]['size'], time(), time());
              $id = $kernel['db']->last_id('files');
              if($args['name']=='') { $args['name'] = $id; }
              $path = NULL;
              do
              {
                $name = preg_replace('~[^a-z0-9\-.]+~', '_', strtolower(translit($args['name'])));
                if($path!==NULL) { $name.= rand(1, 100); }
                print $name;
                $path = "/upload/files/". $name. ".". $args['ext'];
              }
              while(file_exists($_SERVER['DOCUMENT_ROOT']. $path));
              copy($_FILES[$f['name']]['tmp_name'], $_SERVER['DOCUMENT_ROOT']. $path);
              setfileperm($_SERVER['DOCUMENT_ROOT']. $path);
              $q->format("UPDATE modules_files SET path='%s' WHERE id='%d'", $path, $id);

    	        $q->query("select id from modules_files order by id desc limit 0,1");
          	  $val[$f['name']]=$q->get_cell();
            }
          }
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
      $result['commands'][] = array('path'=>'/admin/?mod='.$_GET['mod'], 'title'=>'К списку');
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
    $q->query("select * from ".$args['mod_table_name']." order by ".(($args['mod_pos'])?'pos':'name'));
    $args['items']=$q->get_allrows();
    $template="items.phpt";
  break;
};

if($template!='') { template(dirname(__FILE__). '/templates/'. $template, $args, $errors); }

return $result;

?>