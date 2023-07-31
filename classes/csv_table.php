<?php

    include_once("table.php");
    

    class csv_table extends table {
        private $csv_string;
        private $delimiter;
        private $headings;
        private $has_headings;

        function __construct() {
            parent::__construct();
            $this->csv_string = "";
            $this->delimiter = ",";
        }
        

        // set a sub string as the delimiting string in the csv data
        // takes iN:
        //     $string - the string to use as the delimiter
        //  returns:
        //    null
        function set_delimiter($delimiter) {
            $this->delimiter = $delimiter;
        }

    

        // returns a string that represents the delimiter
        function get_delimiter() {
            return $this->delimiter;
        }

        // set the data of the csv
        // takes in:
        //    $string - the string to use as the csv data
        // returns:
        //   null
        function set_data($string) {
            $string = str_replace("\r", "", $string);
            $this->csv_string = $string;
            $this->init_data();
        }

        function get_link() {
            global $base;
            return $base . $this->source;
        }

        function get_source() {
            global $base;
            $link = $this->get_link();

            $data = file_get_contents($link);

            // $this->set_delimiter($data);
            // $this->set_data($data);
            return $data;
        }

        function init_data() {
            
            $line_array = Utils::split($this->csv_string, "\n");
            $index = 0;

            foreach ($line_array as $index=>$line) {
                if ($line == "") {
                    array_splice($line_array, $index, 1);
                }
            }
            $this->data = array();
            if ($this->has_headings) {
                $this->set_headings($line_array[0]);
                $index = 1;
            } else {
                $count = count(Utils::split($line_array[0], $this->delimiter));
                $this->generate_headings($count);
            }

            for ($i = $index; $i < count($line_array); $i++) {
                $this->add_row($line_array[$i]);
            }

        }

        function add_row($row) {
            $temp = Utils::split($row, $this->delimiter);
            $this->data[] = $temp;
        }

        function set_has_headings($bool) {
            $this->has_headings = $bool;
        }

        function has_headings() {
            return $this->has_headings;
        }

        function get_csv_string() {
            return $this->csv_string;
        }

        function get_data() {
            return $this->data;
        }

        // this is used to generate a templet set of headings for a csv string
        // it takes in:
        //      cout - the number of heading required
        // it returns:
        //     null
        // but it will automatically set the headings of the table with the generated headings
        function generate_headings($count) {
            $string = "";
            for ($i =0; $i < $count; $i++) {
                $string .= "column_".$i.",";
            }

            $string = substr($string, 0, -1);

            $this->set_headings($string);
        }


        // this is used to set the array of headings for the table from a string
        // it takes in:
        //     $string - one line from a csv string of data which represents the headings
        // it returns:
        //     null
        function set_headings($string) {
            $headings_name_array = Utils::split($string, $this->delimiter);
            
            $heading_array = array();

            foreach ($headings_name_array as $heading_name) {
                $heading_array[] = array('name' => $heading_name);
            }

            $this->headings = $heading_array;
            parent::set_headings($heading_array);
            parent::set_heading_types();
        }


       
    }

?>