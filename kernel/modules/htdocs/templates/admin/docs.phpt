<script language="javascript" type="text/javascript"><!--
function delnode(id)
{
  if(confirm('Удалить папку и все её документы?'))
  {
    document.location = '?mod=htdocs&act=delnode&id='+id;
  }
}
function deldoc(id)
{
  var obj = document.getElementById("doctitle"+id);
  if(obj && confirm('Удалить документ "'+obj.innerHTML+'"?'))
  {
    document.location = '?mod=htdocs&act=deldoc&id='+id;
  }
}
//--></script>
<?if(!empty($errors)){?>
<div class="error">Ошибки:<br />
<?if($errors['id']){?>&bull; Родительская папка не существует;<br /><?}?>
</div>
<?}?>
<table width="100%" border="0" cellspacing="2" cellpadding="2" class="table">
<tr>
  <th width="20%" align="right" nowrap="nowrap">Имя папки:</th>
  <td width="79%"><input type="text" style="width:100%" value="<?=htmlspecialchars($args['parent']['fullpath'])?>" readonly="readonly" disabled="disabled" /></td>
  <th width="1%" rowspan="2" nowrap="nowrap">
    <a href="?mod=htdocs&amp;act=addnode&amp;id=<?=$args['parent']['id']?>" title="подраздел"><img class="btn_add" src="images/icon_add.gif" width="17" height="17" border="0" /></a>
    <a href="?mod=htdocs&amp;act=editnode&amp;id=<?=$args['parent']['id']?>" title="редактировать"><img class="btn_edit" src="images/icon_edit.gif" width="17" height="17" border="0" /></a>
<?if($args['parent']['id']!=$args['root']['id']){?>
<?if(!$args['parent']['isparent']){?>
    <a href="javascript:delnode(<?=$args['parent']['id']?>)" title="удалить"><img class="btn_del" src="images/icon_del.gif" width="17" height="17" border="0" /></a>
<?}else{?>
    <img class="btn_disable" src="images/icon_del_disable.gif" width="17" height="17" border="0" />
<?}?>
    <a href="?mod=htdocs&amp;act=movenode&amp;id=<?=$args['parent']['id']?>" title="переместить"><img class="btn_move" src="images/icon_move.gif" width="17" height="17" border="0" /></a>
<?}else{?>
    <img class="btn_disable" src="images/icon_del_disable.gif" width="17" height="17" border="0" />
    <img class="btn_disable" src="images/icon_move_disable.gif" width="17" height="17" border="0" />
<?}?>
  </th>
</tr>
<tr>
  <th align="right" nowrap="nowrap">Название раздела:</th>
  <td><input type="text" style="width:100%" value="<?=htmlspecialchars($args['parent']['title'])?>" readonly="readonly" disabled="disabled" /></td>
</tr>
</table>
<br />
<table width="100%" border="0" cellspacing="2" cellpadding="4" class="table">
<thead>
<tr>
<th width="1%"><a href="?mod=htdocs&amp;act=docs&amp;id=<?=$args['id']?>&amp;order=id&amp;desc=<?=($args['order']=='id'? !$args['desc'] : '')?>"><?=($args['order']=='id'? ($args['desc']? '<img src="images/arr_up.gif" width="10" height="6" border="0" />' : '<img src="images/arr_down.gif" width="10" height="6" border="0" />') : '')?>&nbsp;#</a></th>
<th width="30%"><a href="?mod=htdocs&amp;act=docs&amp;id=<?=$args['id']?>&amp;order=path&amp;desc=<?=($args['order']=='path'? !$args['desc'] : '')?>"><?=($args['order']=='path'? ($args['desc']? '<img src="images/arr_up.gif" width="10" height="6" border="0" />' : '<img src="images/arr_down.gif" width="10" height="6" border="0" />') : '')?>&nbsp;Имя</a></th>
<th width="64%"><a href="?mod=htdocs&amp;act=docs&amp;id=<?=$args['id']?>&amp;order=title&amp;desc=<?=($args['order']=='title'? !$args['desc'] : '')?>"><?=($args['order']=='title'? ($args['desc']? '<img src="images/arr_up.gif" width="10" height="6" border="0" />' : '<img src="images/arr_down.gif" width="10" height="6" border="0" />') : '')?>&nbsp;Название</a></th>
<th width="1%"><a href="?mod=htdocs&amp;act=docs&amp;id=<?=$args['id']?>&amp;order=eval&amp;desc=<?=($args['order']=='eval'? !$args['desc'] : '')?>"><?=($args['order']=='eval'? ($args['desc']? '<img src="images/arr_up.gif" width="10" height="6" border="0" />' : '<img src="images/arr_down.gif" width="10" height="6" border="0" />') : '')?>&nbsp;Тип</a></th>
<th width="1%" title="время публикации"><a href="?mod=htdocs&amp;act=docs&amp;id=<?=$args['id']?>&amp;order=published&amp;desc=<?=($args['order']=='published'? !$args['desc'] : '')?>"><?=($args['order']=='published'? ($args['desc']? '<img src="images/arr_up.gif" width="10" height="6" border="0" />' : '<img src="images/arr_down.gif" width="10" height="6" border="0" />') : '')?>&nbsp;Дата</a></th>
<th width="1%" title="время создания"><a href="?mod=htdocs&amp;act=docs&amp;id=<?=$args['id']?>&amp;order=created&amp;desc=<?=($args['order']=='created'? !$args['desc'] : '')?>"><?=($args['order']=='created'? ($args['desc']? '<img src="images/arr_up.gif" width="10" height="6" border="0" />' : '<img src="images/arr_down.gif" width="10" height="6" border="0" />') : '')?>&nbsp;Создан</a></th>
<th width="1%" title="время последнего редактирования"><a href="?mod=htdocs&amp;act=docs&amp;id=<?=$args['id']?>&amp;order=updated&amp;desc=<?=($args['order']=='updated'? !$args['desc'] : '')?>"><?=($args['order']=='updated'? ($args['desc']? '<img src="images/arr_up.gif" width="10" height="6" border="0" />' : '<img src="images/arr_down.gif" width="10" height="6" border="0" />') : '')?>&nbsp;Редактирован</a></th>
<th width="1%" title="команды"><a href="?mod=htdocs&amp;act=adddoc&amp;id=<?=$args['parent']['id']?>" title="добавить"><img class="btn_add" src="images/icon_add.gif" width="17" height="17" border="0" /></a></th>
</tr>
</thead>
<?foreach($args['items'] as $i){?>
<tr align="center" class="<?=($i['hidden']? 'hidden' : '')?>" style="<?=($i['isindex']? 'font-weight:bold;' : '')?>">
  <td><?=$i['id']?></td>
  <td align="left"><?=htmlspecialchars($i['path'])?>.html</td>
  <td align="left" id="doctitle<?=$i['id']?>"><?=notags($i['title']? $i['title'] : $args['parent']['title'])?></td>
  <td><?=($i['eval']? 'php' : 'html')?></td>
  <td nowrap="nowrap"><?=($i['published']? strftime('%d.%m.%Y', $i['published']) : '&ndash;')?></td>
  <td nowrap="nowrap"><?=strftime('%d.%m.%y %H:%M', $i['created'])?></td>
  <td nowrap="nowrap"><?=($i['updated']? strftime('%d.%m.%y %H:%M', $i['updated']) : '&ndash;')?></td>
  <td nowrap="nowrap">
    <a href="<?=htmlspecialchars($args['parent']['fullpath']. $i['path']. '.html')?>" target="_blank" title="просмотр"><img class="btn_view" src="images/icon_view.gif" width="17" height="17" border="0" /></a>
    <a href="?mod=htdocs&act=editdoc&amp;id=<?=$i['id']?>" title="редактировать"><img class="btn_edit" src="images/icon_edit.gif" width="17" height="17" border="0" /></a>
<?if($i['isindex']){?>
    <img class="btn_disable" src="images/icon_del_disable.gif" width="17" height="17" border="0" />
    <img class="btn_disable" src="images/icon_move_disable.gif" width="17" height="17" border="0" /></a>
<?}else{?>
    <a href="javascript:deldoc(<?=$i['id']?>)" title="удалить"><img class="btn_del" src="images/icon_del.gif" width="17" height="17" border="0" /></a>
    <a href="?mod=htdocs&act=movedoc&amp;id=<?=$i['id']?>" title="переместить"><img class="btn_move" src="images/icon_move.gif" width="17" height="17" border="0" /></a>
<?}?>
  </td>
</tr>
<?}?>
</table>
<p align="center"><?=$args['pages']?></p>