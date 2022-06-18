<?php

   require '../../../ng/server/config/connect_data.php';

   class getTicketCategoryClass {
     public $engineMessage = 0;
     public $noData = 0;
     public $re_data;
   }

   $data = json_decode(file_get_contents("php://input"), true);

   if ($connected) {

     try {
       $conn->beginTransaction();

       $table = $data['table'];

       $sql = "SELECT ticket.user_unique_id, ticket.edit_user_unique_id, ticket.event_unique_id, ticket.unique_id, ticket.ticket_name, ticket.ticket_description, ticket.price, ticket.total_no_of_ticketing,
       ticket.added_date, ticket.last_modified, ticket.status, user.fullname as added_by_fullname FROM ticketing ticket INNER JOIN management user ON ticket.edit_user_unique_id = user.user_unique_id WHERE event_unique_id=:event_unique_id";
       $query = $conn->prepare($sql);
       $query->bindParam(":event_unique_id", $data['event_unique_id']);
       $query->execute();

       if ($query->rowCount() > 0) {

         $allData = $query->fetchAll();

         $returnvalue = new getTicketCategoryClass();
         $returnvalue->engineMessage = 1;
         $returnvalue->re_data = $allData;

       }
       else {
         $returnvalue = new getTicketCategoryClass();
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
