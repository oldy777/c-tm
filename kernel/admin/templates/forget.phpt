<html>
<head>
<title>Забыли пароль?</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/admin/style.css" type="text/css" rel="stylesheet" />
<link rel="shortcut icon" href="/admin/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="/admin/favicon.gif" type="image/gif" />
<script language="javascript" type="text/javascript"><!--
function checkform(form)
{
  var msg='';
  if(form.login.value=='') { msg += "* Не заполнено поле ЛОГИН\n"; }
  if(form.email.value=='') { msg += "* Не заполнено поле E-MAIL\n"; }
  if(msg!='') { alert("Ошибки:\n"+msg); return false; }
  else { return true; }
}
//--></script>
</head>
<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0">

<table width="100%" height="100%" cellpadding="0" cellspacing="0">

<!-- head -->
<tr height="90" style="background:url(/admin/images/head_bg1.gif) repeat-x top">
<td width="3%"><img src="/admin/images/none.gif" width="5" height="1" alt=""></td>
<td width="94%">&nbsp;</td>
<td width="3%"><img src="/admin/images/none.gif" width="5" height="1" alt=""></td>
</tr>

<!-- body -->
<tr>
<td><img src="/admin/images/none.gif" width="5" height="1" alt=""></td>
<td align="center" valign="middle">

	<table width="280" cellpadding="4" cellspacing="2" class="dialog">
	<thead><tr>
	<th>Забыли пароль?</th>
	</tr></thead>
	<tr><td class="main">

		<form action="/admin/forget.php" method="post" onsubmit="return checkform(this)">
		<table width="100%" cellpadding="0" cellspacing="6" class="text">

<?if($errors['user']){?>
		<tr>
		<td>&nbsp;</td>
		<td class="error">Пользователь не найден</td>
		</tr>
<?}?>
<?if($errors['blocked']){?>
		<tr>
		<td>&nbsp;</td>
		<td class="error">Пользователь заблокирован</td>
<?}?>
		<tr>
		<td width="20%" align="right" class="<?=($errors['login']? 'error' : '')?>">Логин:</td>
		<td width="80%"><input type="text" name="login" value="<?=htmlspecialchars($args['login'])?>" style="width:100%" /></td>
		</tr>
		<tr>
		<td align="right" class="<?=($errors['email']? 'error' : '')?>">E-mail:</td>
		<td><input type="text" name="email" value="<?=htmlspecialchars($args['email'])?>" style="width:100%"></td>
		</tr>

		<tr><td colspan="2" align="center"><input type="submit" value="Прислать!" class="button" /></td></tr>
		</table>
		</form>

	</td></tr>
	</table>

	<div class="textsml" style="margin-top:4px">
	<a href="/admin/login.php">Войти в сиcтему</a> | <a href="/">На стартовую</a>
	</div>

</td>
<td><img src="/admin/images/none.gif" width="5" height="1" alt="" /></td>
</tr>

<!-- foot -->
<tr height="90" style="background:url(/admin/images/foot_bg.gif) repeat-x bottom">
<td><img src="/admin/images/none.gif" width="5" height="1" alt="" /></td>
<td align="center" valign="bottom" class="copy" nowrap>Slimpo CMS v<?=htmlspecialchars($args['version'])?>&nbsp;&nbsp;&copy;&nbsp;&nbsp;<a href="http://www.diz24.ru/">Студия Павла Лебедева</a>, 2007</td>
<td><img src="/admin/images/none.gif" width="5" height="1" alt="" /></td>
</tr>

</table>

</body>
</html>
