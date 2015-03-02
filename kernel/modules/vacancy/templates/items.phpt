<?if($args['mod_pos']){?>
<script language="javascript" type="text/javascript" src="/admin/sort.js"></script>
<?}?>
<script language="javascript" type="text/javascript">
function del(id)
{
    
  swal({
        title: "Вы уверены что хотите удалить элемент из списка?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Да, удалить",
        cancelButtonText: "Нет, отмена"
        },
        function(isConfirm) {
            if (isConfirm) {
                document.location = '/admin/?mod=<?= $_GET['mod'] ?>&act=delitem&id=' + id + '<?=isset($_GET['page'])?('&page='.$_GET['page']):''?>';
  }
        });
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
            
            if(ui.position.top > ui.originalPosition.top)
                newpos(ui.item.children('.item').children('.inp'),2);
            else
                newpos(ui.item.children('.item').children('.inp'),1);
    }});
});

</script>
<?}?>
<?
$width=98;
if($args['mod_pos']) $width=$width-3;
$width=ceil($width/$args['mod_view']);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="4" class="table tablelist">
  <thead>
    <tr>
      <th width="1%"><a href="?mod=<?=$_GET['mod']?>&order=id&type=<?=isset($_GET['order']) && $_GET['order']=='id' ? ($_GET['type']=='ASC'?'DESC':'ASC'):'ASC'?>">
                #<?if(isset($_GET['order']) && $_GET['order']=='id'){?>
                    <?if($_GET['type']=='ASC'){?>
                        <img src="/admin/images/arrow_skip_down.png" style="vertical-align: middle" />
                    <?}else{?>
                        <img src="/admin/images/arrow_skip_up.png" style="vertical-align: middle" />
                    <?}?>
                <?}?>
            </a>
      </th>
  <?if($args['mod_pos']){?>
      <th width="3%">Поз.</th>
  <?}?>
<?foreach($args['mod_fields'] as $f){?>
  <?if($f['view']==1){?>
      <th width="<?=$width?>%"><a href="?mod=<?=$_GET['mod']?>&order=<?=$f['alt'] ? $f['alt']:$f['name']?>&type=<?=isset($_GET['order']) && $_GET['order']==($f['alt'] ? $f['alt']:$f['name']) ? ($_GET['type']=='ASC'?'DESC':'ASC'):'ASC'?>">
              <?=$f['title']?><?if(isset($_GET['order']) && $_GET['order']==($f['alt'] ? $f['alt']:$f['name'])){?>
                    <?if($_GET['type']=='ASC'){?>
                        <img src="/admin/images/arrow_skip_down.png" style="vertical-align: middle" />
                    <?}else{?>
                        <img src="/admin/images/arrow_skip_up.png" style="vertical-align: middle" />
                    <?}?>
                <?}?>
          </a></th>
  <?}?>
<?}?>
      <th title="команды" width="1%">
          <img width="16" height="16" border="0" src="/admin/images/tools.png">
      </th>
    </tr>
  </thead>
 <tbody>

<?$cnt = 0;foreach($args['items'] as $i){?>
  <?
    $link = '?mod='.$_GET['mod'].'&act=edititem&id='.$i['id'].(isset($_GET['page'])?('&page='.$_GET['page']):'');
  ?>
  <tr align="center" class="container <?=((++$cnt%2)==0 ? 'odd':'')?>">
    <td style="font-size:11px;color:#999;"><?=$i['id']?></td>
  <?if($args['mod_pos']){?>
    <td class="item" id="<?=$i['pos']?>" style="font-size:11px;color:#999;"><input class="inp" id="<?=$i['id']?>" style="width:40px; display:none; font-size:11px; text-align:center;" type="text" value="<?=$i['pos']?>" rel="<?=$args['mod_table_name']?>" cat="" cat_val="" /><span style="display:block; width:40px;"><?=$i['pos']?></span></td>
  <?}?>
<?foreach($args['mod_fields'] as $f){?>
  <?if($f['view']==1){?>
    <td align="left" id="<?=$i['id']?>">
        <?=ValuesFnc::showValue($i, $f, $args, $link)?>
    </td>
  <?}?>
<?}?>
    <td nowrap="nowrap">
      <a href="<?=$link?>" title="редактировать"><img class="btn_edit" src="/admin/images/icon_edit.gif" width="17" height="17" border="0" /></a>
      <a href="javascript:del(<?=$i['id']?>)" title="удалить"><img class="btn_del" src="/admin/images/icon_del.gif" width="17" height="17" border="0" /></a>
    </td>
  </tr>
<?}?>
 </tbody>
</table>
<?if  ($args['pages'] > 1) { ?>
    <ul class="list-pages">
        <li><a class="pages-left" href="/admin/?mod=<?=$_GET['mod']?><?=isset($_GET['order']) && $_GET['order'] ? '&order='.$_GET['order'].'&type='.$_GET['type']:''?>&page=<?= $args['page'] > 1 ? ($args['page'] - 1) : $args['page'] ?>">Назад</a></li>
        <? for ($i = 1; $i <= $args['pages']; $i++) { ?>
            <? if ($args['page'] == $i) { ?>
                <li><b class="active"><?= $i ?></b></li>
            <? } else { ?>
                <li><a href="/admin/?mod=<?=$_GET['mod']?><?=isset($_GET['order']) && $_GET['order'] ? '&order='.$_GET['order'].'&type='.$_GET['type']:''?>&page=<?= $i ?>"><?= $i ?></a></li>
            <? } ?>
            <? if ($i == 1 && ($args['page'] - 1) > 4) { ?>
                <li>...</li>
                <? $i = $args['page'] - 4;
                continue;
            } ?>
                        
            <? if ($i > ($args['page'] + 4) && ($args['pages'] - $i) > 1) { ?>
                <li>...</li>
                <li><a href="/admin/?mod=<?=$_GET['mod']?><?=isset($_GET['order']) && $_GET['order'] ? '&order='.$_GET['order'].'&type='.$_GET['type']:''?>&page=<?= $args['pages'] ?>"><?= $args['pages'] ?></a></li>
            <? break;
        } ?>
    <? } ?>
        <li><a class="pages-right" href="/admin/?mod=<?=$_GET['mod']?><?=isset($_GET['order']) && $_GET['order'] ? '&order='.$_GET['order'].'&type='.$_GET['type']:''?>&page=<?= $args['page'] < $args['pages'] ? ($args['page'] + 1) : $args['page'] ?>">Вперед</a></li>
    </ul>
<? } ?>
