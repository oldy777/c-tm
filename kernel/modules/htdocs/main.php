<?php

// INIT
$args = array();
$q = $kernel['db']->query();
$template = 'list.phpt';
if($params['template'] && is_file(dirname(__FILE__). '/templates/'. $params['template'])) { $template = $params['template']; }

$args['id_node'] = intval($params['id_node']);
if(!$args['id_node']) { $args['id_node'] = $kernel['node']['id']; }

$args['order'] = $params['order'];
if(!$args['order']) { $args['order'] = 'h.pos,h.published DESC,h.id DESC'; }

$args['limit'] = intval($params['limit']);
$args['pages'] = intval($params['pages']);
$args['hidden'] = (bool)$params['hidden'];

// node
$args['where'] = 'WHERE ';
if($args['id_node'] > 0) { $args['where'].= 'h.id_node='. intval($args['id_node']). ' AND '; }
// not hidden
if(!$args['hidden']) { $args['where'].= 'NOT h.hidden  AND '; }
$args['where'].= ' 1=1';

if($args['limit'])  // limit
{
  $args['limit'] = intval($args['limit']);
}
elseif($args['pages'] > 0) // pages
{
  $q->format("SELECT COUNT(id) as n FROM htdocs as h {$args[where]}");
  $r = $q->get_row();
  $q->free_result();
  $page = intval($_GET['page']);
  if($page<=0) { $page = intval($_SESSION['page'][ $args['id_node'] ]); }
  $pages = pages($page, $r['n'], $args['pages'], '?page=');
  $_SESSION['page'][ $args['id_node'] ] = $pages['cur'];
  $args['pages'] = $pages['content'];
  $args['limit'] = $pages['offset']. ','. $pages['size'];
}

// ITEMS
$args['items'] = array();
$q->format("SELECT h.*,CONCAT(t.fullpath,h.path,'.html') as path FROM htdocs as h INNER JOIN httree as t ON (t.id=h.id_node) {$args[where]} ORDER BY %s LIMIT %s", $args['order'], $args['limit']);
while($r = $q->get_row())
{
  $args['items'][] = $r;
}
$q->free_result();


// TEMPLATE
if(!$params['raw'] && $template!='') { template(dirname(__FILE__). '/templates/'. $template, $args, $errors); }

// FINNALY
if($params['raw']) { return $args; }
?>