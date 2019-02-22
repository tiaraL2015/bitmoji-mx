<?php

header('Content-Type: application/json');
include("config.php");
$code = 0;
$msg = "Invalid Username Or Password";
session_start();
$error = "";
$count = 0;
$row = [];
if($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_POST['user_id'];

    $sql = "SELECT id,score,date(created_at) as created_at FROM user_score WHERE user_id = '$user_id' ORDER BY score DESC LIMIT 5";
    $result = mysqli_query($db,$sql);

    if($result){
        $row = mysqli_fetch_all($result,MYSQLI_ASSOC);

        $count = mysqli_num_rows($result);
    }

    if($count == 1) {
        $code = 1;
        $msg = "Data fetched successfully";
    }else {
        $error = "Something went wrong";
    }
}
$data = new stdClass();
$data->code = $code;
$data->msg = $msg;
$data->data = $row;

echo json_encode($data);
?>