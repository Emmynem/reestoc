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
      $Yes = $functions->Yes;
      $not_allowed_values = $functions->not_allowed_values;
      $null = $functions->null;
      $started = $functions->started;
      $completed = $functions->completed;

      $user_unique_id = isset($_GET['user_unique_id']) ? $_GET['user_unique_id'] : $data['user_unique_id'];
      $sharing_unique_id = isset($_GET['sharing_unique_id']) ? $_GET['sharing_unique_id'] : $data['sharing_unique_id'];
      $amount = isset($_GET['amount']) ? $_GET['amount'] : $data['amount'];
      $payment_method = isset($_GET['payment_method']) ? $_GET['payment_method'] : $data['payment_method'];
      $pickup_location = isset($_GET['pickup_location']) ? $_GET['pickup_location'] : $data['pickup_location'];
      $pickup_location_unique_id = isset($_GET['pickup_location_unique_id']) ? $_GET['pickup_location_unique_id'] : $data['pickup_location_unique_id'];

      $sqlSearchUser = "SELECT unique_id FROM users WHERE unique_id=:unique_id AND status=:status";
      $querySearchUser = $conn->prepare($sqlSearchUser);
      $querySearchUser->bindParam(":unique_id", $user_unique_id);
      $querySearchUser->bindParam(":status", $active);
      $querySearchUser->execute();

      if ($querySearchUser->rowCount() > 0) {

        $sql9 = "SELECT paid FROM sharing_users WHERE user_unique_id=:user_unique_id AND sharing_unique_id=:sharing_unique_id";
        $query9 = $conn->prepare($sql9);
        $query9->bindParam(":user_unique_id", $user_unique_id);
        $query9->bindParam(":sharing_unique_id", $sharing_unique_id);
        $query9->execute();

        if ($query9->rowCount() > 0) {

          $the_sharing_user_details = $query9->fetch();
          $the_sharing_user_paid_status = (int)$the_sharing_user_details[0];

          if ($the_sharing_user_paid_status == 1) {
            $returnvalue = new genericClass();
            $returnvalue->engineError = 3;
            $returnvalue->engineErrorMessage = "You've subscribed and paid for already";
          }
          else {
            $returnvalue = new genericClass();
            $returnvalue->engineError = 3;
            $returnvalue->engineErrorMessage = "You've subscribed already but not paid yet";
          }

        }
        else {

          if ($pickup_location == 1) {

            if (in_array($pickup_location_unique_id,$not_allowed_values)) {
              $returnvalue = new genericClass();
              $returnvalue->engineError = 2;
              $returnvalue->engineErrorMessage = "Pickup location required";
            }
            else {

              $sql5 = "SELECT default_pickup_locations.firstname as address_first_name, default_pickup_locations.lastname as address_last_name, default_pickup_locations.address,
              default_pickup_locations.additional_information, default_pickup_locations.city, default_pickup_locations.state, default_pickup_locations.country,
              pickup_locations.default_pickup_location_unique_id FROM pickup_locations INNER JOIN default_pickup_locations ON pickup_locations.default_pickup_location_unique_id = default_pickup_locations.unique_id WHERE pickup_locations.unique_id=:unique_id AND pickup_locations.status=:status LIMIT 1";
              $query5 = $conn->prepare($sql5);
              $query5->bindParam(":unique_id", $pickup_location_unique_id);
              $query5->bindParam(":status", $active);
              $query5->execute();

              if ($query5->rowCount() > 0) {

                $the_address_details = $query5->fetch();
                $the_address_first_name = $the_address_details[0];
                $the_address_last_name = $the_address_details[1];
                $the_address_address = $the_address_details[2];
                $the_address_additional_information = $the_address_details[3];
                $the_address_city = $the_address_details[4];
                $the_address_state = $the_address_details[5];
                $the_address_country = $the_address_details[6];
                $the_address_default_pickup_location_unique_id = $the_address_details[7];

                $sql6 = "SELECT starting_date, expiry_date, current_no_of_persons, expiration, total_no_of_persons, name FROM sharing_items WHERE unique_id=:unique_id AND status=:status";
                $query6 = $conn->prepare($sql6);
                $query6->bindParam(":unique_id", $sharing_unique_id);
                $query6->bindParam(":status", $active);
                $query6->execute();

                if ($query6->rowCount() > 0) {

                  $the_sharing_details = $query6->fetch();
                  $the_starting_date = $the_sharing_details[0];
                  $the_expiry_date = $the_sharing_details[1];
                  $the_current_no_of_persons = (int)$the_sharing_details[2];
                  $the_expiration = (int)$the_sharing_details[3];
                  $the_total_no_of_persons = (int)$the_sharing_details[4];
                  $the_sharing_item_name = $the_sharing_details[5];

                  $sql7 = "SELECT unique_id, price FROM pickup_locations WHERE unique_id=:unique_id AND sharing_unique_id=:sharing_unique_id AND default_pickup_location_unique_id=:default_pickup_location_unique_id AND status=:status";
                  $query7 = $conn->prepare($sql7);
                  $query7->bindParam(":unique_id", $pickup_location_unique_id);
                  $query7->bindParam(":sharing_unique_id", $sharing_unique_id);
                  $query7->bindParam(":default_pickup_location_unique_id", $the_address_default_pickup_location_unique_id);
                  // $query7->bindParam(":state", $the_address_state);
                  // $query7->bindParam(":country", $the_address_country);
                  $query7->bindParam(":status", $active);
                  $query7->execute();

                  if ($query7->rowCount() > 0) {

                    $the_shipping_details = $query7->fetch();

                    $the_shipping_fee_unique_id = $the_shipping_details[0];
                    $the_shipping_fee_price = (int)$the_shipping_details[1];

                    $amount_alt = $amount + $the_shipping_fee_price;

                    if ($date_added < $the_starting_date) {
                      $returnvalue = new genericClass();
                      $returnvalue->engineError = 2;
                      $returnvalue->engineErrorMessage = "Subscription hasn't commenced yet";
                    }
                    else if ($the_expiration == $active && $date_added > $the_expiry_date) {
                      $returnvalue = new genericClass();
                      $returnvalue->engineError = 2;
                      $returnvalue->engineErrorMessage = "Subscription is over";
                    }
                    else if ($the_current_no_of_persons == $the_total_no_of_persons) {
                      $returnvalue = new genericClass();
                      $returnvalue->engineError = 2;
                      $returnvalue->engineErrorMessage = "Participants occupied";
                    }
                    else if ($the_current_no_of_persons == 0) {

                      $sql4 = "UPDATE sharing_items SET completion=:completion, last_modified=:last_modified WHERE unique_id=:unique_id";
                      $query4 = $conn->prepare($sql4);
                      $query4->bindParam(":unique_id", $sharing_unique_id);
                      $query4->bindParam(":completion", $started);
                      $query4->bindParam(":last_modified", $date_added);
                      $query4->execute();

                      if ($query4->rowCount() > 0) {

                        $sharing_users_unique_id = $functions->random_str(20);

                        $sql10 = "INSERT INTO sharing_users (unique_id, user_unique_id, sharing_unique_id, shipping_fee_unique_id, pickup_location, amount, payment_method, paid, added_date, last_modified, status)
                        VALUES (:unique_id, :user_unique_id, :sharing_unique_id, :shipping_fee_unique_id, :pickup_location, :amount, :payment_method, :paid, :added_date, :last_modified, :status)";
                        $query10 = $conn->prepare($sql10);
                        $query10->bindParam(":unique_id", $sharing_users_unique_id);
                        $query10->bindParam(":user_unique_id", $user_unique_id);
                        $query10->bindParam(":sharing_unique_id", $sharing_unique_id);
                        $query10->bindParam(":shipping_fee_unique_id", $the_shipping_fee_unique_id);
                        $query10->bindParam(":pickup_location", $active);
                        $query10->bindParam(":amount", $amount_alt);
                        $query10->bindParam(":payment_method", $payment_method);
                        $query10->bindParam(":paid", $not_active);
                        $query10->bindParam(":added_date", $date_added);
                        $query10->bindParam(":last_modified", $date_added);
                        $query10->bindParam(":status", $active);
                        $query10->execute();

                        if ($query10->rowCount() > 0) {

                          $the_action = "Subscribed to sharing - ".$the_sharing_item_name.". Cost = ".$amount." naira, Shipping fee = ".$the_shipping_fee_price." naira, Pickup location - ".$the_address_first_name." ".$the_address_last_name.", ".$the_address_city.", ".$the_address_state.", ".$the_address_country.". Total amount = ".$amount_alt.".";

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
                            $returnvalue = new genericClass();
                            $returnvalue->engineMessage = 1;
                            $returnvalue->resultData = $amount_alt;
                            $returnvalue->filteredData = $sharing_users_unique_id;
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
                          $returnvalue->engineErrorMessage = "Not subscribed";
                        }

                      }
                      else {
                        $returnvalue = new genericClass();
                        $returnvalue->engineError = 2;
                        $returnvalue->engineErrorMessage = "Not edited (sharing item)";
                      }

                    }
                    else {

                      $sharing_users_unique_id = $functions->random_str(20);

                      $sql10 = "INSERT INTO sharing_users (unique_id, user_unique_id, sharing_unique_id, shipping_fee_unique_id, pickup_location, amount, payment_method, paid, added_date, last_modified, status)
                      VALUES (:unique_id, :user_unique_id, :sharing_unique_id, :shipping_fee_unique_id, :pickup_location, :amount, :payment_method, :paid, :added_date, :last_modified, :status)";
                      $query10 = $conn->prepare($sql10);
                      $query10->bindParam(":unique_id", $sharing_users_unique_id);
                      $query10->bindParam(":user_unique_id", $user_unique_id);
                      $query10->bindParam(":sharing_unique_id", $sharing_unique_id);
                      $query10->bindParam(":shipping_fee_unique_id", $the_shipping_fee_unique_id);
                      $query10->bindParam(":pickup_location", $active);
                      $query10->bindParam(":amount", $amount_alt);
                      $query10->bindParam(":payment_method", $payment_method);
                      $query10->bindParam(":paid", $not_active);
                      $query10->bindParam(":added_date", $date_added);
                      $query10->bindParam(":last_modified", $date_added);
                      $query10->bindParam(":status", $active);
                      $query10->execute();

                      if ($query10->rowCount() > 0) {

                        $the_action = "Subscribed to sharing - ".$the_sharing_item_name.". Cost = ".$amount." naira, Shipping fee = ".$the_shipping_fee_price." naira, Pickup location - ".$the_address_first_name." ".$the_address_last_name.", ".$the_address_city.", ".$the_address_state.", ".$the_address_country.". Total amount = ".$amount_alt.".";

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
                          $returnvalue = new genericClass();
                          $returnvalue->engineMessage = 1;
                          $returnvalue->resultData = $amount_alt;
                          $returnvalue->filteredData = $sharing_users_unique_id;
                        }
                        else {
                          $returnvalue = new genericClass();
                          $returnvalue->engineError = 2;
                          $returnvalue->engineErrorMessage = "Not subscribed";
                        }

                      }
                      else {
                        $returnvalue = new genericClass();
                        $returnvalue->engineError = 2;
                        $returnvalue->engineErrorMessage = "Not subscribed";
                      }

                    }

                  }
                  else {
                    $returnvalue = new genericClass();
                    $returnvalue->engineError = 2;
                    $returnvalue->engineErrorMessage = "Subscription failed, your pick up location not available";
                  }

                }
                else {
                  $returnvalue = new genericClass();
                  $returnvalue->engineError = 2;
                  $returnvalue->engineErrorMessage = "Sharing Item not found";
                }

              }
              else {
                $returnvalue = new genericClass();
                $returnvalue->engineError = 2;
                $returnvalue->engineErrorMessage = "Address Not Found";
              }

            }

          }
          else {

            $sql5 = "SELECT firstname as address_first_name, lastname as address_last_name, address, additional_information, city, state, country FROM users_addresses WHERE user_unique_id=:user_unique_id AND default_status=:default_status AND status=:status";
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

              $sql6 = "SELECT starting_date, expiry_date, current_no_of_persons, expiration, total_no_of_persons, name FROM sharing_items WHERE unique_id=:unique_id AND status=:status";
              $query6 = $conn->prepare($sql6);
              $query6->bindParam(":unique_id", $sharing_unique_id);
              $query6->bindParam(":status", $active);
              $query6->execute();

              if ($query6->rowCount() > 0) {

                $the_sharing_details = $query6->fetch();
                $the_starting_date = $the_sharing_details[0];
                $the_expiry_date = $the_sharing_details[1];
                $the_current_no_of_persons = (int)$the_sharing_details[2];
                $the_expiration = (int)$the_sharing_details[3];
                $the_total_no_of_persons = (int)$the_sharing_details[4];
                $the_sharing_item_name = $the_sharing_details[5];

                $sql7 = "SELECT unique_id, price FROM sharing_shipping_fees WHERE sharing_unique_id=:sharing_unique_id AND city=:city AND state=:state AND country=:country AND status=:status";
                $query7 = $conn->prepare($sql7);
                $query7->bindParam(":sharing_unique_id", $sharing_unique_id);
                $query7->bindParam(":city", $the_address_city);
                $query7->bindParam(":state", $the_address_state);
                $query7->bindParam(":country", $the_address_country);
                $query7->bindParam(":status", $active);
                $query7->execute();

                if ($query7->rowCount() > 0) {

                  $the_shipping_details = $query7->fetch();

                  $the_shipping_fee_unique_id = $the_shipping_details[0];
                  $the_shipping_fee_price = (int)$the_shipping_details[1];

                  $amount_alt = $amount + $the_shipping_fee_price;

                  if ($date_added < $the_starting_date) {
                    $returnvalue = new genericClass();
                    $returnvalue->engineError = 2;
                    $returnvalue->engineErrorMessage = "Subscription hasn't commenced yet";
                  }
                  else if ($the_expiration == $active && $date_added > $the_expiry_date) {
                    $returnvalue = new genericClass();
                    $returnvalue->engineError = 2;
                    $returnvalue->engineErrorMessage = "Subscription is over";
                  }
                  else if ($the_current_no_of_persons == $the_total_no_of_persons) {
                    $returnvalue = new genericClass();
                    $returnvalue->engineError = 2;
                    $returnvalue->engineErrorMessage = "Participants occupied";
                  }
                  else if ($the_current_no_of_persons == 0) {

                    $sql4 = "UPDATE sharing_items SET completion=:completion, last_modified=:last_modified WHERE unique_id=:unique_id";
                    $query4 = $conn->prepare($sql4);
                    $query4->bindParam(":unique_id", $sharing_unique_id);
                    $query4->bindParam(":completion", $started);
                    $query4->bindParam(":last_modified", $date_added);
                    $query4->execute();

                    if ($query4->rowCount() > 0) {

                      $sharing_users_unique_id = $functions->random_str(20);

                      $sql10 = "INSERT INTO sharing_users (unique_id, user_unique_id, sharing_unique_id, shipping_fee_unique_id, pickup_location, amount, payment_method, paid, added_date, last_modified, status)
                      VALUES (:unique_id, :user_unique_id, :sharing_unique_id, :shipping_fee_unique_id, :pickup_location, :amount, :payment_method, :paid, :added_date, :last_modified, :status)";
                      $query10 = $conn->prepare($sql10);
                      $query10->bindParam(":unique_id", $sharing_users_unique_id);
                      $query10->bindParam(":user_unique_id", $user_unique_id);
                      $query10->bindParam(":sharing_unique_id", $sharing_unique_id);
                      $query10->bindParam(":shipping_fee_unique_id", $the_shipping_fee_unique_id);
                      $query10->bindParam(":pickup_location", $not_active);
                      $query10->bindParam(":amount", $amount_alt);
                      $query10->bindParam(":payment_method", $payment_method);
                      $query10->bindParam(":paid", $not_active);
                      $query10->bindParam(":added_date", $date_added);
                      $query10->bindParam(":last_modified", $date_added);
                      $query10->bindParam(":status", $active);
                      $query10->execute();

                      if ($query10->rowCount() > 0) {

                        $the_action = "Subscribed to sharing - ".$the_sharing_item_name.". Cost = ".$amount." naira, Shipping fee = ".$the_shipping_fee_price." naira, Shipping location - ".$the_address_city.", ".$the_address_state.", ".$the_address_country.". Total amount = ".$amount_alt.".";

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
                          $returnvalue = new genericClass();
                          $returnvalue->engineMessage = 1;
                          $returnvalue->resultData = $amount_alt;
                          $returnvalue->filteredData = $sharing_users_unique_id;
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
                        $returnvalue->engineErrorMessage = "Not subscribed";
                      }

                    }
                    else {
                      $returnvalue = new genericClass();
                      $returnvalue->engineError = 2;
                      $returnvalue->engineErrorMessage = "Not edited (sharing item)";
                    }

                  }
                  else {

                    $sharing_users_unique_id = $functions->random_str(20);

                    $sql10 = "INSERT INTO sharing_users (unique_id, user_unique_id, sharing_unique_id, shipping_fee_unique_id, pickup_location, amount, payment_method, paid, added_date, last_modified, status)
                    VALUES (:unique_id, :user_unique_id, :sharing_unique_id, :shipping_fee_unique_id, :pickup_location, :amount, :payment_method, :paid, :added_date, :last_modified, :status)";
                    $query10 = $conn->prepare($sql10);
                    $query10->bindParam(":unique_id", $sharing_users_unique_id);
                    $query10->bindParam(":user_unique_id", $user_unique_id);
                    $query10->bindParam(":sharing_unique_id", $sharing_unique_id);
                    $query10->bindParam(":shipping_fee_unique_id", $the_shipping_fee_unique_id);
                    $query10->bindParam(":pickup_location", $not_active);
                    $query10->bindParam(":amount", $amount_alt);
                    $query10->bindParam(":payment_method", $payment_method);
                    $query10->bindParam(":paid", $not_active);
                    $query10->bindParam(":added_date", $date_added);
                    $query10->bindParam(":last_modified", $date_added);
                    $query10->bindParam(":status", $active);
                    $query10->execute();

                    if ($query10->rowCount() > 0) {

                      $the_action = "Subscribed to sharing - ".$the_sharing_item_name.". Cost = ".$amount." naira, Shipping fee = ".$the_shipping_fee_price." naira, Shipping location - ".$the_address_city.", ".$the_address_state.", ".$the_address_country.". Total amount = ".$amount_alt.".";

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
                        $returnvalue = new genericClass();
                        $returnvalue->engineMessage = 1;
                        $returnvalue->resultData = $amount_alt;
                        $returnvalue->filteredData = $sharing_users_unique_id;
                      }
                      else {
                        $returnvalue = new genericClass();
                        $returnvalue->engineError = 2;
                        $returnvalue->engineErrorMessage = "Not subscribed";
                      }

                    }
                    else {
                      $returnvalue = new genericClass();
                      $returnvalue->engineError = 2;
                      $returnvalue->engineErrorMessage = "Not subscribed";
                    }

                  }

                }
                else {
                  $returnvalue = new genericClass();
                  $returnvalue->engineError = 2;
                  $returnvalue->engineErrorMessage = "Subscription failed, your shipping location not available";
                }

              }
              else {
                $returnvalue = new genericClass();
                $returnvalue->engineError = 2;
                $returnvalue->engineErrorMessage = "Sharing Item not found";
              }

            }
            else {
              $returnvalue = new genericClass();
              $returnvalue->engineError = 2;
              $returnvalue->engineErrorMessage = "Address Not Found";
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
