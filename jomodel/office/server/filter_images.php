<?php

  require '../../../ng/server/config/connect_data.php';

  class getFilteredDataClass {
    public $engineMessage = 0;
    public $noData = 0;
    public $filteredData;
  }

  $data = json_decode(file_get_contents("php://input"), true);

  if ($connected) {

    try {
      $conn->beginTransaction();

      $table = $data['table'];

      $active = 1;

      $sql = "SELECT blog_images.user_unique_id, blog_images.edit_user_unique_id, blog_images.unique_id, blog_images.image, blog_images.file, blog_images.file_size, blog_images.added_date, blog_images.last_modified, blog_images.status, management.fullname as added_fullname
      FROM blog_images INNER JOIN management ON blog_images.user_unique_id = management.unique_id WHERE blog_images.added_date >:startdate AND (blog_images.added_date <:enddate OR blog_images.added_date=:enddate) ORDER BY blog_images.added_date DESC";
      $query = $conn->prepare($sql);
      $query->bindParam(':startdate', $data['startdate']);
      $query->bindParam(':enddate', $data['enddate']);
      $query->execute();

      if ($query->rowCount() > 0) {
        $allData = $query->fetchAll();

        $returnvalue = new getFilteredDataClass();
        $returnvalue->engineMessage = 1;
        $returnvalue->filteredData = $allData;

      }
      else {
        $returnvalue = new getFilteredDataClass();
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
