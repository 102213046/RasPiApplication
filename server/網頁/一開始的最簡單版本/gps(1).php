<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!-- Load Jquery -->
    <script language="JavaScript" type="text/javascript" src="jquery-1.10.1.min.js"></script>
    
    <!-- Load Google Maps Api -->
    <!-- IMPORTANT: change the API v3 key -->
    <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAdUhN2pkrdJiX2NXk0yFRX8QPmobXP9LQ&sensor=false"></script><!--這裡 sensor=false 表示此為一般電腦, 不是行動裝置,  不使用感應器. -->
    
    <title>歷史路徑</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="shortcut icon" href="icon.ico">

</head>
<style type="text/css">
#googlemap {
    position: absolute;
    top: 75;
    left: 360;
    width: 1000px;
    height: 560px;
}
#info {
    padding-left: 20px;;
}
</style>

<body>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <ul class="nav navbar-nav">  
      <li><a href="http://pm25-test.byethost3.com/raspberry/GPSPHP/gpsnow.php"><h4>現在位置</h4></a></li>         
      <li><a href="http://pm25-test.byethost3.com/raspberry/GPSPHP/user.php"><h4>使用者資訊</h3></a></li>
      <li class="active"><a href="http://pm25-test.byethost3.com/raspberry/GPSPHP/gps.php"><h4>歷史路徑</h4></a></li>
      <!--<li><a href="#"><h4>現在位置</h4></a></li>-->
	  <li><a href="http://pm25-test.byethost3.com/raspberry/GPSPHP/introduce.php"><h4>介紹網站</h4></a></li>
	  <li><a href="http://pm25-test.byethost3.com/raspberry/GPSPHP/about.php#"><h4>關於我們</h4></a></li>
	  <li><a href="#"><h4>FAQs</h4></a></li>
      <li><a href="http://pm25-test.byethost3.com/raspberry/GPSPHP/gps0.php"><h4>登出</h4></a></li>
    </ul>
  </div>
</nav>
<?php
    //ini_set("display_errors", "On"); 
    //error_reporting(E_ALL & ~E_NOTICE);
?>
<div id="info">
<p style="color:red;">使用者資訊</p>
<?php
    session_start();
    $index = $_GET['sortn'];
    
    if($_SESSION['nfc'] == null){
        $_SESSION['nfc'] = $index;
    }
    else{
        $index = $_SESSION['nfc'];
    }
    $nfcdata = opendir('/home/vol15_2/byethost3.com/b3_18191922/htdocs/raspberry/NFC');
    $i = 0;
    while(($nfile = readdir($nfcdata)) !== false){
        if($nfile != "." && $nfile != ".."){
            if($i == $index){
                $newnfctxt = $nfile;
                break;
            }
            $i++;
        }
    }
    closedir($nfcdata);
    $nfcpath = "http://pm25-test.byethost3.com/raspberry/NFC/" . $newnfctxt;
    
    // Use fopen function to open a file
    $file = fopen($nfcpath , "r");

    $value = fgets($file);
    $nfcvalue = explode(":", $value);
    echo $nfcvalue[1];
    fclose($file);
?>
<br><br>
<p style="color:red;">選擇日期</p>
<!--<p style="color:red;">原始時間(UTC)+GPS位址(RMC)</p>-->
<?php
    $gpsdata = opendir('/home/vol15_2/byethost3.com/b3_18191922/htdocs/raspberry/GPS');
    while(($gfile = readdir($gpsdata)) !== false){
        if($gfile != "." && $gfile != ".."){
            if(strcmp(substr($gfile,14),$newnfctxt) == 0){
                $gpsfile[] = $gfile;
            }   
        }
    }
    closedir($gpsdata);
    rsort($gpsfile);
    $gpsdate = '00';
    $matrixb = -1;
    for($i = 0; $i < count($gpsfile); $i++){
        if(strcmp(substr($gpsfile[$i],0,6),$gpsdate) == 0){
            $gpsdateinfo[$matrixb][$matrixa] = $gpsfile[$i];
            $matrixa++;
        }
        else{
            $matrixb++;
            $matrixa = 0;
            $gpsdateinfo[$matrixb][$matrixa] = substr($gpsfile[$i],0,6);
            $gpsdate = substr($gpsfile[$i],0,6);
            $matrixa++;
        }
    }
    echo "<form action='gps.php' name='sort2' method='get'>";
    echo "<select name='sortg' onchange='javascript:submit()'>"; 
    echo "<option> 請選擇日期 </option>"; 
    for($i=0;$i<count($gpsfile);$i++) { 
        if($gpsdateinfo[$i][0] == null)
            break;
        $gdate = "20" . substr($gpsdateinfo[$i][0],0,2) . "/" . substr($gpsdateinfo[$i][0],2,2) . "/" . substr($gpsdateinfo[$i][0],4,2);
        echo "<option value=$i>"; 
        echo $gdate;
        echo "</option>"; 
    }
    echo "</select>";
    echo "</form>";
    if($_GET['sortg'] == null)
        $newgpstxt = 0;
    else
        $newgpstxt = $_GET['sortg'];
?>

<p style="color:red;">日期</p>
<?php
    $datedata = $gpsdateinfo[$newgpstxt][0];
    
    $valuey = substr($datedata,0,2);
    $valuem = substr($datedata,2,2);
    $valued = substr($datedata,4,2);
    /*
    $file = fopen($gpspath, "r");
 
    $value1 = fread($file,2);  //讀取指定長度的資料(小時)
    $value1 = (double)$value1;  //轉成浮點數
    $value1 = $value1+8;
  
    fseek($file,2);           //設定讀取起始位置
    $value2 = fread($file,2);  //讀取指定長度的資料(分)
   
    fseek($file,4);           //設定讀取起始位置
    $value3 = fread($file,2);  //讀取指定長度的資料(秒)
    */
    $time = "20%d 年 %d 月 %d 日";
    echo sprintf($time,$valuey,$valuem,$valued);
    //fclose($file);
    
?>
<br><br>
<!--
<p style="color:red;">GPS位址(RMC格式)</p>
<?php
/*
[PHP]
數字轉字串
$str = sprintf("%d",$num)

字串轉數字
$num = (int)$str
*/
    /*
    // Use fopen function to open a file
    $file = fopen($gpspath, "r");

    // Read the file line by line until the end
    while (!feof($file)) {
        fseek($file,11); //設定讀取起始位置
        $value = fgets($file,30);
        print "" . $value . "<br>";
    }
    fclose($file);
    */
?>
<br></br>
-->

<p style="color:red;">GPS位址(經度 , 緯度)</p>
<?php
    echo "<div style='height:280px; width:325px; overflow:auto;'>";
    for($i = 1; $i < count($gpsdateinfo[$newgpstxt]); $i++){
        $gpstxt = $gpsdateinfo[$newgpstxt][$i];
        $gpspath = "http://pm25-test.byethost3.com/raspberry/GPS/" . $gpstxt;
        
        $valueh = substr($gpstxt,6,2);
        $valuemi = substr($gpstxt,8,2);
        $values = substr($gpstxt,10,2);
        
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
        print $valueh.":".$valuemi.":".$values." (".$answer2." , ".$answer1.")<br><br>";  //注意格式為double
        
        //****************************************//
        $longtude[$i] = $answer2;
        $latitude[$i] = $answer1;
        $gtime[$i] =  $valueh . ":" . $valuemi . ":" . $values;
        fclose($file1);
    }
    echo "</div>";
?>
</div>
</div>

<div class="googlemap" id="googlemap" style="width:1000px; height:560px"> <!--地圖的大小-->
  <script>
    var longtude = ["<?php echo join("\", \"", $longtude); ?>"];  //讀PHP的經度
    var latitude = ["<?php echo join("\", \"", $latitude); ?>"]; //讀PHP的緯度
    var gtime = ["<?php echo join("\", \"", $gtime); ?>"]; //讀PHP的緯度
    

    $(document).ready(function(){
        
        
        var googlemap=$("#googlemap")[0];  //$("#googlemap")所取得的是畫布的 jQuery 包裹物件, 必須呼叫 get() 或用陣列元素 0 取得其 DOM 物件才能為 Google Maps API 所用. 
        <!--這裡要改成可以讀GPS.txt的值-->
        var latlng = [];
        latlng[0]=new google.maps.LatLng(latitude[0],longtude[0]);
        var opt={zoom:15, center:latlng[0], mapTypeId:"roadmap"};              <!--地圖的放大倍數 zoom 為 15 倍, 中心點座標 center 為所建立之座標物件, 以及地圖類型為道路地圖 "roadmap"-->
        var map=new google.maps.Map(googlemap, opt);        
        <!--呼叫 Map() 方法建立地圖物件-->
        var marker=new google.maps.Marker({position:latlng[0], map:map , title:gtime[0]});
        for(var i = 1; i < longtude.length; i++){
            latlng[i]=new google.maps.LatLng(latitude[i],longtude[i]);  <!--傳入經緯度 23.95241728333,120.92856001666-->
            
            marker=new google.maps.Marker({position:latlng[i], map:map , title:gtime[i]}); 
            <!--將座標物件與地圖物件傳給 Marker() 方法就會繪製地圖-->
        }
    });
  </script>
</div>
</body>
</html>