<?php

// url validator
function is_url($str)
{
  return (preg_match('~^(http|https|ftp):\/\/[a-z0-9\/:_\-_\.\?\$,~\=#&%\+]+$~i', $str) > 0);
}

// email validator
function is_email($str)
{
  return (preg_match('~^[_a-zA-Z\d\-\.]+@([_a-zA-Z\d\-]+(\.[_a-zA-Z\d\-]+)*)$~', $str) > 0);
}

// alpha numeric validator
function is_alphanum($str)
{
  return (preg_match('~^[a-zA-Z -пЂ-џ\d]+$~', $str) > 0);
}

// phone number
function is_phone($str)
{
  return (preg_match('~^(\+\d *)?(\(\d+\) *)?(\d+(-\d*)*)$~', $str) > 0);
}

function checkModFields($f) 
{
    global $kernel;
    /* @var $q query_mysql */
    $q = &$kernel['db']->query();
    $val = '';
    switch ($f['type']) {
        case 'image':
            if($_POST[$f['name'].'_del']==1){
                $val=0;
            }
            if($_FILES[$f['name']]['error']==0){
                if(!function_exists('image_module_add'))
                {
                    include_once(INCLUDE_DIR. '/images.php');
                }
                $val = image_module_add($_FILES[$f['name']]['tmp_name'],$args['mod_table_name'], $_FILES[$f['name']]['name']);
            }
            break;
        
        case 'file':
            if($_POST[$f['name'].'_del']==1){
                $val = 0;
              }
              if($_FILES[$f['name']]['error']===0 && is_readable($_FILES[$f['name']]['tmp_name']))
              {
                $args['ext'] = strtolower(end(explode('.',$_FILES[$f['name']]['name'])));
                if(!in_array($args['ext'], $kernel['config']['files']['ext'])) { $errors['ext'] = true; }
                if(empty($errors))
                {

                  $args['name'] = preg_replace('~(\.[a-zA-Z0-9]+)$~','',$_FILES[$f['name']]['name']);
                  $q->format("INSERT INTO modules_files SET section='%s',name='%s',path='%s',ext='%s',mime='%s',size='%d',info='".$args['info']."',created='%d',updated='%d'", 
                    $args['mod_table_name'], $args['name']. ".". $args['ext'], '', $args['ext'], $_FILES[$f['name']]['type'], $_FILES[$f['name']]['size'], time(), time());
                  $id = $kernel['db']->last_id('files');
                  if($args['name']=='') { $args['name'] = $id; }
                  $path = NULL;
                  do
                  {
                    $name = preg_replace('~[^a-z0-9\-.]+~', '_', strtolower(translit($args['name'])));
                    if($path!==NULL) { $name.= rand(1, 100); }
                    print $name;
                    $path = UPLOAD_FILES_PATH. $name. ".". $args['ext'];
                  }
                  while(file_exists($_SERVER['DOCUMENT_ROOT']. $path));
                  copy($_FILES[$f['name']]['tmp_name'], $_SERVER['DOCUMENT_ROOT']. $path);
                  setfileperm($_SERVER['DOCUMENT_ROOT']. $path);
                  $q->format("UPDATE modules_files SET path='%s' WHERE id='%d'", $path, $id);

                    $q->query("select id from modules_files order by id desc limit 0,1");
                      $val = $q->get_cell();
                }
              }
            break;
        
        case 'date':
            $val = parse_date(trim($_POST[$f['name']]));
            break;
        
        case 'pass':
            if(isset($_POST[$f['name']]) && $_POST[$f['name']])
                $val = $_POST[$f['name']] == $_POST['passwd2'] ? md5($_POST[$f['name']]):'';
            break;
            
        case 'checkbox':
          $val = isset($_POST[$f['name']]) && $_POST[$f['name']] ? 1:0;
            break;

        default:
            $val = $_POST[$f['name']];
            break;
    }
    
    return $val;
}


function getPosForNewItem($table, $reverse, $where = '1=1')
{
    global $kernel;
    /* @var $q query_mysql */
    $q = &$kernel['db']->query();
    if ($reverse) {
        $val = 0;
        $q->query("select * from " . $table." WHERE ".$where);
        $args['items'] = $q->get_allrows();
        foreach ($args['items'] as $i) {
            $pos = $i['pos'] + 1;
            $q->query("update " . $table . " set pos='" . $pos . "' WHERE id = ".$i['id']);
        }
    } else {
        $q->query("select max(pos) from " . $table." WHERE ".$where);
        $tmp = $q->get_cell();
        if ($tmp || $tmp == 0)
            $val = $tmp + 1;
    }
    
    return $val;
}

?>