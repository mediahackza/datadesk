<?php

// this is the api for retriving data from datadesk in csv format
// it looks for a table id in the url and returns the csv data for that table

// the user can also specify that they want to download the csv file by including ?download in the url

include_once('../init.php'); // include initialisations
include_once('../classes/query_handler.php'); // include query handler class

    if (isset($_GET['table'])) { // if there is a table id in the url
        $table = query_handler::fetch_table_by_id($_GET['table']); // find the table from the database using the given id

            $table->set_data($table->get_source()); // retrieve the data from the google api and save it as an array in the table object
            $csv_array = $table->get_csv_string(); // get the csv string from the table object
            echo $csv_array;

        if (isset($_GET['download'])) { // if the user specifies that a download is required
            header("Content-type: text/csv"); // set the header to csv
            header("Content-Disposition: attachment; filename=".$table->get_name().".csv"); // set the filename to the table name and make it available for download
        } else {
            $csv_array = str_replace("\n","<br/>", $csv_array); // otherwise replace the new line characters with html line breaks
        }
        echo $csv_array; // print the csv data to the page for the user
    } else {
        echo "no table id set"; // if there is no table id in the url
    }

?>