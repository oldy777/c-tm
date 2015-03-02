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
  <?}else{?>
  <tr>
    <th width="30%" align="right"><?=$f['title']?>:</th>
    <td width="70%">
        <?=  ValuesFnc::makeFormValues($f, $args); ?>
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
 
