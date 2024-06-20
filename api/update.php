<?php

$post = json_decode(file_get_contents("php:://input"), true);

include_once('../init.php');
include_once("../classes/query_handler.php");

$GLOBALS['directory'] = "../local_data/";
$GLOBALS['archive_dir'] = "../local_data/archive/";

$GLOBALS['chunk_size'] = 10;

if (!isset($_SESSION['last_id'])) {
    $_SESSION['last_id'] = 0;
}

if (isset($_GET['limit'])) {
    $GLOBALS['chunk_size'] = $_GET['limit'];
}

$results = array();

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
    $results[$_GET['table']] = array();

    if (($data = $table->get_source()) === FALSE) {
        $results[$_GET['table']]["error"]  = $table->error;
        die(json_encode($results));   
    } else if ($table->set_data($data) === false) {
        $results[$_GET['table']]["error"]  = $table->error;
        die(json_encode($results));
    }

    // $diff = strtotime($table->get_local_update()) - strtotime(date("Y-m-d H:i:s"));
    // echo $diff;
    // echo "<br/>";

    // $years = abs(floor($diff/31536000));
    // $days = abs(floor(($diff-($years * 31536000))/86400));
    // $hours = abs(floor($diff/3600));
    // $mins = abs(floor($diff/60));
    // echo "<p>Time Passed: " . $years . " Years, " . $days . " Days, " . $hours . " Hours, " . $mins . " Minutes.</p>";
    // die();

    save_file($table->get_csv_string(), $table->get_id(), ".csv");
    $results[$_GET['table']]["csv"] = "successfully updated " . $table->get_name() . " csv local file.";
    save_file(json_encode($table->generate_json()), $table->get_id(), ".json");
    $results[$_GET['table']]['json'] = "successfully updated " . $table->get_name() . " json local file.";

    query_handler::set_local_update_time($_GET['table']);

} else {
    $results['info'] = array();
    // var_dump(query_handler::fetch_table_ids($_SESSION['last_id'], $GLOBALS['chunk_size']) === FALSE);
    
    if (($id_list = query_handler::fetch_table_ids($_SESSION['last_id'], $GLOBALS['chunk_size'])) !== FALSE) {
        // var_dump($id_list);

        $results['info']['total_updates'] = sizeof($id_list);
        $results['info']['chunk_size'] = $GLOBALS['chunk_size'];
        $results['info']['updated_ids'] = $id_list;
        if (sizeof($id_list) == 0) {
            $_SESSION['last_id'] = 0;
            header("Refresh:0"); 
            die();
        } 
        $results['info']['chunk_start_id'] = $id_list[0];

        foreach ($id_list as $id){
            $table = query_handler::fetch_table_by_id($id);
            $results[$id] = array();
            
            if (($data = $table->get_source()) === FALSE) {
                $results[$id]['error'] = $table->error;
                $_SESSION['last_id'] = $id;
                continue;
            } else if ($table->set_data($data) == FALSE) {
                $results[$id]["error"]  = $table->error;
                $_SESSION['last_id'] = $id;
                continue;
            }

            save_file($table->get_csv_string(), $table->get_id(), ".csv");
            $results[$id]["csv"] = "successfully updated " . $table->get_name() . " csv local file.";
            save_file(json_encode($table->generate_json()), $table->get_id(), ".json");
            $results[$id]['json'] = "successfully updated " . $table->get_name() . " json local file.";

            query_handler::set_local_update_time($id);
            $_SESSION['last_id'] = $id;
        }
    }

    $results['info']['chunk_end_id'] = $_SESSION['last_id'];
}

echo json_encode($results);

?>