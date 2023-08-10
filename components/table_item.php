<div id='table-<?php echo $t->get_id() ?>'  class='block'>
      <div class='status-wrap'>
    <div class='status status-<?php echo $t->get_status() ?>'><?php echo $t->get_status() ?></div></div>

     <div class='block-container'> 
   
<!-- // Title bar -->
     <div class='block-title'> 
     <div><?php echo $t->get_name() ?> 
    
    <!-- // Actions  -->
 <div class='action-container' >

 <div class='col-container icon-container'><form method='post' action='<?php echo $base . "/view/index.php?table_id=" . $t->get_id() ?>' ><button type='submit' value='<?php echo $t->get_id() ?>' name='edit'>
 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
 </button></form></div>

 <div class='col-container icon-container'><form method='post' action='<?php echo $base . "/manage/edit.php"; ?>' ><button type='submit' value='<?php echo $t->get_id(); ?>' name='edit'>
 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
 </button></form></div>

 <div class='col-container icon-container'><form method='post' action='<?php echo $base . "/manage/delete.php";?>' ><button type='submit' value='<?php echo $t->get_id() ?>' name='delete'>
 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
 </button></form></div>

 <div class='col-container icon-container'><form method='post' action='<?php echo $base . "/account/add-bookmark.php";?>' ><button type='submit' value='<?php echo $t->get_id(); ?>' name='bookmark'>
 <?php
     $class = "";
     if (user_obj()->is_bookmarked($t->get_id())) {
          $class = "bookmarked";
     }
 ?>
 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="<?php echo $class ?> lucide lucide-bookmark"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
 </button></form></div>
 </div>
     </div>
    <!-- <div></div> -->
    
     </div>

     <div class='block-details'>
     <table>
     <tr><td class='table-label'>Description</td><td><?php echo $t->get_description() ?></td></tr>

     <tr><td class='table-label'>Last Modified</td><td><?php echo date('D, j M Y',strtotime($t->get_update())) ?></td></tr>
    <?php
     if (count($t->get_tags())) {
        ?>
         <tr><td class='table-label'>Tags</td><td>
            <?php
        foreach($t->get_tags() as $key=>$value) {
             echo "#" . $value->get_name() . " &nbsp; ";
        }
        ?>
         </td></tr>
         <?php
    }

    ?>
    <tr><td class='table-label'>Source file</td><td><a href='<?php echo $t->get_link();?>' target="_blank">Link</a></td></tr>
 </table>
 </div>

 <div class='detail-container'>


 <div class='data-label json'><a href='<?php echo $base ?>/api/json.php?table=<?php echo $t->get_id() ?>' target='_blank'>JSON View</a></div>
 <div class='data-label json'><a href='<?php echo $base ?>/api/json.php?table=<?php echo $t->get_id() ?>&download' target='_blank' >JSON Download</a></div>
 <div class='data-label csv'><a href='<?php echo $base ?>/api/csv.php?table=<?php echo $t->get_id() ?>' target='_blank'>CSV View</a></div>
 <div class='data-label csv'><a href='<?php echo $base ?>/api/csv.php?table=<?php echo $t->get_id() ?>&download' target='_blank'>CSV Download</a></div>

 </div>
     </div>
         <div class='row'>
         </div>
         </div>