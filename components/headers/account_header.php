
<nav>
    <a href="<?php echo $base ?>" ><div class="logo"><img src="<?php echo $base; ?>/assets/dd-logo.png"></div></a>

    <?php
    
    if (isset($_SESSION['user'])) {
        $account = user_obj();
    ?>
        <div class='account'><?php echo $account->get_full_name();?> &nbsp; | &nbsp;<?php include_once('account_drop.php')?></div>
    <?php     
    } else {
    ?>
        <div class='account'><a href='<?php echo $base."/account/login.php";?>'>Login</a> | <a href='<?php echo $base."/account/register.php";?>'>Register</a></div>
    <?php 
    }
        
    ?>

</nav>
