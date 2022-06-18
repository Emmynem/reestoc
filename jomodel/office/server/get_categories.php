<?php

   require '../../../ng/server/config/connect_data.php';

   class getCategoriesClass {
     public $engineMessage = 0;
     public $noData = 0;
     public $re_data;
   }

   $data = json_decode(file_get_contents("php://input"), true);

   if ($connected) {

     try {
       $conn->beginTransaction();

       $sql = "SELECT blog_categories.user_unique_id, blog_categories.edit_user_unique_id, blog_categories.unique_id, blog_categories.category, blog_categories.added_date, blog_categories.last_modified, blog_categories.status, management.fullname as added_fullname
       FROM blog_categories INNER JOIN management ON blog_categories.user_unique_id = management.unique_id ORDER BY blog_categories.added_date DESC";
       $query = $conn->prepare($sql);
       $query->execute();

       if ($query->rowCount() > 0) {

         $all_blog_categories = $query->fetchAll();

         $returnvalue = new getCategoriesClass();
         $returnvalue->engineMessage = 1;
         $returnvalue->re_data = $all_blog_categories;
       }
       else {
         $returnvalue = new getCategoriesClass();
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
