<?php

include_once('account.php');
include_once('table.php');
// include_once('google_sheet_table.php');
include_once('encrypter.php');

class query_handler {
    static private $db;
    static private $user_table_name;
    static private $meta_table_name;
    static private $notes_table_name;
    static private $tag_table_name;
    static private $table_view_name;

    static private $table_search_term = "";
    static private $table_sorting = "";
    static private $tag_filter = array();
    static public $error = "";

    static function set_db($db) {
        self::$db = $db;
    }

    static function set_table_search_term($term) {
        self::$table_search_term = $term;
    }

    static function set_table_sorting($sorting) {
        if ($sorting == "") {
            return;
        }

        if ($sorting == "latest_up") {
            self::$table_sorting = " date_added DESC,";
        }

        if ($sorting == "oldest_up") {
            self::$table_sorting = " date_added,";
        }

        if ($sorting == "latest_mod") {
            self::$table_sorting = " last_updated DESC, ";
        }

        if ($sorting == "oldest_mod") {
            self::$table_sorting = " last_updated, ";
        }
    }

    static function add_tag_filter($tag) {
        self::$tag_filter[] = $tag;
    }

    static function set_meta_table($name) {
        self::$meta_table_name = $name;   
    } 

    static function set_account_table($name) {
        self::$user_table_name = $name;
    }

    static function set_note_table($name) {
        self::$notes_table_name = $name;
    }

    static function set_tag_table($name) {
        self::$tag_table_name = $name;
    }

    static function set_view_table($name) {
        self::$table_view_name = $name;
    }

    static function check_for_account($email) {
        $query = "SELECT * FROM " . self::$user_table_name . " WHERE email='" . $email . "';";

        if ($res = self::$db->query($query)) {
            if ($res->num_rows > 0) {
                return true;
            }
        }

        return false;
    }

    static function attempt_register($name, $email, $password) {
        $query = "SELECT id FROM " . self::$user_table_name . " WHERE email='" . $email . "';";

        if ($res = self::$db->query($query)) {
            if ($res->num_rows == 0) {
                $names = Utils::split($name, " ");

                $password = encryption::encrypt($password);

                $query = "INSERT INTO " . self::$user_table_name . " (email, password, name, surname) VALUES ('" . $email . "', '" . $password . "', '" . $names[0] . "', '" . $names[1] . "');";

                if ($res = self::$db->query($query)) {
                    return true;
                }

            }
        }

        return false;
    }

    static function attempt_login($email, $password) {
        $password = encryption::encrypt($password);
        $query = "SELECT * FROM " . self::$user_table_name. " WHERE email='". $email. "' AND password='" . $password . "';";

        if ($res = self::$db->query($query)) {
            return self::handle_login_res($res);
        }

        return false;
    }

    static function logout() {
        if (isset($_COOKIE['login_session']) || isset($_SESSION['users'])) {
            unset($_SESSION['user']);
            setcookie('login_session', '', time() - 3600, '/');
        }
    }

    static function handle_login_res($res) {
        if ($res->num_rows == 1) {
            $row = $res->fetch_assoc();
            $account = new Account();
            $account->set_data_from_row($row);
            $account->log_in();
            $token = Utils::generate_token($account->get_id());

            setcookie('login_session', $token, time() + 1209600, "/");
            $data = Utils::decode_token($token);
            $account->set_token($data['token']);
            self::update_user_token($data['id'], $data['token']);

            return $account;
        }  

        return false;
    }

    static function update_user_token($id, $token) {
        $query = "UPDATE " . self::$user_table_name . " SET token='" . $token . "' WHERE id='" . $id . "';";
        if ($res = self::$db->query($query)) {
            return true;
        }

        return false;
    }

    static function attempt_login_with_token() {

        if (!isset($_COOKIE['login_session'])) {
            return false;
        }

        $cookie = $_COOKIE['login_session'];
        $data = Utils::decode_token($cookie);
        $id = $data['id'];
        $token = $data['token'];

        $query = "SELECT * FROM " . self::$user_table_name. " WHERE id=". $id. " AND token='" . $token . "';";

        if ($res = self::$db->query($query)) {
            return self::handle_login_res($res);
        } 

        return false;
    }

    static function create_new_table($table) {
        $table->set_db_name();
        $query = "CREATE TABLE " . $table->get_db_name() . " (";
        
        foreach ($table->get_headings() as $key=>$value) {
            if ($key != 0) {
                $query .= ", ";
            }
            $query .= $value['name'] . " " . $value['type'] ;

        }

        $query .= ");";

        if ($res = self::$db->query($query)) {
            return self::insert_meta_data($table);
        }

        return false;
    }

    static function assign_tags($table) {
        $tags = $table->get_tags();

        if (!self::remove_table_tags($table->get_id())) {
            return false;
        }

        if (count($tags) <= 0) {
            return true;
        }
        $sql = "INSERT IGNORE INTO " . self::$tag_table_name . " (table_id, tag_id) VALUES ";
        foreach($tags as $tag) {
            $sql .= "(".$table->get_id().",".$tag->get_id()."), ";
           
        }

        $sql = substr($sql, 0, -2) . " ";


        if ($res = self::$db->query($sql)) {
            return true;
        }

        return false;
    }

    static function insert_meta_data($table) {
        if ($table->set_data($table->get_source()) === false) {
            return false;
            self::error = "Source could not be read. Please make sure the sheet is published";
        }
        $table->find_meta_data();

        $query = "INSERT INTO " . self::$meta_table_name . " (str_name, db_name, date_added,last_updated, source, upload_user_id, status, type, description, col_count, row_count, headings) VALUES ('" . $table->get_name() . "', '" . $table->get_db_name() . "', STR_TO_DATE('". $table->get_created_date()  ."', '%Y-%m-%d %H:%i:%s'),STR_TO_DATE('". $table->get_created_date()  ."', '%Y-%m-%d %H:%i:%s'), '".$table->get_source()."', ".$table->get_uploader_id().", '".$table->get_status()."', '".$table->get_type()."', '".Utils::check_quotes($table->get_description())."', ".$table->col_count. ", ".$table->row_count.", '".Utils::check_quotes($table->get_heading_string())."');";
       
        if ($res = self::$db->query($query)) {
            $table->set_id(self::$db->insert_id);
            return self::assign_tags($table);
        }
        
        self::error = "Could not insert meta data";
        return false;
    }


    static function update_meta($table) {
        $table->set_data($table->get_source());

        $table->find_meta_data();
        $query = "UPDATE " . self::$meta_table_name . " SET last_updated=CURRENT_TIMESTAMP, str_name='". $table->get_name()."', source='".$table->get_source()."', description='".Utils::check_quotes($table->get_description())."', row_count=".$table->row_count.", col_count=".$table->col_count.", headings='".Utils::check_quotes($table->get_heading_string())."' WHERE id=".$table->get_id().";";

        if ($res = self::$db->query($query)) {
            return self::assign_tags($table);
        }

        return false;
    }

    static function populate_table($table) {
        $headings = $table->get_headings();

        $query = "INSERT INTO " . $table->get_db_name() . " (";

        foreach ($headings as $key =>$value) {
            if ($key != 0) {
                $query .= ", ";
            }

            $query .= $value['name'];
        }

        $query .= ") VALUES";

        $data = $table->get_data();

        foreach ($data as $key=>$value) {

            if ($key != 0) {
                $query .= ", ";
            }

            $query .= "(";

            foreach ($value as $index=>$col) {
                if ($index != 0) {
                    $query .= ", ";
                }

                $query .= "'" . $col . "'"; // this will cause issuses when the type of the col has been changed to a ninteger
            }

            $query .= ")";
        }

        if ($res = self::$db->query($query)) {
            return true;
        }

        return false;
    }

    static function setup_table_from_row($row) {
        $table;
        if ($row['type'] == "google_sheet") {
            $table = new google_sheet_table();
            $table->set_has_headings(true);
        } else if ($row['type'] == "csv_file") {
            $table = new csv_table();
            $table->set_has_headings(true);
        } else {
            $table = new Table();
        }
        $table->set_id($row['table_id']);
        $table->set_created_date($row['date_added']);
        $table->set_name($row['str_name']);
        $table->set_db_name($row['db_name']);
        $table->set_last_updated($row['last_updated']);
        $table->set_source($row['source']);
        $table->set_uploader_id($row['upload_user_id']);
        $table->set_status($row['status']);
        $table->set_type($row['type']);
        $table->set_description($row['description']);
        $table->set_source_name($row['source_name']);
        $table->set_source_link($row['source_link']);
        $table->fetch_notes();
        return $table;  
    }

    static function fetch_table_by_id($id) {
        $query = "SELECT *, ".self::$meta_table_name.".id as table_id FROM " . self::$meta_table_name . " INNER JOIN " . self::$user_table_name . " ON ".self::$meta_table_name.".upload_user_id = ". self::$user_table_name . ".id AND " . self::$meta_table_name . ".id=" . $id;

        if ($res = self::$db->query($query)) {
            $row = $res->fetch_assoc();

            $table = self::setup_table_from_row($row);

            return $table;
        }

        return false;
    }

    static function load_tag_filters($col_name) {
        $string = "";
        foreach (self::$tag_filter as $key=>$value) {

            if ($key == 0) {
                $string .= " AND (";
            } else {
                $string .= " OR ";
            }
            $string .= $col_name . "=" . $value->get_id(). " ";
        }

        if ($string != "") {
            $string .= ") ";
        }
        return $string;
    }

    static function fetch_tables() {
        $query = "SELECT a.*, a.id as table_id, b.table_id as id FROM " . self::$meta_table_name . " AS a LEFT JOIN table_tags as b ON a.id = b.table_id WHERE a.str_name LIKE '%" . self::$table_search_term . "%' ";
        $query .= self::load_tag_filters("b.tag_id");
        // $query .= self::$table_sorting;
        
        $query .= " GROUP BY a.id";
        $query .= " ORDER BY ";
        $query .= self::$table_sorting;
        $query .= " count(a.id) DESC";

        

        if ($res = self::$db->query($query)) {
            $tables = array();
            while($row = $res->fetch_assoc()) {
                    $table = self::setup_table_from_row($row);
                    $tables[] = $table;
                self::populate_tags($table);
            }

            return $tables;
        }

        return false;

    }
    
    static function drop_table($table) {
        $id = $table->get_id();
        $query = "DROP TABLE " . $table->get_db_name() . ";";

        if ($res = self::$db->query($query)) {
            return true;
        }

        return false;
    }

    static function delete_table($id) {
        $query = "SELECT status from " . self::$meta_table_name . " WHERE id=$id";

        if ($res = self::$db->query($query)) {
            if ($row = $res->fetch_assoc()) {
                if ($row['status'] == "deleted") {
                    self::remove_table_meta($id);
                    return true;
                } else {
                   self::mark_as_deleted($id); 
                   return true;
                }

                
            }

            return false;
        }

        return false;
    }

    static function remove_table_tags($id) {
        $sql = "DELETE FROM table_tags WHERE table_id = " . $id;
        if ($res = self::$db->query($sql)) {
            return true;
        }
         return false;
    }

    static function remove_table_meta($id) {
        $query = "DELETE FROM " . self::$meta_table_name . " WHERE id=" . $id;
        if ($res = self::$db->query($query)) {
            
                return self::remove_table_tags($id);
        }

        return false;
    }

    static function mark_as_deleted($id) {
        $query = "UPDATE " . self::$meta_table_name . " SET status='deleted' WHERE id=" . $id;

        if ($res = self::$db->query($query)) {
            return true;
        }

        return false;
    }

    static function init_account_from_row($row) {
        $account = new Account();
        $account->set_data_from_row($row);
        return $account;
    }

    static function fetch_accounts() {
        $query = "SELECT id, email, name, surname from " . self::$user_table_name;
        if ($res = self::$db->query($query)) {
            $accounts = array();
            while($row = $res->fetch_assoc()) {
                $accounts[] = self::init_account_from_row($row);
            }

            return $accounts;
        }

        return false;
    }

    static function add_note($note) {
        $query = "INSERT INTO " . self::$notes_table_name . " (table_id, note, author, date) VALUES (".$note->get_table_id().", '".$note->get_note()."', ".$note->get_author() .", STR_TO_DATE('".$note->get_date()."', '%Y-%m-%d %H:%i:%s'))";

        if ($res = self::$db->query($query)) {
            return true;
        }

        return false;
    }

    static function fetch_notes($id) {
        $query = "SELECT * FROM " . self::$notes_table_name . " WHERE table_id=$id ORDER BY date DESC";

        if ($res = self::$db->query($query)) {
            $notes = array();

            while ($row = $res->fetch_assoc()) {
                $note = new Note();
                $note->set_data_from_row($row);
                $notes[] = $note;
            }

            return $notes;
        }

        return false;


    }

    static function fetch_tags() {
        $query = "select tags.*, count(table_tags.id) as freq from tags LEFT JOIN table_tags ON tags.id = table_tags.tag_id GROUP BY tags.id ORDER BY freq DESC";

        if ($res = self::$db->query($query)) {
            $tags = array();

            while ($row = $res->fetch_assoc()) {
                $tag = new Tag();
                $tag->set_data_from_row($row);
                $tags[] = $tag;
            }

            return $tags;
        }
    }

    static function populate_tags($table) {
        $sql = "SELECT * FROM table_tags INNER JOIN tags ON table_tags.tag_id = tags.id WHERE table_id=" . $table->get_id();

        if ($res = self::$db->query($sql)) {
            $tags = array();

            while ($row = $res->fetch_assoc()) {
                $tag = new Tag();
                $tag->set_data_from_row($row);
                $tags[] = $tag;
            }

            $table->set_tags($tags);
        }
    }

    static function add_tag($tag) {

        $query = "INSERT INTO tags (name) VALUES ('".$tag->get_name()."')";
        if ($res = self::$db->query($query)) {
            return true;
        }

        return false;
    }

    static function delete_tag_relations($id) {
        $sql = "DELETE FROM " . self::$tag_table_name . " WHERE tag_id = " . $id;

        if($res = self::$db->query($sql)) {
            return true;
        }

        return false;
    }

    static function update_tag($tag) {
        $sql = "UPDATE tags SET name='".$tag->get_name()."' WHERE id=".$tag->get_id();

        if ($res = self::$db->query($sql)) {
            return true;
        }

        return false;
    }

    static function delete_tag($id) {
        if (!self:: delete_tag_relations($id)) {
            return false;
        }

        $sql = "DELETE FROM tags WHERE id = " . $id;

        if ($res = self::$db->query($sql)) {
            return true;
        }

        return false;
    }

    static function create_view($id, $cols, $name_to, $value_to, $view_name) {
        
        $cols = Utils::to_csv($cols);

        $sql = "INSERT INTO ". self::$table_view_name . '(table_id, column_names, name_to, value_to, view_name) values (' . $id . ', "'. $cols.'", "' . $name_to .'", "' . $value_to . '", "'.$view_name.'")';

        if ($res = self::$db->query($sql)) {
            return true;
        }

        return false;
    }

    static function fetch_view_by_id($id) {
        $sql = "SELECT * FROM " . self::$table_view_name . " WHERE id=" . $id;

        if ($res = self::$db->query($sql)) {
            if ($row = $res->fetch_assoc()) {
                return $row;
            }
        }

        return false;
    }

    static function fetch_views_by_table_id($table_id) {
        $sql = "SELECT * FROM " . self::$table_view_name . " WHERE table_id=" . $table_id;
        $views = array();
        if ($res = self::$db->query($sql)) {
            while($row = $res->fetch_assoc()) {
                $views[] = $row;
            }

            return $views;
        }

        return false;   
    }

}

// class sql_table {
//     private $table; // the name of the table in the the database
//     private $db; // the database object that the table belongs to
//     private $headings = array(); // the list of columns in the table
//     public $error; // the error message that is returned if one occurs

//     // the constructor takes in a table name and a database object
//     // it then saves the data for the table and queries the database to get a list of column names
//     // this list is then saved in the headings array
//     function __construct($table, $db) {
//         $this->table = $table;  
//         $this->db = $db;

//         $sql = "SELECT `COLUMN_NAME`, 'DATA_TYPE'  FROM `INFORMATION_SCHEMA`.`COLUMNS`  WHERE `TABLE_SCHEMA`='". $this->db->dbname ."' AND `TABLE_NAME`='".  $this->table ."'";

//         if ($res = $this->db->query($sql)) {
//             $this->headings = array();
//             while ($row = $res->fetch_assoc()) {
//                 $this->headings[] = array('name' => $row['COLUMN_NAME'], 'type' => $row['DATA_TYPE']);
//             }
//         } else {
//             $this->error = $this->db->error;
//         }
//     }

//     // the insert function takes in a data array where the keys are the column headings and the values are the data
//     // e.g ['column_name'] => array(2) {['type'] => 'varchar', ['value'] => 'hello world'}
//     function insert_data($data_array) {
//         $sql = "INSERT INTO " . $this->table . " (";
//         $col_string = "(";
//         $val_string = "(";
//         foreach($data_array as $column=>$value) {
//             $col_string .= $column . ", ";

//         }
//     }
// }

?>