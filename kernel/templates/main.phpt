<?php 
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
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?=($kernel['doc']['title']=="")?$kernel['title']." - ".$kernel['params']['main']['sitename_ru']:$kernel['doc']['title'].' - '.$kernel['params']['main']['sitename_ru']?></title>
    <meta name="description" content="<?=isset($kernel['doc']['description']) && $kernel['doc']['description'] ? $kernel['doc']['description']:$kernel['params']['main']['description']?>">
    <meta name="keywords" content="<?=isset($kernel['doc']['keywords']) && $kernel['doc']['keywords'] ? $kernel['doc']['keywords']:$kernel['params']['main']['keywords']?>">

</head>
<body>
<a href="/admin/">Перейти в админку</a>
<br /><br /><br />
<fieldset>
    <legend>Авторизация</legend>
    <label>Логин: <input type="text" name="login" /></label>
    <label>Пароль: <input type="password" name="password" /></label>
    <a href="/auth/">Авторизация</a>
    <a href="/registration/">Регистрация</a>
</fieldset>
<br /><br />
<fieldset>
    <legend>Возможности</legend>
    <a href="/sitemap/">Карта сайта</a>
</fieldset>
<?=$content?>


</body>
</html>