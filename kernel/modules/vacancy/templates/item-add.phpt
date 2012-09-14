<script type="JavaScript/text" src="/jscript/calendar.js"></script>
<form action="?mod=<?=$_GET['mod']?>&act=additem" method="post" enctype="multipart/form-data">
  <table width="100%" border="0" cellspacing="2" cellpadding="4" class="table">
<?foreach($args['mod_fields'] as $f){?>
  <?if($f['type']=="varchar"){?>
  <tr>
    <th width="30%" align="right"><?=$f['title']?>:</th>
    <td width="70%"><input type="text" name="<?=$f['name']?>" value="" autocomplete="off" style="width:100%;"/></td>
  </tr>
  <?}?>
  <?if($f['type']=="text"){?>
  <tr>
    <th width="30%" align="right"><?=$f['title']?>:</th>
    <td width="70%"><textarea name="<?=$f['name']?>" style="width:100%;height:100px"></textarea></td>
  </tr>
  <?}?>
  <?if($f['type']=="editor"){?>
  <tr>
    <th width="30%" align="right"><?=$f['title']?>:</th>
    <td height="300px">
    <?
      $CKEditor = new CKEditor();
      $CKEditor->config['height'] = 200;
      $CKEditor->config['toolbar'] = array(
	      array( 'Source','-','Templates'),
        array( 'Cut','Copy','Paste','PasteText','PasteFromWord'),
        array( 'Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'),
        array( 'BidiLtr', 'BidiRtl'),
        array( 'Bold','Italic','Underline','Strike','-','Subscript','Superscript'),
        array( 'NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'),
        array( 'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'),
        array( 'Link','Unlink','Anchor'),
        array( 'Flash','Image','Youtube','Table','HorizontalRule','SpecialChar'),
        array( 'Format','FontSize'),
        array( 'TextColor','BGColor'),
        array( 'Maximize', 'ShowBlocks')
      );
      $CKEditor->editor($f['name'], $args['item'][$f['name']]);
    ?>
    </td>
  </tr>
  <?}?>
  <?if($f['type']=="image"){?>
  <tr>
    <th width="30%" align="right"><?=$f['title']?>:</th>
    <td width="70%">
      <input type="file" name="<?=$f['name']?>" style="width:100%" maxlength="128"/>
    </td>
  </tr>
  <?}?>
  <?if($f['type']=="file"){?>
  <tr>
    <th width="30%" align="right"><?=$f['title']?>:</th>
    <td width="70%">
      <input type="file" name="<?=$f['name']?>" style="width:100%" maxlength="128"/>
    </td>
  </tr>
  <?}?>

  <?if($f['type']=="pass"){?>
  <tr>
      <th align="right" class="">Пароль:</th>
      <td><input type="password" name="<?=$f['name']?>" value="" style="width:100%" maxlength="128" /></td>
  </tr>
  <tr>
      <th align="right" class=" ">Повтор пароля:</th>
      <td><input type="password" name="passwd2" value="" style="width:100%" maxlength="128" /></td>
  </tr>
  <?}?>

  <?if($f['type']=="option"){?>
  <tr>
    <th width="30%" align="right"><?=$f['title']?>:</th>
    <td width="70%">
      <select name="<?=$f['name']?>" style="width:100%;"/>
      <?foreach($args['options'][$f['name']]['values'] as $v=>$o){?>
        <option value="<?=$v?>"><?=$o?></option>
      <?}?>
      </select>
    </td>
  </tr>
  <?}?>
  <?if($f['type']=="date"){?>
  <tr>
    <th width="30%" align="right"><?=$f['title']?>:</th>
    <td width="70%">
      <div id="created_leer<?=$f['name']?>" style="position: absolute; top: 200; left: 10; z-index: 666; visibility: hidden;"></div>
      <input type="text" id="<?=$f['name']?>" name="<?=$f['name']?>" value="<?=strftime('%d.%m.%Y', time())?>" class="input" style="width:90px" readonly /><?($errors['place']? ' style="color:red"' : '')?>&nbsp;<input type="button" class="button" style="width:18px" value=" &darr; " onMouseDown="showCalendar(this, 'created_leer<?=$f['name']?>', 'created_a', '<?=$f['name']?>');" />
      <a name="created_a">&nbsp;</a>
    </td>
  </tr>
  <?}?>
<?}?>
  <tr>
    <td></td><td><input type="submit" value="Добавить"></td>
  </tr>
  </table>
</form>