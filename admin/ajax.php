<?php
include_once($_SERVER['DOCUMENT_ROOT']. "/kernel.php");
include_once(KERNEL_DIR. '/kernel.php');
/* @var $q query_mysql */
$q = &$kernel['db']->query();

if($_SERVER['REQUEST_METHOD']=='POST'){
    switch ($_POST['act']) {
        case 'bg':
            print_r(111);
            change_bg($_POST, $q, $kernel);
            break;

        default:
            break;
    }
}



function change_bg($post, $q, $kernel){
    $q->format("UPDATE users SET bg = '%s' WHERE id = %d",$post['bg'],$kernel['id_user']);
}
?>
