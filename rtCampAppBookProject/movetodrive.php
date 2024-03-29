<?php 
	if(!session_id()){
		session_start();
	}

	//echo $_SESSION['access_token'];

require_once 'google-api-php-client/src/Google/Client.php';
require_once 'google-api-php-client/src/Google/Service/Oauth2.php';
require_once 'google-api-php-client/src/Google/Service/Drive.php';

//initialise albumid and albumname array for storing details
$albumid = "";
$albumname = "";
$username = "";

//get album-id, album-name and username via ajax request
if(isset($_POST['albumid']) && isset($_POST['albumname']) && isset($_POST['username']))
{
    $albumid = $_REQUEST['albumid'];
    $albumname = $_REQUEST['albumname'];
    $username = $_REQUEST['username'];
}
$album_id = $albumid;
$album_name = $albumname;

// Get access token from session
$access_token = $_SESSION['fb_access_token'];

// Get photos of Facebook page album using Facebook Graph API URL 
//$graphPhoLink = "https://graph.facebook.com/v2.9/{$album_id}/photos?fields=source,images,name&access_token={$access_token}&limit=500";
//echo $graphPhoLink;
$graphPhoLink = "https://graph.facebook.com/v2.9/{$albumid}/photos?fields=source,images,name&access_token={$access_token}&limit=500&type=normal";

$jsonData = file_get_contents($graphPhoLink);

$fbPhotoObj = json_decode($jsonData, true, 512, JSON_BIGINT_AS_STRING);

// Facebook photos content
$fbPhotoData = $fbPhotoObj['data'];
//var_dump($fbPhotoData);

//array for storing urls of images in the album
$images = array();
$name = "";
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



// Init the variables
$driveInfo = "";
$folderName = "";
$folderDesc = "";
$mimeType = "";
$title= "";
// Get the file path from the variable

// Get the client Google credentials
$credentials = $_COOKIE["credentials"];

// Get your app info from JSON downloaded from google dev console
$json = json_decode(file_get_contents("google-api-php-client/client_secret.json"), true);
$CLIENT_ID = $json['web']['client_id'];
$CLIENT_SECRET = $json['web']['client_secret'];
$REDIRECT_URI = $json['web']['redirect_uris'][0];

// Create a new Client
$client = new Google_Client();
$client->setClientId($CLIENT_ID);
$client->setClientSecret($CLIENT_SECRET);
$client->setRedirectUri($REDIRECT_URI);
$client->addScope(
    "https://www.googleapis.com/auth/drive", 
    "https://www.googleapis.com/auth/drive.appfolder");

// Refresh the user token and grand the privileges
$client->setAccessToken($credentials);
$service = new Google_Service_Drive($client);

// Set the file metadata for drive
$mimeType = "application/vnd.google-apps.photo";
$description = "Uploaded from your very first google drive application!";

// Get the folder metadata
$folderName = "Facebook_".$username."_Albums";
$folderDesc = "Facebook_".$username."_Albums";
//$folderInfo = getFolderExistsCreate($service,$folderName,$folderDesc);

// Call the insert function with parameters listed below
insertFile($service, $description, $mimeType, $images, $folderName, $folderDesc);

/**
* Get the folder ID if it exists, if it doesnt exist, create it and return the ID
*
* @param Google_DriveService $service Drive API service instance.
* @param String $folderName Name of the folder you want to search or create
* @param String $folderDesc Description metadata for Drive about the folder (optional)
* @return Google_Drivefile that was created or got. Returns NULL if an API error occured
*/
function getFolderExistsCreate($service, $folderName, $folderDesc,$parentId="") {
    // List all user files (and folders) at Drive root
    global $albumname;
    $files = $service->files->listFiles();
    $found = false;

    // Go through each one to see if there is already a folder with the specified name
    foreach ($files['items'] as $item) {
        if ($item['title'] == $folderName) {
            $found = true;
            return $item['id'];
            break;
        }
    }

    // If not, create one
    if ($found == false) {
        $folder = new Google_Service_Drive_DriveFile();

        //Setup the folder to create
        $folder->setTitle($folderName);

        if($parentId != ""){
            $folder->setParents(array(array('id' => $parentId)));
        }

        if(!empty($folderDesc))
            $folder->setDescription($folderDesc);

        $folder->setMimeType('application/vnd.google-apps.folder');

        //Create the Folder
        try {
            
            $createdFile = $service->files->insert($folder, array(
                'mimeType' => 'application/vnd.google-apps.folder',
                ));

            // Return the created folder's id
            return $createdFile->id;
        } catch (Exception $e) {
            print "An error occurred: " . $e->getMessage();
        }
    }
}

/**
 * Insert new file in the Application Data folder.
 *
 * @param Google_DriveService $service Drive API service instance.
 * @param string $title Title of the file to insert, including the extension.
 * @param string $description Description of the file to insert.
 * @param string $mimeType MIME type of the file to insert.
 * @param string $filename Filename of the file to insert.
 * @return Google_DriveFile The file that was inserted. NULL is returned if an API error occurred.
 */
function insertFile($service, $description, $mimeType, $filename, $folderName, $folderDesc) {
    global $albumname;
    global $images;
    //$file = new Google_Service_Drive_DriveFile();

    // Set the metadata
    //$file->setTitle($title);
    //$file->setDescription($description);
    //$file->setMimeType($mimeType);

    // Setup the folder you want the file in, if it is wanted in a folder
    if(isset($folderName)) {
        if(!empty($folderName)) {
            
            $folderId = getFolderExistsCreate($service,$folderName,$folderDesc); 
            //echo $folderId;
            $folderId = getFolderExistsCreate($service,$albumname,$folderDesc,$folderId);
            
            try {
                // Get the contents of the file uploaded
                foreach ($images as $filename) {

                    //$file->setTitle(basename(substr($images, 0, strpos($images, "?"))));
                    // Try to upload the file, you can add the parameters e.g. if you want to convert a .doc to editable google format, add 'convert' = 'true'
                    
                    //$parentfolderId = $createdFolder->id;
                    $parentfolderId = $folderId;
                    $fileMetadata = new Google_Service_Drive_DriveFile(array(
                        'title' => basename(substr($filename, 0, strpos($filename, "?"))),
                        'parents' => array(array('id' => $parentfolderId))
                    ));
                    $content = file_get_contents($filename);
                    $file = $service->files->insert($fileMetadata, array(
                        'data' => $content,
                        'mimeType' => 'image/jpeg',
                        'fields' => 'id',
                        'uploadType'=> 'multipart'));
                    printf("File ID: %s\n", $file->id);

                }
                // Return a bunch of data including the link to the file we just uploaded
                //return $createdFile;
            } catch (Exception $e) {
                print "An error occurred: " . $e->getMessage();
                ?>
                <script type="text/javascript">
                    altert(<?php print($e->getMessage()); ?>)
                </script>
                <?php
            }
        }
    }

}

//echo "<br>Link to file: " . $driveInfo["alternateLink"];


?>