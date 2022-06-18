<?php

  require '../../../ng/server/config/connect_data.php';

  class deleteGalleryImage {
    public $engineMessage = 0;
  }

  $data = json_decode(file_get_contents("php://input"),true);

  if ($connected) {

    try {
      $conn->beginTransaction();

      $sql = "SELECT file from blog_images WHERE unique_id=:unique_id";
      $query = $conn->prepare($sql);
      $query->bindParam(":unique_id", $data['unique_id']);
      $query->execute();
      $img_to_delete = $query->fetch();

      $data_to_delete = $img_to_delete[0];

      $sql2 = "DELETE FROM blog_images WHERE unique_id=:unique_id";
      $query2 = $conn->prepare($sql2);
      $query2->bindParam(":unique_id", $data['unique_id']);
      $query2->execute();

      if ($query2) {

        $dir = $_SERVER['DOCUMENT_ROOT']."/cerotics_store/images/blog_images/post_images";
        // $dir = $_SERVER['DOCUMENT_ROOT']."/images/blog_images/post_images"; // For online own
        unlink($dir."/".$data_to_delete);

        $returnvalue = new deleteGalleryImage();
        $returnvalue->engineMessage = 1;

      }

      $conn->commit();
    } catch (PDOException $e) {
      $conn->rollback();
      throw $e;
    }

    echo json_encode($returnvalue);
  }

?>
