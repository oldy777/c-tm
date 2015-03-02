<? if ($args['item'][$args['_field']['name'] . '_file']['path'] != ""){ ?>
<div>
            <a href="/upload/files/<?= $args['item'][$args['_field']['name'] . '_file']['name'] ?>"><?= $args['item'][$args['_field']['name'] . '_file']['name'] ?></a>
            <p style="font-size:12px;"><input type="checkbox" value="1" name="<?= $args['_field']['name'] ?>_del"/> Удалить</p>
</div>
            <input type="file" name="<?= $args['_field']['name'] ?>" style="width:100%" maxlength="128"/>
<? } else { ?>
      <input type="file" name="<?= $args['_field']['name'] ?>" style="width:100%" maxlength="128"/>
<?}?>