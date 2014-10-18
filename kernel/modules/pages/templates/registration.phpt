<h1><?=_('Регистрация')?></h1>
<?if($errors){?>
<ul style="color: red">
    <?foreach($errors as $v){?>
    <li><?=$v?></li>
    <?}?>    
</ul>
<?}?>
<fieldset>
    <legend>Введите ваши данные</legend>
    <form method="post">
    <label>ФИО: <input type="text" name="fio" value="<?=(isset($_POST['fio']) ? $_POST['fio']:'')?>" /></label><br />
    <label>Город: <input type="text" name="city" value="<?=(isset($_POST['city']) ? $_POST['city']:'')?>" /></label><br />
    <label>Телефон: <input type="text" name="phone" value="<?=(isset($_POST['phone']) ? $_POST['phone']:'')?>" /></label><br />
    <label>E-mail: <input type="text" name="email" autocomplete="off" value="<?=(isset($_POST['email']) ? $_POST['email']:'')?>" /></label><br /><br />
    
    <label>Пароль: <input type="password" name="pass" value="" autocomplete="off" /></label><br />
    <label>Пароль проверка: <input type="password" name="pass2" value="" autocomplete="off" /></label><br /><br />
    
    <label>Проверочный код: 
        <input type="text"  name="keystring" id="keystring" value="" >
        <div style="width: 165px">
            <img src="/kcaptcha/?<?=time()?>" id="captcha" style="vertical-align: middle">
        <a href="#" onclick="document.getElementById('captcha').src='/kcaptcha/?'+Math.random();
            document.getElementById('keystring').focus();return false;" 
            style="">Обновить картинку</a>
        </div>
    </label><br />
    <input type="submit" value="Зарегистрироваться" />
    </form>
</fieldset>