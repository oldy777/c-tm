<?php
header("HTTP/1.1 200 OK");
include(INCLUDE_DIR. '/httree.php');
$args = array();
set_include_path(INCLUDE_DIR);
ini_set('include_path', INCLUDE_DIR);
$kernel['http_code'] = 200;
$request_uri = $_SERVER['REQUEST_URI'];

// добавляем первый слеш
if($request_uri[0]!='/') { $request_uri = '/'. $request_uri; }

// парсим урл
$url = parse_url(rawurldecode($request_uri));

// заменяем обратные слешы прямыми
$url['path'] = str_replace('\\', '/', $url['path']);
$url['path'] = str_replace('\.', '', $url['path']);
// заменяем повторяющиеся слешы
$url['path'] = preg_replace('~/{2,}~', '/', $url['path']);

// если запрашивали папку а не файл добавляем в путь индексный файл
$url['path'] = preg_replace('~^\/([a-zA-Z0-9_\/\-]+)\/$~', '/\1/index.html', $url['path']);
if($url['path']=='/') { $url['path'] = '/index.html'; }


// получаеи GET заголовки
$_REQUEST = array_merge($_GET, $_POST, $_COOKIE);


// fix
$_SERVER['PHP_SELF'] = $url['path'];
$PHP_SELF = $_SERVER['PHP_SELF'];
$HTTP_SERVER_VARS['PHP_SELF'] = $PHP_SELF;
@putenv('PHP_SELF='. $PHP_SELF);

// настройка ядра
$kernel['request_uri'] = $request_uri;
$kernel['query_string'] = $url['query'];
$kernel['php_self'] = $_SERVER['PHP_SELF'];

// получаем узел и документ
if(!preg_match('~^([a-zA-Z0-9_\/\-]*/)([a-zA-Z0-9_\-]+)\.([a-z]+)$~', $url['path'], $matches)){
  http_redirect($url['path']."/");
}

$kernel['nodepath'] = $matches[1];
$kernel['docpath'] = $matches[2];
$kernel['docext'] = $matches[3];

$kernel['realpath']=$kernel['nodepath'];
$kernel['path'] = explode("/",$kernel['nodepath']);

$kernel['tree'] = new httree();

/***
* Для того что бы не переделывать переменные для англ. версии
*/
$kernel['lng'] = 'ru';
if(in_array($kernel['path'][1], $kernel['config']['languages']))
{
    $kernel['lng'] = $kernel['path'][1];
    setLanguage($kernel['lng'],$kernel['lng'].'_'. strtoupper($kernel['lng']));
    unset($kernel['path'][1]);

    $tmp = $kernel['path'];
    unset($kernel['path']);
    $kernel['path'] = array();
    foreach ($tmp as $v) {
         $kernel['path'][] = $v;
    }
}

$filepath = $kernel['path'][1] == '' ? 'index':$kernel['path'][1];
if($filepath && file_exists(MODULES_DIR.'/pages/'.$filepath.'.php'))
{
    $path = ($kernel['lng']!='ru'?('/'.$kernel['lng']):'').($kernel['path'][1] == '' ? '/':('/'.$kernel['path'][1].'/'));
    $kernel['node'] = $kernel['tree']->findnode($path);
    
    $args['content'] = module('pages/'.$filepath.'.php', array(), true);
}
else
{
    $kernel['node'] = $kernel['tree']->findnode($kernel['nodepath']);
    if(!$kernel['node'])
    {
        $kernel['http_code'] = 404;
    }
    else
    {
        //проверяем чтобы небыло левых расширении для файлов !!!
        if($kernel['docext']=='html')
        {
            //пытаемся запросить документ из базы
            $kernel['doc'] = $kernel['tree']->getdoc($kernel['docpath'], $kernel['node']['id']);

            //если не нашли выкидываем на 404 страничку (шаблон)
            if(empty($kernel['doc']))
            {
                $kernel['http_code'] = 404;
            }
            else{
                $kernel['doc']['content'] = module("pages/typicle.php", array(), true);
            }
        }
        else // 404 binary
        {
            $kernel['http_code'] = 404;
            $kernel['doc'] = array();
        }

    }
}
//если запрашиваемых страничек нету выдаем соответствующий заголовок
if($kernel['http_code']==404){

      $kernel['node'] = $kernel['tree']->getroot();
      $kernel['nodepath'] = '/';
      $kernel['docpath'] = '404';
      $kernel['docext'] = 'html';
      $kernel['doc'] = $kernel['tree']->getdoc($kernel['docpath'], $kernel['node']['id']);
      $kernel['doc']['content'] = module("pages/404.php", array(), true);
      header("HTTP/1.0 404 Not Found");
}


// заголовки страницы
$kernel['title'] = strval($kernel['doc']['title']==''? $kernel['node']['title'] : $kernel['doc']['title']);
$kernel['id_maket'] = intval($kernel['doc']['id_maket']=='' ? $kernel['node']['id_maket'] : $kernel['doc']['id_maket']);
$kernel['keywords'] = strval($kernel['doc']['keywords']=='' ? $kernel['node']['keywords'] : $kernel['doc']['keywords']);
$kernel['description'] = strval($kernel['doc']['description']==''? $kernel['node']['description'] : $kernel['doc']['description']);
clearstatcache();

ob_start();

if(isset($kernel['doc']['content']))
{
    //проверяем на наличие "временного файла" и обновляем по необходимости
    $mtime = NULL;
    $path = HTDOC_CACHE_DIR. '/htdoc#'. urlencode($kernel['node']['fullpath'].$kernel['doc']['path']). '.html#'. $kernel['doc']['id'] .'.phpt';
    if(file_exists($path)) { $mtime = filemtime($path); }
    if($mtime && $mtime==$kernel['doc']['updated']) { } // если время совпадает то все норм
    elseif(!$mtime || $kernel['doc']['updated'] > $mtime){ // иначе пишем из базы в файл
        if(lockwrite($path, $kernel['doc']['content'])){
            if($kernel['doc']['updated']){
                touch($path, $kernel['doc']['updated']);
            }
        }
    }
}

$kernel['etag'] = md5(ob_get_contents());
header('ETag: "'. $kernel['etag']. '" ');

//если тип страницы пхп то просто выводим ее с выполнением
if($kernel['doc']['eval']) 
{
    $kernel['content'] = template($path,array(),array(),1);
}
else
{
    if(!isset($kernel['doc']['content']))
    {
        $kernel['content'] = $args['content'];
    }
    else
    {
        //иначе считываем из файла
        $kernel['content'] = join('',file($path));
    }
}

// макет
if($kernel['id_maket'] > 0) { maket($kernel['id_maket']); }
else { echo $kernel['content']; }

if($kernel['config']['debug']['use']){
  list($usec, $sec) = explode(" ", microtime());
  $exctime = (($usec + $sec)-$kernel['begin']);
  $memory = (function_exists('memory_get_usage')? memory_get_usage() : 0);
  echo '<script type="text/javascript">function showQueries(){if(document.getElementById(\'queries\').style.display==\'none\'){document.getElementById(\'queries\').style.display=\'block\';}else{document.getElementById(\'queries\').style.display=\'none\';}}</script><div style="position:absolute;background:black;color:white;top:0px;left:0px;font-size:11px;z-index:999;">Memory: '.$memory.'. <a href="javascript:showQueries();" style="color:white;">MySQL Queries</a>: '.$kernel['db']->queries_col.' ('.sizeof($kernel['db']->queries).'). Ext.time: ';
  printf("%f",$exctime);
  echo '</div>';
  echo '<div style="position:absolute;background:black;color:white;top:14px;left:0px;font-size:11px;z-index:999;display:none;" id="queries">';
  foreach($kernel['db']->queries as $x=>$q){echo ($x+1).'. <span'.(($q['cache'])?' style="color:#ccc;"':'').'>'.$q['query'].'</span><br/>';}
  echo '</div>';
}

header('Content-type: text/html; charset=utf-8');
$kernel['content_length'] = ob_get_length();  // content length
header('Content-Length: '. $kernel['content_length']);


?>