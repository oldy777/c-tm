<?php
class httree
{
  var $db = NULL;
  var $table = '';
  var $doctable;


  //конструктор класса
  function httree($table='ptree', $doctable='htdocs')
  {
    global $kernel;
    $this->db = $kernel['db'];
    $this->table = $this->db->escape($table);
    $this->doctable = $doctable;
  }

  // получения инфы ветки по ее пути (может пригодится, щас лишняя см. findnode)
  function getnodebypath($path){
      $q = $this->db->query();
      if(!$q->query("SELECT * FROM {$this->table} WHERE fullpath='{$path}'")) { return false; }
      $ret = $q->get_row();
      return $ret;
  }


  //получение "родителя" узла
  function getparent($id){
       $q = $this->db->query();
       if(!$q->query("SELECT * FROM {$this->table} WHERE id=(SELECT id_parent FROM {$this->table} WHERE id=$id)")) { return false; }
       $ret = $q->get_row();
       $q->free_result();
       return $ret;
  }

  //получение массива йд наследников
  function getchild($id,$hidden=0){
      $q = $this->db->query();
      $hid="";
      if ($hidden) $hid=" and hidden=0";
      if(!$q->query("SELECT * FROM {$this->table} WHERE id_parent=$id {$hid} order by pos")) { return false; }
      $ret = $q->get_allrows();
      $q->free_result();
      return $ret;
  }
  function getchild2($id,$hidden=0){
      $q = $this->db->query();
      $hid="";
      if ($hidden) $hid=" and hidden=0";
      if(!$q->query("SELECT * FROM htdocs WHERE id_node=$id {$hid} order by pos")) { return false; }
      $ret = $q->get_allrows();
      $q->free_result();
      return $ret;
  }

  //получение списка корня (где уровень равен нулю)
  function getroot($level=0,$hidden=0){
      $q = $this->db->query();
      $hid="";
      if ($hidden) $hid=" and hidden=0";
      if(!$q->query("SELECT * FROM {$this->table} WHERE level={$level} {$hid} order by pos")) { return false; }
      $ret = $q->get_allrows();
      $q->free_result();
      return $ret;
  }

  //смена родителя
  function setparent($id,$parent){
      //получаем инфу об изменяемом узле
      global $kernel;
      $node=$this->getnode($id);

      $q = $this->db->query();
      //делаем сдвижку позиции всех соседних веток
      if ($node['pos']>0){
        if(!$q->query("UPDATE {$this->table} SET pos=pos-1 WHERE pos>{$node['pos']} and id_parent={$node['id_parent']}")) { return false; }
      }

      //получаем позицию последнего узла у будущего родителя
      if(!$q->query("SELECT max(pos) FROM {$this->table} WHERE id_parent={$parent}")) { return false; }
      $ret=$q->get_row();
      $pos=$ret['pos']+1;

      //изменяем родителя
      if(!$q->query("UPDATE {$this->table} SET pos={$pos}, id_parent='{$parent}' WHERE id = {$id}")) { return false; }

      return 1;

  }


  //повышение положения ветки
  function upnode($id){
      global $kernel;
      $kernel["node"]=$this->getnode($id);
      $q = $this->db->query();
      if ($kernel["node"]["pos"]>0){
        if(!$q->query("SELECT * FROM {$this->table} WHERE id_parent={$kernel["node"]["id_parent"]} and pos={$kernel["node"]["pos"]}-1")) { return false; }
        if ($q->num_rows()>0){
            $ret = $q->get_row();
            $zam_id=$ret["id"];
            if(!$q->query("UPDATE {$this->table} SET pos=pos-1 WHERE id={$kernel["node"]["id"]}")) { return false; }
            if(!$q->query("UPDATE {$this->table} SET pos=pos+1 WHERE id={$zam_id}")) { return false; }
        }
        $q->free_result();
      };
      return 1;
  }

  //полнижение положения ветки
  function downnode($id){
      global $kernel;
      $kernel["node"]=$this->getnode($id);
      $q = $this->db->query();
      if ($kernel["node"]["pos"]>0){
        if(!$q->query("SELECT * FROM {$this->table} WHERE id_parent={$kernel["node"]["id_parent"]} and pos>{$kernel["node"]["pos"]} order by pos asc")) { return false; }
        if ($q->num_rows()>0){
            $ret = $q->get_row();
            $zam_id=$ret["id"];
            if(!$q->query("UPDATE {$this->table} SET pos=pos+1 WHERE id={$kernel["node"]["id"]}")) { return false; }
            if(!$q->query("UPDATE {$this->table} SET pos=pos-1 WHERE id={$zam_id}")) { return false; }
            //echo "UPDATE {$this->table} SET pos=pos+1 WHERE id={$kernel["node"]["id"]}<br>";
            //echo "UPDATE {$this->table} SET pos=pos-1 WHERE id={$zam_id}<br>";
        } else return false;
        $q->free_result();
      };
      return true;
  }

  //изменение положения ветки вверх/вниз

  //получение информации об узле ввиде массива
  function getnode($id, $fields=array())
  {
    $id = intval($id);
    $q = $this->db->query();
    $fields = $this->fields($fields);
    if(!$q->query("SELECT $fields FROM {$this->table} WHERE id=$id")) { return false; }
    $ret = $q->get_row();
    $q->free_result();
    return $ret;
  }
  //получение списка полей как строку с запятыми
  function fields($fields, $prefix='')
  {
    return $prefix. ((is_array($fields) && !empty($fields))? implode(', '.$prefix, $fields) : '*' );
  }

  //функция вывода полного списка меню на экран + иконки действий
  function showallaslist(){
      global $kernel;
      $root=$this->getroot();
      foreach ($root as $k=>$el){
          extract($el,EXTR_PREFIX_ALL,"p");
?>
<table class="treeitem" id="item<?=$el['id']?>" onmouseover="nodeover(this,<?=$el['id']?>)" onmouseout="nodeout(this,<?=$el['id']?>)"><tr>
 <td><img src="images/none.gif" width="6" height="1" class="treeitem" /></td>
 <td><?if($el['isparent']){?><a href="javascript:httreeshow(<?=$el['id']?>)"><img id="node<?=$el['id']?>" src="images/tree_minus.gif" width="9" height="9" title="свернуть" class="treeitem" /></a><?}else{?><img src="images/none.gif" width="9" height="1" class="treeitem" /><?}?></td>
 <td><img id="folder<?=$el['id']?>" src="images/<?=($el['hidden']? 'icon_folder_gray.gif' : 'icon_folder_blue.gif')?>" class="folder" /></td>
 <td><a href="?mod=htdocs&amp;act=docs&amp;id=<?=$el['id']?>" title="документы" class="<?=($el['visible']? '' : 'hidden')?>" id="nodetitle<?=$el['id']?>"><?=$el['title']?></a></td>
 <td width="93%"><?if($el['id']!=$args['root']['id']){?><a href="?mod=htdocs&amp;act=nodeup&amp;id=<?=$el['id']?>" title="позиция выше"><img src="images/arr_up.gif" class="up" /></a><br /><a href="?mod=htdocs&amp;act=nodedown&amp;id=<?=$el['id']?>" title="позиция ниже"><img src="images/arr_down.gif" class="down" /></a><?}else{?>&nbsp;<?}?></td>
 <td id="icons<?=$el['id']?>" class="out">
 <a href="<?=htmlspecialchars($el['fullpath'])?>" target="_blank" title="просмотр"><img class="btn_view" src="images/icon_view.gif" /></a>
 <a href="?mod=htdocs&amp;act=docs&amp;id=<?=$el['id']?>" title="документы"><img class="btn_list" src="images/icon_list.gif" /></a>
 <a href="?mod=htdocs&amp;act=addnode&amp;id=<?=$el['id']?>" title="подраздел"><img class="btn_add" src="images/icon_add.gif" /></a>
 <a href="?mod=htdocs&amp;act=editnode&amp;id=<?=$el['id']?>" title="редактировать"><img class="btn_edit" src="images/icon_edit.gif" /></a>
<?if($el['id']!=$root['id']){?>
<?if(!$el['isparent']){?>
 <a href="javascript:httreedel(<?=$el['id']?>)" title="удалить"><img class="btn_del" src="images/icon_del.gif" /></a>
<?}else{?>
 <img class="btn_disable" src="images/icon_del_disable.gif" />
<?}?>
 <a href="?mod=htdocs&amp;act=movenode&amp;id=<?=$el['id']?>" title="переместить"><img class="btn_move" src="images/icon_move.gif" /></a>
<?}else{?>
 <img class="btn_disable" src="images/icon_del_disable.gif" />
 <img class="btn_disable" src="images/icon_move_disable.gif" />
<?}?>
 </td>
 </tr>
</table>
<?
          $this->printchilds($p_id);
      }
  }

  //функция возвращает список всех йд веток (для сворачивания/разворачивания всего дерева)
  function listallid(){
       $q = $this->db->query();
       if(!$q->query("SELECT id FROM {$this->table}")) {  return array(); }
       if ($q->num_rows()>0){
            $ret=array();
            $temp1=$q->get_allrows();
            for ($i=0;$i<$q->num_rows();$i++){
                $temp=$temp1[$i];
                $ret[] = $temp['id'];
            }
            return $ret;
       }else{
           return 0;
       }
  }

  //выдает полный список всех
  function returnall($id){
    $q = $this->db->query("SELECT * FROM {$this->table} WHERE NOT(id={$id}) ORDER BY fullpath");
    $ret = $q->get_allrows();
    $q->free_result();
    return $ret;
  }

  //вспомогательная рекурсивная функция для вывода меню
  function printchilds($id){
      $childs=$this->getchild($id);
      if (is_array($childs)and(count($childs)>0)){
          echo "<div id=\"tree{$id}\" class=\"treenode\">\n";
          while ($el = each($childs)){
              $el=$el[1];
              extract($el,EXTR_PREFIX_ALL,"p");

?>
<table class="treeitem" id="item<?=$el['id']?>" onmouseover="nodeover(this,<?=$el['id']?>)" onmouseout="nodeout(this,<?=$el['id']?>)"><tr>
 <td><img src="images/none.gif" width="6" height="1" class="treeitem" /></td>
 <td><?if($el['isparent']){?><a href="javascript:httreeshow(<?=$el['id']?>)"><img id="node<?=$el['id']?>" src="images/tree_minus.gif" width="9" height="9" title="свернуть" class="treeitem" /></a><?}else{?><img src="images/none.gif" width="9" height="1" class="treeitem" /><?}?></td>
 <td><img id="folder<?=$el['id']?>" src="images/<?=($el['hidden']? 'icon_folder_gray.gif' : 'icon_folder_blue.gif')?>" class="folder" /></td>
 <td><a href="?mod=htdocs&amp;act=docs&amp;id=<?=$el['id']?>" title="документы" class="<?=($el['visible']? '' : 'hidden')?>" id="nodetitle<?=$el['id']?>"><?=$el['title']?></a></td>
 <td width="93%"><?if($el['id']!=$args['root']['id']){?><a href="?mod=htdocs&amp;act=nodeup&amp;id=<?=$el['id']?>" title="позиция выше"><img src="images/arr_up.gif" class="up" /></a><br /><a href="?mod=htdocs&amp;act=nodedown&amp;id=<?=$el['id']?>" title="позиция ниже"><img src="images/arr_down.gif" class="down" /></a><?}else{?>&nbsp;<?}?></td>
 <td id="icons<?=$el['id']?>" class="out">
 <a href="<?=htmlspecialchars($el['fullpath'])?>" target="_blank" title="просмотр"><img class="btn_view" src="images/icon_view.gif" /></a>
 <a href="?mod=htdocs&amp;act=docs&amp;id=<?=$el['id']?>" title="документы"><img class="btn_list" src="images/icon_list.gif" /></a>
 <a href="?mod=htdocs&amp;act=addnode&amp;id=<?=$el['id']?>" title="подраздел"><img class="btn_add" src="images/icon_add.gif" /></a>
 <a href="?mod=htdocs&amp;act=editnode&amp;id=<?=$el['id']?>" title="редактировать"><img class="btn_edit" src="images/icon_edit.gif" /></a>
<?if($el['id']!=$root['id']){?>
<?if(!$el['isparent']){?>
 <a href="javascript:httreedel(<?=$el['id']?>)" title="удалить"><img class="btn_del" src="images/icon_del.gif" /></a>
<?}else{?>
 <img class="btn_disable" src="images/icon_del_disable.gif" />
<?}?>
 <a href="?mod=htdocs&amp;act=movenode&amp;id=<?=$el['id']?>" title="переместить"><img class="btn_move" src="images/icon_move.gif" /></a>
<?}else{?>
 <img class="btn_disable" src="images/icon_del_disable.gif" />
 <img class="btn_disable" src="images/icon_move_disable.gif" />
<?}?>
 </td>
 </tr>
</table>
<?
              $this->printchilds($p_id);
          }
          echo "</div>";
      }
  }

  //функция выводит подуровни текущей странички
  //в случае если таковых нету выводится текущий уровень
  //учитывается установки параметра hidden
  function printonelevel(){
      global $kernel;
      $level=$kernel["node"]["level"];
      $childs=$this->getchild($kernel['node']['id'],1);
      if (count($childs)>0){
          echo "<ul>";
          while ($el = each($childs)){
              $el=$el[1];
              extract($el,EXTR_PREFIX_ALL,"p");
              echo "<li>$p_id <a href=$p_fullpath>$p_title</a> <a href=?mods=pdocs&act=up&id=$p_id>up</a> <a href=?mods=pdocs&act=down&id=$p_id>down</a></li>";
          }
          echo "</ul>";
      }else{ //eсли не нашли детей печатаем текущий
            $cur = $this->getroot($kernel['node']['level'],1);
            if (count($cur)){
                echo "<ul>";
                while ($el = each($cur)){
                    $el=$el[1];
                    extract($el,EXTR_PREFIX_ALL,"p");
                    echo "<li>$p_id <a href=$p_fullpath>$p_title</a> <a href=?mods=pdocs&act=up&id=$p_id>up</a> <a href=?mods=pdocs&act=down&id=$p_id>down</a></li>";
                }
                echo "</ul>";
            }
      }
  }

  //фунция производит поиск узла по заданому документу
  function findnode($url){
      global $kernel;
      $q = $this->db->query();
      if(!$q->query("SELECT * FROM {$this->table} WHERE fullpath='$url'")) { return false; }
      //echo "SELECT * FROM {$this->table} WHERE fullpath='$url'";
      $ret = $q->get_row();
      if (is_array($ret)){
        return $ret;
      };
      return 0;
  }

  //добавление узла
  function addnode($id,$data){
    //получаем инфу о предке
    $id = intval($id);
    $node = $this->getnode($id);
    if(empty($node)) { return false; }
    $q = $this->db->query();

    $data['level'] = $node['level']+1;
    $data['id_parent'] = $node['id'];
    $data['fullpath'] = $node['fullpath'].$data['path']."/";

    if(!$q->query("SELECT max(pos) as pos FROM {$this->table} WHERE id_parent={$id}")){return false;}
    $ret=$q->get_row();
    if ($ret){
        $data['pos']=$ret['pos']+1;
    }else{
        $data['pos']=0;
    }

    $this->db->transaction();

    //задаем что родительская ветка стала "родителем"
    if(!$q->query("UPDATE {$this->table} SET isparent=1 WHERE id='{$id}'"))
    {
      $this->db->rollback();
      return false;
    }

    $parent=$data['id_parent'];
    $data['id_parent']=99999;
    //вставляем данные с учетом указания на 99999 предка :)
    if(!$q->format("INSERT INTO {$this->table} SET %s", $data))
    {
      $this->db->rollback();
      return false;
    }
    if(!$q->query("SELECT id FROM {$this->table} WHERE id_parent='99999'", $data))
    {
      $this->db->rollback();
      return false;
    }
    $newid=$q->get_row();
    $newid=$newid['id'];

    if(!$q->query("UPDATE {$this->table} SET id_parent='{$parent}' WHERE id='{$newid}'"))
    {
      $this->db->rollback();
      return false;
    }
    $this->db->commit();
    return $newid;

  }

  //удаление узла
  function del($id){
        //удаляем из таблицы узлов
        $node = $this->getnode($id);
        if(empty($node)) { return false; }
        $q = $this->db->query();
        $this->db->transaction();
        if(!$q->query("DELETE FROM {$this->table} WHERE id=$id")){
            $this->db->rollback();
            return false;
        }

        //меняем позицию оставшихся
        if(!$q->query("UPDATE {$this->table} SET pos=pos-1 WHERE id_parent='{$node['id_parent']}' and pos>{$node['pos']}")){
            $this->db->rollback();
            return false;
        }
        //удаляем документы
        if(!$q->query("DELETE FROM {$this->doctable} WHERE id_node='{$id}'")){
            $this->db->rollback();
            return false;
        }


        //если больше не осталось детей у предка то снимаем с него статус родителя
        if(!$q->query("SELECT * FROM {$this->table} WHERE id_parent={$node['id_parent']}")){
            $this->db->rollback();
            return false;
        }
        if (!is_array($q->get_row())){
            if(!$q->query("UPDATE {$this->table} SET isparent='0' WHERE id={$node['id_parent']}")){
                $this->db->rollback();
                return false;
            }
        }
        $this->db->commit();
        return true;
  }

  //изменение информации узла (редактирование)
  function update($id,$data){
          $q = $this->db->query();
          if(!$q->format("UPDATE {$this->table} SET %s WHERE id={$id}", $data)){return false;}
          return true;
  }

  //проверка на уникальность пути возвращает кол-во таких путей
  function checkpath($path){
          $q = $this->db->query();
          if(!$q->query("SELECT * FROM {$this->table} WHERE fullpath='{$path}'")){return false;}
          return $q->num_rows();
  }

  //добавление документа
  function insertdoc($id, $data){
        if(empty($data['path'])) { return false; }
        $node = $this->getnode($id);
        if(empty($node)) { return false; }
        $q = $this->db->query();
        $data['id_node'] = $node['id'];
        $data['created'] = time();
        $temp = $data['pos'];
        $data['pos']=99999;
        if(!$q->format("INSERT INTO {$this->doctable} SET %s", $data)){
            $this->db->rollback();
            return false;
        }
        if(!$q->query("SELECT * FROM {$this->doctable} WHERE pos=99999 LIMIT 1")){
            $this->db->rollback();
            return false;
        };
        $ret=$q->get_row();
        $data['id']=$ret['id'];
        $data['pos']=$temp;

        if(!$q->format("UPDATE {$this->doctable} SET %s WHERE id={$data['id']}", $data)){
            $this->db->rollback();
            return false;
        }

        $this->db->commit();
        return $data['id'];
  }


  //получение документа
  function getdoc($id, $id_node=0){
        $q = $this->db->query();
        if(!$q->format("SELECT * FROM {$this->doctable} WHERE (`%s`='%s') AND (id_node='%d' OR '%d'=0) ORDER BY id LIMIT 1", (is_int($id)? 'id' : 'path'), $id, $id_node, $id_node>0)){ return false; }
        $ret = $q->get_row();
        $q->free_result();
        return $ret;
  }

  //обновление документа
  function updatedoc($id, $data){
        $q = $this->db->query();
        if((is_array($data) && !empty($data)) || !$data['updated']) {
            $data['updated'] = time();
        }else{
            return false;
        }
        return $q->format("UPDATE {$this->doctable} SET %s WHERE id='%d'", $data, $id);
  }

  //проверка на уникальность имени
  function checkdoc($path,$node,$id=0){
          $q = $this->db->query();
          if(!$q->query("SELECT * FROM {$this->doctable} WHERE path='{$path}' and id_node='{$node}' and NOT(id='{$id}')")){return false;}
          if ($q->num_rows()>0){
              return true;
          }else{
              return false;
          }
  }

  //удаление документа
  function deldoc($id)
  {
    $q = $this->db->query();
    $this->db->transaction();
    if(!$q->format("DELETE FROM {$this->doctable} WHERE id='%d'", $id))
    {
      $this->db->rollback();
      return false;
    }
    $this->db->commit();
    return true;
  }
}

//"хлебные крошки"
function broad($template = array()){
  global $kernel;
  $tree=$kernel['tree'];
  extract($kernel['node'],EXTR_PREFIX_ALL,'p');
  if(!is_array($template) or empty($template)){
    $template = array();
    $template = array();
    $template['prefix'] = "::";
    $template['normal'] = "<a href=\".htmlspecialchars(\$fullpath).\" style='broad_link'>\".notags(\$title).\"</a>";
    $template['suffix'] = "";
  }

  $lev=$p_level;
  $id=$p_id;
  //пока уровень не будет равень 0 идем от обратного вверх
  while ($lev>0){
      $parn = $tree->getparent($id);
      $fullpath=$parn['fullpath'];
      $title=$parn['title'];
      $prefix=$template['prefix'];
      $link=$template['normal'];
      $suffix=$template['suffix'];

      eval("\$output=\"$prefix$link$suffix\".\$output;");

      //eval('$ret.=(\''. ($v['active'] && $t['active']!=''? $t['active'] : $t['normal']). '\');');
      $lev=$parn['level'];
      $id=$parn['id'];
  }
  return $output;
}

//функция выводит подуровни текущей странички
  //в случае если таковых нету выводится текущий уровень
  //учитывается установки параметра hidden
function printonelevel($chil=true,$template=array()){
    global $kernel;
    $level=$kernel["node"]["level"];
    $tree=$kernel['tree'];
    $childs=$tree->getchild($kernel['node']['id'],1);
    $output='';
    if(!is_array($template) or empty($template)){
        $template = array();
        $template['prefix'] = "<ul class=\'menu\'>\n";
        $template['active'] = "<li class=\'menu-act\'><a href=\".htmlspecialchars(\$fullpath).\" style='broad_link'>\".notags(\$title).\"</a></li>";
        $template['normal'] = "<li class=\'menu-item\'><a href=\".htmlspecialchars(\$fullpath).\".html\".\" style='broad_link'>\".notags(\$title).\"</a></li>";
        $template['suffix'] = "</ul>\n";
    }
    $prefix=$template['prefix'];
    $link=$template['normal'];
    $alink=$template['active'];
    $suffix=$template['suffix'];

    if ((count($childs)>0)&&($chil)){
        eval("\$output=\$output.\"$prefix\";");
        while (list($param,$el) = each($childs)){
            $fullpath=$el['path'];
            $title=$el['title'];
            eval("\$output=\$output.\"$link\";");
       }
        eval("\$output=\$output.\"$suffix\";");
    }else{ //eсли не нашли детей печатаем текущий
        $cur=$tree->getchild($kernel['node']['id_parent'],1);
        if (count($cur)){
            eval("\$output=\$output.\"$prefix\";");
            while (list($param,$el) = each($cur)){
                $fullpath=$el['fullpath'];
                $title=$el['title'];
                $nod=$el['id'];
                $nownod=$kernel['node']['id'];
                if($nod==$nownod){
                    eval("\$output=\$output.\"$alink\";");
                }else{
                    eval("\$output=\$output.\"$link\";");
                }
            }
            eval("\$output=\$output.\"$suffix\";");
        }
    }
    return $output;
}

?>
