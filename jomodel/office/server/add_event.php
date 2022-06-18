<?php

  require '../../../ng/server/config/connect_data.php';
  include_once "../../../ng/server/objects/functions.php";

  class addEventClass {
    public $engineMessage = 0;
    public $eventAlreadyExists = 0;
  }

  $data = json_decode(file_get_contents("php://input"), true);

  $functions = new Functions();

  if ($connected) {

    try {
      $conn->beginTransaction();

      $sql2 = "SELECT unique_id FROM events WHERE display_title=:display_title OR event_name=:event_name";
      $query2 = $conn->prepare($sql2);
      $query2->bindParam(':display_title', $data['display_title']);
      $query2->bindParam(':event_name', $data['event_name']);
      $query2->execute();

      if ($query2->rowCount() > 0) {
        $returnvalue = new addEventClass();
        $returnvalue->eventAlreadyExists = 2;
      }
      else {

        date_default_timezone_set("Africa/Lagos");
        $date_added = date("Y-m-d H:i:s");

        $active = 1;

        $hybrid_unique_id = $functions->random_str(20);

        $drafted = 0;

        $sql = "INSERT INTO events (user_unique_id, edit_user_unique_id, unique_id, display_title, stripped, event_name, event_date_start, event_time_start, event_date_end, event_time_end, event_location, event_image, event_categories, event_tags, event_venue, event_organizers, total_no_of_tickets, tickets_left, drafted, added_date, last_modified, status)
        VALUES (:user_unique_id, :edit_user_unique_id, :unique_id, :display_title, :stripped, :event_name, :event_date_start, :event_time_start, :event_date_end, :event_time_end, :event_location, :event_image, :event_categories, :event_tags, :event_venue, :event_organizers, :total_no_of_tickets, :tickets_left, :drafted, :added_date, :last_modified, :status)";
        $query = $conn->prepare($sql);
        $query->bindParam(":user_unique_id", $data['user_unique_id']);
        $query->bindParam(":edit_user_unique_id", $data['edit_user_unique_id']);
        $query->bindParam(":unique_id", $hybrid_unique_id);
        $query->bindParam(":display_title", $data['display_title']);
        $query->bindParam(":stripped", $data['stripped']);
        $query->bindParam(":event_name", $data['event_name']);
        $query->bindParam(":event_date_start", $data['event_date_start']);
        $query->bindParam(":event_time_start", $data['event_time_start']);
        $query->bindParam(":event_date_end", $data['event_date_end']);
        $query->bindParam(":event_time_end", $data['event_time_end']);
        $query->bindParam(":event_location", $data['event_location']);
        $query->bindParam(":event_image", $data['event_image']);
        $query->bindParam(":event_categories", $data['event_categories']);
        $query->bindParam(":event_tags", $data['event_tags']);
        $query->bindParam(":event_venue", $data['event_venue']);
        $query->bindParam(":event_organizers", $data['event_organizers']);
        $query->bindParam(":total_no_of_tickets", $data['total_no_of_tickets']);
        $query->bindParam(":tickets_left", $data['tickets_left']);
        $query->bindParam(":drafted", $drafted);
        $query->bindParam(":added_date", $date_added);
        $query->bindParam(":last_modified", $date_added);
        $query->bindParam(":status", $active);
        $query->execute();

        if ($query) {

          $returnvalue = new addEventClass();
          $returnvalue->engineMessage = 1;

        }

      }

      $conn->commit();
    } catch (PDOException $e) {
    $conn->rollback();
    throw $e;
    }

    echo json_encode($returnvalue);

  }

?>
