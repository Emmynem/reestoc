<?php

  require '../../../ng/server/config/connect_data.php';
  include_once "../../../ng/server/objects/functions.php";

  class addTicketCategoryClass {
    public $engineMessage = 0;
  }

  $data = json_decode(file_get_contents("php://input"), true);

  $functions = new Functions();

  if ($connected) {

    try {
      $conn->beginTransaction();

      date_default_timezone_set("Africa/Lagos");
      $date_added = date("Y-m-d H:i:s");

      $active = 1;

      $hybrid_unique_id = $functions->random_str(20);

      $sql = "INSERT INTO ticketing (user_unique_id, edit_user_unique_id, event_unique_id, unique_id, ticket_name, ticket_description, price, total_no_of_ticketing, total_no_of_ticketing_left, added_date, last_modified, status)
      VALUES (:user_unique_id, :edit_user_unique_id, :event_unique_id, :unique_id, :ticket_name, :ticket_description, :price, :total_no_of_ticketing, :total_no_of_ticketing_left, :added_date, :last_modified, :status)";
      $query = $conn->prepare($sql);
      $query->bindParam(":user_unique_id", $data['user_unique_id']);
      $query->bindParam(":edit_user_unique_id", $data['edit_user_unique_id']);
      $query->bindParam(":event_unique_id", $data['event_unique_id']);
      $query->bindParam(":unique_id", $hybrid_unique_id);
      $query->bindParam(":ticket_name", $data['ticket_name']);
      $query->bindParam(":ticket_description", $data['ticket_description']);
      $query->bindParam(":price", $data['price']);
      $query->bindParam(":total_no_of_ticketing", $data['total_no_of_ticketing']);
      $query->bindParam(":total_no_of_ticketing_left", $data['total_no_of_ticketing']);
      $query->bindParam(":added_date", $date_added);
      $query->bindParam(":last_modified", $date_added);
      $query->bindParam(":status", $active);
      $query->execute();

      if ($query) {

        $returnvalue = new addTicketCategoryClass();
        $returnvalue->engineMessage = 1;

      }

      $conn->commit();
    } catch (PDOException $e) {
    $conn->rollback();
    throw $e;
    }

    echo json_encode($returnvalue);

  }

?>
