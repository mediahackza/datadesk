
<nav>
    <a href="<?php echo $base ?>" ><div class="logo"><img src="<?php echo $base; ?>/assets/dd-logo.png"></div></a>

    <?php
    
    if (isset($_SESSION['user'])) {
        $account = user_obj();
        echo "<div class='account'>" . $account->get_full_name() . " &nbsp; | &nbsp; <a href='".$base."/account/logout.php'>logout</a></div>";
    } else {
        echo "<div class='account'><a href='".$base."/account/login.php'>Login</a> | <a href='".$base."/account/register.php'>Register</a></div>";
    }
        
    ?>

</nav>
