<?php
// garbage collection
function log_access_gc()
{
  global $kernel;
  if((rand() % $kernel['config']['log_access']['gc_divisor'])==($kernel['config']['log_access']['gc_probability'] - 1))
  {
    $q = $kernel['db']->query();
    $q->format("DELETE FROM log_access WHERE created < '%d'", time() - $kernel['config']['log_access']['gc_maxlifetime']);
  }
}

// logger
function log_access_shutdown()
{
  flush();
  global $kernel;
  $q = $kernel['db']->query();
  list($usec, $sec) = explode(" ", microtime());
  $exctime = (((float)$usec + (float)$sec)-$kernel['begin']);
  $memory = (function_exists('memory_get_usage')? memory_get_usage() : 0);
  $q->format("INSERT INTO log_access SET id='%d',host='%s',uri='%s',code='%d',protocol='%s',method='%s',remote_addr='%s',user_agent='%s',referer='%s',length='%d',memory='%d',queryes='%d',exctime='%s',created='%d'",
                 $kernel['db']->next_id('log_access'), $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI'], $kernel['http_code'], $_SERVER['SERVER_PROTOCOL'], $_SERVER['REQUEST_METHOD'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'], $_SERVER['HTTP_REFERER'], $kernel['content_length'], $memory, $kernel['db']->queryes, $exctime, time());
  if($kernel['config']['log_access']['gc']) { log_access_gc(); }
}

// log system
function log_system($message)
{
  global $kernel;
  // send to admin mail
  if($kernel['config']['mailadmin'] != '')
  {
    $head = "MIME-Version: 1.0\r\n";
    $head.= "Content-type: text/html; charset=windows-1251\r\n";
    $head.= "To: ".$kernel['config']['mailadmin']. "\r\n";
    $head.= "From: ". $kernel['config']['mailrobot']. "\r\n";
    $subj = "Информационное сообщение сайта ".$_SERVER['HTTP_HOST'];
    $msg = "<html><body><p>".$message."</p><hr />С уважением почтовый демон sendmail.</body></html>";
    mail($kernel['config']['mailadmin'], $subj, $msg, $head);
  }
  // write to DB log...
  // ...............
  // ...............
  // ...............
  // garbage collection
  // ...............
  // ...............
  // ...............
}

?>
