<?php

  require '../../../ng/server/config/connect_data.php';

  class editTicketCategoryClass {
    public $engineMessage = 0;
    public $noData = 0;
  }

  $data = json_decode(file_get_contents("php://input"), true);

  if ($connected) {

    try {
      $conn->beginTransaction();

      $sql2 = "SELECT unique_id FROM ticketing WHERE event_unique_id=:event_unique_id AND unique_id=:unique_id";
      $query2 = $conn->prepare($sql2);
      $query2->bindParam(":event_unique_id", $data['event_unique_id']);
      $query2->bindParam(":unique_id", $data['unique_id']);
      $query2->execute();

      if ($query2->rowCount() > 0) {

        date_default_timezone_set("Africa/Lagos");
        $date_added = date("Y-m-d H:i:s");

        $active = 1;

        $sql = "UPDATE ticketing SET edit_user_unique_id=:edit_user_unique_id, ticket_name=:ticket_name, ticket_description=:ticket_description, price=:price,
        total_no_of_ticketing=:total_no_of_ticketing, total_no_of_ticketing_left=:total_no_of_ticketing_left, last_modified=:last_modified, status=:status WHERE event_unique_id=:event_unique_id AND unique_id=:unique_id";
        $query = $conn->prepare($sql);
        $query->bindParam(":edit_user_unique_id", $data['edit_user_unique_id']);
        $query->bindParam(":event_unique_id", $data['event_unique_id']);
        $query->bindParam(":unique_id", $data['unique_id']);
        $query->bindParam(":ticket_name", $data['ticket_name']);
        $query->bindParam(":ticket_description", $data['ticket_description']);
        $query->bindParam(":price", $data['price']);
        $query->bindParam(":total_no_of_ticketing", $data['total_no_of_ticketing']);
        $query->bindParam(":total_no_of_ticketing_left", $data['total_no_of_ticketing']);
        $query->bindParam(":last_modified", $date_added);
        $query->bindParam(":status", $active);
        $query->execute();

        if ($query) {

          $returnvalue = new editTicketCategoryClass();
          $returnvalue->engineMessage = 1;

        }

      }
      else{
        $returnvalue = new editTicketCategoryClass();
        $returnvalue->noData = 2;
      }

      $conn->commit();
    } catch (PDOException $e) {
    $conn->rollback();
    throw $e;
    }

    echo json_encode($returnvalue);

  }

?>
