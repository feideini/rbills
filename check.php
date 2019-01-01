<?php
session_start();
ob_start();
$host="ip-172-31-16-20.us-west-2.compute.internal";
/* $host="ip-172-31-9-42.us-west-2.compute.internal"; */
// $host="ip-172-31-9-42"; // Host name 
$awsusername="ubuntu"; // Mysql username 
$db_password="3senuf"; // Mysql password 
$db_name="rbills"; // Database name 
$tbl_name="login"; // Table name 

// $a=1;
// session_register("a");

// Connect to server and select databse.
$mysqli = new mysqli($host,$awsusername,$db_password,$db_name);

// Oh no! A connect_errno exists so the connection attempt failed!
if ($mysqli->connect_errno) {
// The connection failed. What do you want to do? 
// You could contact yourself (email?), log the error, show a nice page, etc.
// You do not want to reveal sensitive information

// Let's try this:
echo "Sorry, this website is experiencing problems.";

// Something you should not do on a public site, but this example will show you
// anyways, is print out MySQL error related information -- you might log this
// echo "Error: Failed to make a MySQL connection, here is why: \n";
// echo "Errno: " . $mysqli->connect_errno . "\n";
// echo "Error: " . $mysqli->connect_error . "\n";

// You might want to show them something nice, but we will simply exit
exit;
}

// echo "The hostname is $host, the username is $username, the password is $password, the database name is $db_name, and the table name is $tbl_name.";

// Define $myusername and $mypassword 
$myusername=$_POST['myusername']; 
$mypassword=$_POST['mypassword']; 

// echo "My username is $myusername and my password is $mypassword.";

// To protect MySQL injection (more detail about MySQL injection)
// $myusername = stripslashes($myusername);
// $mypassword = stripslashes($mypassword);
// $myusername = mysql_real_escape_string($myusername);
// $mypassword = mysql_real_escape_string($mypassword);
// $mydepassword = md5($mypassword);

$sql="SELECT * FROM $tbl_name WHERE username='$myusername' and password='$mypassword'";
if (!$result=$mysqli->query($sql)) {
// Oh no! The query failed. 
echo "Sorry, the website is experiencing problems.";

// Again, do not do this on a public site, but we'll show you how
// to get the error information
// echo "Error: Our query failed to execute and here is why: \n";
// echo "Query: " . $sql . "\n";
// echo "Errno: " . $mysqli->errno . "\n";
// echo "Error: " . $mysqli->error . "\n";
exit;
}

// Phew, we made it. We know our MySQL connection and query 
// succeeded, but do we have a result?
// if ($result->num_rows === 0) {
// Oh, no rows! Sometimes that's expected and okay, sometimes
// it is not. You decide. In this case, maybe actor_id was too
// large? 
// echo "We could not find a match for ID $aid, sorry about that. Please try again.";
// exit;
// }

// Now, we know only one result will exist in this example so let's 
// fetch it into an associated array where the array's keys are the 
// table's column names
// $actor = $result->fetch_assoc();
// echo "Sometimes I see " . $actor['first_name'] . " " . $actor['last_name'] . " on TV.";

// Mysql_num_row is counting table row
// $count=mysql_num_rows($result); This is the old phpwebhosting rbills way OPRW
$count=$result->num_rows;
// echo " The count is $count.";
// If result matched $myusername and $mypassword, table row must be 1 row

// this code works but it is moot -- we don't need $un and $pw
// $data = mysqli_fetch_assoc( $result );
// $un = $data['username'];
// $pw = $data['password'];
// echo "$un is the username we are comparing to.";

if($count === 1){
// Register $myusername, $mypassword and redirect to file "login_success.php"
$_SESSION["myusername"] = "$myusername";
$_SESSION["mypassword"] = "$mypassword";
  /* header("location:http://ec2-35-160-211-119.us-west-2.compute.amazonaws.com/lpublic/rbills/statement.php"); */
  header("location:http://ec2-52-35-217-154.us-west-2.compute.amazonaws.com/lpublic/rbills/statement.php");
}
else {
echo "Wrong Username or Password";
}
mysqli_close($mysqli);
ob_end_flush();
?>
