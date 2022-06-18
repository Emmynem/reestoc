<?php

  require '../../../ng/server/config/connect_data.php';

  class getAccountClass {
    public $engineMessage = 0;
    public $noData = 0;
    public $re_data;
    public $plusSum;
    public $minusSum;
    public $totalCount;
  }

  $data = json_decode(file_get_contents("php://input"), true);

  if ($connected) {

    try {
      $conn->beginTransaction();

      $active = 1;

      $acc_type = "Income";

      $sql = "SELECT account.user_unique_id, account.edit_user_unique_id, account.unique_id, account.item, account.acc_type, account.description, account.amount, account.added_date, account.last_modified, account.status, user.fullname as added_fullname, user_edit.fullname as edit_fullname FROM account
        INNER JOIN management user ON account.user_unique_id = user.user_unique_id INNER JOIN management user_edit ON account.edit_user_unique_id = user_edit.user_unique_id ORDER BY account.added_date DESC";
      $query = $conn->prepare($sql);
      $query->execute();

      if ($query->rowCount() > 0) {

        $sql2 = "SELECT SUM(amount) FROM account WHERE status=:status AND acc_type=:acc_type ORDER BY added_date DESC";
        $query2 = $conn->prepare($sql2);
        $query2->bindParam(':acc_type', $acc_type);
        $query2->bindParam(':status', $active);
        $query2->execute();

        $sql3 = "SELECT SUM(amount) FROM account WHERE status=:status AND acc_type!=:acc_type ORDER BY added_date DESC";
        $query3 = $conn->prepare($sql3);
        $query3->bindParam(':acc_type', $acc_type);
        $query3->bindParam(':status', $active);
        $query3->execute();

      // if ($query2 && $query3) {

        $sql4 = "SELECT COUNT(*) FROM account";
        $query4 = $conn->prepare($sql4);
        $query4->execute();

        $allData = $query->fetchAll();

        $totalPlusSum = $query2->fetch();

        $totalMinusSum = $query3->fetch();

        $totalOverallCount = $query4->fetch();

        $returnvalue = new getAccountClass();
        $returnvalue->engineMessage = 1;
        $returnvalue->re_data = $allData;
        $returnvalue->plusSum = $totalPlusSum[0];
        $returnvalue->minusSum = $totalMinusSum[0];
        $returnvalue->totalCount = $totalOverallCount[0];

      // }
      }
      else {
        $returnvalue = new getAccountClass();
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
