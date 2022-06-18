<?php

   require '../../../ng/server/config/connect_data.php';

   class getFilesClass {
     public $engineMessage = 0;
     public $noData = 0;
     public $re_data;
   }

   $data = json_decode(file_get_contents("php://input"), true);

   if ($connected) {

     try {
       $conn->beginTransaction();

       $sql = "SELECT files.user_unique_id, files.unique_id, files.save_as_name, files.file_name, files.file_extension, files.file_size, files.added_date, files.last_modified, files.status, management.fullname as added_fullname
       FROM files INNER JOIN management ON files.user_unique_id = management.unique_id ORDER BY files.added_date DESC";
       $query = $conn->prepare($sql);
       $query->execute();

       if ($query->rowCount() > 0) {

         $allData = $query->fetchAll();

         $returnvalue = new getFilesClass();
         $returnvalue->engineMessage = 1;
         $returnvalue->re_data = $allData;

       }
       else {
         $returnvalue = new getFilesClass();
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
