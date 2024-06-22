<?php
    include_once('csv_table.php');
    set_error_handler(
        function ($severity, $message, $file, $line) {
            throw new ErrorException($message, $severity, $severity, $file, $line);
        }
    );
    
    


    class google_sheet_table extends csv_table {

        function __construct() {
            parent::__construct();
            $this->set_type('google_sheet');
        }

        function get_link() {
            return $this->source;
        }

        function set_source($link) {
            $pos = strripos($link, "/");
            
            
            $params = substr($link, $pos+1);
            $temp_link = substr($link, 0, $pos+1);
            if (strpos($params, "?") != false) {
                $params = substr($params, strpos($params, "?")+1);
            } else {
                $params = substr($params, strpos($params, "#")+1);
            }
            $arr_params = Utils::split($params, "&");
            $id = "";
            foreach($arr_params as $p) {
                $arr_p = Utils::split($p, "=");
                if ($arr_p[0] == "gid") {
                    $id = $arr_p[1];
                    break;
                }
            }
            if (strpos($link, "edit") != false) {
                $this->source = $temp_link . "edit#gid=" . $id;
            } else {
                $this->source = $temp_link . "pub?gid=" . $id;
            }
            
        }

        function set_data($link) {

            $link_csv = $this->check_sheet_id($link);
            $link_tsv = $link_csv;

            // $pos = strripos($link, "/");
            // $link_csv = substr($link, 0, $pos+1);
            // $this->set_source($link_csv);
            $link_csv .= "&output=csv";
            $link_tsv .= "&output=tsv";

            $data = file_get_contents($link_csv);

            $this->error = "Failed to retrieve data from source";

            if ($data == FALSE) {
                return FALSE;
            }

            restore_error_handler();
            $this->set_delimiter(",");
            
            parent::set_data($data);
            if ($this->check_lines()) {
                
                return true;
            }

            $data = file_get_contents($link_tsv);
            $this->set_delimiter("\t");
            
            parent::set_data($data);
            if ($this->check_lines()) {
                
                return true;
            }


            return false;
        }

        function check_sheet_id($string) {
            $string = str_replace("edit", "pub", $string);
            $string = str_replace("#", "?", $string);
            return $string;

        }

        function get_data_meta() {

        }

        function get_source() {
            return $this->source;
        }

        function check_lines() {
            $l = count($this->get_headings());
            foreach($this->data as $key=>$value) {
                if (count($value) != $l) {
                    return false;
                }
            }

            return true;
        }
    }


?>