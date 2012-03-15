<script language="javascript" type="text/javascript"><!--
function userscheckadd(form)
{
  var msg='';
  if(form.login.value=='') { msg += '* Не заполнено поле "Логин"\n'; }
  if(form.email.value=='') { msg += '* Не заполнено поле "Email"\n'; }
  if(form.name.value=='') { msg += '* Не заполнено поле "Имя"\n'; }
  if(form.passwd.value.length < 6) { msg += "* Пароль длиной менее 6 символов\n"; }
  if(form.passwd.value!=form.passwd2.value) { msg += "* Пароли не совпадают\n"; }
  if(msg!='') { alert("Произошли ошибки:\n"+msg); return false; }
  else { return true; }
}
function usersoncancel()
{
  document.location='/admin/?mod=users';
}
//--></script>
<?if(!empty($errors)){?>
<div class="error">Произошли ошибки:
<?if($errors['login']){?>&bull; Не заполнено поле &laquo;Логин&raquo;<br /><?}?>
<?if($errors['email']){?>&bull; Формат &laquo;Email&raquo; не коректный;<br /><?}?>
<?if($errors['name']){?>&bull; Не заполнено поле &laquo;Имя&raquo;<br /><?}?>
<?if($errors['passwd']){?>&bull; Пароль длиной менее 6 символов;<br /><?}?>
<?if($errors['passwd2']){?>&bull; Пароли не совпадают;<br /><?}?>
<?if($errors['unique']){?>&bull; Пользователь с таким логином уже существует;<br /><?}?>
</div>
<?}?>
<form id="usersadd" name="usersadd" method="post" action="/admin/?mod=users&amp;act=add" onsubmit="return (userscheckadd(this) && submitonce(this))">
<table width="450" border="0" cellspacing="2" cellpadding="4" class="table">
<tr>
  <th width="30%" align="right" class="<?=($errors['login']? 'error' : '')?>">Логин:</th>
  <td width="70%"><input type="text" name="login" value="<?=htmlspecialchars($args['login'])?>" style="width:100%" maxlength="128" /></td>
</tr>
<tr>
  <th align="right" class="<?=($errors['email']? 'error' : '')?>">Email:</th>
  <td><input type="text" name="email" value="<?=htmlspecialchars($args['email'])?>" style="width:100%" maxlength="255" /></td>
</tr>
<tr>
  <th align="right" class="<?=($errors['name']? 'error' : '')?>">Имя:</th>
  <td><input type="text" name="name" value="<?=htmlspecialchars($args['name'])?>" style="width:100%" maxlength="255" /></td>
</tr>
<tr>
  <th align="right" class="<?=($errors['passwd']? 'error' : '')?>">Пароль:</th>
  <td><input type="password" name="passwd" value="" style="width:100%" maxlength="128" /></td>
</tr>
<tr>
  <th align="right" class="<?=($errors['passwd2']? 'error' : '')?>" nowrap="nowrap">Повтор пароля:</th>
  <td><input type="password" name="passwd2" value="" style="width:100%" maxlength="128" /></td>
</tr>
<tr>
  <th align="right" nowrap>Статус:</th>
  <td><input type="checkbox" class="checkbox" name="blocked" id="blocked" value="1"<?=($args['blocked']? ' checked="checked"' : '')?> /><label for="blocked">заблокирован</label></td>
</tr>
<tr>
  <td class="none">&nbsp;</td>
  <td class="none">
    <input type="submit" value="Сохранить!" class="button" />
    <input type="reset" value="Отменить!" onclick="usersoncancel()" class="button" />
  </td>
</tr>
</table>
</form>