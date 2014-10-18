<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?=($kernel['doc']['title']=="")?$kernel['title']." - ".$kernel['params']['main']['sitename_ru']:$kernel['doc']['title'].' - '.$kernel['params']['main']['sitename_ru']?></title>
    <meta name="description" content="<?=isset($kernel['doc']['description']) && $kernel['doc']['description'] ? $kernel['doc']['description']:$kernel['params']['main']['description']?>">
    <meta name="keywords" content="<?=isset($kernel['doc']['keywords']) && $kernel['doc']['keywords'] ? $kernel['doc']['keywords']:$kernel['params']['main']['keywords']?>">

</head>
<body>
<a href="/admin/">Перейти в админку</a>
<br /><br /><br />

<div style="margin: 150px auto 20px; color: red; font-size: 70px; text-align: center">ERROR 404</div>
<div style="text-align: center">
    <a href="/">Перейти на главную</a>
</div>

</body>
</html>