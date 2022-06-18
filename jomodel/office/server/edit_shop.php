<?php

   require '../../../ng/server/config/connect_data.php';

   class editShopClass {
     public $engineMessage = 0;
     public $noData = 0;
   }

   $data = json_decode(file_get_contents("php://input"), true);

   if ($connected) {

     try {
       $conn->beginTransaction();

       date_default_timezone_set("Africa/Lagos");
       $date_added = date("Y-m-d H:i:s");

       $sql2 = "SELECT * FROM shop WHERE unique_id=:unique_id";
       $query2 = $conn->prepare($sql2);
       $query2->bindParam(":unique_id", $data['unique_id']);
       $query2->execute();

       if ($query2->rowCount() > 0) {

         $sql = "UPDATE shop SET edit_user_unique_id=:edit_user_unique_id, product_name=:product_name, stripped=:stripped, product_image=:product_image, product_price=:product_price,
         product_url=:product_url, product_description=:product_description, last_modified=:last_modified WHERE unique_id=:unique_id";
         $query = $conn->prepare($sql);
         $query->bindParam(":edit_user_unique_id", $data['edit_user_unique_id']);
         $query->bindParam(":unique_id", $data['unique_id']);
         $query->bindParam(":product_name", $data['product_name']);
         $query->bindParam(":stripped", $data['stripped']);
         $query->bindParam(":product_image", $data['product_image']);
         $query->bindParam(":product_price", $data['product_price']);
         $query->bindParam(":product_url", $data['product_url']);
         $query->bindParam(":product_description", $data['product_description']);
         $query->bindParam(":last_modified", $date_added);
         $query->execute();

         if ($query) {

           $returnvalue = new editShopClass();
           $returnvalue->engineMessage = 1;

         }

       }
       else {

         $returnvalue = new editShopClass();
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
