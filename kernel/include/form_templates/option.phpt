<select name="<?= $args['_field']['name'] ?>" style="width:100%;"/>
<? foreach ($args['options'][$args['_field']['name']]['values'] as $v => $o){ ?>
    <option value="<?= $v ?>"<?= (isset($args['item'][$args['_field']['name']]) && $args['item'][$args['_field']['name']] == $v) ? ' selected' : '' ?>><?= $o ?></option>
<? } ?>
</select>