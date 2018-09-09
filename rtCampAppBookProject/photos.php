<?php
//setting max time limit of php script
set_time_limit(3000000000);
if(!session_id()){
    session_start();
}

// Get album id and user-id from url

$album_id = isset($_GET['album_id'])?$_GET['album_id']:header("Location: test.php");
$album_name = isset($_GET['album_name'])?$_GET['album_name']:header("Location: test.php");
//$user_id = isset($_GET['user_id'])?$_GET['user_id']:header("Location: index.php");
// Get access token from session
$access_token = $_SESSION['fb_access_token'];

// Get photos of Facebook page album using Facebook Graph API URL 
$graphPhoLink = "https://graph.facebook.com/v2.9/{$album_id}/photos?fields=source,images,name&access_token={$access_token}";
//echo $graphPhoLink;

$jsonData = file_get_contents($graphPhoLink);
//echo $jsonData; 
$fbPhotoObj = json_decode($jsonData, true, 512, JSON_BIGINT_AS_STRING);

// Facebook photos content
$fbPhotoData = $fbPhotoObj['data'];
//var_dump($fbPhotoData);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Album Photos</title>
<style type="text/css">
.page-breadcrumb{
    width: 100%;
    float: left;
    font-size: 18px;
    margin-bottom: 20px;
    /*margin-top:10px;*/
}
.fb-album{
    width: 25%;
    padding: 10px;
    float: left;
    box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
    margin-left: 10px;
}
.fb-album img{width: 100%;height: 200px;}
.fb-album h3{font-size: 18px;}
.fb-album p{font-size: 14px;}
</style>
</head>
<body>
<div class="page-breadcrumb">
    <a href="test.php">Facebook Albums</a> / <?php echo $album_name; ?>
</div>
<?php

//Render all photos

foreach($fbPhotoData as $data){
    //echo "1";
    $imageData = $data['images'][2];
    //var_dump($data['images'][2]);
    $imgSource = isset($imageData['source'])?$imageData['source']:'';
    //echo $imgSource. "<br>";
    $name = isset($data['name'])?$data['name']:'';
    
    //echo "<div class='fb-album'>";
    echo "<img src='{$imgSource}' alt=''>";
    echo "<h3>{$name}</h3>";
    //echo "</div>";
}
?>
</body>
</html>