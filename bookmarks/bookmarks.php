<?php

// Utils::add_location('previous', Utils::get_location('bookmarks'));


$bookmarks = $GLOBALS['bookmarks'];
$tables = $GLOBALS['tables'];
$tf = $GLOBALS['tf'];


    $bookmarks->clear_where();
    $bookmarks->add_where('user_id', user_obj()->get_id(), '=');
    $tables->columns(array("*"));
    
    $join = new join_table('left', array($bookmarks, $tables), array(array($bookmarks->get_col('table_id'), $tables->get_col('id'))));
    $join->select();
    if (($res = $join->query()) !== false) {
        foreach($res as $key=>$row){
            $t = $tf->create_table($row);
            include('components/table_item.php');
        }  
        
        if (count($res) == 0) {
            echo "<div class='no-results'>No bookmarks found.</div>";
        }
    } else {
        echo "something went wrong.";
        echo $join->error;
    }

?>


