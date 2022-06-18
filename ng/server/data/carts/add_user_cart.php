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
      $Yes = $functions->Yes;
      $not_allowed_values = $functions->not_allowed_values;

      $user_unique_id = isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : $data['user_unique_id'];
      $cart_array = isset($_GET['cart_array']) ? $_GET['cart_array'] : $data['cart_array'];

      $sqlSearchUser = "SELECT unique_id FROM users WHERE unique_id=:unique_id AND status=:status";
      $querySearchUser = $conn->prepare($sqlSearchUser);
      $querySearchUser->bindParam(":unique_id", $user_unique_id);
      $querySearchUser->bindParam(":status", $active);
      $querySearchUser->execute();

      if ($querySearchUser->rowCount() > 0) {

        if (!is_array($cart_array)) {
          $returnvalue = new genericClass();
          $returnvalue->engineError = 2;
          $returnvalue->engineErrorMessage = "Cart array is not an array";
        }
        else if (in_array($cart_array,$not_allowed_values)) {
          $returnvalue = new genericClass();
          $returnvalue->engineError = 2;
          $returnvalue->engineErrorMessage = "Cart array is required";
        }
        else {

          foreach ($cart_array as $key => $cart_array_details){

            $sub_product_unique_id = $cart_array_details['sub_product_unique_id'];
            $quantity = $cart_array_details['quantity'];
            $cart_offered_service_unique_ids = $cart_array_details['cart_offered_service_unique_ids'];

            $null = $functions->null;

            $sql5 = "SELECT firstname as address_first_name, lastname as address_last_name, address, additional_information, city, state, country FROM users_addresses WHERE user_unique_id=:user_unique_id AND default_status=:default_status AND status=:status ORDER BY added_date DESC";
            $query5 = $conn->prepare($sql5);
            $query5->bindParam(":user_unique_id", $user_unique_id);
            $query5->bindParam(":default_status", $Yes);
            $query5->bindParam(":status", $active);
            $query5->execute();

            if ($query5->rowCount() > 0) {

              $the_address_details = $query5->fetch();
              $the_address_city = $the_address_details[4];
              $the_address_state = $the_address_details[5];
              $the_address_country = $the_address_details[6];

              $sql7 = "SELECT unique_id, price FROM shipping_fees WHERE sub_product_unique_id=:sub_product_unique_id AND city=:city AND state=:state AND country=:country AND status=:status";
              $query7 = $conn->prepare($sql7);
              $query7->bindParam(":sub_product_unique_id", $sub_product_unique_id);
              $query7->bindParam(":city", $the_address_city);
              $query7->bindParam(":state", $the_address_state);
              $query7->bindParam(":country", $the_address_country);
              $query7->bindParam(":status", $active);
              $query7->execute();

              $the_shipping_details = $query7->fetch();

              if ($query7->rowCount() > 0) {

                $the_shipping_fee_unique_id = $the_shipping_details[0];

                $cart_unique_id = $functions->random_str(20);

                $sql = "INSERT INTO carts (unique_id, user_unique_id, sub_product_unique_id, quantity, shipping_fee_unique_id, added_date, last_modified, status)
                VALUES (:unique_id, :user_unique_id, :sub_product_unique_id, :quantity, :shipping_fee_unique_id, :added_date, :last_modified, :status)";
                $query = $conn->prepare($sql);
                $query->bindParam(":unique_id", $cart_unique_id);
                $query->bindParam(":user_unique_id", $user_unique_id);
                $query->bindParam(":sub_product_unique_id", $sub_product_unique_id);
                $query->bindParam(":quantity", $quantity);
                $query->bindParam(":shipping_fee_unique_id", $the_shipping_fee_unique_id);
                $query->bindParam(":added_date", $date_added);
                $query->bindParam(":last_modified", $date_added);
                $query->bindParam(":status", $active);
                $query->execute();

                if ($query->rowCount() > 0) {

                  if (!in_array($cart_offered_service_unique_ids,$not_allowed_values)) {
                    foreach ($cart_offered_service_unique_ids as $key => $cart_offered_service_unique_id){

                      if (!in_array($cart_offered_service_unique_id,$not_allowed_values)) {

                        $order_service_unique_id = $functions->random_str(20);

                        $sql5 = "INSERT INTO order_services (unique_id, user_unique_id, cart_unique_id, order_unique_id, offered_service_unique_id, added_date, last_modified, status)
                        VALUES (:unique_id, :user_unique_id, :cart_unique_id, :order_unique_id, :offered_service_unique_id, :added_date, :last_modified, :status)";
                        $query5 = $conn->prepare($sql5);
                        $query5->bindParam(":unique_id", $order_service_unique_id);
                        $query5->bindParam(":user_unique_id", $user_unique_id);
                        $query5->bindParam(":cart_unique_id", $cart_unique_id);
                        $query5->bindParam(":order_unique_id", $null);
                        $query5->bindParam(":offered_service_unique_id", $cart_offered_service_unique_id);
                        $query5->bindParam(":added_date", $date_added);
                        $query5->bindParam(":last_modified", $date_added);
                        $query5->bindParam(":status", $active);
                        $query5->execute();

                        if ($query5->rowCount() > 0) {
                          $returnvalue = new genericClass();
                          $returnvalue->engineMessage = 1;
                        }
                        else{
                          $counter_now = $key;
                          $returnvalue = new genericClass();
                          $returnvalue->engineError = 2;
                          $returnvalue->engineErrorMessage = $counter_now." index, Not inserted (new order service)";
                        }

                      }
                      else {
                        $returnvalue = new genericClass();
                        $returnvalue->engineMessage = 1;
                      }

                    }
                  }
                  else {
                    $returnvalue = new genericClass();
                    $returnvalue->engineMessage = 1;
                  }

                }
                else {
                  $returnvalue = new genericClass();
                  $returnvalue->engineError = 2;
                  $returnvalue->engineErrorMessage = "Not inserted (user cart)";
                }

              }
              else {

                // $returnvalue = new genericClass();
                // $returnvalue->engineError = 2;
                // $returnvalue->engineErrorMessage = "Not added (your shipping location not available)";

                $cart_unique_id = $functions->random_str(20);

                $shipping_fee_unique_id = $null;

                $sql = "INSERT INTO carts (unique_id, user_unique_id, sub_product_unique_id, quantity, shipping_fee_unique_id, added_date, last_modified, status)
                VALUES (:unique_id, :user_unique_id, :sub_product_unique_id, :quantity, :shipping_fee_unique_id, :added_date, :last_modified, :status)";
                $query = $conn->prepare($sql);
                $query->bindParam(":unique_id", $cart_unique_id);
                $query->bindParam(":user_unique_id", $user_unique_id);
                $query->bindParam(":sub_product_unique_id", $sub_product_unique_id);
                $query->bindParam(":quantity", $quantity);
                $query->bindParam(":shipping_fee_unique_id", $shipping_fee_unique_id);
                $query->bindParam(":added_date", $date_added);
                $query->bindParam(":last_modified", $date_added);
                $query->bindParam(":status", $active);
                $query->execute();

                if ($query->rowCount() > 0) {

                  if (!in_array($cart_offered_service_unique_ids,$not_allowed_values)) {
                    foreach ($cart_offered_service_unique_ids as $key => $cart_offered_service_unique_id){

                      if (!in_array($cart_offered_service_unique_id,$not_allowed_values)) {

                        $order_service_unique_id = $functions->random_str(20);

                        $sql5 = "INSERT INTO order_services (unique_id, user_unique_id, cart_unique_id, order_unique_id, offered_service_unique_id, added_date, last_modified, status)
                        VALUES (:unique_id, :user_unique_id, :cart_unique_id, :order_unique_id, :offered_service_unique_id, :added_date, :last_modified, :status)";
                        $query5 = $conn->prepare($sql5);
                        $query5->bindParam(":unique_id", $order_service_unique_id);
                        $query5->bindParam(":user_unique_id", $user_unique_id);
                        $query5->bindParam(":cart_unique_id", $cart_unique_id);
                        $query5->bindParam(":order_unique_id", $null);
                        $query5->bindParam(":offered_service_unique_id", $cart_offered_service_unique_id);
                        $query5->bindParam(":added_date", $date_added);
                        $query5->bindParam(":last_modified", $date_added);
                        $query5->bindParam(":status", $active);
                        $query5->execute();

                        if ($query5->rowCount() > 0) {
                          $returnvalue = new genericClass();
                          $returnvalue->engineMessage = 1;
                        }
                        else{
                          $counter_now = $key;
                          $returnvalue = new genericClass();
                          $returnvalue->engineError = 2;
                          $returnvalue->engineErrorMessage = $counter_now." index, Not inserted (new order service)";
                        }

                      }
                      else {
                        $returnvalue = new genericClass();
                        $returnvalue->engineMessage = 1;
                      }

                    }
                  }
                  else {
                    $returnvalue = new genericClass();
                    $returnvalue->engineMessage = 1;
                  }

                }
                else {
                  $returnvalue = new genericClass();
                  $returnvalue->engineError = 2;
                  $returnvalue->engineErrorMessage = "Not inserted (user cart)";
                }

              }

            }
            else {
              $returnvalue = new genericClass();
              $returnvalue->engineError = 2;
              $returnvalue->engineErrorMessage = "Address not found";
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
