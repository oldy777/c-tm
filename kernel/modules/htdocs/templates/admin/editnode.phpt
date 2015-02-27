<script language="javascript" type="text/javascript"><!--
var httreepath = new RegExp();
httreepath.compile("^[a-zA-Z0-9_\-]+$");
function oncancel()
{
  document.location='?mod=htdocs';
  return false;
}
function checkform(form)
{
  if(form.upload.value!='' && form.image.value!='')
  {
    return true;
  }
  var msg='';
<?php if(!$args['isroot']) { ?>
  if(String(form.path.value).trim()=='') { msg+='* поле "Имя папки" пусто;\n'; }
  else if(! String(form.path.value).match(httreepath) ) { msg+='* поле "Имя папки"  может содержать только символы латинского алфавита, цифры, \"_\", \"-\";\n'; }
  if(String(form.title.value).trim()=='') { msg+='* поле "Название раздела" пусто;\n'; }
<?php } ?>
  if(msg!='') { alert('Ошибки:\n'+msg); return false; }
  else
  {
    var keywords = document.getElementById('keywords');
    var keywordsNULL = document.getElementById('keywordsNULL');
    if(keywords && keywordsNULL && String(keywords.value).trim()!='')
    {
      keywordsNULL.checked = false;
      keywordsNULL.disabled = true;
    }
    var description = document.getElementById('description');
    var descriptionNULL = document.getElementById('descriptionNULL');
    if(description && descriptionNULL && String(description.value).trim()!='')
    {
      descriptionNULL.checked = false;
      descriptionNULL.disabled = true;
    }
    return true;
  }
  return false;
}
//--></script>
<?php if(!empty($errors)) { ?>
<div class="error">Ошибки:<br />
<?php if($errors['id']) { ?>&bull; Папка не существует;<br /><?php } ?>
<?php if($errors['path']['empty']) { ?>&bull; поле &laquo;<a href="#path">Имя папки</a>&raquo; пусто;<br /><?php } ?>
<?php if($errors['path']['valid']) { ?>&bull; поле &laquo;<a href="#path">Имя папки</a>&raquo; может содержать только символы латинского алфавита, цифры, "_", "-";<br /><?php } ?>
<?php if($errors['path']['unique']) { ?>&bull; поле &laquo;<a href="#path">Имя папки</a>&raquo; не уникально, раздел с таким именем уже существует;<br /><?php } ?>
<?php if($errors['title']) { ?>&bull; поле &laquo;<a href="#title">Название раздела</a>&raquo; пусто;<br /><?php } ?>
</div>
<?php } ?>

<form method="post" action="?mod=htdocs&amp;act=editnode&amp;id=<?php echo $args['id'] ?>" onsubmit="return checkform(this) && submitonce(this)" enctype="multipart/form-data">
<fieldset>
<legend>
  <a href="javascript:onpanel('panelmain')">Основное</a>
</legend>
<table id="panelmain" width="100%" border="0" cellspacing="2" cellpadding="2" class="table" style="border:none">
  <colgroup>
    <col width="25%" />
    <col width="75%" />
  </colgroup>
  <tr>
    <th align="right" class="<?php echo ($errors['path']? 'error' : '') ?>" nowrap="nowrap">* Имя папки:</th>
    <td nowrap="nowrap">
      <table width="100%" cellspacing="0" cellpadding="0" class="control"><tr>
<?php if(!$args['isroot']) { ?>
      <td width="1%" class="none" style="padding-left:1px" nowrap="nowrap"><label for="path"><?php echo htmlspecialchars($args['parent']['fullpath']) ?></label></td>
<?php } ?>
      <td width="99%" class="none"><input type="text" class="none" name="path" id="path" style="width:100%" value="<?php echo htmlspecialchars($args['path']) ?>"<?php echo ($args['isroot']? ' readonly="readonly" disabled="disabled"' : '') ?> /></td>
      </tr></table>
    </td>
  </tr>
  <tr>
    <th align="right" class="<?php echo ($errors['title']? 'error' : '') ?>" nowrap="nowrap">* Название раздела:</th>
    <td><input type="text" name="title" style="width:100%" value="<?php echo htmlspecialchars($args['title']) ?>" /></td>
  </tr>
</table>
</fieldset>
<fieldset>
<legend>
  <a href="javascript:onpanel('panelext')">Дополнительно</a>
</legend>
<table id="panelext" width="100%" border="0" cellspacing="2" cellpadding="2" class="table" style="border:none">
  <colgroup>
    <col width="25%" />
    <col width="75%" />
  </colgroup>
  <tr>
    <th align="right" nowrap="nowrap">Макет дизайна:</th>
    <td>
      <select name="id_maket">
        <option value="0"<?php echo ($args['id_maket']===0? ' selected="selected"' : '') ?>>нет макета</option>
<?php foreach($args['makets'] as $i) { ?>
        <option value="<?php echo $i['id'] ?>"<?php echo ($i['current']? ' selected="selected"' : '') ?>><?php echo htmlspecialchars($i['title']) ?> (id:<?php echo $i['id'] ?>)</option>
<?php } ?>
      </select>
    </td>
  </tr>
<?php if(!$args['isroot']) { ?>
  <tr>
    <th align="right" nowrap="nowrap">Видимость в навигации:</th>
    <td nowrap="nowrap">
      <input type="radio" class="radio" name="hidden" id="hidden0" value="0"<?php echo ($args['hidden']==0? ' checked="checked"' : '') ?> /><label for="hidden0">виден</label>
      <input type="radio" class="radio" name="hidden" id="hidden1" value="1"<?php echo ($args['hidden']==1? ' checked="checked"' : '') ?> /><label for="hidden1">скрыт</label>
    </td>
  </tr>
<?php } ?>
  <tr>
    <th align="right" nowrap="nowrap">
      Ключевые слова:<br />
      <small>meta-keywords, через запятую</small>
    </th>
    <td>
      <textarea name="keywords" id="keywords" style="width:100%;height:40px"><?php echo htmlspecialchars($args['keywords']) ?></textarea><br />
<?php if(!$args['isroot']) { ?>
      <input type="checkbox" class="checkbox" name="keywords" id="keywordsNULL" value="NULL"<?php echo ($args['keywords']===NULL? ' checked="checked"' : '') ?> /><label for="keywordsNULL">наследовать от родителя</label>
<?php } ?>
    </td>
  </tr>
  <tr>
    <th align="right" nowrap="nowrap">
      Описание:<br />
      <small>meta-description</small>
    </th>
    <td>
      <textarea name="description" id="description" style="width:100%;height:40px"><?php echo htmlspecialchars($args['description']) ?></textarea><br />
<?php if(!$args['isroot']) { ?>
      <input type="checkbox" class="checkbox" name="description" id="descriptionNULL" value="NULL"<?php echo ($args["description"]===NULL? ' checked="checked"' : '') ?> /><label for="descriptionNULL">наследовать от родителя</label>
<?php } ?>
    </td>
  </tr>
</table>
</fieldset>
<table width="100%" border="0" cellspacing="2" cellpadding="4">
  <tr>
   <td width="25%">&nbsp;</td>
   <td width="75%">
      <input type="submit" class="button" value="Сохранить!" />
      <input type="submit" class="button btn-primary" name="next" value="Далее" />
      <input type="reset" class="button btn-warning" value="Отменить" onclick="oncancel()" />
   </td>
  </tr>
</table>
</form>
<script language="javascript" type="text/javascript"><!--
var path = document.getElementById('path');
if(path) { path.onkeypress = function() { return inputfilter(window.event, httreepath); } }
onpanel('panelext');
//--></script>