<script language="javascript" type="text/javascript"><!--
function oncancel()
{
  document.location='?mod=htdocs';
  return false;
}
//--></script>
<?if(!empty($errors)){?>
<div class="error">Ошибки:<br />
<?if($errors['id']){?>&bull; Папка не существует;<br /><?}?>
<?if($errors['id_parent']){?>&bull; Перенос не возможен;<br /><?}?>
<?if($errors['id_parent']['equal']){?>&bull; &laquo;Родитель папки&raquo; уже является текущим, перенос не требуется;<br /><?}?>
</div>
<?}?>
<table width="100%" border="0" cellspacing="2" cellpadding="2" class="table">
<form method="post" action="?mod=htdocs&amp;act=movenode&amp;id=<?=$args['id']?>" onsubmit="return submitonce(this)">
  <tr>
    <th width="20%" align="right" nowrap="nowrap">Имя папки:</th>
    <td width="80%"><input type="text" style="width:100%" value="<?=htmlspecialchars($args['fullpath'])?>" readonly="readonly" disabled="disabled" /></td>
  </tr>
  <tr>
    <th align="right" nowrap="nowrap">Название раздела:</th>
    <td><input type="text" style="width:100%" value="<?=htmlspecialchars($args['title'])?>" readonly="readonly" disabled="disabled" /></td>
  </tr>
  <tr>
    <th align="right" nowrap="nowrap" class="<?=($errors['id_parent']? 'error' : '')?>">Родитель папки:</th>
    <td>
      <select name="id_parent" style="style:width:100px;">
<?foreach($args['neighbours'] as $i){?>
      <option value="<?=$i['id']?>"<?=($args['id_parent']==$i['id']? ' selected="selected"' : '')?>><?=str_repeat('&nbsp;',$i['level']*3).htmlspecialchars(text_cut($i['title'],60,true)).($args['id_parent']==$i['id']? ' (текущий)' : '')?></option>
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