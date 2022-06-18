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
      $not_active = $functions->not_active;
      $not_allowed_values = $functions->not_allowed_values;

      $name = isset($_GET['name']) ? $_GET['name'] : $data['name'];
      $details = isset($_GET['details']) ? $_GET['details'] : $data['details'];
      $fullname = isset($_GET['fullname']) ? $_GET['fullname'] : $data['fullname'];
      $email = isset($_GET['email']) ? $_GET['email'] : $data['email'];
      $phone_number = isset($_GET['phone_number']) ? $_GET['phone_number'] : $data['phone_number'];
      $role = 1;
      $access = 1;

      $validation = $functions->store_validation($name, $details, $fullname, $email, $phone_number, $role, $access);

      if ($validation["error"] == true) {
        $returnvalue = new genericClass();
        $returnvalue->engineError = 2;
        $returnvalue->engineErrorMessage = $validation["message"];
      }
      else {

        $stripped = $functions->strip_text($name);

        $sql2 = "SELECT name FROM stores WHERE name=:name OR stripped=:stripped AND status=:status";
        $query2 = $conn->prepare($sql2);
        $query2->bindParam(":name", $name);
        $query2->bindParam(":stripped", $stripped);
        $query2->bindParam(":status", $active);
        $query2->execute();

        $sql3 = "SELECT fullname FROM store_users WHERE (email=:email OR phone_number=:phone_number) AND role=:role AND status=:status";
        $query3 = $conn->prepare($sql3);
        $query3->bindParam(":email", $email);
        $query3->bindParam(":phone_number", $phone_number);
        $query3->bindParam(":role", $role);
        $query3->bindParam(":status", $active);
        $query3->execute();

        if ($query2->rowCount() > 0) {
          $returnvalue = new genericClass();
          $returnvalue->engineError = 2;
          $returnvalue->engineErrorMessage = "Store already exists";
        }
        else if ($query3->rowCount() > 0) {
          $returnvalue = new genericClass();
          $returnvalue->engineError = 2;
          $returnvalue->engineErrorMessage = "Store Owner already exists";
        }
        else {

          $store_users_unique_id = $functions->random_str(20);

          $the_email = in_array($email, $not_allowed_values) ? null : $email;
          $the_phone_number = in_array($phone_number, $not_allowed_values) ? null : $phone_number;

          $sql = "INSERT INTO store_users (unique_id, edit_user_unique_id, fullname, email, phone_number, role, added_date, last_modified, access, status)
          VALUES (:unique_id, :edit_user_unique_id, :fullname, :email, :phone_number, :role, :added_date, :last_modified, :access, :status)";
          $query = $conn->prepare($sql);
          $query->bindParam(":unique_id", $store_users_unique_id);
          $query->bindParam(":edit_user_unique_id", $store_users_unique_id);
          $query->bindParam(":fullname", $fullname);
          $query->bindParam(":email", $the_email);
          $query->bindParam(":phone_number", $the_phone_number);
          $query->bindParam(":role", $role);
          $query->bindParam(":added_date", $date_added);
          $query->bindParam(":last_modified", $date_added);
          $query->bindParam(":access", $active);
          $query->bindParam(":status", $active);
          $query->execute();

          if ($query->rowCount() > 0) {

            $unique_id = $functions->random_str(20);
            $stores_unique_id = $functions->random_str(20);

            $sql4 = "INSERT INTO stores (unique_id, user_unique_id, name, stripped, details, added_date, last_modified, access, status)
            VALUES (:unique_id, :user_unique_id, :name, :stripped, :details, :added_date, :last_modified, :access, :status)";
            $query4 = $conn->prepare($sql4);
            $query4->bindParam(":unique_id", $unique_id);
            $query4->bindParam(":user_unique_id", $store_users_unique_id);
            $query4->bindParam(":name", $name);
            $query4->bindParam(":stripped", $stripped);
            $query4->bindParam(":details", $details);
            $query4->bindParam(":added_date", $date_added);
            $query4->bindParam(":last_modified", $date_added);
            $query4->bindParam(":access", $not_active);
            $query4->bindParam(":status", $active);
            $query4->execute();

            if ($query4->rowCount() > 0) {
              $returnvalue = new genericClass();
              $returnvalue->engineMessage = 1;
            }
            else{
              $returnvalue = new genericClass();
              $returnvalue->engineError = 2;
              $returnvalue->engineErrorMessage = "Not inserted (new store)";
            }

          }
          else{
            $returnvalue = new genericClass();
            $returnvalue->engineError = 2;
            $returnvalue->engineErrorMessage = "Not inserted (new store user)";
          }

        }

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
