<?php

   require '../../../ng/server/config/connect_data.php';

   class getNavigationClass {
     public $engineMessage = 0;
     public $noData = 0;
     public $re_data;
   }

   $data = json_decode(file_get_contents("php://input"), true);

   if ($connected) {

     try {
       $conn->beginTransaction();

       $active = 1;

       $sql = "SELECT * FROM management_navigation WHERE status=:status ORDER BY nav_title ASC";
       $query = $conn->prepare($sql);
       $query->bindParam(":status", $active);
       $query->execute();

       if ($query->rowCount() > 0) {

         $all_navigations = $query->fetchAll();

         $returnvalue = new getNavigationClass();
         $returnvalue->engineMessage = 1;
         $returnvalue->re_data = $all_navigations;
       }
       else {
         $returnvalue = new getNavigationClass();
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
