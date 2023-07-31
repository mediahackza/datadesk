<?php

include('../init.php');
include('../components/headers/html_header.php');
include('../components/headers/account_header.php');

$tags = query_handler::fetch_tags();



?><div class="page-wrap">
  <div class="page-title">Edit Tags</div>

    <div class="right"><form method="post" action="./add.php"><button type="submit">Add a new tag</button></form></div>
    
    <table>
        <tr>
      
            <th>Name</th>
            <th colspan="2">Options</th>
        </tr>
    
    
        <?php
    
        foreach($tags as $t) {
            echo "<tr>";
            // echo "<td>".$t->get_id()."</td>";
            echo "<td>".$t->get_name()."</td>";
            echo "<td><a href='./add.php?tag_name=".$t->get_name()."&id=".$t->get_id()."'>edit</a></td>";
            echo "<td><a href='./delete.php?id=" . $t->get_id()."'>delete</a></td>";
            echo "</tr>";
        }
    
        ?>
    </table>
</div>

<style>
    .right { 
        text-align: right;
    }
.page-wrap { 
    width: 90%; 
    margin: auto;
    max-width: 800px;
}
    tr,td {
        /* border: 1px solid black; */
        /* font-size: 1.2rem; */
    }

    .add-row {
        /* column-count: 3; */
        text-align: center;
    }

    .add-link {
        display: block;
        margin: auto;
        font-size: 1.5rem;
        border-radius: 5px;
        background-color: dodgerblue;
        color: white;
        text-decoration: none;
        text-align: center;
        width: 50%;
    }

    .add-link:hover {
        background-color: lightblue;
    }
    .page-wrap table { 
        margin-top: 5px;
    }
</style>
