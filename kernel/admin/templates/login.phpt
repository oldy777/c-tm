<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
  <title>Вход в систему</title>
  <meta http-equiv="content-type" content="text/html; charset=utf-8"  />
  <link href="/admin/login.css" rel="stylesheet" type="text/css" />

<meta name='robots' content='noindex,nofollow' />
<?if(isset($_POST['login'])){?>
<script type="text/javascript">
    addLoadEvent = function(func){if(typeof jQuery!="undefined")jQuery(document).ready(func);else if(typeof wpOnload!='function'){wpOnload=func;}else{var oldonload=wpOnload;wpOnload=function(){oldonload();func();}}};
    function s(id,pos){g(id).left=pos+'px';}
    function g(id){return document.getElementById(id).style;}
    function shake(id,a,d){c=a.shift();s(id,c);if(a.length>0){setTimeout(function(){shake(id,a,d);},d);}else{try{g(id).position='static';wp_attempt_focus();}catch(e){}}}
    addLoadEvent(function(){ var p=new Array(15,30,15,0,-15,-30,-15,0);p=p.concat(p.concat(p));var i=document.forms[0].id;g(i).position='relative';shake(i,p,20);});
</script>
<?}?>
</head>
<body class="login">
    <div id="login"><h1><a href="http://c-tm.ru/" target="_blank" title="Creative team">Creative team</a></h1>
        <?if(isset($_POST['login'])){?>
        <div id="login_error">	<strong>ОШИБКА</strong>: Неверное имя пользователя или пароль. <br />
        </div>
        <?}?>
            
        <form name="loginform" id="log" method="post" onsubmit="return checkform(this)">
            <p>
                <label for="user_login">Имя пользователя<br />
                    <input type="text" name="login" id="user_login" class="input" value="<?=htmlspecialchars($args['login'])?>" size="20" tabindex="1" /></label>
            </p>
            <p>
                <label for="user_pass">Пароль<br />
                    <input type="password" name="passwd" id="user_pass" class="input" value="" size="20" tabindex="2" /></label>
            </p>
            <!--<p class="forgetmenot"><label for="rememberme"><input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="90" /> Запомнить меня</label></p>-->
            <p class="submit">
                <input type="submit" name="wp-submit" id="wp-submit" class="button-primary" value="Войти" tabindex="100" />
                <input type="hidden" name="redirect_to" value="/admin/" />
                <input type="hidden" name="testcookie" value="1" />
            </p>
        </form>

        <script type="text/javascript">
            function wp_attempt_focus(){
                setTimeout( function(){ try{
                        d = document.getElementById('user_login');
                        d.focus();
                        d.select();
                    } catch(e){}
                }, 200);
            }
            
            if(typeof wpOnload=='function')wpOnload();
        </script>
            
    </div>
        
        
    <div class="clear"></div>
</body>

</html>