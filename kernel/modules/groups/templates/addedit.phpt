<script language="javascript" type="text/javascript"><!--
function groupscheck(form)
{
  var msg='';
  if(form.title.value=='') { msg+='*Не заполнено поле "Имя"\n'; }
  if(msg!='') { alert('Произошли ошибки:\n'+msg); return false; }
  else
  {
    inner = document.getElementById('inner[]').options;
    for(i=inner.length-1; i!=-1; i--)  { inner[i].selected = true; }
    return true;
  }
}
function sortoptions(options)
{
  var n = options.length;
  var buf = new Array(options.length)
  for(i=n-1; i!=-1; i--)
  {
    buf[i].text = options[i].text;
    buf[i].value = options[i].value;
    buf[i].selected = options[i].selected;
    buf[i].defaultSelected = options[i].defaultSelected;
    options[i] = null;
  }
  buf.sort(function (a,b) { return (a.text - b.text); });
  for(i=n-1; i!=-1; i--)
  {
    options.add(new Option(buf[i].text, buf[i].value, buf[i].selected, buf[i].defaultSelected));
  }
}
function groupsadd()
{
  outer = document.getElementById('outer[]').options;
  inner = document.getElementById('inner[]').options;
  for(i=outer.length-1; i!=-1; i--)
  {
    if(outer[i].selected)
    {
      opt = new Option(outer[i].text, outer[i].value, false, true);
      opt.className = outer[i].className;
      inner.add(opt);
      outer[i].selected = false;
      outer[i] = null;
    }
  }
  return false;
}
function groupsremove()
{
  inner = document.getElementById('inner[]').options;
  outer = document.getElementById('outer[]').options;
  for(i=inner.length-1; i!=-1; i--)
  {
    if(inner[i].selected)
    {
      opt = new Option(inner[i].text, inner[i].value, false, true);
      opt.className = inner[i].className;
      outer.add(opt);
      inner[i].selected = false;
      inner[i] = null;
    }
  }
  return false;
}
function groupsdelete()
{
  if(confirm('Удалить группу?'))
  {
    document.location='/admin/?mod=groups&act=delete&id=<?=$args['id']?>';
  }
}
function oncancel()
{
  document.location='/admin/?mod=groups';
  return false;
}
//--></script>
<?if(!empty($errors)){?>
<div class="error">Произошли ошибки:<br />
<?if($errors['title']){?>&bull; Не заполнено поле &laquo;Имя&raquo;<br /><?}?>
</div>
<?}?>
<form method="post" action="<?=htmlspecialchars($args['action'])?>" onsubmit="return (groupscheck(this) && submitonce(this))">
<table width="100%" border="0" cellspacing="2" cellpadding="4" class="table">
<tr>
  <th width="30%" align="right" class="<?=($errors['title']? 'error' : '')?>">Имя:</th>
  <td width="70%"><input type="text" name="title" value="<?=htmlspecialchars($args['title'])?>" style="width:100%" maxlength="128" /></td>
</tr>
<tr>
  <th align="right">Описание:</th>
  <td><textarea name="content" style="width:100%; height:100px"><?=htmlspecialchars($args['content'])?></textarea></td>
</tr>
<tr>
  <th align="right">Статус:</th>
  <td><input type="checkbox" class="checkbox" name="blocked" id="blocked" value="1"<?=($args['blocked']? ' checked="checked"' : '')?> /><label for="blocked">заблокирован</label></td>
</tr>
</table>
<br />
<table width="450" border="0" cellspacing="1" cellpadding="4" align="center" class="table">
<thead>
<tr>
  <th>вне группы</th>
  <th>&nbsp;</th>
  <th>в группе</th>
</tr>
</thead>
<tr>
  <th width="48%">
    <select id="outer[]" style="width:100%;height:250px" ondblclick="groupsadd()" multiple="multiple">
<?foreach($args['users'] as $i) if(! in_array($i['id'], $args['inner'])) {?>
      <option value="<?=$i['id']?>" class="<?=($i['blocked']? 'hidden' : '')?>"><?=htmlspecialchars($i['login'])?></option>
<?}?>
    </select>
  </th>
  <th width="2%">
    <input type="button" class="button" style="width:30px" value="&gt;" onclick="return groupsadd()" />
     <br /><br />
     <input type="button" class="button" style="width:30px" value="&lt;" onclick="return groupsremove()" />
  </th>
  <th width="48%">
    <select name="inner[]" id="inner[]" style="width:100%;height:250px" ondblclick="groupsremove()" multiple="multiple">
<?foreach($args['users'] as $i) if(in_array($i['id'], $args['inner'])) {?>
      <option value="<?=$i['id']?>" class="<?=($i['blocked']? 'hidden' : '')?>"><?=htmlspecialchars($i['login'])?></option>
<?}?>
    </select>
  </th>
</tr>
</table>
<br />
<div align="center">
  <input type="submit" value="Сохранить!" class="button" />
<?if($args['id'] > 0){?>
  <input type="reset" value="Удалить" onclick="groupsdelete()" class="button" />
<?}?>
  <input type="reset" value="Отменить" onclick="oncancel()" class="button" />
</div>
</form>
