<?php
// Toevoeg aan db
include_once("connect.php");

if(isset($_GET['code'])){
    $code = $_GET['code'];
echo $code;
$conn = Db::getInstance();
//catch code
$statement = $conn->prepare("INSERT INTO inputs(code, geldig) values(:code, :geldig)");

$statement->bindValue(':code', $code);
$statement->bindValue(':geldig', 0);
$statement->execute();

} else {
    echo 'code not fouded';
}
//header("Location: index.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
Code has been saved in DB!
</body>
</html>
