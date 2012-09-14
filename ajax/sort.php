<?
include_once($_SERVER['DOCUMENT_ROOT']. "/kernel.php");
include_once(KERNEL_DIR. '/kernel.php');
@header("Content-type: text/javascript; charset=windows-1251");

$q = &$kernel['db']->query();

if($_SERVER['REQUEST_METHOD']=='POST'){
	$id = $_POST['id'];
	$new = $_POST['new'];
        $old = $_POST['old'];
        $table= $_POST['table'];
	
        if($old==$new) return true;
        
        $q->query("update ".$table." SET pos=$new WHERE id=$id");
        
        if($old>$new)
        {
            $q->query("select * from ".$table." where 1=1 AND pos BETWEEN $new AND $old AND id <> $id order by pos");
            $args['items'] = $q->get_allrows();
            
            foreach($args['items'] as $key => $item){
            $i = $item['pos']+1;
                    $q->query("update ".$table." SET pos=$i WHERE id='".$item['id']."'");
            }
        }
        else
        {
            $q->query("select * from ".$table." where 1=1 AND pos BETWEEN $old AND $new AND id <> $id order by pos");
            $args['items'] = $q->get_allrows();
            
            foreach($args['items'] as $key => $item){
            $i = $item['pos']-1;
                    $q->query("update ".$table." SET pos=$i WHERE id='".$item['id']."'");
            }
        }
			
}
?>
