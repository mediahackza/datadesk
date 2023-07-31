<?php
    include_once('csv_table.php');

    class google_sheet_table extends csv_table {

        function __constructor() {
            parent::__constructor();
        }

        function get_link() {
            return $this->source;
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