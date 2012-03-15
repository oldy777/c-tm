<?php
include_once(INCLUDE_DIR. '/editor.php');

$args = array();
$args['editor'] = '';
$result['title'] = "HTML";

if($_SERVER['REQUEST_METHOD']=='POST')
{
  $args['editor'] = trim($_POST['editor']);
  $_SESSION['editor_content'] = $args['editor'];
}
else
{
  $args['editor'] = $_SESSION['editor_content'];
}

?>
<form method="post" action="/admin/?mod=editor">
<?=editor_create('editor', $args['editor'], '100%', '450px', NULL, NULL, false);?>
<div><input type="submit" value="Сохранить!" class="button" style="margin-top:5px; margin-bottom:5px;" /></div>
</form>
<?
return $result;
?>