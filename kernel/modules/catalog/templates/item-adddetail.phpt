
<form method="post" enctype="multipart/form-data">
  <table width="100%" border="0" cellspacing="2" cellpadding="4" class="table">
<?foreach($args['mod_fields3'] as $f){?>
  <tr>
    <th width="30%" align="right"><?=$f['title']?>:</th>
    <td width="70%">
        <?=  ValuesFnc::makeFormValues($f, $args); ?>
    </td>
  </tr>
<?}?>
  <tr>
    <td></td><td><input type="submit" value="Добавить"></td>
  </tr>
  </table>
</form>