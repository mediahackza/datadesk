<?php

function check_note_data() {
    $table = $GLOBALS['table'];
    if (!isset($_POST['table_id']) || $_POST['table_id'] == "") {
        $_SESSION['note_error'] = "NO table selected";
        return false;
    }

    if (!isset($_POST['author']) || $_POST['author'] == "") {
        $_SESSION['note_error'] = "No author set";
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
    $note->set_type($_POST['type']);


    
    $table->add_note($note);
    $GLOBALS['table'] = $table;
    return true;
}

if (isset($_POST['save_note'])) { // if save not ebutton was clicked
    if (check_note_data()) { // check the note data's validity

        unset($_POST['save_note']); 

        // Utils::navigate("home");
    } 
}



?>