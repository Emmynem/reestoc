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

      $default_pickup_location_unique_id = isset($_GET['default_pickup_location_unique_id']) ? $_GET['default_pickup_location_unique_id'] : $data['default_pickup_location_unique_id'];
      $user_unique_id = isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : $data['user_unique_id'];
      $firstname = isset($_GET['firstname']) ? $_GET['firstname'] : $data['firstname'];
      $lastname = isset($_GET['lastname']) ? $_GET['lastname'] : $data['lastname'];
      $address = isset($_GET['address']) ? $_GET['address'] : $data['address'];
      $additional_information = isset($_GET['additional_information']) ? $_GET['additional_information'] : $data['additional_information'];
      $city = isset($_GET['city']) ? $_GET['city'] : $data['city'];
      $state = isset($_GET['state']) ? $_GET['state'] : $data['state'];
      $country = isset($_GET['country']) ? $_GET['country'] : $data['country'];

      $sqlSearchUser = "SELECT unique_id FROM management WHERE unique_id=:unique_id AND status=:status";
      $querySearchUser = $conn->prepare($sqlSearchUser);
      $querySearchUser->bindParam(":unique_id", $user_unique_id);
      $querySearchUser->bindParam(":status", $active);
      $querySearchUser->execute();

      if ($querySearchUser->rowCount() > 0) {

        $validation = $functions->address_validation($firstname, $lastname, $address, $additional_information, $city, $state, $country);

        if ($validation["error"] == true) {
          $returnvalue = new genericClass();
          $returnvalue->engineError = 2;
          $returnvalue->engineErrorMessage = $validation["message"];
        }
        else {

          $not_allowed_values = $functions->not_allowed_values;
          $null = $functions->null;
          $the_additional_information = in_array($additional_information,$not_allowed_values) ? $null : $additional_information;

          $sql2 = "SELECT address FROM default_pickup_locations WHERE unique_id=:unique_id AND user_unique_id=:user_unique_id AND status=:status";
          $query2 = $conn->prepare($sql2);
          $query2->bindParam(":unique_id", $default_pickup_location_unique_id);
          $query2->bindParam(":user_unique_id", $user_unique_id);
          $query2->bindParam(":status", $active);
          $query2->execute();

          if ($query2->rowCount() > 0) {

            $sql3 = "SELECT address FROM default_pickup_locations WHERE unique_id!=:unique_id AND firstname=:firstname AND lastname=:lastname AND city=:city AND state=:state AND country=:country AND status=:status";
            $query3 = $conn->prepare($sql3);
            $query3->bindParam(":unique_id", $default_pickup_location_unique_id);
            $query3->bindParam(":firstname", $firstname);
            $query3->bindParam(":lastname", $lastname);
            $query3->bindParam(":city", $city);
            $query3->bindParam(":state", $state);
            $query3->bindParam(":country", $country);
            $query3->bindParam(":status", $active);
            $query3->execute();

            if ($query3->rowCount() > 0) {
              $returnvalue = new genericClass();
              $returnvalue->engineError = 2;
              $returnvalue->engineErrorMessage = "Default Pickup Location already exists";
            }
            else {

              $sql = "UPDATE default_pickup_locations SET edit_user_unique_id=:edit_user_unique_id, firstname=:firstname, lastname=:lastname, address=:address, additional_information=:additional_information, city=:city, state=:state, country=:country,
              last_modified=:last_modified WHERE unique_id=:unique_id";
              $query = $conn->prepare($sql);
              $query->bindParam(":edit_user_unique_id", $user_unique_id);
              $query->bindParam(":unique_id", $default_pickup_location_unique_id);
              $query->bindParam(":firstname", $firstname);
              $query->bindParam(":lastname", $lastname);
              $query->bindParam(":address", $address);
              $query->bindParam(":additional_information", $the_additional_information);
              $query->bindParam(":city", $city);
              $query->bindParam(":state", $state);
              $query->bindParam(":country", $country);
              $query->bindParam(":last_modified", $date_added);
              $query->execute();

              if ($query->rowCount() > 0) {
                $returnvalue = new genericClass();
                $returnvalue->engineMessage = 1;
              }
              else {
                $returnvalue = new genericClass();
                $returnvalue->engineError = 2;
                $returnvalue->engineErrorMessage = "Not edited (default pickup location)";
              }

            }

          }
          else {
            $returnvalue = new genericClass();
            $returnvalue->engineError = 2;
            $returnvalue->engineErrorMessage = "Default Pickup Location not found";
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
    $returnvalue->engineError = 2;
    $returnvalue->engineErrorMessage = "No connection";
  }

  echo json_encode($returnvalue);

?>
