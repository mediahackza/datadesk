<?php
// this is the api for retriving data from datadesk in json format
// it looks for a table id in the url and returns the json data for that table

// the user can also specify that they want to download the json file by including ?download in the url

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('../init.php'); // include initialisations
include_once('../classes/query_handler.php'); // include query handler class

$post = json_decode(file_get_contents('php://input'), true); // allow for post data from js fetch request


    if (isset($_GET['table'])) { // if there is a table id in the url
        $table = query_handler::fetch_table_by_id($_GET['table']); // fetch the table from the database using the id in the url
        $json_array; // declare the json array
 // if the table is a googlesheet
            
            if ($table->set_data($table->get_source()) == false){
                echo $table->get_source();
                echo "something went wron trying o set the data";
                die(json_encode(array('error' => $table->error)));
            } // get the data from the google api and save it as an array in the table object
            if (isset($post['pivot']) && $post['pivot'] == true) { // if the user has requested a pivot
                $piv_cols = $post['pivot_cols']; // get the pivot columns from the post data
                $name_to = $post['name_to'];    // get the name to column from the post data
                $value_to = $post['value_to']; // get the value to column from the post data

                $piv_d = $table->pivot_table($piv_cols, $name_to, $value_to); // pivout the data using the post pivot details

                
                $json_array = $table->generate_json($piv_d['data'], $piv_d['headings']); // generate the json array from the pivoted data
            } else if (isset($_GET['view_id'])){ // if the user requests a previously saved pivot 
                $v_id = $_GET['view_id']; // get the pivot id to fetch details from the database 

                $data = query_handler::fetch_view_by_id($v_id); // fetch the view from the database using the id
                $cols = Utils::split($data['column_names'], ','); // split the column names into an array
                $name_to = $data['name_to']; // get the name to column from the database
                $value_to = $data['value_to']; // get the value to column from the database
                $piv_d = $table->pivot_table($cols, $name_to, $value_to); // pivot the data using the fetched details
                $json_array = $table->generate_json($piv_d['data'], $piv_d['headings']); // generate the json array from the pivoted data
            } else { // if the user has not requested a pivot
                $json_array = $table->generate_json(); // generate the json array from the data in the table object
            }

        if (isset($_GET['download'])) { // if the user has requested a download
            header("Content-type: text/json"); // set the header to json
            header('Content-Disposition: attachment; filename="'.$table->get_name().'.json"'); // set the filename to the table name and make it available for download
        }

        echo json_encode($json_array); // print the json array to the page for the user
    }

?>