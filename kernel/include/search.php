<?php
function search_make_words($search)
{
  $search = trim($search);
  $search = preg_replace('~[^\w\s]~is', ' ', $search);
  $search = preg_replace('~\s+~s', ' ', $search);
  $words = explode(' ', $search);
  $ret = array();
  foreach($words as $i)
  {
    if(strlen($i) > 1)
    {
      $ret[] = $i;
    }
  }
  return $ret;
}

function search_sort_cmp($a, $b)
{
  if($a['relev']==$b['relev'])
  {
    return 0;
  }
  return ($a['relev'] < $b['relev']? 1 : -1);
}

function search_relev($words, $content)
{
  $ret = 0;
  foreach($words as $w)
  {
    $ret += intval(preg_match_all('~'.preg_quote($w).'~si', $content, $m));
  }
  return $ret;
}

function search_sort($result, $words)
{
  foreach($result as $k=>$v)
  {
    $t = search_relev($words, strtolower($v['title']));
    $c = search_relev($words, strtolower($v['content']));
    $v['relev'] = ($t * 2) + $c;
    $v['founded'] = $t + $c;
    $result[$k] = $v;
  }
  usort($result, 'search_sort_cmp');
  return $result;
}

function search_slice($result, $offset, $size, $lenght)
{
  $result = array_slice($result, $offset, $size);
  foreach($result as $k=>$v)
  {
    $v['content'] = text_cut($v['content'], $lenght);
    $result[$k] = $v;
  }
  return $result;
}

function search_sql_like($words, $field='<field>')
{
  global $kernel;
  $ret = '(';
  foreach($words as $i)
  {
    $ret.= $field. ' LIKE(\'%'. $kernel['db']->escape($i). '%\') OR ';
  }
  $ret.= '1=0)';
  return $ret;
}

?>
