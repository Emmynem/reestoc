<?php

   require '../../../ng/server/config/connect_data.php';

   class getPostDetailsClass {
     public $engineMessage = 0;
     public $noData = 0;
     public $re_data;
     public $cat_data;
   }

   $data = json_decode(file_get_contents("php://input"), true);

   if ($connected) {

     try {
       $conn->beginTransaction();

       $sql = "SELECT * FROM posts WHERE category_unique_id=:category_unique_id AND unique_id!=:unique_id";
       $query = $conn->prepare($sql);
       $query->bindParam(":category_unique_id", $data['category_unique_id']);
       $query->bindParam(":unique_id", $data['unique_id']);
       $query->execute();

       if ($query->rowCount() > 0) {

         $post_details = $query->fetchAll();

         $category_unique_id = $data['category_unique_id'];

         $sql2 = "SELECT category FROM categories WHERE unique_id=:unique_id";
         $query2 = $conn->prepare($sql2);
         $query2->bindParam(":unique_id", $category_unique_id);
         $query2->execute();

         $cat_details = $query2->fetch();

         if ($query2) {

           $returnvalue = new getPostDetailsClass();
           $returnvalue->engineMessage = 1;
           $returnvalue->re_data = $post_details;
           $returnvalue->cat_data = $cat_details[0];

         }

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
