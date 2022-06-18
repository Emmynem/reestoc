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

      $sql = "SELECT files.user_unique_id, files.unique_id, files.save_as_name, files.file_name, files.file_extension, files.file_size, files.added_date, files.last_modified, files.status, management.fullname as added_fullname
      FROM files INNER JOIN management ON files.user_unique_id = management.unique_id WHERE files.added_date >:startdate AND (files.added_date <:enddate OR files.added_date=:enddate) ORDER BY files.added_date DESC";
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
