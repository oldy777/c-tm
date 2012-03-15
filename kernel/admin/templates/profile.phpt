<html>
<head>
<title>Профиль</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/admin/style.css" type="text/css" rel="stylesheet" />
<link rel="shortcut icon" href="/admin/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="/admin/favicon.gif" type="image/gif" />
<script language="javascript" type="text/javascript"><!--
function checkform(form)
{
  var msg='';
  if(form.email.value=='') { msg += "*Заполните Email\n"; }
  if(form.name.value=='') { msg += "*Заполните Имя\n"; }
  if(form.passwd.value!='')
  {
    if(form.passwd.value.length < 6) { msg += "*Пароль длиной не менее 6 символов\n"; }
    if(form.passwd.value!=form.passwd2.value) { msg += "*Пароли не совпадают\n"; }
  }
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
	<th>Профиль</th>
	</tr></thead>
	<tr><td class="main">

		<form method="POST" action="/admin/profile.php" onsubmit="return checkform(this)">
		<table width="100%" cellpadding="0" cellspacing="6" class="text">
		<tr>
<!--		<td width="20%" rowspan="3" align="center" valign="top"><a href="#"><img src="/images/icon_help.gif" width="28" height="28" alt="Вызвать справку"></a><br /><a href="#" class="textsml">помощь</a></td>-->
		<td width="20%" align="right">Логин:</td>
		<td width="80%"><input type="text" value="<?=htmlspecialchars($args['login'])?>" readonly style="width:100%" /></td>
		</tr>
		<tr>
		<td align="right" class="<?=($errors['email']? 'error' : '')?>">Email:</td>
		<td><input type="text" name="email" value="<?=htmlspecialchars($args['email'])?>" style="width:100%"></td>
		</tr>
		<tr>
		<td align="right" class="<?=($errors['name']? 'error' : '')?>">Имя:</td>
		<td><input type="text" name="name" value="<?=htmlspecialchars($args['name'])?>" style="width:100%"></td>
		</tr>
		<tr>
		<td align="right" class="<?=($errors['passwd']? 'error' : '')?>">Пароль:</td>
		<td><input type="password" name="passwd" value="" style="width:100%"></td>
		</tr>
		<tr>
		<td align="right" class="<?=($errors['passwd2']? 'error' : '')?>">Еще&nbsp;пароль:</td>
		<td><input type="password" name="passwd2" value="" style="width:100%"></td>
		</tr>
		<tr><td colspan="2" align="center"><input type="submit" value="Сохранить!" class="button" /></td></tr>
		</table>
		</form>

	</td></tr>
	</table>

	<div class="textsml" style="margin-top:4px">
	<a href="/admin/">Управление сайтом</a> | <a href="/">На стартовую</a>
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