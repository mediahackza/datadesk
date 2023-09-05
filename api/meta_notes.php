<?php

 $notes = $GLOBALS['notes'];

 if (!isset($post['id'])) {
    exit();
 }
 $notes->columns(array('date','author', 'id','note'));
 $notes->clear_where();
 $notes->add_where('table_id', $post['id'], '=');
 $notes->select();

 if ($res = $notes->query()) {
    $out_notes = $res;
 } else {
    $out_notes = array();
 }

?>