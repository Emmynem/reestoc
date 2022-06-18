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
      $completion_status = $functions->completed;

      $order_unique_ids = isset($_GET['order_unique_ids']) ? $_GET['order_unique_ids'] : $data['order_unique_ids'];
      $management_user_unique_id = isset($_GET['management_user_unique_id']) ? $_GET['management_user_unique_id'] : $data['management_user_unique_id'];

      $sqlSearchUser = "SELECT unique_id FROM management WHERE unique_id=:unique_id AND status=:status";
      $querySearchUser = $conn->prepare($sqlSearchUser);
      $querySearchUser->bindParam(":unique_id", $management_user_unique_id);
      $querySearchUser->bindParam(":status", $active);
      $querySearchUser->execute();

      if ($querySearchUser->rowCount() > 0) {

        if (!is_array($order_unique_ids)) {
          $returnvalue = new genericClass();
          $returnvalue->engineError = 2;
          $returnvalue->engineErrorMessage = "Order IDs is not an array";
        }
        else if (in_array($order_unique_ids,$not_allowed_values)) {
          $returnvalue = new genericClass();
          $returnvalue->engineError = 2;
          $returnvalue->engineErrorMessage = "Order IDs is required";
        }
        else {

          foreach ($order_unique_ids as $key => $order_unique_id){

            $sqlOrder = "SELECT sub_product_unique_id, shipment_unique_id, coupon_unique_id, shipping_fee_unique_id, quantity, payment_method, delivery_status, user_unique_id, tracker_unique_id FROM orders WHERE unique_id=:unique_id AND user_unique_id=:user_unique_id AND status=:status";
            $queryOrder = $conn->prepare($sqlOrder);
            $queryOrder->bindParam(":unique_id", $order_unique_id);
            $queryOrder->bindParam(":status", $active);
            $queryOrder->execute();

            if ($queryOrder->rowCount() > 0) {

              $the_order_details = $queryOrder->fetch();
              $the_sub_product_unique_id = $the_order_details[0];
              $the_shipment_unique_id = $the_order_details[1];
              // $the_coupon_unique_id = $the_order_details[2];
              $the_shipping_fee_unique_id = $the_order_details[3];
              $the_quantity = $the_order_details[4];
              $the_payment_method = $the_order_details[5];
              $the_delivery_status = $the_order_details[6];

              $user_unique_id = $the_order_details[7];
              $tracker_unique_id = $the_order_details[8];

              $sqlMiniCategory = "SELECT products.mini_category_unique_id FROM sub_products LEFT JOIN products ON sub_products.product_unique_id = products.unique_id WHERE sub_products.unique_id=:unique_id";
              $queryMiniCategory = $conn->prepare($sqlMiniCategory);
              $queryMiniCategory->bindParam(":unique_id", $the_sub_product_unique_id);
              $queryMiniCategory->execute();

              $the_mini_category_details = $queryMiniCategory->rowCount() > 0 ? $queryMiniCategory->fetch() : null;
              $the_mini_category_unique_id = $the_mini_category_details != null ? $the_mini_category_details[0] : null;

              if (strtolower($the_delivery_status) == strtolower($completion_status)) {
                $returnvalue = new genericClass();
                $returnvalue->engineError = 2;
                $returnvalue->engineErrorMessage = "Order already ".$completion_status;
              }
              else {

                $sql2 = "UPDATE orders SET delivery_status=:delivery_status, last_modified=:last_modified WHERE unique_id=:unique_id AND user_unique_id=:user_unique_id AND tracker_unique_id=:tracker_unique_id";
                $query2 = $conn->prepare($sql2);
                $query2->bindParam(":unique_id", $order_unique_id);
                $query2->bindParam(":delivery_status", $completion_status);
                $query2->bindParam(":user_unique_id", $user_unique_id);
                $query2->bindParam(":tracker_unique_id", $tracker_unique_id);
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
                  $query->bindParam(":completion", $completion_status);
                  $query->bindParam(":added_date", $date_added);
                  $query->bindParam(":last_modified", $date_added);
                  $query->bindParam(":status", $active);
                  $query->execute();

                  if ($query->rowCount() > 0) {

                    $sqlSubProduct = "SELECT name, size, price, sales_price FROM sub_products WHERE unique_id=:unique_id AND status=:status";
                    $querySubProduct = $conn->prepare($sqlSubProduct);
                    $querySubProduct->bindParam(":unique_id", $the_sub_product_unique_id);
                    $querySubProduct->bindParam(":status", $active);
                    $querySubProduct->execute();

                    $the_sub_product_details = $querySubProduct->rowCount() > 0 ? $querySubProduct->fetch() : null;
                    $sub_product_name = $the_sub_product_details != null ? $the_sub_product_details[0] : null;
                    $sub_product_size = $the_sub_product_details != null ? $the_sub_product_details[1] : null;
                    $sub_product_price = $the_sub_product_details != null ? (int)$the_sub_product_details[2] : null;
                    $sub_product_sales_price = $the_sub_product_details != null ? (int)$the_sub_product_details[3] : null;

                    $sub_product_last_price = $sub_product_sales_price == 0 ? $sub_product_price : $sub_product_sales_price;

                    $sqlShipment = "SELECT riders.fullname as rider_fullname, riders.phone_number as rider_phone_number FROM shipments
                    INNER JOIN riders ON shipments.rider_unique_id = riders.unique_id WHERE shipments.shipment_unique_id=:shipment_unique_id";
                    $queryShipment = $conn->prepare($sqlShipment);
                    $queryShipment->bindParam(":shipment_unique_id", $the_shipment_unique_id);
                    $queryShipment->execute();

                    $the_shipment_details = $queryShipment->rowCount() > 0 ? $queryShipment->fetch() : null;
                    $shipment_rider_fullname = $the_shipment_details != null ? $the_shipment_details[0] : null;
                    $shipment_rider_phone_number = $the_shipment_details != null ? $the_shipment_details[1] : null;

                    $sqlOrderCoupon = "SELECT coupon_unique_id FROM order_coupons WHERE tracker_unique_id=:tracker_unique_id";
                    $queryOrderCoupon = $conn->prepare($sqlOrderCoupon);
                    $queryOrderCoupon->bindParam(":tracker_unique_id", $tracker_unique_id);
                    $queryOrderCoupon->execute();

                    $the_order_coupon_details = $queryOrderCoupon->rowCount() > 0 ? $queryOrderCoupon->fetch() : null;
                    $the_coupon_unique_id = $the_order_coupon_details != null ? $the_order_coupon_details[0] : null;

                    $sqlCoupon = "SELECT percentage, name, code, user_unique_id, sub_product_unique_id, mini_category_unique_id FROM coupons WHERE unique_id=:unique_id";
                    $queryCoupon = $conn->prepare($sqlCoupon);
                    $queryCoupon->bindParam(":unique_id", $the_coupon_unique_id);
                    $queryCoupon->execute();

                    $the_coupon_details = $queryCoupon->rowCount() > 0 ? $queryCoupon->fetch() : null;
                    $coupon_user_unique_id = $the_coupon_details != null ? $the_coupon_details[3] : null;
                    $coupon_sub_product_unique_id = $the_coupon_details != null ? $the_coupon_details[4] : null;
                    $coupon_mini_category_unique_id = $the_coupon_details != null ? $the_coupon_details[5] : null;
                    $coupon_percentage = ($the_coupon_details != null && ($coupon_sub_product_unique_id == $the_sub_product_unique_id || $coupon_mini_category_unique_id == $the_mini_category_unique_id || $coupon_user_unique_id == $user_unique_id)) ? (int)$the_coupon_details[0] : null;
                    $coupon_name = ($the_coupon_details != null && ($coupon_sub_product_unique_id == $the_sub_product_unique_id || $coupon_mini_category_unique_id == $the_mini_category_unique_id || $coupon_user_unique_id == $user_unique_id)) ? $the_coupon_details[1] : null;
                    $coupon_code = ($the_coupon_details != null && ($coupon_sub_product_unique_id == $the_sub_product_unique_id || $coupon_mini_category_unique_id == $the_mini_category_unique_id || $coupon_user_unique_id == $user_unique_id)) ? $the_coupon_details[2] : null;

                    $coupon_price = ($the_coupon_details != null && ($coupon_sub_product_unique_id == $the_sub_product_unique_id || $coupon_mini_category_unique_id == $the_mini_category_unique_id || $coupon_user_unique_id == $user_unique_id)) ? (($sub_product_last_price * $coupon_percentage) / 100) * $the_quantity : null;

                    $sqlShippingFee = "SELECT price, city, state, country FROM shipping_fees WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id AND status=:status";
                    $queryShippingFee = $conn->prepare($sqlShippingFee);
                    $queryShippingFee->bindParam(":unique_id", $the_shipping_fee_unique_id);
                    $queryShippingFee->bindParam(":sub_product_unique_id", $the_sub_product_unique_id);
                    $queryShippingFee->bindParam(":status", $active);
                    $queryShippingFee->execute();

                    $the_shipping_fee_details = $queryShippingFee->rowCount() > 0 ? $queryShippingFee->fetch() : null;
                    $shipping_fee_price = $the_shipping_fee_details != null ? (int)$the_shipping_fee_details[0] : null;
                    $shipping_fee_city = $the_shipping_fee_details != null ? $the_shipping_fee_details[1] : null;
                    $shipping_fee_state = $the_shipping_fee_details != null ? $the_shipping_fee_details[2] : null;
                    $shipping_fee_country = $the_shipping_fee_details != null ? $the_shipping_fee_details[3] : null;

                    $sqlUserAddress = "SELECT firstname as address_first_name, lastname as address_last_name, address, additional_information FROM users_addresses WHERE user_unique_id=:user_unique_id AND city=:city AND state=:state AND country=:country AND status=:status";
                    $queryUserAddress = $conn->prepare($sqlUserAddress);
                    $queryUserAddress->bindParam(":user_unique_id", $user_unique_id);
                    $queryUserAddress->bindParam(":city", $shipping_fee_city);
                    $queryUserAddress->bindParam(":state", $shipping_fee_state);
                    $queryUserAddress->bindParam(":country", $shipping_fee_country);
                    $queryUserAddress->bindParam(":status", $active);
                    $queryUserAddress->execute();

                    $the_user_address_details = $queryUserAddress->rowCount() > 0 ? $queryUserAddress->fetch() : null;
                    $user_address_first_name = $the_user_address_details != null ? $the_user_address_details[0] : null;
                    $user_address_last_name = $the_user_address_details != null ? $the_user_address_details[1] : null;
                    $user_address_address = $the_user_address_details != null ? $the_user_address_details[2] : null;
                    $user_address_addtional_information = $the_user_address_details != null ? $the_user_address_details[3] : null;

                    $user_address_fullname = $user_address_first_name." ".$user_address_last_name;
                    $user_address_full_address = $user_address_address." ".$user_address_addtional_information;

                    $order_offered_services = null;
                    $order_offered_services_price = null;
                    $order_offered_services_unique_ids = null;
                    $order_offered_services_price_total_amount = null;

                    $sqlOrderServices = "SELECT offered_service_unique_id FROM order_services WHERE user_unique_id=:user_unique_id AND order_unique_id=:order_unique_id";
                    $queryOrderServices = $conn->prepare($sqlOrderServices);
                    $queryOrderServices->bindParam(":user_unique_id", $user_unique_id);
                    $queryOrderServices->bindParam(":order_unique_id", $order_unique_id);
                    $queryOrderServices->execute();

                    $the_order_services_details = $queryOrderServices->rowCount() > 0 ? $queryOrderServices->fetchAll() : null;

                    $current_order_offered_services = null;
                    $current_order_offered_services_price = null;

                    $total_offered_services_amount = 0;

                    if ($the_order_services_details != null) {
                      foreach ($the_order_services_details as $key => $value) {
                        $offered_service_unique_id = $value["offered_service_unique_id"];

                        $sqlOfferedServices = "SELECT price, service FROM offered_services WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id AND status=:status";
                        $queryOfferedServices = $conn->prepare($sqlOfferedServices);
                        $queryOfferedServices->bindParam(":unique_id", $offered_service_unique_id);
                        $queryOfferedServices->bindParam(":sub_product_unique_id", $the_sub_product_unique_id);
                        $queryOfferedServices->bindParam(":status", $active);
                        $queryOfferedServices->execute();

                        $the_offered_services_details = $queryOfferedServices->rowCount() > 0 ? $queryOfferedServices->fetch() : null;
                        $offered_services_price = $the_offered_services_details != null ? (int)$the_offered_services_details[0] : null;
                        $offered_services_service = $the_offered_services_details != null ? $the_offered_services_details[1] : null;

                        $offered_services_price_txt = strval($offered_services_price);

                        $offered_services_service_alt = ($key + 1) < count($the_order_services_details) ? $offered_services_service.", " : $offered_services_service;
                        $offered_services_price_alt = ($key + 1) < count($the_order_services_details) ? $offered_services_price_txt.", " : $offered_services_price_txt;

                        $current_order_offered_services.=$offered_services_service_alt;
                        $current_order_offered_services_price.=$offered_services_price_alt;
                        $total_offered_services_amount +=$offered_services_price;

                      }
                    }

                    $order_offered_services = $current_order_offered_services;
                    $order_offered_services_price = $current_order_offered_services_price;
                    $order_offered_services_price_total_amount = $total_offered_services_amount * $the_quantity;

                    $order_full_price = $coupon_price != null ? (($sub_product_last_price * $the_quantity) + ($shipping_fee_price * $the_quantity) + $order_offered_services_price_total_amount) - $coupon_price : ($sub_product_last_price * $the_quantity) + ($shipping_fee_price * $the_quantity) + $order_offered_services_price_total_amount;

                    $orders_completed_unique_id = $functions->random_str(20);

                    $sqlOrdersCompleted = "INSERT INTO orders_completed (unique_id, user_unique_id, order_unique_id, tracker_unique_id, quantity, payment_method, sub_product_name, sub_product_size, rider_fullname, rider_phone_number,
                      coupon_name, coupon_code, coupon_percentage, coupon_price, user_address_fullname, user_full_address, city, state, country, offered_services, offered_services_prices, offered_services_total_amount, shipping_fee_price, total_price, added_date, last_modified, status)
                    VALUES (:unique_id, :user_unique_id, :order_unique_id, :tracker_unique_id, :quantity, :payment_method, :sub_product_name, :sub_product_size, :rider_fullname, :rider_phone_number,
                      :coupon_name, :coupon_code, :coupon_percentage, :coupon_price, :user_address_fullname, :user_full_address, :city, :state, :country, :offered_services, :offered_services_prices, :offered_services_total_amount, :shipping_fee_price, :total_price, :added_date, :last_modified, :status)";
                    $queryOrdersCompleted = $conn->prepare($sqlOrdersCompleted);
                    $queryOrdersCompleted->bindParam(":unique_id", $orders_completed_unique_id);
                    $queryOrdersCompleted->bindParam(":user_unique_id", $user_unique_id);
                    $queryOrdersCompleted->bindParam(":order_unique_id", $order_unique_id);
                    $queryOrdersCompleted->bindParam(":tracker_unique_id", $tracker_unique_id);
                    $queryOrdersCompleted->bindParam(":quantity", $the_quantity);
                    $queryOrdersCompleted->bindParam(":payment_method", $the_payment_method);
                    $queryOrdersCompleted->bindParam(":sub_product_name", $sub_product_name);
                    $queryOrdersCompleted->bindParam(":sub_product_size", $sub_product_size);
                    $queryOrdersCompleted->bindParam(":rider_fullname", $shipment_rider_fullname);
                    $queryOrdersCompleted->bindParam(":rider_phone_number", $shipment_rider_phone_number);
                    $queryOrdersCompleted->bindParam(":coupon_name", $coupon_name);
                    $queryOrdersCompleted->bindParam(":coupon_code", $coupon_code);
                    $queryOrdersCompleted->bindParam(":coupon_percentage", $coupon_percentage);
                    $queryOrdersCompleted->bindParam(":coupon_price", $coupon_price);
                    $queryOrdersCompleted->bindParam(":user_address_fullname", $user_address_fullname);
                    $queryOrdersCompleted->bindParam(":user_full_address", $user_address_full_address);
                    $queryOrdersCompleted->bindParam(":city", $shipping_fee_city);
                    $queryOrdersCompleted->bindParam(":state", $shipping_fee_state);
                    $queryOrdersCompleted->bindParam(":country", $shipping_fee_country);
                    $queryOrdersCompleted->bindParam(":offered_services", $order_offered_services);
                    $queryOrdersCompleted->bindParam(":offered_services_prices", $order_offered_services_price);
                    $queryOrdersCompleted->bindParam(":offered_services_total_amount", $order_offered_services_price_total_amount);
                    $queryOrdersCompleted->bindParam(":shipping_fee_price", $shipping_fee_price);
                    $queryOrdersCompleted->bindParam(":total_price", $order_full_price);
                    $queryOrdersCompleted->bindParam(":added_date", $date_added);
                    $queryOrdersCompleted->bindParam(":last_modified", $date_added);
                    $queryOrdersCompleted->bindParam(":status", $active);
                    $queryOrdersCompleted->execute();

                    if ($queryOrdersCompleted->rowCount() > 0) {
                      $returnvalue = new genericClass();
                      $returnvalue->engineMessage = 1;
                    }
                    else {
                      $returnvalue = new genericClass();
                      $returnvalue->engineError = 2;
                      $returnvalue->engineErrorMessage = "Orders completed not inserted";
                    }

                  }
                  else {
                    $returnvalue = new genericClass();
                    $returnvalue->engineError = 2;
                    $returnvalue->engineErrorMessage = "Order history not inserted";
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
