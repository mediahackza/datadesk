<?php

    include_once('../classes/sql_table.php');

    $tags = $GLOBALS['tags'];
    $table_tags = $GLOBALS['table_tags'];

    if (!isset($post['id'])) {
        exit();
    }

    $tags->clear_where();
    $tags->columns(array('*'));

    $table_tags->clear_where();
    $table_tags->add_where('table_id', $post['id'], '=');

    $join = new join_table('join', array($tags, $table_tags), array(array($tags->get_col('id'), $table_tags->get_col('tag_id'))));

    $join->select();

    if ($res = $join->query()) {
        $out_tags = $res;
    } else {
        $out_tags = array();
    }
?>