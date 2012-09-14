<?if($args['mod_pos']){?>
<script language="javascript" type="text/javascript" src="/admin/sort.js"></script>
<?}?>
<script language="javascript" type="text/javascript">
function del(id)
{
  obj = document.getElementById(id);
  if(confirm('Удaлить "'+obj.innerHTML+'" из списка?'))
  {
    document.location = '/admin/?mod=<?=$_GET['mod']?>&act=delitem&id='+id+'';
  }
}
</script>
<?if($args['mod_pos']){?>
<script type="text/javascript">
var fixHelper = function(e, ui) {
    ui.children().each(function() {
        $(this).width($(this).width());
    });
    return ui;
};
$(document).ready(function(){
    $(".table tbody").sortable({helper:fixHelper,opacity: 0.8,update:function(event, ui){
            
            newpos(ui.item.children('.item').children('.inp'),1);
    }});
})

</script>
<?}?>
<?
$width=98;
if($args['mod_pos']) $width=$width-3;
$width=ceil($width/$args['mod_view']);
?>
<table width="100%" border="0" cellspacing="2" cellpadding="4" class="table">
  <thead>
    <tr>
      <th width="1%">#</th>
  <?if($args['mod_pos']){?>
      <th width="3%">&nbsp;</th>
  <?}?>
<?foreach($args['mod_fields'] as $f){?>
  <?if($f['view']==1){?>
      <th width="<?=$width?>%"><?=$f['title']?></th>
  <?}?>
<?}?>
      <th title="команды" width="1%">&nbsp;</th>
    </tr>
  </thead>
 <tbody>
<?foreach($args['items'] as $i){?>
  <tr align="center" class="container">
    <td style="font-size:11px;color:#999;"><?=$i['id']?></td>
  <?if($args['mod_pos']){?>
    <td class="item" id="<?=$i['pos']?>" style="font-size:11px;color:#999;"><input class="inp" id="<?=$i['id']?>" style="width:40px; display:none; font-size:11px; text-align:center;" type="text" value="<?=$i['pos']?>" rel="<?=$args['mod_table_name']?>" /><span style="display:block; width:40px;"><?=$i['pos']?></span></td>
  <?}?>
<?foreach($args['mod_fields'] as $f){?>
  <?if($f['view']==1){?>
    <td align="left" id="<?=$i['id']?>"><?=mb_substr(htmlspecialchars($i[$f['name']]),0,250,'UTF-8')?></td>
  <?}?>
<?}?>
    <td nowrap="nowrap">
      <a href="?mod=<?=$_GET['mod']?>&act=edititem&id=<?=$i['id']?>" title="редактировать"><img class="btn_edit" src="/admin/images/icon_edit.gif" width="17" height="17" border="0" /></a>
      <a href="javascript:del(<?=$i['id']?>)" title="удалить"><img class="btn_del" src="/admin/images/icon_del.gif" width="17" height="17" border="0" /></a>
    </td>
  </tr>
<?}?>
 </tbody>
</table>
