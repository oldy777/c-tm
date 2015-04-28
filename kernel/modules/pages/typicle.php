<?
/* @var $q query_mysql */
$q = $kernel['db']->query();
$args = array();
$errors = array();
$template = '';


$template="typicle.page.phpt";

if($template!='') { template(dirname(__FILE__). '/templates/'. $template, $args, $errors); }

return $result;

?>