<?php

   require '../../../ng/server/config/connect_data.php';

   class getAnEventAltClass {
     public $engineMessage = 0;
     public $noData = 0;
     public $re_data;
     public $remaining_sum;
   }

   $data = json_decode(file_get_contents("php://input"), true);

   if ($connected) {

     try {
       $conn->beginTransaction();

       $table = $data['table'];

       $active = 1;

       $sql = "SELECT * FROM $table WHERE unique_id=:unique_id";
       $query = $conn->prepare($sql);
       $query->bindParam(":unique_id", $data['unique_id']);
       $query->execute();

       if ($query->rowCount() > 0) {

         $allData = $query->fetch();

         $sql2 = "SELECT SUM(total_no_of_ticketing) FROM ticketing WHERE event_unique_id=:event_unique_id AND status=:status";
         $query2 = $conn->prepare($sql2);
         $query2->bindParam(":event_unique_id", $data['unique_id']);
         $query2->bindParam(":status", $active);
         $query2->execute();

         $sum_of_ticketing = $query2->fetch();

         $returnvalue = new getAnEventAltClass();
         $returnvalue->engineMessage = 1;
         $returnvalue->re_data = $allData;
         $returnvalue->remaining_sum = $sum_of_ticketing[0];

       }
       else {
         $returnvalue = new getAnEventAltClass();
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
