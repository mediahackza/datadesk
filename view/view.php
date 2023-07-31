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
    <datalist id="col_names" >
        <?php
            foreach($table->get_headings() as $key=>$value) {
                echo "<option value='" . $value['name'] . "'>";
            }
        ?>
    </datalist>

    <div class="chosen-cols" id="chosen-cols">

    </div>

    <input id="col_drop"  type="text" list="col_names" name="col_name" placeholder="column name" multiple ><br/>
    <input type="text" name="name_to" placeholder="rename selected columns to"><br/>
    <input type="text" name="value_to" placeholder="rename value column to"><br/>
    
    <input type="submit" name="save_view" value="create new view"><br/>
</form>

<script>
    const drop_down = document.getElementById("col_drop");
    const container = document.getElementById("chosen-cols");

            console.log("This is a test")
    console.log(drop_down);

    drop_down.addEventListener("change", () => {
        console.log("drop down value", drop_down.value)
        const new_div = document.createElement("div");
        new_div.addEventListener('click', () => {
            new_div.remove();
        });
        const new_input = document.createElement("input");
        new_input.name='columns[]';
        new_input.value = drop_down.value;
        new_div.appendChild(new_input);
        container.appendChild(new_div);
        drop_down.value = "";
    })

</script>