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
      $not_allowed_values = $functions->not_allowed_values;

      $user_unique_id = isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : $data['user_unique_id'];
      $category_unique_id = isset($_GET['category_unique_id']) ? $_GET['category_unique_id'] : $data['category_unique_id'];
      $sub_category_unique_id = isset($_GET['sub_category_unique_id']) ? $_GET['sub_category_unique_id'] : $data['sub_category_unique_id'];
      $mini_category_unique_id = isset($_GET['mini_category_unique_id']) ? $_GET['mini_category_unique_id'] : $data['mini_category_unique_id'];
      $name = isset($_GET['name']) ? $_GET['name'] : $data['name'];
      $brand_unique_id = isset($_GET['brand_unique_id']) ? $_GET['brand_unique_id'] : $data['brand_unique_id'];
      $description = isset($_GET['description']) ? $_GET['description'] : $data['description'];

      $sqlSearchUser = "SELECT unique_id FROM management WHERE unique_id=:unique_id AND status=:status";
      $querySearchUser = $conn->prepare($sqlSearchUser);
      $querySearchUser->bindParam(":unique_id", $user_unique_id);
      $querySearchUser->bindParam(":status", $active);
      $querySearchUser->execute();

      if ($querySearchUser->rowCount() > 0) {

        $validation = $functions->product_validation($name, $description);

        if ($validation["error"] == true) {
          $returnvalue = new genericClass();
          $returnvalue->engineError = 2;
          $returnvalue->engineErrorMessage = $validation["message"];
        }
        else {

          $start = $functions->start;
          $zero = $functions->zero;
          $null = $functions->null;
          $stripped = $functions->strip_text($name);

          $sql2 = "SELECT name FROM products WHERE (mini_category_unique_id=:mini_category_unique_id OR mini_category_unique_id IS NULL) AND (sub_category_unique_id=:sub_category_unique_id OR sub_category_unique_id IS NULL) AND (category_unique_id=:category_unique_id OR category_unique_id IS NULL)
          AND (name=:name OR stripped=:stripped) AND (brand_unique_id=:brand_unique_id OR brand_unique_id IS NULL) AND status=:status";
          $query2 = $conn->prepare($sql2);
          $query2->bindParam(":mini_category_unique_id", $mini_category_unique_id);
          $query2->bindParam(":sub_category_unique_id", $sub_category_unique_id);
          $query2->bindParam(":category_unique_id", $category_unique_id);
          $query2->bindParam(":name", $name);
          $query2->bindParam(":stripped", $stripped);
          $query2->bindParam(":brand_unique_id", $brand_unique_id);
          $query2->bindParam(":status", $active);
          $query2->execute();

          if ($query2->rowCount() > 0) {
            $returnvalue = new genericClass();
            $returnvalue->engineError = 2;
            $returnvalue->engineErrorMessage = "Product already exists";
          }
          else {
            $unique_id = $functions->random_str(20);

            $brand_unique_id_alt = in_array($brand_unique_id,$not_allowed_values) ? $null : $brand_unique_id;

            $sql = "INSERT INTO products (unique_id, user_unique_id, edit_user_unique_id, mini_category_unique_id, sub_category_unique_id, category_unique_id, name, stripped, brand_unique_id,
            description, favorites, added_date, last_modified, status)
            VALUES (:unique_id, :user_unique_id, :edit_user_unique_id, :mini_category_unique_id, :sub_category_unique_id, :category_unique_id, :name, :stripped, :brand_unique_id,
            :description, :favorites, :added_date, :last_modified, :status)";
            $query = $conn->prepare($sql);
            $query->bindParam(":unique_id", $unique_id);
            $query->bindParam(":user_unique_id", $user_unique_id);
            $query->bindParam(":edit_user_unique_id", $user_unique_id);
            $query->bindParam(":mini_category_unique_id", $mini_category_unique_id);
            $query->bindParam(":sub_category_unique_id", $sub_category_unique_id);
            $query->bindParam(":category_unique_id", $category_unique_id);
            $query->bindParam(":name", $name);
            $query->bindParam(":stripped", $stripped);
            $query->bindParam(":brand_unique_id", $brand_unique_id_alt);
            $query->bindParam(":description", $description);
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
              $returnvalue->engineErrorMessage = "Not inserted (new product)";
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
