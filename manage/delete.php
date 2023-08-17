<?php

    if (isset($_POST['delete'])) {
        $table = query_handler::delete_table($_POST['delete']);
    }

    Utils::navigate('previous');

?>