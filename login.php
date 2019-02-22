<?php
   header('Content-Type: application/json');
   include("config.php");
   $code = 0;
   $msg = "Invalid Username Or Password";
   session_start();
   $error = "";
   $count = 0;
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      
      $myusername = $_POST['name'];
      $mypassword = md5($_POST['password']); 
      
      $sql = "SELECT id,username FROM user WHERE username = '$myusername' and password = '$mypassword'";
      $result = mysqli_query($db,$sql);
	
	  if($result){
		    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
			$active = $row['id'];
			$count = mysqli_num_rows($result);
	  }
      
      
      // If result matched $myusername and $mypassword, table row must be 1 row
		
      if($count == 1) {
		  $code = 1;
		  $msg = "Successfully login";
      //   session_register("myusername");
         $_SESSION['login_user'] = $myusername;
         
         //header("location: welcome.php");
      }else {
         $error = "Your Login Name or Password is invalid";
      }
   } 
   $data = new stdClass();
   $data->code = $code;
   $data->msg = $msg;
   $data->name = $myusername;
   $data->id = $row['id'];

echo json_encode($data);
?>