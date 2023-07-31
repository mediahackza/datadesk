<?php

header("Access-Control-Allow-Origin: *");

$host = ''; // the url of the database host
$username = ''; // the username that has access to mysql
$password = ''; // the password for the mysql
$db_name = '';



$table_tracking_name = "tables";
$account_table_name = "users";
$tags_name = "tags";
$note_table_name = "notes";
$tag_table_name = "table_tags";
$table_view_name = "table_view";


switch($_SERVER['SERVER_NAME']) {
    case 'localhost':
        $site_base_directory = "/datadesk";
        break;
    default:
        $site_base_directory = "";
        break;
}

// if ($db->connect_error) {
//     die("Connection failed: " . $db->connect_error);
// }
?>