<?php

include_once('../init.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    if (query_handler::delete_tag($id)) {
        Utils::navigate('tags');
    }

}

?>