<?php



$names = file("names.txt");
$streets = file("streets.txt");


$name_arr = array();
$street_arr = array();

foreach($names as $line){
	$name = explode(" ", $line);
	$name_arr[] = trim($name[0]);
}

foreach($streets as $line){
	$line = explode(" ", $line);
	$name1 = trim($line[0]);
	$name2 = trim($line[1]);
	$name3 = trim($line[2]);
	$street_arr[] = $name1 . " " . $name2 . " " . $name3;
}

$limit = 300000;
for($i;$i<$limit;$i++){
	$name = $name_arr[rand(0, count($name_arr)-1)];
	$street = rand(1,5000) . " " . $street_arr[rand(0,count($street_arr)-1)];
	$date = date("Y-m-d");
	$phone = "647" . rand(1,9) . rand(1,9) .rand(1,9) . rand(1,9) . rand(1,9) . rand(1,9) . rand(1,9);
	
	$number_of_items = rand(1,9);
	for($j=0;$j<$number_of_items;$J++){
		$inventory_id = rand(3,2709);
		
	}
}

$con = mysql_connect("localhost","root","td8818090");
if (!$con)
{
	die('Could not connect: ' . mysql_error());
}

mysql_select_db("openbook", $con);



$ikea_base = "http://www.ikea.com";
$base_url = "http://www.ikea.com/us/en/catalog/productsaz/";
$yql_base_url = "http://query.yahooapis.com/v1/public/yql";  

for($i=0;$i<=25;$i++){
	$url = $base_url . $i;
	$yql_query1 = "select * from html where (url=\"http://www.ikea.com/us/en/catalog/productsaz/".$i."\") and
      xpath='//span[@class=\"productsAzLink\"]/a'";
	$yql_query_url = $yql_base_url . "?q=" . urlencode($yql_query1);
	$yql_query_url .= "&format=json";
	
	$session = curl_init($yql_query_url);
	curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
	$json = curl_exec($session);
	$phpObj =  json_decode($json);
	
	if(!is_null($phpObj->query->results)){
		foreach($phpObj->query->results->a as $a){
			$item_name = $a->content;
			$item_url = $ikea_base . $a->href;
			$item_quantity = rand(30, 200);
			$item_model = explode("/",$a->href);
			$item_model = $item_model[5];
			
			$yql_query2 = "select * from html where (url=\"".$item_url. "\") and
      xpath='//span[@class=\"packagePrice\"]/text()'";
			$yql_query_url2 = $yql_base_url . "?q=" . urlencode($yql_query2);
			$yql_query_url2 .= "&format=json";
			
			$session2 = curl_init($yql_query_url2);
			curl_setopt($session2, CURLOPT_RETURNTRANSFER,true);
			$json2 = curl_exec($session2);
			$phpObj2 =  json_decode($json2);
			
			if(!is_null($phpObj2->query->results)){
				$item_price = intval(substr($phpObj2->query->results,1));
				$mysql_query = "INSERT INTO `inventory` (`id`, `name`, `model`, `quantity`, `in`, `date`) VALUES (NULL, '$item_name', '$item_model', '$item_quantity', '$item_price', '$date')";
				echo "Executing: " . $mysql_query;
				echo "\n";
				/*
				if (mysql_query($mysql_query)){
					echo "PASSED! \n";
				}else{
					echo "ERROR! \n";
				}
				*/
			}
		}
	}
}

//mysql_close($con);
?>