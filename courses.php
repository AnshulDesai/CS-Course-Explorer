<?php session_start(); ?>
<html>
 <head>
  <title>Team 10 - CS Faculty</title>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">CS 411 - Team 10</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
    <div class="navbar-nav">
      <a class="nav-item nav-link" href="index.php">Home</a>
      <a class="nav-item nav-link" href="professors.php">CS Faculty</a>
      <a class="nav-item nav-link active" href="#">CS Courses <span class="sr-only">(current)</span></a>
	</div>
</div>
</nav>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
 </head>
 <body>

	 <?php require_once 'coursesProcess.php'; ?>

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
		$result = $mysqli->query("SELECT * FROM courses where Subject = 'CS'") or die($mysqli->error);
		//pre_r($result)
		?>

		<div class = "row justify-content-center">
			<table class = "class">
				<thead>
					<tr>
            <th>CourseID</th>
						<th>Subject</th>
						<th>Number</th>
						<th>Course Title</th>
						<th colspan = "2">Action</th>
					</tr>
				</thead>
		<?php
			while ($row = $result->fetch_assoc()): ?>
				<tr>
          <td><?php echo $row["CourseID"]; ?></td>
					<td><?php echo $row["Subject"]; ?></td>
					<td><?php echo $row["Number"]; ?></td>
					<td><?php echo $row["Course_Title"]; ?></td>
					<!-- <td><?php echo $row["CourseID"]; ?></td> -->
					<td>
						<a href="courses.php?edit=<?php echo $row["CourseID"]; ?>"
							class="btn btn-info">Edit</a>
						<a href="coursesProcess.php?delete=<?php echo $row["CourseID"]; ?>"
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
	 <form action = "coursesProcess.php" method = "POST">
	   <input type="hidden" name="CourseID" value="<?php echo $courseID ?>">
	   <div class = "form-group">
	   <label>Number</label>
	   <input type = "text" name = "Number" class = "form-control" value = "<?php echo $number; ?>" placeholder = "Enter Number here">
	   </div>
	   <div class = "form-group">
	   <label>Course Title</label>
	   <input type = "text" name = "Course_Title" class = "form-control" value = "<?php echo $courseTitle; ?>" placeholder = "Enter Course Title here">
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
	   <label>Search By CourseID</label>
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
