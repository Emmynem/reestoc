<?php

  require '../../../ng/server/config/connect_data.php';
  include_once "../../../ng/server/objects/functions.php";

  class addShopUploadClass {
    public $engineMessage = 0;
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

      $sql = "INSERT INTO shop (user_unique_id, edit_user_unique_id, unique_id, product_name, stripped, product_image, product_price, product_url, product_description, views, added_date, last_modified, status)
      VALUES (:user_unique_id, :edit_user_unique_id, :unique_id, :product_name, :stripped, :product_image, :product_price, :product_url, :product_description, :views, :added_date, :last_modified, :status)";
      $query = $conn->prepare($sql);
      $query->bindParam(":user_unique_id", $data['user_unique_id']);
      $query->bindParam(":edit_user_unique_id", $data['edit_user_unique_id']);
      $query->bindParam(":unique_id", $hybrid_unique_id);
      $query->bindParam(":product_name", $data['product_name']);
      $query->bindParam(":stripped", $data['stripped']);
      $query->bindParam(":product_image", $data['product_image']);
      $query->bindParam(":product_price", $data['product_price']);
      $query->bindParam(":product_url", $data['product_url']);
      $query->bindParam(":product_description", $data['product_description']);
      $query->bindParam(":views", $data['views']);
      $query->bindParam(":added_date", $date_added);
      $query->bindParam(":last_modified", $date_added);
      $query->bindParam(":status", $active);
      $query->execute();

      if ($query) {

        $returnvalue = new addShopUploadClass();
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
