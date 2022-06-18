<?php

  require '../../../ng/server/config/connect_data.php';

  class getFilteredDataClass {
    public $engineMessage = 0;
    public $noData = 0;
    public $filteredData;
    public $total_views;
  }

  $data = json_decode(file_get_contents("php://input"), true);

  if ($connected) {

    try {
      $conn->beginTransaction();

      $table = $data['table'];

      $active = 1;

      $sql = "SELECT * FROM $table WHERE added_date >:startdate AND (added_date <:enddate OR added_date=:enddate) ORDER BY added_date DESC";
      $query = $conn->prepare($sql);
      $query->bindParam(':startdate', $data['startdate']);
      $query->bindParam(':enddate', $data['enddate']);
      $query->execute();

      if ($query->rowCount() > 0) {
        $allData = $query->fetchAll();

        $sql3 = "SELECT SUM(views) FROM shop WHERE added_date >:startdate AND (added_date <:enddate OR added_date=:enddate)";
        $query3 = $conn->prepare($sql3);
        $query3->bindParam(':startdate', $data['startdate']);
        $query3->bindParam(':enddate', $data['enddate']);
        $query3->execute();

        $total_views = $query3->fetch();

        $returnvalue = new getFilteredDataClass();
        $returnvalue->engineMessage = 1;
        $returnvalue->filteredData = $allData;
        $returnvalue->total_views = $total_views[0];

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
