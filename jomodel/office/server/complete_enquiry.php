<?php

  require '../../../ng/server/config/connect_data.php';

  class completeEnquiryClass {
    public $engineMessage = 0;
    public $noData = 0;
    public $re_data;
  }

  $data = json_decode(file_get_contents("php://input"), true);

  if ($connected) {
    try {
      $conn->beginTransaction();

      date_default_timezone_set("Africa/Lagos");

      $date_added = date("Y-m-d H:i:s");

      $table = $data['table'];

      $sql = "SELECT * FROM $table WHERE unique_id=:unique_id";
      $query = $conn->prepare($sql);
      $query->bindParam(":unique_id", $data['unique_id']);
      $query->execute();
      $the_data = $query->fetch();

      if ($query->rowCount() > 0) {
        $sql2 = "UPDATE $table SET enquiry_status=:enquiry_status, last_modified=:last_modified WHERE unique_id=:unique_id";
        $query2 = $conn->prepare($sql2);
        $query2->bindParam(':unique_id', $data['unique_id']);
        $query2->bindParam(':enquiry_status', $data['enquiry_status']);
        $query2->bindParam(':last_modified', $date_added);
        $query2->execute();

        if ($query2) {
          $returnvalue = new completeEnquiryClass();
          $returnvalue->engineMessage = 1;
          $returnvalue->re_data = $the_data;
        }

      }
      else {
        $returnvalue = new completeEnquiryClass();
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
