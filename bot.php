<?php
$access_token = 'LZArldUUHwHc6ROvqoAeGz5Kdft2ShdvagfCoiaoPaTpxqjvtA4ImaLk6hbkVguSX6pqlYaJFRB/pLt/q/Ct5w4chCz8hShgIVBOzZYuYM1YPHg8FJ0KS4G8GD3T0iFv7qAbmBvIfFYElhJ+MRgXtQdB04t89/1O/w1cDnyilFU=';

//condb
$db = pg_connect ("postgres://flghpbnnuhfevu:835ecb49bf0c74bc09716dbecdd8aa5df0ff7fa84bde3876dba031b27d632abf@ec2-75-101-142-182.compute-1.amazonaws.com:5432/d5mmu71c2lbm9o");
//
// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data


if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			
			// Get text sent
			$text = $event['message']['text'];
			// Get replyToken
			$replyToken = $event['replyToken'];

			if ($text == "data"){
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				//select//
			/* $json_string = file_get_contents("http://api.wunderground.com/api/a6be6269233f1bc8/conditions/astronomy/q/TH/Bangkok.json");
  			 $parsed_json = json_decode($json_string);
 			 $date = $parsed_json->{'current_observation'}->{'local_time_rfc822'};
			 $temp_c = $parsed_json->{'current_observation'}->{'temp_c'};	
 			 $weather = $parsed_json->{'current_observation'}->{'weather'};
 			 $pressure = $parsed_json->{'current_observation'}->{'pressure_mb'};
			 
			$pushdate = pg_escape_string($date); 
			$pushtemp = pg_escape_string($temp_c);
  			$pushweather = pg_escape_string($weather); 
  			$pushpressure = pg_escape_string($pressure); 
  			$query = ("INSERT INTO weather_botline_proxima VALUES('$pushdate', '$pushtemp', '$pushweather', $pushpressure,'','');");*/
  			///////////////////////////////////////////////////////////////////////////////////////////////////////////
			$query = "SELECT * FROM weather_botline_proxima";
			$num = mysql_num_rows($query);
			$resultsql = pg_query($query);
			$resultArray = array();
				for ($i = 0;$i<$num;$i++) {
					$result = mysql_fetch_array($query);
					array_push($resultArray,$result);
				}
			//echo $resultsql;
			pg_close();
			//////////
			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => "Weather on\n ${date} \n=======================\nTemp is: ${temp_c}C \nWeather is:  ${weather} \nPressure is :  ${pressure}\n======================= "
			];
			}

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";
		}
	}
}
echo "OK";
