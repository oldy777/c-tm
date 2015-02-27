<?if($args['mod_pos3']){?>
<script language="javascript" type="text/javascript" src="/admin/sort.js"></script>
<?}?>
<script language="javascript" type="text/javascript">
function del(id)
{
  obj = document.getElementById(id);
  if(confirm('Удaлить объект из списка?'))
  {
    document.location = '/admin/?mod=<?=$_GET['mod']?>&act=deldetail&detail='+id+'<?=isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:''?><?=isset($_GET['id']) && $_GET['id'] ? '&id='.$_GET['id']:''?>';
  }
}
</script>
<?if($args['mod_pos3']){?>
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
if($args['mod_pos3']) $width=$width-3;
$width=ceil($width/$args['mod_view']);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="4" class="table tablelist">
  <thead>
    <tr>
      <th width="1%">#</th>
  <?if($args['mod_pos3']){?>
      <th width="3%">Поз.</th>
  <?}?>
<?foreach($args['mod_fields3'] as $f){?>
  <?if($f['view']==1){?>
      <th width="<?=$width?>%"><?=$f['title']?></th>
  <?}?>
<?}?>
      <th title="команды" width="1%">
          <img width="16" height="16" border="0" src="/admin/images/tools.png">
      </th>
    </tr>
  </thead>
 <tbody>
<?$cnt = 0;foreach($args['items'] as $i){?>
  <tr align="center" class="container <?=((++$cnt%2)==0 ? 'odd':'')?>">
    <td style="font-size:11px;color:#999;"><?=$i['id']?></td>
  <?if($args['mod_pos3']){?>
    <td class="item" id="<?=$i['pos']?>" style="font-size:11px;color:#999;"><input class="inp" id="<?=$i['id']?>" style="width:40px; display:none; font-size:11px; text-align:center;" type="text" value="<?=$i['pos']?>" rel="<?=$args['mod_table_name3']?>" cat="catalog_items_id" cat_val="<?=$i['catalog_items_id']?>" /><span style="display:block; width:40px;"><?=$i['pos']?></span></td>
  <?}?>
<?foreach($args['mod_fields3'] as $f){?>
  <?if($f['view']==1){?>
    <td align="left" id="<?=$i['id']?>">
        <?if(isset($f['link']) && $f['link']==1){?>
             <a href="?mod=<?=$_GET['mod']?><?=isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:''?><?=isset($_GET['id']) && $_GET['id'] ? '&id='.$_GET['id']:''?><?=isset($_GET['order']) && $_GET['order'] ? '&order='.$_GET['order'].'&type='.$_GET['type']:''?><?=isset($_GET['page']) && $_GET['page'] ? '&page='.$_GET['page']:''?>&act=editdetail&detail=<?=$i['id']?>" title="редактировать">
                    <?=mb_substr(htmlspecialchars($i[$f['name']]),0,250,'UTF-8')?>
             </a>
        <?}else{?>
             <?=mb_substr(htmlspecialchars($i[$f['name']]),0,250,'UTF-8')?>
        <?}?>
    </td>
  <?}?>
<?}?>
    <td nowrap="nowrap">
      <a href="?mod=<?=$_GET['mod']?><?=isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:''?><?=isset($_GET['id']) && $_GET['id'] ? '&id='.$_GET['id']:''?><?=isset($_GET['order']) && $_GET['order'] ? '&order='.$_GET['order'].'&type='.$_GET['type']:''?><?=isset($_GET['page']) && $_GET['page'] ? '&page='.$_GET['page']:''?>&act=editdetail&detail=<?=$i['id']?>" title="редактировать"><img class="btn_edit" src="/admin/images/icon_edit.gif" width="17" height="17" border="0" /></a>
      <a href="javascript:del(<?=$i['id']?>)" title="удалить"><img class="btn_del" src="/admin/images/icon_del.gif" width="17" height="17" border="0" /></a>
    </td>
  </tr>
<?}?>
 </tbody>
</table>

