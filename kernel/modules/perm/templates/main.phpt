<table width="100%" border="0" cellspacing="2" cellpadding="4" class="table">
<thead>
<tr>
  <th>Модуль</th>
  <th colspan="2">Пользователи</th>
  <!--<th colspan="2">Группы</th>-->
</tr>
</thead>
<?foreach($args['items'] as $i){?>
<tr class="<?=($i['hidden']? 'hidden' : '')?>" title="<?=htmlspecialchars($i['descr'])?>">
  <td width="40%"><?=htmlspecialchars($i['title'])?> (<?=htmlspecialchars($i['name'])?>)</td>
  <td width="29%" align="center"><?=(empty($i['users'])? '&mdash;' : implode(', ', $i['users']))?></td>
  <td width="1%"><a href="/admin/?mod=perm&act=users&amp;id=<?=htmlspecialchars($i['name'])?>" title="редактировать"><img class="btn_edit" src="/admin/images/icon_edit.gif" width="17" height="17" border="0" /></a></td>
  <!--<td width="29%" align="center"><?=(empty($i['groups'])? '&mdash;' : implode(', ', $i['groups']))?></td>-->
  <!--<td width="1%"><a href="/admin/?mod=perm&act=groups&amp;id=<?=htmlspecialchars($i['name'])?>" title="редактировать"><img class="btn_edit" src="/admin/images/icon_edit.gif" width="17" height="17" border="0" /></a></td>-->
</tr>
<?}?>
</table>
<p>* суперпользователь &ndash; root имеет полный доступ на все модули сайта.</p>