<?if(!empty($args['items'])){?>
<dl>
<?foreach($args['items'] as $i){?>
<dt><?=strftime('%d.%m.%Y', $i['created'])?></dt>
<dd><a href="<?=$i['path']?>"><?=$i['title']?></a></dd>
<?}?>
</dl>
<?if($args['pages']!=''){?><p align="center">Cтраницы: <?=$args['pages']?></p><?}?>
<?}?>

