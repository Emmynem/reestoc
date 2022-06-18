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
      $completion = $functions->processing;

      $management_user_unique_id = isset($_GET['management_user_unique_id']) ? $_GET['management_user_unique_id'] : $data['management_user_unique_id'];
      $sub_product_unique_ids = isset($_GET['sub_product_unique_ids']) ? $_GET['sub_product_unique_ids'] : $data['sub_product_unique_ids'];
      $code = isset($_GET['code']) ? $_GET['code'] : $data['code'];
      $name = isset($_GET['name']) ? $_GET['name'] : $data['name'];
      $percentage = isset($_GET['percentage']) ? $_GET['percentage'] : $data['percentage'];
      $total_count = isset($_GET['total_coupons']) ? $_GET['total_coupons'] : $data['total_coupons'];
      $expiry_date = isset($_GET['expiry_date']) ? $_GET['expiry_date'] : $data['expiry_date'];

      $sqlSearchUser = "SELECT unique_id FROM management WHERE unique_id=:unique_id AND status=:status";
      $querySearchUser = $conn->prepare($sqlSearchUser);
      $querySearchUser->bindParam(":unique_id", $management_user_unique_id);
      $querySearchUser->bindParam(":status", $active);
      $querySearchUser->execute();

      if ($querySearchUser->rowCount() > 0) {

        $validation = $functions->product_coupon_validation($code, $name, $percentage, $total_count, $expiry_date);

        if ($validation["error"] == true) {
          $returnvalue = new genericClass();
          $returnvalue->engineError = 2;
          $returnvalue->engineErrorMessage = $validation["message"];
        }
        else {

          if (!is_array($sub_product_unique_ids)) {
            $returnvalue = new genericClass();
            $returnvalue->engineError = 2;
            $returnvalue->engineErrorMessage = "Sub Product IDs is not an array";
          }
          else if (in_array($sub_product_unique_ids,$not_allowed_values)) {
            $returnvalue = new genericClass();
            $returnvalue->engineError = 2;
            $returnvalue->engineErrorMessage = "Sub Product IDs is required";
          }
          else {

            $stripped_code = $functions->strip_text($code);

            $the_code = strtoupper($stripped_code);

            foreach ($sub_product_unique_ids as $key => $sub_product_unique_id){

              $sql3 = "SELECT unique_id FROM sub_products WHERE unique_id=:unique_id AND status=:status";
              $query3 = $conn->prepare($sql3);
              $query3->bindParam(":unique_id", $sub_product_unique_id);
              $query3->bindParam(":status", $active);
              $query3->execute();

              if ($query3->rowCount() > 0) {

                $sql2 = "SELECT name FROM coupons WHERE sub_product_unique_id=:sub_product_unique_id AND expiry_date >:today AND status=:status";
                $query2 = $conn->prepare($sql2);
                $query2->bindParam(":sub_product_unique_id", $sub_product_unique_id);
                $query2->bindParam(":today", $date_added);
                $query2->bindParam(":status", $active);
                $query2->execute();

                if ($query2->rowCount() > 0) {
                  $returnvalue = new genericClass();
                  $returnvalue->engineError = 2;
                  $returnvalue->engineErrorMessage = "Current sub product coupon hasn't expired yet";
                }
                else {

                  $unique_id = $functions->random_str(20);

                  $sql = "INSERT INTO coupons (unique_id, user_unique_id, sub_product_unique_id, mini_category_unique_id, code, name, percentage, total_count, current_count, completion, expiry_date, added_date, last_modified, status)
                  VALUES (:unique_id, :user_unique_id, :sub_product_unique_id, :mini_category_unique_id, :code, :name, :percentage, :total_count, :current_count, :completion, :expiry_date, :added_date, :last_modified, :status)";
                  $query = $conn->prepare($sql);
                  $query->bindParam(":unique_id", $unique_id);
                  $query->bindParam(":user_unique_id", $null);
                  $query->bindParam(":sub_product_unique_id", $sub_product_unique_id);
                  $query->bindParam(":mini_category_unique_id", $null);
                  $query->bindParam(":code", $the_code);
                  $query->bindParam(":name", $name);
                  $query->bindParam(":percentage", $percentage);
                  $query->bindParam(":total_count", $total_count);
                  $query->bindParam(":current_count", $total_count);
                  $query->bindParam(":completion", $completion);
                  $query->bindParam(":expiry_date", $expiry_date);
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
                    $returnvalue->engineErrorMessage = "Not inserted (new coupon)";
                  }
                }

              }
              else {
                $returnvalue = new genericClass();
                $returnvalue->engineError = 2;
                $returnvalue->engineErrorMessage = "Not found (sub product)";
              }

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
