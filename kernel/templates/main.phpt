<? 
   $content = '';
   switch($kernel['path'][1]){
      default:
        if($kernel['path'][1] == '') $kernel['path'][1] = 'index';
        if($kernel['path'][1] && file_exists($_SERVER['DOCUMENT_ROOT'].'/kernel/modules/pages/'.$kernel['path'][1].'.php'))
                $content = module('pages/'.$kernel['path'][1].'.php', array(), true);
        else
                $content = module("pages/typicle.php", array(), true);
        break;
   }
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"
</head>
<body>
<a href="/admin/">Перейти в админку</a>
<?=$content?>
</body>
</html>