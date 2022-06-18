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

      $sql = "SELECT blog_posts.user_unique_id, blog_posts.edit_user_unique_id, blog_posts.unique_id,
      blog_posts.author_name, blog_posts.post_title, blog_posts.stripped, blog_posts.category_unique_id,
      blog_posts.bg_image, blog_posts.file, blog_posts.file_size, blog_posts.post_details, blog_posts.views,
      blog_posts.drafted, blog_posts.added_date, blog_posts.last_modified, blog_posts.status, management.fullname as added_fullname
      FROM blog_posts LEFT JOIN management ON blog_posts.user_unique_id = management.unique_id
      WHERE blog_posts.added_date >:startdate AND (blog_posts.added_date <:enddate OR blog_posts.added_date=:enddate) ORDER BY blog_posts.added_date DESC";
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
