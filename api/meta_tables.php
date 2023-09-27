<?php 
// this is the api for retriving data from datadesk of the tables and tags that have been uplaoded in json format

include_once('../conf.php'); // include configuration
include_once('../init.php'); // include initialisations
$post = json_decode(file_get_contents('php://input'), true);

include_once('../classes/query_handler.php');   // include query handler class

$output = array('tables' => array(), 'tags' => array()); // declare the output array

$tables = $GLOBALS['tables'];
$table_tags = $GLOBALS['table_tags'];
$tags = $GLOBALS['tags'];

if (isset($post['verbose']) || isset($_GET['verbose'])) {
    $verbose = true;
    $tables->columns(array('*', 'published_date as source_published_date'));
} else {
    $verbose = false;
    $tables->columns(array('id', 'str_name', 'description'));
}

$tables->clear_where();
$tables->add_where('status', 'active', '=');

if (isset($post['id'])) {
    $tables->add_where('id', $post['id'], '=');
}

if (isset($_GET['id'])) {
    $tables->add_where('id', $_GET['id'], '=');
}


$tables->select();

// echo $tables->query;


if ($res = $tables->query()) {
    // foreach($res as $key => $value) {
    //     $tags->columns('*');
    //     $tags->clear_where();
    //     $tagble_tags->clear_where();
    //     $table_tags->add_where('table_id', $res['id'], '=');

    //     $join = new join_table('left', array($tables, $table_tags),array(array($tables->get_col('id'), $table_tags->get_col('table_id'))));
    //     $join->select();

    //     if($res = $join->query()) {

    //     }
    // }
        if ($verbose) {
            foreach ($res as $t) {
                $post['id'] = $t['id'];
                include('meta_notes.php');
                $t['notes'] = $out_notes;
                $t['citing_notes'] = $out_citing_notes;
                $t['data_notes'] = $out_data_notes;
                include('meta_tags.php');
                $t['tags'] = $out_tags;

                $output['tables'][] = $t;
            }
        } else {
            $output['tables'] = $res;
        }
}

$tags->clear_where();
$tags->columns(array('*'));
$tags->select();

if ($res = $tags->query()) {
    $output['tags'] = $res;
}

echo json_encode($output);

?>