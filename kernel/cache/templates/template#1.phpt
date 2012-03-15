<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"
</head>
<body>
<a href="/admin/">Перейти в админку</a>
<? 
   switch($kernel['path'][1]){
      default:
        module("pages/typicle.php");
        break;
   }
?>
</body>
</html>