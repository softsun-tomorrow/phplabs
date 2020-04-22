<!doctype html>
<html>
<body>
<h2>Album Table</h2>
<h3>Insert a new Album</h3>
<form action="albuminsert.php" method="POST">
Title: <input type="text" name="title" maxlength=500 size=50 required><br><br>
Band: <select name='bid'>
<option value="105"></option><option value="111"></option><option value="85">2010</option><option value="75">aasdf</option><option value="71">Acrobates pygmaeus</option><option value="11">Alectura lathami</option><option value="72">Ammospermophilus nelsoni</option><option value="101">AYYYYYYYYY</option><option value="95">band</option><option value="96">band</option><option value="97">band</option><option value="98">Band123</option><option value="74">bobo band</option><option value="28">Corythornis cristata</option><option value="107">eagles</option><option value="113">gbjljg</option><option value="115">ghhh</option><option value="110">hello</option><option value="76">Hello</option><option value="100">HELLO</option><option value="79">John</option><option value="91">no</option><option value="77">No</option><option value="90">no</option><option value="89">No thank you</option><option value="86">No thank you</option><option value="87">No thank you</option><option value="88">No thank you</option><option value="92">One Direction </option><option value="73">Ovis ammon</option><option value="33">Plectopterus gambensis</option><option value="108">Post</option><option value="93">Roses</option><option value="94">Roses</option><option value="83">Roses</option><option value="84">Roses</option><option value="109">s</option><option value="102">sdgsdg</option><option value="103">sdgsdg</option><option value="22">Spermophilus richardsonii</option><option value="1">Tayassu pecari</option><option value="78">test</option><option value="104">test</option><option value="106">test</option><option value="81">test2</option><option value="80">test2</option><option value="82">test3</option><option value="99">ybnl</option><option value="112">yes</option><option value="114">yoyo</option> 
    </select>
</br><br>
Published Year: <input type="number" name="pyear" min="1900" max="2020" required><br><br>
Publisher: <input type="text" name="title" maxlength=500 size=50 required><br><br>
Format: <select name="format">
    <option value=Album"/ >Album</option>
    <option value="CD">CD</option>
    <option value="WAV">WAV</option>
    <option value="MP3">MP3</option>
  </select><br><br>
<input type="submit" value="Insert Album">  (Button does not work on purpose)
</form>
<h3>Select all Albums</h3>
<form action="albumselect12.php" method="post">
<input type="submit" name="submit" value="Select Album table">  (Button does not work on purpose)
</form>
</body>
</html>

