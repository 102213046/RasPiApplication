<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>快樂三寶機</title>
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
                    <a class="navbar-brand" href="index.html"><img src="images/logo2.png" alt="logo"></a>
                </div>
				
                <div class="collapse navbar-collapse navbar-right">
                    <ul class="nav navbar-nav">
                        <li class="scroll active"><a href="http://pm25-test.byethost3.com/raspberry/GPSPHP2/admit.php">選擇使用者</a></li>
                        <li class="scroll"><a href="http://pm25-test.byethost3.com/raspberry/GPSPHP2/gpsnow.php">現在位置</a></li>
                        <li class="scroll"><a href="http://pm25-test.byethost3.com/raspberry/GPSPHP2/gps0.php">使用者資訊</a></li>
                        <li class="scroll"><a href="http://pm25-test.byethost3.com/raspberry/GPSPHP2/user.php">歷史路徑</a></li>   
                        <li class="scroll"><a href="http://pm25-test.byethost3.com/raspberry/GPSPHP2/gps0.php">登出</a></li>
                    </ul>
                </div>
            </div><!--/.container-->
        </nav><!--/nav-->
    </header><!--/header-->

    <section id="main-slider">
        <div class="owl-carousel">
            <div class="item" style="background-image: url(images/slider/bg1.jpg);">
                <div class="slider-inner">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="carousel-content">
                                    <h2><span>三寶機</span>結合GPS、行車紀錄器和緊急發送簡訊三種功能</h2>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et  dolore magna incididunt ut labore aliqua. </p>
                                    <a class="btn btn-primary btn-lg" href="#">Read More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--/.item-->
             <div class="item" style="background-image: url(images/slider/bg2.jpg);">
                <div class="slider-inner">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="carousel-content">
                                    <h2>Beautifully designed <span>free</span> one page template</h2>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et  dolore magna incididunt ut labore aliqua. </p>
                                    <a class="btn btn-primary btn-lg" href="#">Read More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--/.item-->
        </div><!--/.owl-carousel-->
    </section><!--/#main-slider-->

    <section id="cta" class="wow fadeIn">
        <div class="container">
            <div class="row">
                <div class="col-sm-9">
                    <h2>選擇使用者</h2>
                        <p style="color:red;">選擇使用者</p>
                            <?php
                                session_start();
                                unset($_SESSION['nfc']);
                                $_SESSION['ad'];
                                $nfcdata = opendir('/home/vol15_2/byethost3.com/b3_18191922/htdocs/raspberry/NFC');
                                while(($nfile = readdir($nfcdata)) !== false){
                                    if($nfile != "." && $nfile != ".."){
                                        $nfcfile[] = $nfile;
                                    }
                                }
                                closedir($nfcdata);
                                sort($nfcfile);
                                echo "<form action='select.php' name='sortn1' method='get'>";
                                echo "<select name='sortn'>"; 
                                echo "<option> 請選擇使用者 </option>";
                                for($i = 0; $i < count($nfcfile); $i++){
                                    $nffile = substr($nfcfile[$i],0,-4);
                                    echo "<option value='$nfcfile[$i]'>"; 
                                    echo $nffile; 
                                    echo "</option>";
                                }
                                echo "</select>";
                                echo "<input type='submit' value='查詢'>";
                                echo "</form>";
                            ?>
                </div>
                <div class="col-sm-3 text-right">
                    <a class="btn btn-primary btn-lg" target="_blank" href="http://pm25-test.byethost3.com/raspberry/GPSPHP2/gps0.php">立刻前往!</a>
                </div>
            </div>
        </div>
    </section><!--/#cta-->

    

    <section id="services" >
        <div class="container">

            <div class="section-header">
                <h2 class="section-title text-center wow fadeInDown">系統功能</h2>
                <p class="text-center wow fadeInDown">我們的系統具備以下六種功能 <br> 絕對是非常厲害又有價值的系統 </p>
            </div>

            <div class="row">
                <div class="features">
                    <div class="col-md-4 col-sm-6 wow fadeInUp" data-wow-duration="300ms" data-wow-delay="0ms">
                        <div class="media service-box">
                            <div class="pull-left">
                                <i class="fa fa-line-chart"></i>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">緊急求救訊息</h4>
                                <p>以陀螺儀的數據判斷，當發生嚴重車禍時，第一時間傳送求救簡訊。裡面包含機車騎士個人資料、血型以及事發現場的經緯度。</p>
                            </div>
                        </div>
                    </div><!--/.col-md-4-->

                    <div class="col-md-4 col-sm-6 wow fadeInUp" data-wow-duration="300ms" data-wow-delay="100ms">
                        <div class="media service-box">
                            <div class="pull-left">
                                <i class="fa fa-cubes"></i>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">路徑追蹤</h4>
                                <p>透過網頁呈現機車騎士目前的所在地，也可透過選單點選日期，呈現各個階段的歷史路徑。還能看到寄簡訊、藍芽判斷的紀錄。</p>
                            </div>
                        </div>
                    </div><!--/.col-md-4-->

                    <div class="col-md-4 col-sm-6 wow fadeInUp" data-wow-duration="300ms" data-wow-delay="200ms">
                        <div class="media service-box">
                            <div class="pull-left">
                                <i class="fa fa-pie-chart"></i>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">行車紀錄器</h4>
                                <p>搭配raspberry pi專屬的紅外線相機，記錄路上的所有情況，使用者不必另外買行車紀錄器。</p>
                            </div>
                        </div>
                    </div><!--/.col-md-4-->
                
                    <div class="col-md-4 col-sm-6 wow fadeInUp" data-wow-duration="300ms" data-wow-delay="300ms">
                        <div class="media service-box">
                            <div class="pull-left">
                                <i class="fa fa-bar-chart"></i>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">藍芽語音判斷</h4>
                                <p>為了避免誤傳緊急訊息，在陀螺儀數據出現不尋常的值時，會啟動藍芽語音判斷。使用者安全帽內帶有藍芽耳機，一旦語音辨識啟動，會撥放一段音樂，使用者若按下開關則系統判斷為安全。反之則系統判斷為使用者嚴重受傷，立即傳送緊急求救訊息。</p>
                            </div>
                        </div>
                    </div><!--/.col-md-4-->

                    <div class="col-md-4 col-sm-6 wow fadeInUp" data-wow-duration="300ms" data-wow-delay="400ms">
                        <div class="media service-box">
                            <div class="pull-left">
                                <i class="fa fa-language"></i>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">NFC 儲存個人資料</h4>
                                <p>我們結合NFC TAG功能，使用者在使用系統前，將個人資料輸入手機的NFC app。再將手機靠近raspberry pi上的NFC TAG，資料就會傳進raspberry pi裡。</p>
                            </div>
                        </div>
                    </div><!--/.col-md-4-->

                    <div class="col-md-4 col-sm-6 wow fadeInUp" data-wow-duration="300ms" data-wow-delay="500ms">
                        <div class="media service-box">
                            <div class="pull-left">
                                <i class="fa fa-bullseye"></i>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">Raspberry pi 伺服器陀螺儀數據圖</h4>
                                <p>我們在pi建立簡單的伺服器，只要pi開機，輸入IP就能進入網頁。網頁內容則是將陀螺儀的數據用折線圖表示。</p>
                            </div>
                        </div>
                    </div><!--/.col-md-4-->
                </div>
            </div><!--/.row-->    
        </div><!--/.container-->
    </section><!--/#services-->

    <section id="portfolio">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title text-center wow fadeInDown">硬體設備</h2>
                <p class="text-center wow fadeInDown">以下是我們用到的硬體設備</p>
            </div>

            <div class="text-center">
                <ul class="portfolio-filter">
                    <li><a class="active" href="#" data-filter="*">All </a></li>
                    <li><a href="#" data-filter=".sim908">sim908</a></li>
                    <li><a href="#" data-filter=".MPU6050">MPU6050</a></li>
                    <li><a href="#" data-filter=".NFC">NFC</a></li>
                    <li><a href="#" data-filter=".bluetooth">bluetooth</a></li>
                </ul><!--/#portfolio-filter-->
            </div>

            <div class="portfolio-items">
                <div class="portfolio-item sim908 MPU6050 NFC bluetooth">
                    <div class="portfolio-item-inner">
                        <img class="img-responsive" src="images/portfolio/01.png" alt="">
                        <div class="portfolio-info">
                            <h3>Raspberry B+</h3>
                            系統開發的主要工具
                            <a class="preview" href="images/portfolio/01.png" rel="prettyPhoto"><i class="fa fa-eye"></i>123</a>
                        </div>
                    </div>
                </div><!--/.portfolio-item-->

                <div class="portfolio-item MPU6050">
                    <div class="portfolio-item-inner">
                        <img class="img-responsive" src="images/portfolio/02.png" alt="">
                        <div class="portfolio-info">
                            <h3>MPU6050</h3>
                            可以測加速度與傾斜角度
                            <a class="preview" href="images/portfolio/02.png" rel="prettyPhoto"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                </div><!--/.portfolio-item-->

                <div class="portfolio-item NFC">
                    <div class="portfolio-item-inner">
                        <img class="img-responsive" src="images/portfolio/03.png" alt="">
                        <div class="portfolio-info">
                            <h3>NFC TAG</h3>
                            儲存使用者的個人資料
                            <a class="preview" href="images/portfolio/03.png" rel="prettyPhoto"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                </div><!--/.portfolio-item-->

                <div class="portfolio-item sim908">
                    <div class="portfolio-item-inner">
                        <img class="img-responsive" src="images/portfolio/04.png" alt="">
                        <div class="portfolio-info">
                            <h3>GPRS+GPS Quadband Module</h3>
                            搭載sim908晶片，可通訊和定位的模組
                            <a class="preview" href="images/portfolio/04.png" rel="prettyPhoto"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                </div><!--/.portfolio-item-->

                <div class="portfolio-item creative sim908">
                    <div class="portfolio-item-inner">
                        <img class="img-responsive" src="images/portfolio/05.png" alt="">
                        <div class="portfolio-info">
                            <h3>Raspberry Pi to Arduino Shields Connection Bridge</h3>
                            連接 pi 與 GPRS+GPS 模組的橋樑
                            <a class="preview" href="images/portfolio/05.png" rel="prettyPhoto"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                </div><!--/.portfolio-item-->

                <div class="portfolio-item sim908">
                    <div class="portfolio-item-inner">
                        <img class="img-responsive" src="images/portfolio/06.png" alt="">
                        <div class="portfolio-info">
                            <h3>Internal GPS Antenna</h3>
                            GPS天線，讓收訊更加穩定
                            <a class="preview" href="images/portfolio/06.png" rel="prettyPhoto"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                </div><!--/.portfolio-item-->

                <div class="portfolio-item creative sim908">
                    <div class="portfolio-item-inner">
                        <img class="img-responsive" src="images/portfolio/07.png" alt="">
                        <div class="portfolio-info">
                            <h3>4G-3G-GPRS-GSM Internal Antenna</h3>
                            支援GSM~4G的天線，讓收訊更加穩定
                            <a class="preview" href="images/portfolio/07.png" rel="prettyPhoto"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                </div><!--/.portfolio-item-->

                <div class="portfolio-item sim908 MPU6050 NFC">
                    <div class="portfolio-item-inner">
                        <img class="img-responsive" src="images/portfolio/08.png" alt="">
                        <div class="portfolio-info">
                            <h3>杜邦線</h3>
                            包括公對公、公對母、母對母
                            <a class="preview" href="images/portfolio/08.png" rel="prettyPhoto"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                </div><!--/.portfolio-item-->
                
                <div class="portfolio-item bluetooth">
                    <div class="portfolio-item-inner">
                        <img class="img-responsive" src="images/portfolio/09.png" alt="">
                        <div class="portfolio-info">
                            <h3>藍芽耳機</h3>
                            實施語音辨識
                            <a class="preview" href="images/portfolio/09.png" rel="prettyPhoto"><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                </div><!--/.portfolio-item-->
            </div>
        </div><!--/.container-->
    </section><!--/#portfolio-->

    
    <section id="features">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title text-center wow fadeInDown">未來展望</h2>
                <p class="text-center wow fadeInDown">將系統完善是我們專題結束後的目標 <br> 左下角的圖片要換，真的是醜到爆</p>
            </div>
            <div class="row">
                <div class="col-sm-6 wow fadeInLeft">
                    <img class="img-responsive" src="images/main-feature2.png" alt="">
                </div>
                <div class="col-sm-6">
                    <div class="media service-box wow fadeInRight">
                        <div class="pull-left">
                            <i class="fa fa-line-chart"></i>
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">增加網路傳輸速度</h4>
                            <p>目前我們使用sim908透過FTP方式上傳資料到伺服器，但受限於網路速度太慢，導致系統在實際運作中會有少許時間差。希望未來能使用更好的硬體來提升系統的效率。</p>
                        </div>
                    </div>

                    <div class="media service-box wow fadeInRight">
                        <div class="pull-left">
                            <i class="fa fa-cubes"></i>
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">提升GPS精準度</h4>
                            <p>目前GPS定位晶片較不穩定，希望未來可以採用較高階的設備，使其精準度提升，並且提升抓取訊號的速度。</p>
                        </div>
                    </div>

                    <div class="media service-box wow fadeInRight">
                        <div class="pull-left">
                            <i class="fa fa-pie-chart"></i>
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">結合警察系統與還原車禍情況</h4>
                            <p>希望未來能跟警方合作，導入此緊急系統，一旦有車禍事故發生，可以第一時間通知警局。
                               此外結合GPS位置和陀螺儀數據，透過繪圖軟體依據數據模擬還原當時碰撞的情況，以利警察判斷。
                            </p>
                        </div>
                    </div>

                    <div class="media service-box wow fadeInRight">
                        <div class="pull-left">
                            <i class="fa fa-pie-chart"></i>
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">將系統安裝於機車</h4>
                            <p>未來會實施將系統置入機車內部，行車紀錄器位於龍頭下方；raspberry pi位於機車前殼內並做好固定及防護措施；
                               NFC TAG會固定在外殼上並以透明玻璃保護；電源則利用機車的電，一旦發動，系統就啟動。
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="cta2">
        <div class="container">
            <div class="text-center">
                <h2 class="wow fadeInUp" data-wow-duration="300ms" data-wow-delay="0ms"><span>MULTI</span> 這個圖片要改</h2>
                <p class="wow fadeInUp" data-wow-duration="300ms" data-wow-delay="100ms">Mauris pretium auctor quam. Vestibulum et nunc id nisi fringilla <br />iaculis. Mauris pretium auctor quam.</p>
                <p class="wow fadeInUp" data-wow-duration="300ms" data-wow-delay="200ms"><a class="btn btn-primary btn-lg" href="#">Free Download</a></p>
                <img class="img-responsive wow fadeIn" src="images/cta2/cta2-img.png" alt="" data-wow-duration="300ms" data-wow-delay="300ms">
            </div>
        </div>
    </section>
    
    

    

    

    <footer id="footer">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    &copy; 2016 暨大資管專題第11組. Designed by <a target="_blank" href="http://shapebootstrap.net/" title="Free Twitter Bootstrap WordPress Themes and HTML templates">ShapeBootstrap</a>
                </div>
                <div class="col-sm-6">
                    <ul class="social-icons">
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
                    </ul>
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