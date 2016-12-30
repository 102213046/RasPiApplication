<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!-- Load Jquery -->
    <script language="JavaScript" type="text/javascript" src="jquery-1.10.1.min.js"></script>
    
    <!-- Load Google Maps Api -->
    <!-- IMPORTANT: change the API v3 key -->
    <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAdUhN2pkrdJiX2NXk0yFRX8QPmobXP9LQ&sensor=false"></script><!--這裡 sensor=false 表示此為一般電腦, 不是行動裝置,  不使用感應器. -->
</head>
<body>
<?php
    //ini_set("display_errors", "On"); 
    //error_reporting(E_ALL & ~E_NOTICE);
    $gpsdata = opendir('/home/vol2_5/byethost10.com/b10_19011039/htdocs/raspberry/GPS');
    while(($gfile = readdir($gpsdata)) !== false){
        if($gfile != "." && $gfile != "..")
            $gpsfile[] = $gfile;
    }
    closedir($gpsdata);
    rsort($gpsfile);
    $gpstxt = $gpsfile[0];
    $gpspath = "http://motorcycle.byethost10.com/raspberry/GPS/" . $gpstxt;
    echo $gpspath;
?>

<p style="color:red;">以下會印出使用者的個人資訊</p>
<?php
    // Use fopen function to open a file
    $file = fopen("http://motorcycle.byethost10.com/raspberry/NFC/Chang,yi-tzu.txt", "r");

    // Read the file line by line until the end
    while (!feof($file)) {
    $value = fgets($file);
    print "" . $value . "<br>";
    }

    fclose($file);
?>

<p style="color:red;">原始時間(UTC)+GPS位址(RMC)</p>
<?php
    $file = fopen($gpspath, "r");
    $value = fgets($file);
    print "" . $value . "<br><br>";

    fclose($file);
?>

<p style="color:red;">時間</p>
<?php
    $file = fopen($gpspath, "r");
 
    $value1 = fread($file,2);  //讀取指定長度的資料(小時)
    $value1 = (double)$value1;  //轉成浮點數
    $value1 = $value1+8;
  
    fseek($file,2);           //設定讀取起始位置
    $value2 = fread($file,2);  //讀取指定長度的資料(分)
   
    fseek($file,4);           //設定讀取起始位置
    $value3 = fread($file,2);  //讀取指定長度的資料(秒)
    
    $time = "現在時間是 %d 時 %d 分 %d 秒";
    echo sprintf($time,$value1,$value2,$value3);
    fclose($file);
?>
<br></br>

<p style="color:red;">GPS位址(RMC格式)</p>
<?php
/*
[PHP]
數字轉字串
$str = sprintf("%d",$num)

字串轉數字
$num = (int)$str
*/

    // Use fopen function to open a file
    $file = fopen($gpspath, "r");

    // Read the file line by line until the end
    while (!feof($file)) {
        fseek($file,11); //設定讀取起始位置
        $value = fgets($file,30);
        print "" . $value . "<br>";
    }
    fclose($file);
?>
<br></br>

<p style="color:red;">GPS位址(經緯度)</p>
<?php
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
    print "經度". $answer2 ."，緯度" . $answer1 . "<br><br>";  //注意格式為double
    //****************************************//
    fclose($file1);
?>




<p style="color:red;">以下會顯示出使用者的所在地</p>
<div id="googlemap" style="width:1000px; height:800px"></div> <!--地圖的大小-->
  <script>  
  var answer1 = <?php echo $answer1 ?>;  //讀PHP的緯度
  var answer2 = <?php echo $answer2 ?>;  //讀PHP的經度
    $(document).ready(function(){
      var googlemap=$("#googlemap")[0];  //$("#googlemap")所取得的是畫布的 jQuery 包裹物件, 必須呼叫 get() 或用陣列元素 0 取得其 DOM 物件才能為 Google Maps API 所用. 
      <!--這裡要改成可以讀GPS.txt的值-->
      var latlng=new google.maps.LatLng(answer1,answer2);  <!--傳入經緯度 23.95241728333,120.92856001666-->
      var opt={zoom:15, center:latlng, mapTypeId:"roadmap"};              <!--地圖的放大倍數 zoom 為 15 倍, 中心點座標 center 為所建立之座標物件, 以及地圖類型為道路地圖 "roadmap"-->
      var map=new google.maps.Map(googlemap, opt);                        <!--呼叫 Map() 方法建立地圖物件-->
      var marker=new google.maps.Marker({position:latlng, map:map});      <!--將座標物件與地圖物件傳給 Marker() 方法就會繪製地圖-->
      });
  </script>
  
</body>
</html>