<?php

    error_reporting(E_ALL);
    ini_set('display_errors', 'on');
    require_once "login.php";
    //login check
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

        <title>Login - FacebookPhactory</title>

        <!--  favicon -->
        <link rel="shortcut icon" href="assets/img/ico/favicon.png">
        <!--  apple-touch-icon -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/img/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/img/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/img/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="assets/img/ico/apple-touch-icon-57-precomposed.png">


        
        <link href='https://fonts.googleapis.com/css?family=Raleway:400,300,500,700,900' rel='stylesheet' type='text/css'>
        <!-- Material Icons CSS -->
        <link href="assets/fonts/iconfont/material-icons.css" rel="stylesheet">
        <!-- FontAwesome CSS -->
        <link href="assets/fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <!-- materialize -->
        <!-- <link href="assets/materialize/css/materialize.min.css" rel="stylesheet"> -->
        <link href="assets/materialize/css/materialize.css" rel="stylesheet">
        <!-- Bootstrap -->
        <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <!-- shortcodes -->
        <link href="assets/css/shortcodes/shortcodes.css" rel="stylesheet">
        <link href="assets/css/shortcodes/login.css" rel="stylesheet">
        <!-- Style CSS -->
        <link href="assets/style.css" rel="stylesheet">


        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <style type="text/css">
            
            /*login button*/
            .btnlogin
            {
                position: relative;
                background: #ed2553;
                width: 120px;
                height: 120px;
                border-radius: 100%;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
                color: #ffffff;
                font-size: 50px;
                line-height: 120px;
                text-align: center;
                cursor: pointer;
                background-image: url('assets/images/fblogo.png');
                background-size: contain;    
            }


            

            /*pulse animation*/
            .pulse {
                overflow: initial;
                position: relative;
            }

            .pulse::before {
                content: '';
                display: block;
                position: absolute;
                width: 100%;
                height: 100%;
                top: 0;
                left: 0;
                background-color: blue;
                border-radius: inherit;
                -webkit-transition: opacity .3s, -webkit-transform .3s;
                transition: opacity .3s, -webkit-transform .3s;
                transition: opacity .3s, transform .3s;
                transition: opacity .3s, transform .3s, -webkit-transform .3s;
                -webkit-animation: pulse-animation 1s cubic-bezier(0.24, 0, 0.38, 1) infinite;
                animation: pulse-animation 1s cubic-bezier(0.24, 0, 0.38, 1) infinite;
                z-index: -1
            }

            @-webkit-keyframes pulse-animation {
                0% {
                    opacity: 1;
                    -webkit-transform: scale(1);
                    transform: scale(1)
                }

                50% {
                    opacity: 0;
                    -webkit-transform: scale(1.5);
                    transform: scale(1.5)
                }

                100% {
                    opacity: 0;
                    -webkit-transform: scale(1.5);
                    transform: scale(1.5)
                }
            }

            @keyframes pulse-animation {
                0% {
                    opacity: 1;
                    -webkit-transform: scale(1);
                    transform: scale(1)
                }

                50% {
                    opacity: 0;
                    -webkit-transform: scale(1.5);
                    transform: scale(1.5)
                }

                100% {
                    opacity: 0;
                    -webkit-transform: scale(1.5);
                    transform: scale(1.5)
                }
            }

            @media screen and (max-width: 550px){
                .card-wrapper.alt .btnlogin {
                    width: 80px;
                    height: 80px;
                    font-size: 34px;
                    line-height: 80px;
                }
            }
        </style>
    </head>

    <body id="top" class="has-header-search banner-6" style="background-image: url('assets/img/banner/banner-6.jpg');">

        <section class="section-padding ">
            <div class="container">

                <div class="login-wrapper">
                  <div class="card-wrapper" style="height: 40px;background: #03a9f4; padding-top: 0.6em;">
                    <h2 style="color:white;" class="text-center text-extrabold text-uppercase"><i class="fa fa-picture-o"></i>&nbsp;Facebook Phactory</h2>
                      
                  </div>
                  <div class="card-wrapper">
                    <h1 class="title text-center" style="padding-left: 0em;">
                        <a href="<?php echo htmlspecialchars($loginUrl); ?>">Login</a>
                    </h1>
                    
                  </div>
                  <div class="card-wrapper alt" style="top: 60px;">
                    <a href="<?php echo htmlspecialchars($loginUrl); ?>">
                        <div id="btnlogin" class="btnlogin pulse">
                        </div>
                    </a>    
                  </div>
                </div>

            </div>
        </section>

        <!-- Preloader -->
        <div id="preloader">
          <div class="preloader-position"> 
            <h2 class="text-extrabold text-uppercase" style="font-size: 3em;color: #03a9f4;"><i class="fa fa-picture-o"></i>&nbsp;Facebook Phactory</h2>
            <div class="progress">
              <div class="indeterminate"></div>
            </div>
          </div>
        </div>
        <!-- End Preloader -->

        <!-- jQuery -->
        <script src="assets/js/jquery-2.1.3.min.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/materialize/js/materialize.min.js"></script>
        <script src="assets/materialize/js/materialize.js"></script>
        <script src="assets/js/menuzord.js"></script>
        <script src="assets/js/jquery.easing.min.js"></script>
        <script src="assets/js/jquery.sticky.min.js"></script>
        <script src="assets/js/smoothscroll.min.js"></script>
        <script src="assets/js/jquery.stellar.min.js"></script>
        <script src="assets/js/imagesloaded.js"></script>
        <script src="assets/js/animated-headline.js"></script>
        <script src="assets/js/scripts.js"></script>

        
    
    </body>
  
</html>