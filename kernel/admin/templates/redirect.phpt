<html>
<head>
<meta http-equiv="refresh" content="<?=(int)$args['pause']?>; url=<?=htmlspecialchars($args['url'])?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/admin/style.css" type="text/css" rel="stylesheet" />
</head>
<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
<div align="center">
<?=nl2br(htmlspecialchars($args['msg']))?>
<br /><br />
<b>Пожалуйста, подожите...</b><br />
Если страница не перегрузилась &#151 нажмите на <a href="<?=htmlspecialchars($args['url'])?>">ссылку</a>.
</div>
</body>
</html>
