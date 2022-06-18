<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");
  require '../../config/connect_data.php';
  include_once "../../objects/functions.php";

  class genericClass {
    public $engineMessage = 0;
    public $engineError = 0;
    public $engineErrorMessage;
    public $resultData;
    public $filteredData;
  }

  $data = json_decode(file_get_contents("php://input"), true);

  $functions = new Functions();

  if ($connected) {

    try {
      $conn->beginTransaction();

      $date_added = $functions->today;
      $active = $functions->active;
      $null = $functions->null;
      $not_allowed_values = $functions->not_allowed_values;

      $user_unique_id = isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : $data['user_unique_id'];
      $product_unique_id = isset($_GET['product_unique_id']) ? $_GET['product_unique_id'] : $data['product_unique_id'];
      $name = isset($_GET['name']) ? $_GET['name'] : $data['name'];
      $size = isset($_GET['size']) ? $_GET['size'] : $data['size'];
      $description = isset($_GET['description']) ? $_GET['description'] : $data['description'];
      $stock = isset($_GET['stock']) ? $_GET['stock'] : $data['stock'];
      $price = isset($_GET['price']) ? $_GET['price'] : $data['price'];
      $sales_price = isset($_GET['sales_price']) ? $_GET['sales_price'] : $data['sales_price'];

      $sqlSearchUser = "SELECT unique_id FROM management WHERE unique_id=:unique_id AND status=:status";
      $querySearchUser = $conn->prepare($sqlSearchUser);
      $querySearchUser->bindParam(":unique_id", $user_unique_id);
      $querySearchUser->bindParam(":status", $active);
      $querySearchUser->execute();

      if ($querySearchUser->rowCount() > 0) {

        $validation = $functions->sub_product_validation($product_unique_id, $name, $size, $description, $stock, $price);

        if ($validation["error"] == true) {
          $returnvalue = new genericClass();
          $returnvalue->engineError = 2;
          $returnvalue->engineErrorMessage = $validation["message"];
        }
        else {

          $start = $functions->start;
          $zero = $functions->zero;
          $full_strip = in_array($size,$not_allowed_values) ? $name : $name." ".$size;
          $stripped = $functions->strip_text($full_strip);

          $sql2 = "SELECT name FROM sub_products WHERE product_unique_id=:product_unique_id AND (name=:name OR stripped=:stripped) AND (size=:size OR size IS NULL) AND status=:status";
          $query2 = $conn->prepare($sql2);
          $query2->bindParam(":product_unique_id", $product_unique_id);
          $query2->bindParam(":name", $name);
          $query2->bindParam(":stripped", $stripped);
          $query2->bindParam(":size", $size);
          $query2->bindParam(":status", $active);
          $query2->execute();

          if ($query2->rowCount() > 0) {
            $returnvalue = new genericClass();
            $returnvalue->engineError = 2;
            $returnvalue->engineErrorMessage = "Sub Product already exists";
          }
          else if ($sales_price >= $price) {
            $returnvalue = new genericClass();
            $returnvalue->engineError = 2;
            $returnvalue->engineErrorMessage = "Sales price must me less than Price";
          }
          else {
            $unique_id = $functions->random_str(20);

            $size_alt = in_array($size,$not_allowed_values) ? $null : $size;

            $sql = "INSERT INTO sub_products (unique_id, user_unique_id, edit_user_unique_id, product_unique_id, name, size, stripped,
            description, stock, stock_remaining, price, sales_price, favorites, added_date, last_modified, status)
            VALUES (:unique_id, :user_unique_id, :edit_user_unique_id, :product_unique_id, :name, :size, :stripped,
            :description, :stock, :stock_remaining, :price, :sales_price, :favorites, :added_date, :last_modified, :status)";
            $query = $conn->prepare($sql);
            $query->bindParam(":unique_id", $unique_id);
            $query->bindParam(":user_unique_id", $user_unique_id);
            $query->bindParam(":edit_user_unique_id", $user_unique_id);
            $query->bindParam(":product_unique_id", $product_unique_id);
            $query->bindParam(":name", $name);
            $query->bindParam(":size", $size_alt);
            $query->bindParam(":stripped", $stripped);
            $query->bindParam(":description", $description);
            $query->bindParam(":stock", $stock);
            $query->bindParam(":stock_remaining", $stock);
            $query->bindParam(":price", $price);
            $query->bindParam(":sales_price", $sales_price);
            $query->bindParam(":favorites", $start);
            $query->bindParam(":added_date", $date_added);
            $query->bindParam(":last_modified", $date_added);
            $query->bindParam(":status", $active);
            $query->execute();

            if ($query->rowCount() > 0) {
              $returnvalue = new genericClass();
              $returnvalue->engineMessage = 1;
            }
            else{
              $returnvalue = new genericClass();
              $returnvalue->engineError = 2;
              $returnvalue->engineErrorMessage = "Not inserted (new sub product)";
            }
          }

        }

      }
      else {
        $returnvalue = new genericClass();
        $returnvalue->engineError = 2;
        $returnvalue->engineErrorMessage = "Management user not found";
      }

      $conn->commit();
    } catch (PDOException $e) {
      $conn->rollback();
      throw $e;
    }

  }
  else {
    $returnvalue = new genericClass();
    $returnvalue->engineError = 3;
    $returnvalue->engineErrorMessage = "No connection";
  }

  echo json_encode($returnvalue);

?>
