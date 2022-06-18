<?php

  require '../../../ng/server/config/connect_data.php';

  class addAccessClass {
    public $engineMessage = 0;
  }

  $data = json_decode(file_get_contents("php://input"), true);

  if ($connected) {

    try {
      $conn->beginTransaction();

      date_default_timezone_set("Africa/Lagos");
      $date_added = date("Y-m-d H:i:s");

      $password = "dJ2p8kSj4Mo";

      function cus_salt(){
        $david = md5(rand(100, 200));
        return $david;
      }

      $options = [

        'cost' => 12
      ];

      $lash = password_hash($password, PASSWORD_DEFAULT, $options);

      $sql = "INSERT INTO access (password, added_date, last_modified) VALUES (:password, :added_date, :last_modified)";
      $query = $conn->prepare($sql);
      $query->bindParam(":password", $lash);
      $query->bindParam(":added_date", $date_added);
      $query->bindParam(":last_modified", $date_added);
      $query->execute();

      if ($query) {
        $returnvalue = new addAccessClass();
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
