<?php
    $bookmarks = $GLOBALS['bookmarks'];

    if (isset($_POST['bookmark'])) {
        $id = $_POST['bookmark'];

        if (user_obj()->is_bookmarked($id)) {
            $bookmarks->delete(array('user_id' => user_obj()->get_id(), 'table_id' => $id));
            $bookmarks->query();
            Utils::navigate('previous');
        }

        $bookmarks->insert(array(
            'user_id' => user_obj()->get_id(),
            'table_id' => $id
        ));

        if ($res = $bookmarks->query()) {
            user_obj()->add_bookmark($id);
        };

        Utils::navigate('previous');
    }

?>