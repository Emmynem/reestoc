<?php

  require '../../../ng/server/config/connect_data.php';

  class editUserProfileImageClass {
    public $engineMessage = 0;
    public $noUser = 0;
  }

  $data = json_decode(file_get_contents("php://input"), true);

  if ($connected) {
    try {
      $conn->beginTransaction();

      $data['id'] = 1;

      $sql = "SELECT unique_id FROM profile";
      $query = $conn->prepare($sql);
      $query->execute();

      if ($query->rowCount() > 0) {
        date_default_timezone_set("Africa/Lagos");
        $date_added = date("Y-m-d H:i:s");

        $sql2 = "UPDATE profile SET image=:image, last_modified=:last_modified WHERE id=:id";
        $query2 = $conn->prepare($sql2);
        $query2->bindParam(':id', $data['id']);
        $query2->bindParam(':image', $data['image']);
        $query2->bindParam(':last_modified', $date_added);
        $query2->execute();

        $returnvalue = new editUserProfileImageClass();
        $returnvalue->engineMessage = 1;

      }
      else {
        $returnvalue = new editUserProfileImageClass();
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
