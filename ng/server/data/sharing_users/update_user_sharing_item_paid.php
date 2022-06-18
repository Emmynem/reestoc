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
      $completed = $functions->completed;

      $management_user_unique_id = isset($_GET['management_user_unique_id']) ? $_GET['management_user_unique_id'] : $data['management_user_unique_id'];
      $user_unique_id = isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : $data['user_unique_id'];
      $sharing_user_unique_id = isset($_GET['sharing_user_unique_id']) ? $_GET['sharing_user_unique_id'] : $data['sharing_user_unique_id'];
      $sharing_unique_id = isset($_GET['sharing_unique_id']) ? $_GET['sharing_unique_id'] : $data['sharing_unique_id'];
      $sharing_item_name = isset($_GET['sharing_item_name']) ? $_GET['sharing_item_name'] : $data['sharing_item_name'];
      $amount = isset($_GET['amount']) ? $_GET['amount'] : $data['amount'];

      $sqlSearchUser = "SELECT unique_id FROM management WHERE unique_id=:unique_id AND status=:status";
      $querySearchUser = $conn->prepare($sqlSearchUser);
      $querySearchUser->bindParam(":unique_id", $management_user_unique_id);
      $querySearchUser->bindParam(":status", $active);
      $querySearchUser->execute();

      if ($querySearchUser->rowCount() > 0) {

        $sql6 = "SELECT name, current_no_of_persons, total_no_of_persons FROM sharing_items WHERE unique_id=:unique_id AND status=:status";
        $query6 = $conn->prepare($sql6);
        $query6->bindParam(":unique_id", $sharing_unique_id);
        $query6->bindParam(":status", $active);
        $query6->execute();

        if ($query6->rowCount() > 0) {

          $the_sharing_item_details = $query6->fetch();
          $sharing_item_name = $the_sharing_item_details[0];
          $the_current_no_of_persons = (int)$the_sharing_item_details[1];
          $the_total_no_of_persons = (int)$the_sharing_item_details[2];

          if ($the_current_no_of_persons == $the_total_no_of_persons) {
            $returnvalue = new genericClass();
            $returnvalue->engineError = 2;
            $returnvalue->engineErrorMessage = "Participants occupied !";
          }
          else {

            $sql2 = "SELECT unique_id FROM sharing_users WHERE unique_id=:unique_id AND user_unique_id=:user_unique_id AND sharing_unique_id=:sharing_unique_id AND paid=:paid AND status=:status";
            $query2 = $conn->prepare($sql2);
            $query2->bindParam(":unique_id", $sharing_user_unique_id);
            $query2->bindParam(":user_unique_id", $user_unique_id);
            $query2->bindParam(":sharing_unique_id", $sharing_unique_id);
            $query2->bindParam(":paid", $not_active);
            $query2->bindParam(":status", $active);
            $query2->execute();

            if ($query2->rowCount() > 0) {

              $sql = "UPDATE sharing_users SET paid=:paid, last_modified=:last_modified WHERE unique_id=:unique_id AND user_unique_id=:user_unique_id AND sharing_unique_id=:sharing_unique_id";
              $query = $conn->prepare($sql);
              $query->bindParam(":paid", $active);
              $query->bindParam(":unique_id", $sharing_user_unique_id);
              $query->bindParam(":user_unique_id", $user_unique_id);
              $query->bindParam(":sharing_unique_id", $sharing_unique_id);
              $query->bindParam(":last_modified", $date_added);
              $query->execute();

              if ($query->rowCount() > 0) {

                $the_action = "Successfully paid for - ".$sharing_item_name." sharing item. Amount = ".$amount." naira.";

                $sharing_history_unique_id = $functions->random_str(20);

                $sql3 = "INSERT INTO sharing_history (unique_id, user_unique_id, sharing_unique_id, action, added_date, last_modified, status)
                VALUES (:unique_id, :user_unique_id, :sharing_unique_id, :action, :added_date, :last_modified, :status)";
                $query3 = $conn->prepare($sql3);
                $query3->bindParam(":unique_id", $sharing_history_unique_id);
                $query3->bindParam(":user_unique_id", $user_unique_id);
                $query3->bindParam(":sharing_unique_id", $sharing_unique_id);
                $query3->bindParam(":action", $the_action);
                $query3->bindParam(":added_date", $date_added);
                $query3->bindParam(":last_modified", $date_added);
                $query3->bindParam(":status", $active);
                $query3->execute();

                if ($query3->rowCount() > 0) {

                  $new_current_no_of_persons = $the_current_no_of_persons + 1;

                  if ($new_current_no_of_persons == $the_total_no_of_persons) {

                    $sql5 = "UPDATE sharing_items SET current_no_of_persons=:current_no_of_persons, completion=:completion, last_modified=:last_modified WHERE unique_id=:unique_id";
                    $query5 = $conn->prepare($sql5);
                    $query5->bindParam(":current_no_of_persons", $new_current_no_of_persons);
                    $query5->bindParam(":unique_id", $sharing_unique_id);
                    $query5->bindParam(":completion", $completed);
                    $query5->bindParam(":last_modified", $date_added);
                    $query5->execute();

                    if ($query5->rowCount() > 0) {
                      $returnvalue = new genericClass();
                      $returnvalue->engineMessage = 1;
                    }
                    else {
                      $returnvalue = new genericClass();
                      $returnvalue->engineError = 2;
                      $returnvalue->engineErrorMessage = "Not edited (sharing item)";
                    }

                  }
                  else {

                    $sql5 = "UPDATE sharing_items SET current_no_of_persons=:current_no_of_persons, last_modified=:last_modified WHERE unique_id=:unique_id";
                    $query5 = $conn->prepare($sql5);
                    $query5->bindParam(":current_no_of_persons", $new_current_no_of_persons);
                    $query5->bindParam(":unique_id", $sharing_unique_id);
                    $query5->bindParam(":last_modified", $date_added);
                    $query5->execute();

                    if ($query5->rowCount() > 0) {
                      $returnvalue = new genericClass();
                      $returnvalue->engineMessage = 1;
                    }
                    else {
                      $returnvalue = new genericClass();
                      $returnvalue->engineError = 2;
                      $returnvalue->engineErrorMessage = "Not edited (sharing item)";
                    }

                  }

                }
                else {
                  $returnvalue = new genericClass();
                  $returnvalue->engineError = 2;
                  $returnvalue->engineErrorMessage = "Not inserted (sharing history)";
                }

              }
              else {
                $returnvalue = new genericClass();
                $returnvalue->engineError = 2;
                $returnvalue->engineErrorMessage = "Not edited (sharing user paid item)";
              }

            }
            else {
              $returnvalue = new genericClass();
              $returnvalue->engineError = 2;
              $returnvalue->engineErrorMessage = "User sharing item already paid";
            }

          }

        }
        else {
          $returnvalue = new genericClass();
          $returnvalue->engineError = 2;
          $returnvalue->engineErrorMessage = "Not found (sharing item)";
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
