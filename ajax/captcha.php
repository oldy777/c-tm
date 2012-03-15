<?php

session_start();

/* this compare captcha's number from POST and SESSION */
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['captcha']) && $_POST['captcha'] == $_SESSION['captcha'])
	{
		echo "Превед медвед! как жись?"; /* YOUR CODE GOES HERE */
		unset($_SESSION['captcha']); /* this line makes session free, we recommend you to keep it */
	}
elseif($_SERVER['REQUEST_METHOD'] == "POST" && !isset($_POST['captcha']))
	{
		echo "Неее, ты все таки робот!, вали отсюда!!";
	}
else
	{
		$rand = rand(0,4);
		$_SESSION['captcha'] = $rand;
		echo $rand;
	}
?>



