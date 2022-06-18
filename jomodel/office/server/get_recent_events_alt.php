<?php

   require '../../../ng/server/config/connect_data.php';

   class getRecentEventsAltClass {
     public $engineMessage = 0;
     public $noData = 0;
     public $re_data;
   }

   $data = json_decode(file_get_contents("php://input"), true);

   if ($connected) {

     try {
       $conn->beginTransaction();

       $active = 1;

       date_default_timezone_set("Africa/Lagos");
       $today = date("Y-m-d H:i:s");

       $sql = "SELECT user_unique_id, edit_user_unique_id, unique_id, display_title, event_date_start, event_time_start, event_date_end, event_time_end, event_location,
       event_image FROM events WHERE status=:status AND unique_id!=:unique_id AND event_date_start>:today ORDER BY event_date_start DESC";

       $query = $conn->prepare($sql);
       $query->bindParam(":unique_id", $data['unique_id']);
       $query->bindParam(":status", $active);
       $query->bindParam(":today", $today);
       $query->execute();

       if ($query->rowCount() > 0) {

         $allData = $query->fetchAll();

         $returnvalue = new getRecentEventsAltClass();
         $returnvalue->engineMessage = 1;
         $returnvalue->re_data = $allData;

       }
       else {
         $returnvalue = new getRecentEventsAltClass();
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
