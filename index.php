<!-- Main login page to validate user credentials -->

<?php
session_start();

//---- Logs out of the session if Exit link is clicked 
if(isset($_GET['logout'])){
   $fp = fopen("Data_Files/log_".$_SESSION['name']."_".$_SESSION['friend'].".html", 'a');
   fwrite($fp, "<div class='msgln'><i>You have left the chat session.</i><br></div>");
   fclose($fp);

   $fp1 = fopen("Data_Files/log_".$_SESSION['friend']."_".$_SESSION['name'].".html", 'a');
   fwrite($fp1, "<div class='msgln' align='right'><i>User ". $_SESSION['name'] ." has left the chat session.</i><br></div>");
   fclose($fp1);

   $conn0 = mysqli_connect("localhost","root","sonam123","mychat");
   $sql00 = "UPDATE Users SET flag=0 WHERE username='".$_SESSION['name']."'";
   mysqli_query($conn0,$sql00);

   session_destroy();
   $_SESSION = array();
}

//--- Checks if user is already registered.
if(isset($_GET['register'])){
	$tmp="You have successfully registered.";
}

?>

<!DOCTYPE HTML> 
<html>
<head>
<link type="text/css" rel="stylesheet" href="style.css" />
</head>
<body> 
<div class="register"><b><?php echo $tmp; ?></b></div>

<!-- Function to remove unwanted data in the input --> 
<?php
function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}

//--- Function to execute if your are already logged in different session
function loggedIn() {
	echo '
	<div id="loggedin">
	<br><p><b>You have already logged in<b></p>
	<br><p><b>Please logout from other session and login<b></p>
	<br><a href="index.php" >Login Page</a>
	</div>
	';
	
}

$conn = mysqli_connect("localhost","root","sonam123","mychat");

$sql9 = "SELECT * FROM Users WHERE username='".$_POST['name']."' AND password='".$_POST['password']."' AND flag=1";
$result9=mysqli_query($conn,$sql9);
$row_cnt9 = mysqli_num_rows($result9);

//--- Check if your are already logged in with the same credentials (checks if flag is set to 1). Calls loggenIn() function.
if($row_cnt9 == 1){
    loggedIn();
} 
else{

//--- Check if your are already logged in different session. If true redirect to friends list page
$sql0 = "SELECT * FROM Users WHERE username='".$_SESSION['name']."' AND flag=1";
$result0=mysqli_query($conn,$sql0);
$row_cnt0 = mysqli_num_rows($result0);

if($row_cnt0 == 1){
    header("Location: friendslist.php");
} 
else{

// define variables and set to empty values
$nameErr = $passwordErr = "";
$name = $password = "";

// --- Validate the credentials after form post
 if ($_SERVER["REQUEST_METHOD"] == "POST") {
   if (empty($_POST["name"])) {
     $nameErr = "Username is required";
   } else {
     $name = test_input($_POST["name"]);
	 $_SESSION['name']=$name;
   }
   
   if (empty($_POST["password"])) {
     $passwordErr = "Password is required";
   } else {
     $password = test_input($_POST["password"]);
	 $_SESSION['password']=$password;
   } 

if (!empty($name) && !empty($password)) {

		 $sql1 = "SELECT * FROM Users WHERE username='".$name."'";
	     $result1=mysqli_query($conn,$sql1);
	     $row_cnt = mysqli_num_rows($result1);
	     if($row_cnt==0){
		    $nameErr = "User not registered.";
         } 
		 else {
			 $name = mysqli_real_escape_string($conn, $name);
			 $password = mysqli_real_escape_string($conn, $password);
			 $sql2 = "SELECT password FROM users where username='".$name."'";
			 $result2=mysqli_query($conn,$sql2);
			 $row = mysqli_fetch_assoc($result2);
			 
			 if($row["password"]==$password){
			   // --- Flag is set to 1 if your are logged in correctly
				$sql3 = "UPDATE Users SET flag=1 WHERE username='".$name."'";
                mysqli_query($conn,$sql3);
				header("Location: friendslist.php"); 
			 }
			 else {
                       $passwordErr = "Please enter the correct password";
             }
		 }
	
		 
         $conn->close();
         
 }
}

?>

<div id="container">
<h2>Enter the login details</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
   Username: <input type="text" name="name" value=<?php echo $_POST['name'];?>><br>
   <span class="error"><?php echo $nameErr;?></span>
   <br><br>
   Password: <input type="password" name="password" value=<?php echo $_POST['password'];?>>
   <span class="error"><?php echo $passwordErr;?></span>
   <br><br>
   <input type="submit" name="submit" id="submit1" value="Sign In"> 
</form>

<br><br><br>
New User? <input type="submit" name="submit" id="new" value="Register a New User"> 
</div>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js">
</script>

<script type="text/javascript">

$(document).ready(function(){

// If clicked on register new user, redirect to registration page i.e index2.php
  $("#new").click(function(){
       window.location = 'index2.php';
   });
});

</script>
<?php
}
}
?>

</body>
</html>
