<?php
$json_string = file_get_contents("http://api.wunderground.com/api/aded58d2c14cec26/forecast10day/q/TH/Bangkok.json");
  $parsed_json = json_decode($json_string);
  $data = $parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'};
  print_r($data);
  //$local_date = $parsed_json->{'forecast'}->{'txt_forecast'};
//foreach($data as $c) {
  //echo "Current temperature in " . $c['period'] . " will be "  . "<br />" ;
  //$location = $parsed_json->{'location'}->{'city'};
  //$temp_c = $parsed_json->{'current_observation'}->{'temp_c'};
  //echo "Current temperature in $forecastday['title'][";
//};
