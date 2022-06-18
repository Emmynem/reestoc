<?php

  require '../../../ng/server/config/connect_data.php';

  class loginClass {
    public $engineMessage = 0;
    public $loginWho = 0;
    public $emailDoesNotExist = 0;
    public $notVerified = 0;
    public $accessStatus = 0;
    public $user_unique_id;
    public $email;
    public $fullname;
    public $user_role;
    public $username;
  }

  $data = json_decode(file_get_contents("php://input"), true);

  if ($connected) {
    try {
      $conn->beginTransaction();

      $active = 1;

      $still_have_access = 1;

      $sql = "SELECT unique_id FROM management WHERE email=:email AND status=:status";
      $query = $conn->prepare($sql);
      $query->bindParam(':email', $data['email']);
      $query->bindParam(':status', $active);
      $query->execute();

      $flight_unique_id = $query->fetch();

      $user_unique_id = !$flight_unique_id ? null : $flight_unique_id[0];

      $sql4 = "SELECT access FROM management WHERE unique_id=:unique_id AND access!=:access AND status=:status";
      $query4 = $conn->prepare($sql4);
      $query4->bindParam(':unique_id', $user_unique_id);
      $query4->bindParam(':access', $still_have_access);
      $query4->bindParam(':status', $active);
      $query4->execute();

      $access_now = $query4->fetch();

      $access_check = !$access_now ? null : $access_now[0];

      if ($query->rowCount() > 0 && $query4->rowCount() <= 0) {

        $sql6 = "SELECT fullname, email, role, fullname as username FROM management WHERE unique_id=:unique_id AND status=:status";
        $query6 = $conn->prepare($sql6);
        $query6->bindParam(':unique_id', $user_unique_id);
        $query6->bindParam(':status', $active);
        $query6->execute();

        $flight_3 = $query6->fetch();

        $fullName = !$flight_3 ? null : $flight_3[0];
        $yourEmail = !$flight_3 ? null : $flight_3[1];
        $userrole = !$flight_3 ? null : $flight_3[2];
        $yourUsername = !$flight_3 ? null : $flight_3[3];

        $returnvalue = new loginClass();
        $returnvalue->engineMessage = 1;
        $returnvalue->user_unique_id = $user_unique_id;
        $returnvalue->email = $yourEmail;
        $returnvalue->fullname = $fullName;
        $returnvalue->user_role = $userrole;
        $returnvalue->username = $yourUsername;

        switch ($userrole) {
          case 1:
            $returnvalue->loginWho = 1;
            break;
          case 2:
            $returnvalue->loginWho = 2;
            break;
          case 3:
            $returnvalue->loginWho = 3;
            break;
          case 4:
            $returnvalue->loginWho = 4;
            break;
          case 5:
            $returnvalue->loginWho = 5;
            break;
          case 6:
            $returnvalue->loginWho = 6;
            break;
          case 7:
            $returnvalue->loginWho = 7;
            break;
          default:
            $returnvalue->loginWho = 0;
            break;
        }

      }
      elseif ($query4->rowCount() > 0) {
        $returnvalue = new loginClass();
        $returnvalue->accessStatus = $access_check;
      }
      else {
        $returnvalue = new loginClass();
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
