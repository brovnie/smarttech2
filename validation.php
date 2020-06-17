<?php
// Returns all users
// Validate code sends error of succes as response    
// 

include_once("connect.php");

$conn = Db::getInstance();

$error = false;

//
//Get all users
$statement = $conn->prepare("SELECT * from user");
$statement->execute();
$users = $statement->fetchAll();

//
//Validate Code

//get last code
$statement = $conn->prepare("SELECT * from inputs ORDER BY id DESC LIMIT 1");
$statement->execute();
$result = $statement->fetch();
$code = $result['code'];


// check code
$statement = $conn->prepare("SELECT * from user WHERE code = :code");
$statement->bindValue(':code', $code);
$statement->execute();
$validate = $statement->rowCount();

date_default_timezone_set('Europe/Dublin');
$date = date('Y-m-d H:i:s');

if ($validate == 1) {
    $user = $statement->fetch();
    $statement = $conn->prepare("UPDATE user SET ingelogd = 1, inglod_time = :dateNow WHERE code = :code");
    $statement->bindValue(':code', $code);
    $statement->bindValue(':dateNow', $date);
    $statement->execute();
    $_GET['status'] = "success";
    $code = "";
} else {
    $statement = $conn->prepare("SELECT * from errors WHERE code = :code");
    $statement->bindValue(':code', $code);
    $statement->execute();
    $validateError = $statement->rowCount();

    if ($validateError == 0) {
        $error = true;
        $statement = $conn->prepare("INSERT INTO errors(code, time) values(:code, :time)");
        $statement->bindValue(':code', $code);
        $statement->bindValue(':time', $date);
        $statement->execute();
        $_GET['status'] = "error";
    }
}
//get all errors

$statement = $conn->prepare("SELECT * from errors ");
$statement->execute();
$errors = $statement->fetchAll();

//get all check ins
$statement = $conn->prepare("SELECT * from user WHERE ingelogd = 1");
$statement->execute();
$usersBinnen =  $statement->fetchAll();
