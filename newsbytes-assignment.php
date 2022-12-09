<?php
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'sample_db';
$base_url='http://localhost/myapp/'; 

//Step 3: Get the URL in querystring and return a shortened URL using following code:
if(isset($_GET['url']) && $_GET['url']!="")
{ 
$url=urldecode($_GET['url']);
if (filter_var($url, FILTER_VALIDATE_URL)) 
{
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
} 
$slug=GetShortUrl($url);
$conn->close();

echo $base_url.$slug;


} 
else 
{
die("$url is not a valid URL");
}
 
}
else
{	?>
<center>
<h1>Put Your Url Here</h1>
<form action='shortenurl.php'>
<p><input style="width:500px" type="url" name="url" required /></p>
<p><input type="submit" /></p>
</form>
</center>
<?php
}

//Now we'll create a function called GetShortUrl for creating the short URL:

function GetShortUrl($url){
 global $conn;
 $query = "SELECT * FROM url_shorten WHERE url = '".$url."' "; 
 $result = $conn->query($query);
 if ($result->num_rows > 0) {
$row = $result->fetch_assoc();
 return $row['short_code'];
} else {
$short_code = generateUniqueID();
$sql = "INSERT INTO url_shorten (url, short_code, hits)
VALUES ('".$url."', '".$short_code."', '0')";
if ($conn->query($sql) === TRUE) {
return $short_code;
} else { 
die("Unknown Error Occured");
}
}
}


//The above function is using the generateUniqueID() function to generate a unique id for long urls. We can generate and retrieve the unique ID like so:
function generateUniqueID(){
 global $conn; 
 $token = substr(md5(uniqid(rand(), true)),0,6); $query = "SELECT * FROM url_shorten WHERE short_code = '".$token."' ";
 $result = $conn->query($query); 
 if ($result->num_rows > 0) {
 generateUniqueID();
 } else {
 return $token;
 }
}

/* Now your code is ready to generate a unique short code for long URLs,
 but we still need to setup the redirection. When redirecting it should also increase a pageview/ hits in the table and then redirect to the longer, original URL.

Here is the code that allows us to do this: */

if(isset($_GET['redirect']) && $_GET['redirect']!="")
{ 
$slug=urldecode($_GET['redirect']);
 
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
$url= GetRedirectUrl($slug);
$conn->close();
header("location:".$url);
exit;
}


/* This code uses a function called GetRedirectUrl() that we need to define, like so: */

function GetRedirectUrl($slug){
 global $conn;
 $query = "SELECT * FROM url_shorten WHERE short_code = '".addslashes($slug)."' "; 
 $result = $conn->query($query);
 if ($result->num_rows > 0) {
$row = $result->fetch_assoc();
$hits=$row['hits']+1;
$sql = "update url_shorten set hits='".$hits."' where id='".$row['id']."' ";
$conn->query($sql);
return $row['url'];
}
else 
 { 
die("Invalid Link!");
}
}

/* 
Now if you want to convert any url to short url just pass the url in get param of index.php like following:

http://localhost/myapp/?url=http://www.google.com

or 

http://localhost/myapp/index.php?url=http://www.google.com 

*/
?>
