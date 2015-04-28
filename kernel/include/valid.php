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


class ValuesFnc
{

    const VAL_IMAGE = 'image';
    const VAL_FILE = 'file';
    const VAL_DATE = 'date';
    const VAL_PASSWORD = 'pass';
    const VAL_CHECKBOX = 'checkbox';
    const VAL_VARCHAR = 'varchar';
    const VAL_EDITOR = 'editor';
    const VAL_OPTION = 'option';
    const VAL_TEXTAREA = 'text';

    static function checkModFields($f, $val) 
    {
        global $kernel;
        /* @var $q query_mysql */
        $q = $kernel['db']->query();
        switch ($f['type']) {
            case 'image':
                if($_POST[$f['name'].'_del']==1){
                    $val[$f['name']]=0;
                }
                if($_FILES[$f['name']]['error']==0){
                    if(!function_exists('image_module_add'))
                    {
                        include_once(INCLUDE_DIR. '/images.php');
                    }
                    $val[$f['name']] = image_module_add($_FILES[$f['name']]['tmp_name'],$args['mod_table_name'], $_FILES[$f['name']]['name']);
                }
                break;

            case 'file':
                if($_POST[$f['name'].'_del']==1){
                    $val[$f['name']] = 0;
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
                          $val[$f['name']] = $q->get_cell();
                    }
                  }
                break;

            case 'date':
                $val[$f['name']] = parse_date(trim($_POST[$f['name']]));
                break;

            case 'pass':
                if(isset($_POST[$f['name']]) && $_POST[$f['name']])
                    $val[$f['name']] = $_POST[$f['name']] == $_POST['passwd2'] ? md5($_POST[$f['name']]):'';
                break;

            case 'checkbox':
              $val[$f['name']] = isset($_POST[$f['name']]) && $_POST[$f['name']] ? 1:0;
                break;

            default:
                $val[$f['name']] = $_POST[$f['name']];
                break;
        }

        return $val;
    }
    
    static function getPosForNewItem($table, $reverse, $where = '1=1')
    {
        global $kernel;
        /* @var $q query_mysql */
        $q = $kernel['db']->query();
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
    
    /**
     * Вывод полей в таблицу
     * @param type $item  $item
     * @param type $f  значение из mod_fields
     * @param type $args  $args
     * @param type $link ссылка, если нужна
     * @return string
     */
    static function showValue($item, $f, $args, $link='')
    {
        $val = '';
        switch ($f['type'])
        {
            case 'option':
                $val = $args['options'][$f['name']]['values'][$item[$f['name']]] ;
                break;
            
            case 'date':
                $val = date('d.m.Y',$item[$f['name']]);
                break;

            default:
                $val = mb_substr(htmlspecialchars($item[$f['name']]),0,250,'UTF-8');
                break;
        }
        
        if(isset($f['link']) && $f['link']==1)
        {
           $val = '<a href="'.$link.'" >'.$val.'</a>';
        }
        
        return $val;
    }
    
    static function makeFormValues($f, $args)
    {
        $val = '';
        $args['_field'] = $f;
        switch ($f['type'])
        {
            
            case 'text':
                $val = template(INCLUDE_DIR.'/form_templates/textarea.phpt', $args, array(), true);
                break;
            
            case 'pass':
                $val = template(INCLUDE_DIR.'/form_templates/password.phpt', $args, array(), true);
                break;
            
            default:
                if(file_exists(INCLUDE_DIR.'/form_templates/'.$f['type'].'.phpt'))
                    $val = template(INCLUDE_DIR.'/form_templates/'.$f['type'].'.phpt', $args, array(), true);
                else
                $val = template(INCLUDE_DIR.'/form_templates/varchar.phpt', $args, array(), true);
                break;
        }
        
        return $val;
    }
}

?>