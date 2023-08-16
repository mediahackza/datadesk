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

echo json_encode($output);

?>