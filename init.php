<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
 

    include_once('conf.php');
    include_once('classes/sql_table.php');
    include_once('classes/query_handler.php');
    include_once('classes/utils.php');
    include_once('classes/notes.php');
    include_once('classes/tags.php');
    include_once('classes/table_factory.php');

    

    $db = new database($host, $username, $password, $db_name);

    query_handler::set_db($db);
    query_handler::set_meta_table($table_tracking_name);
    query_handler::set_account_table($account_table_name);
    query_handler::set_note_table($note_table_name);
    query_handler::set_tag_table($tag_table_name);
    query_handler::set_view_table($table_view_name);

    $users = new sql_table($account_table_name, $db);
    $tables = new sql_table($table_tracking_name, $db);
    $tags = new sql_table($tags_name, $db);
    $notes = new sql_table($note_table_name, $db);
    $table_tags = new sql_table($tag_table_name, $db);
    $tf = new Table_Factory();

    session_start();
    function find_base() {
        global $site_base_directory;
        $base = "";
        if (empty($_SERVER['HTTPS'])) {
            $base .= "http://";
        } else {
            $base .= "https://";
        }
        
        $base .= $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT']. $site_base_directory;
        // $base = "http://localhost:8888/datadesk";

        return $base;
    }

    $base = find_base();
    // $base = "http://localhost:8888/datadesk";

    function user_obj() {
        if (gettype($_SESSION['user']) == 'object') {
            return $_SESSION['user'];
        } else {
            return unserialize($_SESSION['user']);
        }
    }


    Utils::add_location('login', $base."/account/login.php");
    Utils::add_location('upload', $base."/upload/upload.php");
    Utils::add_location('tags', $base."/tags/index.php");
    Utils::add_location('home', $base);

?>