<?php

   require '../../../ng/server/config/connect_data.php';

   class editUserPasswordClass {
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

         $password = $data['password'];

         function cus_salt(){
          $david = md5(rand(100, 200));
          return $david;
        }

        $options = [
  
          'cost' => 12
        ];

        $lash = password_hash($password, PASSWORD_DEFAULT, $options);

         $sql = "UPDATE management SET edit_user_unique_id=:edit_user_unique_id, password=:password, last_modified=:last_modified WHERE user_unique_id=:user_unique_id";
         $query = $conn->prepare($sql);
         $query->bindParam(":edit_user_unique_id", $data['edit_user_unique_id']);
         $query->bindParam(":user_unique_id", $data['user_unique_id']);
         $query->bindParam(":password", $lash);
         $query->bindParam(":last_modified", $date_added);
         $query->execute();

         if ($query) {

           $returnvalue = new editUserPasswordClass();
           $returnvalue->engineMessage = 1;

         }

       }
       else {

         $returnvalue = new editUserPasswordClass();
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
