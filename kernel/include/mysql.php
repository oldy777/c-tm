<?php
if (!function_exists("mysql_connect")) { trigger_error("mysql extension not loaded!", E_USER_ERROR); }

class db_mysql
{
  var $dsn                  = "";
  var $debug                = false;
  var $cache                = false;
  var $global_cache         = false;
  var $cache_time           = 0;
  var $cache_path           = false;
  var $cache_folders_depth  = 4;
  var $linkid               = NULL;
  var $queries_col              = 0;
  var $queries              = NULL;
  var $charset              = "";
  var $result_data;

//var $emap    = array(1004=>"Cannot Create", 1005=>"Cannot Create", 1006=>"Cannot Create", 1007=>"Already Exists", 1008=>"Cannot Drop", 1046=>"Nodbselected", 1050=>"Already Exists", 1051=>"Nosuchtable", 1054=>"Nosuchfield", 1062=>"Already Exists", 1064=>"Syntax", 1100=>"Not Locked", 1136=>"Value Count On Row", 1146=>"Nosuchtable", 1048=>"Constraint");

  function db_mysql($dsn="")
  {
    // some parse
    $this->dsn = $dsn;
  }

  function connect($host, $user, $passwd, $name="", $charset="", $debug=false, $cache=false, $cache_time=0, $cache_path='')
  {
    if (!($this->linkid = @mysql_connect($host, $user, $passwd)))
    {
      return false;
    }
    elseif (!mysql_select_db($name, $this->linkid))
    {
      $this->close();
      return false;
    }
    elseif (""!=$charset)
    {
      $this->exec("SET CHARACTER SET '". $charset. "'");
    }
    $this->debug = $debug;
    $this->charset = $charset;
    $this->global_cache = $cache;
    $this->cache_time = $cache_time;
    $this->cache_path = $cache_path;
    return true;
  }

  function close()
  {
    $ret = mysql_close($this->linkid);
    $this->linkid = NULL;
    return $ret;
  }

  function query($query="")
  {
    return (new query_mysql($query, $this));
  }

  function affected_rows()
  {
    return mysql_affected_rows($this->linkid);
  }

  function error()
  {
    return mysql_error($this->linkid);
  }

  function errno()
  {
    return mysql_errno($this->linkid);
  }

  function next_id($table="") // seq emulate
  {
    return 0;
  }

  function last_id($table="") // seq emulate
  {
    return mysql_insert_id($this->linkid);
  }

  function escape($str)
  {
    return (function_exists("mysql_real_escape_string")? mysql_real_escape_string($str, $this->linkid) : mysql_escape_string($str));
  }


  function quote($str)
  {
    return "`{$str}`";
  }

  function transaction()
  {
    return ($this->exec("SET AUTOCOMMIT=0") && $this->exec("BEGIN") && 0==$this->errno());
  }

  function rollback()
  {
    return ($this->exec("ROLLBACK") && $this->exec("SET AUTOCOMMIT=1") && 0==$this->errno());
  }

  function commit()
  {
    return ($this->exec("COMMIT") && $this->exec("SET AUTOCOMMIT=1") && 0==$this->errno());
  }

  function autocommit($state)
  {
    $state = intval((bool)$state);
    return ($this->exec("SET AUTOCOMMIT={$state}") && 0==$this->errno());
  }

  function version()
  {
    $q = $this->query();
    $ret = $q->select_cell("SELECT VERSION()");
    $q->free_result();
    return $ret;
  }

  function tables()
  {
    $q = $this->query();
    $ret = $q->select_col("SHOW TABLES");
    $q->free_result();
    return $ret;
  }

  function databases()
  {
    $q = $this->query();
    $ret = $q->select_col("SHOW DATABASES");
    $q->free_result();
    return $ret;
  }

  function format($format)
  {
    $args = func_get_args();
    array_shift($args);
    foreach ($args as $k=>$v)
    {
      if (is_array($v) && !empty($v))
      {
        foreach ($v as $i=>$j)
        {
          $v[ $i ] = (is_string($i)? $this->quote($this->escape($i)). "=" : "")
                    .(NULL===$j? "NULL" : "'". $this->escape($j). "'");
        }
        $args[ $k ] = implode(",", $v);
      }
      else
      {
        $args[ $k ] = $this->escape($v);
      }
    }
    return (empty($args)? $format : vsprintf($format, $args));
  }


  function exec($query)
  {
    if (preg_match('/^SELECT/', $query)&&$this->cache){
      $path=md5($query);

      unset($this->result_data);

      $dirs=Array();
      $folder=$this->cache_path;
      for($i=1;$i<=$this->cache_folders_depth;$i++){
        $dirs[$i]=substr($path,(($i-1)*2),2);

        $folder.=$dirs[$i].'/';
        if(!is_dir($folder)){
          @mkdir($folder);
        }
      };

      $filename = $folder.$path.'.cache';

      if (!((@$file = fopen($filename, 'r')) && filemtime($filename) > (time() - $this->cache_time))){
        $res=mysql_query($query, $this->linkid);

        $nf = mysql_num_fields($res);
        for ($i = 0; $i < $nf; $i++){
          $this->result_data['fields'][$i] = mysql_fetch_field($res, $i);
        }

        $nr = mysql_num_rows($res);
        for ($i = 0; $i < $nr; $i++)
        {
           $this->result_data['data'][$i] = mysql_fetch_row($res);
        }
        $file = fopen($filename, 'w');
        @flock($file, LOCK_EX);
        @fwrite($file, serialize($this->result_data));
        @fclose($file);
      }else{
        flock($file, LOCK_SH);
        $serial = file_get_contents($filename);
        $this->result_data = unserialize($serial);
        fclose($file);
      }
    }else{
      $res=mysql_query($query, $this->linkid);
    }
    return $res;
  }

  function ping()
  {
    return mysql_ping($this->linkid);
  }

  function name()
  {
    return "MySQL";
  }

}

class query_mysql
{
  var $db = NULL;
  var $res = NULL;
  var $errno = NULL;
  var $query = NULL;
  var $NextRowNo = 0;

  function query_mysql($query, &$db)
  {
    $this->db = &$db;
    if ($query!="")
    {
      $this->query($query);
    }
  }

  function clear_cache($query){
    $filename = $this->db->cache_path.md5($query) . '.cache';
    unlink($filename);
  }

  function get_queries()
  {
    return $this->db->queries;
  }

  //функция запроса к базе
  function query($query,$cache=false)
  {
    if($this->db->global_cache==false) $cache=false;
    $this->db->queries[]=array('query'=>$query,'cache'=>$cache);

    $this->db->cache=$cache;
    if($cache==false) $this->db->queries_col++;
    $this->query = $query;
    $this->res   = $this->db->exec($query);
    $this->errno = $this->db->errno();
    if ($this->errno)
    {
      $msg = $this->db->error();
      $this->db->rollback();
      if ($this->db->debug && error_reporting())
      {
        trigger_error("SQL[{$this->errno}] {$msg}", E_USER_ERROR);
      }
      return false;
    }
    return (0==$this->errno);
  }

  //проверка "если есть результат"
  function is_result()
  {
    return is_resource($this->res);
  }

  //очистить результат выборки
  function free_result()
  {
    $ret = false;
    if ($this->is_result())
    {
      $ret = mysql_free_result($this->res);
    }
    $this->res = NULL;
    return $ret;
  }

  //количество строк в выборке
  function num_rows()
  {
    if (preg_match('/^SELECT/', $this->query)&&$this->db->cache){
      $filename = $this->cache_path.md5($query) . '.cache';
      $res=sizeof($this->db->result_data['data']);
    }else{
      $res=($this->is_result()? mysql_num_rows($this->res) : NULL);
    }

    return $res;
  }

  function num_fields()
  {
    return sizeof($this->db->result_data['fields']);
  }

  //переход к указаной строчке выборки
  function row_seek($row=0)
  {
    return ($this->is_result()? mysql_data_seek($this->res, $row) : NULL);
  }

  function field_seek($field)
  {
    return ($this->is_result()? mysql_field_seek($this->res, $field) : NULL);
  }

  //вернуть строку как асициативный массив
  function get_row($assoc=true)
  {
    if (preg_match('/^SELECT/', $this->query)&&$this->db->cache){
      if($assoc){
        $filename = $this->cache_path.md5($query) . '.cache';
        if (($this->NextRowNo + 1) > $this->num_rows()){
          return false;
        }
        for ($i = 0; $i < $this->num_fields(); $i++){
          $result[$this->db->result_data['fields'][$i]->name] =
          $this->db->result_data['data'][$this->NextRowNo][$i];
        }
        $this->NextRowNo++;
      }else{
        $this->NextRowNo=0;
        if (($this->NextRowNo+1) > $this->num_rows()){
          return false;
        }
        $this->NextRowNo++;
        $result=$this->db->result_data['data'][$this->NextRowNo - 1];
      }

      return $result;
    }else{
      $assoc = ($assoc? MYSQL_ASSOC : MYSQL_NUM);
      return ($this->is_result()? mysql_fetch_array($this->res, $assoc) : NULL);
    }
  }

  //получить только одну ячейку
  function get_cell()
  {
    $r = $this->get_row(false);
    return (is_array($r)? reset($r) : $r);
  }

  function get_col()
  {
    $col = array();
    if ($this->num_rows())
    {
      $this->row_seek(0);
      while ($r = $this->get_row(false))
      {
        if(is_array($r))
        {
          $col[] = reset($r);
        }
         else
        {
          return $r;
        }
      }
    }
    return $col;
  }


  function get_field()
  {
    return ($this->is_result()? mysql_fetch_field($this->res) : NULL);
  }

 //вся выборка как массив
  function get_allrows($field=NULL)
  {
    if (preg_match('/^SELECT/', $this->query)&&$this->db->cache){

      $this->NextRowNo=0;

      for($t = 0; $t < $this->num_rows(); $t++){
        for ($i = 0; $i < $this->num_fields(); $i++){
          $result[$t][$this->db->result_data['fields'][$i]->name] =
          $this->db->result_data['data'][$t][$i];
        }
        $this->NextRowNo++;
      }

      return $result;
    }else{
      $ret = array();
      if ($this->num_rows())
      {
        while ($r = $this->get_row()){
            if($field)
                $ret[]=$r[$field];
            else
            $ret[]=$r;
        }
      }
    }
    return $ret;
  }

  function format($query)
  {
    $args  = func_get_args();
    $query = call_user_func_array(array($this->db,"format"), $args);
    return $this->query($query,$cache);
  }

  function format_cache($query)
  {
    $args  = func_get_args();
    $query = call_user_func_array(array($this->db,"format"), $args);

    return $this->query($query,true);
  }
  
  function last_id()
  {
      return $this->db->last_id();
  }
  
  /**
   * Get row by id number
   * @param string $table table name
   * @param int $id id number
   * @return array
   */
  function z_getRowById($table, $id) 
  {
      $this->format("SELECT * FROM `%s` WHERE id = %d", $table, $id);
      $ret = $this->get_row();
      return $ret;
  }
  
  /**
   * Get all rows with pager
   * @param string $table table name
   * @param int $page page number
   * @param int $num number of items on page
   * @param sring $where
   * @param string $order
   * @return array
   */
  function z_get_allRowsWithPager($table, $page, $num, $where='1=1', $order = 'id DESC') 
  {
        $args = array();
        $this->query("SELECT SQL_CALC_FOUND_ROWS T.* "
                ." FROM ".$table." T"
                ." WHERE ".$where
                ." ORDER BY ".$order
                ." LIMIT " . ($page - 1) * $num . "," . $num);
        $args['items'] = $this->get_allrows();
        
        if($args['items'])
        {
            $this->query("SELECT FOUND_ROWS() as cnt");
            $all = $this->get_cell();
            $args['pages'] = ceil($all / $num);
        }
        else
        {
            $args['pages'] = 0;
        }
        
        return $args;
  }

}
?>