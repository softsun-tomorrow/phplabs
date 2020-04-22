<?php
// Create connection
$conn=mysqli_connect("db.soic.indiana.edu","i308_data","my+sql=i308_data","i308_dataset");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error() . "<br>");
}

// Grab POST Data
//escape variables for security sql injection
$sanbtitle = mysqli_real_escape_string($conn, $_POST['form_bandtitle']);
$sanyearf = mysqli_real_escape_string($conn, $_POST['form_yearformed']);

//Insert query to insert form data into the band table
$sql = "INSERT INTO band12 (title, year_formed) VALUES ('$sanbtitle','$sanyearf')";
//check for error on insert
if (!mysqli_query($con,$sql))
{ die('Error: ' . mysqli_error($con)) . "<br>"; }

echo "Artist Added <br>";


















/**

// Create SQL Statement
$sql = "SELECT * from emp_shift where role = '$var_role'";

// Get Results
$result = mysqli_query($conn, $sql);

// Get Number of Rows
$num_rows = mysqli_num_rows($result);

// Display Results
if ($num_rows > 0) {
    echo "<table>";
	echo "<tr><th>Shift ID</th><th>Employee ID</th><th>wdate</th><th>time_in</th><th>time_out</th><th>role</th></tr>";
    // Output data of each row, ->fetch_assoc gives array of arrays with keys matching column names
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["shiftid"]."</td><td>".$row["empid"]."</td><td>".$row["wdate"]."</td>
		      <td>".$row["time_in"]."</td><td>".$row["time_out"]."</td><td>".$row["role"]."</td></tr>";
    }
    echo "</table>";
} else {
    echo "0 results <br>";
}
echo "$num_rows Rows <br>";
**/

// Close Connection
mysqli_close($conn);
?>
