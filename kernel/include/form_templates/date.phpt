<input type="text" name="<?=$args['_field']['name']?>"  value="<?= $args['item'][$args['_field']['name']] ? strftime('%d.%m.%Y', $args['item'][$args['_field']['name']]):date('d.m.Y')?>" class="input datepicker" style="width:90px" readonly />