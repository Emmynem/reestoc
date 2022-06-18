<?php

  require '../../../ng/server/config/connect_data.php';
  include_once "../../../ng/server/objects/functions.php";

  class addCategoryClass {
    public $engineMessage = 0;
    public $alreadyExists = 0;
  }

  $data = json_decode(file_get_contents("php://input"), true);

  $functions = new Functions();

  if ($connected) {

    try {
      $conn->beginTransaction();

      date_default_timezone_set("Africa/Lagos");
      $date_added = date("Y-m-d H:i:s");

      $active = 1;

      $hybrid_unique_id = $functions->random_str(20);

      // $category = "Travel";

      $sql2 = "SELECT unique_id FROM blog_categories WHERE category=:category AND status=:status";
      $query2 = $conn->prepare($sql2);
      $query2->bindParam(":category", $data['category']);
      $query2->bindParam(":status", $active);
      $query2->execute();

      if ($query2->rowCount() > 0) {
        $returnvalue = new addCategoryClass();
        $returnvalue->alreadyExists = 2;

      }
      else {

        $stripped = $functions->strip_text($data['category']);

        $sql = "INSERT INTO blog_categories (user_unique_id, edit_user_unique_id, unique_id, category, stripped, added_date, last_modified, status)
        VALUES (:user_unique_id, :edit_user_unique_id, :unique_id, :category, :stripped, :added_date, :last_modified, :status)";
        $query = $conn->prepare($sql);
        $query->bindParam(":user_unique_id", $data['user_unique_id']);
        $query->bindParam(":edit_user_unique_id", $data['edit_user_unique_id']);
        $query->bindParam(":unique_id", $hybrid_unique_id);
        $query->bindParam(":category", $data['category']);
        $query->bindParam(":stripped", $stripped);
        $query->bindParam(":added_date", $date_added);
        $query->bindParam(":last_modified", $date_added);
        $query->bindParam(":status", $active);
        $query->execute();

        if ($query) {

          $returnvalue = new addCategoryClass();
          $returnvalue->engineMessage = 1;

        }

      }

      $conn->commit();
    } catch (PDOException $e) {
    $conn->rollback();
    throw $e;
    }

    echo json_encode($returnvalue);

  }

?>
