<?php

include('google_sheet_table.php');

    class Table_Factory {
        private $types;
        function __construct() {
            $this->types = array(
                'google_sheet' => function ($row_data) {
                    $new_table = new google_sheet_table();
                    $new_table->set_meta_from_row($row_data);
                    return $new_table;
                },
                'csv_file' => function ($row_data) {
                    $new_table = new csv_table();
                    $new_table->set_meta_from_row($row_data);
                    return $new_table;
                }
            );
        } 

        function add_type($type, $callback) {
            $this->types[$type] = $callback;
        }

        function create_table($row_data) {
            foreach($this->types as $type => $callback) {
                if ($row_data['type'] == $type) {
                    return $callback($row_data);
                }
            }
        }
    }


?>