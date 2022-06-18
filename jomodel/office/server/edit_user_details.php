<?php

   require '../../../ng/server/config/connect_data.php';

   class editUserDetailsClass {
     public $engineMessage = 0;
     public $noData = 0;
   }

   $data = json_decode(file_get_contents("php://input"), true);

   if ($connected) {

     try {
       $conn->beginTransaction();

       date_default_timezone_set("Africa/Lagos");
       $date_added = date("Y-m-d H:i:s");

       $sql2 = "SELECT * FROM management WHERE user_unique_id=:user_unique_id";
       $query2 = $conn->prepare($sql2);
       $query2->bindParam(":user_unique_id", $data['user_unique_id']);
       $query2->execute();

       if ($query2->rowCount() > 0) {

         $sql = "UPDATE management SET edit_user_unique_id=:edit_user_unique_id, user_role=:user_role, access=:access, last_modified=:last_modified WHERE user_unique_id=:user_unique_id";
         $query = $conn->prepare($sql);
         $query->bindParam(":edit_user_unique_id", $data['edit_user_unique_id']);
         $query->bindParam(":user_unique_id", $data['user_unique_id']);
         $query->bindParam(":user_role", $data['user_role']);
         $query->bindParam(":access", $data['access']);
         $query->bindParam(":last_modified", $date_added);
         $query->execute();

         if ($query) {

           $returnvalue = new editUserDetailsClass();
           $returnvalue->engineMessage = 1;

         }

       }
       else {

         $returnvalue = new editUserDetailsClass();
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
