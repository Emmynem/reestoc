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
    public $resultData;
  }

  $database = new Database();
  $db = $database->getConnection();
  $functions = new Functions();
  $data = json_decode(file_get_contents("php://input"), true);

  $sharing = new Sharing($db);

  // Returns sharing items in json_encoded array
  $result = $sharing->get_all_sharing_for_users();

  if ($functions->isJson($result)) {
    $returnvalue = new genericClass();
    $returnvalue->engineMessage = 1;
    $returnvalue->resultData = $result;
  }
  else if (array_key_exists("success",$result)) {
    if ($result["success"] == true) {
      $returnvalue = new genericClass();
      $returnvalue->engineMessage = 1;
      $returnvalue->resultData = $result["message"];
    }
  }
  else if (array_key_exists("error",$result)) {
    if ($result["error"] == true) {
      $returnvalue = new genericClass();
      $returnvalue->engineError = 2;
      $returnvalue->engineErrorMessage = $result["message"];
    }
    else {
      $returnvalue = new genericClass();
      $returnvalue->engineError = 2;
      $returnvalue->engineErrorMessage = $result["message"];
    }
  }
  else {
    $returnvalue = new genericClass();
    $returnvalue->engineMessage = 1;
    $returnvalue->resultData = $result;
  }

  echo json_encode($returnvalue);

?>
