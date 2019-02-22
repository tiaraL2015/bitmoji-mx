<?php
header('Content-Type: application/json');
include("config.php");
$code = 0;
$msg = "Email id is not registered with us";
session_start();
$error = "";
$count = 0;
$upd_result = false;
$uniqidStr= "";
if($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];

    $sql = "SELECT id,username,email FROM user WHERE email = '$email'";
    $result = mysqli_query($db,$sql);

    if($result){
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
        $active = $row['email'];
        $count = mysqli_num_rows($result);

    }


    // If result matched $myusername and $mypassword, table row must be 1 row

    if($count == 1) {
        $code = 1;
        $msg = "email id is registered";
        $uniqidStr = md5(uniqid(mt_rand()));;
        $upd_sql = "UPDATE user SET forgot_pass_identity='$uniqidStr' WHERE email = '$email'";
        $upd_result = mysqli_query($db,$upd_sql);
        if($upd_result){
            $res_pwd_link = $uniqidStr;
        }
    }else {
        $error = "Your Login Name or Password is invalid";
    }
}
$data = new stdClass();
$data->code = $code;
$data->msg = $msg;
$data->key = $uniqidStr;
$data->updCount = $upd_result;
$data->URL = "/reset?fp_key=".$uniqidStr;
mail($email,"Reset Password Link","http://142.4.15.100:5000".$data->URL);

echo json_encode($data);
?>