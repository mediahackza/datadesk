<?php
include_once('init.php');
include_once('validate.php');
include('components/account_list.php');
include_once('components/headers/html_header.php');
include_once('components/headers/account_header.php');
include_once('components/note_handler.php');
include_once('classes/notes.php');

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
     
    echo "<div id='table-" . $t->get_id() ."'  class='block'>";
    echo "<div class='status-wrap'>
    <div class='status status-" . $t->get_status() . "'>" . $t->get_status() . "</div></div>";

    echo "<div class='block-container'>"; 
   
// Title bar
    echo "<div class='block-title'>"; 
    echo "<div>" . $t->get_name(); 
    
    // Actions 
echo "<div class='action-container' >";

echo "<div class='col-container icon-container'><form method='post' action='./view/index.php?table_id=".$t->get_id()."' ><button type='submit' value='".$t->get_id()."' name='edit'>";
echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>';
echo "</button></form></div>";

echo "<div class='col-container icon-container'><form method='post' action='./manage/edit.php' ><button type='submit' value='".$t->get_id()."' name='edit'>";
echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>';
echo "</button></form></div>";

echo "<div class='col-container icon-container'><form method='post' action='./manage/delete.php' ><button type='submit' value='".$t->get_id()."' name='delete'>";
echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>';
echo "</button></form></div>";
echo "</div>";
    echo "</div>";
    // echo "<div></div>";
    
    echo "</div>";

    echo "<div class='block-details'>";
    echo "<table>";
    echo "<tr><td class='table-label'>Description</td><td>" . $t->get_description() . "</td></tr>";

    echo "<tr><td class='table-label'>Last Modified</td><td>" . date("D, j M Y",strtotime($t->get_update()))  . "</td></tr>";
    if (count($t->get_tags())) {
        echo "<tr><td class='table-label'>Tags</td><td>";
        foreach($t->get_tags() as $key=>$value) {
            echo "#" . $value->get_name() . " &nbsp; ";
        }
        echo "</td></tr>";
    }


echo "</table>";
echo "</div>";



    // beginning of detials panel
echo "<div class='detail-container'>";


echo "<div class='data-label json'><a href='".$base."/api/json.php?table=".$t->get_id()."' target='_blank'>JSON View</a></div>";
echo "<div class='data-label json'><a href='".$base."/api/json.php?table=".$t->get_id()."&download' target='_blank' >JSON Download</a></div>";
echo "<div class='data-label csv'><a href='".$base."/api/csv.php?table=".$t->get_id()."' target='_blank'>CSV View</a></div>";
echo "<div class='data-label csv'><a href='".$base."/api/csv.php?table=".$t->get_id()."&download' target='_blank'>CSV Download</a></div>";

echo "</div>";
// end of details panel 

// echo "<div class='note-label'>Notes</div>";
// echo "<div class='block-note'>";

// foreach($t->get_notes() as $key=>$value) {
//     $note_data = $value;
//     include('components/note.php');
//     if (!$show_all) {
//         break;
//     }
// }
// echo "</div>";

   
    // echo "<a href='./view/index.php?table_id=".$t->get_id()."'>preview data</a>";
// Details section


// // beginning of detials panel
// echo "<div class='detail-container'>";
// echo "<div class='data-link row'>";
// echo "<div class='data-label data-label-top'>JSON data:</div>";
// echo "<div class='data-label'><a href='".$base."/api/json.php?table=".$t->get_id()."' target='_blank'>view</a></div>";
// echo "<div class='data-label'><a href='".$base."/api/json.php?table=".$t->get_id()."&download' target='_blank' >download</a></div>";
// echo "</div>";
// echo "<div class='data-link row'>";
// echo "<div class='data-label data-label-top'>CSV data:</div>";
// echo "<div class='data-label'><a href='".$base."/api/csv.php?table=".$t->get_id()."' target='_blank'>view</a></div>";
// echo "<div class='data-label'><a href='".$base."/api/csv.php?table=".$t->get_id()."&download' target='_blank'>download</a></div>";
// echo "</div>";
// echo "</div>";
// // end of details panel 



    echo "</div>";

   


    // begining off main row
    // echo "<div class='row'>";
    // echo "<div class='title'>" . $t->get_name() . "</div>";
    // echo "</div>";
        echo "<div class='row'>";




        // date mdified
            // echo "<div class='col-container'>";
            // echo "<div class='details'>last modified: " . date("D, j M Y",strtotime($t->get_update()))  . "</div>";
            // echo "<div class='details' >by: " . get_account($t->get_uploader_id())->get_full_name() . "</div>";
            // echo "</div>";
        // date modified end

        // status
            // echo "<div class='col-container'>";
            // echo "<div class='status status-".$t->get_status()."'>" . $t->get_status() . "</div>";
            // echo "</div>";
        // status end

        // delete button
       
        
        // delete button end   
        echo "</div>";
        // end of main row

        // echo "<div class='desc'>" . $t->get_description() . "</div>";

        

        

        // include('components/note-input.php');
        
        echo "</div>";
        // end of block
}


if (isset($_POST['sorting'])) {
    $_SESSION['sorting'] = $_POST['sorting'];
    
}

if (isset($_POST['search'])) {
    $_SESSION['search'] = $_POST['search'];
}

// query_handler::set_table_sorting($_SESSION['sorting']);
// query_handler::set_table_search_term($_SESSION['search']);





?>

<!-- Data Filters --> 
<div class="data-filters filter-right filter-bottom-margin">
    <form method="post" action="upload/upload.php">
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

<script>

    const filter_form = document.getElementById('filter_form');
    const search_form = document.getElementById('search_form');
    const filter_select = filter_form.elements['sorting'];

    console.log(filter_select);

    filter_select.addEventListener('change', () => {
    
        filter_form.submit();
    });

</script>



<style>
    

   
    </style>