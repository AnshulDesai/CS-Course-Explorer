<?php
session_start();
$id = $_GET['id'];

require 'vendor/autoload.php'; // include Composer's autoloader

$collection = (new MongoDB\Client)->nosqldb->csfaculty;
$servername = "127.0.0.1";
$username = "root";
$password = "mysqlUsernamePassword";
$dbname = "mydb";
 $mysqli = new mysqli($servername, $username, $password, $dbname) or die(mysqli_error($mysqli));

 $mysqli->query("DELETE FROM Instructors WHERE InstructorID=".$id) or die($mysqli->error());
 $mysqli->query("DELETE FROM FacultyPartOf WHERE InstructorID=".$id) or die($mysqli->error());
 $mysqli->query("DELETE FROM Teaches WHERE InstructorID=".$id) or die($mysqli->error());
 $collection->deleteOne(['_id' => (int)$id]);

 	$_SESSION["message"] = "Instructor deleted!";
 	$_SESSION["msg_type"] = "danger";
mysqli_close();
header("location: professors.php");
?>
