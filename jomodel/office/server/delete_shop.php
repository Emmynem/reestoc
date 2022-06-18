<?php

  require '../../../ng/server/config/connect_data.php';

  class deleteShopClass {
    public $engineMessage = 0;
    public $noData = 0;
  }

  $data = json_decode(file_get_contents("php://input"), true);

  if ($connected) {
    try {
      $conn->beginTransaction();

      date_default_timezone_set("Africa/Lagos");

      $date_added = date("Y-m-d H:i:s");

      $sql = "SELECT * FROM shop WHERE unique_id=:unique_id";
      $query = $conn->prepare($sql);
      $query->bindParam(":unique_id", $data['unique_id']);
      $query->execute();

      if ($query->rowCount() > 0) {
        $sql2 = "DELETE FROM shop WHERE unique_id=:unique_id";
        $query2 = $conn->prepare($sql2);
        $query2->bindParam(':unique_id', $data['unique_id']);
        $query2->execute();

        if ($query2) {

          $data_received = $data['product_image'];
          $dir = $_SERVER['DOCUMENT_ROOT']."/cerotics_store/jomodel";
          unlink($dir."/".$data_received);

          $returnvalue = new deleteShopClass();
          $returnvalue->engineMessage = 1;
        }

      }
      else {
        $returnvalue = new deleteShopClass();
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
