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
            $this->set_type('csv_file');
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
            // $string = str_replace("\r", "\n", $string);
            $this->csv_string = $string;
            return $this->init_data();
        }

        function get_link() {
            global $base;
            return $base . $this->source;
        }

        function get_source() {
            $link = $this->get_link();
            $extension = pathinfo($link, PATHINFO_EXTENSION);

            if ($extension == "tsv") {
                $this->set_delimiter("\t");
            } else {
                $this->set_delimiter(",");
            }
            try {
                $data = file_get_contents($link);
            } catch (Exception $e) {
                $this->set_error($e->getMessage());
                return false;
            }

            // $this->set_delimiter($data);
            // $this->set_data($data);
            return $data;
        }

        function init_data() {
            
            $line_array = Utils::split($this->csv_string, "\n");
            $index = 0;

            foreach ($line_array as $index=>$line) {
                $line_array[$index] = str_replace("\r",'', $line);
                if ($line == "") {
                    array_splice($line_array, $index, 1);
                }
            }
            $this->data = array();
            $index = 0;
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
            return true;
        }

        function add_row($row) {
            // $row = str_replace("\r", ' ', $row);
            // echo $row . "<br/>";
            $temp = Utils::split($row, $this->delimiter, true);
            $this->data[] = $temp;
        }

        function set_has_headings($bool) {
            $this->has_headings = $bool;
        }

        function has_headings() {
            return $this->has_headings;
        }

        function get_csv_string($array = null, $headings = null) {

            if ($array != null && $headings != null) {
                return parent::get_csv_string($array, $headings);
            }
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