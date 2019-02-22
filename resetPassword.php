<?php
header('Content-Type: application/json');
include("config.php");
$code = 0;
$msg = "Email id is not registered with us";
session_start();
$error = "";
$count = 0;
$upd_result = "";
if($_SERVER["REQUEST_METHOD"] == "POST") {

      $pwd = md5($_POST['password']);
      $cpwd = $_POST['cpassword'];
      $forgot_pass_identity = $_POST['key'];

        $code = 1;
        $msg = "email id is registered";
        $uniqidStr = md5(uniqid(mt_rand()));;
        $upd_sql = "UPDATE user SET password='$pwd' WHERE forgot_pass_identity = '$forgot_pass_identity'";

        //$upd_result = mysqli_query($db,$upd_sql);
        if(mysqli_query($db,$upd_sql) && mysqli_affected_rows($db) >= 1){
            $res_pwd_link = $uniqidStr;
            $msg = "Password reset successfully";
        }else{
            $msg = "Something went wrong";
        }
}
$data = new stdClass();
$data->code = $code;
$data->msg = $msg;
//$data->updCount = $upd_result;
echo json_encode($data);
?>