<?php
    include_once('classes/table.php');
    if (isset($_SESSION["view_table"])) {
        $table = unserialize($_SESSION["view_table"]);
    }

    if (!isset($_SESSION['view_error'])) {
        $_SESSION['view_error'] = "";
    }


    function save_view() {
        global $table;
        if (!(isset($_POST['view_name'])) || $_POST['view_name'] == '') {
            $_SESSION['view_error'] = "Please enter a name for the view";
            return false;
        }

        if (!(isset($_POST['columns'])) || $_POST['columns'] == '') {
            $_SESSION['view_error'] = "Please select columns to include in the view";
            return false;
        }

        if (!(isset($_POST['name_to'])) || $_POST['name_to'] == '') {
            $_SESSION['view_error'] = "Please enter a name for the pivoted column names";
            return false;
        }

        if (!(isset($_POST['value_to'])) || $_POST['value_to'] == '') {
            $_SESSION['view_error'] = "Please enter a name for the value column";
            return false;
        }

        $view_name = $_POST['view_name'];
        $columns = $_POST['columns'];
        $name_to = $_POST['name_to'];
        $value_to = $_POST['value_to'];

        var_dump($columns);

        if (query_handler::create_view($table->get_id(), $columns, $name_to, $value_to, $view_name)) {
            $_SESSION['view_error'] = "";
            return true;

        };
    }  

    if (isset($_POST['save_view']) && save_view()) {
        header("Location: ".$base."/view/index.php?table_id=".$table->get_id());
    }
        


?>
<div class="page-wrap">
    
    <div class="page-title">Pivot Data</div>
    <p>Use this form to create a new view of the data.  You can select which columns to include, and rename the columns and values. This transformation will perform a "pivot longer" transformation on the data. </p>
    <p>See this <a href="http://localhost:8888/datadesk/assets/references/tidyr.pdf" taget="_blank">R Cheatsheet</a> for an example of this.</p>
    <table>
    <form action="view.php" method='post'>
        <div class="error"><?php echo $_SESSION['view_error'] ?></div>
        <input  type="hidden" name="table_id" value="<?php echo $table->get_id() ?>" autocomplete="off">
        <tr><td class="table-label">View name</td><td><input type="text" name="view_name" placeholder="save view as" /></td></tr>
        <tr><td class="table-label">Select columns to include</td><td>
            <?php
                foreach($table->get_headings() as $key=>$value) {
                    echo "<input type='checkbox' name='columns[]' value='".$value['name']."'/>".$value['name']."<br/>";
                }
            ?>

        </td></tr>
        <tr><td class="table-label">Rename columns</td><td>
 
        
        <input type="text" name="name_to" placeholder="rename selected columns to">
        </td></tr>
        <tr><td class="table-label">Rename values</td><td>
        <input type="text" name="value_to" placeholder="rename value column to">
        </td></tr>

        <tr><td></td><td>
                   
        <button type="submit" name="save_view" value="Create new view">Create new view</button>
        </td></tr>
    </form>
    </table>
</div>

<style>
    .page-wrap { 
        width: 90%; 
        max-width: 800px; 
        margin-left: auto;
        margin-right: auto;
        padding-bottom: 100px;
    }
</style>