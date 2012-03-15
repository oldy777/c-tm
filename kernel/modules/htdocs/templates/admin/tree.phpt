<?$tree = new httree();?>

<script language="javascript" type="text/javascript"><!--
function setcookie(name, value, time)
{
  if(!time) { time = 3600*24*30; }
  var expire = new Date();
  expire.setTime(expire.getTime() + time);
  document.cookie = escape(name) + '=' + escape(value) + ';expires=' + expire.toGMTString() + '; path=/';
}

function getcookie(name)
{
  var reg = new RegExp('(\;|^)[^;]*('+name+')\=([^;]*)(;|$)');
  var res = reg.exec(document.cookie);
  return (res!=null? unescape(res[3]) : null);
}

function delcookie(name) { return setcookie(name, '', -1); }

function httreedel(id)
{
  var obj = document.getElementById("nodetitle"+id);
  if(obj && confirm('Удалить папку "'+obj.innerHTML+'" и все её документы?'))
  {
    document.location = '?mod=htdocs&act=delnode&id='+id;
  }
}
function httreeshow(id, state)
{
  var tree = document.getElementById("tree"+id);
  var node = document.getElementById("node"+id);
  if(!node || !tree) { return; }
  state = (typeof state=='undefined'? (tree.style.display=='none') : state);
  if(state)
  {
    var c = "";
    c = ","+getcookie('hidenode')+",";
    c = c.replace(","+id+",", "");
    c = c.replace(",,", ",");
    setcookie('hidenode', c);
    tree.style.display = 'block';
    node.src = "images/tree_minus.gif";
    node.title = "свернуть";
  }
  else
  {
    var c = "";
    c = ","+getcookie('hidenode')+",";
    c = c.replace(","+id+",", "") + "," + id + ",";
    c = c.replace(",,", ",");
    setcookie('hidenode', c);
    tree.style.display = 'none';
    node.src = "images/tree_plus.gif";
    node.title = "развернуть";
  }
}
function httreeall(state)
{
  var i, n, ids = [<?=implode(',',$tree->listallid())?>];
  for(i = 0,n = ids.length; i < n; i++)
  {
   if(state || ids[i]!=<?=intval($args['root']['id'])?>)
    { httreeshow(ids[i], state); }
  }
}
function nodeover(obj, id)
{
  obj.style.background='#F0F7FF';
  var icons = document.getElementById("icons"+id);
  icons.className = "over";
}
function nodeout(obj, id)
{
  obj.style.background='';
  var icons = document.getElementById("icons"+id);
  icons.className = "out";
}
//--></script>
<style type="text/css">
div.treenode { margin:0 0 5px 10px; border-left:1px solid #999999; }
table.treeitem { font-size:0.8em; border-collapse:collapse; }
table.treeitem td { padding:0; white-space:nowrap; }
img.treeitem { background-color:#999999; }
img.up { width:10px; height:6px; margin:1px 4px 1px 4px; }
img.down { width:10px; height:6px; margin:1px 4px 1px 4px; }
img.folder { margin:0 1px 0 1px; width:17px; height:17px; }
table.treeitem td.out
{
  filter:progid:DXImageTransform.Microsoft.Alpha(opacity=5);
  -moz-opacity: 0.05;
  -khtml-opacity: 0.05;
  opacity: 0.05;
}
table.treeitem td.over
{
  filter:progid:DXImageTransform.Microsoft.Alpha(opacity=100);
  -moz-opacity: 1;
  -khtml-opacity: 1;
  opacity: 1;
}
</style>
<?php
$parent = 0;

$tree->showallaslist();


if($_COOKIE['hidenode']){?>
<script language="javascript" type="text/javascript"><!--
<?foreach(explode(",",$_COOKIE['hidenode']) as $i)if($i > 0){?>httreeshow(<?=intval($i)?>); <?}?>
//--></script>
<?}?>