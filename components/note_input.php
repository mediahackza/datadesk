


<form method="post" >
    <input type="hidden" name="table_id" value="<?php echo $t->get_id(); ?>" />
    <input type="hidden" name="author" value="<?php echo user_obj()->get_id(); ?>" />
    <textarea maxlength="1000" name="note" placeholder="Type note... "></textarea>
    <br/><button type="submit" name="save_note" value="save note">Save Note</button>
</form>