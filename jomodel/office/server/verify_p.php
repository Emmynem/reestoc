<?php

  require '../../../ng/server/config/connect_data.php';

  class verifyPasswordClass {
    public $engineMessage = 0;
    public $notVerified = 0;
    public $noUser = 0;
  }

  $data = json_decode(file_get_contents("php://input"), true);

  if ($connected) {
    try {
      $conn->beginTransaction();

      $active = 1;
      $password = $data['oldPassword'];

      $sql = "SELECT user_unique_id FROM management WHERE user_unique_id=:user_unique_id AND status=:status";
      $query = $conn->prepare($sql);
      $query->bindParam(':user_unique_id', $data['user_unique_id']);
      $query->bindParam(":status", $active);
      $query->execute();

      if ($query->rowCount() > 0) {

        $sql2 = "SELECT password FROM management WHERE user_unique_id=:user_unique_id AND status=:status";
        $query2 = $conn->prepare($sql2);
        $query2->bindParam(':user_unique_id', $data['user_unique_id']);
        $query2->bindParam(":status", $active);
        $query2->execute();
        $flight = $query2->fetch();

        $jfk = $flight[0];

        if (password_verify($password, $jfk)) {
          $returnvalue = new verifyPasswordClass();
          $returnvalue->engineMessage = 1;
        }
        else {
          $returnvalue = new verifyPasswordClass();
          $returnvalue->notVerified = 3;
        }

      }
      else {
        $returnvalue = new verifyPasswordClass();
        $returnvalue->noUser = 2;
      }

      $conn->commit();
    } catch (PDOException $e) {
      $conn->rollback();
      throw $e;
    }

    echo json_encode($returnvalue);
  }

?>
