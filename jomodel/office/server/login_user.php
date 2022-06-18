<?php

  require '../../../ng/server/config/connect_data.php';

  class loginUserClass {
    public $engineMessage = 0;
    public $loginWho = 0;
    public $emailDoesNotExist = 0;
    public $notVerified = 0;
    public $accessStatus = 0;
    public $user_unique_id;
    public $fullname;
  }

  $data = json_decode(file_get_contents("php://input"), true);

  if ($connected) {
    try {
      $conn->beginTransaction();

      $active = 1;
      $password_now = $data['password'];

      $still_have_access = 1;

      $sql = "SELECT user_unique_id, password, fullname FROM users_data WHERE email=:email AND status=:status";
      $query = $conn->prepare($sql);
      $query->bindParam(':email', $data['email']);
      $query->bindParam(':status', $active);
      $query->execute();

      $flight_unique_id = $query->fetch();

      $user_unique_id = !$flight_unique_id ? null : $flight_unique_id[0];

      $sql4 = "SELECT access FROM users_data WHERE user_unique_id=:user_unique_id AND email=:email AND access!=:access AND status=:status";
      $query4 = $conn->prepare($sql4);
      $query4->bindParam(':user_unique_id', $user_unique_id);
      $query4->bindParam(':email', $data['email']);
      $query4->bindParam(':access', $still_have_access);
      $query4->bindParam(':status', $active);
      $query4->execute();

      $access_now = $query4->fetch();

      $access_check = !$access_now ? null : $access_now[0];

      if ($query->rowCount() > 0 && $query4->rowCount() <= 0) {

        $password_check = !$flight_unique_id ? null : $flight_unique_id[1];

        $username = !$flight_unique_id ? null : $flight_unique_id[2];

        if (password_verify($password_now, $password_check)) {
          $returnvalue = new loginUserClass();
          $returnvalue->engineMessage = 1;
          $returnvalue->user_unique_id = $user_unique_id;
          $returnvalue->fullname = $username;
          $returnvalue->loginWho = 1;

        }
        else {
          $returnvalue = new loginUserClass();
          $returnvalue->notVerified = 3;
        }

      }
      elseif ($query4->rowCount() > 0) {
        $returnvalue = new loginUserClass();
        $returnvalue->accessStatus = 4;
      }
      else {
        $returnvalue = new loginUserClass();
        $returnvalue->emailDoesNotExist = 2;
      }

      $conn->commit();
    } catch (PDOException $e) {
      $conn->rollback();
      throw $e;
    }

    echo json_encode($returnvalue);
  }

?>
