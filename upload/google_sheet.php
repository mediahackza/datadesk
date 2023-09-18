<?php 

if (!isset($_SESSION['new_table']) || unserialize($_SESSION['new_table'])->get_type() != 'google_sheet') {
    $_SESSION['new_table'] = serialize(new google_sheet_table());

} 

$GLOBALS['new_table'] = unserialize($_SESSION['new_table']);

function save_data() {

    if (!global_save_data()) {
        return false;
    }


    $new_table = $GLOBALS['new_table']; // get new table item to save data to 

    if (!isset($_POST['data']) || $_POST['data'] == "") {
        return false;
    }

    $new_table->set_source($_POST['data']);

        if ($res  = query_handler::insert_meta_data($new_table)) {
            unset($_SESSION['new_table']);
            return true;
        } 
        $_SESSION['upload_error'] = "Oops something went wrong upload data to database.". $new_table->error;
        
    }


if (isset($_POST['save_link'])) {
    if (save_data()){
        Utils::navigate('home');   
    } else {
        header("Refresh: 0");
    }
}

?>
    
<div class="container">
 

    <form method="post" class="inner-container">
    <table>
        <tr><td class="table-label">Save in Datadesk as</td>
        <td><input placehoder="table name" type="text" name="db_name" value="<?php echo $GLOBALS['new_table']->get_name() ?>" /></td></tr>
        <tr><td class="table-label">Link to google sheet</td><td>
        <input type="text" name="data" value="<?php echo $GLOBALS['new_table']->get_link() ?>" /> <br/>
        <tr>
        <td class="table-label">Source name:</td>
        <td><input type="text" name="source_name" value="<?php echo $GLOBALS['new_table']->get_source_name() ?>" /></td>
        </tr>
        <tr>
        <td class="table-label">Source link:</td>
        <td><input type="text" name="source_link" value="<?php echo $GLOBALS['new_table']->get_source_link() ?>" /></td></tr>
        <tr><td class="table-label">Tags</td><td>
        <?php
        include_once("components/tag_selector.php");
        ?>
        </td></tr>
        <tr><td class="table-label">Category:</td><td>
            <?php
            $table_cat_data = $GLOBALS['new_table'];
            include_once("components/category_selector.php");
            ?>
        </td></tr>
        <tr><td class="table-label">Description:</td><td>
        <textarea name="description" maxlength= "1000" ><?php echo $GLOBALS['new_table']->get_description() ?></textarea> </td></tr>
   
<tr><td colspan="2">
        <!-- <input type="submit" name="save_sql" value="save to database" /> -->
        <button class="" type="submit" name="save_link" value="Save" >Save</button>
        <!-- <input type="submit" name="print_json" value="print to json" /> -->
       <a href="/"><button class="cancel" type="" name="Cancel" value="cancel" >Cancel</button></a>
</td></tr>
</table>
    </form>


</div>

<style>
    .container { 
        /* border: solid 1px red; */
        width: 90%; 
        max-width: 800px;
    }
    .container textarea { 
        width: 90%;
        height: 100px;
      
    }
    .container { 
        padding-bottom: 100px;
    }
    .container input { 
        width: 90%;
    }
    .tag-selection-box { 
        width: 200px !important;
    }
    .cancel { 
        background: #eee;
        color: #000;
    }
    
</style>