<link href="/js/uploader/fileuploader.css" rel="stylesheet" type="text/css"> 
<script type="text/javascript" src="/js/uploader/fileuploader.js"></script> 
 <script>        
    function createUploader(){            
        var uploader = new qq.FileUploader({
            element: document.getElementById('file-uploader-demo1'),
            action: '/ajax/addimg.php',
            params: {id:<?=$_GET['id']?>},
            allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
            sizeLimit: 0, // max size   
            minSizeLimit: 0, // min size
            //onSubmit: function(id, fileName){},
            //onProgress: function(id, fileName, loaded, total){},
            onComplete: function(id, fileName, responseJSON){
                if (responseJSON.success) {
                    var imgUrl = '/getimg.php?w=150&h=150&path=';
                    $('#franchise-photo').prepend('<div class="gal_img">'
                        +' <a class="lightbox" href="/upload/images/'+responseJSON.filename+'" >'
                        +'<img  src="'+imgUrl+responseJSON.filename+'">'
                        +'</a><a class="del_fr_foto" href="#" rel="'+responseJSON.img+'" cat="'+responseJSON.product_cat_id+'"></a>'
                        +'<br></div>');
                    $("a.lightbox").fancybox();
                } else {
                    alert('Файл не загружен');
                }
            },
            //onCancel: function(id, fileName){},

            //messages: {
            // error messages, see qq.FileUploaderBasic for content            
            //},
            //showMessage: function(message){ alert(message); },  
            debug: false
        });           
    }

    $(function() {
        createUploader();

    });
 </script> 
<form action="?mod=<?=$_GET['mod']?><?=isset($_GET['f_id']) && $_GET['f_id'] ? '&f_id='.$_GET['f_id']:''?><?=isset($_GET['order']) && $_GET['order'] ? '&order='.$_GET['order'].'&type='.$_GET['type']:''?><?=isset($_GET['page']) && $_GET['page'] ? '&page='.$_GET['page']:''?>&act=editpr&id=<?=$_GET['id']?>" method="post" enctype="multipart/form-data">
  <table width="100%" border="0" cellspacing="2" cellpadding="4" class="table">
<?foreach($args['mod_fields2'] as $f){?>
  <?if($f['type']=="varchar"){?>
  <tr>
    <th width="30%" align="right"><?=$f['title']?>:</th>
    <td width="70%"><input type="text" name="<?=$f['name']?>" style="width:100%;" value="<?=htmlspecialchars($args['item'][$f['name']])?>"/></td>
  </tr>
  <?}?>
  <?if($f['type']=="text"){?>
  <tr>
    <th width="30%" align="right"><?=$f['title']?>:</th>
    <td width="70%"><textarea name="<?=$f['name']?>" style="width:100%;height:100px"><?=htmlspecialchars($args['item'][$f['name']])?></textarea></td>
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
        array( 'Image','Flash','Youtube','Table','HorizontalRule','SpecialChar'),
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
      <img src="/getimg.php?path=<?=$args['item'][$f['name'].'_image']['path']?>&w=300&h=200"/>
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
  <?if($f['type']=="checkbox"){?>
  <tr>
    <th width="30%" align="right"><?=$f['title']?>:</th>
    <td width="70%">
        <input type="checkbox" value="1" name="<?=$f['name']?>" <?=$args['item'][$f['name']] ? 'checked="true"':''?> />
    </td>
  </tr>
  <?}?>
  <?if($f['type']=="option_struct"){?>
  <tr>
    <th width="30%" align="right"><?=$f['title']?>:</th>
    <td width="70%">
      <select name="<?=$f['name']?>" style="width:100%;" >
          <option value="<?=$args['item'][$f['name']]?>">Не выбрано</option>
      <?foreach($args[$f['name']][0] as $v=>$o){?>
        <option value="<?=$o['id']?>"<?=($args['item'][$f['name']]==$o['id'])?' selected':''?>><?=$o['title']?></option>
        <?if(isset($args[$f['name']][$o['id']])){?>
            <?foreach($args[$f['name']][$o['id']] as $o2){?>
            <option value="<?=$o2['id']?>"<?=($args['item'][$f['name']]==$o2['id'])?' selected':''?>>-- <?=$o2['title']?></option>
            <?if(isset($args[$f['name']][$o2['id']])){?>
                <?foreach($args[$f['name']][$o2['id']] as $o3){?>
                <option value="<?=$o3['id']?>"<?=($args['item'][$f['name']]==$o3['id'])?' selected':''?>>---- <?=$o3['title']?></option>
                <?if(isset($args[$f['name']][$o3['id']])){?>
                    <?foreach($args[$f['name']][$o3['id']] as $o4){?>
                    <option value="<?=$o4['id']?>"<?=($args['item'][$f['name']]==$o4['id'])?' selected':''?>>----- <?=$o4['title']?></option>
                    <?}?>
                <?}?>
                <?}?>
            <?}?>
            <?}?>
        <?}?>
      <?}?>
      </select>
    </td>
  </tr>
  <?}?>
    <?if($f['type']=="date"){?>
  <tr>
    <th width="30%" align="right"><?=$f['title']?>:</th>
    <td width="70%">
        <input type="text" name="<?=$f['name']?>" id="datepicker" value="<?=strftime('%d.%m.%Y', $args['item'][$f['name']])?>" class="input" style="width:90px" readonly />
    </td>
  </tr>
  <?}?>
<?}?>
  <tr>
    <td></td><td><input type="submit" value="Сохранить" class="red"></td>
  </tr>
  </table>
</form>
 
 
<div class="sub_fields" style="font-size: 14px; text-align: center;margin: 10px 0 5px;"><small><b>Для загрузки картинок нажмите кнопку "Загрузить файл" или перетащите на нее выбранные фотографии</b></small></div>
<fieldset class="sub_fields">
    <legend>Фото товара</legend>
    <div id="file-uploader-demo1"></div>
    <div id="franchise-photo">
        <?foreach($args['items_img'] as $v){?>
        <div class="gal_img">
            <a class="lightbox" rel="group" href="/upload/images/<?=$v['path']?>" >
                <img  src="/getimg.php?w=150&h=150&path=<?=$v['path']?>">
            </a><a class="del_fr_foto" href="#" rel="<?=$v['img']?>" cat="<?=$v['catalog_items_id']?>"></a>
            <br>
        </div>
        <?}?>
        <div class="clear"></div>
    </div>
</fieldset>

<script>
$(document).ready(function(){
   $('.samecats button').click(function(){
       var id = $(this).parent().children('select').val();
       if(id)
       {
           $.post('/admin/?mod=<?=$_GET['mod']?>&f_id=<?=$_GET['f_id']?>&act=getsamecat&id=<?=$_GET['id']?>', {id:id}, function(data){
               if(data.sux == 1)
               {
                   var str = '<tr>';
                   str += '<td style="text-align: center;">'+data.id+'</td>';
                   str += '<td>'+data.title+'</td>';
                   str += '<td style="text-align: center;"><a href="#" rel="'+data.id+'" class="samedel"><img class="btn_del" src="/admin/images/icon_del.gif" width="17" height="17" border="0" /></a></td>';
                   $('.samecats table tbody').append(str);
               }
               else
                   alert(data.msg);
           },'json');
       }
   }); 
   
   $('.samedel').click(function(){
        var id = $(this).attr('rel');
        var obj = $(this);
        $.post('/admin/?mod=<?=$_GET['mod']?>&f_id=<?=$_GET['f_id']?>&act=delsamecat&id=<?=$_GET['id']?>', {id:id}, function(data){
            if(data.sux == 1)
            {
                obj.parent().parent().remove();
            }
            else
                   alert(data.msg);
        },'json');
        return false;
   });
});
</script>
<style>
    .firstcat {
        font-weight: bold;
    }
</style>
 
