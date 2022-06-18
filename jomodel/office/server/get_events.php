<?php

   require '../../../ng/server/config/connect_data.php';

   class getEventsClass {
     public $engineMessage = 0;
     public $noData = 0;
     public $re_data;
   }

   $data = json_decode(file_get_contents("php://input"), true);

   if ($connected) {

     try {
       $conn->beginTransaction();

       $sql = "SELECT events.user_unique_id, events.edit_user_unique_id, events.unique_id, events.display_title, events.stripped, events.event_name, events.event_date_start, events.event_time_start, events.event_date_end, events.event_time_end,
       events.event_location, events.event_image, events.event_categories, events.event_tags, events.event_venue, events.event_organizers, events.total_no_of_tickets, events.tickets_left, events.drafted, events.added_date, events.last_modified, events.status, management.fullname as added_fullname
       FROM events INNER JOIN management ON events.user_unique_id = management.unique_id ORDER BY events.added_date DESC";
       $query = $conn->prepare($sql);
       $query->execute();

       if ($query->rowCount() > 0) {

         $allData = $query->fetchAll();

         $returnvalue = new getEventsClass();
         $returnvalue->engineMessage = 1;
         $returnvalue->re_data = $allData;

       }
       else {
         $returnvalue = new getEventsClass();
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
