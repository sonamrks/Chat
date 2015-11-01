<!-- Friends list page after success login -->

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

unset($_SESSION['friend']);
}

// --- Redirect to login page if session is destroyed
if(!isset($_SESSION['name'])) {
	header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>chat</title>
<link type="text/css" rel="stylesheet" href="style.css" />
</head>

<div id="wrapper">

<div id="menu">
<p class="logout"><b><a id="exit" href="#">Exit Chat</a><b></p>
<p class="welcome">Welcome, <b><span Style="font-size: 15pt"><?php echo $_SESSION['name']; ?></span></b></p><br>
<div id="addform">
<form method="post">
    Friend Name: <input type="text" name="friendname" id="friendname" size="15" /><br>
	<span class="error" id="error1"></span><br><br>
    <input type="submit" name="add" id="add" value="Add Friend">    
</form>
</div>
</div>

<div id="list">
<h2>My Friends</h2>
<div id="friendbox"></div>
</div>

</div>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js">
</script>

<script type="text/javascript">

$(document).ready(function(){

   $("#exit").click(function(){
       var exit = confirm("Are you sure you want to end the session?");
       if(exit==true){window.location = 'index.php?logout=true';}
   });

   // --- adds a friend to the list of friends using postfriend.php and it is saved in Data_Files/list_".$_SESSION['name'].".html file
   $("#add").click(function(){
      var fname = $("#friendname").val();
	  if(fname==""){
		  $("#error1").html(" Please enter a name to add");
	  }
	  else {
      $.post("postfriend.php", {text: fname}, function(result){
            $("#error1").html(result);
        });
	  $("#friendname").attr("value", "");
	  }
      return false;
   });
   
   setInterval (loadList, 500);
                              
//--- Function lo refresh the friends list 
function loadList(){
    var oldscrollHeight = $("#friendbox").attr("scrollHeight") - 20;

    $.ajax({ url: "Data_Files/list_<?php echo $_SESSION['name']; ?>.html",
             cache: false,
             success: function(html){
                $("#friendbox").html(html);
                var newscrollHeight = $("#friendbox").attr("scrollHeight") - 20;
                if(newscrollHeight > oldscrollHeight){
                    $("#friendbox").animate({ scrollTop: newscrollHeight }, 'normal'); 
                }
             },
    });
}

});
</script>