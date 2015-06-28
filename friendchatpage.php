<!-- Friends chat room after after clicking on a friend to chat -->

<?php
session_start();
$text = $_GET['tname'];
$_SESSION['friend']=$text;

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
<div id="wrapper2">

<div id="menu">
<p class="logout"><b><a id="exit" href="#">Exit Chat</a></b></p>	
<p class="welcome">Welcome, <b><span Style="font-size: 15pt"><?php echo $_SESSION['name']; ?></span></b></p><br><br><br>
<p class="welcome">Your friend is <b><?php echo $text; ?></b></p>
<p class="logout"><b><a id="back" href="#">Friends List</a></b></p><br>
<div style="clear:both"></div>
</div>

<div id="chatbox"></div>

<div id="messagebox">
<form name="message" action="">
<input name="usermsg" type="text" id="usermsg" size="63" />
<input type="hidden" name="hidden" id="hidden" value=<?php echo $text; ?>>
<input name="submitmsg" type="submit"  id="submitmsg" value="Send" />
</form>
<div>

</div>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js">
</script>

<script type="text/javascript">

$(document).ready(function(){
  
   $("#exit").click(function(){
       var exit = confirm("Are you sure you want to end the session?");
       if(exit==true){window.location = 'index.php?logout=true';}
   });

   $("#back").click(function(){
       window.location = 'friendslist.php?logout=true';
   });

// Save chat messages in a file "Data_Files/log_".$_SESSION['name']."_".$fname.".html" by calling post.php
   $("#submitmsg").click(function(){
      var clientmsg = $("#usermsg").val();
      var frndname = $("#hidden").val();
      $.post("post.php", {text: clientmsg,fname: frndname});
      $("#usermsg").attr("value", "");
      return false;
   });
   
   setInterval (loadLog, 1000);
                              

//--- Function lo refresh the friends chat messages
function loadLog(){
    var oldscrollHeight = $("#chatbox").attr("scrollHeight") - 20;

    $.ajax({ url: "Data_Files/log_<?php echo $_SESSION['name']; ?>_<?php echo $text; ?>.html",
             cache: false,
             success: function(html){
                $("#chatbox").html(html);
                var newscrollHeight = $("#chatbox").attr("scrollHeight") - 20;
                if(newscrollHeight > oldscrollHeight){
                    $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); 
                }
             },
    });
}
});
</script>