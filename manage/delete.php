<?php

    include_once('../init.php');
    include_once('../classes/query_handler.php');

    if (isset($_POST['delete'])) {
        $table = query_handler::delete_table($_POST['delete']);
    }

    Utils::navigate('home');

?>