<?php

include_once('classes/account.php');

if (isset($_SESSION['user'])) {
    $account = user_obj();
    $_SESSION['user'] = serialize($account);
}   else {
    $account = new Account();
    if ($account->attempt_login_token()) {
        $_SESSION['user'] = serialize($account);
    } else {
        Utils::navigate('welcome');
    }
}

?>