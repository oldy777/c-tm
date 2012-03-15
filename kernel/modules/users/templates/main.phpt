<script language="javascript" type="text/javascript"><!--
function usersdelete(id)
{
  if(id==1) { alert('Суперпользователя удалить нельзя'); }
  else
  {
    obj = document.getElementById(id);
    if(obj && confirm('Удaлить пользователя "'+obj.innerHTML+'"?'))
    {
      document.location = '/admin/?mod=users&act=delete&id='+id;
    }
  }
}
//--></script>
<table width="100%" border="0" cellspacing="2" cellpadding="4" class="table">
<thead>
<tr>
  <th width="1%"><a href="/admin/?mod=users&amp;order=id&amp;desc=<?=($args['order']=='id'? !$args['desc'] : '')?>"><?=($args['order']=='id'? ($args['desc']? '<img src="/admin/images/arr_up.gif" width="10" height="6" border="0" />' : '<img src="/admin/images/arr_down.gif" width="10" height="6" border="0" />') : '')?>&nbsp;#</a></th>
  <th width="32%"><a href="/admin/?mod=users&amp;order=login&amp;desc=<?=($args['order']=='login'? !$args['desc'] : '')?>"><?=($args['order']=='login'? ($args['desc']? '<img src="/admin/images/arr_up.gif" width="10" height="6" border="0" />' : '<img src="/admin/images/arr_down.gif" width="10" height="6" border="0" />') : '')?>&nbsp;Логин</a></th>
  <th width="32%"><a href="/admin/?mod=users&amp;order=email&amp;desc=<?=($args['order']=='email'? !$args['desc'] : '')?>"><?=($args['order']=='email'? ($args['desc']? '<img src="/admin/images/arr_up.gif" width="10" height="6" border="0" />' : '<img src="/admin/images/arr_down.gif" width="10" height="6" border="0" />') : '')?>&nbsp;Email</a></th>
  <th width="32%"><a href="/admin/?mod=users&amp;order=name&amp;desc=<?=($args['order']=='name'? !$args['desc'] : '')?>"><?=($args['order']=='name'? ($args['desc']? '<img src="/admin/images/arr_up.gif" width="10" height="6" border="0" />' : '<img src="/admin/images/arr_down.gif" width="10" height="6" border="0" />') : '')?>&nbsp;Имя</a></th>
  <th width="1%"><a href="/admin/?mod=users&amp;order=created&amp;desc=<?=($args['order']=='created'? !$args['desc'] : '')?>"><?=($args['order']=='created'? ($args['desc']? '<img src="/admin/images/arr_up.gif" width="10" height="6" border="0" />' : '<img src="/admin/images/arr_down.gif" width="10" height="6" border="0" />') : '')?>&nbsp;Создан</a></th>
  <th width="2%" title="команды">&nbsp;</th>
</tr>
</thead>
<?foreach($args['items'] as $i){?>
<tr align="center" class="<?=($i['blocked']? 'hidden' : '')?>">
  <td><?=$i['id']?></td>
  <td align="left" id="<?=$i['id']?>" nowrap="nowrap"><?=htmlspecialchars($i['login'])?></td>
  <td align="left" nowrap="nowrap"><?=htmlspecialchars($i['email'])?>&nbsp;</td>
  <td align="left" nowrap="nowrap"><?=htmlspecialchars($i['name'])?>&nbsp;</td>
  <td nowrap="nowrap"><?=strftime('%d.%m.%y %H:%M', $i['created'])?></td>
  <td nowrap="nowrap">
    <a href="/admin/?mod=users&act=edit&amp;id=<?=$i['id']?>" title="редактировать"><img class="btn_edit" src="/admin/images/icon_edit.gif" width="17" height="17" border="0" /></a>
    <a href="javascript:usersdelete(<?=$i['id']?>)" title="удалить"><?if($i['id']==1){?><img class="btn_disable" src="/admin/images/icon_del_disable.gif" width="17" height="17" border="0" /><?}else{?><img class="btn_del" src="/admin/images/icon_del.gif" width="17" height="17" border="0" /><?}?></a>
  </td>
</tr>
<?}?>
</table>
<?if($args['pages']){?><p align="center">Страницы:<?=$args['pages']?></p><?}?>
<p>* суперпользователя &mdash; root, удалить нельзя.</p>