
<?php

//    unset($_SESSION['new_table']);

    if (!isset($_SESSION['upload_error'])) {
        $_SESSION['upload_error'] = "";
    }

    if (isset($_POST['cancel'])) {
        unset($_SESSION['new_table']);
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
$GLOBALS['tag_list'] = $tags_list;

if ($res = $tags->query()) {
    foreach($res as $row) {
        $t = new Tag();
        $t->set_data_from_row($row);
        $tags_list[$t->get_name()] = $t;
    }
}

var_dump($tags_list);
function make_tag($tag) {
    global $tags;
    $tags_list = $GLOBALS['tag_list'];
    var_dump($tags_list);

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

    // $new_table->set_uploader_id(user_obj()->get_id());

    

    // if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
    //     save_data();
    // }

?>

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

