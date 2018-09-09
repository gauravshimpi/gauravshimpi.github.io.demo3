<?php 

	require_once 'Facebook/autoload.php';
	session_start();
	$fb = new Facebook\Facebook([
  'app_id' => '2077887022527500', // Replace {app-id} with your app id
  'app_secret' => '9402dbf757496530274eed01218fb452',
  'default_graph_version' => 'v2.2',
  ]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email','user_photos']; // Optional permissions
$loginUrl = $helper->getLoginUrl('http://localhost/rtCampAppBookProject/fbcallback.php', $permissions);

?>