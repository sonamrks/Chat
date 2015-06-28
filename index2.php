<!-- User registration page if not registered-->

<?php
session_start();
?>

<!DOCTYPE HTML> 
<html>
<head>
<link type="text/css" rel="stylesheet" href="style.css" />
</head>
<body> 

<?php
// --- Define variables and set to empty values
$fnameErr = $newpasswordErr = $newnameErr = "";
$fname = $newpassword = "";

// --- Validate the credentials after form post
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   if (empty($_POST["fname"])) {
     $fnameErr = "Name is required";
   } else {
     $fname = test_input($_POST["fname"]);
   }

   if (empty($_POST["newname"])) {
     $newnameErr = "Username is required";
   } else {
     $newname = test_input($_POST["newname"]);
   }
   
   if (empty($_POST["newpassword"])) {
     $newpasswordErr = "Password is required";
   } else {
     $newpassword = test_input($_POST["newpassword"]);
   }
  
   if (!empty($fname) && !empty($newname) && !empty($newpassword)) {
	  
	  $conn = mysqli_connect("localhost","root","sonam123","mychat");

      if (mysqli_connect_errno())
      {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
      }
	  
	  $newname = mysqli_real_escape_string($conn, $newname);
	  $newpassword = mysqli_real_escape_string($conn, $newpassword);
	  
	  //--- Checks if user is already registered.
	  $sql0 = "SELECT * FROM Users WHERE username='".$newname."'";
	  $result0=mysqli_query($conn,$sql0); 
	  $row_cnt0 = mysqli_num_rows($result0);

      if($row_cnt0 == 1){
         echo '
		 <div class="register">User is already registered.</div>
		 ';
      } 
	  else {
	  $sql = "INSERT INTO Users VALUES ('".$fname."','".$newname."','".$newpassword."',0);";
	  mysqli_query($conn,$sql); 
      $conn->close();
	  
      session_destroy();
      header("Location: index.php?register=true");
	  }
   }
}

// Function to remove unwanted data in the input 
function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}
?>

<div id="container2">
<h2>Enter below details to register</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
   Full Name: <input type="text" name="fname" value=<?php echo $fname;?>>*<br>
   <span class="error"> <?php echo $fnameErr;?></span>
   <br><br>
   Create a username: <input type="text" name="newname" value=<?php echo $newname;?>>*<br>
   <span class="error1"> <?php echo $newnameErr;?></span>
   <br><br>
   Create a password: <input type="newpassword" name="newpassword" value=<?php echo $newpassword;?>>*<br>
   <span class="error1">    <?php echo $newpasswordErr;?></span>
   <br><br>
   <input type="submit" name="submit" id="submit2" value="Enter"> 
</form>
</div>

</body>
</html>