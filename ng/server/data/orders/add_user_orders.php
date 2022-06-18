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
      $null = $functions->null;
      $cart_checked_out = $functions->cart_checked_out;

      $user_unique_id = isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : $data['user_unique_id'];
      $cart_unique_ids = isset($_GET['cart_unique_ids']) ? $_GET['cart_unique_ids'] : $data['cart_unique_ids'];
      $payment_method = isset($_GET['payment_method']) ? $_GET['payment_method'] : $data['payment_method'];
      $tracker_unique_id = isset($_GET['tracker_unique_id']) ? $_GET['tracker_unique_id'] : $data['tracker_unique_id'];

      $sqlSearchUser = "SELECT unique_id FROM users WHERE unique_id=:unique_id AND status=:status";
      $querySearchUser = $conn->prepare($sqlSearchUser);
      $querySearchUser->bindParam(":unique_id", $user_unique_id);
      $querySearchUser->bindParam(":status", $active);
      $querySearchUser->execute();

      if ($querySearchUser->rowCount() > 0) {

        $sql5 = "SELECT firstname as address_first_name, lastname as address_last_name, address, additional_information, city, state, country FROM users_addresses WHERE user_unique_id=:user_unique_id ORDER BY added_date DESC";
        $query5 = $conn->prepare($sql5);
        $query5->bindParam(":user_unique_id", $user_unique_id);
        $query5->execute();

        if ($query5->rowCount() > 0) {

          if (!is_array($cart_unique_ids)) {
            $returnvalue = new genericClass();
            $returnvalue->engineError = 2;
            $returnvalue->engineErrorMessage = "Cart IDs is not an array";
          }
          else if (in_array($cart_unique_ids,$not_allowed_values)) {
            $returnvalue = new genericClass();
            $returnvalue->engineError = 2;
            $returnvalue->engineErrorMessage = "Cart IDs is required";
          }
          else {

            $countErrors = 0;

            foreach ($cart_unique_ids as $key => $cart_unique_id){
              $sql3 = "SELECT shipping_fee_unique_id FROM carts WHERE user_unique_id=:user_unique_id AND unique_id=:unique_id AND status=:status";
              $query3 = $conn->prepare($sql3);
              $query3->bindParam(":user_unique_id", $user_unique_id);
              $query3->bindParam(":unique_id", $cart_unique_id);
              $query3->bindParam(":status", $active);
              $query3->execute();

              if ($query3->rowCount() > 0) {
                $the_cart_details = $query3->fetch();
                $the_shipping_fee_unique_id = $the_cart_details[0];

                if ($the_shipping_fee_unique_id == null) {$countErrors += 1;}

              }
              else {
                $countErrors += 1;
                $returnvalue = new genericClass();
                $returnvalue->engineError = 2;
                $returnvalue->engineErrorMessage = $key." index, Cart Not Found";
              }

            }

            if ($countErrors != 0) {
              $returnvalue = new genericClass();
              $returnvalue->engineError = 2;
              $returnvalue->engineErrorMessage = "Checkout failed, (".$countErrors.") product shipping location not available";
            }
            else {

              $the_tracker_unique_id = in_array($tracker_unique_id,$not_allowed_values) ? $functions->random_str(20) : $tracker_unique_id;

              foreach ($cart_unique_ids as $key => $cart_unique_id){
                $sql4 = "SELECT sub_product_unique_id, quantity, shipping_fee_unique_id FROM carts WHERE user_unique_id=:user_unique_id AND unique_id=:unique_id AND status=:status";
                $query4 = $conn->prepare($sql4);
                $query4->bindParam(":user_unique_id", $user_unique_id);
                $query4->bindParam(":unique_id", $cart_unique_id);
                $query4->bindParam(":status", $active);
                $query4->execute();

                if ($query4->rowCount() > 0) {

                  $the_cart_details = $query4->fetch();
                  $sub_product_unique_id = $the_cart_details[0];
                  $current_quantity = $the_cart_details[1];
                  $int_quantity = (int)$current_quantity;
                  $the_shipping_fee_unique_id = $the_cart_details[2];

                  $order_unique_id = $functions->random_str(20);
                  // $tracker_unique_id = $functions->random_str(20);
                  $shipment_unique_id = $functions->null;
                  $coupon_unique_id = $functions->null;
                  $shipping_fee_unique_id = in_array($the_shipping_fee_unique_id,$not_allowed_values) ? $functions->null : $the_shipping_fee_unique_id;
                  $not_active = $functions->not_active;
                  $delivery_status = $functions->processing;
                  $completion_status = $functions->checked_out;

                  $sql = "INSERT INTO orders (unique_id, user_unique_id, sub_product_unique_id, tracker_unique_id, shipment_unique_id, coupon_unique_id, shipping_fee_unique_id, quantity, payment_method, checked_out, paid, shipped, disputed, delivery_status, added_date, last_modified, status)
                  VALUES (:unique_id, :user_unique_id, :sub_product_unique_id, :tracker_unique_id, :shipment_unique_id, :coupon_unique_id, :shipping_fee_unique_id, :quantity, :payment_method, :checked_out, :paid, :shipped, :disputed, :delivery_status, :added_date, :last_modified, :status)";
                  $query = $conn->prepare($sql);
                  $query->bindParam(":unique_id", $order_unique_id);
                  $query->bindParam(":user_unique_id", $user_unique_id);
                  $query->bindParam(":sub_product_unique_id", $sub_product_unique_id);
                  $query->bindParam(":tracker_unique_id", $the_tracker_unique_id);
                  $query->bindParam(":shipment_unique_id", $shipment_unique_id);
                  $query->bindParam(":coupon_unique_id", $coupon_unique_id);
                  $query->bindParam(":shipping_fee_unique_id", $shipping_fee_unique_id);
                  $query->bindParam(":quantity", $int_quantity);
                  $query->bindParam(":payment_method", $payment_method);
                  $query->bindParam(":checked_out", $active);
                  $query->bindParam(":paid", $not_active);
                  $query->bindParam(":shipped", $not_active);
                  $query->bindParam(":disputed", $not_active);
                  $query->bindParam(":delivery_status", $delivery_status);
                  $query->bindParam(":added_date", $date_added);
                  $query->bindParam(":last_modified", $date_added);
                  $query->bindParam(":status", $active);
                  $query->execute();

                  if ($query->rowCount() > 0) {

                    $sql3 = "UPDATE order_services SET order_unique_id=:order_unique_id, last_modified=:last_modified WHERE user_unique_id=:user_unique_id AND cart_unique_id=:cart_unique_id";
                    $query3 = $conn->prepare($sql3);
                    $query3->bindParam(":order_unique_id", $order_unique_id);
                    $query3->bindParam(":user_unique_id", $user_unique_id);
                    $query3->bindParam(":cart_unique_id", $cart_unique_id);
                    $query3->bindParam(":last_modified", $date_added);
                    $query3->execute();

                    if ($query3->rowCount() > 0) {

                      $order_history_unique_id = $functions->random_str(20);

                      $sql10 = "INSERT INTO order_history (unique_id, user_unique_id, order_unique_id, price, completion, added_date, last_modified, status)
                      VALUES (:unique_id, :user_unique_id, :order_unique_id, :price, :completion, :added_date, :last_modified, :status)";
                      $query10 = $conn->prepare($sql10);
                      $query10->bindParam(":unique_id", $order_history_unique_id);
                      $query10->bindParam(":user_unique_id", $user_unique_id);
                      $query10->bindParam(":order_unique_id", $order_unique_id);
                      $query10->bindParam(":price", $null);
                      $query10->bindParam(":completion", $completion_status);
                      $query10->bindParam(":added_date", $date_added);
                      $query10->bindParam(":last_modified", $date_added);
                      $query10->bindParam(":status", $active);
                      $query10->execute();

                      if ($query10->rowCount() > 0) {

                        $sql6 = "UPDATE carts SET status=:status, last_modified=:last_modified WHERE user_unique_id=:user_unique_id AND unique_id=:unique_id";
                        $query6 = $conn->prepare($sql6);
                        $query6->bindParam(":status", $cart_checked_out);
                        $query6->bindParam(":user_unique_id", $user_unique_id);
                        $query6->bindParam(":unique_id", $cart_unique_id);
                        $query6->bindParam(":last_modified", $date_added);
                        $query6->execute();

                        if ($query6->rowCount() > 0) {
                          $returnvalue = new genericClass();
                          $returnvalue->engineMessage = 1;
                          $returnvalue->resultData = $the_tracker_unique_id;
                        }
                        else {
                          $returnvalue = new genericClass();
                          $returnvalue->engineError = 2;
                          $returnvalue->engineErrorMessage = $key." index, Cart not checked out";
                        }

                      }
                      else {
                        $returnvalue = new genericClass();
                        $returnvalue->engineError = 2;
                        $returnvalue->engineErrorMessage = $key." index, Order history not updated";
                      }

                    }
                    else {

                      $order_history_unique_id = $functions->random_str(20);

                      $sql10 = "INSERT INTO order_history (unique_id, user_unique_id, order_unique_id, price, completion, added_date, last_modified, status)
                      VALUES (:unique_id, :user_unique_id, :order_unique_id, :price, :completion, :added_date, :last_modified, :status)";
                      $query10 = $conn->prepare($sql10);
                      $query10->bindParam(":unique_id", $order_history_unique_id);
                      $query10->bindParam(":user_unique_id", $user_unique_id);
                      $query10->bindParam(":order_unique_id", $order_unique_id);
                      $query10->bindParam(":price", $null);
                      $query10->bindParam(":completion", $completion_status);
                      $query10->bindParam(":added_date", $date_added);
                      $query10->bindParam(":last_modified", $date_added);
                      $query10->bindParam(":status", $active);
                      $query10->execute();

                      if ($query10->rowCount() > 0) {

                        $sql6 = "UPDATE carts SET status=:status, last_modified=:last_modified WHERE user_unique_id=:user_unique_id AND unique_id=:unique_id";
                        $query6 = $conn->prepare($sql6);
                        $query6->bindParam(":status", $cart_checked_out);
                        $query6->bindParam(":user_unique_id", $user_unique_id);
                        $query6->bindParam(":unique_id", $cart_unique_id);
                        $query6->bindParam(":last_modified", $date_added);
                        $query6->execute();

                        if ($query6->rowCount() > 0) {
                          $returnvalue = new genericClass();
                          $returnvalue->engineMessage = 1;
                          $returnvalue->resultData = $the_tracker_unique_id;
                        }
                        else {
                          $returnvalue = new genericClass();
                          $returnvalue->engineError = 2;
                          $returnvalue->engineErrorMessage = $key." index, Cart not checked out";
                        }

                      }
                      else {
                        $returnvalue = new genericClass();
                        $returnvalue->engineError = 2;
                        $returnvalue->engineErrorMessage = $key." index, Order history not updated";
                      }

                    }

                  }
                  else {
                    $returnvalue = new genericClass();
                    $returnvalue->engineError = 2;
                    $returnvalue->engineErrorMessage = $key." index, Not inserted (user order)";
                  }

                }
                else {
                  $returnvalue = new genericClass();
                  $returnvalue->engineError = 2;
                  $returnvalue->engineErrorMessage = $key." index, Cart Not Found";
                }
              }

            }

          }

        }
        else {
          $returnvalue = new genericClass();
          $returnvalue->engineError = 2;
          $returnvalue->engineErrorMessage = "Address Not Found";
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
