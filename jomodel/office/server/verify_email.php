<?php

  require '../../../ng/server/config/connect_data.php';

  class verifyEmailClass {
    public $engineMessage = 0;
    public $noUser = 0;
    public $alreadyVerified = 0;
  }

  $data = json_decode(file_get_contents("php://input"), true);

  if ($connected) {
    try {
      $conn->beginTransaction();

      $sql = "SELECT access FROM users_data WHERE user_unique_id=:user_unique_id";
      $query = $conn->prepare($sql);
      $query->bindParam(":user_unique_id", $data['user_unique_id']);
      $query->execute();

      $sql2 = "SELECT access FROM users_data WHERE user_unique_id=:user_unique_id";
      $query2 = $conn->prepare($sql2);
      $query2->bindParam(":user_unique_id", $data['user_unique_id']);
      $query2->execute();

      $the_access = $query2->fetch();

      $access = $the_access[0];

      if ($query->rowCount() > 0 && $access == 1) {
        $returnvalue = new verifyEmailClass();
        $returnvalue->alreadyVerified = 3;
      }
      else if ($query->rowCount() > 0 && $access != 1) {
        date_default_timezone_set("Africa/Lagos");
        $date_added = date("Y-m-d H:i:s");

        $new_access = 1;

        $sql2 = "UPDATE users_data SET access=:access, last_modified=:last_modified WHERE user_unique_id=:user_unique_id";
        $query2 = $conn->prepare($sql2);
        $query2->bindParam(':user_unique_id', $data['user_unique_id']);
        $query2->bindParam(':access', $new_access);
        $query2->bindParam(':last_modified', $date_added);
        $query2->execute();

        $returnvalue = new verifyEmailClass();
        $returnvalue->engineMessage = 1;

      }
      else {
        $returnvalue = new verifyEmailClass();
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
