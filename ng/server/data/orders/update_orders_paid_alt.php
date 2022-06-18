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
      $not_allowed_values = $functions->not_allowed_values;
      $completion = $functions->completed;
      $completion_status = $functions->paid;

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
            $sqlQuantity = "SELECT quantity, coupon_unique_id, user_unique_id, tracker_unique_id, sub_product_unique_id, shipping_fee_unique_id FROM orders WHERE unique_id=:unique_id AND paid!=:paid";
            $queryQuantity = $conn->prepare($sqlQuantity);
            $queryQuantity->bindParam(":unique_id", $order_unique_id);
            $queryQuantity->bindParam(":paid", $active);
            $queryQuantity->execute();

            if ($queryQuantity->rowCount() > 0) {

              $the_quantity_details = $queryQuantity->fetch();
              $quantity = (int)$the_quantity_details[0];
              $coupon_unique_id = $the_quantity_details[1];
              $user_unique_id = $the_quantity_details[2];
              $tracker_unique_id = $the_quantity_details[3];
              $sub_product_unique_id = $the_quantity_details[4];
              $shipping_fee_unique_id = $the_quantity_details[5];

              $sql2 = "SELECT price, sales_price FROM sub_products WHERE unique_id=:unique_id";
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

                    $sql3 = "SELECT price, current_count, name, unique_id, user_unique_id, sub_product_unique_id FROM coupons WHERE unique_id=:unique_id AND (user_unique_id=:user_unique_id OR sub_product_unique_id=:sub_product_unique_id) AND expiry_date >:today AND current_count!=0";
                    $query3 = $conn->prepare($sql3);
                    $query3->bindParam(":unique_id", $coupon_unique_id);
                    $query3->bindParam(":user_unique_id", $user_unique_id);
                    $query3->bindParam(":sub_product_unique_id", $sub_product_unique_id);
                    $query3->bindParam(":today", $date_added);
                    $query3->execute();

                    if ($query3->rowCount() > 0) {
                      $the_coupon_price_details = $query3->fetch();
                      $the_coupon_price = (int)$the_coupon_price_details[0];
                      $the_coupon_count = (int)$the_coupon_price_details[1];
                      $the_coupon_name = $the_coupon_price_details[2];
                      $the_coupon_unique_id = $the_coupon_price_details[3];
                      $the_coupon_user_unique_id = $the_coupon_price_details[4] != null ? $the_coupon_price_details[4] : $user_unique_id;
                      $the_coupon_sub_product_unique_id = $the_coupon_price_details[5];

                      if ($the_coupon_count != 0) {

                        $sql11 = "SELECT price, city, unique_id, sub_product_unique_id FROM shipping_fees WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id";
                        $query11 = $conn->prepare($sql11);
                        $query11->bindParam(":unique_id", $shipping_fee_unique_id);
                        $query11->bindParam(":sub_product_unique_id", $sub_product_unique_id);
                        $query11->execute();

                        if ($query11->rowCount() > 0) {
                          $the_shipping_fee_price_details = $query11->fetch();
                          $the_shipping_fee_price = (int)$the_shipping_fee_price_details[0];
                          $the_shipping_fee_city = $the_shipping_fee_price_details[1];
                          $the_shipping_fee_unique_id = $the_shipping_fee_price_details[2];
                          $the_shipping_fee_sub_product_unique_id = $the_shipping_fee_price_details[3];

                          $final_price = (($product_full_price + $the_shipping_fee_price) * $quantity ) - $the_coupon_price;
                          $coupon_history_unique_id = $functions->random_str(20);

                          $sql5 = "INSERT INTO coupon_history (unique_id, user_unique_id, sub_product_unique_id, name, price, completion, added_date, last_modified, status)
                          VALUES (:unique_id, :user_unique_id, :sub_product_unique_id, :name, :price, :completion, :added_date, :last_modified, :status)";
                          $query5 = $conn->prepare($sql5);
                          $query5->bindParam(":unique_id", $coupon_history_unique_id);
                          $query5->bindParam(":user_unique_id", $the_coupon_user_unique_id);
                          $query5->bindParam(":sub_product_unique_id", $the_coupon_sub_product_unique_id);
                          $query5->bindParam(":name", $the_coupon_name);
                          $query5->bindParam(":price", $the_coupon_price);
                          $query5->bindParam(":completion", $completion);
                          $query5->bindParam(":added_date", $date_added);
                          $query5->bindParam(":last_modified", $date_added);
                          $query5->bindParam(":status", $active);
                          $query5->execute();

                          if ($query5->rowCount() > 0) {

                            $current_count_update = $the_coupon_count - 1;

                            $sql6 = "UPDATE coupons SET current_count=:current_count, last_modified=:last_modified WHERE unique_id=:unique_id AND (user_unique_id=:user_unique_id OR sub_product_unique_id=:sub_product_unique_id)";
                            $query6 = $conn->prepare($sql6);
                            $query6->bindParam(":current_count", $current_count_update);
                            $query6->bindParam(":unique_id", $the_coupon_unique_id);
                            $query6->bindParam(":user_unique_id", $the_coupon_user_unique_id);
                            $query6->bindParam(":sub_product_unique_id", $the_coupon_sub_product_unique_id);
                            $query6->bindParam(":last_modified", $date_added);
                            $query6->execute();

                            if ($query6->rowCount() > 0) {

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

                                  if ($current_count_update == 0) {
                                    $sql6 = "UPDATE coupons SET completion=:completion, last_modified=:last_modified WHERE unique_id=:unique_id AND (user_unique_id=:user_unique_id OR sub_product_unique_id=:sub_product_unique_id)";
                                    $query6 = $conn->prepare($sql6);
                                    $query6->bindParam(":completion", $completion);
                                    $query6->bindParam(":unique_id", $the_coupon_unique_id);
                                    $query6->bindParam(":user_unique_id", $the_coupon_user_unique_id);
                                    $query6->bindParam(":sub_product_unique_id", $the_coupon_sub_product_unique_id);
                                    $query6->bindParam(":last_modified", $date_added);
                                    $query6->execute();

                                    if ($query6->rowCount() > 0) {
                                      $returnvalue = new genericClass();
                                      $returnvalue->engineMessage = 1;
                                    }
                                    else {
                                      $returnvalue = new genericClass();
                                      $returnvalue->engineError = 2;
                                      $returnvalue->engineErrorMessage = $key." index, Coupons Not updated";
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
                                  $returnvalue->engineErrorMessage = $key." index, Order history not updated";
                                }

                              }
                              else {
                                $returnvalue = new genericClass();
                                $returnvalue->engineError = 2;
                                $returnvalue->engineErrorMessage = $key." index, Orders not updated";
                              }

                            }
                            else {
                              $returnvalue = new genericClass();
                              $returnvalue->engineError = 2;
                              $returnvalue->engineErrorMessage = $key." index, Coupons Not updated";
                            }

                          }
                          else {
                            $returnvalue = new genericClass();
                            $returnvalue->engineError = 2;
                            $returnvalue->engineErrorMessage = $key." index, Coupon history not inserted";
                          }

                        }
                        else {
                          $returnvalue = new genericClass();
                          $returnvalue->engineError = 2;
                          $returnvalue->engineErrorMessage = $key." index, Shipping fee not available";
                        }

                      }
                      else{

                        $sql11 = "SELECT price, city, unique_id, sub_product_unique_id FROM shipping_fees WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id";
                        $query11 = $conn->prepare($sql11);
                        $query11->bindParam(":unique_id", $shipping_fee_unique_id);
                        $query11->bindParam(":sub_product_unique_id", $sub_product_unique_id);
                        $query11->execute();

                        if ($query11->rowCount() > 0) {
                          $the_shipping_fee_price_details = $query11->fetch();
                          $the_shipping_fee_price = (int)$the_shipping_fee_price_details[0];
                          $the_shipping_fee_city = $the_shipping_fee_price_details[1];
                          $the_shipping_fee_unique_id = $the_shipping_fee_price_details[2];
                          $the_shipping_fee_sub_product_unique_id = $the_shipping_fee_price_details[3];

                          $final_price = ($product_full_price + $the_shipping_fee_price) * $quantity;

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
                              $sql6 = "UPDATE coupons SET completion=:completion, last_modified=:last_modified WHERE unique_id=:unique_id AND user_unique_id=:user_unique_id";
                              $query6 = $conn->prepare($sql6);
                              $query6->bindParam(":completion", $completion);
                              $query6->bindParam(":unique_id", $the_coupon_unique_id);
                              $query6->bindParam(":user_unique_id", $user_unique_id);
                              $query6->bindParam(":last_modified", $date_added);
                              $query6->execute();

                              if ($query6->rowCount() > 0) {
                                $returnvalue = new genericClass();
                                $returnvalue->engineMessage = 1;
                              }
                              else {
                                $returnvalue = new genericClass();
                                $returnvalue->engineError = 2;
                                $returnvalue->engineErrorMessage = $key." index, Coupons Not updated";
                              }
                            }
                            else {
                              $returnvalue = new genericClass();
                              $returnvalue->engineError = 2;
                              $returnvalue->engineErrorMessage = $key." index, Order history not updated";
                            }

                          }
                          else {
                            $returnvalue = new genericClass();
                            $returnvalue->engineError = 2;
                            $returnvalue->engineErrorMessage = $key." index, Orders not updated";
                          }

                        }
                        else {
                          $returnvalue = new genericClass();
                          $returnvalue->engineError = 2;
                          $returnvalue->engineErrorMessage = $key." index, Shipping fee not available";
                        }

                      }
                    }
                    else {

                      $sql11 = "SELECT price, city, unique_id, sub_product_unique_id FROM shipping_fees WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id";
                      $query11 = $conn->prepare($sql11);
                      $query11->bindParam(":unique_id", $shipping_fee_unique_id);
                      $query11->bindParam(":sub_product_unique_id", $sub_product_unique_id);
                      $query11->execute();

                      if ($query11->rowCount() > 0) {
                        $the_shipping_fee_price_details = $query11->fetch();
                        $the_shipping_fee_price = (int)$the_shipping_fee_price_details[0];
                        $the_shipping_fee_city = $the_shipping_fee_price_details[1];
                        $the_shipping_fee_unique_id = $the_shipping_fee_price_details[2];
                        $the_shipping_fee_sub_product_unique_id = $the_shipping_fee_price_details[3];

                        $final_price = ($product_full_price + $the_shipping_fee_price) * $quantity;

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
                            $returnvalue->engineErrorMessage = $key." index, Order history not updated";
                          }

                        }
                        else {
                          $returnvalue = new genericClass();
                          $returnvalue->engineError = 2;
                          $returnvalue->engineErrorMessage = $key." index, Orders not updated";
                        }

                      }
                      else {
                        $returnvalue = new genericClass();
                        $returnvalue->engineError = 2;
                        $returnvalue->engineErrorMessage = $key." index, Shipping fee not available";
                      }

                    }

                  }
                  else {

                    $product_full_price = $product_price;

                    $sql3 = "SELECT price, current_count, name, unique_id, user_unique_id, sub_product_unique_id FROM coupons WHERE unique_id=:unique_id AND (user_unique_id=:user_unique_id OR sub_product_unique_id=:sub_product_unique_id) AND expiry_date >:today AND current_count!=0";
                    $query3 = $conn->prepare($sql3);
                    $query3->bindParam(":unique_id", $coupon_unique_id);
                    $query3->bindParam(":user_unique_id", $user_unique_id);
                    $query3->bindParam(":sub_product_unique_id", $sub_product_unique_id);
                    $query3->bindParam(":today", $date_added);
                    $query3->execute();

                    if ($query3->rowCount() > 0) {
                      $the_coupon_price_details = $query3->fetch();
                      $the_coupon_price = (int)$the_coupon_price_details[0];
                      $the_coupon_count = (int)$the_coupon_price_details[1];
                      $the_coupon_name = $the_coupon_price_details[2];
                      $the_coupon_unique_id = $the_coupon_price_details[3];
                      $the_coupon_user_unique_id = $the_coupon_price_details[4] != null ? $the_coupon_price_details[4] : $user_unique_id;
                      $the_coupon_sub_product_unique_id = $the_coupon_price_details[5];

                      if ($the_coupon_count != 0) {
                        $final_price = ($product_full_price * $quantity ) - $the_coupon_price;
                        $coupon_history_unique_id = $functions->random_str(20);

                        $sql5 = "INSERT INTO coupon_history (unique_id, user_unique_id, sub_product_unique_id, name, price, completion, added_date, last_modified, status)
                        VALUES (:unique_id, :user_unique_id, :sub_product_unique_id, :name, :price, :completion, :added_date, :last_modified, :status)";
                        $query5 = $conn->prepare($sql5);
                        $query5->bindParam(":unique_id", $coupon_history_unique_id);
                        $query5->bindParam(":user_unique_id", $the_coupon_user_unique_id);
                        $query5->bindParam(":sub_product_unique_id", $the_coupon_sub_product_unique_id);
                        $query5->bindParam(":name", $the_coupon_name);
                        $query5->bindParam(":price", $the_coupon_price);
                        $query5->bindParam(":completion", $completion);
                        $query5->bindParam(":added_date", $date_added);
                        $query5->bindParam(":last_modified", $date_added);
                        $query5->bindParam(":status", $active);
                        $query5->execute();

                        if ($query5->rowCount() > 0) {

                          $current_count_update = $the_coupon_count - 1;

                          $sql6 = "UPDATE coupons SET current_count=:current_count, last_modified=:last_modified WHERE unique_id=:unique_id AND (user_unique_id=:user_unique_id OR sub_product_unique_id=:sub_product_unique_id)";
                          $query6 = $conn->prepare($sql6);
                          $query6->bindParam(":current_count", $current_count_update);
                          $query6->bindParam(":unique_id", $the_coupon_unique_id);
                          $query6->bindParam(":user_unique_id", $the_coupon_user_unique_id);
                          $query6->bindParam(":sub_product_unique_id", $the_coupon_sub_product_unique_id);
                          $query6->bindParam(":last_modified", $date_added);
                          $query6->execute();

                          if ($query6->rowCount() > 0) {

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

                                if ($current_count_update == 0) {
                                  $sql6 = "UPDATE coupons SET completion=:completion, last_modified=:last_modified WHERE unique_id=:unique_id AND (user_unique_id=:user_unique_id OR sub_product_unique_id=:sub_product_unique_id)";
                                  $query6 = $conn->prepare($sql6);
                                  $query6->bindParam(":completion", $completion);
                                  $query6->bindParam(":unique_id", $the_coupon_unique_id);
                                  $query6->bindParam(":user_unique_id", $the_coupon_user_unique_id);
                                  $query6->bindParam(":sub_product_unique_id", $the_coupon_sub_product_unique_id);
                                  $query6->bindParam(":last_modified", $date_added);
                                  $query6->execute();

                                  if ($query6->rowCount() > 0) {
                                    $returnvalue = new genericClass();
                                    $returnvalue->engineMessage = 1;
                                  }
                                  else {
                                    $returnvalue = new genericClass();
                                    $returnvalue->engineError = 2;
                                    $returnvalue->engineErrorMessage = $key." index, Coupons Not updated";
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
                                $returnvalue->engineErrorMessage = $key." index, Order history not updated";
                              }

                            }
                            else {
                              $returnvalue = new genericClass();
                              $returnvalue->engineError = 2;
                              $returnvalue->engineErrorMessage = $key." index, Orders not updated";
                            }

                          }
                          else {
                            $returnvalue = new genericClass();
                            $returnvalue->engineError = 2;
                            $returnvalue->engineErrorMessage = $key." index, Coupons Not updated";
                          }

                        }
                        else {
                          $returnvalue = new genericClass();
                          $returnvalue->engineError = 2;
                          $returnvalue->engineErrorMessage = $key." index, Coupon history not inserted";
                        }

                      }
                      else{
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
                            $sql6 = "UPDATE coupons SET completion=:completion, last_modified=:last_modified WHERE unique_id=:unique_id AND user_unique_id=:user_unique_id";
                            $query6 = $conn->prepare($sql6);
                            $query6->bindParam(":completion", $completion);
                            $query6->bindParam(":unique_id", $the_coupon_unique_id);
                            $query6->bindParam(":user_unique_id", $user_unique_id);
                            $query6->bindParam(":last_modified", $date_added);
                            $query6->execute();

                            if ($query6->rowCount() > 0) {
                              $returnvalue = new genericClass();
                              $returnvalue->engineMessage = 1;
                            }
                            else {
                              $returnvalue = new genericClass();
                              $returnvalue->engineError = 2;
                              $returnvalue->engineErrorMessage = $key." index, Coupons Not updated";
                            }
                          }
                          else {
                            $returnvalue = new genericClass();
                            $returnvalue->engineError = 2;
                            $returnvalue->engineErrorMessage = $key." index, Order history not updated";
                          }

                        }
                        else {
                          $returnvalue = new genericClass();
                          $returnvalue->engineError = 2;
                          $returnvalue->engineErrorMessage = $key." index, Orders not updated";
                        }

                      }
                    }
                    else {

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
                          $returnvalue->engineErrorMessage = $key." index, Order history not updated";
                        }

                      }
                      else {
                        $returnvalue = new genericClass();
                        $returnvalue->engineError = 2;
                        $returnvalue->engineErrorMessage = $key." index, Orders not updated";
                      }

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

                    $sql3 = "SELECT price, current_count, name, unique_id, user_unique_id, sub_product_unique_id FROM coupons WHERE unique_id=:unique_id AND (user_unique_id=:user_unique_id OR sub_product_unique_id=:sub_product_unique_id) AND expiry_date >:today AND current_count!=0";
                    $query3 = $conn->prepare($sql3);
                    $query3->bindParam(":unique_id", $coupon_unique_id);
                    $query3->bindParam(":user_unique_id", $user_unique_id);
                    $query3->bindParam(":sub_product_unique_id", $sub_product_unique_id);
                    $query3->bindParam(":today", $date_added);
                    $query3->execute();

                    if ($query3->rowCount() > 0) {
                      $the_coupon_price_details = $query3->fetch();
                      $the_coupon_price = (int)$the_coupon_price_details[0];
                      $the_coupon_count = (int)$the_coupon_price_details[1];
                      $the_coupon_name = $the_coupon_price_details[2];
                      $the_coupon_unique_id = $the_coupon_price_details[3];
                      $the_coupon_user_unique_id = $the_coupon_price_details[4] != null ? $the_coupon_price_details[4] : $user_unique_id;
                      $the_coupon_sub_product_unique_id = $the_coupon_price_details[5];

                      if ($the_coupon_count != 0) {

                        $sql11 = "SELECT price, city, unique_id, sub_product_unique_id FROM shipping_fees WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id";
                        $query11 = $conn->prepare($sql11);
                        $query11->bindParam(":unique_id", $shipping_fee_unique_id);
                        $query11->bindParam(":sub_product_unique_id", $sub_product_unique_id);
                        $query11->execute();

                        if ($query11->rowCount() > 0) {
                          $the_shipping_fee_price_details = $query11->fetch();
                          $the_shipping_fee_price = (int)$the_shipping_fee_price_details[0];
                          $the_shipping_fee_city = $the_shipping_fee_price_details[1];
                          $the_shipping_fee_unique_id = $the_shipping_fee_price_details[2];
                          $the_shipping_fee_sub_product_unique_id = $the_shipping_fee_price_details[3];

                          $final_price = (($product_full_price + $the_shipping_fee_price) * $quantity ) - $the_coupon_price;
                          $coupon_history_unique_id = $functions->random_str(20);

                          $sql5 = "INSERT INTO coupon_history (unique_id, user_unique_id, sub_product_unique_id, name, price, completion, added_date, last_modified, status)
                          VALUES (:unique_id, :user_unique_id, :sub_product_unique_id, :name, :price, :completion, :added_date, :last_modified, :status)";
                          $query5 = $conn->prepare($sql5);
                          $query5->bindParam(":unique_id", $coupon_history_unique_id);
                          $query5->bindParam(":user_unique_id", $the_coupon_user_unique_id);
                          $query5->bindParam(":sub_product_unique_id", $the_coupon_sub_product_unique_id);
                          $query5->bindParam(":name", $the_coupon_name);
                          $query5->bindParam(":price", $the_coupon_price);
                          $query5->bindParam(":completion", $completion);
                          $query5->bindParam(":added_date", $date_added);
                          $query5->bindParam(":last_modified", $date_added);
                          $query5->bindParam(":status", $active);
                          $query5->execute();

                          if ($query5->rowCount() > 0) {

                            $current_count_update = $the_coupon_count - 1;

                            $sql6 = "UPDATE coupons SET current_count=:current_count, last_modified=:last_modified WHERE unique_id=:unique_id AND (user_unique_id=:user_unique_id OR sub_product_unique_id=:sub_product_unique_id)";
                            $query6 = $conn->prepare($sql6);
                            $query6->bindParam(":current_count", $current_count_update);
                            $query6->bindParam(":unique_id", $the_coupon_unique_id);
                            $query6->bindParam(":user_unique_id", $the_coupon_user_unique_id);
                            $query6->bindParam(":sub_product_unique_id", $the_coupon_sub_product_unique_id);
                            $query6->bindParam(":last_modified", $date_added);
                            $query6->execute();

                            if ($query6->rowCount() > 0) {

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

                                  if ($current_count_update == 0) {
                                    $sql6 = "UPDATE coupons SET completion=:completion, last_modified=:last_modified WHERE unique_id=:unique_id AND (user_unique_id=:user_unique_id OR sub_product_unique_id=:sub_product_unique_id)";
                                    $query6 = $conn->prepare($sql6);
                                    $query6->bindParam(":completion", $completion);
                                    $query6->bindParam(":unique_id", $the_coupon_unique_id);
                                    $query6->bindParam(":user_unique_id", $the_coupon_user_unique_id);
                                    $query6->bindParam(":sub_product_unique_id", $the_coupon_sub_product_unique_id);
                                    $query6->bindParam(":last_modified", $date_added);
                                    $query6->execute();

                                    if ($query6->rowCount() > 0) {
                                      $returnvalue = new genericClass();
                                      $returnvalue->engineMessage = 1;
                                    }
                                    else {
                                      $returnvalue = new genericClass();
                                      $returnvalue->engineError = 2;
                                      $returnvalue->engineErrorMessage = $key." index, Coupons Not updated";
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
                                  $returnvalue->engineErrorMessage = $key." index, Order history not updated";
                                }

                              }
                              else {
                                $returnvalue = new genericClass();
                                $returnvalue->engineError = 2;
                                $returnvalue->engineErrorMessage = $key." index, Orders not updated";
                              }

                            }
                            else {
                              $returnvalue = new genericClass();
                              $returnvalue->engineError = 2;
                              $returnvalue->engineErrorMessage = $key." index, Coupons Not updated";
                            }

                          }
                          else {
                            $returnvalue = new genericClass();
                            $returnvalue->engineError = 2;
                            $returnvalue->engineErrorMessage = $key." index, Coupon history not inserted";
                          }

                        }
                        else {
                          $returnvalue = new genericClass();
                          $returnvalue->engineError = 2;
                          $returnvalue->engineErrorMessage = $key." index, Shipping fee not available";
                        }

                      }
                      else{

                        $sql11 = "SELECT price, city, unique_id, sub_product_unique_id FROM shipping_fees WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id";
                        $query11 = $conn->prepare($sql11);
                        $query11->bindParam(":unique_id", $shipping_fee_unique_id);
                        $query11->bindParam(":sub_product_unique_id", $sub_product_unique_id);
                        $query11->execute();

                        if ($query11->rowCount() > 0) {
                          $the_shipping_fee_price_details = $query11->fetch();
                          $the_shipping_fee_price = (int)$the_shipping_fee_price_details[0];
                          $the_shipping_fee_city = $the_shipping_fee_price_details[1];
                          $the_shipping_fee_unique_id = $the_shipping_fee_price_details[2];
                          $the_shipping_fee_sub_product_unique_id = $the_shipping_fee_price_details[3];

                          $final_price = ($product_full_price + $the_shipping_fee_price) * $quantity;

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
                              $sql6 = "UPDATE coupons SET completion=:completion, last_modified=:last_modified WHERE unique_id=:unique_id AND user_unique_id=:user_unique_id";
                              $query6 = $conn->prepare($sql6);
                              $query6->bindParam(":completion", $completion);
                              $query6->bindParam(":unique_id", $the_coupon_unique_id);
                              $query6->bindParam(":user_unique_id", $user_unique_id);
                              $query6->bindParam(":last_modified", $date_added);
                              $query6->execute();

                              if ($query6->rowCount() > 0) {
                                $returnvalue = new genericClass();
                                $returnvalue->engineMessage = 1;
                              }
                              else {
                                $returnvalue = new genericClass();
                                $returnvalue->engineError = 2;
                                $returnvalue->engineErrorMessage = $key." index, Coupons Not updated";
                              }
                            }
                            else {
                              $returnvalue = new genericClass();
                              $returnvalue->engineError = 2;
                              $returnvalue->engineErrorMessage = $key." index, Order history not updated";
                            }

                          }
                          else {
                            $returnvalue = new genericClass();
                            $returnvalue->engineError = 2;
                            $returnvalue->engineErrorMessage = $key." index, Orders not updated";
                          }

                        }
                        else {
                          $returnvalue = new genericClass();
                          $returnvalue->engineError = 2;
                          $returnvalue->engineErrorMessage = $key." index, Shipping fee not available";
                        }

                      }
                    }
                    else {

                      $sql11 = "SELECT price, city, unique_id, sub_product_unique_id FROM shipping_fees WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id";
                      $query11 = $conn->prepare($sql11);
                      $query11->bindParam(":unique_id", $shipping_fee_unique_id);
                      $query11->bindParam(":sub_product_unique_id", $sub_product_unique_id);
                      $query11->execute();

                      if ($query11->rowCount() > 0) {
                        $the_shipping_fee_price_details = $query11->fetch();
                        $the_shipping_fee_price = (int)$the_shipping_fee_price_details[0];
                        $the_shipping_fee_city = $the_shipping_fee_price_details[1];
                        $the_shipping_fee_unique_id = $the_shipping_fee_price_details[2];
                        $the_shipping_fee_sub_product_unique_id = $the_shipping_fee_price_details[3];

                        $final_price = ($product_full_price + $the_shipping_fee_price) * $quantity;

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
                            $returnvalue->engineErrorMessage = $key." index, Order history not updated";
                          }

                        }
                        else {
                          $returnvalue = new genericClass();
                          $returnvalue->engineError = 2;
                          $returnvalue->engineErrorMessage = $key." index, Orders not updated";
                        }

                      }
                      else {
                        $returnvalue = new genericClass();
                        $returnvalue->engineError = 2;
                        $returnvalue->engineErrorMessage = $key." index, Shipping fee not available";
                      }

                    }

                  }
                  else {

                    $product_full_price = $product_sales_price;

                    $sql3 = "SELECT price, current_count, name, unique_id, user_unique_id, sub_product_unique_id FROM coupons WHERE unique_id=:unique_id AND (user_unique_id=:user_unique_id OR sub_product_unique_id=:sub_product_unique_id) AND expiry_date >:today AND current_count!=0";
                    $query3 = $conn->prepare($sql3);
                    $query3->bindParam(":unique_id", $coupon_unique_id);
                    $query3->bindParam(":user_unique_id", $user_unique_id);
                    $query3->bindParam(":sub_product_unique_id", $sub_product_unique_id);
                    $query3->bindParam(":today", $date_added);
                    $query3->execute();

                    if ($query3->rowCount() > 0) {
                      $the_coupon_price_details = $query3->fetch();
                      $the_coupon_price = (int)$the_coupon_price_details[0];
                      $the_coupon_count = (int)$the_coupon_price_details[1];
                      $the_coupon_name = $the_coupon_price_details[2];
                      $the_coupon_unique_id = $the_coupon_price_details[3];
                      $the_coupon_user_unique_id = $the_coupon_price_details[4] != null ? $the_coupon_price_details[4] : $user_unique_id;
                      $the_coupon_sub_product_unique_id = $the_coupon_price_details[5];

                      if ($the_coupon_count != 0) {

                        $sql11 = "SELECT price, city, unique_id, sub_product_unique_id FROM shipping_fees WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id";
                        $query11 = $conn->prepare($sql11);
                        $query11->bindParam(":unique_id", $shipping_fee_unique_id);
                        $query11->bindParam(":sub_product_unique_id", $sub_product_unique_id);
                        $query11->execute();

                        if ($query11->rowCount() > 0) {
                          $the_shipping_fee_price_details = $query11->fetch();
                          $the_shipping_fee_price = (int)$the_shipping_fee_price_details[0];
                          $the_shipping_fee_city = $the_shipping_fee_price_details[1];
                          $the_shipping_fee_unique_id = $the_shipping_fee_price_details[2];
                          $the_shipping_fee_sub_product_unique_id = $the_shipping_fee_price_details[3];

                          $final_price = (($product_full_price + $the_shipping_fee_price) * $quantity ) - $the_coupon_price;
                          $coupon_history_unique_id = $functions->random_str(20);

                          $sql5 = "INSERT INTO coupon_history (unique_id, user_unique_id, sub_product_unique_id, name, price, completion, added_date, last_modified, status)
                          VALUES (:unique_id, :user_unique_id, :sub_product_unique_id, :name, :price, :completion, :added_date, :last_modified, :status)";
                          $query5 = $conn->prepare($sql5);
                          $query5->bindParam(":unique_id", $coupon_history_unique_id);
                          $query5->bindParam(":user_unique_id", $the_coupon_user_unique_id);
                          $query5->bindParam(":sub_product_unique_id", $the_coupon_sub_product_unique_id);
                          $query5->bindParam(":name", $the_coupon_name);
                          $query5->bindParam(":price", $the_coupon_price);
                          $query5->bindParam(":completion", $completion);
                          $query5->bindParam(":added_date", $date_added);
                          $query5->bindParam(":last_modified", $date_added);
                          $query5->bindParam(":status", $active);
                          $query5->execute();

                          if ($query5->rowCount() > 0) {

                            $current_count_update = $the_coupon_count - 1;

                            $sql6 = "UPDATE coupons SET current_count=:current_count, last_modified=:last_modified WHERE unique_id=:unique_id AND (user_unique_id=:user_unique_id OR sub_product_unique_id=:sub_product_unique_id)";
                            $query6 = $conn->prepare($sql6);
                            $query6->bindParam(":current_count", $current_count_update);
                            $query6->bindParam(":unique_id", $the_coupon_unique_id);
                            $query6->bindParam(":user_unique_id", $the_coupon_user_unique_id);
                            $query6->bindParam(":sub_product_unique_id", $the_coupon_sub_product_unique_id);
                            $query6->bindParam(":last_modified", $date_added);
                            $query6->execute();

                            if ($query6->rowCount() > 0) {

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

                                  if ($current_count_update == 0) {
                                    $sql6 = "UPDATE coupons SET completion=:completion, last_modified=:last_modified WHERE unique_id=:unique_id AND (user_unique_id=:user_unique_id OR sub_product_unique_id=:sub_product_unique_id)";
                                    $query6 = $conn->prepare($sql6);
                                    $query6->bindParam(":completion", $completion);
                                    $query6->bindParam(":unique_id", $the_coupon_unique_id);
                                    $query6->bindParam(":user_unique_id", $the_coupon_user_unique_id);
                                    $query6->bindParam(":sub_product_unique_id", $the_coupon_sub_product_unique_id);
                                    $query6->bindParam(":last_modified", $date_added);
                                    $query6->execute();

                                    if ($query6->rowCount() > 0) {
                                      $returnvalue = new genericClass();
                                      $returnvalue->engineMessage = 1;
                                    }
                                    else {
                                      $returnvalue = new genericClass();
                                      $returnvalue->engineError = 2;
                                      $returnvalue->engineErrorMessage = $key." index, Coupons Not updated";
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
                                  $returnvalue->engineErrorMessage = $key." index, Order history not updated";
                                }

                              }
                              else {
                                $returnvalue = new genericClass();
                                $returnvalue->engineError = 2;
                                $returnvalue->engineErrorMessage = $key." index, Orders not updated";
                              }

                            }
                            else {
                              $returnvalue = new genericClass();
                              $returnvalue->engineError = 2;
                              $returnvalue->engineErrorMessage = $key." index, Coupons Not updated";
                            }

                          }
                          else {
                            $returnvalue = new genericClass();
                            $returnvalue->engineError = 2;
                            $returnvalue->engineErrorMessage = $key." index, Coupon history not inserted";
                          }

                        }
                        else {
                          $returnvalue = new genericClass();
                          $returnvalue->engineError = 2;
                          $returnvalue->engineErrorMessage = $key." index, Shipping fee not available";
                        }

                      }
                      else{

                        $sql11 = "SELECT price, city, unique_id, sub_product_unique_id FROM shipping_fees WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id";
                        $query11 = $conn->prepare($sql11);
                        $query11->bindParam(":unique_id", $shipping_fee_unique_id);
                        $query11->bindParam(":sub_product_unique_id", $sub_product_unique_id);
                        $query11->execute();

                        if ($query11->rowCount() > 0) {
                          $the_shipping_fee_price_details = $query11->fetch();
                          $the_shipping_fee_price = (int)$the_shipping_fee_price_details[0];
                          $the_shipping_fee_city = $the_shipping_fee_price_details[1];
                          $the_shipping_fee_unique_id = $the_shipping_fee_price_details[2];
                          $the_shipping_fee_sub_product_unique_id = $the_shipping_fee_price_details[3];

                          $final_price = ($product_full_price + $the_shipping_fee_price) * $quantity;

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
                              $sql6 = "UPDATE coupons SET completion=:completion, last_modified=:last_modified WHERE unique_id=:unique_id AND user_unique_id=:user_unique_id";
                              $query6 = $conn->prepare($sql6);
                              $query6->bindParam(":completion", $completion);
                              $query6->bindParam(":unique_id", $the_coupon_unique_id);
                              $query6->bindParam(":user_unique_id", $user_unique_id);
                              $query6->bindParam(":last_modified", $date_added);
                              $query6->execute();

                              if ($query6->rowCount() > 0) {
                                $returnvalue = new genericClass();
                                $returnvalue->engineMessage = 1;
                              }
                              else {
                                $returnvalue = new genericClass();
                                $returnvalue->engineError = 2;
                                $returnvalue->engineErrorMessage = $key." index, Coupons Not updated";
                              }
                            }
                            else {
                              $returnvalue = new genericClass();
                              $returnvalue->engineError = 2;
                              $returnvalue->engineErrorMessage = $key." index, Order history not updated";
                            }

                          }
                          else {
                            $returnvalue = new genericClass();
                            $returnvalue->engineError = 2;
                            $returnvalue->engineErrorMessage = $key." index, Orders not updated";
                          }
                        }
                        else {
                          $returnvalue = new genericClass();
                          $returnvalue->engineError = 2;
                          $returnvalue->engineErrorMessage = $key." index, Shipping fee not available";
                        }

                      }
                    }
                    else {

                      $sql11 = "SELECT price, city, unique_id, sub_product_unique_id FROM shipping_fees WHERE unique_id=:unique_id AND sub_product_unique_id=:sub_product_unique_id";
                      $query11 = $conn->prepare($sql11);
                      $query11->bindParam(":unique_id", $shipping_fee_unique_id);
                      $query11->bindParam(":sub_product_unique_id", $sub_product_unique_id);
                      $query11->execute();

                      if ($query11->rowCount() > 0) {
                        $the_shipping_fee_price_details = $query11->fetch();
                        $the_shipping_fee_price = (int)$the_shipping_fee_price_details[0];
                        $the_shipping_fee_city = $the_shipping_fee_price_details[1];
                        $the_shipping_fee_unique_id = $the_shipping_fee_price_details[2];
                        $the_shipping_fee_sub_product_unique_id = $the_shipping_fee_price_details[3];

                        $final_price = ($product_full_price + $the_shipping_fee_price) * $quantity;

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
                            $returnvalue->engineErrorMessage = $key." index, Order history not updated";
                          }

                        }
                        else {
                          $returnvalue = new genericClass();
                          $returnvalue->engineError = 2;
                          $returnvalue->engineErrorMessage = $key." index, Orders not updated";
                        }

                      }
                      else {
                        $returnvalue = new genericClass();
                        $returnvalue->engineError = 2;
                        $returnvalue->engineErrorMessage = $key." index, Shipping fee not available";
                      }

                    }

                  }

                }

              }
              else {
                $returnvalue = new genericClass();
                $returnvalue->engineError = 2;
                $returnvalue->engineErrorMessage = $key." index, Sub Product not found";
              }

            }
            else {
              $returnvalue = new genericClass();
              $returnvalue->engineError = 2;
              $returnvalue->engineErrorMessage = $key." index, Order not found (probably paid for already)";
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
