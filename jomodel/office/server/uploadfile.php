<?php

  // ini_set('upload_max_filesize', "150M");
  //
  // ini_set('post_max_size', "150M");

  require '../../../ng/server/config/connect_data.php';

  class uploadFilesClass{
    public $engineMessage = 0;
    public $invalidFormat = 0;
    public $fileLarge = 0;
    public $return_file;
    public $return_filename;
    public $return_filesize;
  }

  $data = json_decode(file_get_contents("php://input"),true);

  if ($connected) {
    if (!empty($_FILES)) {
      $file_name = $_FILES['file']['name'];
      $file_error = $_FILES['file']['error'];
      $file_size = $_FILES['file']['size'];
      $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
      // $not_allowed_ext = array('jpg','JPG','jpeg','JPEG','png','PNG');
      $not_allowed_ext = array('pdf','PDF','docx','DOCX','doc','DOC','odt','ODT','ods','ODS','txt','TXT','ppt','PPT','pptx','PPTX','zip','ZIP','rar','RAR','xls','XLS','xlsx','XLSX');

      if ($file_name) {
        if (!$file_error) {
          if (!in_array($file_ext,$not_allowed_ext)) {
            $returnvalue = new uploadFilesClass();
            $returnvalue->invalidFormat = 3;
            echo json_encode($returnvalue);
          }
          elseif ($file_size > 100000000) {
            $returnvalue = new uploadFilesClass();
            $returnvalue->fileLarge = 2;
            echo json_encode($returnvalue);
          }
          else {
            // $ext = explode('.',$file_name);
            // $filename = time().'.'.$ext[1];

            $filename = time() . "_" . $file_name;
            $destination = '../files-manager/' . $filename;
            $location = $_FILES['file']['tmp_name'];
            move_uploaded_file($location,$destination);
            $return = 'office/files-manager/' . $filename;

            $returnvalue = new uploadFilesClass();
            $returnvalue->engineMessage = 1;
            $returnvalue->return_file = $return;
            $returnvalue->return_filename = $filename;
            $returnvalue->return_filesize = $file_size;

            echo json_encode($returnvalue);
          }
        }

      }

    }
    else {
      $returnvalue = new uploadFilesClass();
      $returnvalue->return_file = null;

      echo json_encode($returnvalue);
    }

  }

 ?>
