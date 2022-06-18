<?php

   require '../../../ng/server/config/connect_data.php';

   class getAnEnquiryClass {
     public $engineMessage = 0;
     public $noData = 0;
     public $re_data;
   }

   $data = json_decode(file_get_contents("php://input"), true);

   if ($connected) {

     try {
       $conn->beginTransaction();

       $table = $data['table'];

       $sql = "SELECT * FROM $table WHERE unique_id=:unique_id ORDER BY added_date DESC";
       $query = $conn->prepare($sql);
       $query->bindParam(":unique_id", $data['unique_id']);
       $query->execute();

       if ($query->rowCount() > 0) {

         $allData = $query->fetch();

         $returnvalue = new getAnEnquiryClass();
         $returnvalue->engineMessage = 1;
         $returnvalue->re_data = $allData;

       }
       else {
         $returnvalue = new getAnEnquiryClass();
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
