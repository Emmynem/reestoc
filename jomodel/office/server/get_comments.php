<?php

   require '../../../ng/server/config/connect_data.php';

   class getCommentsClass {
     public $engineMessage = 0;
     public $noData = 0;
     public $re_data;
     public $comment_count;
   }

   $data = json_decode(file_get_contents("php://input"), true);

   if ($connected) {

     try {
       $conn->beginTransaction();

       $sql = "SELECT unique_id, name, email, message, post_unique_id, added_date, last_modified, status FROM comments WHERE post_unique_id=:post_unique_id";
       $query = $conn->prepare($sql);
       $query->bindParam(":post_unique_id", $data['post_unique_id']);
       $query->execute();

       if ($query->rowCount() > 0) {

         $comments_details = $query->fetchAll();

         $sql2 = "SELECT COUNT(*) FROM comments WHERE post_unique_id=:post_unique_id";
         $query2 = $conn->prepare($sql2);
         $query2->bindParam(":post_unique_id", $data['post_unique_id']);
         $query2->execute();

         $count_details = $query2->fetch();

         if ($query2) {

           $returnvalue = new getCommentsClass();
           $returnvalue->engineMessage = 1;
           $returnvalue->re_data = $comments_details;
           $returnvalue->comment_count = $count_details[0];

         }

       }
       else {
         $returnvalue = new getCommentsClass();
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
