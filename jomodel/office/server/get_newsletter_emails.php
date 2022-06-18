<?php

   require '../../../ng/server/config/connect_data.php';

   class getNewslettersEmailsClass {
     public $engineMessage = 0;
     public $noData = 0;
     public $re_data;
   }

   $data = json_decode(file_get_contents("php://input"), true);

   if ($connected) {

     try {
       $conn->beginTransaction();

       $sql = "SELECT email FROM newsletter";
       $query = $conn->prepare($sql);
       $query->execute();

       if ($query->rowCount() > 0) {

         $all_newsletters_emails = $query->fetchAll();

         $returnvalue = new getNewslettersEmailsClass();
         $returnvalue->engineMessage = 1;
         $returnvalue->re_data = $all_newsletters_emails;
       }
       else {
         $returnvalue = new getNewslettersEmailsClass();
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
