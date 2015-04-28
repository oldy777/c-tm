<?
list($usec, $sec) = explode(' ', microtime());
error_reporting(0);
$kernel = array('version'=>'0.2', 'begin'=>((float)$usec + (float)$sec), 'mode'=>$kernel['mode']);
unset($usec, $sec);

// define folders
define('KERNEL_DIR', dirname(__FILE__), true);
define('ADMIN_DIR', KERNEL_DIR. '/admin', true);
define('INCLUDE_DIR', KERNEL_DIR. '/include', true);
define('MODULES_DIR', KERNEL_DIR. '/modules', true);
define('TMP_DIR', KERNEL_DIR. '/tmp', true);
define('IMAGE_CACHE_DIR', KERNEL_DIR.'/cache/images',true);
define('HTDOC_CACHE_DIR', KERNEL_DIR.'/cache/htdocs',true);
define('TEMP_CACHE_DIR', KERNEL_DIR.'/cache/templates',true);
define('UPLOAD_IMAGES_PATH', '/upload/images/',true);
define('UPLOAD_FILES_PATH', '/upload/files/',true);

// default include path
set_include_path(INCLUDE_DIR);
ini_set('include_path', INCLUDE_DIR);


// load config
$kernel['config'] = include(KERNEL_DIR. '/config.php');
if(empty($kernel['config'])) { trigger_error('Load kernel configure file failed!', E_USER_ERROR); }

// debug IP`s
if(is_array($config['debug']['addr']) && !empty($config['debug']['addr']))
{
  foreach($config['debug']['addr'] as $i)
  {
    if(preg_match('~'. str_replace('*', '\d{1,3}', preg_quote($i, '~')).'~', $_SERVER['REMOTE_ADDR']))
    {
      $kernel['config']['debug']['use'] = true;
      break;
    }
  }
}
// init debug
if($kernel['config']['debug']['use'])
{
  error_reporting(E_ALL ^ E_NOTICE);
  ini_set('display_errors', true);
  ini_set('display_startup_errors', true);
}

// some check
if(!is_writable(TMP_DIR) || !is_readable(TMP_DIR))
 { trigger_error("Temp directory is not writable/readable!", E_USER_ERROR); }

// database init
include_once(INCLUDE_DIR. '/'. $kernel['config']['db']['lib']);

// construct database object
$classname = $kernel['config']['db']['class'];
if(!class_exists($classname))
 { trigger_error("SQL database class: '${classname}' not exists!", E_USER_ERROR); }
$kernel['db'] = new $classname();
unset($classname);

// db conection
if(!$kernel['db']->connect($kernel['config']['db']['host'], $kernel['config']['db']['user'], $kernel['config']['db']['passwd'], $kernel['config']['db']['name'], $kernel['config']['db']['charset'], $kernel['config']['db']['debug']))
 { trigger_error('Connect SQL database server failed!', E_USER_ERROR); }

// hide some, security
unset($kernel['config']['db']);

// kernel functions
include_once(INCLUDE_DIR. '/common.php');

// custom session handler
if($kernel['mode']!='file' && $kernel['mode']!='normal')
{
  include_once(INCLUDE_DIR. '/session.php');
  session_set_save_handler('sess_open', 'sess_close', 'sess_read', 'sess_write', 'sess_destroy', 'sess_gc');
  session_start();
  $kernel['id_session'] = session_id();
}

$q = $kernel['db']->query();
//$q->query('set character set cp1251_koi8');

// modules params
$kernel['params'] = array();
$q->query('SELECT (CASE WHEN module=\'\' THEN 0 ELSE module END) as module,var,value FROM modules_config ORDER BY module,var');
while($r = $q->get_row()) { $kernel['params'][ $r['module'] ] [ $r['var'] ] = $r['value']; }
$q->free_result();

?>