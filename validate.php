<?php

include_once('init.php');
include_once('classes/account.php');

if (isset($_SESSION['user'])) {
    $account = user_obj()->refresh();
    $_SESSION['user'] = serialize($account);
}   else {
    $account = new Account();
    if ($account->attempt_login_token()) {
        $_SESSION['user'] = serialize($account);
    } else {
        Utils::navigate('login');
    }
}
// else if ($account = query_handler::attempt_login_with_token()) {
//     $_SESSION['user'] = serialize($account);
// } else {
//     Utils::navigate('login');
// }

?>