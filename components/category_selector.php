<?php 
    

    $categories_list = [
        'Climate',
        'Economy',
        'Education',
        'Governance',
        'Health',
        'Lifestyle',
        'Sports'
    ];

?>

    <select name="category">
        <?php

            foreach ($categories_list as $key=>$value) {
                $opt = "<option value='" . $value . "'";

                if (isset($table_cat_data) && $table_cat_data->get_category() == $value) {
                    $opt .= " selected='selected' ";
                }
                $opt .= ">$value</option>";

                echo $opt;
            }

        ?>
    </select>