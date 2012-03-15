<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
  <title>Вход в систему</title>
  <meta http-equiv="content-type" content="text/html; charset=utf-8"  />
  <link href="/admin/adminka.css" rel="stylesheet" type="text/css" />
</head>
<body id="login_body">
<script language="javascript" type="text/javascript">
function checkform(form)
{
  var msg='';
  if(form.login.value=='') { msg += "* Не заполнено поле ЛОГИН\n"; }
  if(form.passwd.value=='') { msg += "* Не заполнено поле ПАРОЛЬ\n"; }
  if(msg!='') { alert("Ошибки:\n"+msg); return false; }
  else { return true; }
}
</script>
    <form method="post" onsubmit="return checkform(this)" id="log" action="">
    <div class="login_container">
        <div class="vhod_v_sist">ВХОД В СИСТЕМУ УПРАВЛЕНИЯ</div>
        <div>Логин</div>
        <input type="text" name="login" value="<?=htmlspecialchars($args['login'])?>" tabindex="1"/>
        <div>Пароль</div>
        <input type="password" name="passwd" value="" tabindex="2" />
        <a href="javascript:document.getElementById('log').submit();" class="vhod_but"></a>
    </div>
    </form>
</body>

</html>