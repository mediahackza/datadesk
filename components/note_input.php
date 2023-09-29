
<?php

if (!isset($note_types)) {
    $note_types = [
        'general' => 'General note',
        'citing' => 'Citing note',
        'data' => 'Data note'
    ];
}
    
?>
    <!-- <select name="type">
        <?php
            // foreach ($note_types as $key=>$value) {
            //     $opt = "<option value='$key'> $value</option>";
            //     echo $opt;
            // }
        ?>
    </select><br/> -->
    <input type="hidden" value="general" name="type" />
    <input type="hidden" name="table_id" value="<?php echo $t->get_id(); ?>" />
    <input type="hidden" name="author" value="<?php echo user_obj()->get_id(); ?>" />
    <textarea maxlength="1000" name="note" placeholder="Type note... "></textarea>
    <br/><button type="submit" name="save_note" value="save note">Add this note</button>
