<?php


if (isset($params['id'])) {
    $id = $params['id'];
    
    if (query_handler::delete_tag($id)) {
        Utils::navigate('tags');
    }

}

?>