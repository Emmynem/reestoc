<?php

  require '../../../ng/server/config/connect_data.php';

  class deleteOldBackgroundImageClass {
    public $engineMessage = 0;
  }

  $data = json_decode(file_get_contents("php://input"),true);

  if ($connected) {

    try {
      $conn->beginTransaction();

      // $sql = "SELECT bg_image from posts WHERE unique_id=:unique_id";
      // $query = $conn->prepare($sql);
      // $query->bindParam(":unique_id", $data['unique_id']);
      // $query->execute();
      // $img_to_delete = $query->fetch();

      $img_to_delete = $data['old_image'];

      // $data = $img_to_delete[0];
      $data = $img_to_delete;
      $dir = $_SERVER['DOCUMENT_ROOT']."/cerotics_store/images/blog_images/background_images";
      // $dir = $_SERVER['DOCUMENT_ROOT']."/images/blog_images/background_images"; // For online own
      unlink($dir."/".$data);

      $returnvalue = new deleteOldBackgroundImageClass();
      $returnvalue->engineMessage = 1;

      $conn->commit();
    } catch (PDOException $e) {
      $conn->rollback();
      throw $e;
    }

    echo json_encode($returnvalue);
  }

?>
