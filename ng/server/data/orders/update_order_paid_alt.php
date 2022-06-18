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
      $completion = $functions->completed;
      $completion_status = $functions->paid;

      $order_unique_id = isset($_GET['order_unique_id']) ? $_GET['order_unique_id'] : $data['order_unique_id'];
      $user_unique_id = isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : $data['user_unique_id'];
      $tracker_unique_id = isset($_GET['tracker_unique_id']) ? $_GET['tracker_unique_id'] : $data['tracker_unique_id'];
      $sub_product_unique_id = isset($_GET['sub_product_unique_id']) ? $_GET['sub_product_unique_id'] : $data['sub_product_unique_id'];

      $sqlSearchUser = "SELECT unique_id FROM users WHERE unique_id=:unique_id AND status=:status";
      $querySearchUser = $conn->prepare($sqlSearchUser);
      $querySearchUser->bindParam(":unique_id", $user_unique_id);
      $querySearchUser->bindParam(":status", $active);
      $querySearchUser->execute();

      if ($querySearchUser->rowCount() > 0) {

        $sqlQuantity = "SELECT quantity FROM orders WHERE unique_id=:unique_id AND user_unique_id=:user_unique_id AND tracker_unique_id=:tracker_unique_id AND paid!=:paid";
        $queryQuantity = $conn->prepare($sqlQuantity);
        $queryQuantity->bindParam(":unique_id", $order_unique_id);
        $queryQuantity->bindParam(":user_unique_id", $user_unique_id);
        $queryQuantity->bindParam(":tracker_unique_id", $tracker_unique_id);
        $queryQuantity->bindParam(":paid", $active);
        $queryQuantity->execute();

        if ($queryQuantity->rowCount() > 0) {

          $the_quantity_details = $queryQuantity->fetch();
          $quantity = (int)$the_quantity_details[0];

          $sql2 = "SELECT price, sales_price FROM products WHERE unique_id=:unique_id";
          $query2 = $conn->prepare($sql2);
          $query2->bindParam(":unique_id", $sub_product_unique_id);
          $query2->execute();

          if ($query2->rowCount() > 0) {
            $the_price_details = $query2->fetch();
            $product_price = (int)$the_price_details[0];
            $product_sales_price = (int)$the_price_details[1];

            if ($product_sales_price == 0) {

              $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND order_unique_id=:order_unique_id";
              $query7 = $conn->prepare($sql7);
              $query7->bindParam(":user_unique_id", $user_unique_id);
              $query7->bindParam(":order_unique_id", $order_unique_id);
              $query7->execute();

              if ($query7->rowCount() > 0) {
                $the_order_services_details = $query7->fetchAll();
                $offered_services_price = 0;

                foreach ($the_order_services_details as $key => $value) {
                  $offered_service_unique_id = $value["offered_service_unique_id"];
                  $sql8 = "SELECT price FROM offered_services WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id AND status=:status";
                  $query8 = $conn->prepare($sql8);
                  $query8->bindParam(":unique_id", $offered_service_unique_id);
                  $query8->bindParam(":sub_product_unique_id", $sub_product_unique_id);
                  $query8->bindParam(":status", $active);
                  $query8->execute();

                  $the_offered_services_price_details = $query8->fetch();
                  $the_offered_services_price = (int)$the_offered_services_price_details[0];
                  if ($query8->rowCount() > 0) {
                    $offered_services_price += $the_offered_services_price;
                  }
                  else{
                    $offered_services_price = 0;
                  }
                }

                $product_full_price = $product_price + $offered_services_price;
                $final_price = $product_full_price * $quantity;

                $sql9 = "UPDATE orders SET paid=:paid, delivery_status=:delivery_status, last_modified=:last_modified WHERE unique_id=:unique_id AND user_unique_id=:user_unique_id";
                $query9 = $conn->prepare($sql9);
                $query9->bindParam(":paid", $active);
                $query9->bindParam(":delivery_status", $completion_status);
                $query9->bindParam(":unique_id", $order_unique_id);
                $query9->bindParam(":user_unique_id", $user_unique_id);
                $query9->bindParam(":last_modified", $date_added);
                $query9->execute();

                if ($query9->rowCount() > 0) {

                  $order_history_unique_id = $functions->random_str(20);

                  $sql10 = "INSERT INTO order_history (unique_id, user_unique_id, order_unique_id, price, completion, added_date, last_modified, status)
                  VALUES (:unique_id, :user_unique_id, :order_unique_id, :price, :completion, :added_date, :last_modified, :status)";
                  $query10 = $conn->prepare($sql10);
                  $query10->bindParam(":unique_id", $order_history_unique_id);
                  $query10->bindParam(":user_unique_id", $user_unique_id);
                  $query10->bindParam(":order_unique_id", $order_unique_id);
                  $query10->bindParam(":price", $final_price);
                  $query10->bindParam(":completion", $completion_status);
                  $query10->bindParam(":added_date", $date_added);
                  $query10->bindParam(":last_modified", $date_added);
                  $query10->bindParam(":status", $active);
                  $query10->execute();

                  if ($query10->rowCount() > 0) {
                    $returnvalue = new genericClass();
                    $returnvalue->engineMessage = 1;
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
              else {

                $product_full_price = $product_price;
                $final_price = $product_full_price * $quantity;

                $sql9 = "UPDATE orders SET paid=:paid, delivery_status=:delivery_status, last_modified=:last_modified WHERE unique_id=:unique_id AND user_unique_id=:user_unique_id";
                $query9 = $conn->prepare($sql9);
                $query9->bindParam(":paid", $active);
                $query9->bindParam(":delivery_status", $completion_status);
                $query9->bindParam(":unique_id", $order_unique_id);
                $query9->bindParam(":user_unique_id", $user_unique_id);
                $query9->bindParam(":last_modified", $date_added);
                $query9->execute();

                if ($query9->rowCount() > 0) {

                  $order_history_unique_id = $functions->random_str(20);

                  $sql10 = "INSERT INTO order_history (unique_id, user_unique_id, order_unique_id, price, completion, added_date, last_modified, status)
                  VALUES (:unique_id, :user_unique_id, :order_unique_id, :price, :completion, :added_date, :last_modified, :status)";
                  $query10 = $conn->prepare($sql10);
                  $query10->bindParam(":unique_id", $order_history_unique_id);
                  $query10->bindParam(":user_unique_id", $user_unique_id);
                  $query10->bindParam(":order_unique_id", $order_unique_id);
                  $query10->bindParam(":price", $final_price);
                  $query10->bindParam(":completion", $completion_status);
                  $query10->bindParam(":added_date", $date_added);
                  $query10->bindParam(":last_modified", $date_added);
                  $query10->bindParam(":status", $active);
                  $query10->execute();

                  if ($query10->rowCount() > 0) {
                    $returnvalue = new genericClass();
                    $returnvalue->engineMessage = 1;
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

              $sql7 = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND order_unique_id=:order_unique_id";
              $query7 = $conn->prepare($sql7);
              $query7->bindParam(":user_unique_id", $user_unique_id);
              $query7->bindParam(":order_unique_id", $order_unique_id);
              $query7->execute();

              if ($query7->rowCount() > 0) {
                $the_order_services_details = $query7->fetchAll();
                $offered_services_price = 0;

                foreach ($the_order_services_details as $key => $value) {
                  $offered_service_unique_id = $value["offered_service_unique_id"];
                  $sql8 = "SELECT price FROM offered_services WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id AND status=:status";
                  $query8 = $conn->prepare($sql8);
                  $query8->bindParam(":unique_id", $offered_service_unique_id);
                  $query8->bindParam(":sub_product_unique_id", $sub_product_unique_id);
                  $query8->bindParam(":status", $active);
                  $query8->execute();

                  $the_offered_services_price_details = $query8->fetch();
                  $the_offered_services_price = (int)$the_offered_services_price_details[0];
                  if ($query8->rowCount() > 0) {
                    $offered_services_price += $the_offered_services_price;
                  }
                  else{
                    $offered_services_price = 0;
                  }
                }

                $product_full_price = $product_sales_price + $offered_services_price;
                $final_price = $product_full_price * $quantity;

                $sql9 = "UPDATE orders SET paid=:paid, delivery_status=:delivery_status, last_modified=:last_modified WHERE unique_id=:unique_id AND user_unique_id=:user_unique_id";
                $query9 = $conn->prepare($sql9);
                $query9->bindParam(":paid", $active);
                $query9->bindParam(":delivery_status", $completion_status);
                $query9->bindParam(":unique_id", $order_unique_id);
                $query9->bindParam(":user_unique_id", $user_unique_id);
                $query9->bindParam(":last_modified", $date_added);
                $query9->execute();

                if ($query9->rowCount() > 0) {

                  $order_history_unique_id = $functions->random_str(20);

                  $sql10 = "INSERT INTO order_history (unique_id, user_unique_id, order_unique_id, price, completion, added_date, last_modified, status)
                  VALUES (:unique_id, :user_unique_id, :order_unique_id, :price, :completion, :added_date, :last_modified, :status)";
                  $query10 = $conn->prepare($sql10);
                  $query10->bindParam(":unique_id", $order_history_unique_id);
                  $query10->bindParam(":user_unique_id", $user_unique_id);
                  $query10->bindParam(":order_unique_id", $order_unique_id);
                  $query10->bindParam(":price", $final_price);
                  $query10->bindParam(":completion", $completion_status);
                  $query10->bindParam(":added_date", $date_added);
                  $query10->bindParam(":last_modified", $date_added);
                  $query10->bindParam(":status", $active);
                  $query10->execute();

                  if ($query10->rowCount() > 0) {
                    $returnvalue = new genericClass();
                    $returnvalue->engineMessage = 1;
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
              else {

                $product_full_price = $product_sales_price;
                $final_price = $product_full_price * $quantity;

                $sql9 = "UPDATE orders SET paid=:paid, delivery_status=:delivery_status, last_modified=:last_modified WHERE unique_id=:unique_id AND user_unique_id=:user_unique_id";
                $query9 = $conn->prepare($sql9);
                $query9->bindParam(":paid", $active);
                $query9->bindParam(":delivery_status", $completion_status);
                $query9->bindParam(":unique_id", $order_unique_id);
                $query9->bindParam(":user_unique_id", $user_unique_id);
                $query9->bindParam(":last_modified", $date_added);
                $query9->execute();

                if ($query9->rowCount() > 0) {

                  $order_history_unique_id = $functions->random_str(20);

                  $sql10 = "INSERT INTO order_history (unique_id, user_unique_id, order_unique_id, price, completion, added_date, last_modified, status)
                  VALUES (:unique_id, :user_unique_id, :order_unique_id, :price, :completion, :added_date, :last_modified, :status)";
                  $query10 = $conn->prepare($sql10);
                  $query10->bindParam(":unique_id", $order_history_unique_id);
                  $query10->bindParam(":user_unique_id", $user_unique_id);
                  $query10->bindParam(":order_unique_id", $order_unique_id);
                  $query10->bindParam(":price", $final_price);
                  $query10->bindParam(":completion", $completion_status);
                  $query10->bindParam(":added_date", $date_added);
                  $query10->bindParam(":last_modified", $date_added);
                  $query10->bindParam(":status", $active);
                  $query10->execute();

                  if ($query10->rowCount() > 0) {
                    $returnvalue = new genericClass();
                    $returnvalue->engineMessage = 1;
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

          }
          else {
            $returnvalue = new genericClass();
            $returnvalue->engineError = 2;
            $returnvalue->engineErrorMessage = "Product not found";
          }

        }
        else {
          $returnvalue = new genericClass();
          $returnvalue->engineError = 2;
          $returnvalue->engineErrorMessage = "Order not found";
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
