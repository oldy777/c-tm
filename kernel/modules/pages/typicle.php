<?
$q = &$kernel['db']->query();
$args = array();
$errors = array();
$template = '';


$q->query("select id from ptree where fullpath='/".$path."/' limit 0,1");
$id=$q->get_cell();
//print_r($id);
//$q->query("select * from ptree where id_parent=".$id." and hidden='0' order by pos");
//$args['menu']=$q->get_allrows();


$template="typicle.page.phpt";

if($template!='') { template(dirname(__FILE__). '/templates/'. $template, $args, $errors); }

return $result;

?>