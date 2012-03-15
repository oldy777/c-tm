<script language="javascript" type="text/javascript"><!--
function groupsdelete(id)
{
  obj = document.getElementById(id);
  if(confirm('Удaлить группу "'+obj.innerHTML+'"?'))
  {
    document.location = '/admin/?mod=groups&act=delete&id='+id;
  }
}
//--></script>
<table width="100%" border="0" cellspacing="2" cellpadding="4" class="table">
<thead>
<tr>
  <th width="1%"><a href="/admin/?mod=groups&amp;order=id&amp;desc=<?=($args['order']=='id'? !$args['desc'] : '')?>"groups><?=($args['order']=='id'? ($args['desc']? '<img src="/admin/images/arr_up.gif" width="10" height="6" border="0" />' : '<img src="/admin/images/arr_down.gif" width="10" height="6" border="0" />') : '')?>&nbsp;#</a></th>
  <th width="96%"><a href="/admin/?mod=groups&amp;order=title&amp;desc=<?=($args['order']=='title'? !$args['desc'] : '')?>"groups><?=($args['order']=='title'? ($args['desc']? '<img src="/admin/images/arr_up.gif" width="10" height="6" border="0" />' : '<img src="/admin/images/arr_down.gif" width="10" height="6" border="0" />') : '')?>&nbsp;Имя</a></th>
  <th width="1%" title="пользоватлей в группе"><a href="/admin/?mod=groups&amp;order=members&amp;desc=<?=($args['order']=='members'? !$args['desc'] : '')?>"><?=($args['order']=='members'? ($args['desc']? '<img src="/admin/images/arr_up.gif" width="10" height="6" border="0" />' : '<img src="/admin/images/arr_down.gif" width="10" height="6" border="0" />') : '')?>&nbsp;Членов</a></th>
  <th width="1%" title="время создания"><a href="/admin/?mod=groups&amp;order=created&amp;desc=<?=($args['order']=='created'? !$args['desc'] : '')?>"><?=($args['order']=='created'? ($args['desc']? '<img src="/admin/images/arr_up.gif" width="10" height="6" border="0" />' : '<img src="/admin/images/arr_down.gif" width="10" height="6" border="0" />') : '')?>&nbsp;Создан</a></th>
  <th width="1%" title="время последнего редактирования"><a href="/admin/?mod=groups&amp;order=updated&amp;desc=<?=($args['order']=='updated'? !$args['desc'] : '')?>"><?=($args['order']=='updated'? ($args['desc']? '<img src="/admin/images/arr_up.gif" width="10" height="6" border="0" />' : '<img src="/admin/images/arr_down.gif" width="10" height="6" border="0" />') : '')?>&nbsp;Редактирован</a></th>
  <th title="команды" width="1%">&nbsp;</th>
</tr>
</thead>
<?foreach($args['items'] as $i){?>
<tr align="center" class="<?=($i['blocked']? 'hidden' : '')?>">
  <td><?=$i['id']?></td>
  <td align="left" id="<?=$i['id']?>"><?=htmlspecialchars($i['title'])?></td>
  <td><?=$i['members']?></td>
  <td nowrap="nowrap"><?=strftime('%d.%m.%y %H:%M', $i['created'])?></td>
  <td nowrap="nowrap"><?=strftime('%d.%m.%y %H:%M', $i['updated'])?></td>
  <td nowrap="nowrap">
    <a href="/admin/?mod=groups&act=edit&amp;id=<?=$i['id']?>" title="редактировать"><img class="btn_edit" src="/admin/images/icon_edit.gif" width="17" height="17" border="0" /></a>
    <a href="javascript:groupsdelete(<?=$i['id']?>)" title="удалить"><img class="btn_del" src="/admin/images/icon_del.gif" width="17" height="17" border="0" /></a>
  </td>
</tr>
<?}?>
</table>