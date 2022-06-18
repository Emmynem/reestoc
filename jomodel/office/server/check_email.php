<?php

  require '../../../ng/server/config/connect_data.php';

  class checkEmailClass {
    public $engineMessage = 0;
    public $noEmail = 0;
    public $userName;
  }

  $data = json_decode(file_get_contents("php://input"), true);

  if ($connected) {
    try {
      $conn->beginTransaction();

      $active = 1;

      $sql = "SELECT image FROM users_data WHERE email=:email AND status=:status";
      $query = $conn->prepare($sql);
      $query->bindParam(':email', $data['email']);
      $query->bindParam(":status", $active);
      $query->execute();

      if ($query->rowCount() > 0) {
        $sql2 = "SELECT fullname FROM users_data WHERE email=:email AND status=:status";
        $query2 = $conn->prepare($sql2);
        $query2->bindParam(":email", $data['email']);
        $query2->bindParam(":status", $active);
        $query2->execute();
        $allUserName = $query2->fetchAll();

        $returnvalue = new checkEmailClass();
        $returnvalue->engineMessage = 1;
        $returnvalue->userName = $allUserName;

      }
      else {
        $returnvalue = new checkEmailClass();
        $returnvalue->noEmail = 2;

      }

      $conn->commit();
    } catch (PDOException $e) {
      $conn->rollback();
      throw $e;
    }

    echo json_encode($returnvalue);
  }

?>
