<?php

  require '../../../ng/server/config/connect_data.php';

  class addViewClass {
    public $engineMessage = 0;
    public $noData = 0;
  }

  $data = json_decode(file_get_contents("php://input"), true);

  if ($connected) {
    try {
      $conn->beginTransaction();

      $sql = "SELECT views FROM shop WHERE unique_id=:unique_id";
      $query = $conn->prepare($sql);
      $query->bindParam(':unique_id', $data['unique_id']);
      $query->execute();

      if ($query->rowCount() > 0) {
        date_default_timezone_set("Africa/Lagos");
        $date_added = date("Y-m-d H:i:s");

        $recent_view = $query->fetch();

        $the_recent_view = $recent_view[0];

        $latest_views = $the_recent_view + 1;

        $sql2 = "UPDATE shop SET views=:views, last_modified=:last_modified WHERE unique_id=:unique_id";
        $query2 = $conn->prepare($sql2);
        $query2->bindParam(':unique_id', $data['unique_id']);
        $query2->bindParam(':views', $latest_views);
        $query2->bindParam(':last_modified', $date_added);
        $query2->execute();

        $returnvalue = new addViewClass();
        $returnvalue->engineMessage = 1;

      }
      else {
        $returnvalue = new addViewClass();
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
