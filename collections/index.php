<?php

    $tables = $GLOBALS['tables'];

    $tables->columns(['*']);
    $tables->clear_where();
    $tables->add_where('type', 'collection', '=');

    $tables->select();

    $t = $tables->query();

    var_dump($t);

?>

<form>
    <input type="text" name="collection-name" placeholder="name of collection" />
    <input type="submit" value="add a new collection"/>
</form>