<?php

if(!session_id()){
    session_start();
}

require_once "googleloginfunc.php";
require_once 'Facebook/autoload.php';
require_once 'Facebook/FacebookRequest.php';


  global $CLIENT_ID, $CLIENT_SECRET, $REDIRECT_URI;
  $client = new Google_Client();
  $client->setClientId($CLIENT_ID);
  $client->setClientSecret($CLIENT_SECRET);
  $client->setRedirectUri($REDIRECT_URI);
  $client->setScopes('email');

  //forcefully set cookie if not set by browser
  if(!isset($_COOKIE['credentials']) && isset($_GET['code'])){
    $authUrl = $client->createAuthUrl();  
    getCredentials($_GET['code'], $authUrl);
    $userName = $_SESSION["userInfo"]["name"];
    $userEmail = $_SESSION["userInfo"]["email"];
    header('Location: https://rtcampproject.herokuapp.com/rtCampAppBookProject/userhome.php?code='.$_GET['code']);
  }

    use Facebook\FacebookRequest;
    //session_start();
    $fb = new Facebook\Facebook([
  'app_id' => '2234494873436555',
  'app_secret' => 'a17c0bc50acf260027b6736848177ca5',
  'default_graph_version' => 'v2.2',
  ]);

try {
  // Returns a `Facebook\FacebookResponse` object
  $response = $fb->get('/me?fields=id,name,albums', $_SESSION['fb_access_token']);
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

$user = $response->getGraphUser();

if(isset($_SESSION['fb_access_token'])){
    // Get access token from session
    $access_token = $_SESSION['fb_access_token'];
}else{
    // Facebook app id & app secret 
    $appId = '2234494873436555'; 
    $appSecret = 'a17c0bc50acf260027b6736848177ca5';
    
    // Generate access token
    $graphActLink = "https://graph.facebook.com/oauth/access_token?client_id={$appId}&client_secret={$appSecret}&grant_type=client_credentials";
    
    // Retrieve access token
    $accessTokenJson = file_get_contents($graphActLink);
    $accessTokenObj = json_decode($accessTokenJson);
    $access_token = $accessTokenObj->access_token;
    
    // Store access token in session
    $_SESSION['fb_access_token'] = $access_token;
}

// Get photo albums of Facebook page using Facebook Graph API
$fields = "id,name,description,link,cover_photo,count";
$fb_page_id = $user['id'];
$graphAlbLink = "https://graph.facebook.com/v2.9/{$fb_page_id}/albums?fields={$fields}&access_token={$access_token}";

$jsonData = file_get_contents($graphAlbLink);
$fbAlbumObj = json_decode($jsonData, true, 512, JSON_BIGINT_AS_STRING);

// Facebook albums content
$fbAlbumData = $fbAlbumObj['data'];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="materialize is a material design based mutipurpose responsive template">
        <meta name="keywords" content="material design, card style, material template, portfolio, corporate, business, creative, agency">
        <meta name="author" content="trendytheme.net">

        <title><?php echo $user['name']; ?> | Facebook Albums</title>

        <!--  favicon -->
        <link rel="shortcut icon" href="assets/img/ico/favicon.png">
        <!--  apple-touch-icon -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/img/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/img/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/img/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="assets/img/ico/apple-touch-icon-57-precomposed.png">


        
        <link href='https://fonts.googleapis.com/css?family=Raleway:400,300,500,700,900' rel='stylesheet' type='text/css'>
        <!-- FontAwesome CSS -->
        <link href="assets/fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <!-- Material Icons CSS -->
        <link href="assets/fonts/iconfont/material-icons.css" rel="stylesheet">
        <!-- animate CSS -->
        <link href="assets/css/animate.min.css" rel="stylesheet">
        
        <!-- materialize -->
        <link href="assets/materialize/css/materialize-0.100.2.css" rel="stylesheet">
        <!-- Bootstrap -->
        <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <!-- shortcodes -->
        <link href="assets/css/shortcodes/shortcodes.css" rel="stylesheet">
        <!-- Style CSS -->
        <link href="assets/style.css" rel="stylesheet">


        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body id="top" data-spy="scroll" data-target="#materialize-menu" data-offset="148">
        
        <!-- Top bar -->
        <!-- /.top-bar -->

                
        <!--header start-->
        <header id="header" class="tt-nav nav-border-bottom">

            <div class="header-sticky brand-header">

                <div class="container">
                    <div id="materialize-menu" class="menuzord">

                        <!--logo start-->
                        <a href="#top" class="logo-brand page-scroll"  data-section="#top">
                            <!-- <img src="assets/img/logo-white.png" alt=""/> -->
                            <h2 style="color:white;" class="text-extrabold text-uppercase"><i class="fa fa-picture-o"></i>&nbsp;Facebook
                            Phactory</h2>
                        </a>
                        <!--logo end-->

                        <!-- onepage menu start-->
                        <ul class="nav menuzord-menu pull-right light op-nav" style="float: right;">
                            <li class="active">
                                <a href="#home" data-scroll>Home</a>
                            </li>
                            <li>
                                <a href="#about" data-scroll>About</a>
                            </li>
                            <li>
                                <a href="#albums" data-scroll>Download/View Albums</a>
                            </li>
                            <li>
                                
                                <a href="logout.php" id="btnlogout">
                                  Logout
                                </a>
                                <i class="fa fa-sign-out" style="color: white;"></i>
                            </li>
                        </ul>
                        <!-- onepage menu end-->

                    </div>
                </div>
            </div>

        </header>
        <!--header end-->


        <section id="home" class="banner-15 height-650 valign-wrapper" style="background-image: url('assets/img/banner/banner-15.jpg');">
            <div class="valign-cell">
              <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <h2 class="text-uppercase text-extrabold wow fadeInDown" style="font-size: 2em;color:#03a9f4;">Welcome
                        </h2>
                        <br>
                        <h3 id="username" class="intro-title text-uppercase mb-30 wow fadeInUp animated" style="font-size: 3em;">
                          <?php echo $user['name']; ?>                         
                        </h3>
                        <p style="color:#16c35d;font-size: 1.2em;">
                            <a>View, Download and Share</a> your Favourite moments  anytime, anywhere.
                        </p>
                        <br>  
                          <a id="btngooglelogin" href="<?php echo getAuthorizationUrl("","");?>" class="waves-effect waves-light btn cyan">Login with &nbsp;<img src="assets/images/googlelogin.png" style="height: 32px;width: 32px;">&nbsp;</a>
                    </div><!-- row -->
                </div><!-- row -->
              </div><!-- /.container -->
            </div><!-- /.valign-cell -->
        </section>


        <section id="about" class="section-padding">
          <div class="container">

                <div class="text-center mb-80 wow fadeInUp animated" style="visibility: visible; animation-name: fadeInUp;">
                    <center><h1 class="text-uppercase mb-30 wow fadeInLeft animated" style="visibility: visible; animation-name: fadeInLeft;">Facebook Phactory</h1></center>

                    <p class="card-title activator wow fadeInRight animated" style="font-size:1.5em;visibility: visible; animation-name: fadeInRight;">
                      Welcome to <a>Facebook Phactory</a>. Here you can View and Manage your Facebook Albums by <a>downloading <i class="fa fa-download"></i></a> them to your PC or by <a>uploading <i class="fa fa-cloud-upload"></i></a> them to your Google Drive(Login Required).
                    </p>
                </div>
            </div>
        </section>

        <section id="albums" class="section-padding gray-bg" style="padding-top: 1em;">
            
            <div class="container">

              <div class="text-center mb-50 wow fadeInUp">
                  <h2 class="section-title text-uppercase">Your Albums</h2>
              </div>

              <div class="row">
                <div class="text-center" style="margin: 1em;">
                  <a class="waves-effect waves-light btn green wow fadeInLeft" style="margin: 0.5em;" id="btndownlaadselected"><i class="fa fa-download" style="font-size: 1em;"></i>&nbsp;Download 
                  </a>
                  <a class="waves-effect waves-light btn info wow fadeInRight" style="margin: 0.5em;" id="btnmoveselected"><i class="fa fa-cloud-upload" style="font-size: 1em;"></i>&nbsp;Move to <img src="assets/images/Google-Drive-icon.png"> 
                  </a>

                </div>
              </div>

              <div class="row">
                
                <!-- Facebook Album Section -->
                <?php 
                  foreach($fbAlbumData as $data){
                    $id = isset($data['id'])?$data['id']:'';
                    $name = isset($data['name'])?$data['name']:'';
                    $description = isset($data['description'])?$data['description']:'';
                    $link = isset($data['link'])?$data['link']:'';
                    $cover_photo_id = isset($data['cover_photo']['id'])?$data['cover_photo']['id']:'';
                    $count = isset($data['count'])?$data['count']:'';
                    $albumdetails = $id.":".$name;
                    $pictureLink = "carousel.php?album_id={$id}&album_name={$name}&user_id={$user['id']}";
                    $downloadLink = "downloadAlbum.php?album_id={$id}&album_name={$name}&user_id={$user['id']}";
                    $driveLink= "";
                    
                     $photoCount = $count;;
                    
                
              ?> 
                  
                    <div class="col-md-4 col-sm-6 wow fadeInUp">
                      <div class="team-wrapper">
                          <div class="team-img">
                              <a href="<?php echo $pictureLink; ?>">
                                <img src="https://graph.facebook.com/v2.9/<?php echo $cover_photo_id; ?>/picture?access_token=<?php echo $access_token; ?>" class="img-responsive" alt="Image" style="height:300px;width: 357px;" onerror="this.onerror=null;this.src='assets/images/noimage.jpg';">
                              </a>
                          </div><!-- /.team-img -->

                          <div class="text-center" style="margin-top: 0.5em;">
                              <input type="checkbox" class="chkdownloadalbum pulse" id="<?php echo $albumdetails; ?>"><label for="<?php echo $albumdetails; ?>"></label>
                          </div>

                          <div class="team-title text-center" style="padding: 0">
                              <h4><caption><a href="<?php echo $link; ?>"><?php echo $name; ?>&nbsp;(<?php echo $photoCount; ?>)</a></caption></h4>
                              
                          </div><!-- /.team-title -->

                          <ul class="team-social-links list-inline text-center">
                              <li><a class="downloadalbum" id="<?php echo $albumdetails; ?>"><i class="fa fa-download" style="font-size: 1.5em;"></i></a></li>
                              <li><a class="movesingle" id="<?php echo $albumdetails; ?>"><i class="fa fa-cloud-upload" style="font-size: 1.5em;"></i></a></li>
                          </ul>

                      </div><!-- /.team-wrapper -->
                    </div><!-- /.col-md-4 -->
                  
                  <?php 
                     } 
                  ?>  
                <!-- Facebook Album Section -->

              </div><!-- /.row -->

              <div class="row" style="margin: 1em;">
                <div class="text-center" style="margin: 1em;">
                  <a class="waves-effect waves-light btn green wow fadeInLeft" style="margin: 0.5em;" id="btndownloadall">
                    <i class="fa fa-download" style="font-size: 1em;"></i>&nbsp;Download All 
                  </a>
                  
                  <a class="waves-effect waves-light btn info wow fadeInRight" style="margin: 0.5em;" id="btnmoveall">
                    <i class="fa fa-cloud-upload" style="font-size: 1em;"></i>&nbsp;Move all to 
                    <img src="assets/images/Google-Drive-icon.png">
                  </a>

                </div>

            </div><!-- /.container -->
        </section>

        <footer class="footer footer-two">
            <div class="primary-footer brand-bg" style="padding: 0;">
                <div class="container">

                    <a href="#top" class="page-scroll btn-floating btn-large pink back-top waves-effect waves-light tt-animate btt" data-section="#top">
                      <i class="material-icons">&#xE316;</i>
                    </a>

                    
                </div><!-- /.container -->
            </div><!-- /.primary-footer -->

            <div class="secondary-footer brand-bg darken-2">
                <div class="container text-center">
                    <span class="copy-text">Developed By <a>Gaurav Shimpi</a></span>
                </div><!-- /.container -->
            </div><!-- /.secondary-footer -->
        </footer>

        
        <!-- Preloader -->
        
        <div id="preloader">
          <div class="preloader-position"> 
            <h2 class="text-extrabold text-uppercase" style="font-size: 3em;color: #03a9f4;"><i class="fa fa-picture-o"></i>&nbsp;Facebook Phactory</h2>
            <!-- <img src="assets/img/logo-colored.png" alt="logo" > -->
            <div class="progress">
              <div class="indeterminate"></div>
            </div>
            <h2>Please Wait...</h2>
          </div>
        </div>
        <!-- End Preloader -->

        <script src="assets/js/jquery-2.1.3.min.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/materialize/js/materialize.js"></script>
        <script src="assets/js/menuzord.js"></script>
        <script src="assets/js/bootstrap-tabcollapse.min.js"></script>
        <script src="assets/js/jquery.easing.min.js"></script>
        <script src="assets/js/jquery.sticky.min.js"></script>
        <script src="assets/js/smooth-menu.js"></script>
        <script src="assets/js/jquery.stellar.min.js"></script>
        <script src="assets/js/imagesloaded.js"></script>
        <script src="assets/js/jquery.inview.min.js"></script>
        <script src="assets/js/jquery.shuffle.min.js"></script>
        <script src="assets/js/wow.min.js"></script>
        <script src="assets/owl.carousel/owl.carousel.min.js"></script>
        <script src="assets/magnific-popup/jquery.magnific-popup.min.js"></script>
        <script src="assets/js/scripts.js"></script>

        <script>
            
            function getCookie(name) {
              var dc = document.cookie;
              var prefix = name + "=";
              var begin = dc.indexOf("; " + prefix);
              if (begin == -1) {
                  begin = dc.indexOf(prefix);
                  if (begin != 0) return null;
              }
              else
              {
                  begin += 2;
                  var end = document.cookie.indexOf(";", begin);
                  if (end == -1) {
                  end = dc.length;
                  }
              }
              // because unescape has been deprecated, replaced with decodeURI
              //return unescape(dc.substring(begin + prefix.length, end));
              return decodeURI(dc.substring(begin + prefix.length, end));
            } 

            new WOW({
                mobile:  false
            }).init();

            $(document).ready(function(){
               
                
               // setTimeout(function(){ $("#preloader").show(); },1000);
                if(getCookie("credentials") != null)
                {
                    $("#btngooglelogin").hide();
                }

                //display tooltip
                $('.tooltipped').tooltip({delay: 50});

                //Single Album Download
                $(".downloadalbum").click(function(){

                    $("#preloader").show();
                    var albumdetails =  $(this).attr("id").split(":");
                    var username = $("#username").text().trim();

                    $.ajax({
                          type: "POST",
                          url: "downloadAlbum.php",
                          data: {albumid : albumdetails[0],albumname : albumdetails[1],username : username},
                          success: function (response) 
                          {
                            $("#preloader").hide();
                            location.href = "downloadzip.php?albumname="+albumdetails[1]+"&username="+username;
                          }
                      });
                });

                //Download All Albums 
                $("#btndownloadall").click(function(){
                  //$("#preloader").show();
                  var username = $("#username").text().trim();
                  var albumarray = [];
                    
                    $(".chkdownloadalbum").each(function(){
                      albumarray.push($(this).attr("id"));
                    });

                    var albumdata = albumarray;
                    var albumid = [];
                    var albumname = [];
                    var albumdetails;
                    for(var i=0; i < albumdata.length; i++)
                    {
                      albumdetails = albumdata[i].split(":");
                      //alert(albumdetails);
                      albumid.push(albumdetails[0]);
                      albumname.push(albumdetails[1]);
                    }
                    $name = "https://graph.facebook.com/v2.9/"+$albumid+"/photos?fields=source,images,name&access_token="+$access_token+"&limit=500";
                    alert($name);
                    $.ajax({
                        type: "POST",
                        url: "downloadmultiplealbum.php",
                        data: {albumid : albumid,albumname : albumname,username : username},
                        success: function (response) 
                        {
                          $("#preloader").hide();
                          location.href="downloadzip.php?type=all&username="+username;              
                        }
                    });
                });

                //Download Selected Albums 
                $("#btndownlaadselected").click(function(){
                  
                  if($(".chkdownloadalbum:checked").length > 0) {
                    $("#preloader").show();
                    var username = $("#username").text().trim();
                    var albumarray = [];
                      
                      $(".chkdownloadalbum:checkbox:checked").each(function(){
                        albumarray.push($(this).attr("id"));
                      });

                      var albumdata = albumarray;
                      var albumid = [];
                      var albumname = [];
                      var albumdetails;
                      for(var i=0; i < albumdata.length; i++)
                      {
                        albumdetails = albumdata[i].split(":");
                        albumid.push(albumdetails[0]);
                        albumname.push(albumdetails[1]);
                      }

                      $.ajax({
                          type: "POST",
                          url: "downloadmultiplealbum.php",
                          data: {albumid : albumid,albumname : albumname,username : username},
                          success: function (response) 
                          {
                            $("#preloader").hide();
                            location.href="downloadzip.php?type=all&username="+username;              
                          }
                      });
                  }
                  else
                  {
                      Materialize.toast("Please select at least any one album !",3000);
                  }
                  
                });


                //Move Single Album to Drive
                $(".movesingle").click(function(){
                    if(getCookie("credentials") != null)
                    {
                      $("#preloader").show();
                      var albumdetails =  $(this).attr("id").split(":");
                      var username = $("#username").text().trim();

                      $.ajax({
                          type: "POST",
                          url: "movetodrive.php",
                          data: {albumid : albumdetails[0],albumname : albumdetails[1], username : username},
                          success: function (response) 
                          {
                            $("#preloader").hide();
                            Materialize.toast("Album Moved Successfully !",2000);
                          }
                      });  
                    }
                    else
                    {
                      $("html, body").animate({ scrollTop: 150 }, 1500);
                      Materialize.toast("PLease Login to Continue",3000);
                      $("#btngooglelogin").addClass("pulse");  
                      setTimeout(function(){ $("#btngooglelogin").removeClass("pulse"); }, 7000);
                    }
                });      

                //Move Selected Albums
                $("#btnmoveselected").click(function(){
                    if(getCookie("credentials") != null)
                    {
                      $("#preloader").show();
                      var albumarray = [];

                      var username = $("#username").text().trim();

                      $(".chkdownloadalbum:checkbox:checked").each(function(){
                        albumarray.push($(this).attr("id"));
                      });

                      var albumdata = albumarray;
                      var albumid = [];
                      var albumname = [];
                      var albumdetails;

                      for(var i=0; i < albumdata.length; i++)
                      {
                        albumdetails = albumdata[i].split(":");
                        //alert(albumdetails);
                        albumid.push(albumdetails[0]);
                        albumname.push(albumdetails[1]);
                      }
                     
                      $.ajax({
                          type: "POST",
                          url: "movemultiple.php",
                          data: {albumid : albumid,albumname : albumname,username : username},
                          success: function (response) 
                          {
                            $("#preloader").hide();
                            Materialize.toast("Albums Moved Successfully !",2000);
                          }
                      });
                    }
                    else
                    {
                      $("html, body").animate({ scrollTop: 150 }, 1500);
                      Materialize.toast("PLease Login to Continue",3000);
                      $("#btngooglelogin").addClass("pulse");  
                      setTimeout(function(){ $("#btngooglelogin").removeClass("pulse"); }, 7000);
                    }  
                });

                $("#btnmoveall").click(function(){
                    if(getCookie("credentials") != null)
                    {
                        $("#preloader").show();
                        var albumarray = [];

                        var username = $("#username").text().trim();

                        $(".chkdownloadalbum").each(function(){
                          albumarray.push($(this).attr("id"));
                        });

                        var albumdata = albumarray;
                        var albumid = [];
                        var albumname = [];
                        var albumdetails;

                        for(var i=0; i < albumdata.length; i++)
                        {
                          albumdetails = albumdata[i].split(":");
                          //alert(albumdetails);
                          albumid.push(albumdetails[0]);
                          albumname.push(albumdetails[1]);
                        }

                        $.ajax({
                            type: "POST",
                            url: "movemultiple.php",
                            data: {albumid : albumid,albumname : albumname,username : username},
                            success: function (response) 
                            {
                              $("#preloader").hide();
                              Materialize.toast("<p style='margin: 1em;'>Albums Moved Successfully !</p>",2000);
                            }
                        });
                    }
                    else
                    {
                      $("html, body").animate({ scrollTop: 150 }, 1500);
                      Materialize.toast("PLease Login to Continue",3000);
                      $("#btngooglelogin").addClass("pulse");  
                      setTimeout(function(){ $("#btngooglelogin").removeClass("pulse"); }, 7000);
                    }
                });   

            });
        </script>

    </body>
  
</html>