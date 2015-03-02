<?if($args['mod_pos']){?>
<script language="javascript" type="text/javascript" src="/admin/sort.js"></script>
<?}?>
<script language="javascript" type="text/javascript">
function del(id)
{
  obj = document.getElementById(id);
  if(confirm('Удaлить "'+obj.innerHTML+'" из списка?'))
  {
    document.location = '/admin/?mod=<?=$_GET['mod']?>&act=delitem&id='+id+'<?=isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:''?><?=isset($_GET['order']) && $_GET['order'] ? '&order='.$_GET['order'].'&type='.$_GET['type']:''?><?=isset($_GET['page']) && $_GET['page'] ? '&page='.$_GET['page']:''?>';
  }
}
function del2(id)
{
  obj = document.getElementById(id);
  if(confirm('Удaлить "'+obj.innerHTML+'" из списка?'))
  {
    document.location = '/admin/?mod=<?=$_GET['mod']?>&act=delpr&id='+id+'<?=isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:''?><?=isset($_GET['order']) && $_GET['order'] ? '&order='.$_GET['order'].'&type='.$_GET['type']:''?><?=isset($_GET['page']) && $_GET['page'] ? '&page='.$_GET['page']:''?>';
  }
}
$(document).ready(function(){
    $('.market').change(function(){
        var id = $(this).attr('rel');
        var val = 0;
        if($(this).is(':checked'))
        {
            val = 1;
        }
        $.post('/admin/index.php?mod=<?=$_GET['mod']?>&act=setmarkt&f_id=<?=$_GET['f_id']?>',{id:id, val:val});
    });
});
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
<?if(isset($args['item'])){?>
<h1 style="margin-bottom: 10px;"><?=$args['item']['title']?></h1>
<?}?>
<table width="100%" border="0" cellspacing="0" cellpadding="4" class="table tablelist" id="maintbl">
  <thead>
    <tr>
      <th width="1%"><a href="?mod=<?=$_GET['mod']?><?=isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:''?>&order=id&type=<?=isset($_GET['order']) && $_GET['order']=='id' ? ($_GET['type']=='ASC'?'DESC':'ASC'):'ASC'?>">
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
      <th width="<?=$width?>%"><a href="?mod=<?=$_GET['mod']?><?=isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:''?>&order=<?=$f['alt'] ? $f['alt']:$f['name']?>&type=<?=isset($_GET['order']) && $_GET['order']==($f['alt'] ? $f['alt']:$f['name']) ? ($_GET['type']=='ASC'?'DESC':'ASC'):'ASC'?>">
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
        $link = '?mod='.$_GET['mod'].(isset($_GET['order']) && $_GET['order'] ? '&order='.$_GET['order'].'&type='.$_GET['type']:'').(isset($_GET['page']) && $_GET['page'] ? '&page='.$_GET['page']:'').'&f_id='.$i['id'];
   ?>
  <tr align="center" class="container <?=((++$cnt%2)==0 ? 'odd':'')?>">
    <td style="font-size:11px;color:#999;"><?=$i['id']?></td>
  <?if($args['mod_pos']){?>
    <td class="item" id="<?=$i['pos']?>" style="font-size:11px;color:#999;"><input class="inp" id="<?=$i['id']?>" style="width:40px; display:none; font-size:11px; text-align:center;" type="text" value="<?=$i['pos']?>" rel="<?=$args['mod_table_name']?>" cat="<?=$_GET['f_id']?>" cat_val="parent_id" /><span style="display:block; width:40px;"><?=$i['pos']?></span></td>
  <?}?>
<?foreach($args['mod_fields'] as $f){?>
  <?if($f['view']==1){?>
    <td align="left" id="<?=$i['id']?>">
        <?=ValuesFnc::showValue($i, $f, $args, $link)?>
    </td>
  <?}?>
<?}?>
    <td nowrap="nowrap">
      <a href="?mod=<?=$_GET['mod']?><?=isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:''?><?=isset($_GET['order']) && $_GET['order'] ? '&order='.$_GET['order'].'&type='.$_GET['type']:''?><?=isset($_GET['page']) && $_GET['page'] ? '&page='.$_GET['page']:''?>&act=edititem&id=<?=$i['id']?>" title="редактировать"><img class="btn_edit" src="/admin/images/icon_edit.gif" width="17" height="17" border="0" /></a>
      <a href="javascript:del(<?=$i['id']?>)" title="удалить"><img class="btn_del" src="/admin/images/icon_del.gif" width="17" height="17" border="0" /></a>
    </td>
  </tr>
<?}?>
 </tbody>
</table>

<?if(isset($_GET['f_id']) && (int)$_GET['f_id']){?>
<h2 style="margin: 30px 0 15px 0">Продукты</h2>
<table width="100%" border="0" cellspacing="0" cellpadding="4" class="table tablelist" >
  <thead>
    <tr>
      <th width="1%"><a href="?mod=<?=$_GET['mod']?><?=isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:''?>&order=id&type=<?=isset($_GET['order']) && $_GET['order']=='id' ? ($_GET['type']=='ASC'?'DESC':'ASC'):'ASC'?>">
                #<?if(isset($_GET['order']) && $_GET['order']=='id'){?>
                    <?if($_GET['type']=='ASC'){?>
                        <img src="/admin/images/arrow_skip_down.png" style="vertical-align: middle" />
                    <?}else{?>
                        <img src="/admin/images/arrow_skip_up.png" style="vertical-align: middle" />
                    <?}?>
                <?}?>
            </a>
      </th>
  <?if($args['mod_pos2']){?>
      <th width="3%">Поз.</th>
  <?}?>
<?foreach($args['mod_fields2'] as $f){?>
  <?if($f['view']==1){?>
      <th width="<?=$width?>%"><a href="?mod=<?=$_GET['mod']?><?=isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:''?>&order=<?=$f['alt'] ? $f['alt']:$f['name']?>&type=<?=isset($_GET['order']) && $_GET['order']==($f['alt'] ? $f['alt']:$f['name']) ? ($_GET['type']=='ASC'?'DESC':'ASC'):'ASC'?>">
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
<?$cnt = 0;foreach($args['itemspr'] as $i){?>
  <?
        $link = '?mod='.$_GET['mod'].(isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:'').(isset($_GET['order']) && $_GET['order'] ? '&order='.$_GET['order'].'&type='.$_GET['type']:'').(isset($_GET['page']) && $_GET['page'] ? '&page='.$_GET['page']:'').'&act=editpr&id='.$i['id'];
  ?>
  <tr align="center" class="container <?=((++$cnt%2)==0 ? 'odd':'')?>">
    <td style="font-size:11px;color:#999;"><?=$i['id']?></td>
  <?if($args['mod_pos2']){?>
    <td class="item" id="<?=$i['pos']?>" style="font-size:11px;color:#999;"><input class="inp" id="<?=$i['id']?>" style="width:40px; display:none; font-size:11px; text-align:center;" type="text" value="<?=$i['pos']?>" rel="<?=$args['mod_table_name2']?>" cat="<?=$args['mod_table_name']?>_id" cat_val="<?=$_GET['f_id']?>" /><span style="display:block; width:40px;"><?=$i['pos']?></span></td>
  <?}?>
<?foreach($args['mod_fields2'] as $f){?>
  <?if($f['view']==1){?>
    <td align="left" id="<?=$i['id']?>">
        <?if($f['name']=='price'){?>
            <div class="color"><?=$i[$f['name']] ? mb_substr(($i[$f['name']]),0,250,'UTF-8'):'Не указан'?></div>
            <div class="color_inp"><input type="text" act="price" value="<?=mb_substr(($i[$f['name']]),0,250,'UTF-8')?>" title="<?=mb_substr(($i[$f['name']]),0,250,'UTF-8')?>" rel="<?=$i['id']?>" /></div>
        <?}else{?>
             <?=ValuesFnc::showValue($i, $f, $args, $link)?>
        <?}?>
    </td>
  <?}?>
<?}?>

    <td nowrap="nowrap">
      <a href="?mod=<?=$_GET['mod']?><?=isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:''?><?=isset($_GET['order']) && $_GET['order'] ? '&order='.$_GET['order'].'&type='.$_GET['type']:''?><?=isset($_GET['page']) && $_GET['page'] ? '&page='.$_GET['page']:''?>&act=editpr&id=<?=$i['id']?>" title="редактировать"><img class="btn_edit" src="/admin/images/icon_edit.gif" width="17" height="17" border="0" /></a>
      <a href="javascript:del2(<?=$i['id']?>)" title="удалить"><img class="btn_del" src="/admin/images/icon_del.gif" width="17" height="17" border="0" /></a>
    </td>
  </tr>
<?}?>
 </tbody>
</table>
<?}?>

<?if  ($args['pages'] > 1) { ?>
    <ul class="list-pages">
        <li><a class="pages-left" href="/admin/?mod=<?=$_GET['mod']?><?=isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:''?><?=isset($_GET['order']) && $_GET['order'] ? '&order='.$_GET['order'].'&type='.$_GET['type']:''?>&page=<?= $args['page'] > 1 ? ($args['page'] - 1) : $args['page'] ?>">Назад</a></li>
        <? for ($i = 1; $i <= $args['pages']; $i++) { ?>
            <? if ($args['page'] == $i) { ?>
                <li><b class="active"><?= $i ?></b></li>
            <? } else { ?>
                <li><a href="/admin/?mod=<?=$_GET['mod']?><?=isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:''?><?=isset($_GET['order']) && $_GET['order'] ? '&order='.$_GET['order'].'&type='.$_GET['type']:''?>&page=<?= $i ?>"><?= $i ?></a></li>
            <? } ?>
            <? if ($i == 1 && ($args['page'] - 1) > 4) { ?>
                <li>...</li>
                <? $i = $args['page'] - 4;
                continue;
            } ?>
                        
            <? if ($i > ($args['page'] + 4) && ($args['pages'] - $i) > 1) { ?>
                <li>...</li>
                <li><a href="/admin/?mod=<?=$_GET['mod']?><?=isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:''?><?=isset($_GET['order']) && $_GET['order'] ? '&order='.$_GET['order'].'&type='.$_GET['type']:''?>&page=<?= $args['pages'] ?>"><?= $args['pages'] ?></a></li>
            <? break;
        } ?>
    <? } ?>
        <li><a class="pages-right" href="/admin/?mod=<?=$_GET['mod']?><?=isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:''?><?=isset($_GET['order']) && $_GET['order'] ? '&order='.$_GET['order'].'&type='.$_GET['type']:''?>&page=<?= $args['page'] < $args['pages'] ? ($args['page'] + 1) : $args['page'] ?>">Вперед</a></li>
        <? if ($args['page'] != 'all') { ?>
        <li><a class="pages-right" href="/admin/?mod=<?=$_GET['mod']?><?=isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:''?><?=isset($_GET['order']) && $_GET['order'] ? '&order='.$_GET['order'].'&type='.$_GET['type']:''?>&page=all">Все</a></li>
        <?}else{?>
        <li><b class="active">Все</b></li>
        <?}?>
    </ul>
<? } ?>
<style>
    .color_inp{display: none}
</style>
<script>
$(document).ready(function(){
    $('.color').click(function(){
        $(this).hide();
        var inp = $(this).parent().children('.color_inp');
        $(inp).show();
        $(inp).children('input').focus();
    });
    
    $('.color_inp input').blur(function(){
        $(this).parent().hide();
        $(this).parent().parent().children('.color').show();
    });
    
    $('.color_inp input').keyup(function(e) {
        if(e.keyCode == 13){
          var id = $(this).attr('rel');
          var old = $(this).attr('title');
          var act = $(this).attr('act');
          var news = $(this).val();
          var obj = $(this);
          if(old != news)
          {
              $(this).attr('disabled','true');
              $.post('/admin/?mod=<?=$_GET['mod']?>&act='+act+'&id=<?=$_GET['id']?>', 
              {id:id, title:news}, function(data){
                  if(data.sux == 1)
                  {
                      $(obj).parent().hide();
                      $(obj).parent().parent().children('.color').text(news);
                      $(obj).parent().parent().children('.color').show();
                  }
                  else
                  {
                      alert('Ошибка при сохранении');
                  }
                   $(obj).removeAttr('disabled');
              },'json');
          }
        }
      });
});
</script>
