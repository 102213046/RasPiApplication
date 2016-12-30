<html>
    <head>
        <meta charset="utf-8">
        <script src ="http://cdn.hcharts.cn/jquery/jquery-1.8.3.min.js"></script>
        <script src ="http://cdn.hcharts.cn/highcharts/highcharts.js"></script>
        <script src ="http://cdn.hcharts.cn/highcharts/modules/exporting.js"></script>
        <?php
            $file = fopen("mpudata.txt", "r");
            $num=0;
            $uid="加速度";
            $uid2="傾斜角度";
            while (!feof($file)){      //判斷是否到文件尾端
                $value = fgets($file); //一行一行讀
                $value2 = preg_split("/[\s,]+/",$value);//把每一行資料再以空白切三等(時間 加速度 傾斜角度)
                /*
                value[0] = 時間     = date  array
                value[1] = 加速度   = speed array
                value[2] = 傾斜角度 = angle array
                */
                $date[$num]=$value2[0];
                $value2[1]=(double)$value2[1]; //將原"字串"陣列轉成double(這樣後面才能畫圖)
                $speed[$num]=$value2[1];
                $value2[2]=(double)$value2[2]; //同上
                $angle[$num]=$value2[2];
                $num=$num+1;
            }
            fclose($file);
        
            $array1 = json_encode($date)  ; //接收php的時間陣列 
            $array2 = array(array("name"=>$uid,"data"=>$speed),array("name"=>$uid2,"data"=>$angle)); //接收php的加速度、傾斜角度陣列 
            $array2 = json_encode($array2) ; 
        ?>
    </head>
    <body>
        <div id="container" style="min-width:400px;height:400px"></div>
        <script>    
            $(function () {
                $('#container').highcharts({
                    title: {
                        text: '陀螺儀加速度折線圖',
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
                            text: '加速度 (m/s)'
                        },
                        plotLines: [{
                            value: 0,
                            width: 1,
                            color: '#808080'
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
                    series: <?php echo $array2; ?> 

                    /*[{
                        name: 'Tokyo',
                        data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]},]
                    */
                });
            });
        </script>
    </body>
<html>