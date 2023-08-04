<?php
include_once('../init.php'); // include initialisations
include_once('../classes/query_handler.php'); // include query handler class

if ($_SESSION['user']) { // if there is a user session
    user_obj()->logout(); // log out the user
    unset($_SESSION['user']); // unset the user session
}

Utils::navigate('home'); // return the the main page 


?>