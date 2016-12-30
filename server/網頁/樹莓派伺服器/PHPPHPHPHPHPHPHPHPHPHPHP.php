<html>
    <head>
        <meta charset="utf-8">
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
                value[1] = 加速度   = speed array
                value[2] = 傾斜角度 = angle array
                */
                $date[$num]=$value2[0];         //時間陣列
                $value2[1]=(double)$value2[1];  //將原"字串"陣列轉成double(這樣後面才能畫圖)
                $speed[$num]=$value2[1];        //加速度陣列
                $value2[2]=(double)$value2[2];  //轉double
                $angle[$num]=$value2[2];        //角度陣列
                /*$angle[$num]= ($angle[$num]*3000)/17; //將傾斜角度做正規畫*/
                $num=$num+1;
            }
            fclose($file);
            
            /*加速度圖的水平線
            for($i=0; $i<count($date); $i++) //設定"藍芽判斷"水平線 = 1500
                $level[$i] = 2000;
                
            for($i=0; $i<count($date); $i++) //設定"寄簡訊"水平線2 = 3000
                $level2[$i] = 3000;
            
            //傾斜角度的水平線         
            for($i=0; $i<count($date); $i++) //設定"藍芽判斷"水平線 = 1500
                $level3[$i] = 15;
            
            for($i=0; $i<count($date); $i++) //設定"寄簡訊"水平線2 = 3000
                $level4[$i] = 17;
            */


            /*javascript接收PHP陣列*/
            $uid  = "加速度";
            $uid2 = "傾斜角度";
            $uid3 = "藍芽判斷";
            $uid4 = "傳簡訊";
            $array1 = json_encode($date)  ; //接收php的時間陣列 
            
            $array2 = array(array("name"=>$uid,"data"=>$speed)); //接收php的加速度陣列 
            $array2 = json_encode($array2) ;        
            $array3 = array(array("name"=>$uid2,"data"=>$angle)); //接收php的傾斜角度陣列 
            $array3 = json_encode($array3) ;
            
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
        <div id="container" style="min-width:400px;height:800px"></div>
        <script>             
                /*加速度圖*/
            $(function () {
                $('#container').highcharts({
                    title: { //標題
                        text: '加速度折線圖',
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
                            text: '加速度 (m/s)'
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
                        valueSuffix: 'm/s'
                        
                    },
                    legend: { /*右邊會有圖示，哪一條的顏色和名稱*/
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'middle',
                        borderWidth: 0
                    },
                    series: <?php echo $array2; ?> 

                    /*[{
                        name: 'Tokyo',
                        data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]},]
                    */
                });
            });
        </script>
        <p></p>
        <div id="container2" style="min-width:400px;height:800px"></div>
        <script>   
            /*傾斜角度圖*/        
            $(function () {
                $('#container2').highcharts({
                    colors: ['#2f938d'], /*線的顏色*/
                    title: {
                        text: '傾斜角度折線圖',
                        x: -20 //center
                    },
                    subtitle: {
                        text: 'Source: MPU6050 in Puli',
                        x: -20
                    },
                    xAxis: {
                       // categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                       //             'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                       categories:<?php echo $array1; ?>
                    },
                    yAxis: {
                        title: {
                            text: '傾斜角度 (單位我不知道)'
                        },
                        plotLines: [{
                            value: 0,
                            width: 1,
                            color: '#808080'
                        }],
                        plotBands: [{    /*表示圖框*/
                            from: 15,
                            to: 17,
                            color: 'rgba(68, 170, 213, 0.1)',
                            label: {
                                text: '藍芽判斷',
                                style: {
                                    color: '#606060'
                                }
                            }
                        },{ 
                            from: 17,
                            to: 40,
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
                        valueSuffix: 'm/s'
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'middle',
                        borderWidth: 0
                    },
                    series: <?php echo $array3; ?> 

                    /*[{
                        name: 'Tokyo',
                        data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]},]
                    */
                });
            });
        </script>
        <!--
        <p></p>
        <div id="container3" style="min-width:400px;height:400px"></div>
        <script>   
            /*兩者合一圖*/        
            $(function () {
                $('#container3').highcharts({
                    title: {
                        text: '兩者合一折線圖',
                        x: -20 //center
                    },
                    subtitle: {
                        text: 'Source: WorldClimate.com',
                        x: -20
                    },
                    xAxis: {
                       // categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                       //             'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                       categories:<?php echo $array1; ?>
                    },
                    yAxis: {
                        title: {
                            text: ' 我不知道要填什麼來著'
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
                        valueSuffix: 'm/s'
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'middle',
                        borderWidth: 0
                    },
                    series: <?php echo $array4; ?> 

                    /*[{
                        name: 'Tokyo',
                        data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]},]
                    */
                });
            });                   
        </script>
        -->
    </body>
<html>