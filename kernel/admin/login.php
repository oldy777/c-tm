<?php
if($kernel['id_user'] > 0) { http_redirect("/admin/"); } // already login

$args = array();
$errors = array();
$args['version'] = $kernel['version'];

if($_SERVER['REQUEST_METHOD']=='POST')
{
  $args['login'] = trim($_POST['login']);
  $args['passwd'] = $_POST['passwd'];

  if($args['login']=='') { $errors['login'] = true; }
  if($args['passwd']=='') { $errors['passwd'] = true; }

  if(empty($errors))
  {
    if(login($args['login'], $args['passwd']))
    {
      setcookie('login', $args['login'], time() + $kernel['config']['auth']['login_expire'], '/');
      http_redirect(empty($_SESSION['REQUEST_URI'])? "/admin/" : $_SESSION['REQUEST_URI']);
    }
    else { $errors['auth'] = true; }
  }
}

if(!isset($_POST['login'])) { $args['login'] = $_COOKIE['login']; }

template(ADMIN_DIR. '/templates/login.phpt', $args, $errors);
?>