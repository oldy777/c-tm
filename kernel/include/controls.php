<?php

// compress whitespace
function pack_ws($text)
{
  return trim(preg_replace('~\s+~s', ' ', $text));
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
    if($title) { $text = '<span title="'. trim($srctext).'">'. trim($text).'<span>'; }
  }
  return $text;
}

function datebox_create($name, $value=NULL)
{
  $ret = '';
  if($value) { $value = intval($value); }
  else { $value = time(); }
  $d = date('j', $value);
  $m = date('n', $value);
  $y = date('Y', $value);
  $ret.= "<select name=\"{$name}[m]\" id=\"{$name}[m]\">";
  foreach(months() as $k=>$v)
  {
    $sel = ($k==$m? ' selected="selected"' : '');
    $ret.= "<option value=\"{$k}\"{$sel}>{$v}</option>";
  }
  $ret.= "</select>&nbsp;";
  $ret.= "<input style=\"width:50px\" type=\"text\" maxlength=\"2\" size=\"2\" value=\"{$d}\" name=\"{$name}[d]\" id=\"{$name}[d]\" onkeydow n=\"return inputinckey(event,'{$name}[d]',1,1,31)\" onkeypress=\"return inputfilter(event, /^[0-9]+$/)\" />,&nbsp;&nbsp;&nbsp;&nbsp;";
  $ret.= "<input style=\"width:50px\" type=\"text\" maxlength=\"4\" size=\"4\" value=\"{$y}\" name=\"{$name}[y]\" id=\"{$name}[y]\" onkeydo wn=\"return inputinckey(event,'{$name}[y]',1)\" onkeypress=\"return inputfilter(event, /^[0-9]+$/)\" />";
  return $ret;
}

function datebox_parse($value)
{
  $d = intval($value['d']) % 32;
  $m = intval($value['m']) % 13;
  $y = intval($value['y']);
  if(checkdate($m, $d, $y)) { return mktime(date('H'), date('i'), date('s'), $m, $d, $y); }
  else { return NULL; }
}

?>