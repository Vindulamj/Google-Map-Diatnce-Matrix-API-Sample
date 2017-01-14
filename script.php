  <?php
 
//reading the routes.txt file
echo "Loading the routes.txt".PHP_EOL;
 
$myfile = fopen("routes.txt", "r") or die("Unable to open file!");
$content =  fread($myfile,filesize("routes.txt"));
fclose($myfile);

//split by ;
$list =  (explode(";",$content));

//taking the time, date and timezone
$created_dateTime =  date("Y-m-d h:i:sa");
$timezone = date_default_timezone_get();

//iterate through the start/dest pairs
echo "Calculating the results..".PHP_EOL;
WriteToFile(PHP_EOL);
WriteToFile(PHP_EOL);
for($x = 0; $x <= count($list); $x=$x+3) {
	
	$route_name = $list[$x];
    	$start =  $list[$x+1];
	$destination = $list[$x+2];
	
	//api call
	$url = "https://maps.googleapis.com/maps/api/distancematrix/json?units=metrics&origins=".$start."&destinations=".$destination."&key=AIzaSyAiuGv4Ljuv_zGrOXQuK4lAx-8REomt1c0&mode=driving&transit_mode=bus&traffic_model=best_guess&departure_time=now";
	$response = file_get_contents($url);
	
	//json decode 
	$json_decoded = json_decode($response);
	$distance =  $json_decoded->rows[0]->elements[0]->distance->text;
	$duration = $json_decoded->rows[0]->elements[0]->duration_in_traffic->text;
	
	//write to the results file
	$write_line = $route_name.",".$distance.",".$duration.",".$timezone.",".$created_dateTime;
	if($route_name != null) {
			WriteToFile($write_line);
	}
} 
echo "Successfully completed";

//function for writing to a file
function WriteToFile($text){
	$myfile = file_put_contents('results.csv', $text , FILE_APPEND);
}
?> 
