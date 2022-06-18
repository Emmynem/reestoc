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
      $user_role_super = 1;

      $sql = "SELECT checker.user_unique_id, checker.edit_user_unique_id, checker.user_role, checker.username, checker.email, checker.fullname, checker.gender, checker.phone_number, checker.added_date, checker.last_modified, checker.access, checker.status, add_user.fullname as added_fullname FROM checkers checker INNER JOIN management add_user ON checker.edit_user_unique_id = add_user.user_unique_id WHERE checker.user_role!=:user_role AND checker.added_date >:startdate AND (checker.added_date <:enddate OR checker.added_date=:enddate) ORDER BY checker.added_date DESC";
      $query = $conn->prepare($sql);
      $query->bindParam(":user_role", $user_role_super);
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
