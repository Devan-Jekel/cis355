<?php
echo "<p>Link to Prog04 GitHub code: <a href='https://github.com/Devan-Jekel/cis355/tree/master/Prog04'>https://github.com/Devan-Jekel/cis355/tree/master/Prog04</a></p>";
printForm(); 

#-----------------------------------------------------------------------------
// display the entry form for course search
function printForm(){
	
	echo '<h2>Course Lookup</h2>';
	
	// print user entry form
	echo "<form action='courses.php'>";
	echo "Course Prefix (Department)<br/>";
	echo "<input type='text' placeholder='CS' name='prefix'><br/>";
	echo "Course Number<br/>";
	echo "<input type='text' placeholder='116' name='courseNumber'><br/>";
	echo "Instructor<br/>";
	echo "<input type='text' placeholder='gpcorser' name='instructor'><br/>";
        echo "Day<br/>";
        echo "<input type='text' placeholder='M' name='days'><br/>";
	//echo "Building/Room<br/>";
	//echo "<input type='text' name='building'>";
	//echo "<input type='text' name='room'><br/>";
	echo "<input type='submit' value='Submit'>";
	echo "</form>";
}
