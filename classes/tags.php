<?php

class Tag {
    private $name;
    private $id;
    private $active;

    function __construct() {
        $this->active = false;
    }

    function set_data_from_row($row) {
        $this->set_name($row['name']);
        $this->set_id($row['id']);
    }

    function get_name() {
        return $this->name;
    }

    function set_name($name) {
        $this->name = $name;
    }

    function set_id($id) {
        $this->id = $id;
    }

    function get_id() {
        return $this->id;
    }

    function toggle_active() {
        $this->active = !$this->active;
    }

    function make_inactive() {
        $this->active = false;
    }

    function is_active() {
        return $this->active;
    }
    
}

?>