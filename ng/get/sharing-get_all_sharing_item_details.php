<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");

  ini_set('display_errors', 1);

  include_once '../server/config/connect.php';
  include_once "../server/objects/sharing_obj.php";
  include_once "../server/objects/functions.php";

  class genericClass {
    public $engineMessage = 0;
    public $engineError = 0;
    public $engineErrorMessage;
    public $sharing_details;
    // public $sharing_item_locations;
    public $sharing_item_pickup_locations;
    public $sharing_item_participants;
    // public $sharing_item_history;
  }

  $database = new Database();
  $db = $database->getConnection();
  $functions = new Functions();
  $data = json_decode(file_get_contents("php://input"), true);

  $sharing = new Sharing($db);

  $unique_id_alt = null;
  $stripped = isset($_GET['stripped']) ? $_GET['stripped'] : $data["stripped"];

  // Returns sharing item details in json_encoded array
  $result_sharing_details = $sharing->get_all_sharing_details_for_users($unique_id_alt, $stripped);

  if ($functions->isJson($result_sharing_details)) {
    $returnvalue = new genericClass();
    $returnvalue->engineMessage = 1;
    $returnvalue->sharing_details = $result_sharing_details[0] == null ? null : $result_sharing_details[0];
    // $returnvalue->sharing_item_locations = $result_sharing_details[1] == null ? null : $result_sharing_details[1];
    $returnvalue->sharing_item_pickup_locations = $result_sharing_details[1] == null ? null : $result_sharing_details[1];
    $returnvalue->sharing_item_participants = $result_sharing_details[2] == null ? null : $result_sharing_details[2];
    // $returnvalue->sharing_item_history = $result_sharing_details[4] == null ? null : $result_sharing_details[4];
  }
  else if (array_key_exists("success",$result_sharing_details)) {
    if ($result_sharing_details["success"] == true) {
      $returnvalue = new genericClass();
      $returnvalue->engineMessage = 1;
      $returnvalue->sharing_details = null;
    }
  }
  else if (array_key_exists("error",$result_sharing_details)) {
    if ($result_sharing_details["error"] == true) {
      $returnvalue = new genericClass();
      $returnvalue->engineError = 2;
      $returnvalue->sharing_details = null;
    }
    else {
      $returnvalue = new genericClass();
      $returnvalue->engineError = 2;
      $returnvalue->sharing_details = null;
    }
  }
  else {
    $returnvalue = new genericClass();
    $returnvalue->engineMessage = 1;
    $returnvalue->sharing_details = $result_sharing_details[0] == null ? null : $result_sharing_details[0];
    // $returnvalue->sharing_item_locations = $result_sharing_details[1] == null ? null : $result_sharing_details[1];
    $returnvalue->sharing_item_pickup_locations = $result_sharing_details[1] == null ? null : $result_sharing_details[1];
    $returnvalue->sharing_item_participants = $result_sharing_details[2] == null ? null : $result_sharing_details[2];
    // $returnvalue->sharing_item_history = $result_sharing_details[4] == null ? null : $result_sharing_details[4];
  }

  echo json_encode($returnvalue);

?>
