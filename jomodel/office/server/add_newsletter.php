<?php

  require '../../../ng/server/config/connect_data.php';
  include_once "../../../ng/server/objects/functions.php";

  class addNewsletterClass {
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

      // $email = "Imagick@gmail.com";

      $sql = "INSERT INTO newsletter (unique_id, email, added_date, last_modified, status)
      VALUES (:unique_id, :email, :added_date, :last_modified, :status)";
      $query = $conn->prepare($sql);
      $query->bindParam(":unique_id", $hybrid_unique_id);
      $query->bindParam(":email", $data['email']);
      $query->bindParam(":added_date", $date_added);
      $query->bindParam(":last_modified", $date_added);
      $query->bindParam(":status", $active);
      $query->execute();

      if ($query) {

        $returnvalue = new addNewsletterClass();
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
