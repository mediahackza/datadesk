<?php

    include_once('utils.php');

    class table {
        private $id;
        private $name;
        private $headings;
        private $data;
        private $db_name;
        private $date_created;
        private $last_update;
        private $prefix = "dd_";
        private $uploader_id;
        private $type;
        private $status;
        private $description;
        public $source;
        public $error;
        private $source_name;
        private $source_link;

        private $notes = array();
        private $tags = array();

        function get_link() {
            return $this->source;
        }

        function pivot_table($cols, $name_to, $value_to) {
        

            $old_data = $this->get_data();
            $new_data = array();
            $index_array = array();
            $headings = $this->headings;

            foreach($headings as $index=>$heading) {
                if (in_array($heading['name'], $cols)) {
                    $index_array[] = array('index' =>$index, 'name' => $heading['name']);
                    unset($headings[$index]);
                }
            }
            
            $headings[] = array('name' => $name_to, 'type' => 'VARCHAR(255)');
            $headings[] = array('name' => $value_to, 'type' => 'VARCHAR(255)');


            foreach($old_data as $key=>$value) {
                foreach($index_array as $col) {
                    $temp_row = $value;
                    $temp_row[] = $col['name'];
                    $temp_row[] = $temp_row[$col['index']];
                    foreach($index_array as $col) {

                        unset($temp_row[$col['index']]);
                    }

                    $new_data[] = $temp_row;
                }

                

            }

            return array('data' => $new_data, 'headings' => $headings);
        }

        function __construct() {
            $this->name = "";
            $this->db_name = "";
            // $this->date_created = date("Y-m-d H:i:s");
            $this->status = "active";
            $this->source = "";
        }

        function get_heading_string() {
            $heading_string = "";
            foreach($this->get_headings() as $key=>$h) {
                $heading_string .= $h['name'] .  ",";
            } 

            return rtrim($heading_string, ",");
        }

        function find_meta_data() {
            $this->col_count = count($this->get_headings());
            $this->row_count = count($this->get_data());

        }

        function set_created_date($date) {
            if (utils::validateDate($date, "Y-m-d")  || utils::validateDate($date, "Y-m-d H:i:s")) {
                $this->date_created = date($date);
                return;
            }

        }

        function set_tags($tags) {
            $this->tags = $tags;
        }

        function get_source_name() {
            return $this->source_name;
        }

        function get_source_link() {
            return $this->source_link;
        }

        function set_source_name($name) {
            $this->source_name = $name;
        }

        function set_source_link($link) {
            $this->source_link = $link;
        }

        function get_id() {
            return $this->id;
        }

        function add_tag($tag) {

            $this->tags[] = $tag;
        }

        function remove_tag($tag_name) {
            $key = array_search($this->tags);

            if (!$key) {
                return;
            }

            array_splice($this->tags, $key, 1);
            
        }

        function get_tags() {
            return $this->tags;
        }

        function fetch_notes() {
            global $notes;
            $notes->columns(array('*'));
            $notes->clear_where();
            $notes->add_where("table_id", $this->id, '=');
            $notes->select();
            if ($res = $notes->query()) {
                foreach($res as $row) {
                    $note = new Note();
                    $note->set_data_from_row($row);
                    $this->notes[] = $note;
                }
            }
        }

        function fetch_tags() {
            global $tags, $table_tags;
            $tags->columns(array('*'));
            $table_tags->columns(array('table_id'));
            $table_tags->clear_group_by();
            $join = new join_table('left', array($tags, $table_tags), array(array($tags->get_col('id'), $table_tags->get_col('tag_id'))));
            $table_tags->clear_where();
            $table_tags->add_where("table_id", $this->id, '=');
            $join->select();
            if ($res = $join->query()) {
                foreach($res as $row) {
                    $tag = new Tag();
                    $tag->set_data_from_row($row);
                    $this->tags[] = $tag;
                }
            }
        }

        function set_notes($notes) {
            $this->notes = $notes;
        }

        function get_notes() {
            return $this->notes;
        }

        function get_delimiter() {
            return $this->delimiter;
        }

        function set_description($desc) {
            $this->description = $desc;
        }

        function get_description() {
            return $this->description;
        }

        function set_id($id) {
            $this->id = $id;
        }

        function set_update($update) {
            $this->last_update = $update;
        }

        function set_last_updated($date) {
            if (utils::validateDate($date)) {
                $this->last_update = date($date);
                return;
            }
        }

        function generate_json($array = null, $headings = null) {

            if ($array == null) {
                $array = $this->get_data();
            }
            if ($headings == null) {
                $headings = $this->get_headings();
            }
            

            $json_array = array();
            $temp = $array;
            foreach($temp as $key=>$value) {
                $json_array[] = Utils::get_json_object($value, $headings);
            }
            return $json_array;
        }

        function count_cols() {
            return count($this->headings);
        }

        function set_status($status) {
            $this->status = $status;   
        }

        function get_update() {
            return $this->last_update;
        }

        function get_created_date() {
            return $this->date_created;
        }

        function set_data($array) {
            // if ()
            $this->data = $array;
        }

        function get_data() {
            return $this->data;
        }

        function set_db_name() {
            $new_name = $this->name;
            $this->db_name = $this->prefix . $new_name;
            $this->db_name .= "_".$this->date_created;

            $this->db_name = Utils::check_chars($this->db_name);
        }

        function set_type($type){
            $this->type = $type;
        }

        function set_source($source) {
            $this->source = $source;
        } 

        function get_source() {
            return $this->source;
        }

        function get_status() {
            return $this->status;
        }

        function get_type() {
            return $this->type;
        }

        function set_uploader_id($id) {
            $this->uploader_id = $id;
        }

        function get_uploader_id() {
            return $this->uploader_id;
        }

        function get_name() {
            return $this->name;
        }

        function get_db_name() {
            return $this->db_name;
        }

        function set_name($name) {
            $this->name = $name;
            // $this->set_db_name();
        }

        // this is used to set the headings of a table
        // it takes in:
        //     $array - an array of heading array objects
        //      which each contain ['name'] and ['type'] values
        // returns null
        function set_headings($array) {
            $this->headings = $array;
        }

        // returns an array of heading objects
        // each heading array contains:
        //   ['name'] - the name of the heading
        //   ['type'] - the type of the heading, e.g. VARCHAR(255)
        
        function get_headings() {
            return $this->headings;
        }
        
         // used to assign a type to each heading name in an array an complete the heading object
        // takes in no extra informatin but $this->headings must be initialised at leasts
        // heading array object needs to be an array of arrays where:
        //      ['name'] is set to a value in each sub array
        // 
        function set_heading_types() {
            $temp = array();
            foreach ($this->headings as $array) {
                $array['type'] = 'VARCHAR(255)';
                $temp[] = $array;

            }

            $this->headings = $temp;
        }


        function set_meta_from_row($row) {
            if ($row['type'] == "google_sheet") {
                $this->set_has_headings(true);
            } else {
            }
            $this->set_id($row['id']);
            $this->set_created_date($row['date_added']);
            $this->set_name($row['str_name']);
            $this->set_db_name($row['db_name']);
            $this->set_last_updated($row['last_updated']);
            $this->set_source($row['data_source']);
            $this->set_uploader_id($row['upload_user_id']);
            $this->set_status($row['status']);
            $this->set_type($row['type']);
            $this->set_description($row['description']);
            $this->set_source_name($row['source_name']);
            $this->set_source_link($row['source_link']);
            $this->fetch_notes(); 
            $this->fetch_tags();
        }

    }

?>