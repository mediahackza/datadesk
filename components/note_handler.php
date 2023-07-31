<?php

function check_note_data() {
    if (!isset($_POST['table_id']) || $_POST['table_id'] == "") {
        return false;
    }
    if (!isset($_POST['author']) || $_POST['author'] == "") {
        return false;
    }

    if (!isset($_POST['note']) || $_POST['note'] == "") {
        return false;
    }
    
    $note = new Note();

    $note->set_table_id($_POST['table_id']);
    
    $note->set_date(date("Y-m-d H:i:s"));
    $note->set_author($_POST['author']);
    $note->set_note($_POST['note']);
    
    query_handler::add_note($note);


    return true;
}

if (isset($_POST['save_note'])) {

    if (check_note_data()) {

        unset($_POST['save_note']);

        // Utils::navigate("home");
    } 
}



?>