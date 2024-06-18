<?php

$post = json_decode(file_get_contents("php:://input"), true);

include_once('../init.php');
include_once("../classes/query_handler.php");

if ($id_list = query_handler::fetch_table_ids()) {

    $results = array();
    // foreach ($id_list as $id){
        $table = query_handler::fetch_table_by_id($id_list[0]);
        // $results[$id] = array();
        
        if ($table->set_data($table->get_source()) == false) {
            json_encode(array("error" => $table->error));
        }

        // $csv_array = $table->get_csv_string();
        // echo $csv_array;

        $directory = "../local_data/";
        $file_name = str_replace(" ", "_", $table->get_id()) . ".csv";

        $fp = fopen($directory . $file_name, "x");
        
        if ($fp === false) {

            $zip = new ZipArchive();
            $zip->open("../local_data/archive/".$table->get_id()."_archive.zip", ZipArchive::CREATE);
            $zip->addFile($directory . $file_name, str_replace(" ", "_", date('Y-m-d_H_i_s')."_".$file_name));
            $zip->close();

            $fp = fopen($directory . $file_name, "w");
        }

        fwrite($fp, $table->get_csv_string());
        fclose($fp);

        $file_name = $table->get_id(). ".json";

        $fp = fopen($directory . $file_name, "x");

        if ($fp === false) {
            $zip = new ZipArchive();
            $zip->open("../local_data/archive/".$table->get_id()."_archive.zip", ZipArchive::CREATE);
            $zip->addFile($directory . $file_name, str_replace(" ", "_", date('Y-m-d_H_i_s')."_".$file_name));
            $zip->close();

            $fp = fopen($directory . $file_name, "w");
        }

        fwrite($fp, json_encode($table->generate_json()));
        fclose($fp);
        // fopen("")
        // die("welp");
    // }

}

?>