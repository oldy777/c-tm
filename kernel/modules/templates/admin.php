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

# Configurate

$args['mod_sections']=Array(
  Array('name'=>'templates','title'=>'Основное','fields'=>
    Array(
      Array('name'=>'t_email','title'=>'От кого (email)','type'=>'text'),
      Array('name'=>'t_name','title'=>'От кого (имя)','type'=>'text'),
      Array('name'=>'t_header','title'=>'Шапка шаблона','type'=>'textarea'),
      Array('name'=>'t_footer','title'=>'Подвал шаблона','type'=>'textarea'),
    )
  ),
  Array('name'=>'templates','title'=>'Регистрация','fields'=>
    Array(
      Array('name'=>'t_register_t','title'=>'Тема письма','type'=>'text'),
      Array('name'=>'t_register','title'=>'Шаблон','type'=>'textarea'),
    )
  ),
/*
  Array('name'=>'contacts','title'=>'Контакты','fields'=>
    Array(
      Array('name'=>'email','title'=>'E-mail','type'=>'text'),
      Array('name'=>'phone','title'=>'Телефон','type'=>'text'),
      Array('name'=>'address','title'=>'Адрес','type'=>'text'),
      Array('name'=>'map','title'=>'Карта','type'=>'textarea'),
    )
  ),
  Array('name'=>'mail','title'=>'E-mail адреса для приема вопросов с сайта','fields'=>
    Array(
      Array('name'=>'mail1','title'=>'E-mail','type'=>'text'),
      Array('name'=>'mail2','title'=>'E-mail','type'=>'text'),
      Array('name'=>'mail3','title'=>'E-mail','type'=>'text'),
    )
  ),
*/
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
              $val['value']="noneupload";
              if($_FILES[$f['name']]['error']===0 && is_readable($_FILES[$f['name']]['tmp_name']))
              {
                $args['ext'] = strtolower(end(explode('.',$_FILES[$f['name']]['name'])));
                if(!in_array($args['ext'], $kernel['config']['files']['ext'])) { $errors['ext'] = true; }
                if(empty($errors))
                {

                  $args['name'] = preg_replace('~(\.[a-zA-Z0-9]+)$~','',$_FILES[$f['name']]['name']);
                  $q->format("INSERT INTO modules_files SET id='%d',section='%s',name='%s',path='%s',ext='%s',mime='%s',size='%d',info='".$args['info']."',created='%d',updated='%d'",
                  $kernel['db']->next_id('files'), 'prices', $args['name']. ".". $args['ext'], '', $args['ext'], $_FILES[$f['name']]['type'], $_FILES[$f['name']]['size'], time(), time());
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
              	  $val['value']=$q->get_cell();
                }
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