
<?php
    
    ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    if (!isset($_SESSION['upload_error'])) { //  init upload error for session
        $_SESSION['upload_error'] = "";
    }

    if (isset($_POST['cancel'])) { // if upload is cancelled
        unset($_SESSION['new_table']); // clear $_SESSION data
        unset($_SESSION['upload_error']);
        unset($_SESSION['upload_type']);

        Utils::navigate('home');
        // die;
    }

    if (!isset($_SESSION['upload_type'])) {
        $type = 'google sheet';
        $_SESSION['upload_type'] = 'google sheet';
    } else {
        $type = $_SESSION['upload_type'];
    }

    $add_tags_list = array();
    $tags = $GLOBALS['tags'];

    $tags->columns(array('*'));
    $tags->select();

    $tags_list = array();

    if ($res = $tags->query()) {
        foreach($res as $row) {
            $t = new Tag();
            $t->set_data_from_row($row);
            $tags_list[$t->get_name()] = $t;
        }

        $GLOBALS['tags_list'] = $tags_list;
    }

    function make_tag($tag) {
        global $tags;
        $tags_list = $GLOBALS['tags_list'];

        foreach ($tags_list as $key=>$value) {
            if ($value->get_name() == $tag->get_name()) {
                $tag->set_id($value->get_id());
                return $tag;
            }
        }

        $tags->insert(array('name' => $tag->get_name()));
        $id = $tags->query();
        $tag->set_id($id);
        unset($_SESSION['home-data']['tags']);
        return $tag;
        

    }

    if (isset($_POST['upload_type'])) {
        $type = $_POST['upload_type'];
        $_SESSION['upload_type'] = $type;
        unset($_SESSION['new_table']);
    }


    function global_save_data() {
        $new_table = $GLOBALS['new_table'];
        $success = true;

        if (isset($_POST['add_tags'])) {
            $add_tags_list = $_POST['add_tags'];
            foreach ($add_tags_list as $tag) {
                $t = new Tag();
                $t->set_name($tag);
                $t = make_tag($t);
                $new_table->add_tag($t);
            }
        }

        if (!isset($_POST['db_name']) || $_POST['db_name'] == "") {
            $_SESSION['upload_error'] = "No name given for data set";
            $success = false;
        }

        if (!isset($_POST['category']) || $_POST['category'] == '') {
            $_SESSION['upload_error'] = "Category can not be left blank";
            $success = false;
        }
        if (isset($_POST['source_name'])) {
            $new_table->set_source_name($_POST['source_name']);
        }

        if (isset($_POST['source_link'])) {
            $new_table->set_source_link($_POST['source_link']);
        }

        if (isset($_POST['citing_note']) && $_POST['citing_note'] != '') {
            $note = new Note();

            // $note->set_table_id($_POST['table_id']);
            
            $note->set_date(date("Y-m-d H:i:s"));
            $note->set_author(user_obj()->get_id());
            $note->set_note($_POST['citing_note']);
            $note->set_type('citing');

            $new_table->set_citing_note($note);
        }

        if (isset($_POST['data_note']) && $_POST['data_note'] != '') {
            $note = new Note();

            // $note->set_table_id($_POST['table_id']);
            
            $note->set_date(date("Y-m-d H:i:s"));
            $note->set_author(user_obj()->get_id());
            $note->set_note($_POST['data_note']);
            $note->set_type('data');

            $new_table->set_data_note($note);   
        }

        $new_table->set_category($_POST['category']);
        $new_table->set_name($_POST['db_name']);
        $new_table->set_description($_POST['description']);
        $new_table->set_uploader_id(user_obj()->get_id());
        $new_table->set_created_date(date("Y-m-d H:i:s"));
        $new_table->set_published_date($_POST['published_date']);

        $_SESSION['new_table'] = serialize($new_table);

        return $success;
    }


    ?>
   <div class="page-wrap">
        <div class="page-title">New Table</div>
        <div class="error"><?php echo $_SESSION['upload_error']; ?></div>
    <div class="input-switch">
            <form method="post" id="type_form" >
                <input type="radio" onclick="change_type()" name="upload_type" value="google sheet" <?php if ($_SESSION['upload_type'] == 'google sheet') { echo "checked";} ?>>Google Sheet</input> &nbsp; 
                <input type="radio" onclick="change_type()" name="upload_type" value="csv file" <?php if ($_SESSION['upload_type'] == 'csv file') { echo "checked";}  ?>>CSV File</input>
                
            </form>
    
            
    </div>
   </div>

   <div class="container">
    

   <form method="post" class="inner-container">
    <table>
    <?php

switch ($type) {
    default:
        include_once('google_sheet.php');
        break;
    case 'google sheet':
        include_once('google_sheet.php');
        break;
    case 'csv file':
        include_once('csv_file.php');
        break;
} 
?>


</table>

<?php

$note_types = [
    'citing' => "Citing note",
    'data' => 'Data note'
];
$GLOBALS['table'] = unserialize($_SESSION['new_table']);

include_once('components/note_handler.php'); 

$t = $GLOBALS['table']; // set table to $t to be used in not.php
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

$_SESSION['new_table'] = serialize($t);

include_once('components/note_input.php');
$_SESSION['new_table'] = serialize($GLOBALS['table']);

?>

    </form>


</div>

<script>
    const type_form = document.getElementById('type_form');

    function change_type() {
        console.log("changed")
        type_form.submit();
    }

</script>


<style>
    .page-wrap { 
        width: 90%; 
        max-width: 800px; 
        margin: auto;
 
    }
    .input-switch { 
        width: 90%; 
        max-width: 718px; 
        margin: auto;
        background: #F7F7F7; 
        padding: 40px;
        border:solid 1px lightgray;
        text-align: center;
        border-bottom: 0px; 

    }
    .container table { 
        margin-top: 0px;
    }
</style>

