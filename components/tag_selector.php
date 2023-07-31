<?php





?>


<div id="active_tag_display" >
<?php
if (isset($table)) {
    $cur_tags = $table->get_tags(); 

    foreach ($cur_tags as $key=>$value) {
        echo "<div class='tag-outer-container' ><input class='tag-input active' type='hidden' name='add_tags[]' value='".$value->get_name()."' />";
        echo "<div class='tag' >" . $value->get_name() . "</div>";
        echo '<svg class="remove-button" onclick="removeTag(this)"s xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>';
        echo "</div>";   
    }
}
?>
</div>

<input type="text" class="tag-selection-box" id="tag_input" list="tags" autocomplete="off" />

<datalist id="tags" autocomplete="off" >
    <select>
    <?php
        foreach($tags_list as $key=>$value) {

            echo "<option value='" . $key . "' ></option>";
        }
    ?>
    </select>
</datalist>
<button type="button" onclick="add_tag()" >add tag</button>
<br/>

<script>

    const tag_input = document.getElementById('tag_input');
    const tag_display = document.getElementById('active_tag_display');
    console.log("tag input is:", tag_input);
    // tag_input.addEventListener('change', add_tag);

        function add_tag() {
            console.log("adding tag")
            let id = tag_input.value;
            console.log(id)

            let div = document.createElement("div");
            div.className = "tag-outer-container";
            let input = document.createElement("input");
            input.className = "tag-input active";
            input.type = "hidden";
            input.name = "add_tags[]";
            input.value = id;
            let tag = document.createElement("div");
            tag.className = "tag";
            tag.innerHTML = id;
            div.appendChild(input);
            div.appendChild(tag);
            div.innerHTML += '<svg onclick="removeTag(this)" class="remove-button" onclick="removeTag(this)" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>'

            tag_display.appendChild(div);             
            tag_input.value = "";
        }

        function removeTag(e) {
            console.log("remove tag", e.parentNode);
            e.parentNode.remove();
        }

</script>


<style>
    .tag { 
        /* display: inline-block; */
    }
    .tag-outer-container { 
        display: inline-block;
    }
    /* .tag_drop {
        width: 100%;
    } */
    /* .tag-input {
        display: block;
        flex: 1;
        margin: 10px;
    }

    .tag {
        border-radius: 5px;
        padding: 5px 10px;
        background-color: green;
        color: white;

        margin: 5px;
    }

    .tag-outer-container {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        padding: 5px;
        margin: 5px;
        width: fit-content;
        border: 1px solid lightgray;
        border-radius: 5px;

    } */

    .remove-button {
    
        border-radius: 50%;
        /* color: white;
         */
        /* background-color: r; */
        /* flex: 1; */
        aspect-ratio: 1/1;
        /* width: 30px;
        height: 30px; */
        text-align: center;
        /* margin: 10px; */
        width: 15px;
        cursor: pointer;
        transform: translate(-10px, 0px);
    }
</style>

