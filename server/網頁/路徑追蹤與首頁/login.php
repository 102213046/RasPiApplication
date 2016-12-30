<?php
    session_start();
    unset($_SESSION['nfc']);
    unset($_SESSION['ad']);
    $name = $_GET['sortn'];
    $password = $_GET['password'];
    if($name == null){
        header('Location: index.html');
    }
    $nfcpath = "0";
    if(strcmp($name,'CY') == 0){
        if(strcmp($password,'123456') == 0){
            $_SESSION['ad'] = $name;
            $nfcpath = "1";
            header('Location: admit.php');
        }
		else
            header('Location: index.html');
	}	
    
    
    $nfcdata = opendir('/home/vol2_5/byethost10.com/b10_19011039/htdocs/raspberry/NFC');
    while(($nfile = readdir($nfcdata)) !== false){
        if($nfile != "." && $nfile != ".."){
            if(strcmp(substr($nfile,0,-4),$name) == 0){
                $nfcpath = "http://motorcycle.byethost10.com/raspberry/NFC/" . $name . ".txt";
            }
        }
    }
    closedir($nfcdata);
    if(strcmp($nfcpath, "0") == 0)
        header('Location: index.html');
    $file = fopen($nfcpath , "r");
    while (!feof($file)) {
        $value = fgets($file);
        $nfcvalue = explode(":", $value);
        if(count($nfcvalue) != 2)
            break;
        if(strcmp($nfcvalue[0],'ID') == 0){
            echo $nfcvalue[0]. "<br>";
            if(strcmp(substr($nfcvalue[1],0,-1),$password) == 0){
                $_SESSION['nfc'] = $name . ".txt";
                header('Location: gpsnow.php');
                break;
            }
            else
                header('Location: index.html'); 
        }  
			
    }
    fclose($file);
?>