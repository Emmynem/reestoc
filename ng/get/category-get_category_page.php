<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");

  ini_set('display_errors', 1);

  include_once '../server/config/connect.php';
  include_once "../server/objects/products_obj.php";
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

  $product = new Products($db);
  $data = json_decode(file_get_contents("php://input"), true);

  $category_unique_id = isset($_POST['category_unique_id']) ? $_POST['category_unique_id'] : $data['category_unique_id'];
  $stripped = isset($_POST['stripped']) ? $_POST['stripped'] : $data['stripped'];

  // Returns all category products (long version, with sub arrays) in json_encoded array with product_images array
  $result = $product->get_category_page_for_users($category_unique_id, $stripped);

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
