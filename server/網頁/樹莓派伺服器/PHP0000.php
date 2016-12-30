<html>
    <head>
        <meta charset="utf-8">
        <!-- 最新編譯和最佳化的 CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
        <script src ="http://cdn.hcharts.cn/jquery/jquery-1.8.3.min.js"></script>
        <script src ="http://cdn.hcharts.cn/highcharts/highcharts.js"></script>
        <script src ="http://cdn.hcharts.cn/highcharts/modules/exporting.js"></script>
        <script src ="http://cdn.hcharts.cn/highcharts/themes/sand-signika.js"></script> <!--背景主題 grid-light.js、sand-signika.js、dark-unica.js、!-->
        <?php
            /*開檔、讀檔、分割檔案內容並存至陣列*/
            $file = fopen("mpudata.txt", "r");
            $num=0;
            while (!feof($file)){      //判斷是否到文件尾端
                $value = fgets($file); //一行一行讀
                $value2 = preg_split("/[\s,]+/",$value);//把每一行資料再以空白切三等(時間 加速度 傾斜角度)
                /*
                value[0] = 時間     = date  array         
                value[1] = 陀螺儀   = angle array
                value[2] = 加速度   = speed array
                */
                $date[$num]=$value2[0];         //時間陣列
                $value2[1]=(double)$value2[1];  //將原"字串"陣列轉成double(這樣後面才能畫圖)
                $speed[$num]=$value2[1];        //陀螺儀陣列
                $value2[2]=(double)$value2[2];  //轉double
                $angle[$num]=$value2[2];        //加速度陣列
                $angle[$num]= ($angle[$num]*3000)/17; //將加速度做正規畫
                $num=$num+1;
            }
            fclose($file);
            /*
            /*加速度圖的水平線
            for($i=0; $i<count($date); $i++) /*設定"藍芽判斷"水平線 = 1500
                $level[$i] = 2000;
                
            for($i=0; $i<count($date); $i++) /*設定"寄簡訊"水平線2 = 3000
                $level2[$i] = 3000;
            
            /*傾斜角度的水平線           
            for($i=0; $i<count($date); $i++) /*設定"藍芽判斷"水平線 = 1500
                $level3[$i] = 15;
            
            for($i=0; $i<count($date); $i++) /*設定"寄簡訊"水平線2 = 3000
                $level4[$i] = 17;
            */


            /*javascript接收PHP陣列*/
            $uid  = "陀螺儀";
            $uid2 = "加速度";
            $uid3 = "藍芽判斷";
            $uid4 = "傳簡訊";
            $array1 = json_encode($date)  ; //接收php的時間陣列 
            
            $array2 = array(array("name"=>$uid,"data"=>$speed)); //接收php的加速度陣列 
            $array2 = json_encode($array2) ;        
            $array3 = array(array("name"=>$uid2,"data"=>$angle)); //接收php的傾斜角度陣列 
            $array3 = json_encode($array3) ;
            
            
            $array4 = array(array("name"=>$uid,"data"=>$speed),array("name"=>$uid2,"data"=>$angle)); //接收php的陀螺儀、加速度陣列 
            $array4 = json_encode($array4) ;
            /*
            $array4 = array(array("name"=>$uid,"data"=>$speed),array("name"=>$uid2,"data"=>$angle),array("name"=>$uid3,"data"=>$level),array("name"=>$uid4,"data"=>$level2)); //接收php的加速度、傾斜角度陣列 
            $array4 = json_encode($array4) ;
            $array5 = array(array("name"=>$uid,"data"=>$speed),array("name"=>$uid3,"data"=>$level),array("name"=>$uid4,"data"=>$level2)); //接收php的加速度、水平線陣列 
            $array5 = json_encode($array5) ;
            $array6 = array(array("name"=>$uid,"data"=>$angle),array("name"=>$uid3,"data"=>$level3),array("name"=>$uid4,"data"=>$level4)); //接收php的加速度、水平線陣列 
            $array6 = json_encode($array6) ;
            */
        ?>
    </head>
    <body>
        <div id="introduction" style="min-width:400px;height:100px">
          <div class="row">
              <div class="col-xs-6">
                <strong>標示區</strong>：
                  <ol>
                    <li>當加速度與陀螺儀同時達到2000以上，進行藍芽判斷</li>
                    <li>當加速度與陀螺儀同時達到3000以上，就傳送緊急求救訊息</li>
                  </ol>
              </div>
              <div class="col-xs-6">
                <dl>
                  <dt>平移：</dt>
                    <ol>
                      <dd><li>滑鼠移到想看的區域，將區域框起來</li></dd>
                      <dd><li>案住「shift」+滑鼠左右拖曳</li></dd>
                      <dd><li>平移後按下右上角的Reset zoom可回到原圖</li></dd>
                    </ol>
                </dl>
              </div>
          </div>            
        </div>
        <div id="container" style="min-width:400px;height:800px"></div>
        <script>   
            
            $(function () {            
                /*兩合一圖*/
                $('#container').highcharts({
                    chart: {
                        type: 'line',
                        zoomType: 'x',
                        panning: true,
                        panKey: 'shift'
                    },
                    title: { //標題
                        text: '陀螺儀與加速度折線圖',
                        x: -20 //center
                    },
                    subtitle: {  //副標題
                        text: 'Source: MPU6050 in Puli',
                        x: -20
                    },
                    xAxis: {    //X軸的資料
                       // categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                       //             'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                       categories:<?php echo $array1; ?>
                    },
                    yAxis: {    //Y軸的資料
                        title: {
                            text: '陀螺儀、加速度 '
                        },
                        plotLines: [{
                            value: 0,
                            width: 1,
                            color: '#808080'
                        }],
                        plotBands: [{    /*表示圖框*/
                            from: 2000,
                            to: 3000,
                            color: 'rgba(68, 170, 213, 0.1)',
                            label: {
                                text: '藍芽判斷',
                                style: {
                                    color: '#606060'
                                }
                            }
                        },{ 
                            from: 3000,
                            to: 6000,
                            color: 'rgba(158, 70, 213, 0.1)',
                            label: {
                                text: '傳簡訊',
                                style: {
                                    color: '#606060'
                                }
                            }
                        }]
                    },                  
                    tooltip: {
                        
                        pointFormat: '<span style="color:{series.color}">{series.name}</span>:  {point.y:,.0f}　　<br/>', /*point.y:值(可加單位)*/
                        //crosshairs: [false,true], 水平線
                        crosshairs: [true,false],
                        shared: true,  //共用提示框
                        crosshairs: [{            // 设置准星线样式
                            width: 1,
                            color: "#006cee",
                            dashStyle: 'longdashdot',
                            zIndex: 100
                        }]
                        
                        
                    },
                    
                    
                    series: <?php echo $array4; ?> 

                    /*[{
                        name: 'Tokyo',
                        data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]},]
                    */
                });
            });
        </script>


    </body>
<html>