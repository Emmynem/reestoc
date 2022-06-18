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
      $anonymous = $functions->anonymous;

      $user_unique_id = isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : $data['user_unique_id'];
      $search_word = isset($_GET['search_word']) ? $_GET['search_word'] : $data['search_word'];
      $type = isset($_GET['type']) ? $_GET['type'] : $data['type'];

      $sqlSearchUser = "SELECT unique_id FROM users WHERE unique_id=:unique_id AND status=:status";
      $querySearchUser = $conn->prepare($sqlSearchUser);
      $querySearchUser->bindParam(":unique_id", $user_unique_id);
      $querySearchUser->bindParam(":status", $active);
      $querySearchUser->execute();

      if ($querySearchUser->rowCount() > 0) {

        $user_unique_id_alt = in_array($user_unique_id,$not_allowed_values) ? $anonymous : $user_unique_id;

        $unique_id = $functions->random_str(20);

        $sql = "INSERT INTO search_history (unique_id, user_unique_id, search, type, added_date, last_modified, status)
        VALUES (:unique_id, :user_unique_id, :search, :type, :added_date, :last_modified, :status)";
        $query = $conn->prepare($sql);
        $query->bindParam(":unique_id", $unique_id);
        $query->bindParam(":user_unique_id", $user_unique_id_alt);
        $query->bindParam(":search", $search_word);
        $query->bindParam(":type", $type);
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
          $returnvalue->engineErrorMessage = "Not inserted (new search history)";
        }

      }
      else {
        $user_unique_id_alt = in_array($user_unique_id,$not_allowed_values) ? $anonymous : $user_unique_id;

        $unique_id = $functions->random_str(20);

        $sql = "INSERT INTO search_history (unique_id, user_unique_id, search, added_date, last_modified, status)
        VALUES (:unique_id, :user_unique_id, :search, :added_date, :last_modified, :status)";
        $query = $conn->prepare($sql);
        $query->bindParam(":unique_id", $unique_id);
        $query->bindParam(":user_unique_id", $user_unique_id_alt);
        $query->bindParam(":search", $search_word);
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
          $returnvalue->engineErrorMessage = "Not inserted (new search history)";
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
