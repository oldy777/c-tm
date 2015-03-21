<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?=$kernel['title']." - ".$kernel['params']['main']['sitename_ru']?></title>
    <meta name="description" content="<?=$kernel['description'] ? $kernel['description']:$kernel['params']['main']['description']?>">
    <meta name="keywords" content="<?=$kernel['keywords'] ? $kernel['keywords']:$kernel['params']['main']['keywords']?>">

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
<?=$kernel['content']?>


</body>
</html>