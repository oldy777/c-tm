<script language="javascript" type="text/javascript"><!--
function maketsinherit(docs)
{
   alert('Удаление не возможно!\nС этим макетом дизайн связано ' + docs + ' докуметов или папок');
}
function maketsdelete(id)
{
  obj = document.getElementById(id);
  if(confirm('Удaлить макет "'+obj.innerHTML+'"?'))
  {
    document.location = '/admin/?mod=makets&act=delete&id='+id;
  }
}
//--></script>
<table width="100%" border="0" cellspacing="2" cellpadding="4" class="table">
<thead><tr>
<th width="1%"><a href="/admin/?mod=makets&amp;order=id&amp;desc=<?=($args['order']=='id'? !$args['desc'] : '')?>"><?=($args['order']=='id'? ($args['desc']? '<img src="/admin/images/arr_up.gif" width="10" height="6" border="0" />' : '<img src="/admin/images/arr_down.gif" width="10" height="6" border="0" />') : '')?>&nbsp;#</a></th>
<th width="96%"><a href="/admin/?mod=makets&amp;order=title&amp;desc=<?=($args['order']=='title'? !$args['desc'] : '')?>"><?=($args['order']=='title'? ($args['desc']? '<img src="/admin/images/arr_up.gif" width="10" height="6" border="0" />' : '<img src="/admin/images/arr_down.gif" width="10" height="6" border="0" />') : '')?>&nbsp;Имя</a></th>
<th width="1%"><a href="/admin/?mod=makets&amp;order=docs&amp;desc=<?=($args['order']=='docs'? !$args['desc'] : '')?>"><?=($args['order']=='docs'? ($args['desc']? '<img src="/admin/images/arr_up.gif" width="10" height="6" border="0" />' : '<img src="/admin/images/arr_down.gif" width="10" height="6" border="0" />') : '')?>&nbsp;Документов</a></th>
<th title="время создания" width="1%"><a href="/admin/?mod=makets&amp;order=created&amp;desc=<?=($args['order']=='created'? !$args['desc'] : '')?>"><?=($args['order']=='created'? ($args['desc']? '<img src="/admin/images/arr_up.gif" width="10" height="6" border="0" />' : '<img src="/admin/images/arr_down.gif" width="10" height="6" border="0" />') : '')?>&nbsp;Создан</a></th>
<th title="время последнего редактирования" width="1%"><a href="/admin/?mod=makets&amp;order=updated&amp;desc=<?=($args['order']=='updated'? !$args['desc'] : '')?>"><?=($args['order']=='updated'? ($args['desc']? '<img src="/admin/images/arr_up.gif" width="10" height="6" border="0" />' : '<img src="/admin/images/arr_down.gif" width="10" height="6" border="0" />') : '')?>&nbsp;Редактирован</a></th>
<th title="команды" width="1%">&nbsp;</th>
</tr></thead>
<?foreach($args['items'] as $i){?>
<tr align="center">
<td><?=$i['id']?></td>
<td align="left" id="<?=$i['id']?>"><?=htmlspecialchars($i['title'])?></td>
<td><?=(int)$i['docs']?></td>
<td nowrap="nowrap"><?=strftime('%d.%m.%y %H:%M', $i['created'])?></td>
<td nowrap="nowrap"><?=strftime('%d.%m.%y %H:%M', $i['updated'])?></td>
<td nowrap="nowrap">
<a href="/admin/?mod=makets&act=view&amp;id=<?=$i['id']?>" title="просмотр"><img class="btn_view" src="/admin/images/icon_view.gif" width="17" height="17" border="0" /></a>
<a href="/admin/?mod=makets&act=copy&amp;id=<?=$i['id']?>" title="копировать"><img class="btn_copy" src="/admin/images/icon_copy.gif" width="17" height="17" border="0" /></a>
<a href="/admin/?mod=makets&act=edit&amp;id=<?=$i['id']?>" title="редактировать"><img class="btn_edit" src="/admin/images/icon_edit.gif" width="17" height="17" border="0" /></a>
<?if($i['docs'] > 0){?>
<a href="javascript:maketsinherit(<?=(int)$i['docs']?>)"><img class="btn_disable" src="/admin/images/icon_del_disable.gif" width="17" height="17" border="0" /></a>
<?}else{?>
<a href="javascript:maketsdelete(<?=$i['id']?>)" title="удалить"><img class="btn_del" src="/admin/images/icon_del.gif" width="17" height="17" border="0" /></a>
<?}?>
</td>
</tr>
<?}?>
</table>
<br /><br />
<p align="center"><?=$args['pages']?></p>