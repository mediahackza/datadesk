<?php

$table_id;


if (!isset($params['table_id'])) {
    echo "params arenn't set";
    // Utils::navigate('home');
    die;
} else {
    $table_id = $params['table_id'];


}

// Utils::add_location('previous', $base . '/dataset/' . $table_id);

    $table = query_handler::fetch_table_by_id($table_id);
    $table->set_data($table->get_source());

    $_SESSION["view_table"] = $table;

    $temp = query_handler::fetch_views_by_table_id($table_id);
    $views_tables = array();
    foreach($temp as $key=>$value) {
        if ($value['view_name'] != '') {
            $views_tables[$value['view_name']] = $value;
        } else {
            $views_tables[$value['id']] = $value;
        }
    }

    if (!isset($_POST['view_id']) || $_POST['view_id'] == "unformatted") {
        $view_string = "";
        $data = $table->get_data();
        $headings = $table->get_headings();
    } else {
        $view_id = $_POST['view_id'];
        $view_string = "&view_id=".$view_id;
        $v = $views_tables[$view_id];
        $view = query_handler::fetch_view_by_id($v['id']);
        $cols = Utils::split($view['column_names'], ',');
        $name_to = $view['name_to'];
        $value_to = $view['value_to'];

        $piv = $table->pivot_table($cols, $name_to, $value_to);
        $data = $piv['data'];
        $headings = $piv['headings'];
    }

?>

<div class="view-wrap">
<div class="view-controls">
    <div>
        <div class="view-controls-title">Views of this table</div>
        <div class="views-text">
            Views are different ways of looking at the data in this table. You can create a new view, or select an existing view to preview it. Views are mostly used for "pivoting" data. See this <a href="../assets/references/tidyr.pdf" target="_blank">R Cheatsheet</a> for an example of this. 
        </div>
    </div>
    <div></div>
   


<div class="controls-item">
    <form method="post">
        <input list="views" name="view_id" placeholder="select view" autocomplete="off">
        <datalist id="views">
            <option value="unformatted" ></option>
            <?php
                foreach($views_tables as $key=>$value) {
                        echo "<option value='" . $key . "'></option>";
                }
            ?>
        </datalist>
        <button type="submit" value="Preview view">Preview View</button>
    </form>
</div>
<div class="controls-item">
        <form action="view.php" method='post'>
            <input type="hidden" name="table_id" value="<?php echo $table_id; ?>">
            <button type="submit" name="submit" value="Create new view">Create new view</button>
            
        </form>
    </div>
</div>


<div class="table_container">
    <div class="table_header">
        <h1><?php echo $table->get_name(); ?></h1>
        <h2><?php echo $table->get_description(); ?></h2>
        </div>
        
        <table>
        <tr><td class="table-label">Source</td><td>
        <a href="<?php echo $table->get_link(); ?>" target="_blank">Link</a>
        </td></tr>
 

   <tr><td class="table-label">
        Name in database</td><td><?php echo $table->get_db_name(); ?></td></tr>
        <tr><td class="table-label">Type</td><td><?php echo $table->get_type(); ?></td></tr>
        <tr><td class="table-label">Uploaded by</td><td><?php echo get_account($table->get_uploader_id())->get_full_name(); ?></td></tr>
        <tr><td class="table-label">Uploaded on</td><td><?php echo $table->get_created_date(); ?></td></tr>
        <tr><td class="table-label">Last updated</td><td><?php echo $table->get_update(); ?></td></tr>  
        
        </table>
        <div class='detail-container' style="margin-top: 20px; margin-bottom: 20px">


            <div class='data-label json'><a href='<?php echo $base ?>/api/json.php?table=<?php echo $table->get_id(); if (isset($_POST['view_id'])) { echo "&view_id=". $views_tables[$_POST['view_id']]['id'];} ?>' target='_blank'>JSON View</a></div>
            <div class='data-label json'><a href='<?php echo $base ?>/api/json.php?table=<?php echo $table->get_id(); if (isset($_POST['view_id'])) { echo "&view_id=". $views_tables[$_POST['view_id']]['id'];} ?>&download' target='_blank' >JSON Download</a></div>
            <div class='data-label csv'><a href='<?php echo $base ?>/api/csv.php?table=<?php echo $table->get_id(); if (isset($_POST['view_id'])) { echo "&view_id=". $views_tables[$_POST['view_id']]['id'];} ?>' target='_blank'>CSV View</a></div>
            <div class='data-label csv'><a href='<?php echo $base ?>/api/csv.php?table=<?php echo $table->get_id(); if (isset($_POST['view_id'])) { echo "&view_id=". $views_tables[$_POST['view_id']]['id'];} ?>&download' target='_blank'>CSV Download</a></div>

            </div>

    <!-- <div class="source-link">
        <a href="<?php echo $base . '/api/json.php?table=' . $table_id . $view_string ?>" target="_blank">source</a>
    </div> -->

    <div class="data-table">
        <table>
            <thead>
                <tr>
                    <?php
                 
                        foreach($headings as $key=>$value) {
                            echo "<th><span class='type'>" . $value['type'] . "</span><br/>" .$value['name']. "</th>";
                        }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php

                

                foreach($data as $key=>$value) {
                    echo "<tr>";

                    foreach($value as $key=>$v) {
                        echo "<td>" . $v . "</td>";
                    }

                    echo "</tr>";
                }
                    // foreach($table['rows'] as $row) {
                    //     echo "<tr>";
                    //     foreach($row as $key=>$value) {
                    //         echo "<td>" . $value . "</td>";
                    //     }
                    //     echo "</tr>";
                    // }
                ?>
            </tbody>
        </table>
    </div>
</div>


<div class="note-container">
    <h2>Notes</h2>
    <?php
        foreach($table->get_notes() as $key=>$value) {
            echo "<div class='note'>";
            echo "<div class='note-header'>";
            echo "<div class='note-date'>" . $value->get_date() . "</div>";
            echo "<div class='note-title'>" . get_account($value->get_author())->get_full_name() . "</div>";
            
            echo "</div>";
            echo "<div class='note-body'>" . $value->get_note() . "</div>";
            echo "</div>";
        } 

  

     ?>
     
</div>

    </div>

<style>
    .view-wrap { 
        width: 90%; 
        max-width: 1000px;
        margin-left: auto;
        margin-right: auto;
    }
    .note {
        padding: 10px;

    }

    .type {
        /* font-size: 10px; */
    }

    .button {
        /* border: 1px solid red; */
    }
    .data-table { 
        /* display: none !important; */
        max-width: 100%; 
        max-height: 90vh;
        overflow-x: auto;
        /* overflow: auto; */
    }
    .ddtable { 
        width: 500px; 
        /* background: red; */
    }
    .view-controls { 
        background: #eee; 
        padding: 40px 40px;
        display: grid; 
        grid-template-columns: 1fr 1fr;

    }
    .controls-item { 
        display: inline-block;
      
    }
    .view-controls-title { 
        font-weight: 700; 
        font-size: 1rem;
        margin-bottom: 10px;
       
    


    }
    .views-text { 
        color: gray;
        margin-top: 5px; 
        margin-bottom: 15px;
        max-width: 500px; 

    }
    .view-controls input { 
        margin-top: 0px;
    }
</style>