<?php 

	require_once 'Facebook/autoload.php';
	session_start();
	$fb = new Facebook\Facebook([
  'app_id' => '2234494873436555', // Replace {app-id} with your app id
  'app_secret' => 'a17c0bc50acf260027b6736848177ca5',
  'default_graph_version' => 'v2.2',
  ]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email','user_photos']; // Optional permissions
$loginUrl = $helper->getLoginUrl('http://rtcampproject.herokuapp.com/rtCampAppBookProject/fbcallback.php', $permissions);

?>