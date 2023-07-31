<?php 
// this is the api for retriving data from datadesk of the tables and tags that have been uplaoded in json format

include_once('../conf.php'); // include configuration
include_once('../init.php'); // include initialisations


include_once('../classes/query_handler.php');   // include query handler class

$output = array('tables' => array(), 'tags' => array()); // declare the output array
$sql = "select count(a.id) as count, c.id, c.name from tables as a JOIN table_tags as b ON a.id = b.table_id JOIN tags as c ON c.id = b.tag_id WHERE status = 'active' GROUP BY c.name, c.id;"; // get all the tags and the number of tables they are associated with

if ($res = $db->query($sql)) { // if the query is successful
    while ($row = $res->fetch_assoc()) { // save each tag from the database into the output array
        $output['tags'][] = $row;
    }
}
$sql = "SELECT * FROM tables where status='active'"; // get all the tables from the database that are publicly available
if ($res = $db->query($sql)) { // if the query is successful
    while ($row = $res->fetch_assoc()) { // for each table item from the database

        $tags = array(); // declare ths table's array of tags
        // and fetch the tags associated with the table by id
        $sql =  "SELECT a.* from tags as a RIGHT JOIN table_tags as b ON b.tag_id = a.id WHERE b.table_id = " . $row['id'];
        
        if ($inner_res = $db->query($sql)){ // if tags fetch is successful
            while ($inner_row = $inner_res->fetch_assoc()) {
                $tags[] = $inner_row; // save each tag to the table's array of tags
            }
        }
        $row['tags'] = $tags; // save the array of tags to the table item
        $notes = array();
        $sql = "SELECT * FROM notes WHERE table_id = " . $row['id'];

        if ($inner_res = $db->query($sql)) {
            while ($inner_row = $inner_res->fetch_assoc()) {
                $notes[] = $inner_row;
            }
        }

        $row['notes'] = $notes;
        $row['json_link'] = $base . "/api/json.php?table=" . $row['id'];
        $row['csv_link'] = $base . "/api/csv.php?table=" . $row['id'];
        $output['tables'][] = $row;
    }
}

echo json_encode($output);

?>