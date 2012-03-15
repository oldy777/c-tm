<?php
$sql = trim($_POST['sql']);
$kernel['db']->debug = false;
$q = $kernel['db']->query();
?>
<div class="text"><?=$config['db']['name']?></div>
<form method="POST">
<textarea name="sql" style="width:100%; height:150px"><?=htmlspecialchars($sql)?></textarea>
<div><input type="submit" class="button" value="Выполнить!" style="margin-top:5px; margin-bottom:5px;" /></div>
</form>
<?php
if($sql!='')
{
$q->query($sql);
if($kernel['db']->errno())
 { echo '<div class="error">'. htmlspecialchars($kernel['db']->error()). '</div>'; }
if($q->is_result()){?>
<div class="text">Количество рядов: <?=$q->num_rows()?></div>
<div style="width:100%; height:300px; overflow:auto; border:1px solid #C7DFE3;">
<table width="100%" border="0" cellspacing="2" cellpadding="2" class="table">
<?php
$r = $q->get_row();
echo "<thead><tr>\n";
foreach($r as $k=>$v) { echo '<th>'.htmlspecialchars($k)."</th>\n"; }
echo "</tr></thead>\n";
do
{
  echo "<tr>\n";
  foreach($r as $i)
  if($i===NULL) { echo '<td align="center" style="background:#000000;color:#FFFFFF">NULL</td>'; }
  else { echo '<td'. (is_numeric($i)? ' align="right"' : ''). '>'. ($i==''? '&nbsp;' : nl2br(htmlspecialchars($i))). "</td>"; }
  echo "\n</tr>\n";
}
while($r = $q->get_row());?>
</table>
</div>
<?}else{?>
Затронуто рядов: <?=$kernel['db']->affected_rows()?><br />
<?}?>
<?
}
?>
