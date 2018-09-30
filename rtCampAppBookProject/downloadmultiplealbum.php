<?php


//setting max time limit of php script
if(!session_id()){
    session_start();
}

$albumid = array();
$albumname = array();
$username = "";

//get username
if(isset($_POST['username']))
{
    $username = $_POST['username'];
}

//get album-id and album-name via ajax request
if(isset($_POST['albumid']) && isset($_POST['albumname']))
{
  foreach ($_POST['albumid'] as $id) {
    $albumid[] = $id;
  }

  foreach ($_POST['albumname'] as $name) {
    $albumname[] = $name;
  }
}

$filename = "Facebook_".$username."_Albums.zip";

$cnt = 0;

    // create new zip opbject
    $zip = new ZipArchive();

    $zip->open("Albums/".$filename, ZipArchive::CREATE);


foreach ($albumid as $id) {
  
    //Get album id and user-id from url
    $album_id = $id;
    
    // Get access token from session
    $access_token = $_SESSION['fb_access_token'];

    // Get photos of Facebook page album using Facebook Graph API URL 
    $graphPhoLink = "https://graph.facebook.com/v2.9/{$id}/photos?fields=source,images,name&access_token={$access_token}&limit=500";
    //echo $graphPhoLink;

    $jsonData = file_get_contents($graphPhoLink);
    //echo $jsonData; 
    $fbPhotoObj = json_decode($jsonData, true, 512, JSON_BIGINT_AS_STRING);

    // Facebook photos content
    $fbPhotoData = $fbPhotoObj['data'];
    
    //array for storing the url of the images
    $images = array();

        while(true)
        {
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
            if($fbPhotoObj['paging']['next'])
            {

                    $jsonData = file_get_contents($fbPhotoObj['paging']['next']);
                    //echo $jsonData; 
                    $fbPhotoObj = json_decode($jsonData, true, 512, JSON_BIGINT_AS_STRING);

                    // Facebook photos content
                    $fbPhotoData = $fbPhotoObj['data'];
                    //echo $fbPhotoData;
                    //var_dump($fbPhotoData);
            }
            else
            {

                    //$jsonData = file_get_contents($fbPhotoObj['data']);
                    //echo $jsonData; 
                    //$fbPhotoObj = json_decode($jsonData, true, 512, JSON_BIGINT_AS_STRING);

                    // Facebook photos content
                    //$fbPhotoData = $fbPhotoObj['data'];

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
                break;
            }
        }

        

    $files = $images;

    // loop through each file
    foreach($files as $file){

        // download file
        $download_file = file_get_contents($file);

        //add it to the zip
        $zip->addFromString($albumname[$cnt].'/'.basename(substr($file, 0, strpos($file, "?"))),$download_file);

    }
   
    $cnt++;
}

    $zip->close();

?>