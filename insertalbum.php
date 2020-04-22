<?php
// Create connection
$conn=mysqli_connect("xxxxx","xxxxx","xxxxx","i308_dataset");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error() . "<br>");
}

// Grab POST Data
//escape variables for security sql injection
$title = mysqli_real_escape_string($conn, $_POST['form_title']);
$pub_year = mysqli_real_escape_string($conn, $_POST['form_pub_year']);
$publisher = mysqli_real_escape_string($conn, $_POST['form_publisher']);
$media = mysqli_real_escape_string($conn, $_POST['form_media']);
$band_id = mysqli_real_escape_string($conn, $_POST['form_band_id']);


if($title){
    $where = "where title = '".$title."' ";
}else{
    $where = '';
}

$sql = "SELECT * from album12 ".$where;
$result = mysqli_query($conn, $sql);
$num_rows = mysqli_num_rows($result);
if ($num_rows > 0) {
    echo "The title already exists<br>";
    // Close Connection
    mysqli_close($conn);
    exit;
}
//Insert query to insert form data into the band table
$sql = "INSERT INTO album12 (title, pub_year, publisher, media, band_id) VALUES ('$title','$pub_year','$publisher','$media','$band_id')";
//check for error on insert
if (!mysqli_query($conn,$sql)) {
    die('Error: ' . mysqli_error($conn)) . "<br>";
}

echo "Artist Added <br><br><br><br>";





if($title){
    $where = "where title = '".$title."' ";
}else{
    $where = '';
}

// Create SQL Statement
$sql = "SELECT * from album12 ".$where;

// Get Results
$result = mysqli_query($conn, $sql);

// Get Number of Rows
$num_rows = mysqli_num_rows($result);

// Display Results
if ($num_rows > 0) {
    echo "<table>";
    echo "<tr><th>ID</th><th>Title</th><th>Published Year</th><th>Publisher</th><th>Format</th><th>Band</th></tr>";
// Output data of each row, ->fetch_assoc gives array of arrays with keys matching column names
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["id"]."</td><td>".$row["title"]."</td><td>".$row["pub_year"]."</td>
<td>".$row["publisher"]."</td><td>".$row["media"]."</td><td>".$row["band_id"]."</td></tr>";
    }
    echo "</table>";
} else {
    echo "0 results <br>";
}
echo "$num_rows Rows <br>";

// Close Connection
mysqli_close($conn);


exit;
