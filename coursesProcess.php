<?php

session_start();



$servername = "127.0.0.1";
$username = "root";
$password = "password123";
$dbname = "final";
$mysqli = new mysqli($servername, $username, $password, $dbname) or die(mysqli_error($mysqli));

$update = false;
$subject = "";
$number = "";
$courseTitle = "";
$searchID = "";
$courseID = 0;

if (isset($_POST["Add"])){
	$number = $_POST["Number"];
	$courseTitle = $_POST["Course_Title"];

	$mysqli->query("INSERT INTO courses (Subject, Number, Course_Title) VALUES('CS', '$number', '$courseTitle')") or die($mysqli->error);

	$_SESSION["message"] = "Course added!";
	$_SESSION["msg_type"] = "success";

	header("location: courses.php");
}

if (isset($_GET["delete"])){
	$courseID = $_GET["delete"];
	$mysqli->query("DELETE FROM courses WHERE CourseID=$courseID") or die($mysqli->error());

	$_SESSION["message"] = "Course deleted!";
	$_SESSION["msg_type"] = "danger";

	header("location: courses.php");
}

if (isset($_GET["edit"])){
	$courseID = $_GET["edit"];
	$update = true;
	$result = $mysqli->query("SELECT * FROM courses WHERE CourseID=$courseID") or die($mysqli->error());
	if (count(array($result))==1){
		$row = $result->fetch_array();
		$subject = $row["Subject"];
		$number = $row["Number"];
		$courseTitle = $row["Course_Title"];
	}
}

if (isset($_POST["Update"])){
	$courseID = $_POST["CourseID"];
	$number = $_POST["Number"];
	$courseTitle = $_POST["Course_Title"];

	$mysqli->query("UPDATE courses SET Number='$number', Course_Title='$courseTitle' WHERE CourseID=$courseID") or die($mysqli->error());

	$_SESSION["message"] = "Course updated!";
	$_SESSION["msg_type"] = "warning";

	header("location: courses.php");
}

if (isset($_POST["Search"])){
	$subjectID = $_POST["SearchByID"];

	$result = $mysqli->query("SELECT * FROM courses WHERE CourseID=$subjectID AND Subject='CS'") or die($mysqli->error);
	if(mysqli_num_rows($result)==1){
		$row = $result->fetch_array();
		$subject = $row["Subject"];
		$number = $row["Number"];
		$courseTitle = $row["Course_Title"];

		$_SESSION["message"] = "Search result: ".$subject." ".$number." ".$courseTitle."<br>";

		$result = $mysqli->query("select * from Instructors natural join Teaches natural join Courses where CourseID =$subjectID") or die($mysqli->error);
		if(mysqli_num_rows($result)>0){
			$_SESSION["message"] .= "Instructors Who Have Taught This Course:<br>";
			$number = 1;
			while($row = $result->fetch_assoc()){
				$_SESSION["message"] .= $number.". ".$row['Primary_Instructor']."<br>";
				$number++;
			}
			$_SESSION["msg_type"] = "success";
		} else{
			$_SESSION["message"] .= "No Instructors have taught this course";
			$_SESSION["msg_type"] = "success";
		}
	} else {
		$_SESSION["message"] = "INVALID COURSE ID: Please be sure to enter a CourseID of a CS Course Only";
		$_SESSION["msg_type"] = "success";
	}

	header("location: courses.php");
}
