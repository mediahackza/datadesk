<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// this extends the mysli class in order to keep the name of initialised database available to the query creator
class database extends mysqli {
    private $host;
    private $username;
    private $password;
    public $database;

    function __construct($host, $username, $password, $database) {

        
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        parent::__construct($this->host, $this->username, $this->password, $this->database);

        if ($this->connect_error) {
            die("Connection failed: " . $this->database->connect_error);
        }
    }

    function __destruct() {
        $this->close();
    }
}

class sql_table {
    public $table; // the name of the table in the the database
    public $db; // the database object that the table belongs to
    private $headings = array(); // the list of columns in the table
    public $error; // the error message that is returned if one occurs
    public $last_insert = 0; // the id of the last inserted row
    private $query_type;
    public $select_columns = array();
    private $on_columns = array();
    public $query = "";
    public $where_data = array();
    public $sorting_data = array();
    public $group_by_data = array();


    // the constructor takes in a table name and a database object
    // it then saves the data for the table and queries the database to get a list of column names
    // this list is then saved in the headings array
    function __construct($table, $db) {
        $this->table = $table;  
        $this->db = $db;

        // var_dump($db);

        $sql = "SELECT `COLUMN_NAME`, `DATA_TYPE` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='".  $this->db->database ."' AND `TABLE_NAME`='". $this->table  ."'";
        if ($res = $this->db->query($sql)) {
            $this->headings = array();
            while ($row = $res->fetch_assoc()) {
                $this->headings[$row['COLUMN_NAME']] = $row['DATA_TYPE'];
            }
        } else {
            $this->error = $this->db->error;
        }


    }

    function set_db($db) {
        $this->db = $db;
    } 

    // this takes in an array with a key of the column name and a value of an array
    // the innner array contains the value the solumn needs to match and the operator type
    // e.g. array('column_name' => array('value' => 'data', 'operator' => 'LIKE', 'join' => 'AND')));

    function clear_where() {
        $this->where_data = array();
    }


    function add_group_by($col_name) {
        $this->group_by_data[] = $col_name;
    }

    function set_where($where) {
        $this->where_data = $where;
    }

    function add_where($col_name, $value, $operator, $join = "AND") {
        $this->where_data[][$col_name] = array('value' => $value, 'operator' => $operator, 'join' => $join);
    }


    function add_sorting($col_name, $direction ="desc") {
        $this->sorting_data[$col_name] = $direction;
    }

    function clear_sorting() {
        $this->sorting_data = array();
    }

    function clear_group_by() {
        $this->group_by_data = array();
    }
    function group() {
        $string = "";
        foreach($this->group_by_data as $column) {
            $string .= $this->table . "." .$column . ", ";       
        }

        return rtrim($string, ", ");
    }

    function where() {
        $string = "";
        foreach($this->where_data as $i=>$where) {
            foreach($where as $column=>$data)
            switch($this->get_type($column)) {
                case false:
                    $this->error = "undefined column name: " . $column;
                    throw new Exception($this->error);
                    break;
                case 'varchar':
                    $string .= $this->table . "." .$column ." ".$data['operator']. " '" . $data['value'] ."' " . $data['join'] . " ";
                    break;
                case 'int':
                    $string .= $this->table . "." .$column . " " . $data['operator'] . " " . $data['value'] . " ".  $data['join'] . " ";
                    break;
                }    
                    if ($i == count($this->where_data) - 1) {
                        $string = rtrim($string, " " . $where[array_keys($where)[count(array_keys($where))-1]]['join'] . " ");

                    }
        }

        if ($string != "") {
            $string = "(" . $string . ")";
        }

        
        return $string;
    }



    function sorting() {
        $string = "";
        foreach($this->sorting_data as $column=>$direction) {
            if (strpos($column, 'count(') !== false) {
                $string .= substr_replace($column, $this->table . ".", 6, 0) . " " . $direction . ", ";
            } else {
                $string .= $this->table . "." .$column ." ".$direction. ", "; 
            }
               
            
            
        }

        return rtrim($string, ", ");
    }

    function select_string() {
        $string = '';

        foreach($this->select_columns as $column) {

            if (strpos($column, "count") !== false) {
                $string .= "count(".$this->table.".".substr($column, 6) . ", ";
                continue;
            }
            $string .= $this->table . "." . $column . ", ";
        }

        $string = rtrim($string, ", ");

        return $string;
    }

    function on_string() {
        return "";
    }

    function get_db() {
        return $this->db;
    }


    function columns($array) {
        $this->select_columns = $array;
    }

    function query() {
        if ($this->query == null) {
            $this->error = "no sql string";
            return false;
        }

        if ($res = $this->db->query($this->query)) {
            switch ($this->type) {
                case 'insert':
                    $this->last_insert = $this->db->insert_id;
                    return $this->db->insert_id;
                    break;
                case 'delete':
                    return true;
                    break;
                case 'select':
                    $data = array();
                    while($row = $res->fetch_assoc()) {
                        $data[] = $row;
                    }

                    return $data;
                    break;
            }
        } else {
            $this->error = $this->db->error;
            return false;
        }
    }

    // get type is given a column name and return the data type
    // saved in the list of headings
    // if nothing is found it returns false
    function get_type($column_name) {
        if (isset($this->headings[$column_name])) {
            return $this->headings[$column_name];
        } else {
            return false;
        }
    }

    function from() {
        return $this->table;
    }

    function get_col($name) {
        if (isset($this->headings[$name])) {
            return $this->table.".".$name;
        } else {
            return false;
        }
    }


 
    // the insert function takes in a data array where the keys are the column headings and the values are the data
    // e.g ['column_name'] => 'data value'
    // if the insert is successful it will return the id of the inserted row
    // if the insert fails it will return false
    function insert($data_array) {
        $this->type = 'insert';
        $sql = "INSERT INTO " . $this->table . " ";
        $col_string = "(";
        $val_string = "(";
        foreach($data_array as $column=>$value) {
            switch($this->get_type($column)) {
                case false:
                    $this->error = "undefined column name: " . $column;
                    throw new Exception($this->error);
                    break;
                case 'varchar':
                    $col_string .= $column . ",";
                    $val_string .= "'" . $value . "',";
                    break;
                case 'int':
                    $col_string .= $column . ",";
                    $val_string .= $value . ",";
                    break;
                }    
        }

        $col_string = rtrim($col_string, ",") . ")";
        $val_string = rtrim($val_string, ",") . ")";

        $sql .= $col_string . " VALUES " . $val_string;
        $this->query = $sql;
    }

    // the delete function take in a data array of column names and values
    // e.g ['column_name'] => 'data value'
    // constructs a delete query and executes it
    // if it is successful it returns true
    // if it fails it will return false
    function delete($data_array) {
        $this->type = 'delete';
        $sql = "DELETE FROM " . $this->table . " WHERE ";

        foreach($data_array as $column=>$value) {
            switch($this->get_type($column)) {
                case false:
                    $this->error = "undefined column name: " . $column;
                    throw new Exception($this->error);
                    break;
                case 'varchar':
                    $sql .= $column . "='" . $value . "' AND ";
                    break;
                case 'int':
                    $sql .= $column . "=" . $value . " AND ";
                    break;
                }    
        }

        $sql = rtrim($sql, " AND ");

        $this->query = $sql;
    }


    // the select function takes in a data array of columns that the user desires to return
    function select() {
        $this->type = 'select';
        $sql = "SELECT " . $this->select_string() . " FROM " . $this->from();
        $w = $this->where(); 
        if ($w != "") {
            $sql .= " WHERE " . $w;
        } 
        $g = $this->group();

        if ($g != "") {
            $sql .= " GROUP BY " . $g;
        }

        $s = $this->sorting();

        if ($s != '') {
            $sql .= " ORDER BY " . $s;
        }

        $this->query = $sql;

    }


}

class join_table extends sql_table{
    private $tables = array();
    private $col_string = "";
    private $join_type;
    private $on_columns = array();

    // the constructor takes in an array of sql table objects in order to query them with
    // a join
    // it takes in a type of join (inner, left, right, cross)
    //it takes in an array of keys to join on
    // e.g. 
    // $tables_keys = array([0] => array(
    //     '0' => 'table1_key',
    //     '1' => 'table2_key'
    // ))

    function __construct($type, $tables, $table_keys) {
        $this->db = $tables[0]->get_db();
        $this->join_type = $this->get_type_string($type);
        $this->tables = $tables;
        $this->on_columns = $table_keys;


    }

    function select_string() {
        $string = "";

        foreach($this->tables as $table) {
            $param = $table->select_string();
            if (strlen($param) > 0) {
                $string .= $table->select_string() . ",";
            }   
            
        }

        return rtrim($string, ",");
    }

    function from() {
        $string = $this->tables[0]->from();
        for ($i = 1; $i < count($this->tables); $i++) {
            $string .= " " . $this->join_type . " " . $this->tables[$i]->from() .  $this->on_string();
            
        }

        return rtrim($string, ",");
    }

    function on_string() {
        $on_string = " ON ";
        foreach($this->on_columns as $key=>$pair) {
            $on_string .= $pair[0] . "=" . $pair[1] . " AND ";
        } 

        return rtrim($on_string, "AND ");
    }

    function where() {
        $where_string = "";

        foreach($this->tables as $table) {
            if(count($table->where_data) > 0) {
                $where_string .= $table->where() . " AND ";
            }
            
        }

        return rtrim($where_string, "AND ");
    }

    function sorting() {
        $sorting_string  = "";

        foreach($this->tables as $table) {
            if(count($table->sorting_data) > 0) {
                $sorting_string .= $table->sorting() . ", ";
            }
            
        }

        return rtrim($sorting_string, ", ");
    }

    function group() {
        $group_string  = "";

        foreach($this->tables as $table) {
            if(count($table->group_by_data) > 0) {
                $group_string .= $table->group() . ", ";
            }
            
        }

        return rtrim($group_string, ", ");
    }

    
    function get_type_string($type) {
        switch($type) {
            case 'inner':
                return "INNER JOIN";
                break;
            case 'left':
                return "LEFT JOIN";
                break;
            case 'right':
                return "RIGHT JOIN";
                break;
            case 'full':
                return "FULL JOIN";
                break;
            default:
                return "JOIN";
                break;
        }
    }
    // takes in an array object fot columns
    // e.g []
    function select($ret_col = null, $where_array = null) {
        $sql = "SELECT ";
        
        $col_string = "";
        $this->type = 'select';

        // this uses a resursive to construct all the copullmns to be selected
        $ss = $this->select_string();

        $from_string = $this->from();

        $ss = rtrim($ss, ",");;

        $query = $sql . $ss . " FROM " . $from_string ;
        $w = $this->where();

        if ($w != "") {
            $query .= " WHERE " . $w;
        }

        $g = $this->group();

        if ($g != "") {
            $query .= " GROUP BY " . $g;
        }

        $s = $this->sorting();

        if ($s != '') {
            $query .= " ORDER BY " . $s;
        }
        
        $this->query = $query;
    }
}
?>