<?php


    if (!isset($_SESSION['new_table']) || unserialize($_SESSION['new_table'])->get_type() != 'csv_file') {
        $_SESSION['new_table'] = serialize(new csv_table());

    } 

    $GLOBALS['new_table'] = unserialize($_SESSION['new_table']);

    function save_data() {

        if (!global_save_data()) {
            return false;
        } 
        global $base;
        $new_table = $GLOBALS['new_table'];
    
        if (!isset($_FILES['data']) || $_FILES['data']['size'] == 0) {
            $_SESSION['upload_error'] = "No file has been given";
            return false;
        }

        $target_dir =  "uploaded_files/";
        
        $file_name = basename($_FILES["data"]["name"]);
        $file_name  = Utils::check_chars($file_name);

        $target_file = $target_dir .$file_name;

        $extension = pathinfo($file_name, PATHINFO_EXTENSION);

        $source = "/uploaded_files/" . $file_name;
        echo $target_file . " " . $_FILES['data']['tmp_name'];
        if (move_uploaded_file($_FILES["data"]["tmp_name"], $target_file)) {
        } else {
            $_SESSION['upload_error'] = "Oops. Something went wrong transfering you file";
            return false;
        }

        $new_table->set_source($source);

        if ($extension == "tsv") {
            $new_table->set_delimiter("\t");
        }

        if ($extension == "csv") {
            $new_table->set_delimiter(",");
        }
    
        return $new_table;
    }

    if (isset($_POST['save_link']) || isset($_POST['save_note'])) {
        if ($res = save_data()) {
            $GLOBALS['new_table'] = $res;
        }
    }

    if (isset($_POST['save_link'])) {
        if ($res  = query_handler::insert_meta_data($GLOBALS['new_table'])) {
            $GLOBALS['new_table']->save_notes();
            unset($_SESSION['new_table']);
            Utils::navigate('home');
        } else {
            $_SESSION['upload_error'] = "Something went wrong uploading the table. ".$GLOBALS['new_table']->error;
            header('Refresh: 0');
        }
    }

?>

        <tr><td class="table-label">Save in Datadesk as:</td><td>
        <input placehoder="table name" type="text" name="db_name" value="<?php echo $GLOBALS['new_table']->get_name() ?>" /> </td></tr>
        <tr><td class="table-label">CSV file:</td><td>
        <input type="file" name="data" /></td></tr>
        <tr>
        <td class="table-label">Source name:</td>
        <td><input type="text" name="source_name" value="<?php echo $GLOBALS['new_table']->get_source_name() ?>" /></td>
        </tr>
        <tr>
        <td class="table-label">Source link:</td>
        <td><input type="text" name="source_link" value="<?php echo $GLOBALS['new_table']->get_source_link() ?>" /></td></tr>
        <tr>
            <td class="table-label">Last published:<br/><span class='note'>the date the source last updated this data.</span></td>
            <td><input type="text" name="published_date" value="<?php echo $GLOBALS['new_table']->get_published_date() ?>" />
            <span class="note">
           preferred formats:  
                </br> - 12 September 2023
                </br> - September 2023
                </br> - 2023</span></td>
        </tr>

        <tr><td class="table-label">Citing note:</td>
        <td><textarea name="citing_note" maxlength= "1000" ><?php if (!empty($GLOBALS['new_table']->get_citing_note())) {echo $GLOBALS['new_table']->get_citing_note()->get_note(); }?></textarea> </td></tr>
        
        <tr><td class="table-label">Data note:</td>
        <td><textarea name="data_note" maxlength= "1000" ><?php if (!empty($GLOBALS['new_table']->get_data_note())) { echo $GLOBALS['new_table']->get_data_note()->get_note(); }?></textarea></td></tr>
        
        <tr><td class="table-label">Tags:</td><td>
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
        <textarea name="description" maxlength= "1000" ><?php echo $GLOBALS['new_table']->get_description() ?></textarea> </td></td>
  
    <tr><td colspan="2">
        <!-- <input type="submit" name="save_sql" value="save to database" /> -->
        <button class="" type="submit" name="save_link" value="save" >Save</button>
        <!-- <input type="submit" name="print_json" value="print to json" /> -->
        <a href="/"><button class="cancel" type="" name="cancel" value="cancel" >Cancel</button></a>
</td></tr>

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

    .note {
        color: grey;
        font-size: 0.8rem;
        font-weight: 300;
    }

</style>