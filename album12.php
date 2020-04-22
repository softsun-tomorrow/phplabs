<!doctype html>
<html>
<body>
<h2>Album Table</h2>
<h3>Insert a new Album</h3>
<form action="insertalbum.php" method="POST">
    Title: <input type="text" name="form_title" maxlength=500 size=50 required><br><br>
    Band: <select name='form_band_id' required>
        <?php while($row = $result->fetch_assoc()) { ?>
             <option value="<?php echo $row['id'];?>"><?php echo $row['title'];?></option>
        <?php } ?>
          </select>
    </br><br>
    Published Year: <input type="number" name="form_pub_year" min="1900" max="2025" required><br><br>
    Publisher: <input type="text" name="form_publisher" maxlength=500 size=50 required><br><br>
    Format: <select name="form_media" required>
        <option value=Album"/ >Album</option>
        <option value="CD">CD</option>
        <option value="WAV">WAV</option>
        <option value="MP3">MP3</option>
    </select><br><br>
    <input type="submit" value="Insert Album">  (Button does not work on purpose)
</form>
<h3>Select all Albums</h3>
<form action="selectalbum.php" method="post">
    <input type="submit" name="submit" value="Select Album table">  (Button does not work on purpose)
</form>
</body>
</html>