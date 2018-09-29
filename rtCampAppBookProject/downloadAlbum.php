<?php
//setting max time limit of php script
set_time_limit(3000000000);
if(!session_id()){
    session_start();
}

// initialize variables

$albumid = "";
$albumname = "";
$username = "";

//get album-id and album-name via ajax request
if(isset($_POST['albumid']) && isset($_POST['albumname']) && isset($_REQUEST['username']))
{
    $albumid = $_REQUEST['albumid'];
    $username = $_REQUEST['username'];
    $albumname = $_REQUEST['albumname'];
}

// Get access token from session
$access_token = $_SESSION['fb_access_token'];


//new Started
    

$album_photos = datafromfacebook ( '/' . $album_id . '/photos?fields=source&limit=100' );
    $album_directory = $album_download_directory . $album_name;
    if (! file_exists ( $album_directory )) {
        mkdir ( $album_directory, 0777 );
    }
    $i = 1;
    $offset=0;
    while(count($album_photos ['data']) > 0)
    {
        foreach ( $album_photos ['data'] as $album_photo ) {
            $album_photo = ( array ) $album_photo;
            file_put_contents ( $album_directory . '/' . $i . ".jpg", fopen ( $album_photo ['source'], 'r' ) );
            $i ++;
        }
        $offset+=100;
        $album_photos = datafromfacebook ( '/' . $album_id . '/photos?fields=source&limit=100&offset='.$offset);
    }
//new End




// Get photos of Facebook page album using Facebook Graph API URL 
$graphPhoLink = "https://graph.facebook.com/v2.9/{$albumid}/photos?fields=source,images,name&access_token={$access_token}&limit=100";
//echo $graphPhoLink;

$jsonData = file_get_contents($graphPhoLink);
//echo $jsonData; 

$fbPhotoObj = json_decode($jsonData, true, 512, JSON_BIGINT_AS_STRING);
print_r($fbPhotoObj);
die();
...
// Facebook photos content
$fbPhotoData = $fbPhotoObj['data'];
//echo $fbPhotoData;
//var_dump($fbPhotoData);

$i=1;
$offset=0;
while(count($fbPhotoData))
//array for storing urls of images in the album
$images = array();
$name = "";
foreach($fbPhotoData as $data){
    //echo "1";
    $imageData = end($data['images']);
    //echo $imageData;
    $imgSource = isset($imageData['source'])?$imageData['source']:'';
    //echo $imgSource ."<br>";
    
    //extract only the url
    //$imageUrl = substr($imgSource, 0, strpos($imgSource, "?"));
    
    //$images[] = $imageUrl;
    $images[] = $imgSource;
    $name = isset($data['name'])?$data['name']:'';
    echo $name;
    
}

//echo $name;

 	$files = $images;

    // create new zip opbject
    $zip = new ZipArchive();
    $zip->open("Albums/".$username."_".$albumname.".zip", ZipArchive::CREATE);

    // loop through each file
    foreach($files as $file){

        // download file
        $download_file = file_get_contents($file);

        //add it to the zip
        $zip->addFromString(basename(substr($file, 0, strpos($file, "?"))),$download_file);

    }

    // close zip
    $zip->close();



?>