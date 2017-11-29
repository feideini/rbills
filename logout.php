<?php
session_start();
session_destroy();
echo "Session ended. Back to <a href=\"http://ec2-35-160-211-119.us-west-2.compute.amazonaws.com/lpublic/rbills/login.php\">log in</a>.";
?>
