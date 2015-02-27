
<form action="?mod=<?=$_GET['mod']?>&act=addpr<?=isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:''?>" method="post" enctype="multipart/form-data">
  <table width="100%" border="0" cellspacing="2" cellpadding="4" class="table">
<?foreach($args['mod_fields2'] as $f){?>
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
  <?if($f['type']=="checkbox"){?>
  <tr>
    <th width="30%" align="right"><?=$f['title']?>:</th>
    <td width="70%">
        <input type="checkbox" value="1" name="<?=$f['name']?>" <?=$args['item'][$f['name']] ? 'checked="true"':''?> />
    </td>
  </tr>
  <?}?>
  <?if($f['type']=="date"){?>
  <tr>
    <th width="30%" align="right"><?=$f['title']?>:</th>
    <td width="70%">
      <input type="text" name="<?=$f['name']?>" id="datepicker" value="<?=strftime('%d.%m.%Y', time())?>" class="input" style="width:90px" readonly />
    </td>
  </tr>
  <?}?>
<?}?>
  <tr>
    <td></td><td><input type="submit" value="Добавить"></td>
  </tr>
  </table>
</form>