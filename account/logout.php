<?php
include_once('../init.php'); // include initialisations
include_once('../classes/query_handler.php'); // include query handler class


    user_obj()->logout();
    unset($_SESSION['user']);

Utils::navigate('home'); // return the the main page 


?>