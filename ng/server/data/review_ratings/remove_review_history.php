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
      $yes = $functions->yes;
      $no = $functions->no;

      $user_unique_id = isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : $data['user_unique_id'];
      $review_unique_id = isset($_GET['review_unique_id']) ? $_GET['review_unique_id'] : $data['review_unique_id'];
      $product_unique_id = isset($_GET['product_unique_id']) ? $_GET['product_unique_id'] : $data['product_unique_id'];

      $sqlSearchUser = "SELECT unique_id FROM users WHERE unique_id=:unique_id AND status=:status";
      $querySearchUser = $conn->prepare($sqlSearchUser);
      $querySearchUser->bindParam(":unique_id", $user_unique_id);
      $querySearchUser->bindParam(":status", $active);
      $querySearchUser->execute();

      if ($querySearchUser->rowCount() > 0) {

        $sql2 = "SELECT rating FROM review_history WHERE unique_id=:unique_id AND user_unique_id=:user_unique_id AND product_unique_id=:product_unique_id AND status=:status";
        $query2 = $conn->prepare($sql2);
        $query2->bindParam(":unique_id", $review_unique_id);
        $query2->bindParam(":user_unique_id", $user_unique_id);
        $query2->bindParam(":product_unique_id", $product_unique_id);
        $query2->bindParam(":status", $active);
        $query2->execute();

        $result = $query2->fetchAll();

        if ($query2->rowCount() > 0) {
          foreach ($result as $key => $value) {

            $current_review_history_unique_id = $review_unique_id;
            $current_review_history_product_unique_id = $product_unique_id;
            $current_review_history_rating = $value['rating'];

            $sql4 = "SELECT yes_rating, no_rating FROM review_ratings WHERE unique_id=:unique_id AND product_unique_id=:product_unique_id AND status=:status";
            $query4 = $conn->prepare($sql4);
            $query4->bindParam(":unique_id", $current_review_history_unique_id);
            $query4->bindParam(":product_unique_id", $current_review_history_product_unique_id);
            $query4->bindParam(":status", $active);
            $query4->execute();

            $the_review_ratings = $query4->fetch();

            if ($query4->rowCount() > 0) {

              $the_total_yes_ratings = (int)$the_review_ratings[0];
              $the_total_no_ratings = (int)$the_review_ratings[1];

              $new_total_yes_ratings = $the_total_yes_ratings - 1;
              $new_total_no_ratings = $the_total_no_ratings - 1;

              if (strtolower($current_review_history_rating) == $yes) {
                $sql5 = "UPDATE review_ratings SET yes_rating=:yes_rating, last_modified=:last_modified WHERE unique_id=:unique_id AND product_unique_id=:product_unique_id";
                $query5 = $conn->prepare($sql5);
                $query5->bindParam(":yes_rating", $new_total_yes_ratings);
                $query5->bindParam(":unique_id", $current_review_history_unique_id);
                $query5->bindParam(":product_unique_id", $current_review_history_product_unique_id);
                $query5->bindParam(":last_modified", $date_added);
                $query5->execute();

                if ($query5->rowCount() > 0) {
                  $sql = "DELETE FROM review_history WHERE unique_id=:unique_id AND user_unique_id=:user_unique_id AND product_unique_id=:product_unique_id";
                  $query = $conn->prepare($sql);
                  $query->bindParam(":user_unique_id", $user_unique_id);
                  $query->bindParam(":unique_id", $current_review_history_unique_id);
                  $query->bindParam(":product_unique_id", $current_review_history_product_unique_id);
                  $query->execute();

                  if ($query->rowCount() > 0) {
                    $returnvalue = new genericClass();
                    $returnvalue->engineMessage = 1;
                  }
                  else{
                    $returnvalue = new genericClass();
                    $returnvalue->engineError = 2;
                    $returnvalue->engineErrorMessage = "Not removed (review history) (".$key.")";
                  }
                }
                else {
                  $returnvalue = new genericClass();
                  $returnvalue->engineError = 2;
                  $returnvalue->engineErrorMessage = "Not edited (review ratings) (".$key.")";
                }
              }
              else {
                $sql5 = "UPDATE review_ratings SET no_rating=:no_rating, last_modified=:last_modified WHERE unique_id=:unique_id AND product_unique_id=:product_unique_id";
                $query5 = $conn->prepare($sql5);
                $query5->bindParam(":no_rating", $new_total_no_ratings);
                $query5->bindParam(":unique_id", $current_review_history_unique_id);
                $query5->bindParam(":product_unique_id", $current_review_history_product_unique_id);
                $query5->bindParam(":last_modified", $date_added);
                $query5->execute();

                if ($query5->rowCount() > 0) {

                  $sql = "DELETE FROM review_history WHERE unique_id=:unique_id AND user_unique_id=:user_unique_id AND product_unique_id=:product_unique_id";
                  $query = $conn->prepare($sql);
                  $query->bindParam(":user_unique_id", $user_unique_id);
                  $query->bindParam(":unique_id", $current_review_history_unique_id);
                  $query->bindParam(":product_unique_id", $current_review_history_product_unique_id);
                  $query->execute();

                  if ($query->rowCount() > 0) {
                    $returnvalue = new genericClass();
                    $returnvalue->engineMessage = 1;
                  }
                  else{
                    $returnvalue = new genericClass();
                    $returnvalue->engineError = 2;
                    $returnvalue->engineErrorMessage = "Not removed (review history) (".$key.")";
                  }
                }
                else {
                  $returnvalue = new genericClass();
                  $returnvalue->engineError = 2;
                  $returnvalue->engineErrorMessage = "Not edited (review ratings) (".$key.")";
                }
              }

            }
            else {
              $returnvalue = new genericClass();
              $returnvalue->engineError = 2;
              $returnvalue->engineErrorMessage = "Users review rating not found";
            }

          }
        }
        else {
          $returnvalue = new genericClass();
          $returnvalue->engineError = 2;
          $returnvalue->engineErrorMessage = "Users review histories not found";
        }

      }
      else {
        $returnvalue = new genericClass();
        $returnvalue->engineError = 2;
        $returnvalue->engineErrorMessage = "User not found";
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
