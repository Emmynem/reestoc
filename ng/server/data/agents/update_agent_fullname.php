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

      $fullname = isset($_GET['fullname']) ? $_GET['fullname'] : $data['fullname'];
      $email = isset($_GET['email']) ? $_GET['email'] : $data['email'];
      $phone_number = isset($_GET['phone_number']) ? $_GET['phone_number'] : $data['phone_number'];

      $validation = $functions->update_management_user_fullname_validation($fullname, $email, $phone_number);

      if ($validation["error"] == true) {
        $returnvalue = new genericClass();
        $returnvalue->engineError = 2;
        $returnvalue->engineErrorMessage = $validation["message"];
      }
      else {

        $sql2 = "SELECT unique_id FROM agents WHERE (email=:email OR phone_number=:phone_number) AND status=:status";
        $query2 = $conn->prepare($sql2);
        $query2->bindParam(":email", $email);
        $query2->bindParam(":phone_number", $phone_number);
        $query2->bindParam(":status", $active);
        $query2->execute();

        if ($query2->rowCount() > 0) {

          $sql = "UPDATE agents SET fullname=:fullname, last_modified=:last_modified WHERE email=:email OR phone_number=:phone_number";
          $query = $conn->prepare($sql);
          $query->bindParam(":fullname", $fullname);
          $query->bindParam(":email", $email);
          $query->bindParam(":phone_number", $phone_number);
          $query->bindParam(":last_modified", $date_added);
          $query->execute();

          if ($query->rowCount() > 0) {
            $returnvalue = new genericClass();
            $returnvalue->engineMessage = 1;
          }
          else {
            $returnvalue = new genericClass();
            $returnvalue->engineError = 2;
            $returnvalue->engineErrorMessage = "Not edited (agent fullname)";
          }

        }
        else {
          $returnvalue = new genericClass();
          $returnvalue->engineError = 2;
          $returnvalue->engineErrorMessage = "User not found";
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
    $returnvalue->engineError = 2;
    $returnvalue->engineErrorMessage = "No connection";
  }

  echo json_encode($returnvalue);

?>
