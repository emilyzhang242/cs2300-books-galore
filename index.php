<!DOCTYPE html>
<html>
<head>
	<title>Books Galore! </title>
	<link href="css/styles.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
</head>
<body style="background-image:url('http://bgfons.com/upload/books_texture3018.jpg'); background-size: cover">

	<!-- start: nav-bar -->

	<ul class="nav-bar">
		<li><a href="index.php" id="active_page">Home</a></li>
		<li><a href="add.php">Add Books</a></li>
	</ul>

	<!-- end: nav-bar -->

	<h1 class="title" style="text-align: center"> Books Galore </h1>
	<div>

		<div class="search">
			<form action="index.php" method="post" style="line-height: 40px">
				<p style="line-height: 0px">Search Bar: </p>
				<p style="font-size: 15px; line-height: 15px">Pick at least one element.</p>
				<label for="bookname"> Book Name: </label>
				<input id="bookname" class="form-el" type="text" name="bookname">
				<br>
				<label for="author">Author:</label> 
				<input id="author" class="form-el" type="text" name="author">
				<br>
				<label for="genre">Genre:</label>
				<select id="genre" class="form-el" name="genre">
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
				<select id="rating" class="form-el" name="rating">
					<option value="none"> -- select -- </option>
					<option value="&#9733;&#9734;&#9734;&#9734;&#9734;">&#9733;&#9734;&#9734;&#9734;&#9734;</option>
					<option value="&#9733;&#9733;&#9734;&#9734;&#9734;">&#9733;&#9733;&#9734;&#9734;&#9734;</option>
					<option value="&#9733;&#9733;&#9733;&#9734;&#9734;">&#9733;&#9733;&#9733;&#9734;&#9734;</option>
					<option value="&#9733;&#9733;&#9733;&#9733;&#9734;">&#9733;&#9733;&#9733;&#9733;&#9734;</option>
					<option value="&#9733;&#9733;&#9733;&#9733;&#9733;">&#9733;&#9733;&#9733;&#9733;&#9733;</option>
				</select><br>
				<input type="submit" name="submit" value="Submit">
				<input type="submit" name="reset" value="Reset">
				<input type="submit" name="clear" value="Clear Books">
			</form>

			<?php
			if (isset($_POST['submit'])) {

				#list out search results so user remembers
				echo "<p style='line-height: 0px'>Search Results:</p><br>";

				$checks = array();
				$criteria = array('Name', 'Author', 'Genre', 'Rating');

				$bookname = $_POST['bookname'];
				$match_book = preg_match('/^[A-z ]+$/', $bookname);
				$author = $_POST['author'];
				$match_author = preg_match('/^[A-Z][a-z]+ [A-Z][a-z]+$/', $author);
				$genre = $_POST['genre']; 
				$rating = $_POST['rating'];

				#if format is wrong
				if ($match_book === False || $match_author === False) {
					echo "<p style='font-size: 15px; color: red;line-height: 0px'>Your formatting is incorrect!</p>";
				}else{ 
					if ($bookname !== "") {$checks[] = $bookname;}else{$checks[]="";}
					if ($author !== "") {$checks[] = $author;}else{$checks[]="";}
					if ($_POST['genre'] !== "none") {$checks[] = $genre;}else{$checks[]="";}
					if ($_POST['rating'] !== "none") {$checks[] = $rating;}else{$checks[]="";}

					#tell user what they searched for
					for ($x=0; $x<sizeof($checks); $x++) {
						if ($checks[$x] !== "") {
							echo "<p style='font-size: 12px'>$criteria[$x]: $checks[$x]</p>";
						}
					}
				}
			}

			if (isset($_POST['clear'])) {
				$fh = fopen( 'data.txt', 'w' );
				fclose($fh);
			}
			?>

		</div>
		<?php 
		if(!isset($_POST['submit']) || isset($_POST['reset'])){
			echo "<table>";
			echo "<tr>";
			echo"<th>Name</th>";
			echo"<th>Author</th>";
			echo"<th>Genre</th>";
			echo"<th>Rating</th>";
			echo"<th>Review</th>";
			echo"</tr>";
			include('php/table_calc.php');
			echo "</table>";
		}else{

			$delimiter = '|';
			$checks = array();
			$results = False;

			$bookname = $_POST['bookname'];
			$author = $_POST['author'];
			$genre = $_POST['genre']; 
			$rating = $_POST['rating']; 

			$array = file("data.txt");   

			#add elements to array of things to check in order
			if ($bookname !== "") {$checks[] = $bookname;}else{$checks[]="";}
			if ($author !== "") {$checks[] = $author;}else{$checks[]="";}
			if ($_POST['genre'] !== "none") {$checks[] = $genre;}else{$checks[]="";}
			if ($_POST['rating'] !== "none") {$checks[] = $rating;}else{$checks[]="";}

			#this for loop merely checks whether any search results match
			#if not, we don't build the table.
			foreach ($array as $book) {
				$check = True;
				$comp = explode($delimiter, $book);
				for ($x=0; $x < sizeof($checks); $x++) {
					if ($checks[$x] !== "" && strtolower($checks[$x]) !== strtolower($comp[$x])) {
						$check = False;
					}
				}
				if ($check == True) {
					$results = True;
					break;
				}
			}
				#we build the table if there is at least one search result that matches
			if ($results == True) {
				echo "<table>";
				echo "<tr>";
				echo"<th>Name</th>";
				echo"<th>Author</th>";
				echo"<th>Genre</th>";
				echo"<th>Rating</th>";
				echo"<th>Review</th>";
				echo"</tr>";
				foreach ($array as $book) {
					#each book starts out as accepted into table
					$check = True;
					$comp = explode($delimiter, $book);
					#if doesn't match for any element, set check to false
					for ($x=0; $x < sizeof($checks); $x++) {
						if ($checks[$x] !== "" && strtolower($checks[$x]) !== strtolower($comp[$x])) {
							$check = False;
						}
					}
					#build table
					if ($check == True) {
						echo "<tr>";
						for ($y=0; $y < sizeof($comp); $y++) {
							echo "<td>$comp[$y]</td>";
						}
						echo "</tr>";
					}
				}
				echo "</table>";
			}
			if ($results == False) {
				echo "<p style='position: absolute; top:225px; left:390px'> No results matched your search. </p>";
			}
		}
		?>
	</div>

	<p class="credits"> Wallpaper thanks to BGFons.com </p>

</body>
</html>