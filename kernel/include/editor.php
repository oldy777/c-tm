<?php

function editor_encode($value)
{
  return $value;
}

function editor_checkbrowser()
{
  if(eregi('MSIE[^;]*', $_SERVER['HTTP_USER_AGENT'], $msie) &&
     eregi('[0-9]+\.[0-9]+', $msie[0], $version) &&
     floatval($version[0])>=5.5 &&
     !eregi('opera', $_SERVER['HTTP_USER_AGENT']))
  { return 'ie'; }
  if(eregi('Gecko/[0-9]*', $_SERVER['HTTP_USER_AGENT'], $gecko) &&
     eregi('[0-9]+', $gecko[0], $build) &&
     intval($build[0])>=20030312)
  { return 'gecko'; }
  return '';
}

function editor_create($name, $value='', $width='100%', $height='300px', $toolbar=NULL, $toolbarex=NULL, $state=false)
{
  global $kernel;

  $name = trim($name);
  $value = trim(editor_encode($value));
  $state = (int)$state;
  $width = trim($width);
  $height = trim($height);

  if($toolbar===NULL) { $toolbar = $kernel['config']['editor']['toolbar']; }
  if(!is_array($toolbar)) { $toolbar = array(); }
  $toolbar = trim(implode(',',$toolbar));

  if($toolbarex===NULL) { $toolbarex = $kernel['config']['editor']['toolbarex']; }
  if(!is_array($toolbarex)) { $toolbarex = array(); }
  $toolbarex = trim(implode(',',$toolbarex));

$content = "";

$content.= <<<HERE
<!--editor-->
<table cellspacing="1" cellpadding="0" border="0" width="{$width}" height="{$height}" style="background:#C7DFE3;border:1px solid #C7DFE3;font-size:12px;">
  <tr height="1%">
    <td style="background:#C7DFE3;border:none;">
HERE;

  $ua = editor_checkbrowser();
  if($ua=='ie' || $ua=='gecko')
  {
$content.= <<<HERE
      <input type="button" onclick="return editor_wnd('{$name}')" value="Визуальное редактирование HTML" style="background:#EBF4F5;border:none;width:240px;font-size:10pt;font-family:Arial;" />
HERE;

$content.= <<<HERE
      <nobr style="margin:1px 5px 1px 5px;"><input type="checkbox" style="width:1.5em;border:none;" id="wrap" onclick="return editor_wrap('{$name}')" /><label for="wrap">Перенос строк</label></nobr>
<script language="javascript" type="text/javascript">
<!--
function editor_wnd(name)
{
  var wnd, editor;
  var width = 720;
  var height = 550;
  opt = 'top=' + ((screen.availHeight-height)/2) + ',left=' + ((screen.availWidth-width)/2) + ',width=' + width + ',height=' + height + ',location=no,center=yes,status=no,center=yes,resizable=yes,scrollbars=no';
  wnd = window.open('/editor/wnd.php?engine={$ua}', name, opt);
  return false;
}
function editor_wrap(name)
{
  var obj = document.getElementById(name);
  if(obj) { obj.wrap = (String(obj.wrap).toLowerCase()=='off'? 'soft' : 'off'); }
  return (obj? true : false);
}
//-->
</script>
<!--/editor-->
HERE;
  }
  else // not support design mode
  {
$content.= <<<HERE
   необходим броузер: IE 5.5+, Netscape 7.1+, Mozilla 1.3+, Firefox 0.8+.
HERE;
  }
// close table
$content.= <<<HERE
    </td>
  </tr>
  <tr height="99%">
    <td style="background:#C7DFE3;border:none;">
      <textarea name="{$name}" wrap="off" id="{$name}" style="width:100%;height:100%;" toolbar="{$toolbar}" toolbarex="{$toolbarex}" state="{$state}">{$value}</textarea>
    </td>
  </tr>
</table>
HERE;
  return $content;
}

?>