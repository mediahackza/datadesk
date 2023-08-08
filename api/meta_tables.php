<?php 
// this is the api for retriving data from datadesk of the tables and tags that have been uplaoded in json format

include_once('../conf.php'); // include configuration
include_once('../init.php'); // include initialisations


include_once('../classes/query_handler.php');   // include query handler class

$output = array('tables' => array(), 'tags' => array()); // declare the output array
$sql = "select count(a.id) as count, c.id, c.name from tables as a JOIN table_tags as b ON a.id = b.table_id JOIN tags as c ON c.id = b.tag_id WHERE status = 'active' GROUP BY c.name, c.id;"; // get all the tags and the number of tables they are associated with

$tags->columns(array('id', "count(id) as count", 'name'));
$tables->clear_where();
$tables->where('status', 'active', '=');
$tags->clear_group_by();
$tags->add_group_by('id');
$tags->add_group_by('name');
$join_1 = new join_table('', array($tables, $table_tags), array(array($tables->get_col('id'), $table_tags->get_col('table_id'))));
$join_2 = new Join_table('', array($join_1, $tags), array(array($table_tags->get_col('tag_id'), $tags->get_col('id'))));

$join_2->select();
// echo $join_2->query;
if ($res = $join_2->query()) { // if the query is successful
    foreach($res as $row) { // save each tag from the database into the output array
        $output['tags'][] = $row;
    }
}

$tables->clear_where();
$tables->columns(array('*'));
$tables->where('status', 'active', '=');
$tables->clear_sorting();
$tables->add_sorting('id', 'DESC');
$tags->columns(array('id as tag_id', 'name as tag_name'));
$table_tags->columns(array());
$tags->clear_group_by();
// $tag->clear_sorting();
// $tags->add_sorting('id', 'DESC');

$table_join_tags_mid = new join_table('', array($tables, $table_tags), array(array($tables->get_col('id'), $table_tags->get_col('table_id'))));
$table_join_tags = new join_table('', array($table_join_tags_mid, $tags), array(array($table_tags->get_col('tag_id'), $tags->get_col('id'))));
$table_join_tags->select();

// echo $table_join_tags->query;

// echo $table_join_tags->query;

if ($res = $table_join_tags->query()) {
    $temp_table = $res[0];
    for ($i =0; $i < count($res); $i++) {

        if ($res[$i]['id'] == $temp_table['id']) {
            $temp_tag = array('id' => $res[$i]['tag_id'], 'name' => $res[$i]['tag_name']);
            $temp_table['tags'][] = $temp_tag;
        } else {
            unset($temp_table['tag_id']);
            unset($temp_table['tag_name']);
            $notes->columns(array('*'));
            $notes->clear_where();
            $notes->add_where('table_id', $temp_table['id'], '=');
            $notes->select();

            if ($note_res = $notes->query()) {
                $temp_table['notes'] = $note_res;
            }
            $temp_table['json_link'] = $base . "/api/json.php?table=" . $temp_table['id'];
            $temp_table['csv_link'] = $base . "/api/csv.php?table=" . $temp_table['id'];
            $output['tables'][] = $temp_table;
            $temp_table = $res[$i];

            
        }
    }

}

// if ($res = $table_join_tags->query()) {
//     // var_dump($res);
//     // echo json_encode($res);
//     foreach($res as $row) {
//         // echo json_encode($row);
//         $tags->clear_where();
//         $table_tags->clear_where();
//         $tags->columns(array('*'));
//         $table_tags->add_where('table_id', $row['id'], '=');
//         $join = new join_table('right', array($tags, $table_tags), array(array($tags->get_col('id'), $table_tags->get_col('tag_id'))));

//         $join->select();

        
//         $row['tags'] = $join->query();

//         $output['tables'][] = $row;
//     }
// }

// echo json_encode($output); // return the output array as json
// $sql = "SELECT * FROM tables where status='active'"; // get all the tables from the database that are publicly available
// if ($res = $db->query($sql)) { // if the query is successful
//     while ($row = $res->fetch_assoc()) { // for each table item from the database

//         $tags = array(); // declare ths table's array of tags
//         // and fetch the tags associated with the table by id
//         $sql =  "SELECT a.* from tags as a RIGHT JOIN table_tags as b ON b.tag_id = a.id WHERE b.table_id = " . $row['id'];
        
//         if ($inner_res = $db->query($sql)){ // if tags fetch is successful
//             while ($inner_row = $inner_res->fetch_assoc()) {
//                 $tags[] = $inner_row; // save each tag to the table's array of tags
//             }
//         }
//         $row['tags'] = $tags; // save the array of tags to the table item
//         $notes = array();
//         $sql = "SELECT * FROM notes WHERE table_id = " . $row['id'];

//         if ($inner_res = $db->query($sql)) {
//             while ($inner_row = $inner_res->fetch_assoc()) {
//                 $notes[] = $inner_row;
//             }
//         }

//         $row['notes'] = $notes;
//         $row['json_link'] = $base . "/api/json.php?table=" . $row['id'];
//         $row['csv_link'] = $base . "/api/csv.php?table=" . $row['id'];
//         $output['tables'][] = $row;
//     }
// }

echo json_encode($output);

?>