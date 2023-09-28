<?php
    include('conf.php');
    define( 'WP_MEMORY_LIMIT', '256M' );

    session_start();
    
    function find_base($site_base_directory) {
        $base = "";
        if (empty($_SERVER['HTTPS'])) {
            $base .= "http://";
        } else {
            $base .= "https://";
        }
        
        $base .= $_SERVER['SERVER_NAME'];

        if ($_SERVER['SERVER_NAME'] == 'localhost') {
            $base .=  ":" . $_SERVER['SERVER_PORT'];
        } 

        $base .= $site_base_directory;
        // $base = "http://localhost:8888/datadesk";

        return $base;
    }

    $base = find_base($site_base_directory);
    $GLOBALS['base'] = $base;

   
    
    include_once('classes/sql_table.php');
    include_once('classes/query_handler.php');
    include_once('classes/table_factory.php');

    $db = new database($host, $username, $password, $db_name);

    $GLOBALS['users'] = new sql_table($account_table_name, $db);
    $GLOBALS['tables'] = new sql_table($table_tracking_name, $db);
    $GLOBALS['tags'] = new sql_table($tags_name, $db);
    $GLOBALS['notes'] = new sql_table($note_table_name, $db);
    $GLOBALS['table_tags'] = new sql_table($tag_table_name, $db);
    $GLOBALS['bookmarks'] = new sql_table($bookmarks_name, $db);
    $GLOBALS['tf'] = new Table_Factory();

    include_once('classes/table.php');
    include_once('classes/utils.php');
    include_once('classes/notes.php');
    include_once('classes/tags.php');
    include_once('components/account_list.php');


    Utils::add_location('login', $base."/login");
    Utils::add_location('upload', $base."/upload");
    Utils::add_location('tags', $base."/tags");
    Utils::add_location('home', $base);
    Utils::add_location('welcome', $base."/welcome");
    Utils::add_location('bookmarks', $base."/bookmarks");
    Utils::add_location('collections', $base."/collections");
    Utils::add_location('trash', $base.'/trash');

    

    

    query_handler::set_db($db);
    query_handler::set_meta_table($table_tracking_name);
    query_handler::set_account_table($account_table_name);
    query_handler::set_note_table($note_table_name);
    query_handler::set_tag_table($tag_table_name);
    query_handler::set_view_table($table_view_name);

    
    
    // $base = "http://localhost:8888/datadesk";

    function user_obj() {
        if (gettype($_SESSION['user']) == 'object') {
            return $_SESSION['user'];
        } else {
            return unserialize($_SESSION['user']);
        }
    }
    
    // include_once("validate.php");
    

?>
