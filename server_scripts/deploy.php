<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "I'm working";
$output = shell_exec("./deploy.sh");
echo $output;
?>
