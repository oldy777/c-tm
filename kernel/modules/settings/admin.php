<?php

include_once(INCLUDE_DIR. '/images.php');
include_once(INCLUDE_DIR. '/valid.php');
include_once($_SERVER['DOCUMENT_ROOT']."/editor/ckeditor.php");

$result = array();
$result['title'] = '';
$result['commands'] = array();
$q = &$kernel['db']->query();
$action = trim($_GET['act']);
$args = array();
$errors = array();
$template = '';

# Configurate

$args['mod_sections']=Array(
  Array('name'=>'main','title'=>'Название сайта','fields'=>
    Array(
      Array('name'=>'sitename_ru','title'=>'Название сайта','type'=>'text'),
    )
  ),
    
  Array('name'=>'contact','title'=>'Контакты','fields'=>
    Array(
      Array('name'=>'mail','title'=>'Email','type'=>'text'),
      Array('name'=>'phone','title'=>'Телефон','type'=>'text'),
    )
  ),
   Array('name'=>'page','title'=>'Прочее','fields'=>
    Array(
      Array('name'=>'copyright','title'=>'Копирайт','type'=>'text'),
    )
  ),
);

# Don't change code after this line

switch($action){
  case "save":
    if($_SERVER['REQUEST_METHOD']=='POST'){
		  foreach($args['mod_sections'] as $s){
    	  if(sizeof($s['fields'])>0){
    		  foreach($s['fields'] as $f){
            if($f['type']=="image"){
              if($_FILES[$f['name']]['error']==0){
                image_module_add($_FILES[$f['name']]['tmp_name'],$args['mod_table_name'], $_FILES[$f['name']]['name']);
                $q->query("select * from modules_images");
                $images=$q->get_allrows();
                $val['value']=$images[($q->num_rows()-1)]['path'];
              }else{
                $val['value']="noneupload";
              }
            }
            if($f['type']=="text"){
              $val['value']=$_POST[$f['name']];
            }
            if($f['type']=="textarea"){
              $val['value']=$_POST[$f['name']];
            }
            if($f['type']=="editor"){
              $val['value']=$_POST[$f['name']];
            }
            if($f['type']=="option"){
              $val['value']=$_POST[$f['name']];
            }
            if($f['type']=="date"){
              $val['value']=parse_date(trim($_POST[$f['name']]));
            }
   		   if($f['type']=="file"){
              if($_FILES[$f['name']]['error']==0){
                image_module_add($_FILES[$f['name']]['tmp_name'],$args['mod_table_name'], $_FILES[$f['name']]['name']);
                $q->query("select * from modules_files");
                $images=$q->get_allrows();
                $val['value']=$images[($q->num_rows()-1)]['path'];
              }else{
                $val['value']="noneupload";
              }
            }
            $val['module']=$s['name'];
            $val['var']=$f['name'];
            $val['title']=$f['title'];
            if($val['value']!="noneupload"){
            $q->query("select value from modules_config where module='".$val['module']."' and var='".$val['var']."'");
              if($q->num_rows()>0){
                $q->format("update modules_config set %s where var='".$val['var']."'",$val);
              }else{
                $q->format("insert into modules_config set %s",$val);
              }
            }
          }

        }
      }
    }
    http_redirect("?mod=".$_GET['mod']);
  break;
  default:
    $q->query("select * from modules_config");
    $config=$q->get_allrows();
    $x=0;
    foreach($config as $c){
      $args['item'][$c['var']]=$c['value'];
      $x++;
    }

    foreach($args['mod_sections'] as $s){
      if(sizeof($s['fields'])>0){
        foreach($s['fields'] as $f){
          if($f['type']=="image"){
            $q->query("select * from modules_images where path='".$args['item'][$f['name']]."'");
            $args['item'][$f['name'].'_image']=$q->get_row();
          }
          if($f['type']=="file"){
            $q->query("select * from modules_files where id='".$args['item'][$f['name']]."'");
            $args['item'][$f['name'].'_file']=$q->get_row();
          }
        }
      }
    }

    $template="settings.phpt";
  break;
};

if($template!='') { template(dirname(__FILE__). '/templates/'. $template, $args, $errors); }

return $result;

?>