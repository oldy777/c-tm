<?php
include_once($_SERVER['DOCUMENT_ROOT']. "/kernel.php");
include_once(KERNEL_DIR. '/kernel.php');
/* @var $q query_mysql */
$q = &$kernel['db']->query();
 
if(isset($_POST['ids']) && isset($_POST['tbl']) && strstr($_POST['tbl'], 'photo') && $kernel['id_user']>0)
{
    $ids =$_POST['ids'];
    $pos = 0;
    foreach ($ids as $v) { 
        if((int)$v=='') continue;
        $q->query("UPDATE ".$_POST['tbl']." SET pos = ".$pos." WHERE id = ".(int)$v);
        
        $pos++;
    }
    echo 1;
}
 else {
     echo 0;
}
?>
