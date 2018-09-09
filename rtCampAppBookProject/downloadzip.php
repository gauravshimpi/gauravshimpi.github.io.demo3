<?php 
	if(isset($_GET['albumname']) && isset($_GET['username']))
	{
		$albumname = $_GET['albumname'];
		$username = $_GET['username'];
		$filename = $username."_".$albumname.".zip";
		$path = "Albums/".$filename;
		header('Content-disposition: attachment; filename='.$filename);
	    header('Content-type: application/zip');
	    //echo $path;
	    //die;
	    readfile($path);
	    unlink($path);
	}
	else if(isset($_GET['type']) && isset($_GET['username']))
	{
		$username = $_GET['username'];
		$filename = "Facebook_".$username."_Albums.zip";
		$path = "Albums/".$filename;
		header('Content-disposition: attachment; filename='.$filename);
	    header('Content-type: application/zip');
	    //echo $path;
	    //die;
	    readfile($path);
	    unlink($path);
    }  
?>