<?php

include('components/note_handler.php');

$tags = $GLOBALS['tags'];
$tags->columns(array('*'));
$tags->select();

// $tags_list = query_handler::fetch_tags();
$tags_list = array();

$GLOBALS['tags_list'] = $tags_list;


function make_tag($tag, $tags_list) {
    global $tags;
    foreach ($tags_list as $key=>$value) {

        if ($value->get_name() == $tag->get_name()) {
            $tag->set_id($value->get_id());

            var_dump($tag);
            echo "<br/><br/>";
            return $tag;
        }
    }

    $tags->insert(array('name' => $tag->get_name()));
    $id = $tags->query();
    echo "new id: ". $id . "<br/>";
    $tag->set_id($id);
    return $tag;
    

}

if ($res = $tags->query()) {
    foreach($res as $row) {
        $t = new Tag();
        $t->set_data_from_row($row);
        $tags_list[$t->get_name()] = $t;
    }

    $GLOBALS['tags_list'] = $tags_list;
}





    if (isset($params['table_id'])) {
        $id = $params['table_id'];
        $table = Utils::fetch_table($id);
        
        $_SESSION['edit'] = $id;
    }

    if (isset($_SESSION['edit'])) {
        $id = $_SESSION['edit'];
        $table = Utils::fetch_table($id);
    }


    function save_data($table) {
        global $base;
        $tags_list = $GLOBALS['tags_list'];
        $table->set_tags(array());
        if (isset($_POST['add_tags'])) {
            $tags = $_POST['add_tags'];
            foreach($tags as $tag) {
                $t = new Tag();
                $t->set_name($tag);
                $t = make_tag($t, $tags_list);
                $table->add_tag($t);
            }
            
            
           
            
        }

        if ($_POST['name'] == "") {
            echo "name of table can not be blank";
            return false;
        }

        if (($table->get_type() == 'google_sheet' && $_POST['source'] == "")) {
            echo "source of table can not be blank";
            return false;
        }

        $table->set_name($_POST['name']);

        switch ($table->get_type()) {
            case 'google_sheet':
                $table->set_source($_POST['source']);
                break;
            case 'csv_file':

                var_dump($_FILES);
                echo "<br/>";
    
                if (!isset($_FILES['source']) && $_FILES['source']['size'] == 0) {
                    break;
                }
                
                echo "tryign to update file <br/>";
                $target_dir = "uploaded_files/";
                $file_name = basename($_FILES["source"]["name"]);
                $file_name  = Utils::check_chars($file_name);

                $target_file = $target_dir .$file_name;
                $source = "/uploaded_files/" . $file_name;
                if (move_uploaded_file($_FILES["source"]["tmp_name"], $target_file)) {
                    // unlink($base . $table->source);
                    $table->set_source($source);
                }
                break;
        }
        
        $table->set_description($_POST['description']);
        $table->set_status($_POST['status']);
        $table->set_source_name($_POST['source_name']);
        $table->set_source_link($_POST['source_link']);
        $table->set_category($_POST['category']);
        $table->set_published_date($_POST['published_date']);

        if (query_handler::update_meta($table)) {
            Utils::navigate('home');
        }

        
    }

    if (isset($_POST['update'])) {
        save_data($table);
    }

    

?>
<div class="edit-wrap">
<form method="post" enctype="multipart/form-data">
<table>


        <input type="hidden" name="edit" value="<?php echo $table->get_id(); ?>" />
        <tr><td class="table-label">Table name</td><td>
        <input type ="text" name="name" value="<?php echo $table->get_name(); ?>" /><br/>
        <tr><td class="table-label">Status</td><td>
        <select name="status">
            <option value="active" <?php if ($table->get_status() == 'active') { echo "selected"; } ?>>active</option>
            <option value="inactive" <?php if ($table->get_status() == 'inactive') { echo "selected"; } ?>>inactive</option>
            <option value="deleted" <?php if ($table->get_status() == 'deleted') { echo "selected"; } ?>>deleted</option>
        </select></td></tr>
        <tr><td class="table-label">Description</td><td>
        <textarea type="text" name="description"><?php echo $table->get_description(); ?></textarea></td></tr>
        <tr>
        <td class="table-label">Source name:</td>
        <td><input type="text" name="source_name" value="<?php echo $table->get_source_name() ?>" /></td>
        </tr>
        <tr>
        <td class="table-label">Source link:</td>
        <td><input type="text" name="source_link" value="<?php echo $table->get_source_link() ?>" /></td></tr>
        <tr><td class="table-label">Source published:</td><td><input type="text" name="published_date" value ="<?php echo $table->get_published_date(); ?>" /></td></tr>
        <tr><td class="table-label">Tags</td><td>
        <?php
        include('components/tag_selector.php');
        ?></td></tr>
        <tr><td class="table-label">Category</td><td>
        <?php
        $table_cat_data = $table;
        include('components/category_selector.php');
        ?></td></tr>
        <tr><td class="table-label">Source</td>
        <td>
        <?php
            if ($table->get_type() == 'google_sheet') {
        ?>
             <input type="text" name="source" value="<?php echo $table->source; ?>" /> <a href="<?php echo $table->get_link(); ?>" target="_blank">Link</a>

        <?php
            } else if ($table->get_type() == 'csv_file') {
        ?>
            <input type="file" name="source" /> <a href="<?php echo $table->get_link(); ?>" target="_blank">Link</a>
        <?php
            }
        ?>

        </td></tr>
        <!-- <input type="text" name="source" value="<?php echo $table->source; ?>" /> <a href="<?php echo $table->get_link(); ?>" target="_blank">Link</a></td></tr> -->
        <tr><td colspan="2"><button type="submit" name="update" value="update" >Update</button></td></tr>

</table> 
   
</form>



<?php
    $t = $table;
    echo "<div class='block-note'>";
    $show_all = true;
    foreach($t->get_notes() as $key=>$value) {
        $note_data = $value;
        $edit_note = true;
        // echo $note_data . "<br/>";
        include('components/note.php');
        if (!$show_all) {
            break;
        }
    }
echo "</div>";

    include('components/note_input.php');

?>

</div>

<style>

.edit-wrap { 
   
    width: 90%; 
    max-width: 1000px; 
    margin: 0 auto;
    padding-bottom: 100px;
    /* text-align: center; */
}
input { 
    width: 300px;
}
textarea {
    width: 600px;
    height: 100px; 
}
.block-note { 
    margin-top: 30px;
}


    </style>