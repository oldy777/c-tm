<script language="javascript" type="text/javascript"><!--
function checkform(form)
{
  var msg='';
  if(form.title.value=='') { msg+='*Имя пусто\n'; }
//  if(form.content.value=='') { msg+='*Текст пуст\n'; }
  if(msg!='') { alert('Ошибки:\n'+msg); return false; }
  else { return true; }
}
function oncancel()
{
  document.location='/admin/?mod=makets';
  return false;
}
function onwrap()
{
  obj = document.getElementById('content');
  obj.wrap = (obj.wrap=='off'? 'soft' : 'off');
}
//--></script>
<?if(!empty($errors)){?><div class="error">Ошибки:</div>
<?if($errors['title']){?><div class="error">&middot; Имя пуст</div><?}?>
<?if($errors['content']){?><div class="error">&middot; Текст пуст</div><?}?>
<?}?>

<table width="100%" border="0" cellspacing="2" cellpadding="4" class="table">
<form method="POST" action="<?=htmlspecialchars($args['action'])?>" onsubmit="return checkform(this)">
<tr>
<th width="1%" align="right" nowrap="nowrap">&nbsp;Имя:</th>
<td width="98%"><input type="text" name="title" style="width:100%" value="<?=htmlspecialchars($args['title'])?>" /></td>
</tr>
<tr>
<td colspan="2">
<textarea id="content" name="content" wrap="off" style="width:100%;height:350;"><?=$args['content']?></textarea>
<input type="checkbox" class="checkbox" id="wrap" onclick="onwrap()" /><label for="wrap">перенос строк</label>
</td>
</tr>
<tr>
    <th width="1%" align="right">Использовать <br />php файл:</th>
    <td width="98%">
      <select name="file" style="width:100%;"/>
      <?foreach($args['option']['files']['values'] as $v=>$o){?>
        <option value="<?=$v?>"<?=($args['file']==$o)?' selected':''?>><?=$o?></option>
      <?}?>
      </select>
    </td>
  </tr>
<tr>
<td class="none">&nbsp;</td>
<td class="none">
<input type="submit" value="Cохранить!" class="button" />
<input type="reset" value="Отменить" onclick="oncancel()" class="button" />
</td>
</tr>
</form>
</table>