<?php

class watermark3{

    function create_watermark( $main_img_obj, $watermark_img_obj, $alpha_level = 100 ) {
        $alpha_level    /= 100; # convert 0-100 (%) alpha to decimal

        # calculate our images dimensions
        $main_img_obj_w = imagesx( $main_img_obj );
        $main_img_obj_h = imagesy( $main_img_obj );
        $watermark_img_obj_w    = imagesx( $watermark_img_obj );
        $watermark_img_obj_h    = imagesy( $watermark_img_obj );

        # determine center position coordinates
        $main_img_obj_min_x = floor( ( $main_img_obj_w / 1.03 ) - ( $watermark_img_obj_w / 1.03 ) );
        $main_img_obj_max_x = ceil( ( $main_img_obj_w / 1.03 ) + ( $watermark_img_obj_w / 1.03 ) );
        $main_img_obj_min_y = floor( ( $main_img_obj_h / 1.05 ) - ( $watermark_img_obj_h / 1.05 ) );
        $main_img_obj_max_y = ceil( ( $main_img_obj_h / 1.05 ) + ( $watermark_img_obj_h / 1.05 ) );

        # create new image to hold merged changes
        $return_img = imagecreatetruecolor( $main_img_obj_w, $main_img_obj_h );

        # walk through main image
        for( $y = 0; $y < $main_img_obj_h; $y++ ) {
            for( $x = 0; $x < $main_img_obj_w; $x++ ) {
                $return_color   = NULL;

                # determine the correct pixel location within our watermark
                $watermark_x    = $x - $main_img_obj_min_x;
                $watermark_y    = $y - $main_img_obj_min_y;

                # fetch color information for both of our images
                $main_rgb = imagecolorsforindex( $main_img_obj, imagecolorat( $main_img_obj, $x, $y ) );

                # if our watermark has a non-transparent value at this pixel intersection
                # and we're still within the bounds of the watermark image
                if (    $watermark_x >= 0 && $watermark_x < $watermark_img_obj_w &&
                            $watermark_y >= 0 && $watermark_y < $watermark_img_obj_h ) {
                    $watermark_rbg = imagecolorsforindex( $watermark_img_obj, imagecolorat( $watermark_img_obj, $watermark_x, $watermark_y ) );

                    # using image alpha, and user specified alpha, calculate average
                    $watermark_alpha    = round( ( ( 127 - $watermark_rbg['alpha'] ) / 127 ), 2 );
                    $watermark_alpha    = $watermark_alpha * $alpha_level;

                    # calculate the color 'average' between the two - taking into account the specified alpha level
                    $avg_red        = $this->_get_ave_color( $main_rgb['red'],       $watermark_rbg['red'],      $watermark_alpha );
                    $avg_green  = $this->_get_ave_color( $main_rgb['green'], $watermark_rbg['green'],    $watermark_alpha );
                    $avg_blue       = $this->_get_ave_color( $main_rgb['blue'],  $watermark_rbg['blue'],     $watermark_alpha );

                    # calculate a color index value using the average RGB values we've determined
                    $return_color   = $this->_get_image_color( $return_img, $avg_red, $avg_green, $avg_blue );

                # if we're not dealing with an average color here, then let's just copy over the main color
                } else {
                    $return_color   = imagecolorat( $main_img_obj, $x, $y );

                } # END if watermark

                # draw the appropriate color onto the return image
                imagesetpixel( $return_img, $x, $y, $return_color );

            } # END for each X pixel
        } # END for each Y pixel

        # return the resulting, watermarked image for display
        return $return_img;

    } # END create_watermark()

    # average two colors given an alpha
    function _get_ave_color( $color_a, $color_b, $alpha_level ) {
        return round( ( ( $color_a * ( 1 - $alpha_level ) ) + ( $color_b    * $alpha_level ) ) );
    } # END _get_ave_color()

    # return closest pallette-color match for RGB values
    function _get_image_color($im, $r, $g, $b) {
        $c=imagecolorexact($im, $r, $g, $b);
        if ($c!=-1) return $c;
        $c=imagecolorallocate($im, $r, $g, $b);
        if ($c!=-1) return $c;
        return imagecolorclosest($im, $r, $g, $b);
    } # EBD _get_image_color()

} # END watermark API

function image_delete($id)
{
  if($info = image_info($id))
  {
    global $kernel;
    /* @var $q query_mysql */
    $q = &$kernel['db']->query();
    $q->format("DELETE FROM modules_images WHERE id='%d'", $id);
    unlink($_SERVER['DOCUMENT_ROOT']. "/upload/images/". $info['path']);
    return true;
  }
  return false;
}

function image_genpath($ext, $len=8)
{
  $path = md5(uniqid(rand(), true));
  $path = substr($path, 0, $len). '.'. $ext;
  if(file_exists($_SERVER['DOCUMENT_ROOT']. "/upload/images/". $path))
   { $path = image_genpath($ext, $len); }
  return $path;
}

function image_add($srcpath, $section, $name)
{
  $ret = false;
  $section = (int)$section;
  if(is_readable($srcpath) && ($image = getimagesize($srcpath)))
  {
    $types = array(1=>'gif', 2=>'jpg', 4=>'swf', 13=>'swf');
    list($width, $height, $typeid) = $image;
    if($ext = $types[$typeid])
    {
      $path = image_genpath($ext,15);
      $dstpath = $_SERVER['DOCUMENT_ROOT']. "/upload/images/". $path;
      if(copy($srcpath, $dstpath))
      {
        global $kernel;
        setfileperm($dstpath);
        $q = &$kernel['db']->query();
        $q->format("INSERT INTO images SET id='%d',id_section='%d',name='%s',path='%s',width='%d',height='%d',typeid='%d',created='%d',updated='%d'", $kernel['db']->next_id('images'), $section, $name, $path, $width, $height, $typeid, time(), time());
        $ret = $kernel['db']->last_id();
      }
    }
  }
  return $ret;
}

function image_module_add($srcpath, $section, $name, $types = array())
{
  $ret = false;
  if(is_readable($srcpath) && ($image = getimagesize($srcpath)))
  {
    if(!$types)
        $types = array(1=>'gif', 2=>'jpg', 3=>'png', 4=>'swf', 13=>'swf');
    list($width, $height, $typeid) = $image;
    if($ext = $types[$typeid])
    {
      $path = image_genpath($ext,15);
      $dstpath = $_SERVER['DOCUMENT_ROOT']. "/upload/images/". $path;
      if(copy($srcpath, $dstpath))
      {
        global $kernel;
        setfileperm($dstpath);
        $q = &$kernel['db']->query();
        $time = time();
        $q->format("INSERT INTO modules_images SET section='%s',path='%s',width='%d',height='%d',created='%d',updated='%d'", $section, $path, $width, $height, $time, $time);
        $ret = $kernel['db']->last_id();
      }
    }
  }
  return $ret;
}

function image_module_download_add($srcpath, $section, $name,$ext)
{
  $ret = false;

  $path = image_genpath($ext,15);
  $dstpath = $_SERVER['DOCUMENT_ROOT']. "/upload/images/". $path;
  if(copy($srcpath, $dstpath))
  {
    global $kernel;
    setfileperm($dstpath);
    $q = &$kernel['db']->query();
    $time = time();
    $q->format("INSERT INTO modules_images SET section='%s',path='%s',width='%d',height='%d',created='%d',updated='%d'", $section, $path, $width, $height, $time, $time);
    $ret = $kernel['db']->last_id();
  }

  return $ret;
}

function image_module_download_add_new($srcpath, $section, $name,$ext)
{
  $ret = false;

  $path = image_genpath($ext,15);
  $dstpath = $_SERVER['DOCUMENT_ROOT']. "/upload/images/". $path;
  if(copy($srcpath, $dstpath))
  {
    global $kernel;
    setfileperm($dstpath);
    $q = &$kernel['db']->query();
    $time = time();
    $q->format("INSERT INTO modules_images SET section='%s',path='%s',width='%d',height='%d',created='%d',updated='%d'", $section, $path, $width, $height, $time, $time);
    $ret = array('id'=>$kernel['db']->last_id(),'path'=>$path);
  }

  return $ret;
}

function flash_add($srcpath, $section, $name,$ext)
{
  $ret = false;
  $section = (int)$section;
  if(is_readable($srcpath))
  {
    list($width, $height, $typeid) = $image;
    $pa=explode(".",$name);

    $dstpath = $_SERVER['DOCUMENT_ROOT']. "/upload/video/". $pa['0'].".".$ext;
    if(copy($srcpath, $dstpath))
    {
      global $kernel;
      setfileperm($dstpath);
      $q = &$kernel['db']->query();
      $q->format("INSERT INTO portfolio_video SET id='%d',id_work='%d',path='%s',width='%d',height='%d',typeid='%d',created='%d',updated='%d'", $kernel['db']->next_id('portfolio_images'), $section, $path, $width, $height, $typeid, time(), time());
      $ret = $kernel['db']->last_id();
    }

  }
  return $ret;
}

function image_info($id)
{
  global $kernel;
  $q = &$kernel['db']->query();
  $q->format("SELECT * FROM modules_images WHERE id='%d'", $id);
  $r = $q->get_row();
  $q->free_result();
  return $r;
}

function image_thumb($srcpath, $dstpath, $width, $height, $quality=100, $bgcolor='FFFFFF', $watermark=false)
{
  $ret = false;
  if(is_readable($srcpath) && ($image = getimagesize($srcpath)))
  {
    list($srcwidth, $srcheight, $typeid) = $image;
    if($typeid==1) { $imgsrc = imagecreatefromgif($srcpath); }
    elseif($typeid==2) { $imgsrc = imagecreatefromjpeg($srcpath); }
    elseif($typeid==3) { $imgsrc = imagecreatefrompng($srcpath); }
    elseif($typeid==4 || $typeid==13)
    {
      $srcpath = $_SERVER['DOCUMENT_ROOT']. "/admin/images/flash.gif";
      $imgsrc = imagecreatefromgif($srcpath);
      $image = getimagesize($srcpath);
      list($srcwidth, $srcheight, $typeid) = $image;
    }
    else { $imgsrc = NULL; }
    if(!$imgsrc) { return false; }

    if($width<=0) { $width = $srcwidth; }
    if($height<=0) { $height = $srcheight; }
    if(!$width) { $width = 1; }
    if(!$height) { $height = 1; }


    $imgdst = (function_exists('imagecreatetruecolor')? imagecreatetruecolor($width, $height) : imagecreate($width, $height));
    if(!$imgdst) { return false; }
    if(strlen($bgcolor)==6)
    {
      list($r) = sscanf(substr($bgcolor, 0, 2), '%x');
      list($g) = sscanf(substr($bgcolor, 2, 2), '%x');
      list($b) = sscanf(substr($bgcolor, 4, 2), '%x');
      imagefill($imgdst, 1, 1, imagecolorallocate($imgdst, $r, $g, $b));
      unset($r, $g, $b);
    }

    $k = (($width < $srcwidth || $height < $srcheight)? min($width/$srcwidth, $height/$srcheight) : 1);
    $dstwidth = ceil($k * $srcwidth);
    $dstheight = ceil($k * $srcheight);
    // center
    $dstx = ceil(($width - $dstwidth)/2);
    $dsty = ceil(($height - $dstheight)/2);

    imagecopyresampled($imgdst, $imgsrc, $dstx, $dsty, 0, 0, $dstwidth, $dstheight, $srcwidth, $srcheight);

    if($watermark){
      $watermark = new watermark3();
      $water = imagecreatefrompng($_SERVER['DOCUMENT_ROOT']."/images/watermark.png");
      $imgdst=$watermark->create_watermark($imgdst,$water,100);
    }

    imagejpeg($imgdst, $dstpath, $quality);
    imagedestroy($imgdst);
    $ret = true;
  }
  return $ret;
}

function image_crop($srcpath, $dstpath, $width, $height, $quality=100, $bgcolor='FFFFFF', $watermark=false)
{
  $ret = false;
  if(is_readable($srcpath) && ($image = getimagesize($srcpath)))
  {
    list($srcwidth, $srcheight, $typeid) = $image;
    if($typeid==1) { $imgsrc = imagecreatefromgif($srcpath); }
    elseif($typeid==2) { $imgsrc = imagecreatefromjpeg($srcpath); }
    elseif($typeid==3) { $imgsrc = imagecreatefrompng($srcpath); }
    elseif($typeid==4 || $typeid==13)
    {
      $srcpath = $_SERVER['DOCUMENT_ROOT']. "/admin/images/flash.gif";
      $imgsrc = imagecreatefromgif($srcpath);
      $image = getimagesize($srcpath);
      list($srcwidth, $srcheight, $typeid) = $image;
    }
    else { $imgsrc = NULL; }
    if(!$imgsrc) { return false; }

    if($width<=0) { $width = $srcwidth; }
    if($height<=0) { $height = $srcheight; }
    if(!$width) { $width = 1; }
    if(!$height) { $height = 1; }

    $imgdst = (function_exists('imagecreatetruecolor')? imagecreatetruecolor($width, $height) : imagecreate($width, $height));
    if(!$imgdst) { return false; }
    if(strlen($bgcolor)==6)
    {
      list($r) = sscanf(substr($bgcolor, 0, 2), '%x');
      list($g) = sscanf(substr($bgcolor, 2, 2), '%x');
      list($b) = sscanf(substr($bgcolor, 4, 2), '%x');
      imagefill($imgdst, 1, 1, imagecolorallocate($imgdst, $r, $g, $b));
      unset($r, $g, $b);
    }

    $k = (($width < $srcwidth || $height < $srcheight)? max($width/$srcwidth, $height/$srcheight) : 1);
    $dstwidth = ceil($k * $srcwidth);
    $dstheight = ceil($k * $srcheight);
    // center
    $dstx = ceil(($width - $dstwidth)/2);
    $dsty = ceil(($height - $dstheight)/2);

    imagecopyresampled($imgdst, $imgsrc, $dstx, $dsty, 0, 0, $dstwidth, $dstheight, $srcwidth, $srcheight);

    if($watermark){
      $watermark = new watermark3();
      $water = imagecreatefrompng($_SERVER['DOCUMENT_ROOT']."/images/watermark.png");
      $imgdst=$watermark->create_watermark($imgdst,$water,100);
    }

    imagejpeg($imgdst, $dstpath, $quality);
    imagedestroy($imgdst);
    $ret = true;
  }
  return $ret;
}

function img_resize($src, $out, $width, $height, $quality = 100, $color = 0xFFFFFF, $gs=false, $watermark = false)
{
    $color = 0xFFFFFF;
    // Если файл не существует
    if (!file_exists($src)) {
        return false;
    }
    // Получаем массив с информацией о размере и формате картинки (mime)
    $size = getimagesize($src);

    // Исходя из формата (mime) картинки, узнаем с каким форматом имеем дело
    $format = strtolower(substr($size['mime'], strpos($size['mime'], '/') + 1));
    //и какую функцию использовать для ее создания
    $picfunc = 'imagecreatefrom'.$format;

    // Вычилсить горизонтальное соотношение
    $gor = $width  / $size[0];
    // Вертикальное соотношение
    $ver = $height / $size[1];

    // Если не задана высота, вычислить изходя из ширины, пропорционально
    if ($height == 0) {
        $ver = $gor;
        $height  = $ver * $size[1];
    }
	// Так же если не задана ширина
	elseif ($width == 0) {
        $gor = $ver;
        $width   = $gor * $size[0];
    }

    // Формируем размер изображения
    $ratio   = min($gor, $ver);
    // Нужно ли пропорциональное преобразование
//    if ($gor == $ratio)
        $use_gor = true;
//    else
//        $use_gor = false;

    $new_width   = $use_gor  ? $width  : floor($size[0] * $ratio);
    $new_height  = $use_gor ? $height : floor($size[1] * $ratio);
    $new_left    = $use_gor  ? 0 : floor(($width - $new_width)   / 2);
    $new_top     = $use_gor ? 0 : floor(($height - $new_height) / 2);

    $picsrc  = $picfunc($src);
    // Создание изображения в памяти
    $picout = imagecreatetruecolor($width, $height);

    // Заполнение цветом
    imagefill($picout, 0, 0, $color);

    // Нанесение старого на новое
    imagecopyresampled($picout, $picsrc, $new_left, $new_top, 0, 0, $new_width, $new_height, $size[0], $size[1]);

    if($watermark){
      $watermark = new watermark3();
      $water = imagecreatefrompng($_SERVER['DOCUMENT_ROOT']."/images/watermark.png");
      $picout=$watermark->create_watermark($picout,$water,100);
    }

    // Создание файла изображения
    imagejpeg($picout, $out, $quality);

    // Очистка памяти
    imagedestroy($picsrc);
    imagedestroy($picout);

    return true;
}

function image_resize($srcpath, $dstpath, $width, $height, $quality=100, $bgcolor='FFFFFF', $gs=false, $watermark=false)
{
  $ret = false;
  if(is_readable($srcpath) && ($image = getimagesize($srcpath)))
  {
    list($srcwidth, $srcheight, $typeid) = $image;
    if($typeid==1) { $imgsrc = imagecreatefromgif($srcpath); }
    elseif($typeid==2) { $imgsrc = imagecreatefromjpeg($srcpath); }
    elseif($typeid==3) { $imgsrc = imagecreatefrompng($srcpath); }
    elseif($typeid==4 || $typeid==13)
    {
      $srcpath = $_SERVER['DOCUMENT_ROOT']. "/admin/images/flash.gif";
      $imgsrc = imagecreatefromgif($srcpath);
      $image = getimagesize($srcpath);
      list($srcwidth, $srcheight, $typeid) = $image;
    }
    else { $imgsrc = NULL; }
    if(!$imgsrc) { return false; }

    list($width, $height) = image_preresize($srcwidth, $srcheight, $width, $height);

    if($width<=0) { $width = $srcwidth; }
    if($height<=0) { $height = $srcheight; }
    if(!$width) { $width = 1; }
    if(!$height) { $height = 1; }

    $imgdst = (function_exists('imagecreatetruecolor')? imagecreatetruecolor($width, $height) : imagecreate($width, $height));
    if(!$imgdst) { return false; }
    if(strlen($bgcolor)==6)
    {
      list($r) = sscanf(substr($bgcolor, 0, 2), '%x');
      list($g) = sscanf(substr($bgcolor, 2, 2), '%x');
      list($b) = sscanf(substr($bgcolor, 4, 2), '%x');
      imagefill($imgdst, 1, 1, imagecolorallocate($imgdst, $r, $g, $b));
      unset($r, $g, $b);
    }

    $k = (($width < $srcwidth || $height < $srcheight)? max($width/$srcwidth, $height/$srcheight) : 1);
    $dstwidth = ceil($k * $srcwidth);
    $dstheight = ceil($k * $srcheight);
    // center
    $dstx = ceil(($width - $dstwidth)/2);
    $dsty = ceil(($height - $dstheight)/2);

    imagecopyresampled($imgdst, $imgsrc, $dstx, $dsty, 0, 0, $dstwidth, $dstheight, $srcwidth, $srcheight);

    $imgdst2=$imgdst;
    if($gs){
        imagefilter($imgdst, IMG_FILTER_GRAYSCALE);

        imagefill($imgdst, 1, 1, imagecolorallocate($imgdst, '239', '239', '247'));
        imagefill($imgdst, 1, $height-1, imagecolorallocate($imgdst, '239', '239', '247'));
        imagefill($imgdst, $width-1, 1, imagecolorallocate($imgdst, '239', '239', '247'));
        imagefill($imgdst, $width-1, $height-1, imagecolorallocate($imgdst, '239', '239', '247'));
    }

    if($watermark){
      $watermark = new watermark3();
      $water = imagecreatefrompng($_SERVER['DOCUMENT_ROOT']."/images/watermark.png");
      $imgdst=$watermark->create_watermark($imgdst,$water,100);
    }

    imagejpeg($imgdst, $dstpath, $quality);
    imagedestroy($imgdst);
    $ret = true;
  }
  return $ret;
}


function image_resize_png($srcpath, $dstpath, $width, $height, $quality=100, $bgcolor='FFFFFF', $gs=false, $watermark=false)
{
  $ret = false;
  if(is_readable($srcpath) && ($image = getimagesize($srcpath)))
  {
    list($srcwidth, $srcheight, $typeid) = $image;
    if($typeid==1) { $imgsrc = imagecreatefromgif($srcpath); }
    elseif($typeid==2) { $imgsrc = imagecreatefromjpeg($srcpath); }
    elseif($typeid==3) { $imgsrc = imagecreatefrompng($srcpath); }
    elseif($typeid==4 || $typeid==13)
    {
      $srcpath = $_SERVER['DOCUMENT_ROOT']. "/admin/images/flash.gif";
      $imgsrc = imagecreatefromgif($srcpath);
      $image = getimagesize($srcpath);
      list($srcwidth, $srcheight, $typeid) = $image;
    }
    else { $imgsrc = NULL; }
    if(!$imgsrc) { return false; }

    list($width, $height) = image_preresize($srcwidth, $srcheight, $width, $height);

    if($width<=0) { $width = $srcwidth; }
    if($height<=0) { $height = $srcheight; }
    if(!$width) { $width = 1; }
    if(!$height) { $height = 1; }
    
    
    $imgdst = (function_exists('imagecreatetruecolor')? imagecreatetruecolor($width, $height) : imagecreate($width, $height));
    
    imageAlphaBlending($imgsrc,1);
    imageAlphaBlending($imgdst,0);
    imagesavealpha($imgsrc,1);
    imagesavealpha($imgdst,1);


    $k = (($width < $srcwidth || $height < $srcheight)? max($width/$srcwidth, $height/$srcheight) : 1);
    $dstwidth = ceil($k * $srcwidth);
    $dstheight = ceil($k * $srcheight);
    // center
    $dstx = ceil(($width - $dstwidth)/2);
    $dsty = ceil(($height - $dstheight)/2);

    imagecopyresampled($imgdst, $imgsrc, $dstx, $dsty, 0, 0, $dstwidth, $dstheight, $srcwidth, $srcheight);

    $imgdst2=$imgdst;
    if($gs){
        imagefilter($imgdst, IMG_FILTER_GRAYSCALE);

        imagefill($imgdst, 1, 1, imagecolorallocate($imgdst, '239', '239', '247'));
        imagefill($imgdst, 1, $height-1, imagecolorallocate($imgdst, '239', '239', '247'));
        imagefill($imgdst, $width-1, 1, imagecolorallocate($imgdst, '239', '239', '247'));
        imagefill($imgdst, $width-1, $height-1, imagecolorallocate($imgdst, '239', '239', '247'));
    }

    if($watermark){
      $watermark = new watermark3();
      $water = imagecreatefrompng($_SERVER['DOCUMENT_ROOT']."/images/watermark.png");
      $imgdst=$watermark->create_watermark($imgdst,$water,100);
    }
    
     
    imagepng($imgdst, $dstpath);
    imagedestroy($imgdst);
    $ret = true;
  }
  return $ret;
}

function image_thumb_png($srcpath, $dstpath, $width, $height, $quality=100, $bgcolor='FFFFFF',$gs=false, $watermark=false)
{
  $ret = false;
  if(is_readable($srcpath) && ($image = getimagesize($srcpath)))
  {
    list($srcwidth, $srcheight, $typeid) = $image;
    if($typeid==1) { $imgsrc = imagecreatefromgif($srcpath); }
    elseif($typeid==2) { $imgsrc = imagecreatefromjpeg($srcpath); }
    elseif($typeid==3) { $imgsrc = imagecreatefrompng($srcpath); }
    elseif($typeid==4 || $typeid==13)
    {
      $srcpath = $_SERVER['DOCUMENT_ROOT']. "/admin/images/flash.gif";
      $imgsrc = imagecreatefromgif($srcpath);
      $image = getimagesize($srcpath);
      list($srcwidth, $srcheight, $typeid) = $image;
    }
    else { $imgsrc = NULL; }
    if(!$imgsrc) { return false; }

    if($width<=0) { $width = $srcwidth; }
    if($height<=0) { $height = $srcheight; }
    if(!$width) { $width = 1; }
    if(!$height) { $height = 1; }


    $imgdst = (function_exists('imagecreatetruecolor') ? imagecreatetruecolor($width, $height) : imagecreate($width, $height));

    ImageFill($imgdst,0,0,IMG_COLOR_TRANSPARENT);
    
    imageAlphaBlending($imgsrc,0);
    imageAlphaBlending($imgdst,0);
    imagesavealpha($imgsrc,1);
    imagesavealpha($imgdst,1);

    $k = (($width < $srcwidth || $height < $srcheight)? min($width/$srcwidth, $height/$srcheight) : 1);
    $dstwidth = ceil($k * $srcwidth);
    $dstheight = ceil($k * $srcheight);
    // center
    $dstx = ceil(($width - $dstwidth)/2);
    $dsty = ceil(($height - $dstheight)/2);

    imagecopyresampled($imgdst, $imgsrc, $dstx, $dsty, 0, 0, $dstwidth, $dstheight, $srcwidth, $srcheight);
    
    
    $imgdst2=$imgdst;

    if($watermark){
      $watermark = new watermark3();
      $water = imagecreatefrompng($_SERVER['DOCUMENT_ROOT']."/images/watermark.png");
      $imgdst=$watermark->create_watermark($imgdst,$water,100);
    }

    imagepng($imgdst, $dstpath);
    imagedestroy($imgdst);
    $ret = true;
  }
  return $ret;
}

function image_preresize($width, $height, $maxw=NULL, $maxh=NULL)
{
  $k = $width/$height;
  if($maxw && $width > $maxw) { $height = (($width = $maxw) * (1/$k)); }
  elseif($maxh && $height > $maxh) { $width = (($height = $maxh) * $k); }
  return array(ceil($width), ceil($height));
}

?>