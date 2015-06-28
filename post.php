<!-- phpcode to save friends messages to a file -->

<?php
session_start();
if(isset($_SESSION['name'])){
   $text = $_POST['text'];
   $fname = $_POST['fname'];

   $fp = fopen("Data_Files/log_".$_SESSION['name']."_".$fname.".html", 'a');
   fwrite($fp, "<div class='msgln'>(".date("g:i A").") <b>You</b>: ".stripslashes(htmlspecialchars($text))."<br></div>");
   fclose($fp);

   $fp1 = fopen("Data_Files/log_".$fname."_".$_SESSION['name'].".html", 'a');
   fwrite($fp1, "<div class='msgln' align='right'>(".date("g:i A").") <b>".$_SESSION['name']."</b>: ".stripslashes(htmlspecialchars($text))."<br></div>");
   fclose($fp1);
}
?>