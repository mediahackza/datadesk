<?php

// this is the api for retriving data from datadesk in csv format
// it looks for a table id in the url and returns the csv data for that table
$post = json_decode(file_get_contents('php://input'), true); 
// the user can also specify that they want to download the csv file by including ?download in the url

include_once('../init.php'); // include initialisations
include_once('../classes/query_handler.php'); // include query handler class

    if (isset($_GET['table'])) { // if there is a table id in the url
        $table = query_handler::fetch_table_by_id($_GET['table']); // find the table from the database using the given id

        if ($table->set_data($table->get_source()) == false){
            die(json_encode(array('error' => $table->error)));
        }  // retrieve the data from the google api and save it as an array in the table object

        if (isset($_GET['view_id'])) {
            $v_id = $_GET['view_id'];

            $data = query_handler::fetch_view_by_id($v_id);
            $cols = Utils::split($data['column_names'], ',');
            $name_to = $data['name_to'];
            $value_to = $data['value_to'];
            $piv_d = $table->pivot_table($cols, $name_to, $value_to);
            $csv_array = $table->get_csv_string($piv_d['data'], $piv_d['headings']);

        } else {
            echo "I'm obstinant and will use OG data";
            $csv_array = $table->get_csv_string(); 
        }

        if (isset($_GET['download'])) { // if the user specifies that a download is required
            header("Content-type: text/csv"); // set the header to csv
            header("Content-Disposition: attachment; filename=".$table->get_name().".csv"); // set the filename to the table name and make it available for download
        }
        echo $csv_array; // print the csv data to the page for the user
    } else {
        echo "no table id set"; // if there is no table id in the url
    }

?>