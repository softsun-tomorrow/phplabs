<?php
// Create connection
$conn=mysqli_connect("118.25.5.174","liutengfei","ltf107834","i308_dataset");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error() . "<br>");
}

if($_POST['title']){
    $where = "where title = '".$title."' ";
}else{
    $where = '';
}

// Create SQL Statement
$sql = "SELECT *,band12.title AS band_title from album12 LEFT JOIN band12 ON band12.id=album12.band_id ".$where;

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
<td>".$row["publisher"]."</td><td>".$row["media"]."</td><td>".$row["band_title"]."</td></tr>";
    }
    echo "</table><br>";
} else {
    echo "0 results <br>";
}
echo "$num_rows Rows <br>";

// Close Connection
mysqli_close($conn);
?>
