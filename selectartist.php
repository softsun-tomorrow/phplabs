<?php
// Create connection
$conn=mysqli_connect("118.25.5.174","liutengfei","ltf107834","i308_dataset");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error() . "<br>");
}

if($_POST['form_fname']){
    $where = "where fname = '".$_POST['form_fname']."'";
}else{
    $where = '';
}

// Create SQL Statement
$sql = "SELECT *, concat(fname,' ',lname) as full_name from artist12 ".$where ." order by lname";

// Get Results
$result = mysqli_query($conn, $sql);

// Get Number of Rows
$num_rows = mysqli_num_rows($result);

// Display Results
if ($num_rows > 0) {
echo "<table>";
echo "<tr><th>ID</th><th>Full Name</th><th>Last Name</th><th>Dob</th><th>Hometown</th><th>gender</th></tr>";
// Output data of each row, ->fetch_assoc gives array of arrays with keys matching column names
while($row = $result->fetch_assoc()) {
echo "<tr><td>".$row["id"]."</td>
<td>".$row["full_name"]."</td>
<td>".$row["lname"]."</td>
<td>".date_format(date_create($row["dob"]), 'M jS,Y')."</td>
<td>".$row["hometown"]."</td>
<td>".$row["gender"]."</td></tr>";
}
echo "</table><br>";
} else {
echo "0 results <br>";
}
echo "$num_rows Rows <br>";

// Close Connection
mysqli_close($conn);
?>
