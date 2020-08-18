<?php session_start(); ?>
<html>
 <head>
 <title>Team 10 - CS Courses</title>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">CS 411 - Team 10</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
    <div class="navbar-nav">
      <a class="nav-item nav-link" href="index.php">Home</a>
      <a class="nav-item nav-link active" href="#">CS Faculty <span class="sr-only">(current)</span></a>
      <a class="nav-item nav-link active" href="courses.php">CS Courses</a>
	</div>
</div>
</nav>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
 </head>
 <body>

	 <?php require_once 'professorsProcess.php'; ?>

	 <?php

	if (isset($_SESSION["message"])): ?>

	<div class="alert alert-<?=$_SESSION["msg_type"]?>">

		<?php
			echo $_SESSION["message"];
			unset($_SESSION["message"]);
		?>

	</div>

	<?php endif ?>

	 <div class = "container">

	 <?php
   $servername = "127.0.0.1";
   $username = "root";
   $password = "password123";
   $dbname = "final";
		$mysqli = new mysqli($servername, $username, $password, $dbname) or die(mysqli_error($mysqli));
		$result = $mysqli->query("select * from FacultyPartOf natural join Instructors natural join Departments where DepartmentID = (select DepartmentID from Departments where Department_Title = 'CS')") or die($mysqli->error);
		//pre_r($result)
		?>

		<div class = "row justify-content-center">
			<table class = "class">
				<thead>
					<tr>
						<th>InstructorID</th>
						<th>Instructor</th>
					</tr>
				</thead>
		<?php
			while ($row = $result->fetch_assoc()): ?>
				<tr>
					<td><?php echo $row["InstructorID"]; ?></td>
					<td><?php echo $row["Primary_Instructor"]; ?></td>
          <td>
            <a href="professors.php?editt=<?php echo $row["InstructorID"]; ?>"
              class="btn btn-info">Edit</a>
            <a href="delete.php?id=<?php echo $row["InstructorID"]; ?>"
							class="btn btn-danger">Delete</a>
            </td>
				</tr>
			<?php endwhile; ?>
			</table>
		</div>
		<?php

		function pre_r($array) {
			echo "<pre>";
			print_r($array);
			echo "</pre>";
		}
	 ?>

     <div class = "row justify-content-center">
	 <form action = "professorsProcess.php" method = "POST">
     <input type="hidden" name="InstructorID" value="<?php echo $idd ?>">
     <div class = "form-group">
     <label>ID</label>
     <input type = "text" name = "ID" class = "form-control" value = "<?php echo $id; ?>" placeholder = "Enter ID">
     </div>
     <div class = "form-group">
     <label>Name</label>
     <input type = "text" name = "Name" class = "form-control" value = "<?php echo $name; ?>" placeholder = "Enter Name">
     </div>
     <div class = "form-group">
     <label>Research Interests</label>
     <input type = "text" name = "Research_Interests" class = "form-control" value = "<?php echo $researchInterests; ?>" placeholder = "Enter Research Interests">
     </div>

     <div class = "form-group">
     <label>CS Classes Taught (CourseID)</label>
     <input type = "text" name = "Classes_Taught" class = "form-control" value = "<?php echo $classesTaught; ?>" placeholder = "Enter CS CourseIDs">
     </div>

     <div class = "form-group">
     <?php
     if ($update == true):
     ?>
      <button type = "submit" class = "btn btn-info" name = "Update">Update</button>
     <?php else: ?>
      <button type = "submit" class = "btn btn-primary" name = "Add">Add</button>
     <?php endif; ?>
     </div>


	   <div class = "form-group">
	   <label>Search by ID</label>
	   <input type = "text" name = "SearchByID" class = "form-control" value = "<?php echo $searchID; ?>" placeholder = "Enter CourseID here">
	   </div>
	   <div class = "form-group">
	   <button type = "submit" class = "btn btn-primary" name = "Search">Search</button>
	   </div>

	 </form>
	 </div>
	 </div>

 </body>
</html>
