<?php
include_once($_SERVER['DOCUMENT_ROOT']. "/kernel.php");
$kernel['mode'] = 'page';
include_once(KERNEL_DIR. '/kernel.php');// emulate request
include_once(KERNEL_DIR. '/router.php');
?>