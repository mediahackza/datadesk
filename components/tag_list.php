<?php

// include_once('../conf.php');
// include_once($base . '/');

$tags = $GLOBALS['tags'];

if (!isset($_SESSION['active_tags'])) {
    $_SESSION['active_tags'] = array();
}
if (!isset($_SESSION['home-data']['tags'])) {
    $tags->columns(array('*'));
    $tags->select();

    if ($res = $tags->query()) {
        foreach($res as $row) {
            $tag = new Tag();
            $tag->set_data_from_row($row);
            $tags_list[$tag->get_name()] = $tag;
            
        }
    }

    $_SESSION['home-data']['tags'] = serialize($tags_list);
} else {
    $tags_list = unserialize($_SESSION['home-data']['tags']);
}


$active_inner = "";
$inactive_inner = "";

if (isset($_POST['new_tag'])) {
    $tags_list[$_POST['new_tag']]->toggle_active();
}

foreach($tags_list as $tag) {
    if (isset($_POST['tag_'.$tag->get_id()])) {
        $tag->toggle_active();
        unset($_POST['tag_'.$tag->get_id()]);
        
    }

    if (isset($_POST['remove_tag_'.$tag->get_id()])) {
        $tag->make_inactive();
        unset($_POST['remove_tag_'.$tag->get_id()]);
    }

    if (isset($_POST['cancel_search'])) {
        $tag->make_inactive();
    }

    

    if ($tag->is_active()) {
        $_SESSION['active_tags'][$tag->get_id()] = $tag;
        $active_inner .= "<form method='post' class='form-cont' ><input class='tag' type='submit' name='remove_tag_" . $tag->get_id()."'  value='#".$tag->get_name()."' /></form>";
    } else {
        unset($_SESSION['active_tags'][$tag->get_id()]);
        $inactive_inner .= "<option value='".$tag->get_name()."'>";
    }
}
$_SESSION['home-data']['tags'] = serialize($tags_list);



?>

<div class="container">
    <?php
        echo $active_inner;
    ?>
</div>

<div class="container">
    <form id="tag_form" method="post">
        
    <input autocomplete="off"  placeholder="Tags" type="text" oninput="update_tags()" list="inactive_tags" name="new_tag">
    <datalist id="inactive_tags"  >
        <?php
            foreach($tags_list as $tag) {
                if (!$tag->is_active()) {
                    echo "<option value='".$tag->get_name()."'>";
                }
            }
        ?>

    </datalist>
</form>


    <a href="<?php echo $base;?>/tags" ><button class="add-tag"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-diff"><path d="M12 3v14"/><path d="M5 10h14"/><path d="M5 21h14"/></svg></button></a>


        </div>



<!-- <form method="post" action="/datadesk/tags/add.php">
    <input type="text" name="tag" placeholder="Tag name" /><br/>
    <input type="submit" value="Add" />
</form> -->
<script>

    function update_tags() {
        var form = document.getElementById("tag_form");
        form.submit();
    }

</script>
<style>

    .container {
        display: flex;
        flex-direction: row;
    }

    .form-cont {
        display: block;
        margin: 0px 5px;
        padding: 0px;
    }

    

    form {
        margin: 5px;
    }
    .add-tag { 
        padding: 3px;
        transform: translate(0px, 5px);
    }

</style>