<?php 
include_once("connect.php");


if(isset($_POST['submit'])){
    if(isset($_POST['newUser']) && isset($_POST['newCode'])){
        $newUser = $_POST['newUser'];
        $newCode = $_POST['newCode'];


        $conn = Db::getInstance();

        $statement = $conn->prepare("INSERT INTO user(user_name, code, ingelogd) values(:user, :code, 0)");

$statement->bindValue(':user', $newUser);
$statement->bindValue(':code', $newCode);
$statement->execute();
    }
$message = "New user added!";
}


?>