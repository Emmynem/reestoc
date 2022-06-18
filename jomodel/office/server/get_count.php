<?php

  require '../../../ng/server/config/connect_data.php';

  class getCountClass{
    public $engineMessage = 0;
    public $totalPostsCount;
    public $totalPostViewsCount;
    public $totalEnquiriesCount;
    public $totalNewsletterCount;
    // public $totalClientsCount;
    public $totalBlogImagesCount;
    public $totalBlogImagesFileSizeCount;
    public $totalCommentsCount;
    public $totalUsersCount;
    public $totalCheckersCount;
    public $totalGalleryCount;
    public $totalGalleryFileSizeCount;
    public $totalShopCount;
    public $totalShopViewsCount;
    public $totalFilesCount;
    public $totalFilesSizeCount;
    public $totalEventCount;
    public $totalCategoryCount;
    public $totalIncomeCount;
    public $totalExpenseCount;
  }

  $data = json_decode(file_get_contents("php://input"), true);

  if ($connected) {
    try {
      $conn->beginTransaction();

      $active = 1;
      $acc_type = "Income";

      $sql = "SELECT COUNT(*) FROM posts";
      $query = $conn->prepare($sql);
      $query->execute();
      $postsCount = $query->fetch();

      $sql2 = "SELECT COUNT(*) FROM enquiries";
      $query2 = $conn->prepare($sql2);
      $query2->execute();
      $enquiriesCount = $query2->fetch();

      $sql3 = "SELECT COUNT(*) FROM newsletter";
      $query3 = $conn->prepare($sql3);
      $query3->execute();
      $newsletterCount = $query3->fetch();

      $sql4 = "SELECT COUNT(*) FROM comments";
      $query4 = $conn->prepare($sql4);
      $query4->execute();
      $commentsCount = $query4->fetch();

      $sql5 = "SELECT COUNT(*) FROM management";
      $query5 = $conn->prepare($sql5);
      $query5->execute();
      $usersCount = $query5->fetch();

      $sql6 = "SELECT COUNT(*) FROM gallery";
      $query6 = $conn->prepare($sql6);
      $query6->execute();
      $galleryCount = $query6->fetch();

      $sql7 = "SELECT COUNT(*) FROM events";
      $query7 = $conn->prepare($sql7);
      $query7->execute();
      $eventCount = $query7->fetch();

      $sql8 = "SELECT COUNT(*) FROM categories";
      $query8 = $conn->prepare($sql8);
      $query8->execute();
      $categoriesCount = $query8->fetch();

      // $sql9 = "SELECT COUNT(*) FROM clients";
      // $query9 = $conn->prepare($sql9);
      // $query9->execute();
      // $clientsCount = $query9->fetch();

      $sql10 = "SELECT COUNT(*) FROM images";
      $query10 = $conn->prepare($sql10);
      $query10->execute();
      $blogImagesCount = $query10->fetch();

      $sql11 = "SELECT COUNT(*) FROM shop";
      $query11 = $conn->prepare($sql11);
      $query11->execute();
      $shopCount = $query11->fetch();

      $sql12 = "SELECT COUNT(*) FROM files";
      $query12 = $conn->prepare($sql12);
      $query12->execute();
      $filesCount = $query12->fetch();

      $sql13 = "SELECT SUM(file_size) FROM files";
      $query13 = $conn->prepare($sql13);
      $query13->execute();
      $filesSizeCount = $query13->fetch();

      $sql14 = "SELECT COUNT(*) FROM checkers";
      $query14 = $conn->prepare($sql14);
      $query14->execute();
      $checkersCount = $query14->fetch();

      $sql15 = "SELECT SUM(views) FROM shop";
      $query15 = $conn->prepare($sql15);
      $query15->execute();
      $shopViewsCount = $query15->fetch();

      $sql16 = "SELECT SUM(views) FROM posts";
      $query16 = $conn->prepare($sql16);
      $query16->execute();
      $postViewsCount = $query16->fetch();

      $sql17 = "SELECT SUM(file_size) FROM gallery";
      $query17 = $conn->prepare($sql17);
      $query17->execute();
      $galleryFileSizeCount = $query17->fetch();

      $sql18 = "SELECT SUM(file_size) FROM images";
      $query18 = $conn->prepare($sql18);
      $query18->execute();
      $blogImagesFileSizeCount = $query18->fetch();

      $sql19 = "SELECT SUM(amount) FROM account WHERE status=:status AND acc_type=:acc_type";
      $query19 = $conn->prepare($sql19);
      $query19->bindParam(':acc_type', $acc_type);
      $query19->bindParam(':status', $active);
      $query19->execute();
      $incomeCount = $query19->fetch();

      $sql20 = "SELECT SUM(amount) FROM account WHERE status=:status AND acc_type!=:acc_type";
      $query20 = $conn->prepare($sql20);
      $query20->bindParam(':acc_type', $acc_type);
      $query20->bindParam(':status', $active);
      $query20->execute();
      $expenseCount = $query20->fetch();

      $returnvalue = new getCountClass();
      $returnvalue->engineMessage = 1;
      $returnvalue->totalPostsCount = $postsCount[0];
      $returnvalue->totalPostViewsCount = $postViewsCount[0];
      $returnvalue->totalEnquiriesCount = $enquiriesCount[0];
      $returnvalue->totalNewsletterCount = $newsletterCount[0];
      $returnvalue->totalCommentsCount = $commentsCount[0];
      $returnvalue->totalUsersCount = $usersCount[0];
      $returnvalue->totalCheckersCount = $checkersCount[0];
      $returnvalue->totalGalleryCount = $galleryCount[0];
      $returnvalue->totalGalleryFileSizeCount = $galleryFileSizeCount[0];
      $returnvalue->totalFilesCount = $filesCount[0];
      $returnvalue->totalFilesSizeCount = $filesSizeCount[0];
      $returnvalue->totalCategoryCount = $categoriesCount[0];
      // $returnvalue->totalClientsCount = $clientsCount[0];
      $returnvalue->totalBlogImagesCount = $blogImagesCount[0];
      $returnvalue->totalBlogImagesFileSizeCount = $blogImagesFileSizeCount[0];
      $returnvalue->totalEventCount = $eventCount[0];
      $returnvalue->totalShopCount = $shopCount[0];
      $returnvalue->totalShopViewsCount = $shopViewsCount[0];
      $returnvalue->totalIncomeCount = $incomeCount[0];
      $returnvalue->totalExpenseCount = $expenseCount[0];

      $conn->commit();
    } catch (PDOException $e) {
      $conn->rollback();
      throw $e;
    }

    echo json_encode($returnvalue);
  }

?>
