<!DOCTYPE html>
<html>
<head>
	<title>Add Books! </title>
	<link href="css/styles.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
</head>
<body style="background-image:url('https://wallpaperscraft.com/image/vintage_paper_cameo_key_pen_book_76277_1400x1050.jpg')">

	<?php 

	$delimiter = '|';

	#add book
	if(isset($_POST['submit'])){

		$bookname = $_POST['bookname'];
		$match_book = preg_match('/^[A-z ]+$/', $bookname);
		$author = $_POST['author'];
		$match_author = preg_match('/^[A-Z][a-z]+ [A-Z][a-z]+$/', $author);
		$genre = $_POST['genre']; 
		$rating = $_POST['rating'];
		$review = filter_input(INPUT_POST, 'review', FILTER_SANITIZE_STRING);

		$duplicate = check_duplicates($bookname, $author);  

		if ($match_book == True && $match_author == True && $duplicate == False) {
			$file = fopen("data.txt", 'a');   

			if (!$file) {
				die("There was a problem opening the data.txt file");
			}

			if ($_POST['genre'] == "none") {
				$genre = "N/A";
			}
			if ($_POST['rating'] == "none") {
				$rating = "&#9734;&#9734;&#9734;&#9734;&#9734;";
			}


			$line = "$bookname$delimiter$author$delimiter$genre$delimiter$rating$delimiter$review\n";
			fwrite($file, $line);
			fclose($file);
		}

	}

	#delete book
	if(isset($_POST['delete'])){

		$bookname = $_POST['bookname'];
		$author = $_POST['author']; 
		$match_book = preg_match('/^[A-z ]+$/', $bookname);
		$match_author = preg_match('/^[A-Z][a-z]+ [A-Z][a-z]+$/', $author);

		if ($match_book == True && $match_author == True) {

			$file = file("data.txt");
			$open_file = fopen("data.txt", "w");

			foreach ($file as $line) {
				$comp = explode($delimiter, $line);
				if (strtolower($comp[0]) !== strtolower($bookname) || strtolower($comp[1]) !== strtolower($author)) {
					fwrite($open_file, $line);
				}
			}
			fclose($open_file);

			echo "You have deleted a book!";
		}
	}

	

	#can't have same bookname and author
	function check_duplicates($bookname, $author) {

		global $delimiter;

		$array = file("data.txt");

		foreach ($array as $book) {
			$comp = explode($delimiter, $book);
			if (strtolower($comp[0]) == strtolower($bookname) && strtolower($comp[1]) == strtolower($author)) {
				return True;
			}
		}
		return False;
	}

	?>
	<!-- start: nav-bar -->
	<ul class="nav-bar">
		<li><a href="index.php">Home</a></li>
		<li><a href="add.php" id="active_page">Add Books</a></li>
	</ul>

	<!-- end: nav-bar -->

	<h1 class="title" style="text-align: center"> Add/Delete Books </h1>

	<div class="container">

		<p> Inputs labeled with * are required. </p>

		<!-- start: check duplicate input -->
		<?php
		if(isset($_POST['submit'])){
			if ($duplicate == True) {
				echo "<p style='color: red'> This is a duplicate! </p>";
			}elseif ($match_book == True && $match_author == True) {
				echo "<p> You have added a book!</p>";
			}
		}

		if(isset($_POST['delete'])){
			if ($match_book == True && $match_author == True) {
				echo "<p> You have deleted a book!</p>";
			}
		}

		?>
		<!-- end: check duplicate input -->

		<form action="add.php" method="post" style="line-height: 50px">

			<label for="bookname"> Book Name*: </label>
			<input class="form-el" id="bookname" type="text" name="bookname">

			<!-- start: check bookname input -->
			<?php
			if(isset($_POST['submit']) || isset($_POST['delete'])){
				if ($bookname == "") {
					echo "<span class='warning'>You need to type a book name! </span>";
				}elseif ($match_book == False) {
					echo "<span class='warning'>Letters, spaces, & apost. only!</span>";
				}
			}
			?>
			<!-- end: check bookname input -->

			<br>
			<label for="author">Author*:</label> 
			<input class="form-el" id="author" type="text" name="author">

			<!-- start: check author input -->
			<?php
			if(isset($_POST['submit']) || isset($_POST['delete'])){
				if ($author == ""){
					echo "<span class='warning'>Please enter an author.</span>";
				}elseif ($match_author == False) {
					echo "<span class='warning'>First Last only (First letters CAPS!)</span>";
				}
			}
			?>
			<!-- end: check author input -->

			<br>
			<label for="genre">Genre:</label>
			<select class="form-el" id="genre" name="genre">
				<option value="none"> -- select -- </option>
				<option value="Dystopian">Dystopian</option>
				<option value="Fantasy">Fantasy</option>
				<option value="Historical">Historical</option>
				<option value="Mystery">Mystery</option>
				<option value="Non-fiction">Non-fiction</option>
				<option value="Paranormal">Paranormal</option>
				<option value="Romance">Romance</option>
				<option value="Science Fiction">Science Fiction</option>
				<option value="Thriller">Thriller</option>
				<option value="Young Adult">Young Adult</option>
			</select><br>
			<label for="rating">Rating:</label>
			<select class="form-el" id="rating" name="rating">
				<option value="none"> -- select -- </option>
				<option value="&#9733;&#9734;&#9734;&#9734;&#9734;">&#9733;&#9734;&#9734;&#9734;&#9734;</option>
				<option value="&#9733;&#9733;&#9734;&#9734;&#9734;">&#9733;&#9733;&#9734;&#9734;&#9734;</option>
				<option value="&#9733;&#9733;&#9733;&#9734;&#9734;">&#9733;&#9733;&#9733;&#9734;&#9734;</option>
				<option value="&#9733;&#9733;&#9733;&#9733;&#9734;">&#9733;&#9733;&#9733;&#9733;&#9734;</option>
				<option value="&#9733;&#9733;&#9733;&#9733;&#9733;">&#9733;&#9733;&#9733;&#9733;&#9733;</option>
			</select><br>
			<label for="review">Review:</label><br>
			<textarea id="review" class="form-el" name="review" rows="20" cols="70" style="max-width: 400px; max-height: 300px">
			</textarea><br>
			<input type="submit" name="submit" value="Add Book">
			<input type="submit" name="delete" value="Delete Book">
		</form>
	</div>
	<p class="credits">Wallpaper found on WallpapersCraft</p>

</body>
</html>