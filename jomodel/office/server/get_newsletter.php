<?php

   require '../../../ng/server/config/connect_data.php';

   class getNewslettersClass {
     public $engineMessage = 0;
     public $noData = 0;
     public $re_data;
   }

   $data = json_decode(file_get_contents("php://input"), true);

   if ($connected) {

     try {
       $conn->beginTransaction();

       $sql = "SELECT * FROM newsletter ORDER BY added_date DESC";
       $query = $conn->prepare($sql);
       $query->execute();

       if ($query->rowCount() > 0) {

         $all_newsletters = $query->fetchAll();

         $returnvalue = new getNewslettersClass();
         $returnvalue->engineMessage = 1;
         $returnvalue->re_data = $all_newsletters;
       }
       else {
         $returnvalue = new getNewslettersClass();
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
