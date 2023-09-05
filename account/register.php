<?php
    // include_once('../init.php'); // include initialisations
    // include_once('../classes/account.php'); // include account class
    // include_once('../classes/query_handler.php'); // include query handler class

    // if (!isset($_GET['dev'])) {
    //     Utils::navigate('welcome');
    //     exit;
    // }
    // the set_data function handles the registration errors and returns false if there is an error
    function set_data() { 

        if (!isset($_POST['name']) || $_POST['name'] == '') { // if the name field is empty
            echo "please enter your full name"; // set the error
            return false;   // return unsuccesful registration
        }

        if (count(Utils::split($_POST['name'], " ")) != 2) { // if the name field does not contain a first and last name
            echo "please enter your full name"; // set the error
            return false;  // return unsuccesful registration
        }

        if (!isset($_POST['email']) || $_POST['email'] == "") { // if the email field is empty
            echo "please enter your email"; // set the error
            return false; // return unsuccesful registration
        } 

        if (query_handler::check_for_account($_POST['email'])) { // if the email is already registered
            echo "sorry this email is already registered"; // set the error
            return false; // return unsuccesful registration
        }

        if (!isset($_POST['password']) || $_POST['password'] == "") { // if the password field is empty
            echo "password cannot be blank"; // set the error
            return false; // return unsuccesful registration
            
        }

        // if the function runs this far the email and password fields are not empty
        // and a register attempt can be made
        return query_handler::attempt_register($_POST['name'], $_POST['email'], $_POST['password']);
    }

    if (isset($_POST['register'])) { // when the register button is clicked
        if (set_data()) { // check for errors and attempt to register
            // if register attempt was succesful
            // navigate to the login page
            $_SESSION['login_error'] = ""; // clear the error
            Utils::navigate('login');
        } else {
            $_SESSION['login_error'] = "sorry something went wrong"; // set the error
        }
        
    }


    // include_once("../components/headers/html_header.php"); // include the html header with styles
    // include_once("../components/headers/account_header.php"); // include the navigation bar

?>

<!-- this is the registration box on the page -->
<div class ="container">

    <div class="error" ></div>
    <form method="post" class="inner-container">

        <h2 class="heading">Register</h2>

        <input class="input" type="text" placeholder="full name" name="name"/>
        <div class="spacer"></div>
        <input class="input" type="text" placeholder="email" name="email"/>
        <div class="spacer"></div>
        <input class="input" type="password" placeholder="password" name="password"/>
        <div class="spacer"></div>
        <input class="submit button" name="register" type="submit" value="register" />
        <br/>
        <p>already have an account? <a href="login.php">Login</a></p>
    </form>

</div>

<?php
    // include_once("../components/html_footer.php"); // include the html footer
?>