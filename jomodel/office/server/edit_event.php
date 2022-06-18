<?php

   require '../../../ng/server/config/connect_data.php';

   class editEventClass {
     public $engineMessage = 0;
     public $noData = 0;
   }

   $data = json_decode(file_get_contents("php://input"), true);

   if ($connected) {

     try {
       $conn->beginTransaction();

       date_default_timezone_set("Africa/Lagos");
       $date_added = date("Y-m-d H:i:s");

       $sql2 = "SELECT * FROM events WHERE unique_id=:unique_id";
       $query2 = $conn->prepare($sql2);
       $query2->bindParam(":unique_id", $data['unique_id']);
       $query2->execute();

       if ($query2->rowCount() > 0) {

         $sql = "UPDATE events SET edit_user_unique_id=:edit_user_unique_id, display_title=:display_title, stripped=:stripped, event_name=:event_name, event_date_start=:event_date_start, event_time_start=:event_time_start,
         event_date_end=:event_date_end, event_time_end=:event_time_end, event_location=:event_location, event_image=:event_image, event_categories=:event_categories, event_tags=:event_tags,
         event_venue=:event_venue, event_organizers=:event_organizers, last_modified=:last_modified WHERE unique_id=:unique_id";
         $query = $conn->prepare($sql);
         $query->bindParam(":edit_user_unique_id", $data['edit_user_unique_id']);
         $query->bindParam(":unique_id", $data['unique_id']);
         $query->bindParam(":display_title", $data['display_title']);
         $query->bindParam(":stripped", $data['stripped']);
         $query->bindParam(":event_name", $data['event_name']);
         $query->bindParam(":event_date_start", $data['event_date_start']);
         $query->bindParam(":event_time_start", $data['event_time_start']);
         $query->bindParam(":event_date_end", $data['event_date_end']);
         $query->bindParam(":event_time_end", $data['event_time_end']);
         $query->bindParam(":event_location", $data['event_location']);
         $query->bindParam(":event_image", $data['event_image']);
         $query->bindParam(":event_categories", $data['event_categories']);
         $query->bindParam(":event_tags", $data['event_tags']);
         $query->bindParam(":event_venue", $data['event_venue']);
         $query->bindParam(":event_organizers", $data['event_organizers']);
         $query->bindParam(":last_modified", $date_added);
         $query->execute();

         if ($query) {

           $returnvalue = new editEventClass();
           $returnvalue->engineMessage = 1;

         }

       }
       else {

         $returnvalue = new editEventClass();
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
