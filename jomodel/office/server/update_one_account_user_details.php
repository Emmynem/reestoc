<?php

  require '../../../ng/server/config/connect_data.php';

  class updateOneAccountUserDetailsClass {
    public $engineMessage = 0;
  }

  $data = json_decode(file_get_contents("php://input"), true);

  if ($connected) {
    try {
      $conn->beginTransaction();

      date_default_timezone_set("Africa/Lagos");
      $date_added = date("Y-m-d H:i:s");

      $user_role = $data['user_role'];

      if ($user_role == 1) {
        $sql2 = "UPDATE management SET fullname=:fullname, phone_number=:phone_number WHERE user_unique_id=:user_unique_id AND email=:email";
        $query2 = $conn->prepare($sql2);
        $query2->bindParam(':user_unique_id', $data['user_unique_id']);
        $query2->bindParam(':email', $data['email']);
        $query2->bindParam(':fullname', $data['fullname']);
        $query2->bindParam(':phone_number', $data['phone_number']);
        $query2->execute();

        if ($query2) {

          $returnvalue = new updateOneAccountUserDetailsClass();
          $returnvalue->engineMessage = 1;

        }
      }
      else {

        $sql2 = "UPDATE management SET fullname=:fullname, phone_number=:phone_number WHERE user_unique_id=:user_unique_id AND email=:email";
        $query2 = $conn->prepare($sql2);
        $query2->bindParam(':user_unique_id', $data['user_unique_id']);
        $query2->bindParam(':email', $data['email']);
        $query2->bindParam(':fullname', $data['fullname']);
        $query2->bindParam(':phone_number', $data['phone_number']);
        $query2->execute();

        if ($query2) {

          $returnvalue = new updateOneAccountUserDetailsClass();
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
