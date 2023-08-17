<?php
// Utils::add_location('previous', $base);

include_once('components/note_handler.php');
include_once('classes/notes.php');

Utils::remove_location('previous');

$table_tags = $GLOBALS['table_tags'];
$tables = $GLOBALS['tables'];
$tf = $GLOBALS['tf'];
$base = $GLOBALS['base'];

$_SESSION['upload_error'] = "";

if (isset($_POST['cancel_search'])) {
    unset($_POST['search']);
    unset($_SESSION['search']);
    unset($_POST['sorting']);
    unset($_SESSION['sorting']);
}

if (!isset($_SESSION['search'])) {
    $_SESSION['search'] = "";
}

if (!isset($_SESSION['sorting'])) {
    $_SESSION['sorting'] = "";
}

if (isset($_POST['delete'])) {
    echo $_POST['delete'];
}

$data = array();


?>


<?php

function print_row($t) {

    $show_all = false;

    $base = $GLOBALS['base'];

    include('components/table_item.php');
}


if (isset($_POST['sorting'])) {
    $_SESSION['sorting'] = $_POST['sorting'];
    
}

if (isset($_POST['search'])) {
    $_SESSION['search'] = $_POST['search'];
}


?>

<!-- Data Filters --> 
<div class="data-filters filter-right filter-bottom-margin">
    <form method="post" action="upload">
                    <button class="action green" type="submit" name="upload" value="upload data">
                        <img src="./assets/icons/plus-white.png" class="button-icons">Add Dataset
                    <!-- <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg> -->
                    </button>
                </form> 
</div>
<div class="data-filters">
    
       
           
            <div></div>
            <div></div>

          
  
    
       <div class="filter-item">
            <form id="search_form" method="post">
                <input name="search" type="text" placeholder="Search" value="<?php if (isset($_SESSION['search'])) {echo $_SESSION['search'];} ?>"/>
              
                <input class="button inline" type="submit" value="Search" />
                <input class="cancel button inline" type="submit" value="Clear" name="cancel_search" />
            </form>
       </div>
    
        <div class="filter-item">
            <form method="post" id="filter_form">
                <select name="sorting" >
                    <option value="">Sort</options>
                    <option value="latest_up" <?php if ((isset($_SESSION['sorting'])) && ($_SESSION['sorting'] == 'latest_up')) { echo "selected='selected'";} ?> >Latest Upload</option>
                    <option value="oldest_up" <?php if ((isset($_SESSION['sorting'])) && ($_SESSION['sorting'] == 'oldest_up')) { echo "selected='selected'";} ?> >Oldest Upload</option>
                    <option value="latest_mod" <?php if ((isset($_SESSION['sorting'])) && ($_SESSION['sorting'] == 'latest_mod')) { echo "selected='selected'";} ?> >Last Modified</option>
                    <!-- <option value="oldest_mod" <?php if ((isset($_SESSION['sorting'])) && ($_SESSION['sorting'] == 'oldest_mod')) { echo "selected='selected'";} ?> >oldest modification first</option> -->
                </select>
            </form>
        </div>
        <div class="filter-item">
        <?php include('components/tag_list.php'); ?>
        </div>
    
</div>
<!-- Data Filters End -->

<script>

    const filter_form = document.getElementById('filter_form');
    const search_form = document.getElementById('search_form');
    const filter_select = filter_form.elements['sorting'];

    console.log(filter_select);

    filter_select.addEventListener('change', () => {
    
        filter_form.submit();
    });

</script>

 
<?php


if (isset($_SESSION['home-data'])) {
    $data = $_SESSION['home-data'];
}


if (isset($_POST['show_deleted']) && ($_POST['show_deleted'] == 'true')) {
    $data['show_deleted'] = true;
} else {
    $data['show_deleted'] = false;
}



$_SESSION['home-data'] = $data;

// include('components/tag_list.php');

foreach($_SESSION['active_tags'] as $id=>$t_id) {
    $table_tags->add_where('tag_id', $id, '=', "OR");
}

if (count($table_tags->where_data) > 0) {
    $tables->add_sorting('count(id)', "DESC");
    
}
$tables->add_group_by('id');


$tables->add_where("str_name", "%".$_SESSION['search']."%", "LIKE");
switch($_SESSION['sorting']) {
    case 'latest_up':
        $tables->add_sorting("date_added", "DESC");
        break;
    case 'oldest_up':
        $tables->add_sorting("date_added", "ASC");
        break;
    case 'latest_mod':
        $tables->add_sorting("last_updated", "DESC");
        break;
    case 'oldest_mod':
        $tables->add_sorting("last_updated", "ASC");
        break;
    defult:
        $tables->clear_sorting();
}

$tables->columns(array('*'));


$table_tags->columns(array('table_id'));
$join = new join_table('left', array($tables, $table_tags),array(array($tables->get_col('id'), $table_tags->get_col('table_id'))));
$join->select();

if ($tabs = $join->query()) {;
    foreach($tabs as $key=>$value) {
        $t = $tf->create_table($value);
        print_row($t);
    }
} else {
    echo $tables->error;
}



?>




<style>
    

   
    </style>