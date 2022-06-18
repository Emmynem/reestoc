<?php

  require '../../../ng/server/config/connect_data.php';
  include_once "../../../ng/server/objects/functions.php";

  class notifyClass {
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

      $sql = "INSERT INTO notifications (user_unique_id, unique_id, type, action, added_date, status)
      VALUES (:user_unique_id, :unique_id, :type, :action, :added_date, :status) ";
      $query = $conn->prepare($sql);
      $query->bindParam(':user_unique_id', $data['user_unique_id']);
      $query->bindParam(':unique_id', $hybrid_unique_id);
      $query->bindParam(':type', $data['type']);
      $query->bindParam(':action', $data['action']);
      $query->bindParam(':added_date', $date_added);
      $query->bindParam(':status', $active);
      $query->execute();

      if ($query) {
        $returnvalue  = new notifyClass();
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
