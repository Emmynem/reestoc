<?php

   require '../../../ng/server/config/connect_data.php';
   include_once "../../../ng/server/objects/functions.php";

   class savePostClass {
     public $engineMessage = 0;
   }

   $data = json_decode(file_get_contents("php://input"), true);

   $functions = new Functions();

   if ($connected) {

     try {
       $conn->beginTransaction();

       date_default_timezone_set("Africa/Lagos");
       $date_added = date("Y-m-d H:i:s");

       $active = 1;

       $hybrid_unique_id = $functions->random_str(20);

       $drafted = 1;

       $stripped = $functions->strip_text($data['post_title']);

       $sql = "INSERT INTO blog_posts (user_unique_id, edit_user_unique_id, unique_id, author_name, post_title, stripped, category_unique_id, bg_image, file, file_size, post_details, views, drafted, added_date, last_modified, status)
       VALUES (:user_unique_id, :edit_user_unique_id, :unique_id, :author_name, :post_title, :stripped, :category_unique_id, :bg_image, :file, :file_size, :post_details, :views, :drafted, :added_date, :last_modified, :status)";
       $query = $conn->prepare($sql);
       $query->bindParam(":user_unique_id", $data['user_unique_id']);
       $query->bindParam(":edit_user_unique_id", $data['edit_user_unique_id']);
       $query->bindParam(":unique_id", $hybrid_unique_id);
       $query->bindParam(":author_name", $data['author_name']);
       $query->bindParam(":post_title", $data['post_title']);
       $query->bindParam(":stripped", $stripped);
       $query->bindParam(":category_unique_id", $data['category_unique_id']);
       $query->bindParam(":bg_image", $data['bg_image']);
       $query->bindParam(":file", $data['file']);
       $query->bindParam(":file_size", $data['file_size']);
       $query->bindParam(":post_details", $data['post_details']);
       $query->bindParam(":views", $data['views']);
       $query->bindParam(":drafted", $drafted);
       $query->bindParam(":added_date", $date_added);
       $query->bindParam(":last_modified", $date_added);
       $query->bindParam(":status", $active);
       $query->execute();

       if ($query) {

         $returnvalue = new savePostClass();
         $returnvalue->engineMessage = 1;

       }

       $conn->commit();
     } catch (PDOException $e) {
       $conn->rollback();
       throw $e;
     }

     echo json_encode($returnvalue);

   }

 ?>
