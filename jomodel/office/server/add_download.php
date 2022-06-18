<?php

  require '../../../ng/server/config/connect_data.php';

  class addDownloadClass {
    public $engineMessage = 0;
    public $noData = 0;
  }

  $data = json_decode(file_get_contents("php://input"), true);

  if ($connected) {
    try {
      $conn->beginTransaction();

      $sql = "SELECT downloads FROM magazine WHERE unique_id=:unique_id";
      $query = $conn->prepare($sql);
      $query->bindParam(':unique_id', $data['unique_id']);
      $query->execute();

      if ($query->rowCount() > 0) {
        date_default_timezone_set("Africa/Lagos");
        $date_added = date("Y-m-d H:i:s");

        $recent_download = $query->fetch();

        $the_recent_download = $recent_download[0];

        $latest_downloads = $the_recent_download + 1;

        $sql2 = "UPDATE magazine SET downloads=:downloads, last_modified=:last_modified WHERE unique_id=:unique_id";
        $query2 = $conn->prepare($sql2);
        $query2->bindParam(':unique_id', $data['unique_id']);
        $query2->bindParam(':downloads', $latest_downloads);
        $query2->bindParam(':last_modified', $date_added);
        $query2->execute();

        $returnvalue = new addDownloadClass();
        $returnvalue->engineMessage = 1;

      }
      else {
        $returnvalue = new addDownloadClass();
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
