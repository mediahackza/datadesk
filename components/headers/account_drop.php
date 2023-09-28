<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

if (isset($_POST['menu_choice'])) {
    $_SESSION['account_nav_data'] = $_POST['menu_choice'];
    switch ($_POST['menu_choice']) {
        default:
            break;
        case 'logout':
            header("Location: ".$base."/logout");
            break;
        case 'bookmarks':
            Utils::navigate('bookmarks');
            break;
        case 'collections':
            Utils::navigate('collections');
            break;
        case 'trash':
            Utils::navigate('trash');
            break;
    }
}


?>

<form id="account-menu" method="post">
    <select name="menu_choice" id="account-menu" onchange="submitAccountForm()">
        <option value='account'  >My account</option>
        <option value=''>My profile</option>
        <option value="collections" >Collections</option>
        <option value='bookmarks' <?php if (Utils::is_selected('/bookmarks')) { echo "selected";} ?> >My bookmarks</option>
        <option value="trash" <?php if (Utils::is_selected('/trash')) {echo "selected" ;} ?> >Trash</options>
        <option value="logout">Logout</option>
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