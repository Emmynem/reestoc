<?php

   require '../../../ng/server/config/connect_data.php';

   class getAnEventClass {
     public $engineMessage = 0;
     public $noData = 0;
     public $re_data;
   }

   $data = json_decode(file_get_contents("php://input"), true);

   if ($connected) {

     try {
       $conn->beginTransaction();

       $table = $data['table'];

       $sql = "SELECT * FROM $table WHERE unique_id=:unique_id";
       $query = $conn->prepare($sql);
       $query->bindParam(":unique_id", $data['unique_id']);
       $query->execute();

       if ($query->rowCount() > 0) {

         $allData = $query->fetch();

         $returnvalue = new getAnEventClass();
         $returnvalue->engineMessage = 1;
         $returnvalue->re_data = $allData;

       }
       else {
         $returnvalue = new getAnEventClass();
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
