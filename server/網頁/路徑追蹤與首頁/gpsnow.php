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
    <title>最新位置</title>
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
    <!-- Load Jquery -->
    <script language="JavaScript" type="text/javascript" src="jquery-1.10.1.min.js"></script>
    
    <!-- Load Google Maps Api -->
    <!-- IMPORTANT: change the API v3 key -->
    <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAdUhN2pkrdJiX2NXk0yFRX8QPmobXP9LQ&sensor=false"></script><!--這裡 sensor=false 表示此為一般電腦, 不是行動裝置,  不使用感應器. -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
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
                    <a class="navbar-brand" href="http://motorcycle.byethost10.com/"><img src="images/logo2.png" alt="logo"></a>
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
                        <li class="scroll"><a href="http://motorcycle.byethost10.com/gpsnow.php">最新位置</a></li>
                        <li class="scroll"><a href="http://motorcycle.byethost10.com/gps.php">歷史路徑</a></li>   
                        <li class="scroll"><a href="http://motorcycle.byethost10.com/user.php">使用者資訊</a></li>
                        <li class="scroll"><a href="http://motorcycle.byethost10.com">登出</a></li>
                    </ul>
                </div>
            </div><!--/.container-->
        </nav><!--/nav-->
    </header><!--/header-->



    <section id="cta" class="wow fadeIn">
        <div class="container">
            <div class="row">
                <div class="col-sm-9">
                    <h2>&nbsp;&nbsp;&nbsp;&nbsp;最新位置</h2>
                       <div class="container">
                            <div id="info">
                                <div class="col-md-3">
                                    <table class="table table-bordered">
                                        <?php
                                            echo "<tr><th class='text-info'>使用者</th></tr>";
                                            session_start();
                                            $newnfctxt = $_SESSION['nfc'];  
                                            $nfcpath = "http://motorcycle.byethost10.com/raspberry/NFC/" . $newnfctxt;
            
                                            // Use fopen function to open a file
                                            $file = fopen($nfcpath , "r");

                                            // Read the file line by line until the end
                                            $value = fgets($file);
                                            $nfcvalue = explode(":", $value);
                                            echo "<tr><td>$nfcvalue[1]</td></tr>";
                                            fclose($file);
                                        ?>
                                    </table>
                                </div>
                                
                                
                                <div class="col-md-3">
                                    <table class="table table-bordered">
                                        <?php
                                            $gpsdata = opendir('/home/vol2_5/byethost10.com/b10_19011039/htdocs/raspberry/GPS');
                                            while(($gfile = readdir($gpsdata)) !== false){
                                                if($gfile != "." && $gfile != ".."){
                                                    if(strcmp(substr($gfile,14),$newnfctxt) == 0){
                                                        $gpsfile[] = $gfile;
                                                    }   
                                                }
                                            }
                                            closedir($gpsdata);
                                            rsort($gpsfile);
                                        ?>                                    
                                        <?php
                                            echo "<tr><th class='text-info'>最新日期時間</th></tr>";
                                            $datedata = $gpsfile[0];                                    
                                            $valuey = substr($datedata,0,2);
                                            $valuem = substr($datedata,2,2);
                                            $valued = substr($datedata,4,2);
                                            $valueh = substr($datedata,6,2);
                                            $valuemi = substr($datedata,8,2);
                                            $values = substr($datedata,10,2);
                                        
                                            $time = "20%d 年 %d 月 %d 日 %d 時 %d 分 %d 秒";
                                            echo "<tr><td>";
                                            echo sprintf($time,$valuey,$valuem,$valued,$valueh,$valuemi,$values);    
                                            echo "</td></tr>";
                                        ?>
                                    </table>
                                </div>
                                
                                <div class="col-md-3">
                                    <table class="table table-bordered">                                       
                                        <?php
                                            echo "<tr><th class='text-info'>最新GPS位址(經度 , 緯度)</th></tr>";
                                            $gpstxt = $gpsfile[0];
                                            $gpspath = "http://motorcycle.byethost10.com/raspberry/GPS/" . $gpstxt;
                                            
                                            $file1 = fopen($gpspath, "r");
                                                //************抓緯度************************//
                                            fseek($file1,11);           //設定讀取起始位置 
                                            $value1 = fread($file1,2);  //讀取指定長度的資料(緯度r)
                                            $value1 = (double)$value1;  //轉成浮點數
                                      
                                            fseek($file1,13);           //設定讀取起始位置 
                                            $value2 = fread($file1,9);  //讀取指定長度的資料(緯度mm到小數點後6位數)
                                            $value2 = (double)$value2;  //轉成浮點數
                                            $value2 = $value2/60;       //把分轉成緯度的小數點
                                            $answer1 = $value2+$value1; //緯度
                                            //print "" . $answer1 . "<br>";
                                     
                                            //****************************************//
                                            
                                            
                                            //************抓經度************************//
                                            fseek($file1,25);           //設定讀取起始位置 
                                            $value3 = fread($file1,3);  //讀取指定長度的資料(經度r)
                                            $value3 = (double)$value3;  //轉成浮點數

                                            fseek($file1,28);           //設定讀取起始位置 
                                            $value4 = fread($file1,9);  //讀取指定長度的資料(經度mm到小數點後6位數)
                                            $value4 = (double)$value4;  //轉成浮點數
                                            $value4 = $value4/60;       //把分轉成經度的小數點
                                            $answer2 = $value3+$value4; //經度
                                            echo "<tr><td>";
                                            print " (".$answer2." , ".$answer1.")";  //注意格式為double
                                            echo "</td></tr>";
                                          
                                            //****************************************//
                                            $longtude = $answer2;
                                            $latitude = $answer1;
                                            fclose($file1);                                   
                                        ?>
                                    </table>
                                </div>                             
                            </div>


                            <div class="googlemap" id="googlemap" style="border-width:1px; border-style:dashed; width:100%; height:560px"> <!--地圖的大小-->
                                <script>
                                    var longtude = ["<?php echo $longtude ?>"];  //讀PHP的經度
                                    var latitude = ["<?php echo $latitude ?>"]; //讀PHP的緯度
                                    var now = "http://maps.google.com/mapfiles/ms/micons/pink-pushpin.png"
                                
                                    $(document).ready(function(){
                                        var googlemap=$("#googlemap")[0];  //$("#googlemap")所取得的是畫布的 jQuery 包裹物件, 必須呼叫 get() 或用陣列元素 0 取得其 DOM 物件才能為 Google Maps API 所用. 
                                        <!--這裡要改成可以讀GPS.txt的值-->
                                        var latlng=new google.maps.LatLng(latitude,longtude);
                                        var opt={zoom:15, center:latlng, mapTypeId:"roadmap"};              <!--地圖的放大倍數 zoom 為 15 倍, 中心點座標 center 為所建立之座標物件, 以及地圖類型為道路地圖 "roadmap"-->
                                        var map=new google.maps.Map(googlemap, opt);        
                                        <!--呼叫 Map() 方法建立地圖物件-->
                                        var marker=new google.maps.Marker({position:latlng, map:map ,icon: now});
                                    });
                                </script>
                            </div>
                            
                        </div>                     
                </div>                
            </div>
        </div>
    </section><!--/#cta-->

    

    
    
    

    

    

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