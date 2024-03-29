<?php
    if (!isset($_SESSION['login_error'])) {
        $_SESSION['login_error'] = "";
    }

    $account; 

    // if (isset($_SESSION['user'])) { // check for an logged in user session
    //     $account = user_obj(); // if there is one, get the account object
    // } else {
    //     $account = new Account(); // otherwise create a new account object
    //     $_SESSION['user'] = serialize($account); // and save it in session
    // }

    if (isset($_POST['login'])) { // when the login button is pressed
        if ($account = set_data()) { // run the set data function to attempt a log in and retrieve the account data
            $_SESSION['user'] = serialize($account); // and save it in the session

            if (Utils::get_location('previous') != null) { // if there is a previous location
                Utils::navigate('previous'); // navigate to it
            } else {
                Utils::navigate('home'); // otherwise navigate to the home page
            }
        }      
    }

    
    // the set_data function handles login errors and returns false if there is an error
    // or the account object if the login is successful
    function set_data() { 
        global $account; // the the account object declared above
        $account = new Account(); // create a new account object
        if (!isset($_POST['email']) || $_POST['email'] == "") { // if the email field is empty
            $_SESSION['login_error'] = "please enter your email"; // set the error
            return false; // return unsuccesful login
        } 

    

        if (!isset($_POST['password']) || $_POST['password'] == "") { // if the password field is empty
            $_SESSION['login_error'] = "please enter your password"; // set the error
            return false; // return unsuccesful login
        }


        // if the function runs this far the email and password fields are not empty
        // and a login attempt will be made
        if (!$account->attempt_login($_POST['email'], $_POST['password'])) { // attenmpt a logion using email and password
            // if login attempt was unsuccesful
            
            $_SESSION['login_error'] = "invalid email address or password"; // set the error
            return false; // return unsuccesful login
        }

        $_SESSION['login_error'] = ""; // if the function runs this far there is no error
        return $account; // return the account object from the database 
    }
?>
<!-- this is the login box on the page -->
<div class="container">

    
    <form method="post" class="inner-container" >

        <h2 class="heading">Login</h2>

        <div class="error" >
                <?php 
        echo $_SESSION['login_error'];
        ?>
        </div>

        <input class="input" type="text" placeholder="email" name="email"  value=""/>
        <div class="spacer"></div>
        <input class="input" type="password" placeholder="password" name="password"/>
        <div class="spacer"></div>
        <input class="submit button" name="login" type="submit" value="login" /><br/>

        <!-- <p>Don't have an account yet? <a href="register.php">Register</a></p> -->
    </form>

</div>
