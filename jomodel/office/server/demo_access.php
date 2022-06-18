<?php

  class demoAccessClass {
    public $engineMessage = 0;
    public $hashed_password;
  }

  $data = json_decode(file_get_contents("php://input"), true);

  date_default_timezone_set("Africa/Lagos");
  $date_added = date("Y-m-d H:i:s");

  $password = "L3Kl9Hu7nmIOp";

  function cus_salt(){
    $david = md5(rand(100, 200));
    return $david;
  }

  $options = [
    'salt' => cus_salt(),
    'cost' => 12
  ];

  $lash = password_hash($password, PASSWORD_DEFAULT, $options);

  $returnvalue = new demoAccessClass();
  $returnvalue->engineMessage = 1;
  $returnvalue->hashed_password = $lash;

  if (password_verify($password, $lash)) {
    echo "Yess It does";
  }

  echo json_encode($returnvalue);

?>
