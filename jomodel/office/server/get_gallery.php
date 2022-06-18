<?php

   require '../../../ng/server/config/connect_data.php';

   class getGalleryClass {
     public $engineMessage = 0;
     public $noData = 0;
     public $re_data;
   }

   $data = json_decode(file_get_contents("php://input"), true);

   if ($connected) {

     try {
       $conn->beginTransaction();

       $sql = "SELECT gallery.user_unique_id, gallery.edit_user_unique_id, gallery.unique_id, gallery.image, gallery.added_date, gallery.last_modified, gallery.status, management.fullname as added_fullname FROM gallery INNER JOIN management ON gallery.user_unique_id = management.unique_id ORDER BY gallery.added_date DESC";
       $query = $conn->prepare($sql);
       $query->execute();

       if ($query->rowCount() > 0) {

         $allData = $query->fetchAll();

         $returnvalue = new getGalleryClass();
         $returnvalue->engineMessage = 1;
         $returnvalue->re_data = $allData;

       }
       else {
         $returnvalue = new getGalleryClass();
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
