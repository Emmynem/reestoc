<?php

  require '../../../ng/server/config/connect_data.php';

  class restorePostClass {
    public $engineMessage = 0;
    public $noData = 0;
  }

  $data = json_decode(file_get_contents("php://input"), true);

  if ($connected) {
    try {
      $conn->beginTransaction();

      date_default_timezone_set("Africa/Lagos");

      $date_added = date("Y-m-d H:i:s");

      $table = $data['table'];

      $sql = "SELECT * FROM $table WHERE unique_id=:unique_id";
      $query = $conn->prepare($sql);
      $query->bindParam(":unique_id", $data['unique_id']);
      $query->execute();

      if ($query->rowCount() > 0) {
        $sql2 = "UPDATE $table SET edit_user_unique_id=:edit_user_unique_id, status=:status, last_modified=:last_modified WHERE unique_id=:unique_id";
        $query2 = $conn->prepare($sql2);
        $query2->bindParam(':edit_user_unique_id', $data['edit_user_unique_id']);
        $query2->bindParam(':unique_id', $data['unique_id']);
        $query2->bindParam(':status', $data['status']);
        $query2->bindParam(':last_modified', $date_added);
        $query2->execute();

        if ($query2) {
          $returnvalue = new restorePostClass();
          $returnvalue->engineMessage = 1;
        }

      }
      else {
        $returnvalue = new restorePostClass();
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
