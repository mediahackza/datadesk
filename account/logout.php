<?php

    user_obj()->logout();
    unset($_SESSION['user']);

Utils::navigate('home'); // return the the main page 


?>