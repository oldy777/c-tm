<script language="javascript" type="text/javascript">
function del(id)
{
  obj = document.getElementById(id);
  if(confirm('Удaлить "'+obj.innerHTML+'" из списка?'))
  {
    document.location = '/admin/?mod=<?=$_GET['mod']?>&act=delitem&id='+id;
  }
}
</script>
<?
$width=98;
if($args['mod_pos']) $width=$width-3;
$width=ceil($width/$args['mod_view']);
?>
<table width="100%" border="0" cellspacing="2" cellpadding="4" class="table">
  <thead>
    <tr>
      <th width="1%">#</th>
  <?if($args['mod_pos']){?>
      <th width="3%">&nbsp;</th>
  <?}?>
<?foreach($args['mod_fields'] as $f){?>
  <?if($f['view']==1){?>
      <th width="<?=$width?>%"><?=$f['title']?></th>
  <?}?>
<?}?>
      <th title="команды" width="1%">&nbsp;</th>
    </tr>
  </thead>
<?foreach($args['items'] as $i){?>
  <tr align="center">
    <td style="font-size:11px;color:#999;"><?=$i['id']?></td>
  <?if($args['mod_pos']){?>
    <td align="left"><?if($i['pos']>0){?><a title="позиция выше" href="?mod=<?=$_GET['mod']?>&act=upitem&id=<?=$i['id']?>"><img class="down" src="images/arr_up.gif"/></a><?}?><br/><?if($i['pos']<=sizeof($args['items'])-2){?><a title="позициИ ниже" href="?mod=<?=$_GET['mod']?>&act=downitem&id=<?=$i['id']?>"><img class="down" src="images/arr_down.gif"/></a><?}?></td>
  <?}?>
<?foreach($args['mod_fields'] as $f){?>
  <?if($f['view']==1){?>
    <td align="left" id="<?=$i['id']?>"><?=substr(htmlspecialchars($i[$f['name']]),0,150)?></td>
  <?}?>
<?}?>
    <td nowrap="nowrap">
      <a href="?mod=<?=$_GET['mod']?>&act=edititem&id=<?=$i['id']?>" title="редактировать"><img class="btn_edit" src="/admin/images/icon_edit.gif" width="17" height="17" border="0" /></a>
      <a href="javascript:del(<?=$i['id']?>)" title="удалить"><img class="btn_del" src="/admin/images/icon_del.gif" width="17" height="17" border="0" /></a>
    </td>
  </tr>
<?}?>
</table>