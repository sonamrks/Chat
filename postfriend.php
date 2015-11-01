<!-- phpcode to save friends list to a file -->

<?php
session_start();
if(isset($_SESSION['name'])){
   $text = $_POST['text'];
   $conn = mysqli_connect("localhost","root","sonam123","mychat");
   
   $sql0 = "SELECT * FROM Friends WHERE user='".$_SESSION['name']."' AND fname='".$text."'";
   $result0=mysqli_query($conn,$sql0); 
   $row_cnt0 = mysqli_num_rows($result0);
	  
   if($row_cnt0 == 1) {
     echo " Is already your friend!";
   }
   else {   
     $sql = "INSERT INTO Friends VALUES('".$_SESSION['name']."','".$text."')";   
     mysqli_query($conn,$sql);  
   
     $var = "Data_Files/list_".$_SESSION['name'].".html";

     $fp = fopen($var, 'a');
     fwrite($fp, "<div id='msgln'><a href='friendchatpage.php?tname=".$text."'style='color:blue; font-size: 12pt; margin-bottom:30px;'><b>".$text."</a></b></div><br>");
     fclose($fp);
   }
   
}
?>