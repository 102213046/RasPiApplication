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
    <title>使用者資訊</title>
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
                        <li class="scroll"><a href="http://motorcycle.byethost10.com/gpsnow.php">最新位置</a></li>
                        <li class="scroll"><a href="http://motorcycle.byethost10.com/gps.php">歷史路徑</a></li> 
                        <li class="scroll"><a href="http://motorcycle.byethost10.com/user.php">使用者資訊</a></li>
                        <li class="scroll"><a href="http://motorcycle.byethost10.com">登出</a></li>
                    </ul>
                </div>
            </div><!--/.container-->
        </nav><!--/nav-->
    </header><!--/header-->

     <?php
            session_start();
            $newnfctxt = $_SESSION['nfc'];
        
            $nfcpath = "http://motorcycle.byethost10.com/raspberry/NFC/" . $newnfctxt;
        
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
            for($i = 0; $i < count($gpsfile); $i++){
                $gpspath = "http://motorcycle.byethost10.com/raspberry/GPS/" . $gpsfile[$i];
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
            
                //************抓經度************************//
                fseek($file1,25);           //設定讀取起始位置 
                $value3 = fread($file1,3);  //讀取指定長度的資料(經度r)
                $value3 = (double)$value3;  //轉成浮點數

                fseek($file1,28);           //設定讀取起始位置 
                $value4 = fread($file1,9);  //讀取指定長度的資料(經度mm到小數點後6位數)
                $value4 = (double)$value4;  //轉成浮點數
                $value4 = $value4/60;       //把分轉成經度的小數點
                $answer2 = $value3+$value4; //經度
            
                $gps = "(".$answer2." , ".$answer1.")";
            
                fseek($file1,40);
                $types = fread($file1,1);
                $types = (int)$types;
            
                $valuey = substr($gpsfile[$i],0,2);
                $valuem = substr($gpsfile[$i],2,2);
                $valued = substr($gpsfile[$i],4,2);
                $valueh = substr($gpsfile[$i],6,2);
                $valuemi = substr($gpsfile[$i],8,2);
                $values = substr($gpsfile[$i],10,2);
                $time = "20".$valuey."/".$valuem."/".$valued."/".$valueh.":".$valuemi.":".$values;
            
            
                if($types == 2){
                    $smst[] = $time;
                    $smsg[] = $gps;
                }
                else if($types == 1){
                    $bluet[] = $time;
                    $blueg[] = $gps;
                }
            }
    ?> 

    
    

    <section id="cta" class="wow fadeIn">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                 <!--   <div class="table-responsive"> -->
                        <h2>使用者資訊</h2>
                        <table class="table table-bordered " width="1100" height="200">
                            <thead>
                                <tr>
                                    <th class="text-center text-info">基本資料</th>
                                    <th class="text-center text-info">簡訊紀錄</th>
                                    <th class="text-center text-info">藍芽紀錄</th>
                                </tr>
                            </thead>

                            <tr>
                              <td valign="top">
                                  <?php
                                      // Use fopen function to open a file
                                      $file = fopen($nfcpath , "r");

                                      echo "<table width='270'>";
                                      // Read the file line by line until the end
                                      while (!feof($file)) {
                                          $value = fgets($file);
                                          $nfcvalue = explode(":", $value);
                                          if(count($nfcvalue) != 2)
                                              break;  
                                          echo "<tr><td>$nfcvalue[0]</td><td>$nfcvalue[1]</td></tr>";
                                      }
                                      echo "</table>";
                                      fclose($file);
                                  ?>
                              </td>
                              <td valign="top">
                                  <?php
                                      echo "<div style='height:600px; width:400px; overflow:auto;'>";
                                      echo "<table width='400'>";
                                      for($i = 0; $i < count($smst); $i++){
                                          echo "<tr><td>$smst[$i]</td><td>$smsg[$i]</td></tr>";
                                      }
                                      echo "</table>";
                                      echo "</div>";
                                  ?>
                              </td>
                              <td valign="top">
                                  <?php
                                      echo "<div style='height:500px; width:400px; overflow:auto;'>";
                                      echo "<table width='400'>";
                                      for($i = 0; $i < count($bluet); $i++){
                                          echo "<tr><td>$bluet[$i]</td><td>$blueg[$i]</td></tr>";
                                      }
                                      echo "</table>";
                                      echo "</div>";
                                  ?>
                              </td>
                            </tr>
                        </table>   
                    <!--</div>-->
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