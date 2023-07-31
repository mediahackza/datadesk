<?php
include_once('../init.php');
include_once('../classes/notes.php');
include_once('../components/note_handler.php');
include_once('../classes/query_handler.php');
include_once('../classes/table.php');
include_once('../classes/google_sheet_table.php');
include('../components/account_list.php');


include_once('../components/headers/html_header.php');
include_once('../components/headers/account_header.php');

$tags->columns(array('*'));
$tags->select();

$tags_list = array();



function make_tag($tag) {
    global $tags_list, $tags;

    foreach ($tags_list as $key=>$value) {
        if ($value->get_name() == $tag->get_name()) {
            $tag->set_id($value->get_id());
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
}


    if (isset($_POST['edit'])) {
        $id = $_POST['edit'];
        $table = query_handler::fetch_table_by_id($id);
        query_handler::populate_tags($table);
        $_SESSION['edit'] = $id;
    }

    if (isset($_SESSION['edit'])) {
        $id = $_SESSION['edit'];
        $table = query_handler::fetch_table_by_id($id);
        query_handler::populate_tags($table);
    }


    function save_data() {
        $table = $GLOBALS['table'];
        $table->set_tags(array());
        if (isset($_POST['add_tags'])) {
            $tags = $_POST['add_tags'];
            foreach($tags as $tag) {
                $t = new Tag();
                $t->set_name($tag);
                $t = make_tag($t);
                $table->add_tag($t);
            }
            
            
           
            
        }

        if ($_POST['name'] == "") {
            echo "name of table can not be blank";
            return false;
        }

        if ($_POST['source'] == "") {
            echo "source of table can not be blank";
            return false;
        }

        global $table;
        $table->set_name($_POST['name']);
        $table->set_source($_POST['source']);
        $table->set_description($_POST['description']);
        $table->set_status($_POST['status']);

        if (query_handler::update_meta($table)) {
            Utils::navigate('home');
        }

        
    }

    if (isset($_POST['update'])) {
        save_data();
    }

    

?>
<div class="edit-wrap">
<form method="post">
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
        <tr><td class="table-label">Tags</td><td>
        <?php
        include('../components/tag_selector.php')
        ?></td></tr>
        <tr><td class="table-label">Source</td><td>
        <input type="text" name="source" value="<?php echo $table->source; ?>" /> <a href="<?php echo $table->get_link(); ?>" target="_blank">Link</a></td></tr>
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
        include('../components/note.php');
        if (!$show_all) {
            break;
        }
    }
echo "</div>";

    include('../components/note_input.php');

    include_once('../components/html_footer.php');
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