<?php

  require '../../../ng/server/config/connect_data.php';

  class searchClass {
    public $engineMessage = 0;
    public $search_results;
  }

  $data = json_decode(file_get_contents("php://input"),true);

  if ($connected) {
    $keyword = $data['postTitle'];
    $sql = "SELECT * FROM posts cp
    JOIN categories cc ON cp.postCategory = cc.cat_id
    WHERE cp.postTitle LIKE :postTitle ORDER BY cp.id DESC ,cp.postActivity DESC";
    $keyword="%".$keyword."%";
    $query = $conn->prepare($sql);
    $query->bindParam('postTitle', $keyword);
    $query->execute();

    $results = $query->fetchAll();

    $returnvalue = new searchClass();
    $returnvalue->engineMessage = 1;
    $returnvalue->search_results = $results;

    echo json_encode($returnvalue);
  }

?>
