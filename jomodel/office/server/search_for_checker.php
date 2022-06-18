<?php

  require '../../../ng/server/config/connect_data.php';

  class searchCheckerClass {
    public $engineMessage = 0;
    public $alreadyExists = 0;
  }

  $data = json_decode(file_get_contents("php://input"), true);

  if ($connected) {

    try {
      $conn->beginTransaction();

      $sql = "SELECT user_unique_id FROM checkers WHERE email=:email";
      $query = $conn->prepare($sql);
      $query->bindParam(":email", $data['email']);
      $query->execute();

      if ($query->rowCount() > 0) {

        $returnvalue = new searchCheckerClass();
        $returnvalue->alreadyExists = 2;

      }
      else {

        $returnvalue = new searchCheckerClass();
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
