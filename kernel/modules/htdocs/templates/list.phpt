<?if(!empty($args['items'])){?>
<ul>
<?foreach($args['items'] as $i){?>
<li><a href="<?=$i['path']?>"><?=$i['title']?></a></li>
<?}?>
</ul>
<?if($args['pages']){?><p align="center">Cтраницы: <?=$args['pages']?></p><?}?>
<?}?>