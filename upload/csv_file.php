<?php
    $new_table = new csv_table();
    // var_dump($new_table);

    function save_data() {
        global $new_table, $base;
        
        if (isset($_POST['add_tags'])) {
            $add_tags_list = $_POST['add_tags'];
            foreach($add_tags_list as $tag) {
                $t = new Tag();
                $t->set_name($tag);
                $t = make_tag($t);
                $new_table->add_tag($t);
            }
        }

        if (!isset($_POST['db_name']) || $_POST['db_name'] == "") {
            echo "no name";
            return false;
        } 
    
        if (!isset($_FILES['data']) || $_FILES['data']['size'] == 0) {
            echo "no data";
            return false;
        }

        if (isset($_POST['source_name'])) {
            $new_table->set_source_name($_POST['source_name']);
        }

        if (isset($_POST['source_link'])) {
            $new_table->set_source_link($_POST['source_link']);
        }

    
        $new_table->set_name($_POST['db_name']);

        $target_dir = "../uploaded_files/";
        
        $file_name = basename($_FILES["data"]["name"]);
        $file_name  = Utils::check_chars($file_name);

        $target_file = $target_dir .$file_name;

        

        $source = "/uploaded_files/" . $file_name;
        if (move_uploaded_file($_FILES["data"]["tmp_name"], $target_file)) {
        } else {
            return false;
        }

        echo "Source:";
        var_dump($source);
        $new_table->set_source($source);
    
        
        $new_table->set_description($_POST['description']);
        $new_table->set_uploader_id(user_obj()->get_id());

        $new_table->set_created_date(date("Y-m-d H:i:s"));
        $new_table->set_type("csv_file");
        if ($res  = query_handler::insert_meta_data($new_table)) {
            return true;
        }
        return false;
    }

    if (isset($_POST['save_link'])) {
        if (save_data()) {
            Utils::navigate('home');
        }
        
    }

?>
<div class="container">

<table>
    <form method="post" class="inner-container" enctype="multipart/form-data">
        <tr><td class="table-label">Save in Datadesk as:</td><td>
        <input placehoder="table name" type="text" name="db_name" value="<?php echo $new_table->get_name() ?>" /> </td></tr>
        <tr><td class="table-label">Link to google sheet:</td><td>
        <input type="file" name="data" /></td></tr>
        <tr>
        <td class="table-label">Source name:</td>
        <td><input type="text" name="source_name" value="<?php echo $new_table->get_source_name() ?>" /></td>
        </tr>
        <tr>
        <td class="table-label">Source link:</td>
        <td><input type="text" name="source_link" value="<?php echo $new_table->get_source_link() ?>" /></td></tr>
        <tr><td class="table-label">Tags:</td><td>
        <?php
        include_once("../components/tag_selector.php");
        ?>
        <tr><td class="table-label">Description:</td><td>
        <textarea name="description" maxlength= "1000" ><?php echo $new_table->get_description() ?></textarea> </td></td>
  
    <tr><td colspan="2">
        <!-- <input type="submit" name="save_sql" value="save to database" /> -->
        <button class="" type="submit" name="save_link" value="save" >Save</button>
        <!-- <input type="submit" name="print_json" value="print to json" /> -->
        <a href="/"><button class="cancel" type="" name="cancel" value="cancel" >Cancel</button></a>
</td></tr>
    </form>
</table>

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