<?php

   require '../../../ng/server/config/connect_data.php';
   include_once "../../../ng/server/objects/functions.php";

   class editCategoryClass {
     public $engineMessage = 0;
     public $alreadyExists = 0;
   }

   $data = json_decode(file_get_contents("php://input"), true);

   $functions = new Functions();

   if ($connected) {

     try {
       $conn->beginTransaction();

       date_default_timezone_set("Africa/Lagos");
       $date_added = date("Y-m-d H:i:s");

       $sql2 = "SELECT unique_id FROM blog_categories WHERE category=:category";
       $query2 = $conn->prepare($sql2);
       $query2->bindParam(":category", $data['category']);
       $query2->execute();

       if ($query2->rowCount() > 0) {

         $returnvalue = new editCategoryClass();
         $returnvalue->alreadyExists = 2;

       }
       else {

         $stripped = $functions->strip_text($data['category']);

         $sql = "UPDATE blog_categories SET edit_user_unique_id=:edit_user_unique_id, category=:category, stripped=:stripped, last_modified=:last_modified WHERE unique_id=:unique_id";
         $query = $conn->prepare($sql);
         $query->bindParam(":edit_user_unique_id", $data['edit_user_unique_id']);
         $query->bindParam(":unique_id", $data['unique_id']);
         $query->bindParam(":category", $data['category']);
         $query->bindParam(":stripped", $stripped);
         $query->bindParam(":last_modified", $date_added);
         $query->execute();

         if ($query) {

           $returnvalue = new editCategoryClass();
           $returnvalue->engineMessage = 1;

         }

       }


       $conn->commit();
     } catch (PDOException $e) {
       $conn->rollback();
       throw $e;
     }

     echo json_encode($returnvalue);

   }

 ?>
