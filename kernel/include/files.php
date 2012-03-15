<?php

function files_section_tree($id, $level=0)
{
  $ret = array();
  global $kernel;
  $q = &$kernel['db']->query();
  $q->format("SELECT id,name FROM files_sections WHERE id_parent='%d' ORDER BY name", $id);
  while($r = $q->get_row())
  {
    $r['level'] = $level;
    $ret[] = $r;
    $ret = array_merge($ret, files_section_tree($r['id'], $level+1));
  }
  $q->free_result();
  return $ret;
}

function uniquepath($name='', $dir='', $allowed=array())
{
  $name = trim($name);
  if($name=='') { $name = substr(md5(uniqid(rand(),true)), 0, 8); }
  else { $name = preg_replace('~[^a-z0-9\-.]+~', '_', strtolower(translit($name))); }

  $dir = trim($dir);
  if($dir=='') { $dir = '/upload/'; }
  if(!is_dir($dir)) { $dir = $_SERVER['DOCUMENT_ROOT']. $dir; }
  if(!is_dir($dir)) { return false; }
  $dir = realpath($dir);

  if(empty($allowed) || !is_array($allowed))
  {
    global $kernel;
    $allowed = $kernel['config']['files']['ext'];
  }

  $ext = trim(end(explode('.', $name)));
  if($ext=='' || !in_array($ext, $allowed)) { $ext.= 'bin'; }

  $i = 0;
  $path = NULL;
  do
  {
    if($path!==NULL) { $name.= (++$i); }
    $path = $dir. "/". $name. ".". $ext;
  }
  while(file_exists($path));

  return $path;
}

function files_mimename($mime)
{
  static $types = NULL;

  if($types===NULL)
  {
    $types = array('application/x-shockwave-flash' => 'flash',
                   'application/x-zip-compressed' => 'zip',
                   'application/msword' => 'word',
                   'image/pjpeg' => 'jpeg',
                   'image/jpeg' => 'jpeg',
                   'image/gif' => 'gif');
  }
  $mime = strtolower($mime);
  return (isset($types[ $mime ])? $types[ $mime ] : $mime);
}

function files_save()
{
  global $kernel;
  files_clear();
  if(is_array($_FILES))
  {
    $tmp = ini_get('upload_tmp_dir');
    if(!is_writeable($tmp)) { $tmp = dirname(tempnam(' ','upl')); }
    if(!is_writeable($tmp)) { $tmp = TMP_DIR; }
    if(!is_writeable($tmp)) { return false; }
    foreach($_FILES as $k=>$v) if(is_file($v['tmp_name']))
    {
      unset($_SESSION['_FILES'][$k]);
      $dst = tempnam($tmp,'upload');
      if(@copy($v['tmp_name'], $dst))
      {
        @chmod($dst, $kernel['config']['chmod']);
        $_SESSION['_FILES'][$k] = $v;
        $_SESSION['_FILES'][$k]['tmp_name'] = $dst;
      }
    }
    files_load();
    return true;
  }
  else { return false; }
}

function files_load()
{
  global $kernel;
  if(is_array($_SESSION['_FILES']) && is_array($_FILES))
  {
    $_FILES = array_merge($_FILES, $_SESSION['_FILES']);
    return true;
  }
  else { return false; }
}

function files_clear()
{
  global $kernel;
  if(is_array($_SESSION['_FILES']))
  {
    foreach($_SESSION['_FILES'] as $v)
     { @unlink($v['tmp_name']); }
    unset($_SESSION['_FILES']);
    return true;
  }
  else
  {
    unset($_SESSION['_FILES']);
    return false;
  }
}

?>