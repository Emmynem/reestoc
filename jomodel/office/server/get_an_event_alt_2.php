<?php

   require '../../../ng/server/config/connect_data.php';

   class getAnEventAlt2Class {
     public $engineMessage = 0;
     public $noData = 0;
     public $re_data;
     public $ticket_details;
     public $remaining_sum;
     public $total_remaining_sum;
   }

   $data = json_decode(file_get_contents("php://input"), true);

   if ($connected) {

     try {
       $conn->beginTransaction();

       $table = $data['table'];

       $active = 1;

       $sql = "SELECT * FROM $table WHERE unique_id=:unique_id";
       $query = $conn->prepare($sql);
       $query->bindParam(":unique_id", $data['event_unique_id']);
       $query->execute();

       if ($query->rowCount() > 0) {

         $allData = $query->fetch();

         $sql2 = "SELECT SUM(total_no_of_ticketing_left) FROM ticketing WHERE event_unique_id=:event_unique_id AND unique_id=:unique_id AND status=:status";
         $query2 = $conn->prepare($sql2);
         $query2->bindParam(":event_unique_id", $data['event_unique_id']);
         $query2->bindParam(":unique_id", $data['unique_id']);
         $query2->bindParam(":status", $active);
         $query2->execute();

         $sum_of_ticketing = $query2->fetch();

         $sql4 = "SELECT SUM(total_no_of_ticketing) FROM ticketing WHERE event_unique_id=:event_unique_id AND status=:status";
         $query4 = $conn->prepare($sql4);
         $query4->bindParam(":event_unique_id", $data['event_unique_id']);
         $query4->bindParam(":status", $active);
         $query4->execute();

         $total_sum_of_ticketing = $query4->fetch();

         $sql3 = "SELECT * FROM ticketing WHERE event_unique_id=:event_unique_id AND unique_id=:unique_id AND status=:status";
         $query3 = $conn->prepare($sql3);
         $query3->bindParam(":event_unique_id", $data['event_unique_id']);
         $query3->bindParam(":unique_id", $data['unique_id']);
         $query3->bindParam(":status", $active);
         $query3->execute();

         $ticket_details = $query3->fetch();

         $returnvalue = new getAnEventAlt2Class();
         $returnvalue->engineMessage = 1;
         $returnvalue->re_data = $allData;
         $returnvalue->ticket_details = $ticket_details;
         $returnvalue->remaining_sum = $sum_of_ticketing[0];
         $returnvalue->total_remaining_sum = $total_sum_of_ticketing[0];

       }
       else {
         $returnvalue = new getAnEventAlt2Class();
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
