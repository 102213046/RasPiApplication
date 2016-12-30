<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="我們的裝置產品有三大功能：行車紀錄、緊急連絡報警、線上路線追蹤。
        行車紀錄：不論白天或夜晚，都可以紀錄行車狀況，避免事故糾紛。
        緊急連絡報警：若騎士不幸發生事故，此功能可以透過簡訊即時報案，不用擔心忘記帶手機或是周遭沒有人幫忙。
        線上路線追蹤：此功能在許多情況下皆可以發揮有效的輔佐作用。例如當機車遭竊盜時，可提供警方即時行車路線；或者家裡老年人騎車時，家人可以追蹤他們的行蹤，確保行車安全等。
    ">
    <meta name="author" content="暨大資管專題第11組">
    <meta name="keywords" content="樹莓派,raspberry,pi,緊急連絡,緊急訊息,緊急求救,三寶機,機車安全,三寶,三寶雞,路徑追蹤,路線追蹤,行車紀錄,騎車,機車,暨大資管專題,暨大專題,資管專題">
    <title>選擇使用者</title>
	<!-- core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animate.min.css" rel="stylesheet">
    <link href="css/owl.carousel.css" rel="stylesheet">
    <link href="css/owl.transitions.css" rel="stylesheet">
    <link href="css/prettyPhoto.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->       
    <link rel="shortcut icon" href="images/ico/icon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png">
</head><!--/head-->

<body id="home" class="homepage">

    <header id="header">
        <nav id="main-menu" class="navbar navbar-default navbar-fixed-top" role="banner">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="http://motorcycle.byethost10.com"><img src="images/logo2.png" alt="logo"></a>
                </div>
				
                <div class="collapse navbar-collapse navbar-right">
                    <ul class="nav navbar-nav">
                        <?php
                            session_start();
                            $newnfctxt = $_SESSION['nfc'];
                            $name = $_SESSION['ad'];       
                            if(strcmp($name,'CY') == 0){
                                echo "<li><a href='http://motorcycle.byethost10.com/admit.php'>選擇使用者</a></li>";
                            }
                        ?>
                        <!--管理者在選擇使用者前不能進到其他頁面-->
                        <li class="scroll"><a href="">最新位置</a></li>
                        <li class="scroll"><a href="">歷史路徑</a></li>  
                        <li class="scroll"><a href="">使用者資訊</a></li>
                        <li class="scroll"><a href="http://motorcycle.byethost10.com">登出</a></li>
                    </ul>
                </div>
            </div><!--/.container-->
        </nav><!--/nav-->
    </header><!--/header-->

    

    <div id="cta" >
        <div class="container">
            <div class="row">
                <div class="col-sm-12">              
                    <div class="col-sm-4 alert alert-warning alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <strong>恭喜您完成登入！</strong> 
                    </div>
                </div>
                
                <div class="col-sm-12">
                    <p class="text-info col-sm-4">您現在是以管理者的身分進入系統。在選擇使用者之前，點選其他頁面都不會出現任何資訊。</p>
                </div>
                <div class="col-sm-12">
                    <div class="col-sm-4">
                    <p><h2>首先請選擇使用者</h2></p>
                    <?php
                        session_start();
                        unset($_SESSION['nfc']);
                        $_SESSION['ad'];
                        $nfcdata = opendir('/home/vol2_5/byethost10.com/b10_19011039/htdocs/raspberry/NFC');
                        while(($nfile = readdir($nfcdata)) !== false){
                            if($nfile != "." && $nfile != ".."){
                                $nfcfile[] = $nfile;
                            }
                        }
                        closedir($nfcdata);
                        sort($nfcfile);
                        echo "<form action='select.php' name='sortn1' method='get'>";
                        echo "<select name='sortn' class='form-control'>"; 
                        echo "<option> 請選擇使用者 </option>";
                        for($i = 0; $i < count($nfcfile); $i++){
                            $nffile = substr($nfcfile[$i],0,-4);
                            echo "<option value='$nfcfile[$i]'>"; 
                            echo $nffile; 
                            echo "</option>";
                        }
                        echo "</select>";
                        echo "<input type='submit' value='查詢' class='btn btn-defult'>";
                        echo "</form>";
                    ?>
                    </div>
                </div>               
            </div>
        </div>
    </div><!--/#cta-->

    <footer id="footer">
        <div class="container">
            <div class="row">
                <div class="col-sm-9">
                    <div class="col-sm-6">
                        &copy; 2016 暨大資管專題第11組.
                    </div>    
                    <div class="col-sm-3">    
                        網頁維護：張怡臻
                    </div>
                    <!--Designed by <a target="_blank" href="http://shapebootstrap.net/" title="Free Twitter Bootstrap WordPress Themes and HTML templates">ShapeBootstrap</a>-->
                
                    <div class="col-sm-6">
                        <ul class="social-icons">
                            <!--
                            <li><a href="https://www.facebook.com/%E4%B8%89%E5%AF%B6%E6%A9%9F-205477516547981/?ref=aymt_homepage_panel"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                            <li><a href="#"><i class="fa fa-pinterest"></i></a></li>
                            <li><a href="#"><i class="fa fa-dribbble"></i></a></li>
                            <li><a href="#"><i class="fa fa-behance"></i></a></li>
                            <li><a href="#"><i class="fa fa-flickr"></i></a></li>
                            <li><a href="#"><i class="fa fa-youtube"></i></a></li>
                            <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                            <li><a href="#"><i class="fa fa-github"></i></a></li>
                            -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer><!--/#footer-->

    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="http://maps.google.com/maps/api/js?sensor=true"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/mousescroll.js"></script>
    <script src="js/smoothscroll.js"></script>
    <script src="js/jquery.prettyPhoto.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/jquery.inview.min.js"></script>
    <script src="js/wow.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>