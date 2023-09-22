<?php

 $notes = $GLOBALS['notes'];

 if (!isset($post['id'])) {
    exit();
 }

 $notes->columns(array('date','author', 'id','note'));
 $notes->clear_where();
 $notes->add_where('table_id', $post['id'], '=');
 $notes->add_where('type', 'general', '=');
 $notes->select();

 if ($res = $notes->query()) {
    $out_notes = $res;
 } else {
    $out_notes = array();
 }

 $notes->clear_where();
 $notes->add_where('table_id', $post['id'], '=');
 $notes->add_where('type', 'citing', '=');
 $notes->select();

 if ($res = $notes->query()) {
   $out_citing_notes = $res;
 } else {
   $out_citing_notes = array();
 }

 $notes->clear_where();
 $notes->add_where('table_id', $post['id'], '=');
 $notes->add_where('type', 'data', '=');
 $notes->select();

 if ($res = $notes->query()) {
   $out_data_notes = $res;
 } else {
   $out_data_notes = array();
 }

?>