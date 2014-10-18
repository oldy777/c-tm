<?
$q = &$kernel['db']->query();
$args = array();
$errors = array();
$template="registration.phpt";

if(isset($_POST['keystring']) && $_POST['keystring'] == $_SESSION['captcha_keystring'])
{
    if($_POST['pass'] && $_POST['pass2'] && $_POST['pass']==$_POST['pass2'])
    {
        if($_POST['fio'] && $_POST['email'])
        {
            $q->format("SELECT id FROM accounts WHERE email = %s", $_POST['email']);
            $id = $q->get_cell();
            if(!$id)
            {
                $val = array();
                $val['fio'] = $_POST['fio'];
                $val['email'] = $_POST['email'];
                $val['city'] = $_POST['city'];
                $val['phone'] = $_POST['phone'];
                $val['passwd'] = md5($_POST['pass']);
                $q->format("INSERT INTO accounts SET %s", $val);
                $template="regok.phpt";
            }
            else
            {
                $errors[] = 'Пользователь с таким E-mail уже зарегистрирован';
            }
        }
        else
        {
            if(!$_POST['fio'])
            {
                $errors[] = 'Заполните поле ФИО';
            }
            if(!$_POST['email'])
            {
                $errors[] = 'Заполните поле E-mail';
            }
        }
    }
    else
    {
         $errors[] = 'Пароли не совпадают';
    }
}
elseif (isset($_POST['keystring'] )) {
    $errors[] = 'Не правильно введена капча';
}



if($template!='') { template(dirname(__FILE__). '/templates/'. $template, $args, $errors); }

return $result;

?>