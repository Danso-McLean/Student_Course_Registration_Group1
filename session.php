<?php
session_start();

header('Content-Type: application/json');

$response = [
    "loggedIn" => false,
    "username" => "",
    "full_name" => ""
];

if(isset($_SESSION['username'])){
    $response["loggedIn"] = true;
    $response["username"] = $_SESSION['username'];
    $response["full_name"] = $_SESSION['full_name'] ?? $_SESSION['username'];
}

echo json_encode($response);
?>