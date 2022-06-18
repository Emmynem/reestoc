<?php

   require '../../../ng/server/config/connect_data.php';

   class getImagesClass {
     public $engineMessage = 0;
     public $noData = 0;
     public $re_data;
   }

   $data = json_decode(file_get_contents("php://input"), true);

   if ($connected) {

     try {
       $conn->beginTransaction();

       $sql = "SELECT blog_images.user_unique_id, blog_images.edit_user_unique_id, blog_images.unique_id, blog_images.image, 
       blog_images.file, blog_images.file_size, blog_images.added_date, blog_images.last_modified, blog_images.status, management.fullname as added_fullname
       FROM blog_images INNER JOIN management ON blog_images.user_unique_id = management.unique_id ORDER BY blog_images.added_date DESC";
       $query = $conn->prepare($sql);
       $query->execute();

       if ($query->rowCount() > 0) {

         $allData = $query->fetchAll();

         $returnvalue = new getImagesClass();
         $returnvalue->engineMessage = 1;
         $returnvalue->re_data = $allData;

       }
       else {
         $returnvalue = new getImagesClass();
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
