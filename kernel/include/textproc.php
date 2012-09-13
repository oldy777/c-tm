<?php
function rnamefdate($val){
  switch($val){
  case"01":$val="января";	break;
  case"02":$val="февраля";	break;
  case"03":$val="марта";	break;
  case"04":$val="апреля";	break;
  case"05":$val="мая";		break;
  case"06":$val="июня";		break;
  case"07":$val="июля";		break;
  case"08":$val="августа";	break;
  case"09":$val="сентября";	break;
  case"10":$val="октября";	break;
  case"11":$val="ноября";	break;
  case"12":$val="декабря";	break;
  }
  return $val;
}

function enamefdate($val){
  switch($val){
  case"01":$val="january";	break;
  case"02":$val="february";	break;
  case"03":$val="march";	break;
  case"04":$val="april";	break;
  case"05":$val="may";		break;
  case"06":$val="june";		break;
  case"07":$val="july";		break;
  case"08":$val="august";	break;
  case"09":$val="september";	break;
  case"10":$val="october";	break;
  case"11":$val="november";	break;
  case"12":$val="december";	break;
  }
  return $val;
}

function rname2fdate($val){
  switch($val){
  case"01":$val="янваь";	break;
  case"02":$val="февраль";	break;
  case"03":$val="март";		break;
  case"04":$val="апрель";	break;
  case"05":$val="май";		break;
  case"06":$val="июнь";		break;
  case"07":$val="июль";		break;
  case"08":$val="август";	break;
  case"09":$val="сентябрь";	break;
  case"10":$val="октябрь";	break;
  case"11":$val="ноябрь";	break;
  case"12":$val="декабрь";	break;
  }
  return $val;
}


function time_format($format,$value){
  $rdate=date($format,$value);
  return $rdate;
}
function para($html)
{
  $html = trim($html);
  if($html!='' && !preg_match('~^<p[^>]*>.*?</p>$~is', $html))
    { $html = "<p>\n$html\n</p>"; }
  return $html;
}

function unhtmlspecialchars($html)
{
  static $tt = array();
  if(empty($tt)) { $tt = array_flip(get_html_translation_table(HTML_ENTITIES)); }
  return strtr($html, $tt);
}

// replace \n to &#13;
function nl2ent($str)
{
  return str_replace(array("\r\n","\r","\n"), '&#13;', $str);
}

// replace br tag to \n
function br2nl($str)
{
  return preg_replace("~<br[^>]*?/?>~si", "\n", $str);
}

// replace whitespace to &nbsp;
function nowrap($str)
{
  return preg_replace('~\s~s', '&nbsp;', $str);
}

function nbsp($str) { return nowrap($str); }

// compress whitespace
function pack_ws($text)
{
  return trim(preg_replace('~\s+~s', ' ', $text));
}

// remove tags, replace br tag to \n, replace entitie
function html2text($html)
{
  $text = preg_replace("~<script[^>]*?>.*?</script>~si", '', $html);
  $text = br2nl($text);
  $text = strip_tags($text);
  $text = pack_ws($text);
  $text = html_entity_decode($text);
  return $text;
}


// \n convert, escape special chars
function text2html($text)
{
  $html = htmlspecialchars($text);
  $html = nl2br($html);
  return $html;
}

// get part of text
function text_cut($srctext, $maxlenght=80, $dots=false, $title=false)
{
  $srctext = pack_ws($srctext);
  $text = substr($srctext, 0, $maxlenght);
  $lenght = strrpos($text, ' ');
  if($lenght > (($maxlenght*2)/3)) { $text = substr($text, 0, $lenght); }
  if(strlen($srctext) > strlen($text))
  {
    if($dots) { $text.= '...'; }
    if($title) { $text = '<span title="'. htmlspecialchars($srctext).'">'. htmlspecialchars($text).'<span>'; }
  }
  return $text;
}

// get part of html
function html_cut($srchtml, $maxlenght=80, $dots=false)
{
  $html = html2text($srchtml);
  $html = text_cut($html, $maxlenght, $dots);
  return text2html($html);
}

// lower case
function str2lower($text) { return strtolower($text); }
// upper case
function str2upper($text) { return strtoupper($text); }

// java script mail
function jsmail($email)
{
  $ret = "this.href='mailto'+':'+'";
  $ret.= preg_replace("~(\:|\.|\@)~is", "'+'\\1'+'", htmlspecialchars($email));
  $ret = str_replace('++', '+', $ret);
  $ret.= "';return true;";
  return $ret;
}

function escape($html, $amp=false, $br=false)
{
  if($amp) { $html = str_replace('&', '&amp;', $html); }
  $html = str_replace(array('<','>','"'), array('&lt;','&gt;','&quot;'), $html);
  if($br) { $html = nl2br($html); }
  return $html;
}

function html_options($options, $selected=0)
{
  $ret = '';
  foreach($options as $k=>$v)
  {
    $ret.= '<option value="'. htmlspecialchars($k). '"'. ($selected==$k? ' selected="selected"' : ""). ">". htmlspecialchars($v). "</option>";
  }
  return $ret;
}


function utf8_to_cp1251($text)
{
  static $t = array('\xD0\x81'=>'\xA8','\xD1\x91'=>'\xB8','\xD0\x8E'=>'\xA1','\xD1\x9E'=>'\xA2',
                    '\xD0\x84'=>'\xAA','\xD0\x87'=>'\xAF','\xD0\x86'=>'\xB2','\xD1\x96'=>'\xB3',
                    '\xD1\x94'=>'\xBA','\xD1\x97'=>'\xBF','\xD3\x90'=>'\x8C','\xD3\x96'=>'\x8D',
                    '\xD2\xAA'=>'\x8E','\xD3\xB2'=>'\x8F','\xD3\x91'=>'\x9C','\xD3\x97'=>'\x9D',
                    '\xD2\xAB'=>'\x9E','\xD3\xB3'=>'\x9F');
  return preg_replace('~([\xD0-\xD1])([\x80-\xBF])~se', 'isset($t["$0"])? $t["$0"] : chr(ord("$2")+("$1"=="\xD0"? 0x30 : 0x70))', $text);
}

function page2offset($page, $size) { return floor(($page-1) * $size); }

// page list
function pages($cur, $rows, $size=10, $url='')
{
  $ret = array();
  if($size<=0) { $size = 10; }
  $ret['size'] = $size;
  $ret['rows'] = $rows;
  $count = ceil($rows / $size);
  if($cur > $count || $cur==-1) { $cur = $count; }
  if($cur<=0) { $cur = 1; }

  if($url=='') { $url = $_SERVER['PHP_SELF']. '?page='; }
  $ret['url'] = $url;
  $url = htmlspecialchars($url);
  $content = '';
  if($count > 1)
  {
    if($cur > 2 && $count > 3) { $content.= ' <a href="'. $url. '1'. '">начало</a> '; }
    if($cur!=1 && $count > 2) { $content.= ' <a href="'. $url. ($cur-1). '">назад</a> '; }

    $lb = 0; $le = 0;
    $rb = 0; $re = 0;

    $left = false;
    $right = false;

    if($count > 25)
    {
      $lb = 3 + 1; $le = $cur - 3;
      if($le-$lb < 3) { $lb = 0; $le = 0; }

      $rb = $cur + 3; $re = $count - 3;
      if($re-$rb < 3) { $rb = 0; $re = 0; }
    }

    for($i=1, $n=($count+1); $i < $n; $i++)
    {
      if($i > $lb && $i < $le)
      {
        if(!$left) { $content.= '...'; $left = true; } // left group
        continue;
      }
      if($i > $rb && $i < $re)
      {
        if(!$right) { $content.= '...'; $right = true; } // right group
        continue;
      }
      if($i!=1) { $content.= ' &bull; '; } // delimeter
      if($i==$cur) // current
      {
        $content.= '<b style="background:#dddddd;color:#000000;padding:0px 5px 0px 5px;">'.$i.'</b> ';
      }
      else // default
      {
        $content.= '<a href="'. $url. $i. '"><b>'.$i.'</b></a> ';
      }
    }
    //
    if($cur!=$count && $count > 2) { $content.= ' <a href="'. $url. ($cur+1). '">вперед</a> '; }
    if($cur < ($count-1) && $count > 3) { $content.= ' <a href="'. $url. $count. '">конец</a> '; }
  }
  $ret['offset'] = floor(($cur-1) * $size);
  $ret['count'] = $count;
  $ret['content'] = $content;
  $ret['cur'] = $cur;
  return $ret;
}

/*
$template['next'] =
$template['prev'] =
$template['first'] =
$template['last'] =
$template['normal'] =
$template['active'] =
$template['div'] =
$template['divleft'] =
$template['divright'] =
$template['left'] =
$template['right'] =
*/

function urlreplace($url, $arg, $value=NULL)
{
  if(is_string($arg)) { $args = array($arg=>$value); }
  else { $args = $arg; }
  foreach($args as $arg=>$value)
  {
    if(preg_match('/([?&]'. preg_quote($arg, '/'). '=)[^&]*/s', $url, $m))
    {
        $url = str_replace($m[0], (is_null($value) ? '' : $m[1]. urlencode($value)), $url);
        continue;
    }
    if(is_null($value)) { continue; }
    $url = $url. (strpos($url, '?')!==false? '&' : '?'). $arg. '='. urlencode($value);
  }
  return $url;
}

function unstrftime($format, $str)
{
  if(empty($format) || empty($str)) { return NULL; }
  $format = str_replace('%D', '%m/%d/%y', $format);
  $format = str_replace('%h', '%b', $format);
  $format = str_replace('%r', '%H:%M', $format);
  $format = str_replace('%R', '%I:%M:%S %p', $format);
  $format = str_replace('%T', '%H:%M:%S', $format);

  $tt = array();
  $tt['%a'] = '(\w+)';
  $tt['%A'] = '(\w+)';
  $tt['%b'] = '(\w+)';
  $tt['%B'] = '(\w+)';
  $tt['%c'] = '(\w+)';
  $tt['%C'] = '(\w+)';
  $tt['%d'] = '(\d+)';
  $tt['%e'] = '(\d+)';
  $tt['%g'] = '(\d+)';
  $tt['%G'] = '(\d+)';
  $tt['%H'] = '(\d+)';
  $tt['%i'] = '(\d+)';
  $tt['%I'] = '(\d+)';
  $tt['%j'] = '(\d+)';
  $tt['%m'] = '(\d+)';
  $tt['%M'] = '(\d+)';
  $tt['%n'] = "\n";
  $tt['%p'] = '(AM|PM)';
  $tt['%P'] = '(am|pm)';
  $tt['%y'] = '(\d+)';
  $tt['%Y'] = '(\d+)';
  $pattern = '~'. str_replace(array_keys($tt), array_values($tt), preg_quote($format,'~')). '~is';

  if(!preg_match_all('~(\%[\%\w])~is', $format, $keys)) { return NULL; }
  if(!is_array($keys)) { return NULL; }
  $keys = $keys[0];
  if(empty($keys)) { return NULL; }

  if(!preg_match_all($pattern, $str, $values, PREG_SET_ORDER)) { return NULL; }
  if(!is_array($values)) { return NULL; }
  $values = $values[0];
  if(is_array($values)) { array_shift($values); }
  if(empty($values)) { return NULL; }

  $result = array();
  foreach($values as $k=>$v) { $result[ $keys[$k] ] = $v; }

  if(!isset($result['%Y']) && isset($result['%y'])) { $result['%Y'] = (date('Y')-(date('Y')%1000)) + intval($result['%y']); }
  else { $result['%Y'] = date('Y'); }
  $year = intval($result['%Y']);

  if(!isset($result['%d']) && isset($result['%j'])) { $result['%d'] = intval($result['%j']); }
  else { $result['%d'] = date('d'); }
  $result['%d'] = $result['%d'] % 32;
  $day = intval($result['%d']);

  if(!isset($result['%m']) && isset($result['%n'])) { $result['%m'] = intval($result['%n']); }
  else { $result['%m'] = date('m'); }
  $month = intval($result['%m'] % 13);

  if(!isset($result['%S'])) { $result['%S'] = date('s'); }
  $second = intval($result['%S'] % 61);

  if(!isset($result['%M'])) { $result['%M'] = date('i'); }
  $minute = intval($result['%M'] % 61);

  if(!isset($result['%H']) && isset($result['%I']) ) { $result['%H'] = intval($result['%I']) + 12; }
  else { $result['%H'] = date('H'); }
  $hour = intval($result['%H'] % 25);

  return @mktime($hour, $minute, $second, $month, $day, $year);
}


/**
 * Возвращает сумму прописью
 * @ author diman
 * 
 */
function num2str($num) {
    $nul='ноль';
    $ten=array(
        array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),
        array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'),
    );
    $a20=array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать');
    $tens=array(2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто');
    $hundred=array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот');
    $unit=array( // Units
        array('копейка' ,'копейки' ,'копеек',	 1),
        array('рубль'   ,'рубля'   ,'рублей'    ,0),
        array('тысяча'  ,'тысячи'  ,'тысяч'     ,1),
        array('миллион' ,'миллиона','миллионов' ,0),
        array('миллиард','милиарда','миллиардов',0),
    );
    //
    list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
    $out = array();
    if (intval($rub)>0) {
        foreach(str_split($rub,3) as $uk=>$v) { // by 3 symbols
            if (!intval($v)) continue;
            $uk = sizeof($unit)-$uk-1; // unit key
            $gender = $unit[$uk][3];
            list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
            // mega-logic
            $out[] = $hundred[$i1]; # 1xx-9xx
            if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3]; # 20-99
            else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
            // units without rub & kop
            if ($uk>1) $out[]= morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
        }
    }
    else $out[] = $nul;
    $out[] = morph(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]); // rub
    $out[] = $kop.' '.morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]); // kop
    return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
}

/**
 * Склоняем словоформу
 * 
 */
function morph($n, $f1, $f2, $f5) {
    $n = abs(intval($n)) % 100;
    if ($n>10 && $n<20) return $f5;
    $n = $n % 10;
    if ($n>1 && $n<5) return $f2;
    if ($n==1) return $f1;
    return $f5;
}


?>