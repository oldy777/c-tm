<?php

// dummy-function not used
function sess_open($savepath, $name) { return true; }
// dummy-function not used
function sess_close() { return true; }

// read session data
function sess_read($id)
{
  global $kernel;
  $q = &$kernel['db']->query();
  $q->format("SELECT s.*,u.id as userid,u.login as login,u.name as username,u.email as useremail FROM sessions as s LEFT JOIN users as u ON (u.id=s.id_user AND NOT u.blocked) WHERE s.id='%s'", $id);
  $r = $q->get_row();
  $q->free_result();
  if(!empty($r) && (($kernel['config']['session']['remote_addr'] && $_SERVER["REMOTE_ADDR"]!=$r['remote_addr']) || ($kernel['config']['session']['user_agent'] && $_SERVER["HTTP_USER_AGENT"]!=$r['user_agent'])))
   { $r = NULL; }
  if(empty($r) || $r['accounts_id'])
  {
     
    $q->format("SELECT s.*,u.id as userid,u.login as login,u.fio as username FROM sessions as s LEFT JOIN accounts as u ON (u.id=s.accounts_id ) WHERE s.id='%s'", $id);  
    $r = $q->get_row();  
    if(empty($r))
    {
        session_regenerate_id();
        $kernel['id_session'] = session_id();
        if(!is_robot())
        {
          $q->format("INSERT INTO sessions SET id='%s',id_user='0',remote_addr='%s',user_agent='%s',storage='',created='%d',updated='%d'",
                     $kernel['id_session'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'], time(), 0);
        }
        $kernel['id_user'] = 0;
        $kernel['login']   = '';
        $storage = NULL;
    }
    else
    {
        $kernel['login']     = $r['login'];
        $kernel['accounts_id']   = $r['userid'];
        $kernel['username']  = $r['username'];
        $kernel['id_user']   = $r['id_user'];
        $storage = $r['storage'];
    }
  }
  else
  {
    $kernel['login']     = $r['login'];
    $kernel['id_user']   = $r['userid'];
    $kernel['username']  = $r['username'];
    $kernel['useremail'] = $r['useremail'];
    $storage = $r['storage'];
  }
  return $storage;
}

// write session data
function sess_write($id, $storage)
{
  global $kernel;
  $q = &$kernel['db']->query();
  $q->format("UPDATE sessions SET updated='%d', storage='%s' WHERE id='%s'", time(), $storage, $id);
  return true;
}

// destroy session
function sess_destroy($id, $id_user=0)
{
  global $kernel;
  $q = &$kernel['db']->query();
  $q->format("DELETE FROM sessions WHERE id='%s'", $id);
  if($id_user > 0)
  {
    if($id_user==$kernel['id_user']) { $kernel['id_user'] = 0; $kernel['login'] = ''; }
    $q->format("UPDATE users SET updated='%d' WHERE id='%d'", time(), $id_user);
  }
  return true;
}

// garbage collection
function sess_gc($expire)
{
  global $kernel;
  $q = &$kernel['db']->query();
  $q->format("SELECT id,id_user,updated,created FROM sessions WHERE updated < '%d'", time() - $expire);
  while($r = $q->get_row()) { sess_destroy($r['id'], $r['id_user']); }
  $q->free_result();
  return true;
}

// login
function login($login, $passwd='', $hash=true)
{
  global $kernel;
  $q = &$kernel['db']->query();
  if($hash) { $passwd = md5($passwd); }

  $q->format("SELECT id,login,blocked FROM users WHERE login='%s' AND passwd='%s'", $login, $passwd);
  $r = $q->get_row();
  $q->free_result();
  if(empty($r) || ($r['blocked'] && $r['id']!=1))
   { return false; } // root always active
  $kernel['id_user'] = $r['id'];
  $kernel['login'] = $r['login'];
  $q->format("UPDATE sessions SET id_user='%d' WHERE id='%s'", $kernel['id_user'], $kernel['id_session']);
  return true;
}

// login
function login_acc($login, $passwd='', $hash=true)
{
  global $kernel;
  $q = &$kernel['db']->query();
  if($hash) { $passwd = md5($passwd); }

  $q->format("SELECT id,login,fio FROM accounts WHERE login='%s' AND passwd='%s'", $login, $passwd);
  $r = $q->get_row();
  $q->free_result();
  if(empty($r))
   { return false; } // root always active
  $kernel['accounts_id'] = $r['id'];
  $kernel['login'] = $r['login'];
  $kernel['username'] = $r['fio'];
  $q->format("UPDATE sessions SET accounts_id='%d' WHERE id='%s'", $kernel['accounts_id'], $kernel['id_session']);
  return true;
}

// logout
function logout_acc()
{
  global $kernel;
  $q = &$kernel['db']->query();
  $q->format("UPDATE sessions SET accounts_id=0 WHERE id='%s'", $kernel['id_session']);
  $kernel['accounts_id'] = 0;
  $kernel['login']   = '';
  $kernel['username'] = '';
  return true;
}

// logout
function logout()
{
  global $kernel;
  $q = &$kernel['db']->query();
  $q->format("UPDATE sessions SET id_user=0 WHERE id='%s'", $kernel['id_session']);
  $kernel['id_user'] = 0;
  $kernel['login']   = '';
  return true;
}

// check search bot
function is_robot($ua=NULL)
{
  if(empty($ua)) { $ua = $_SERVER['HTTP_USER_AGENT']; }
  $ua = strtolower($ua);
  static $robots = array(
			'appie','ask jeeves','aspseek','avantgo',
			'bigfoot.com','blitzbot','blogchecker','bot','bot','bsdseek','bumblebee@relevare.com',
			'charybdis','cosmos','crawl','crawl','curl',
			'db/0.2; spc','diagem','disco pump',
			'emailsiphon',
			'fairad','firefly','flunky','fmii url validator',
			'gazz','getright','getweb','google','gozilla','gulliver',
			'htdig','httrack',
			'ia_archiver','ideare','indy library','internetlinkagent',
			'java','jigsaw',
			'larbin','libcurl','libwww','linklint','linkman','linkwalker','lisen','lwp',
			'mercator','microsoft url control','mnogo','mnogosearch-dimensional','moget','ms search','msnbot',
			'netcraft','netprospector','ng/1.0',
			'offline explorer','open sourfce retriver','openfind',
			'perl','php','pompos','powermarks',
			'rambler','reifier','robozilla','rpt-httpclient',
			'scooter','scoutabout','searchtone','signsite','sitecheck.internetseer.com','slurp','slysearch','snoopy','spider','spider','spinne','steeler','sun4u','surferx',
			'teleport','teradex mapper','tkensaku',
			'vagabondo','vayala','vias.ncsa.uiuc.edu','viking',
			'w3c_validator','w3mir','watchfire webxm','web downloader','webcapture','webcollage','webcopier','webreaper','webshuttle','webstripper','wget','whizbang','whizbang','www.walhello.com','wwwc','wwwoffle',
			'xenu link sleuth',
			'yahoo.com','yandex',
			'zyborg'
			);
  foreach($robots as $i)
   if(strstr($ua, $i))
    { return true; }
  return false;
}

// russian login normilize, win1251
function userhash($name)
{
  $name = strtr(strtolower($name), 'ЙЦУКЕНГШЩЗХЪФЫВАПРОЛДЖЭЯЧСМИТЬБЮ', 'йцукенгшщзхъфывапролджэячсмитьбю');
  $name = preg_replace('~[^а-яa-z0-9]~si', '', $name);
  $name = strtr($name, 'eyuopaghklxcbnm03', 'еуиораднк1хсвпмоз');
  $name = substr($name, 0, 16);
  return $name;
}

// username
function is_login($login)
{
  global $kernel;
  $q = $kernel['db']->query();
  $q->format("SELECT id FROM users WHERE login='%s'", $login);
  $r = $q->get_row();
  $q->free_result();
  return intval($r['id']);
}

function id_user($login) {  return is_login($login); }

function setLanguage($lang='ru', $locale='ru_RU') 
{
    $lang = 'en';
    $locale = "en_US";
    $domain = 'default'; // так должны называться файлы *.po и *.mo
    $locale_path = $_SERVER['DOCUMENT_ROOT'].'/locale'; // папка с каталогами переводов
    // Set enviroment
    putenv('LC_MESSAGES='.$locale);
    putenv('LANG='.$locale);
    putenv('LANGUAGE='.$locale);
    // Set locale
    if (!setlocale (LC_MESSAGES, $locale.'.utf8', $locale.'.utf-8', $locale.'.UTF8', $locale.'.UTF-8', $lang.'.utf-8', $lang.'.UTF-8', $lang)) {
        // Set current locale
        setlocale(LC_MESSAGES, '');
    }
    // Bind domain 
    bindtextdomain($domain, $locale_path);
    bind_textdomain_codeset($domain, 'UTF-8');
    // Set default domain
    textdomain($domain);
}

?>