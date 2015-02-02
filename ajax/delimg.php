<?php
include_once($_SERVER['DOCUMENT_ROOT']. "/kernel.php");
include_once(KERNEL_DIR. '/kernel.php');
/* @var $q query_mysql */
$q = $kernel['db']->query();
  
if(isset($_POST['img']) && isset($_POST['cat']) && isset($_POST['tbl']) && strstr($_POST['tbl'], 'photo') && $kernel['id_user']>0)
{
    $q->format("SELECT id FROM %s WHERE %s = %d AND img = %d", $_POST['tbl'], $_POST['link'], $_POST['cat'],$_POST['img']);
    $id = $q->get_cell();
    if($id)
    {
        $q->format("SELECT id FROM %s WHERE id > %d AND %s = %d",$_POST['tbl'], $id, $_POST['link'], $_POST['cat']);
        $all = $q->get_allrows();
        if($all)
        {
            foreach ($all as $v) {
                $q->format("UPDATE %s SET pos = pos - 1 WHERE id = %d", $_POST['tbl'], $v['id']);
            }
        }
        $q->format("SELECT path FROM modules_images WHERE id = %d", $_POST['img']);
        $path = $q->get_cell();
        unlink($_SERVER['DOCUMENT_ROOT'].'/upload/images/'.$path);
        $q->format("DELETE FROM modules_images WHERE id = %d", $_POST['img']);
         $q->format("DELETE FROM %s WHERE id = %d", $_POST['tbl'], $id);
        echo 1;
    }
}
 else {
     echo 0;
}
?>
