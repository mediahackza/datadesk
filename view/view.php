<?php
    include_once('../init.php');
    include_once('../classes/query_handler.php');

    if (isset($_SESSION["view_table"])) {
        $table = $_SESSION["view_table"];
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

<form action="view.php" method='post'>
    <div class="error"><?php echo $_SESSION['view_error'] ?></div>
    <input  type="hidden" name="table_id" value="<?php echo $table->get_id() ?>" autocomplete="off">
    <input type="text" name="view_name" placeholder="save view as" /><br/>
        <?php
            foreach($table->get_headings() as $key=>$value) {
                echo "<input type='checkbox' name='columns[]' value='".$value['name']."'/>".$value['name']."<br/>";
            }
        ?>


    
    <input type="text" name="name_to" placeholder="rename selected columns to"><br/>
    <input type="text" name="value_to" placeholder="rename value column to"><br/>
    
    <input type="submit" name="save_view" value="create new view"><br/>
</form>