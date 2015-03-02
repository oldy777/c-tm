<? if ($args['item'][$args['_field']['name'] . '_image']['path'] != ""){ ?>
<div>
            <? if ($args['item'][$args['_field']['name'] . '_image']['width'] < 300 && $args['item'][$args['_field']['name'] . '_image']['height'] < 300){ ?>
                <img src="/upload/images/<?= $args['item'][$args['_field']['name'] . '_image']['path'] ?>"/>
            <? } else{ ?>
                <img src="/getimg.php?path=<?= $args['item'][$args['_field']['name'] . '_image']['path'] ?>&w=300&h=200"/>
    <? } ?>
            <p style="font-size:12px;"><input type="checkbox" value="1" name="<?= $args['_field']['name'] ?>_del"/> Удалить</p>

            <input type="file" name="<?= $args['_field']['name'] ?>" style="width:100%" maxlength="128"/>
</div>
<? } else{ ?>
       <input type="file" name="<?= $args['_field']['name'] ?>" style="width:100%" maxlength="128"/>
<?}?>