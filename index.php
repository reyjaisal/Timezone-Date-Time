<?php 
$str = file_get_contents("jsons/timezone.json");
$jsonZone = json_decode($str);


$findByName = function($name) use ($jsonZone) {
    foreach ($jsonZone->one as $city) {
        if ($city->city == $name) return $city->timezone;
    }
    return false;
};

if(isset($_GET['timezone']))
{   
    $tmzn = $_GET['timezone'];  
    $temp = $findByName($tmzn);
    
    if($temp != ""){
        $var = $findByName($tmzn);
        $date = new DateTime("now", new DateTimeZone($var) );
        $theTime = $date->format('Y-m-d H:i:s');
    }
    else{
        $date = new DateTime("now", new DateTimeZone($tmzn) );
        $theTime = $date->format('Y-m-d H:i:s');
    }
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
    <title>Internet Time</title>
</head>
<body>
    
    <h1>Date/Time of your <?php echo $tmzn; ?>: <span style="color: red;"><?php echo $theTime ?></span></h1>
    <h4 style=" background-color:#333; color:#fff;">Note: <em>In Localhost you can't get your region time zone</em></h3>
    <h2 style="">If you want to run it on LocalHost then choose your method</h2> 
    <h3 style="color:#eed5d5"><span style="color: red">NOTE:</span> The Input tags have same name and id, so make your you do not use the tags with same 'name' and 'id' because the 'name' and 'id' is unique in whole project.</h3> 
	
    <h4 style="margin-bottom:8px">1. Choose Timezone from List:</h4> 
    <!-- Choosing Timezone from Datalist -->
    <form action="">
        <input type="search" list="timezonelist1" name="timezone" id="timezone" placeholder="Find Your Timezone" class="form-control" required> <button type="submit">Submit</button>
        <datalist id="timezonelist1"> 
            <?php foreach ($jsonZone->two as $value) { ?> 
                <option value="<?php echo $value ?>"></option>
            <?php } ?>
        </datalist> 
    </form>

    <h4 style="margin-bottom:8px">2. By Entering City Name:</h4> 
    <!-- Find time zone by city name! -->
	<form action="">
    <input type="search" list="timezonelist2" name="timezone" id="timezone" placeholder="Enter City Name" class="form-control" required> <button type="submit">Submit</button>
        <datalist id="timezonelist2"> 
            <?php foreach ($jsonZone->one as $key => $value) { ?> 
                <option value="<?php echo $value->city ?>"></option>
            <?php } ?>
        </datalist> 
    </form>
</body>
</html>
