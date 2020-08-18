<?php

session_start();
require 'vendor/autoload.php'; // include Composer's autoloader

$collection = (new MongoDB\Client)->nosqldb->csfaculty;



$servername = "127.0.0.1";
$username = "root";
$password = "password123";
$dbname = "final";
$mysqli = new mysqli($servername, $username, $password, $dbname) or die(mysqli_error($mysqli));

$update = false;
$idd = 0;
$name = "";


// if (isset($_GET["deleteeeee"])){
// 	$idd = (int)$_GET["deleteeeee"];
// 	$deleteInstructor = "DELETE FROM Instructors WHERE InstructorID=".$idd;
// 	$deleteFaculty = "DELETE FROM FacultyPartOf WHERE InstructorID=".$idd;
// 	$mysqli->query($deleteInstructor);
// 	$mysqli->query($deleteFaculty);
// 	//$mysqli->query("DELETE FROM Instructors WHERE InstructorID=".$idd) or die($mysqli->error());
// 	//$mysqli->query("DELETE FROM FacultyPartOf WHERE InstructorID=".$idd) or die($mysqli->error());
// 	$collection->deleteOne(['_id' => (int)$idd]);
// 	$_SESSION["message"] = "Instructor deleted!";
// 	$_SESSION["msg_type"] = "danger";
//
// 	header("location: testProfessors.php");
// }


if (isset($_GET["editt"])){
	$idd = (int)$_GET["editt"];
	$update = true;
	$result = $mysqli->query("SELECT * FROM Instructors WHERE InstructorID=$idd") or die($mysqli->error());
	if (count(array($result))==1){
		$row = $result->fetch_array();
		$id = $row["InstructorID"];
		$name = $row["Primary_Instructor"];
		$researchInterests = "";
		$document = $collection->findOne(['_id' => $idd]);
		if($document['researchInterests'] != NULL){
			for ($i = 0; $i < $document['researchInterests']->count(); $i++){
				if(($i+1) == $document['researchInterests']->count()){
					$researchInterests .= $document['researchInterests'][$i];
				} else {
					$researchInterests .= $document['researchInterests'][$i].",";
				}
			}
		}
		$result = $mysqli->query("SELECT * FROM Teaches WHERE InstructorID=$idd") or die($mysqli->error());
		$classesTaught = "";
		while($row = $result->fetch_assoc()){
			$classesTaught .= $row['CourseID'].",";
		}
	}
}

if (isset($_POST["Update"])){
	$id = (int)$_POST["ID"];
	$name = $_POST["Name"];
	$researchInterests = explode(',',$_POST["Research_Interests"]);
	$classesTaught = explode(',',$_POST["Classes_Taught"]);
	$mysqli->query("Delete from Teaches where InstructorID=$idd") or die($mysqli->error);
	for($i = 0; $i < count($classesTaught); $i++){
		$classID = (int)$classesTaught[$i];
		$mysqli->query("INSERT INTO Teaches (InstructorID, CourseID) VALUES('$id', '$classID')") or die($mysqli->error);
	}
	$idd = (int)$_POST["InstructorID"];
	$mysqli->query("UPDATE Instructors SET InstructorID='$id', Primary_Instructor='$name' WHERE InstructorID=$idd") or die($mysqli->error());
	$mysqli->query("UPDATE FacultyPartOf SET InstructorID='$id' WHERE InstructorID=$idd") or die($mysqli->error());
	$collection->updateOne(
    ['_id' => $idd],
    ['$set' => ['researchInterests' => $researchInterests]]);

		$collection->updateOne(
			['_id' => $idd],
			['$set' => ['name' => $name]]);

			if($id != $idd){
				$doc = $collection->findOne(['_id' => $idd]);
				$doc->_id = $id;
				$collection->insertOne($doc);
				$collection->deleteOne(['_id' => $idd]);
			}



	$_SESSION["message"] = "Instructor updated!";
	$_SESSION["msg_type"] = "warning";

	header("location: professors.php");
}

if (isset($_POST["Add"])){
	$id = (int)$_POST["ID"];
	$name = $_POST["Name"];
	$researchInterests = explode(',',$_POST["Research_Interests"]);
	$classesTaught = explode(',',$_POST["Classes_Taught"]);
	$collection->insertOne([
    '_id' => (int)$id,
    'name' => $name,
    'researchInterests' => $researchInterests
]);
	$mysqli->query("INSERT INTO Instructors (Primary_Instructor, InstructorID) VALUES('$name', '$id')") or die($mysqli->error);
	$mysqli->query("INSERT INTO FacultyPartOf (DepartmentID, InstructorID) VALUES(38, '$id')") or die($mysqli->error);
	for($i = 0; $i < count($classesTaught); $i++){
		$classID = (int)$classesTaught[$i];
		$mysqli->query("INSERT INTO Teaches (InstructorID, CourseID) VALUES('$id', '$classID')") or die($mysqli->error);
	}
	$_SESSION["message"] = "CS Instructor Added!";
	$_SESSION["msg_type"] = "success";
	header("location: professors.php");
}

if (isset($_POST["Search"])){
	$passed = true;
	$subjectID = (int)$_POST["SearchByID"];
	$result = $mysqli->query("select * from FacultyPartOf natural join Instructors
	 natural join
	 Departments where DepartmentID = (select DepartmentID from Departments where Department_Title = 'CS')
	 and InstructorID = $subjectID") or die($mysqli->error);
	if(mysqli_num_rows($result)==0){
		$_SESSION["message"] = "INVALID INSTRUCTOR ID: Please be sure to enter an InstructorID of a CS Faculty Member Only";
		$passed = false;
	} else {
		$row = $result->fetch_array();
		$subject = $row["Primary_Instructor"];
		$cursor = $collection->findOne(['_id' => $subjectID]);
		if($cursor[researchInterests] != NULL){
			$_SESSION["message"] = "Research Interests of ".$subject."<br>";
			for ($i = 0; $i < $cursor['researchInterests']->count(); $i++){
				$number = $i + 1;
				$_SESSION["message"] .= $number.". ".$cursor['researchInterests'][$i]."<br>";
			}
		} else {
			$_SESSION["message"] .= "No Research Interests Listed for ".$subject."<br>";
		}
			$result = $mysqli->query("select * from Instructors natural join Teaches natural join Courses where InstructorID=$subjectID") or die($mysqli->error);
			if(mysqli_num_rows($result)==0){
				$_SESSION["message"] .= "<br>".$subject." has not taught any classes<br>";
			} else {
				$_SESSION["message"] .= "<br> Classes taught by ".$subject."<br>";
				$number = 1;
				while($row = $result->fetch_assoc()){
					$_SESSION["message"] .= $number.". ".$row['Subject']." ".$row['Number']." - ".$row['Course_Title']."<br>";
					$number++;
				}
			}
	}
	if($passed){
		$_SESSION["msg_type"] = "success";
	} else {
		$_SESSION["msg_type"] = "danger";
	}
	header("location: professors.php");
}
