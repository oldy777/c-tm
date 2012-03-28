<? 
   $content = '';
   switch($kernel['path'][1]){
      default:
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