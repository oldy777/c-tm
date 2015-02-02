<?php
include_once($_SERVER['DOCUMENT_ROOT']. "/kernel.php");
$kernel['mode'] = 'file';
include_once(KERNEL_DIR. '/kernel.php');
include_once(INCLUDE_DIR. '/images.php');

$path = $_SERVER['DOCUMENT_ROOT']. trim($_GET['path']);

// hack atack
if(strpos($path, '..')!==false)
{
  header('HTTP/1.1 404'); 
  exit(0); 
}

// find

if(!is_file($path)) { $path = $_SERVER['DOCUMENT_ROOT']. "/upload/images/". trim($_GET['path']); }

//if(!is_file($path)) 
//{
//  $tmp = ini_get('upload_tmp_dir');
//  if(!$tmp) { $tmp = dirname(tempnam(' ','upl')); }
//  if(!$tmp) { $tmp = IMAGE_CACHE_DIR; }
//  $path = $tmp. '/'. trim($_GET['path']); 
//}



if(!is_file($path)) 
{ 
  header('HTTP/1.1 404'); 
  exit(0); 
}

// check image type
$img = @getimagesize($path);
if(!$img || !($img[2]==1 || $img[2]==3 || $img[2]==2 || $img[2]==4 || $img[2]==13)) 
{
  header('HTTP/1.1 404'); 
  exit(0); 
}



// check image size
$width = (int)$_GET['w'];
$height = (int)$_GET['h'];
if(($width*$height)>=(30000*3500) || $width < 1 || $height < 1) 
{ 
  header('HTTP/1.1 404'); 
  exit(0); 
}


// color
$color = trim($_GET['c']);
if(strlen($color)!=6) { $color = 'FFFFFF'; }

// quality
$quality = (int)$_GET['q'];
if($quality <= 0) { $quality = 100; }
elseif($quality > 100) { $quality = 100; }

$mirror = $_GET['mirror'];
if($_GET['mirror']=="") $mirror=0;

// function
$func = 'image_thumb';
$mode = trim($_GET['m']);
if($mode=='thumb') { $func = 'image_thumb'; }
elseif($mode=='crop') { $func = 'image_crop'; }
elseif($mode=='resize') { $func = 'image_resize'; }
elseif($mode=='i_resize') { $func = 'img_resize'; }
elseif($mode=='resize_png') { $func = 'image_resize_png'; }
elseif($mode=='thumb_png') { $func = 'image_thumb_png'; }
else { $mode = 'thumb'; }

// cache
$cache = IMAGE_CACHE_DIR. "/image_". urlencode(str_replace('/', '_', str_replace($_SERVER['DOCUMENT_ROOT'],'', $path))). "_{$mode}_{$width}x{$height}_{$color}_{$quality}_{$mirror}.jpg";
if(!file_exists($cache) || (filemtime($path) > filemtime($cache)))
{
  $func($path, $cache, $width, $height, $quality, $color,$mirror);
  setfileperm($cache);
}

// finally 
$etag = md5_file($cache);

header("Content-Type: image/jpeg");
//  header("Content-Disposition: inline; filename=". basename($path));
header('Last-Modified: '. gmdate("D, d M Y H:i:s",filemtime($cache)). ' GMT');
//  header("Cache-Control: post-check=0, pre-check=0", false);
//  header("Pragma: no-cache"); 
header("Expires: ". gmdate("D, d M Y H:i:s",time()+8600*24*2). " GMT");
//  header('Last-Modified: '. gmdate("D, d M Y H:i:s"). ' GMT');
header('ETag: "'. $etag. '" ');
//  header("Cache-Control: no-cache, no-store, must-revalidate");
readfile($cache);

?>