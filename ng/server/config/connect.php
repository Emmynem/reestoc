<?php
  class Database{

      // specify your own database credentials
      // private $host = "localhost";
      // private $db_name = "cerotics_store";
      // private $username = "my_username";
      // private $password = "MypassworD";

      private $host = "localhost";
      private $db_name = "cerotics_store";
      private $username = "root";
      private $password = "";
      public $conn;
      public $connection;

      // get the database connection
      public function getConnection(){

          $this->conn = null;
          $this->connection = "false";

          try{
              $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
              $this->conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
              // $this->conn->exec("set names utf8");
              $this->connection = "true";
          }catch(PDOException $exception){
              $this->connection = "false";
              echo "Connection error: " . $exception->getMessage();
          }

          return $this->conn;
      }

  }
?>
