<?php
$accounts = array();

$users = $GLOBALS['users'];

$users->columns(array('id', 'email', 'name', 'surname'));
$users->select();
if ($res = $users->query()) {
    foreach($res as $row) {
        $account = new Account();
        $account->set_data_from_row($row);
        $accounts[] = $account;
    }
}

$GLOBALS['accounts'] = $accounts;

function get_account($id) {
    $accounts = $GLOBALS['accounts'];
    foreach($accounts as $key=>$value) {
        if ($value->get_id() == $id) {
            return $value;
        }
    }
}


?>