<?
$q = &$kernel['db']->query();
$args = array();
$errors = array();
$template = '';



$q->query("SELECT * FROM ptree WHERE id<>9 AND id_parent<>9 AND hidden = 0  ORDER by pos");

$tmp = $q->get_allrows();

foreach ($tmp as $v)
{
    $args['items'][$v['id_parent']][]=$v;
}

$template="sitemap.phpt";

if($template!='') { template(dirname(__FILE__). '/templates/'. $template, $args, $errors); }

return $result;

?>