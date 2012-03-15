<script language="javascript" type="text/javascript"><!--
var httreepath = new RegExp();
httreepath.compile("^[a-zA-Z0-9_\-]+$");
function oncancel()
{
  document.location='?mod=htdocs&act=docs&id=<?=$args['parent']['id']?>';
  return false;
}
function htdoctype(e)
{
  var panelhtml = document.getElementById('panelhtml');
  var panelphp =  document.getElementById('panelphp');
  if(e) // php
  {
    panelphp.style.display = '';
    panelhtml.style.display = 'none';
  }
  else // html
  {
    panelhtml.style.display = '';
    panelphp.style.display = 'none';
  }
}
function checkform(form)
{
  if(form.upload.value!='' && form.image.value!='')
  {
    return true;
  }
  var msg='';
  if(String(form.path.value).trim()=='') { msg+='* поле "Имя документа" пусто;\n'; }
  else if(! String(form.path.value).match(httreepath) ) { msg+='* поле "Имя документа"  может содержать только символы латинского алфавита, цифры, \"_\", \"-\";\n'; }
<?if(!$args['isindex']){?>
  if(String(form.title.value).trim()=='') { msg+='* поле "Название документа" пусто;\n'; }
<?}?>
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
}
//--></script>
<?if(!empty($errors)){?>
<div class="error">Ошибки:<br />
<?if($errors['id']){?>&bull; Документ не существует;<br /><?}?>
<?if($errors['parent']){?>&bull; Родительская папка не существует;<br /><?}?>
<?if($errors['path']['empty']){?>&bull; поле &laquo;<a href="#path">Имя документа</a>&raquo; пусто;<br /><?}?>
<?if($errors['path']['valid']){?>&bull; поле &laquo;<a href="#path">Имя документа</a>&raquo; может содержать только символы латинского алфавита, цифры, "_", "-";<br /><?}?>
<?if($errors['path']['unique']){?>&bull; поле &laquo;<a href="#path">Имя документа</a>&raquo; не уникально, документ с таким именем уже существует;<br /><?}?>
<?if($errors['title']){?>&bull; поле &laquo;<a href="#title">Название документа</a>&raquo; пусто;<br /><?}?>
</div>
<?}?>
<form method="post" action="?mod=htdocs&amp;act=editdoc&amp;id=<?=$args['id']?>" onsubmit="return checkform(this) && submitonce(this)" enctype="multipart/form-data">
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
    <th align="right" class="<?=($errors['path']? 'error' : '')?>" nowrap="nowrap">* Имя документа:</th>
    <td nowrap="nowrap">
      <table width="100%" cellspacing="0" cellpadding="0" class="control"><tr>
      <td width="1%" class="none" style="padding-left:1px" nowrap="nowrap"><label for="path"><?=trim($args['parent']['fullpath'])?></label></td>
      <td width="98%" class="none"><input type="text" class="none" name="path" id="path" style="width:100%" value="<?=trim($args['path'])?>"<?=($args['isindex']? ' readonly="readonly" disabled="disabled"' : '')?> /></td>
      <td width="1%" class="none" style="padding-right:1px"><label for="path">.html</label></td>
      </tr></table>
    </td>
  </tr>
  <tr>
    <th align="right" class="<?=($errors['title']? 'error' : '')?>" nowrap="nowrap"><?=($args['isindex']? '' : '*')?> Название документа:</th>
    <td><input type="text" name="title" style="width:100%" value="<?=trim($args['title'])?>" /></td>
  </tr>
  <tr id="panelhtml"<?=($args['eval']? ' style="display:none"' : '')?>>
    <td colspan="2" height="400px">
    <?
      $CKEditor = new CKEditor();
      $CKEditor->config['height'] = 300;
      $CKEditor->editor("html", $args['html']);
    ?>
  </td>
  </tr>
  <tr id="panelphp"<?=($args['eval']? '' : ' style="display:none"')?>>
    <td colspan="2" height="400px">
      <textarea name="php" wrap="off" style="width:100%;height:100%"><?=trim($args['php'])?></textarea>
    </td>
  </tr>
</table>
</fieldset>
<fieldset>
<legend>
  <a href="javascript:onpanel('panelext')">Дополнительно</a>
</legend>
<table id="panelext" width="100%" border="0" cellspacing="2" cellpadding="2" class="table" style="border:none">
  <colgroup>
    <col width="27%" />
    <col width="75%" />
  </colgroup>
  <tr>
    <th align="right" nowrap="nowrap">Тип документа:</th>
    <td>
      <input type="radio" name="eval" value="0" onclick="htdoctype(!this.checked)" id="eval0" class="checkbox"<?=($args['eval']? '' : ' checked')?> /><label for="eval0">html</label>
      <input type="radio" name="eval" value="1" onclick="htdoctype(this.checked)" id="eval1" class="checkbox"<?=($args['eval']? ' checked' : '')?> /><label for="eval1">php</label>
    </td>
  </tr>
  <tr>
    <th align="right" nowrap="nowrap">Макет дизайна:</th>
    <td>
      <select name="id_maket">
        <option value="0"<?=($args['id_maket']===0? ' selected="selected"' : '')?>>нет макета</option>
        <option value="<?=$args['parent']['id_maket']?>" selected="selected">наследовать (id:<?=$args['parent']['id_maket']?>)</option>
<?foreach($args['makets'] as $i){?>
        <option value="<?=$i['id']?>"<?=($i['current']? ' selected="selected"' : '')?>><?=trim($i['title'])?> (id:<?=$i['id']?>)</option>
<?}?>
      </select>
    </td>
  </tr>
<?if(!$args['isindex']){?>
  <tr>
    <th align="right" nowrap="nowrap">Видимость документа:</th>
    <td>
      <input type="radio" class="radio" name="hidden" id="hidden0" value="0"<?=($args['hidden']==0? ' checked="checked"' : '')?> /><label for="hidden0">виден</label>
      <input type="radio" class="radio" name="hidden" id="hidden1" value="1"<?=($args['hidden']==1? ' checked="checked"' : '')?> /><label for="hidden1">скрыт</label>
    </td>
  </tr>
<?}?>
  <tr>
    <th align="right" nowrap="nowrap">
      SEO текст 1 колонка:<br />
    </th>
    <td>
     <textarea name="seo1" style="width:100%;height:100px"><?=trim($args['seo1'])?></textarea><br />
    </td>
  </tr>
  <tr>
    <th align="right" nowrap="nowrap">
      SEO текст 2 колонка:<br />
    </th>
    <td>
      <textarea name="seo2" style="width:100%;height:100px"><?=trim($args['seo2'])?></textarea><br />
    </td>
  </tr>
 
  <tr>
    <th align="right" nowrap="nowrap">
      Краткий аннос:<br />
      <small>html</small>
    </th>
    <td>
      <textarea name="notice" style="width:100%;height:55px"><?=trim($args['notice'])?></textarea>
    </td>
  </tr>
  <tr>
    <th align="right" nowrap="nowrap">
      Ключевые слова:<br />
      <small>meta-keywords, через запятую</small>
    </th>
    <td>
      <textarea name="keywords" style="width:100%;height:40px"><?=trim($args['keywords'])?></textarea><br />
      <input type="checkbox" class="checkbox" name="keywords" id="keywordsNULL" value="NULL"<?=($args['keywords']===NULL? ' checked="checked"' : '')?> /><label for="keywordsNULL">наследовать от папки</label>
    </td>
  </tr>
  <tr>
    <th align="right" nowrap="nowrap">
      Описание:<br />
      <small>meta-description</small>
    </th>
    <td>
      <textarea name="description" style="width:100%;height:40px"><?=trim($args['description'])?></textarea><br />
      <input type="checkbox" class="checkbox" name="description" id="descriptionNULL" value="NULL"<?=($args['description']===NULL? ' checked="checked"' : '')?> /><label for="descriptionNULL">наследовать от папки</label>
    </td>
  </tr>
  <tr>
    <th align="right" nowrap="nowrap">Дата публикации:</th>
    <td nowrap="nowrap">
      <?=datebox_create('published', $args['published'])?>
      <input type="checkbox" class="checkbox" name="published" id="published" value="0"<?=($args['published']? '' : ' checked="checked"')?> /><label for="published">дата отсутвует</label>
    </td>
  </tr>
<?if(!$args['isindex']){?>
  <tr>
    <th align="right" nowrap="nowrap">Позиция:</th>
    <td>
      <input type="text" maxlength="6" size="6" name="pos" value="<?=trim($args['pos'])?>" />
    </td>
  </tr>
<?}?>
</table>
</fieldset>
<table width="100%" border="0" cellspacing="2" cellpadding="4">
  <tr>
    <td width="25%">&nbsp;</td>
    <td width="75%">
      <input type="submit" class="button" value="Сохранить!" />
      <input type="reset" class="button" value="Отменить" onclick="oncancel()" />
    </td>
  </tr>
</table>
</form>
<script language="javascript" type="text/javascript"><!--
var path = document.getElementById('path');
if(path) { path.onkeypress = function() { return inputfilter(window.event, httreepath); } }
onpanel('panelext');
//--></script>