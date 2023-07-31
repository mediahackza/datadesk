<!-- <?php




?>


<div class="container">
    Save in Datadesk as:<br/>
    <input type="text" name="db_name" value="<?php echo $new_table->get_name() ?>" /> <br />
    Delimiting character:<br />
    <input type="text" name="delimiter" value="<?php echo $new_table->get_delimiter() ?>" size="1" /><br />

    First line is column headings:<br />
    <input type="checkbox" name="headings" <?php if($new_table->has_headings()) { echo "checked"; } ?> /> <br/>

CSV data:<br/>
<textarea name='data'><?php echo $new_table->get_csv_string() ?></textarea><br/>
<br /> 
    <input type="submit" name="save_sql" value="save to database" />
    <input type="submit" name="print_json" value="print to json" />
    <input type="submit" name="cancel" value="cancel" />
</div> -->