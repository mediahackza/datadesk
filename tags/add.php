<?php


$update = false;
$tag = new tag();

if (isset($params['id'])) {
    $tags = $GLOBALS['tags'];
    $tags->columns(array('*'));
    $tags->clear_where();
    $tags->add_where('id', $params['id'], '=');
    $tags->select();
    if ($res = $tags->query()) {
        $tag->set_data_from_row($res[0]);
    }
    $update = true;
}

if (isset($_POST['tag-name'])) {
    $tag_name = $_POST['tag-name'];
    $tag->set_name($tag_name);
    

    if ($update) {
        if (query_handler::update_tag($tag)) {
            Utils::navigate('tags');
        }
    } else if (query_handler::add_tag($tag)) {
        unset($_SESSION['home-data']['tags']);
        Utils::navigate('tags');
    }

}

?>

<div class="page-wrap">
    <div class="page-title">Edit Tag</div>
    <div class="form-wrap">
        <form method="post">
            <input type="text" name="tag-name" id="tag-name" placeholder="tag name" value="<?php echo $tag->get_name() ?>"/>
            <?php 
            if($tag->get_name() == "") { ?>
            <button type="submit" value="Add" >Add</button>
            <?php } else { ?>
            <button type="submit" value="Update">Update</button>
            <?php } ?>
        </form>
        <form class="inline" method="post" action="./index.php">
            <button type="submit" class="inline">Cancel</button>
        </form>
    </div>
</div>

<style>
    .page-wrap { 
        width: 90%; 
        max-width: 800px; 
        margin: auto;
    }
  .inline { 
    margin-left: 5px;
  }
  .inline button { 
    background: #eee;
    color: #000;
  }
    .form-wrap { 
        display: flex;
        flex-direction: row;
    }
    .form-wrap input { 
        margin-top: 0px;
    }
    
</style>