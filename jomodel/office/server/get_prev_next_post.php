<?php

   require '../../../ng/server/config/connect_data.php';

   class getPrevNextPostClass {
     public $engineMessage = 0;
     public $noData = 0;
     public $re_data;
     public $re_data_2;
   }

   $data = json_decode(file_get_contents("php://input"), true);

   if ($connected) {

     try {
       $conn->beginTransaction();

       $sql = "SELECT added_date FROM posts WHERE unique_id=:unique_id";
       $query = $conn->prepare($sql);
       $query->bindParam(":unique_id", $data['unique_id']);
       $query->execute();

       if ($query->rowCount() > 0) {

         $the_date = $query->fetch();
         $date_now = $the_date['added_date'];

         $sql2 = "SELECT unique_id, category_unique_id, post_title FROM posts WHERE added_date <:the_date ORDER BY added_date DESC LIMIT 1";
         $query2 = $conn->prepare($sql2);
         $query2->bindParam(":the_date", $date_now);
         $query2->execute();

         $previous_post = $query2->fetch();

         $sql3 = "SELECT unique_id, category_unique_id, post_title FROM posts WHERE added_date >:the_date ORDER BY added_date ASC LIMIT 1";
         $query3 = $conn->prepare($sql3);
         $query3->bindParam(":the_date", $date_now);
         $query3->execute();

         $next_post = $query3->fetch();

         $returnvalue = new getPrevNextPostClass();
         $returnvalue->engineMessage = 1;
         $returnvalue->re_data = $previous_post;
         $returnvalue->re_data_2 = $next_post;
       }
       else {
         $returnvalue = new getPrevNextPostClass();
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
