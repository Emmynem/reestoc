<?php

   require '../../../ng/server/config/connect_data.php';
   include_once "../../../ng/server/objects/functions.php";

   class editPostClass {
     public $engineMessage = 0;
     public $noData = 0;
   }

   $data = json_decode(file_get_contents("php://input"), true);

   $functions = new Functions();

   if ($connected) {

     try {
       $conn->beginTransaction();

       date_default_timezone_set("Africa/Lagos");
       $date_added = date("Y-m-d H:i:s");

       $sql2 = "SELECT * FROM blog_posts WHERE unique_id=:unique_id";
       $query2 = $conn->prepare($sql2);
       $query2->bindParam(":unique_id", $data['unique_id']);
       $query2->execute();

       if ($query2->rowCount() > 0) {

         $stripped = $functions->strip_text($data['post_title']);

         $sql = "UPDATE blog_posts SET edit_user_unique_id=:edit_user_unique_id, author_name=:author_name, post_title=:post_title, stripped=:stripped, category_unique_id=:category_unique_id,
         bg_image=:bg_image, file=:file, file_size=:file_size, post_details=:post_details, last_modified=:last_modified WHERE unique_id=:unique_id";
         $query = $conn->prepare($sql);
         $query->bindParam(":edit_user_unique_id", $data['edit_user_unique_id']);
         $query->bindParam(":unique_id", $data['unique_id']);
         $query->bindParam(":author_name", $data['author_name']);
         $query->bindParam(":post_title", $data['post_title']);
         $query->bindParam(":stripped", $stripped);
         $query->bindParam(":category_unique_id", $data['category_unique_id']);
         $query->bindParam(":bg_image", $data['bg_image']);
         $query->bindParam(":file", $data['file']);
         $query->bindParam(":file_size", $data['file_size']);
         $query->bindParam(":post_details", $data['post_details']);
         $query->bindParam(":last_modified", $date_added);
         $query->execute();

         if ($query) {

           $returnvalue = new editPostClass();
           $returnvalue->engineMessage = 1;

         }

       }
       else {

         $returnvalue = new editPostClass();
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
