<?php

class Note {
    private $id;
    private $date;
    private $author;
    private $note;
    private $table_id;
    private $type;
    private $saved;


    function __construct() {
        $this->saved =false;
    }

    function set_data_from_row($row) {;
        $this->set_id($row['id']);
        $this->set_date($row['date']);
        $this->set_author($row['author']);
        $this->set_note($row['note']);
        $this->set_table_id($row['table_id']);

    }

    function set_saved($saved) {
        $this->saved = $saved;
    }

    function is_saved() {
        return $this->saved;
    }

    function set_table_id($id) {
        $this->table_id = $id;
    }

    function set_id($id) {
        $this->id = $id; 
    }

    function set_date($date) {
        $this->date =$date;
    }

    function set_author($author) {
        $this->author = $author; 
    }

    function set_note($note) {
        $this->note = $note;
    }

    function set_type($type) {
        $this->type = $type;
    }

    function get_type() {
        return $this->type;
    }

    function get_table_id() {
        return $this->table_id;
    }

    function get_note() {
        return $this->note;
    }

    function get_date() {
        return $this->date;
    }

    function get_author() {
        return $this->author;
    }

    function get_id() {
        return $this->id;
    }

    function save_note() {

        if ($this->saved) {
            return true;
        }
        global $notes;

        $data = [
            'date' => "STR_TO_DATE('".$this->get_date()."', '%Y-%m-%d %H:%i:%s')",
            'table_id' => $this->get_table_id(),
            'note' => $this->get_note(),
            'author' => $this->get_author(),
            'type' => $this->get_type()
        ];

            $notes->insert($data);  
        

        echo $notes->query;

        if ($res = $notes->query()) {
            $this->set_id($res);
            $this->set_saved(true);
            return true;
        }

        return false;

    }

    function delete() {
        if ($this->saved) {
            global $notes;
            $where = array('id' => $this->id);
            $notes->delete(array('id' => $this->get_id()));
            if ($notes->query()) {
                return true;
            }

            return false;
        }
        

        return true;
    }
}

?>