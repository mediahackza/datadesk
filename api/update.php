<?php

$post = json_decode(file_get_contents("php:://input"), true);

include_once('../init.php');
include_once("../classes/query_handler.php");

$GLOBALS['directory'] = "../local_data/";
$GLOBALS['archive_dir'] = "../local_data/archive/";


function save_file($data,$file_name, $ext) {
    $fp = fopen($GLOBALS['directory']. $file_name . $ext, "x");

    if ($fp === false) {
        $zip = new ZipArchive();
        $zip->open($GLOBALS['archive_dir'].$file_name."_archive.zip", ZipArchive::CREATE);
        $zip->addFile($GLOBALS['directory'] . $file_name . $ext, str_replace(" ", "_", date('Y-m-d_H_i_s')."_".$file_name.$ext));
        $zip->close();

        $fp = fopen($GLOBALS['directory']. $file_name . $ext, "w");
    }

    fwrite($fp, $data);
    fclose($fp);
}


if (isset($_GET['table'])) {
    $table = query_handler::fetch_table_by_id($_GET['table']);

    if ($table->set_data($table->get_source()) == false) {
        die(json_encode(array("error" => $table->error)));
    }

    save_file($table->get_csv_string(), $table->get_id(), ".csv");
    save_file(json_encode($table->generate_json()), $table->get_id(), ".json");
    
} else {
    if ($id_list = query_handler::fetch_table_ids()) {

    $results = array();
    // foreach ($id_list as $id){
    $table = query_handler::fetch_table_by_id($id_list[0]);
    // $results[$id] = array();
    
    if ($table->set_data($table->get_source()) == false) {
        die(json_encode(array("error" => $table->error)));
    }

    save_file($table->get_csv_string(), $table->get_id(), ".csv");
    save_file(json_encode($table->generate_json()), $table->get_id(), ".json");

    }
}

?>