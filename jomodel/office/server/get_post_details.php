<?php

   require '../../../ng/server/config/connect_data.php';

   class getPostDetailsClass {
     public $engineMessage = 0;
     public $noData = 0;
     public $re_data;
   }

   $data = json_decode(file_get_contents("php://input"), true);

   if ($connected) {

     try {
       $conn->beginTransaction();

       $sql = "SELECT * FROM blog_posts WHERE unique_id=:unique_id";
       $query = $conn->prepare($sql);
       $query->bindParam(":unique_id", $data['unique_id']);
       $query->execute();

       if ($query->rowCount() > 0) {

         $post_details = $query->fetch();

         $returnvalue = new getPostDetailsClass();
         $returnvalue->engineMessage = 1;
         $returnvalue->re_data = $post_details;

       }
       else {
         $returnvalue = new getPostDetailsClass();
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
