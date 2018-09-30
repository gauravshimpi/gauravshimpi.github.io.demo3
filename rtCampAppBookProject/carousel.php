<?php
//setting max time limit of php script
set_time_limit(3000000000);
if(!session_id()){
    session_start();
}

// Get album id and user-id from url

$album_id = isset($_GET['album_id'])?$_GET['album_id']:header("Location: test.php");
$album_name = isset($_GET['album_name'])?$_GET['album_name']:header("Location: test.php");

// Get access token from session
$access_token = $_SESSION['fb_access_token'];

// Get photos of Facebook page album using Facebook Graph API URL 
$graphPhoLink = "https://graph.facebook.com/v2.9/{$album_id}/photos?fields=source,images,name&access_token={$access_token}&limit=500";
//echo $graphPhoLink;

$jsonData = file_get_contents($graphPhoLink);
//echo $jsonData; 
$fbPhotoObj = json_decode($jsonData, true, 512, JSON_BIGINT_AS_STRING);

// Facebook photos content
$fbPhotoData = $fbPhotoObj['data'];

?>

<!DOCTYPE html>
<html>
<head>
    <title>Album Slideshow</title>
    <link rel="stylesheet" type="text/css" href="assets/materialize/css/materialize-0.100.2.css">
    <style type="text/css">
            
            .size
            {
                height: 450px !important;
                width: 450px !important;
            }

            .position
            {
                top: -100px !important;
                left: -100px !important;
            }

    </style>
        <link type="text/css" rel="stylesheet" href="lightGallery/dist/css/lightgallery.min.css" />

</head>
<body style="background-color: #f5f5f5;overflow: hidden;">
    <div class="row" style="padding: 1em;background-color: #3b5998;margin: 0;">
        <div class="col s2" >
            <a class="btn blue" href="userhome.php">Back</a>
        </div>
        <div class="col s8">
            <b><h4 align="center" style="margin: 0;color: white"><?php echo $album_name; ?></h4></b>    
        </div>    
    </div>
    <div style="padding: 10px auto;font-family: sans-serif;font-size: 1.5rem; margin:3px auto;text-align: center;">Click anyone of the images images!</div>
    <div id="lightgallery" style="margin: 0 auto;">

            <?php 
                foreach($fbPhotoData as $data)
                {
                    //echo "1";
                    $imageData = $data['images'][0];
                    //echo $imageData;
                    $imgSource = isset($imageData['source'])?$imageData['source']:'';
                    //echo $imgSource;
                    $name = isset($data['name'])?$data['name']:'';
                    
                    //echo "<div class='fb-album'>";
                    //echo "<a class='carousel-item position' href='#!' style='top: -100px;'>
                    //    <img src='{$imgSource}' class='size'  alt=''>
                    //</a>";
                    echo "<a href='".$imgSource."'><img src='".$imgSource."' style='width:300px;height:300px;float:left;margin:5px;border:solid 2px grey;'/></a>";
                    //echo "<h3>{$name}</h3>";
                    //echo "</div>";
                }
            ?>             
        </div>
        
    <script src="assets/js/jquery-2.1.3.min.js"></script>
    <script type="text/javascript" src="assets/materialize/js/materialize.js"></script>
    
        <script type="text/javascript">
            $(document).ready(function() {
                $("#lightgallery").lightGallery(); 
            });
        </script>
        <!-- jQuery version must be >= 1.8.0; -->
        <script src="jquery.min.js"></script>

        <!-- A jQuery plugin that adds cross-browser mouse wheel support. (Optional) -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js"></script>

        <script src="lightGallery/dist/js/lightgallery.min.js"></script>

        <!-- lightgallery plugins -->
        <script src="lightGallery/dist/js/lg-thumbnail.min.js"></script>
        <script src="lightGallery/dist/js/lg-fullscreen.min.js"></script>
        <script src="lightGallery/dist/js/lg-autoplay.min.js"></script>
        <script src="lightGallery/dist/js/lg-pager.min.js"></script>
        <script src="lightGallery/dist/js/lg-zoom.min.js"></script>

</body>
</html>