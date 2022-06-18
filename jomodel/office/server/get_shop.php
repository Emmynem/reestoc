<?php

   require '../../../ng/server/config/connect_data.php';

   class getShopClass {
     public $engineMessage = 0;
     public $noData = 0;
     public $re_data;
     public $total_views;
   }

   $data = json_decode(file_get_contents("php://input"), true);

   if ($connected) {

     try {
       $conn->beginTransaction();

       $sql = "SELECT shop.user_unique_id, shop.edit_user_unique_id, shop.unique_id, shop.product_name, shop.stripped, shop.product_image, shop.product_price, shop.product_url, shop.product_description, shop.views, shop.added_date, shop.last_modified, shop.status, management.fullname as added_fullname
       FROM shop INNER JOIN management ON shop.user_unique_id = management.unique_id ORDER BY shop.added_date DESC";
       $query = $conn->prepare($sql);
       $query->execute();

       if ($query->rowCount() > 0) {

         $allData = $query->fetchAll();

         $sql3 = "SELECT SUM(views) FROM shop";
         $query3 = $conn->prepare($sql3);
         $query3->execute();

         $total_views = $query3->fetch();

         $returnvalue = new getShopClass();
         $returnvalue->engineMessage = 1;
         $returnvalue->re_data = $allData;
         $returnvalue->total_views = $total_views[0];

       }
       else {
         $returnvalue = new getShopClass();
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
