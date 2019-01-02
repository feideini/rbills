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

$username=$_SESSION['myusername'];
if(!isset($_SESSION['myusername']))
  {
    header("location:http://ec2-52-35-217-154.us-west-2.compute.amazonaws.com/lpublic/rbills/login.php");
}
else
  {
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

/* functions */
// This function didn't work because within the function definition mysqli wasn't defined
// I solved this by painstakingly writing out this function for each instance below
/*
function convertMonthNumberToMonthString($mn)
{
$sqlqM="SELECT * FROM months WHERE monthNumber=$mn";
$result20=$mysqli->query($sqlqM);
while($row = mysqli_fetch_array($result20))
  {
    return $row[1];
  }
}
*/

function itemCodeToDescription($ic)
{
  if ($ic=="PAY")
    {
      return "Rental Payment";
    }
  elseif ($ic=="SEC")
    {
      return "Security Deposit";
    }
  elseif ($ic=="PET")
    {
      return "Pet Deposit";
    }
  elseif ($ic=="SDP")
    {
      return "Security Deposit Payment";
    }
  elseif ($ic=="PDP")
    {
      return "Pet Deposit Payment";
    }
  elseif ($ic=="UFM")
    {
      return "Utilities First Month";
    }
  elseif ($ic=="UTD")
    {
      return "Utility Discount";
    }
  elseif ($ic=="MAT")
    {
      return "Improvement Materials";
    }
  elseif ($ic=="MAP")
    {
      return "Improvement Materials Payment";
    }
  elseif ($ic=="WAT")
    {
      return "City of Vancouver Water";
    }
  elseif ($ic=="BCH")
    {
      return "BC Hydro";
    }
  elseif ($ic=="TRD")
    {
      return "Temporary Rent Discount";
    }
  elseif ($ic=="TUD")
    {
      return "Temporary Utility Discount";
    }
  elseif ($ic=="LAN")
    {
      return "Landscaping";
    }
  elseif ($ic=="TEX")
    {
      return "Tenant Expenses";
    }
  elseif ($ic=="LEX")
    {
      return "Landlord Expenses";
    }
  elseif ($ic=="WFI")
    {
      return "Wifi";
    }
  elseif ($ic=="FMP")
    {
      return "Pre-First-Month Prorated";
    }
  else
    {
      return "no description";
    }
}

setlocale(LC_MONETARY, 'en_US');
$year = date("Y");
$month = date("m");
$day = date("d");

$sqlqR="SELECT * FROM renters WHERE shortName=\"$username\"";
$result01=$mysqli->query($sqlqR);
while($row = mysqli_fetch_array($result01))
  {
    $renter_id=$row[0];
    $fullName=$row[1];
    $shortName=$row[2];
    $rentalUnit=$row[3];
    $rentAmount=$row[4];
    $percentageTerasen=$row[5];
    $percentageBchydro=$row[6];
    $firstMonth=$row[7];
  }

// echo "The full name is $fullName, the rent amount is $rentAmount.";

$sqlqS="SELECT * FROM rentalUnits WHERE id=\"$rentalUnit\"";
$result02=$mysqli->query($sqlqS);
while($row = mysqli_fetch_array($result02))
  {
    $rentalProperty=$row[1];
    $addressLine=$row[4];
    $municipality=$row[5];
    $province=$row[6];
    $postalCode=$row[7];
  }

$currentMonthString=$year."-".$month;
$sqlqT="SELECT * FROM months WHERE monthString=\"$currentMonthString\"";
$result10=$mysqli->query($sqlqT);
while($row = mysqli_fetch_array($result10))
  {
    $currentMonthNumber=$row[0];
  }
$nextMonthNumber=$currentMonthNumber+1;
$sqlqM1="SELECT * FROM months WHERE monthNumber=$nextMonthNumber";
$result21=$mysqli->query($sqlqM1);
while($row = mysqli_fetch_array($result21))
  {
     $nextMonthString=$row[1];
  }
// $nextMonthString=convertMonthNumberToMonthString($nextMonthNumber);

if ($username=="admin")
  {
echo "<html>";
echo "<head><title>Rental Bill for $fullName</title></head>";
echo "<body>";
echo "You are logged in as the administrator.";
echo "</body>";
echo "</html>";
  }
else
  {
echo "<html>";
echo "<head><title>Rental Statement for $fullName</title></head>";
echo "<body>";
echo "<h3>Rental Statement for $fullName</h3>";
echo "<p>Address of Unit: $addressLine, $municipality, $province, $postalCode</p>";
echo "<p>Total Payable: please see below table.</p>";
echo "<table border=5>";
echo "<tr><td><tt>Date</tt></td><td><tt>Description</tt></td><td><tt>Amount</tt></td></tr>";
$rentAmountString=money_format("%!i",$rentAmount);

/* this is current rent */
echo "<tr><td><tt>$nextMonthString-01</tt></td><td><tt>Rent for $nextMonthString</tt></td><td align=\"right\"><tt>$rentAmountString</tt></td></tr>";
$total=$rentAmount;

for ($s=0;$s<=$currentMonthNumber-$firstMonth;++$s)
{
  $r=$currentMonthNumber-$s;

/* this is stateItems */
$sqlqU="SELECT * FROM stateItems WHERE renterid=\"$renter_id\" AND month=$r";
$result03=$mysqli->query($sqlqU);
$c1=0;
while($row = mysqli_fetch_array($result03))
  {
    $itemMonth[$c1]=$row[1];
    $itemDay[$c1]=$row[2];
    $itemCode[$c1]=$row[3];
    $itemAmount[$c1]=$row[4];
    ++$c1;
  }
$numberOfItems=$c1;
for ($i1=0;$i1<$numberOfItems;++$i1)
{
  $description=itemCodeToDescription($itemCode[$i1]);
  $itemAmountString=money_format("%!i",$itemAmount[$i1]);

$sqlqM2="SELECT * FROM months WHERE monthNumber=$itemMonth[$i1]";
$result22=$mysqli->query($sqlqM2);
while($row = mysqli_fetch_array($result22))
  {
     $t=$row[1];
  }
//  $t=convertMonthNumberToMonthString($itemMonth[$i1]);

echo "<tr><td><tt>$t-01</tt></td><td><tt>$description</tt></td><td align=\"right\"><tt>$itemAmountString</tt></td></tr>";
$total=$total+$itemAmount[$i1];
}

/* this is utilities */
$sqlqV="SELECT * FROM utilities WHERE property=\"$rentalProperty\" AND month=$r";
$result05=$mysqli->query($sqlqV);
$c2=0;
while($row = mysqli_fetch_array($result05))
  {
    $utilsString[$c2]=$row[2];
    $utilsAmount[$c2]=$row[3];
    ++$c2;
  }
for ($i8=0;$i8<$c2;++$i8)
{
  if ($utilsString[$i8]=="BC Hydro")
    {
  $uAmount=$utilsAmount[$i8]*$percentageBchydro;
    }
  elseif ($utilsString[$i8]=="Terasen")
    {
  $uAmount=$utilsAmount[$i8]*$percentageTerasen;
    }
  else
    {
  $uAmount=-1;
    }
$uAmountString=money_format("%!i",$uAmount);

$sqlqM3="SELECT * FROM months WHERE monthNumber=$r";
$result23=$mysqli->query($sqlqM3);
while($row = mysqli_fetch_array($result23))
  {
     $thisMonthString=$row[1];
  }
// $thisMonthString=convertMonthNumberToMonthString($r);

echo "<tr><td><tt>$thisMonthString-01</tt></td><td><tt>$utilsString[$i8] for $thisMonthString</tt></td><td align=\"right\"><tt>$uAmountString</tt></td></tr>";
$total=$total+$uAmount;
}

/* this is monthly rent */
$sqlqM4="SELECT * FROM months WHERE monthNumber=$r";
$result24=$mysqli->query($sqlqM4);
while($row = mysqli_fetch_array($result24))
  {
     $thisMonthString=$row[1];
  }
// $thisMonthString=convertMonthNumberToMonthString($r);
echo "<tr><td><tt>$thisMonthString-01</tt></td><td><tt>Rent for $thisMonthString</tt></td><td align=\"right\"><tt>$rentAmountString</tt></td></tr>";
$total=$total+$rentAmount;

}
echo "</table>";
$totalString=money_format("%!i",$total);
echo "<p>The Total Payable is <font color=\"red\">\$$totalString</font>. Please make your cheque out to Stefan or Sherry Lukits. The due date is $nextMonthString-01.</p>";
echo "<p><a href=\"http://ec2-52-35-217-154.us-west-2.compute.amazonaws.com/lpublic/rbills/logout.php\">log out</a></p>";
echo "</body>";
echo "</html>";
  }
  }
?>
// $host="ip-172-31-16-20.us-west-2.compute.internal";
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
/* echo "Entschuldigung, this website is experiencing problems."; */
echo "Error: Failed to make a MySQL connection, here is why: \n";
echo "Errno: " . $mysqli->connect_errno . "\n";
echo "Error: " . $mysqli->connect_error . "\n";

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
