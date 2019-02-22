<?php

header('Content-Type: application/json');
include("config.php");
$code = 0;
$msg = "not inserted";
session_start();
$error = "";
$count = 0;
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_POST['user_id'];
    $score = $_POST['score'];

        $sql = "INSERT INTO user_score (user_id, score) 
  			  VALUES('$user_id', '$score')";

        $result = mysqli_query($db, $sql);
        if ($result == 1) {
            $code = 1;
            $msg = "User's score stored successfully";
        } else {
            $error = "Something went wrong";
         }
}
$data = new stdClass();
$data->code = $code;
$data->msg = $msg;
echo json_encode($data);
?>