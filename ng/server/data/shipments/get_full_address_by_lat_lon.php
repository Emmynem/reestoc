<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");
  require '../../config/connect_data.php';

  class getPriceClass {
    public $engineMessage = 0;
    public $address;
  }

  $data = json_decode(file_get_contents("php://input"), true);

  if ($connected) {

      try {

        // $latitude = $data['latitude'];
        // $longitude = $data['longitude'];

        $latitude = $_GET['latitude'];
        $longitude = $_GET['longitude'];

        // $url = 'https://blockchain.info/ticker';
        $url = 'https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat='.$latitude.'&lon='.$longitude.'';
        $curl = curl_init($url);

        // Set the CURLOPT_RETURNTRANSFER option to true
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // Set the CURLOPT_POST option to true for POST request
        curl_setopt($curl, CURLOPT_POST, true);
        // Set the request data as JSON using json_encode function
        curl_setopt($curl, CURLOPT_POSTFIELDS,  json_encode($data));

        $result = curl_exec($curl);

        $r_rate = curl_exec($curl);

        // $new_rate = (object) $r_rate;

        // $new_rate_2 = $new_rate->{"scalar"};
        //
        // $new_rate_3 = json_decode($new_rate_2);

        // $new_rate_4 = $new_rate_3->{"USD"};
        //
        // $new_rate_5 = $new_rate_4->{"last"};

        if ($result) {
          $returnvalue = new getPriceClass();
          $returnvalue->engineMessage = 1;
          $returnvalue->address = $r_rate;
        }

    } catch (Exception $e) {
      // header("HTTP/1.0 500 Unable to pull ticker results");
      echo $e->getMessage();
    }

    echo json_encode($returnvalue);

  }

?>
