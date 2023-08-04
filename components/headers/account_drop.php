<?php

if (isset($_POST['menu_choice'])) {
    $_SESSION['account_nav_data'] = $_POST['menu_choice'];
    switch ($_POST['menu_choice']) {
        default:
            break;
        case 'logout':
            header("Location: ".$base."/account/logout.php");
            break;
        case 'bookmarks':
            Utils::navigate('bookmarks');
            break;
    }
}

$current_dir = $_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
$temp_base = str_replace("http://", "", $base);
$temp_base = str_replace("https://", "", $temp_base);

function is_selected($dir) {
    global $current_dir, $temp_base;

    
    // echo $current_dir;
    // echo " === " . $temp_base . $dir;
    // echo "<br/><br/>";

    if ($current_dir == $temp_base . $dir) {
        return true;
    } else {
        return false;
    }
}

?>

<form id="account-menu" method="post">
    <select name="menu_choice" id="account-menu" onchange="submitAccountForm()">
        <option value='account' <?php if(is_selected("")) { echo "selected";} ?> >my account</option>
        <option value=''>my profile</option>

        <option value='bookmarks' <?php if (is_selected('/bookmarks/index.php')) { echo "selected";} ?> >my bookmarks</option>
        <option value="logout">logout</option>
    </select>
</form>

<script>
    const form = document.getElementById('account-menu');
    const select = document.getElementById('select');


    function submitAccountForm() {
        console.log('chnage in scciount select has occured');
        form.submit();
    }
</script>