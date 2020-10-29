<?php 
$str = file_get_contents("timezone.json");
$jsonZone = json_decode($str);

if(isset($_GET['timezone']))
{   
    $tmzn = $_GET['timezone'];  
    $date = new DateTime("now", new DateTimeZone($tmzn) );
    $theTime = $date->format('Y-m-d H:i:s');
}
else{
    $tmzn = "";
    // Function for getting client IP
    function get_client_ip() {
        $ipaddress = '';
        if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        return $ipaddress;
    }
    $ip = get_client_ip(); 

    // Getting client location by using device IP
    $query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip)); 
    if($query && $query['status'] == 'success') 
    {
        $date = new DateTime("now", new DateTimeZone($query['timezone']) );
        $theTime = $date->format('Y-m-d H:i:s');
    }
    else{
        $theTime = "Error! (LocalHost Detected)";
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TimeZone | By ReyJaisal</title>
</head>
<body>
    
    <h1>Date/Time of your Timezone <?php echo $tmzn; ?>: <span style="color: red;"><?php echo $theTime ?></span></h1>
    <h4 style=" background-color:#333; color:#fff;">Note: <em>In Localhost you can't get your region time zone</em></h3>
    <form action="">
    <h4>If you really want to run it on LocalHost, or use custom timezone then select a custom timezone from list and submit it.</h4>
    <input type="search" list="timezones" name="timezone" id="timezone" placeholder="Find Your Timezone" class="form-control" required> <button type="submit">Submit</button>
    <datalist id="timezones"> 
        <?php foreach ($jsonZone as $zone) { ?> 
            <option value="<?php echo $zone ?>"></option>
        <?php } ?>
    </datalist> 
     
    
    </form>
	
</body>
</html>