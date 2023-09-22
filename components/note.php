<?php

    if (isset($_POST['delete_note_' . $note_data->get_id()])) {
        $t->delete_note($note_data);
        $note_data->delete();
        unset($_POST['delete_note' . $note_data->get_id()]);
    } else {

?>

<div class="note-container">
    <div class="meta-container">
    <?php echo get_account($note_data->get_author())->get_full_name(); ?> | 
        <?php echo $note_data->get_date(); ?>
    </div>
        <div class='type meta-container'><?php echo $note_data->get_type(); ?> note</div>
    <div class="author-container">
        
    </div>

    <div class="note">
        <?php echo $note_data->get_note(); ?>
    </div>

    <?php 

    if (isset($edit_note) && $edit_note) {

        ?>
    <form method="post" class="note-form">
        <button type="submit" name='delete_note_<?php echo $note_data->get_id()?>'>Delete Note</button>
    </form>

    <?php 
    }?>
</div>

<?php 

    } ?>
    

    <style>
        .note-form button { 
            font-size: 0.5rem;
            padding: 3px 10px;
            /* display: none; */
        }
    </style>