<?php

include_once('csv_table.php');

    class csv_file_table extends csv_table {

        function __constructor() {
            parent::__constructor();
            $this->set_type("csv file");
            $this->set_source("local file");
            
        }

        //takes in a file as $data to vbe opened and read into a csv table
        function set_data($file_name) {

            

            if ($file_name == "") {
                return;
            }

            $file = fopen($file_name, "r");

            if (!$file) {
                return false;
            }


            parent::set_data(fread($file, filesize($file_name)));
        }
    }

?>