<?php

  $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : null;
  $allowed_domains = array("https://auth.reestoc.com", "https://dashboard.reestoc.com");
  foreach ($allowed_domains as $value) {if ($http_origin === $value) {header('Access-Control-Allow-Origin: ' . $http_origin);}}
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization, origin, Accept-Language, Range, X-Requested-With");
  header("Access-Control-Allow-Credentials: true");
  require '../../config/connect_data.php';
  include_once "../../objects/functions.php";

  ini_set('max_execution_time', 3600);

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

      // $data['mini_category_image_unique_id'] = isset($_GET['mini_category_image_unique_id']);
      // $data['user_unique_id'] = isset($_GET['user_unique_id']);
      // $data['mini_category_unique_id'] = isset($_GET['mini_category_unique_id']);

      $mini_category_image_unique_id = isset($_GET['mini_category_image_unique_id']) ? $_GET['mini_category_image_unique_id'] : $data['mini_category_image_unique_id'];
      $user_unique_id = isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : $data['user_unique_id'];
      $mini_category_unique_id = isset($_GET['mini_category_unique_id']) ? $_GET['mini_category_unique_id'] : $data['mini_category_unique_id'];

      $path_to_upload = "../../../../images/mini_category_images/";
      // $path_to_delete = $_SERVER['DOCUMENT_ROOT']."/images/mini_category_images"; // For online own
      $path_to_delete = $_SERVER['DOCUMENT_ROOT']."/cerotics_store/images/mini_category_images"; // For offline own
      $path_to_save = "https://www.reestoc.com/images/mini_category_images/";
      // $path_to_save = "https://www.reestock.com/images/mini_category_images/";

      $sqlSearchUser = "SELECT unique_id FROM management WHERE unique_id=:unique_id AND status=:status";
      $querySearchUser = $conn->prepare($sqlSearchUser);
      $querySearchUser->bindParam(":unique_id", $user_unique_id);
      $querySearchUser->bindParam(":status", $active);
      $querySearchUser->execute();

      if ($querySearchUser->rowCount() > 0) {

        $sql2 = "SELECT unique_id FROM mini_category WHERE unique_id=:unique_id AND status=:status";
        $query2 = $conn->prepare($sql2);
        $query2->bindParam(":unique_id", $mini_category_unique_id);
        $query2->bindParam(":status", $active);
        $query2->execute();

        if ($query2->rowCount() > 0) {

          $sql3 = "SELECT file FROM mini_category_images WHERE unique_id=:unique_id AND mini_category_unique_id=:mini_category_unique_id AND status=:status";
          $query3 = $conn->prepare($sql3);
          $query3->bindParam(":unique_id", $mini_category_image_unique_id);
          $query3->bindParam(":mini_category_unique_id", $mini_category_unique_id);
          $query3->bindParam(":status", $active);
          $query3->execute();

          if ($query3->rowCount() > 0) {

            $the_mini_category_image_details = $query3->fetch();
            $old_mini_category_image = $the_mini_category_image_details[0];

            $sql = "DELETE FROM mini_category_images WHERE unique_id=:unique_id AND mini_category_unique_id=:mini_category_unique_id";
            $query = $conn->prepare($sql);
            $query->bindParam(":unique_id", $mini_category_image_unique_id);
            $query->bindParam(":mini_category_unique_id", $mini_category_unique_id);
            $query->execute();

            if ($query->rowCount() > 0) {
              unlink($path_to_delete."/".$old_mini_category_image);
              $returnvalue = new genericClass();
              $returnvalue->engineMessage = 1;
              $returnvalue->resultData = 'Image Deleted Successfully';
            }
            else {
              $returnvalue = new genericClass();
              $returnvalue->engineError = 2;
              $returnvalue->engineErrorMessage = "Image Not Deleted";
            }


          }
          else {
            $returnvalue = new genericClass();
            $returnvalue->engineError = 2;
            $returnvalue->engineErrorMessage = "Old Mini category image not found";
          }

        }
        else {
          $returnvalue = new genericClass();
          $returnvalue->engineError = 2;
          $returnvalue->engineErrorMessage = "Mini category not found";
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
