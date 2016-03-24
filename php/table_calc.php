<?php 

$delimiter = '|';

$array = file("data.txt");

foreach ($array as $book) {
	$comp = explode($delimiter, $book);
		#begin building table
	echo "<tr>";
	for ($x=0; $x < sizeof($comp); $x++) {
		echo "<td>$comp[$x]</td>";
	}
	echo "</tr>";
}

?>