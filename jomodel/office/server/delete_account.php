<?php

  require '../../../ng/server/config/connect_data.php';

  class deleteAccountClass {
    public $engineMessage = 0;
    public $noData = 0;
  }

  $data = json_decode(file_get_contents("php://input"), true);

  if ($connected) {
    try {
      $conn->beginTransaction();

      date_default_timezone_set("Africa/Lagos");

      $date_added = date("Y-m-d H:i:s");

      $active = 1;

      $sql = "SELECT * FROM account WHERE unique_id=:unique_id AND acc_type=:acc_type AND status=:status";
      $query = $conn->prepare($sql);
      $query->bindParam(":unique_id", $data['unique_id']);
      $query->bindParam(":acc_type", $data['acc_type']);
      $query->bindParam(":status", $active);
      $query->execute();

      if ($query->rowCount() > 0) {
        $sql2 = "UPDATE account SET edit_user_unique_id=:edit_user_unique_id, status=:status, last_modified=:last_modified WHERE unique_id=:unique_id AND acc_type=:acc_type";
        $query2 = $conn->prepare($sql2);
        $query2->bindParam(':edit_user_unique_id', $data['user_unique_id']);
        $query2->bindParam(':unique_id', $data['unique_id']);
        $query2->bindParam(':acc_type', $data['acc_type']);
        $query2->bindParam(':status', $data['status']);
        $query2->bindParam(':last_modified', $date_added);
        $query2->execute();

        $returnvalue = new deleteAccountClass();
        $returnvalue->engineMessage = 1;

      }
      else {
        $returnvalue = new deleteAccountClass();
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
