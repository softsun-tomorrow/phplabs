<?php
// Create connection
$conn=mysqli_connect("xxxxx","xxxxx","xxxxx","i308_dataset");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error() . "<br>");
}

// Grab POST Data
//escape variables for security sql injection
$fname = mysqli_real_escape_string($conn, $_POST['form_fname']);
$lname = mysqli_real_escape_string($conn, $_POST['form_lname']);
$dob = mysqli_real_escape_string($conn, $_POST['form_dob']);
$hometown = mysqli_real_escape_string($conn, $_POST['form_hometown']);
$gender = mysqli_real_escape_string($conn, $_POST['form_gender']);


if($fname){
    $where = "where fname = '".$fname."' and lname = '".$lname."' ";
}else{
    $where = '';
}

$sql = "SELECT * from artist12 ".$where;
$result = mysqli_query($conn, $sql);
$num_rows = mysqli_num_rows($result);
if ($num_rows > 0) {
    echo "The First Name and Last Name already exists<br>";
    // Close Connection
    mysqli_close($conn);
    exit;
}
//Insert query to insert form data into the band table
$sql = "INSERT INTO artist12 (fname, lname, dob, hometown, gender) VALUES ('$fname','$lname','$dob','$hometown','$gender')";
//check for error on insert
if (!mysqli_query($conn,$sql)) {
    die('Error: ' . mysqli_error($conn)) . "<br>";
}

echo "Artist Added <br><br><br><br>";





if($fname){
    $where = "where fname = '".$fname."' and lname = '".$lname."' ";
}else{
    $where = '';
}

// Create SQL Statement
$sql = "SELECT * from artist12 ".$where;

// Get Results
$result = mysqli_query($conn, $sql);

// Get Number of Rows
$num_rows = mysqli_num_rows($result);

// Display Results
if ($num_rows > 0) {
    echo "<table>";
    echo "<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Dob</th><th>Hometown</th><th>gender</th></tr>";
// Output data of each row, ->fetch_assoc gives array of arrays with keys matching column names
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["id"]."</td><td>".$row["fname"]."</td><td>".$row["lname"]."</td>
<td>".$row["dob"]."</td><td>".$row["hometown"]."</td><td>".$row["gender"]."</td></tr>";
    }
    echo "</table>";
} else {
    echo "0 results <br>";
}
echo "$num_rows Rows <br>";

// Close Connection
mysqli_close($conn);


exit;
