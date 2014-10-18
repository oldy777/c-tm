<?php

include_once($_SERVER['DOCUMENT_ROOT']. "/kernel.php");
$kernel['mode'] = 'page';
include_once(KERNEL_DIR. '/kernel.php');

include('kcaptcha.php');

//session_start();

$captcha = new KCAPTCHA();

if($_REQUEST[session_name()]){
	$_SESSION['captcha_keystring'] = $captcha->getKeyString();
}


?>