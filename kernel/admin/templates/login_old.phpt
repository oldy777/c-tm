
<html>
<head>
<title>Вход в систему</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/admin/style.css" type="text/css" rel="stylesheet" />
<link rel="shortcut icon" href="/admin/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="/admin/favicon.gif" type="image/gif" />
<script language="javascript" type="text/javascript"><!--
function checkform(form)
{
  var msg='';
  if(form.login.value=='') { msg += "* Не заполнено поле ЛОГИН\n"; }
  if(form.passwd.value=='') { msg += "* Не заполнено поле ПАРОЛЬ\n"; }
  if(msg!='') { alert("Ошибки:\n"+msg); return false; }
  else { return true; }
}
//--></script>
</head>
<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0"
marginwidth="0" marginheight="0" bgcolor="#ECF7FF"
style="background:url(/admin/images/dialog_bg2.gif) repeat-x
center center #ECF7FF">

<!-- form -->
<div style="position:absolute; z-index:1; width:100%; top:65%;">

	<div align="center">

	<form method="POST" onsubmit="return checkform(this)">
	<table width="290" cellpadding="0" cellspacing="6" class="text">
<?if($errors['auth']){?>
	<tr>
	<td>&nbsp;</td>
	<td align="center" class="error" nowrap>Ошибка авторизации</td>
	</tr>
<?}?>
	<tr>
	<td width="30%" align="right" class="<?=($errors['login']? 'error' : '')?>">Логин:</td>
	<td width="70%"><input type="text" name="login" value="<?=htmlspecialchars($args['login'])?>" tabindex="1" style="width:100%" /></td>
	</tr>
	<tr>
	<td align="right" class="<?=($errors['passwd']? 'error' : '')?>">Пароль:</td>
	<td><input type="password" name="passwd" value="" tabindex="2" style="width:100%" /></td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td align="center"><input type="submit" value="Войти!" tabindex="3" class="button" /></td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td align="center" nowrap>
	<a href="/admin/forget.php">Забыли пароль?</a>&nbsp;|&nbsp;<a href="/">На стартовую</a>
	</td>
	</tr>
	</table>
	</form>

	</div>

</div>

<!-- logo -->
<table width="100%" height="100%" cellspacing="0" cellpadding="0">
<tr><td align="center" valign="middle"></td></tr>
<td align="center" valign="bottom" class="copy" nowrap>&copy;&nbsp;&nbsp;<a href="http://www.c-tm.ru/">Creative Team</a>, 2008</td>
</table>

</body>
</html>