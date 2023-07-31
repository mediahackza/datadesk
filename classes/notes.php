<?php

class Note {
    private $id;
    private $date;
    private $author;
    private $note;
    private $table_id;


    function __construct() {

    }

    function set_data_from_row($row) {;
        $this->set_id($row['id']);
        $this->set_date($row['date']);
        $this->set_author($row['author']);
        $this->set_note($row['note']);
        $this->set_table_id($row['table_id']);

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

    function delete() {
        global $notes;
        $where = array('id' => $this->id);
        $notes->delete(array('id' => $this->get_id()));

        return $notes->query();
    }
}

?>