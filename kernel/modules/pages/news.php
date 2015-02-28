<?
include_once(INCLUDE_DIR. '/textproc.php');
/* @var $q query_mysql */
$q = &$kernel['db']->query();
$table = 'news';
$args = array();
$errors = array();
$template = '';
$args['page'] = 1;
$where = '';
$num = 10;


foreach ($kernel['path'] as $k => $p) {
    if (substr($kernel['path'][$k], 0, 3) == "id-") {
        $args['id'] = substr($kernel['path'][$k], 3, strlen($kernel['path'][$k]));
    }
    if (substr($kernel['path'][$k], 0, 5) == "page-") {
        $args['page'] = substr($kernel['path'][$k], 5, strlen($kernel['path'][$k]));
    }
}

//Предпросмотр, данные передаются из админки
if($args['id'] == 'preview' && isset($_POST))
{

    $args['item']['title'] = $_POST['title'];
    $args['item']['text'] = $_POST['text'];
    $template = "news_item.phpt";
}
else
    if (isset($args['id']) && $args['id']) {

        $args['item'] = $q->z_getRowById($table, $args['id']);
        
        $kernel['title'] = $args['item']['title'];
        $kernel['doc']['description'] = $args['item']['description'];
        $kernel['doc']['keywords'] =  $args['item']['keywords'];

        $template = "news_item.phpt";
    } else {
            $tmp = $q->z_get_allRowsWithPager($table, $args['page'], $num);
            $args['items'] = $tmp['items'];
            $args['pages'] = $tmp['pages'];
            
            $template = "news.phpt";
    }

if ($template != '') {
    template(dirname(__FILE__) . '/templates/' . $template, $args, $errors);
}

return $result;
?>