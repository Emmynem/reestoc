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
      $completion_status = $functions->disputed;
      $completed = $functions->completed;

      $management_user_unique_id = isset($_GET['management_user_unique_id']) ? $_GET['management_user_unique_id'] : $data['management_user_unique_id'];
      $tracker_unique_ids = isset($_GET['tracker_unique_ids']) ? $_GET['tracker_unique_ids'] : $data['tracker_unique_ids'];
      $message = isset($_GET['message']) ? $_GET['message'] : $data['message'];
      $option = isset($_GET['option']) ? $_GET['option'] : $data['option'];

      $sqlSearchUser = "SELECT unique_id FROM management WHERE unique_id=:unique_id AND status=:status";
      $querySearchUser = $conn->prepare($sqlSearchUser);
      $querySearchUser->bindParam(":unique_id", $management_user_unique_id);
      $querySearchUser->bindParam(":status", $active);
      $querySearchUser->execute();

      if ($querySearchUser->rowCount() > 0) {

        $validation = $functions->dispute_validation($message);

        if ($validation["error"] == true) {
          $returnvalue = new genericClass();
          $returnvalue->engineError = 2;
          $returnvalue->engineErrorMessage = $validation["message"];
        }
        else {

          if (!is_array($tracker_unique_ids)) {
            $returnvalue = new genericClass();
            $returnvalue->engineError = 2;
            $returnvalue->engineErrorMessage = "Tracker IDs is not an array";
          }
          else if (in_array($tracker_unique_ids,$not_allowed_values)) {
            $returnvalue = new genericClass();
            $returnvalue->engineError = 2;
            $returnvalue->engineErrorMessage = "Tracker IDs is required";
          }
          else {

            foreach ($tracker_unique_ids as $key => $tracker_unique_id){

              $sqlTracker = "SELECT order_unique_id, user_unique_id FROM orders WHERE tracker_unique_id=:tracker_unique_id AND paid!=:paid AND status=:status";
              $queryTracker = $conn->prepare($sqlTracker);
              $queryTracker->bindParam(":tracker_unique_id", $tracker_unique_id);
              $queryTracker->bindParam(":paid", $active);
              $queryTracker->bindParam(":status", $active);
              $queryTracker->execute();

              if ($queryTracker->rowCount() > 0) {

                $tracker_details = $queryTracker->fetchAll();

                if (!is_array($tracker_details)) {
                  $returnvalue = new genericClass();
                  $returnvalue->engineError = 2;
                  $returnvalue->engineErrorMessage = "Tracker details is not an array";
                }
                else if (in_array($tracker_details,$not_allowed_values)) {
                  $returnvalue = new genericClass();
                  $returnvalue->engineError = 2;
                  $returnvalue->engineErrorMessage = "Tracker details is required";
                }
                else {

                  foreach ($tracker_details as $key => $tracker_details){

                    $order_unique_id = $tracker_details['order_unique_id'];
                    $user_unique_id = $tracker_details['user_unique_id'];

                    $sqlOrder = "SELECT delivery_status FROM orders WHERE unique_id=:unique_id AND user_unique_id=:user_unique_id AND status=:status";
                    $queryOrder = $conn->prepare($sqlOrder);
                    $queryOrder->bindParam(":unique_id", $order_unique_id);
                    $queryOrder->bindParam(":user_unique_id", $user_unique_id);
                    $queryOrder->bindParam(":status", $active);
                    $queryOrder->execute();

                    if ($queryOrder->rowCount() > 0) {

                      $the_order_details = $queryOrder->fetch();
                      $the_delivery_status = $the_order_details[0];

                      if (strtolower($the_delivery_status) == strtolower($option)) {
                        $returnvalue = new genericClass();
                        $returnvalue->engineError = 2;
                        $returnvalue->engineErrorMessage = "Order already ".$option;
                      }
                      else if (strtolower($the_delivery_status) == strtolower($completed)) {
                        $returnvalue = new genericClass();
                        $returnvalue->engineError = 2;
                        $returnvalue->engineErrorMessage = "Order already ".$completed;
                      }
                      else {

                        $completion_status_alt = $option == "Other" ? $completion_status : $option;

                        $sql2 = "UPDATE orders SET disputed=:disputed, delivery_status=:delivery_status, last_modified=:last_modified WHERE unique_id=:unique_id AND user_unique_id=:user_unique_id";
                        $query2 = $conn->prepare($sql2);
                        $query2->bindParam(":disputed", $active);
                        $query2->bindParam(":unique_id", $order_unique_id);
                        $query2->bindParam(":delivery_status", $completion_status_alt);
                        $query2->bindParam(":user_unique_id", $user_unique_id);
                        $query2->bindParam(":last_modified", $date_added);
                        $query2->execute();

                        if ($query2->rowCount() > 0) {

                          $order_history_unique_id = $functions->random_str(20);

                          $sql = "INSERT INTO order_history (unique_id, user_unique_id, order_unique_id, price, completion, added_date, last_modified, status)
                          VALUES (:unique_id, :user_unique_id, :order_unique_id, :price, :completion, :added_date, :last_modified, :status)";
                          $query = $conn->prepare($sql);
                          $query->bindParam(":unique_id", $order_history_unique_id);
                          $query->bindParam(":user_unique_id", $user_unique_id);
                          $query->bindParam(":order_unique_id", $order_unique_id);
                          $query->bindParam(":price", $null);
                          $query->bindParam(":completion", $completion_status_alt);
                          $query->bindParam(":added_date", $date_added);
                          $query->bindParam(":last_modified", $date_added);
                          $query->bindParam(":status", $active);
                          $query->execute();

                          if ($query->rowCount() > 0) {

                            $dispute_unique_id = $functions->random_str(20);

                            $sql3 = "INSERT INTO disputes (unique_id, user_unique_id, order_unique_id, message, added_date, last_modified, status)
                            VALUES (:unique_id, :user_unique_id, :order_unique_id, :message, :added_date, :last_modified, :status)";
                            $query3 = $conn->prepare($sql3);
                            $query3->bindParam(":unique_id", $dispute_unique_id);
                            $query3->bindParam(":user_unique_id", $user_unique_id);
                            $query3->bindParam(":order_unique_id", $order_unique_id);
                            $query3->bindParam(":message", $message);
                            $query3->bindParam(":added_date", $date_added);
                            $query3->bindParam(":last_modified", $date_added);
                            $query3->bindParam(":status", $active);
                            $query3->execute();

                            if ($query3->rowCount() > 0) {
                              $returnvalue = new genericClass();
                              $returnvalue->engineMessage = 1;
                            }
                            else {
                              $returnvalue = new genericClass();
                              $returnvalue->engineError = 2;
                              $returnvalue->engineErrorMessage = "Dispute not inserted";
                            }

                          }
                          else {
                            $returnvalue = new genericClass();
                            $returnvalue->engineError = 2;
                            $returnvalue->engineErrorMessage = "Order history not updated";
                          }

                        }
                        else {
                          $returnvalue = new genericClass();
                          $returnvalue->engineError = 2;
                          $returnvalue->engineErrorMessage = "Orders not updated";
                        }

                      }

                    }
                    else {
                      $returnvalue = new genericClass();
                      $returnvalue->engineError = 2;
                      $returnvalue->engineErrorMessage = "Order not found";
                    }

                  }

                }

              }
              else {
                $returnvalue = new genericClass();
                $returnvalue->engineError = 2;
                $returnvalue->engineErrorMessage = "Order tracking ID not found";
              }

            }

          }

        }

      }
      else {
        $returnvalue = new genericClass();
        $returnvalue->engineError = 2;
        $returnvalue->engineErrorMessage = "Management User not found";
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
