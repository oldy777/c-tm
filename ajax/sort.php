<?
include_once($_SERVER['DOCUMENT_ROOT']. "/kernel.php");
include_once(KERNEL_DIR. '/kernel.php');
@header("Content-type: text/javascript; charset=windows-1251");
/* @var $q query_mysql */
$q = &$kernel['db']->query();

if($_SERVER['REQUEST_METHOD']=='POST'){
	$id = (int)$_POST['id'];
	$new = (int)$_POST['new'];
        $old = (int)$_POST['old'];
        $table= addslashes($_POST['table']);
        $cat_val= isset($_POST['cat_val']) ? (int)$_POST['cat_val']:'';
        $cat= isset($_POST['cat']) && $_POST['cat'] ? addslashes($_POST['cat']):'';
        $where = '';
        if($cat)
            $where = " AND ".$cat_val." = ".$cat;
	
        if($old==$new) return true;
        
        $q->query("update ".$table." SET pos=$new WHERE id=$id");
        
        if($old>$new)
        {
            $q->query("select * from ".$table." where 1=1 AND pos BETWEEN $new AND $old AND id <> $id ".$where." order by pos");
            $args['items'] = $q->get_allrows();
            
            foreach($args['items'] as $key => $item){
            $i = $item['pos']+1;
                    $q->query("update ".$table." SET pos=$i WHERE id='".$item['id']."'");
            }
        }
        else
        {
            $q->query("select * from ".$table." where 1=1 AND pos BETWEEN $old AND ".($new)." AND id <> $id ".$where." order by pos");
            $args['items'] = $q->get_allrows();
            
            foreach($args['items'] as $key => $item){
            $i = $item['pos']-1;
                    $q->query("update ".$table." SET pos=$i WHERE id='".$item['id']."'");
            }
            
            $q->query("select * from ".$table." where 1=1 AND pos >=$new AND id <> $id  ".$where." order by pos");
            $args['items'] = $q->get_allrows();
            $i = $new;
            foreach($args['items'] as $key => $item){
                    $i++;
                    $q->query("update ".$table." SET pos=$i WHERE id='".$item['id']."'");
            }
        }
			
}
?>
