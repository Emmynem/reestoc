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
      $yes = $functions->yes;
      $no = $functions->no;

      $user_unique_id = isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : $data['user_unique_id'];
      $product_unique_id = isset($_GET['product_unique_id']) ? $_GET['product_unique_id'] : $data['product_unique_id'];
      $message = isset($_GET['message']) ? $_GET['message'] : $data['message'];
      $rating = isset($_GET['rating']) ? $_GET['rating'] : $data['rating'];

      $sqlSearchUser = "SELECT unique_id FROM users WHERE unique_id=:unique_id AND status=:status";
      $querySearchUser = $conn->prepare($sqlSearchUser);
      $querySearchUser->bindParam(":unique_id", $user_unique_id);
      $querySearchUser->bindParam(":status", $active);
      $querySearchUser->execute();

      if ($querySearchUser->rowCount() > 0) {

        $validation = $functions->review_validation($message);

        if ($validation["error"] == true) {
          $returnvalue = new genericClass();
          $returnvalue->engineError = 2;
          $returnvalue->engineErrorMessage = $validation["message"];
        }
        else {

          $sql2 = "SELECT unique_id, rating FROM review_history WHERE user_unique_id=:user_unique_id AND product_unique_id=:product_unique_id AND status=:status";
          $query2 = $conn->prepare($sql2);
          $query2->bindParam(":user_unique_id", $user_unique_id);
          $query2->bindParam(":product_unique_id", $product_unique_id);
          $query2->bindParam(":status", $active);
          $query2->execute();

          if ($query2->rowCount() > 0) {
            $returnvalue = new genericClass();
            $returnvalue->engineError = 2;
            $returnvalue->engineErrorMessage = "User review already exists";
          }
          else {

            $unique_id = $functions->random_str(20);

            $sql = "INSERT INTO reviews (unique_id, user_unique_id, product_unique_id, message, added_date, last_modified, status)
            VALUES (:unique_id, :user_unique_id, :product_unique_id, :message, :added_date, :last_modified, :status)";
            $query = $conn->prepare($sql);
            $query->bindParam(":unique_id", $unique_id);
            $query->bindParam(":user_unique_id", $user_unique_id);
            $query->bindParam(":product_unique_id", $product_unique_id);
            $query->bindParam(":message", $message);
            $query->bindParam(":added_date", $date_added);
            $query->bindParam(":last_modified", $date_added);
            $query->bindParam(":status", $active);
            $query->execute();

            if ($query->rowCount() > 0) {

              $validation = $functions->review_ratings_validation($rating);

              if ($validation["error"] == true) {
                $returnvalue = new genericClass();
                $returnvalue->engineError = 2;
                $returnvalue->engineErrorMessage = $validation["message"];
              }
              else {

                if (strtolower($rating) == $yes) {
                  $new_yes_ratings = 1;
                  $new_no_ratings = 0;

                  $sql3 = "INSERT INTO review_ratings (unique_id, user_unique_id, product_unique_id, yes_rating, no_rating, added_date, last_modified, status)
                  VALUES (:unique_id, :user_unique_id, :product_unique_id, :yes_rating, :no_rating, :added_date, :last_modified, :status)";
                  $query3 = $conn->prepare($sql3);
                  $query3->bindParam(":unique_id", $unique_id);
                  $query3->bindParam(":user_unique_id", $user_unique_id);
                  $query3->bindParam(":product_unique_id", $product_unique_id);
                  $query3->bindParam(":yes_rating", $new_yes_ratings);
                  $query3->bindParam(":no_rating", $new_no_ratings);
                  $query3->bindParam(":added_date", $date_added);
                  $query3->bindParam(":last_modified", $date_added);
                  $query3->bindParam(":status", $active);
                  $query3->execute();

                  if ($query3->rowCount() > 0) {

                    $sql4 = "INSERT INTO review_history (unique_id, user_unique_id, product_unique_id, rating, added_date, last_modified, status)
                    VALUES (:unique_id, :user_unique_id, :product_unique_id, :rating, :added_date, :last_modified, :status)";
                    $query4 = $conn->prepare($sql4);
                    $query4->bindParam(":unique_id", $unique_id);
                    $query4->bindParam(":user_unique_id", $user_unique_id);
                    $query4->bindParam(":product_unique_id", $product_unique_id);
                    $query4->bindParam(":rating", $rating);
                    $query4->bindParam(":added_date", $date_added);
                    $query4->bindParam(":last_modified", $date_added);
                    $query4->bindParam(":status", $active);
                    $query4->execute();

                    if ($query4->rowCount() > 0) {
                      $returnvalue = new genericClass();
                      $returnvalue->engineMessage = 1;
                    }
                    else {
                      $returnvalue = new genericClass();
                      $returnvalue->engineError = 2;
                      $returnvalue->engineErrorMessage = "Not inserted (user rating history)";
                    }

                  }
                  else {
                    $returnvalue = new genericClass();
                    $returnvalue->engineError = 2;
                    $returnvalue->engineErrorMessage = "Not inserted (user ratings)";
                  }

                }
                else {
                  $new_yes_ratings = 0;
                  $new_no_ratings = 1;

                  $sql3 = "INSERT INTO review_ratings (unique_id, user_unique_id, product_unique_id, yes_rating, no_rating, added_date, last_modified, status)
                  VALUES (:unique_id, :user_unique_id, :product_unique_id, :yes_rating, :no_rating, :added_date, :last_modified, :status)";
                  $query3 = $conn->prepare($sql3);
                  $query3->bindParam(":unique_id", $unique_id);
                  $query3->bindParam(":user_unique_id", $user_unique_id);
                  $query3->bindParam(":product_unique_id", $product_unique_id);
                  $query3->bindParam(":yes_rating", $new_yes_ratings);
                  $query3->bindParam(":no_rating", $new_no_ratings);
                  $query3->bindParam(":added_date", $date_added);
                  $query3->bindParam(":last_modified", $date_added);
                  $query3->bindParam(":status", $active);
                  $query3->execute();

                  if ($query3->rowCount() > 0) {

                    $sql4 = "INSERT INTO review_history (unique_id, user_unique_id, product_unique_id, rating, added_date, last_modified, status)
                    VALUES (:unique_id, :user_unique_id, :product_unique_id, :rating, :added_date, :last_modified, :status)";
                    $query4 = $conn->prepare($sql4);
                    $query4->bindParam(":unique_id", $unique_id);
                    $query4->bindParam(":user_unique_id", $user_unique_id);
                    $query4->bindParam(":product_unique_id", $product_unique_id);
                    $query4->bindParam(":rating", $rating);
                    $query4->bindParam(":added_date", $date_added);
                    $query4->bindParam(":last_modified", $date_added);
                    $query4->bindParam(":status", $active);
                    $query4->execute();

                    if ($query4->rowCount() > 0) {
                      $returnvalue = new genericClass();
                      $returnvalue->engineMessage = 1;
                    }
                    else {
                      $returnvalue = new genericClass();
                      $returnvalue->engineError = 2;
                      $returnvalue->engineErrorMessage = "Not inserted (user rating history)";
                    }

                  }
                  else {
                    $returnvalue = new genericClass();
                    $returnvalue->engineError = 2;
                    $returnvalue->engineErrorMessage = "Not inserted (user ratings)";
                  }

                }

              }

            }
            else{
              $returnvalue = new genericClass();
              $returnvalue->engineError = 2;
              $returnvalue->engineErrorMessage = "Not inserted (new user review)";
            }

          }

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
