
<script>
$(function() {
		$( "#tabs" ).tabs();
	});
</script>
<form action="?mod=<?=$_GET['mod']?>&act=save" method="post" enctype="multipart/form-data">
<div id="tabs">
    <ul>
        <?foreach($args['mod_sections'] as $s){?>
            <li><a href="#tabs-<?=$s['name']?>"><?=$s['title']?></a></li>
        <?}?>
    </ul>
<?foreach($args['mod_sections'] as $s){?>
  <?if(sizeof($s['fields'])>0){?>
  <div id="tabs-<?=$s['name']?>">
  <table width="100%" border="0" cellspacing="2" cellpadding="4" class="table">
    <?foreach($s['fields'] as $f){?>
      <?if($f['type']=='text'){?>
      <tr>
        <th width="30%" align="right"><?=$f['title']?>:</th>
        <td width="70%"><input type="text" name="<?=$f['name']?>" style="width:100%" maxlength="128" value="<?=htmlspecialchars($args['item'][$f['name']])?>" /></td>
      </tr>
      <?}?>
      <?if($f['type']=='textarea'){?>
      <tr>
        <th width="30%" align="right"><?=$f['title']?>:</th>
        <td width="70%"><textarea name="<?=$f['name']?>" style="width:100%;height:100px;"><?=htmlspecialchars($args['item'][$f['name']])?></textarea></td>
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
        array( 'Image','Flash','Table','HorizontalRule','SpecialChar'),
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
      <?if($args['item'][$f['name'].'_image']['path']!=""){?>
      <tr>
        <th width="30%" align="right" rowspan="2"><?=$f['title']?>:</th>
        <td width="70%">
          <?if($args['item'][$f['name'].'_image']['width']<300&&$args['item'][$f['name'].'_image']['height']<300){?>
          <img src="/upload/images/<?=$args['item'][$f['name'].'_image']['path']?>"/>
          <?}else{?>
          <img src="/upload/images/<?=$args['item'][$f['name'].'_image']['path']?>"/>
          <?}?>
          <p style="font-size:12px;"><input type="checkbox" value="1" name="<?=$f['name']?>_del"/> Удалить</p>
        </td>
      </tr>
      <tr>
        <td>
          <input type="file" name="<?=$f['name']?>" style="width:100%" maxlength="128"/>
        </td>
      </tr>
        <?}else{?>
      <tr>
        <th width="30%" align="right"><?=$f['title']?>:</th>
        <td width="70%">
          <input type="file" name="<?=$f['name']?>" style="width:100%" maxlength="128"/>
        </td>
      </tr>
        <?}?>
      <?}?>
      <?if($f['type']=="file"){?>
        <?if($args['item'][$f['name'].'_file']['path']!=""){?>
      <tr>
        <th width="30%" align="right" rowspan="2"><?=$f['title']?>:</th>
        <td width="70%">
          <a href="/upload/files/<?=$args['item'][$f['name'].'_file']['name']?>"><?=$args['item'][$f['name'].'_file']['name']?></a>
          <p style="font-size:12px;"><input type="checkbox" value="1" name="<?=$f['name']?>_del"/> Удалить</p>
        </td>
      </tr>
      <tr>
        <td>
          <input type="file" name="<?=$f['name']?>" style="width:100%" maxlength="128"/>
        </td>
      </tr>
        <?}else{?>
      <tr>
        <th width="30%" align="right"><?=$f['title']?>:</th>
        <td width="70%">
          <input type="file" name="<?=$f['name']?>" style="width:100%" maxlength="128"/>
        </td>
      </tr>
        <?}?>
      <?}?>
      <?if($f['type']=="option"){?>
      <tr>
        <th width="30%" align="right"><?=$f['title']?>:</th>
        <td width="70%">
          <select name="<?=$f['name']?>" style="width:100%;"/>
          <?foreach($args['options'][$f['name']]['values'] as $v=>$o){?>
            <option value="<?=$v?>"<?=($args['item'][$f['name']]==$v)?' selected':''?>><?=$o?></option>
          <?}?>
          </select>
        </td>
      </tr>
      <?}?>
        <?if($f['type']=="date"){?>
      <tr>
        <th width="30%" align="right">Дата:</th>
        <td width="70%">
          <div id="created_leer" style="position: absolute; top: 200; left: 10; z-index: 666; visibility: hidden;"></div>
          <input type="text" name="<?=$f['name']?>" value="<?=strftime('%d.%m.%Y', $args['item'][$f['name']])?>" class="input" style="width:90px" readonly /><?($errors['place']? ' style="color:red"' : '')?>&nbsp;<input type="button" class="button" style="width:18px" value=" &darr; " onMouseDown="showCalendar(this, 'created_leer', 'created_a', 'date');" />
          <a name="created_a">&nbsp;</a>
        </td>
      </tr>
      <?}?>
    <?}?>
  </table>
  </div>
  <?}?>
<?}?>
<table width="100%" border="0" cellspacing="2" cellpadding="4" class="table">
<tr>
  <td width="30%"></td><td><input type="submit" value="Сохранить"></td>
</tr>
</table>
</div>
</form>

