<script language="javascript" type="text/javascript"><!--
function oncancel()
{
  document.location='?mod=htdocs&act=docs&id=<?=$args['id_node']?>';
  return false;
}
//--></script>
<?if(!empty($errors)){?>
<div class="error">Ошибки:<br />
<?if($errors['id']){?>&bull; Папка не существует;<br /><?}?>
<?if($errors['id_node']){?>&bull; Перенос не возможен;<br /><?}?>
<?if($errors['id_node']['equal']){?>&bull; &laquo;Папка родитель&raquo;  уже является текущей, перенос не требуется;<br /><?}?>
<?if($errors['id_node']['unique']){?>&bull; &laquo;Папка родитель&raquo; уже содержит такой документ;<br /><?}?>
<?if($errors['id_node']['isindex']){?>&bull; Индексный документ;<br /><?}?>
<?print_r($errors);?>
</div>
<?}?>
<table width="100%" border="0" cellspacing="2" cellpadding="2" class="table">
<form method="post" action="?mod=htdocs&amp;act=movedoc&amp;id=<?=$args['id']?>" onsubmit="return submitonce(this)">
  <tr>
    <th width="20%" align="right" nowrap="nowrap">Имя папки:</th>
    <td width="80%"><input type="text" style="width:100%" value="<?=htmlspecialchars($args['fullpath'])?>.html" readonly="readonly" disabled="disabled" /></td>
  </tr>
  <tr>
    <th align="right" nowrap="nowrap">Название раздела:</th>
    <td><input type="text" style="width:100%" value="<?=htmlspecialchars($args['title'])?>" readonly="readonly" disabled="disabled" /></td>
  </tr>
  <tr>
    <th align="right" class="<?=($errors['id_node']? 'error' : '')?>" nowrap="nowrap">Папка родитель:</th>
    <td>
      <select name="id_node">
<?foreach($args['tree'] as $i){?>
      <option value="<?=$i['id']?>"<?=($args['id_node']==$i['id']? ' selected="selected"' : '')?>><?=str_repeat('&nbsp;',$i['level']*3).htmlspecialchars($i['title']).($args['id_node']==$i['id']? ' (текущий)' : '')?></option>
<?}?>
      </select>
    </td>
  </tr>
  <tr>
    <td class="none">&nbsp;</td>
    <td class="none" nowrap="nowrap">
      <input type="submit" class="button" value="Переместить!" />
      <input type="reset" class="button" value="Отменить" onclick="oncancel()" />
    </td>
  </tr>
</form>
</table>