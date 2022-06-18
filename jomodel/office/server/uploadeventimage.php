<?php

    header("Access-Control-Allow-Origin: *");

    ini_set('display_errors', 1);

    require '../../../ng/server/config/connect_data.php';
    // ini_set('post_max_size', "200M");
    // ini_set('upload_max_filesize', "200M");
    ini_set('max_execution_time', 3600);

    $out = array('error' => false);

    try {
       $conn->beginTransaction();

       $data = json_decode(file_get_contents("php://input"),true);

       function compressImage($source, $destination, $quality) {
          $info = getimagesize($source);

          if ($info['mime'] == 'image/jpeg')
            $image = imagecreatefromjpeg($source);

          elseif ($info['mime'] == 'image/gif')
            $image = imagecreatefromgif($source);

          elseif ($info['mime'] == 'image/png')
            $image = imagecreatefrompng($source);

          if(imagejpeg($image, $destination, $quality)){
            return true;
          };
        };

       if ($connected) {
         $allowed_ext = array('jpg','JPG','jpeg','JPEG','png','PNG');
         if(!empty($_FILES['file']['name'])){

           $filename = $_FILES['file']['name'];
           $fileSize = $_FILES["file"]["size"];
           $fileType = $_FILES['file']['type'];
           $fileError = $_FILES["file"]["error"];
           $file_ext = pathinfo($filename, PATHINFO_EXTENSION);

           $the_time = time();

           $string = $filename;
           $pattern = '/[ +#(),*!"<>~`;:?]/i';
           $replacement = '_';

           $newFilename = $the_time . "_" . preg_replace($pattern, $replacement, $string);

           $path = '../images/events/' . $newFilename;
           $path_to_delete = 'office/images/events/' . $newFilename;

           $image_webp_name = $filename . ".webp";

           if(($fileType == 'image/jpeg')  || ($fileType == 'image/jpg') || ($fileType == 'image/png')){

              // CHECKING THE IMAGE FILE BEFORE UPLOADING APPROXIMATELY 10MB
              if($fileSize < 30000000){

                $fileSizeInMb = round(($fileSize/1000000), 1) . " MB";

                // CHECK IF THERE'S AN ERROR BEFORE UPLOADING
                if($fileError <= 0){

                  // CHECK IF FILE EXIST IN FOLDER
                  if(file_exists($path)){
                     $out['error'] = true;
                     $out['message'] = "Image already exist";
                  }
                  else {
                    $fileURL = $path;
                    $image_webp_url = "../images/events/" . $image_webp_name;

                     if((isset($newFilename)) && ($newFilename != "Image already exist")){
                      $sourcePath = $_FILES["file"]["tmp_name"]; // Storing source path of the file in a variable
                      $targetPath = $path; // Target path where file is to be stored
                      if(compressImage($sourcePath, $path, 60) == 1){
                        $file = $path;
                        $image = imagecreatefromstring(file_get_contents($file));
                        ob_start();
                        imagejpeg($image,NULL,60);
                        $cont = ob_get_contents();
                        ob_end_clean();
                        imagedestroy($image);
                        $content = imagecreatefromstring($cont);

                         $string = pathinfo($_FILES['file']['name'], PATHINFO_FILENAME);
                         $pattern = '/[ +#(),*!"<>~`;:?]/i';
                         $replacement = '_';

                         $newFilenameWithoutExtension = $the_time . "_" . preg_replace($pattern, $replacement, $string);

                        $output = '../images/events/' .$the_time . '.webp';
                        $image_webp_url = 'office/images/events/'.$the_time . ".webp";
                        imagewebp($content,$output);
                        imagedestroy($content);

                         $out['message'] = 'File Uploaded Successfully';
                         $out['return_file'] = $image_webp_url;
                         $out['return_filename'] = $the_time . '.webp';

                         $data_to_delete = $path_to_delete;

                         $dir = $_SERVER['DOCUMENT_ROOT']."/cerotics_store/jomodel";
                         unlink($dir."/".$data_to_delete);

                      }
                      else {
                         $out['error'] = true;
                         $out['message'] = "Compression failed";
                      }
                    }
                    else {
                       $out['error'] = true;
                       $out['message'] = "Don't know what happened";
                    }

                  }
                }
                else {
                   $out['error'] = true;
                   $out['message'] = "Oops! An error occured on our server!";
                }
              }
              else {
                 $out['error'] = true;
                 $out['message'] = "Image file is too large";
              }
            }
            else {
               // $out['error'] = true;
               // $out['message'] = "Invalid image format";

               if ($file_ext == "webp" || $file_ext =="WEBP") {

                 $newFilename = $the_time.".".$file_ext;

                 $path = '../images/events/' . $newFilename;

                 if(move_uploaded_file($_FILES['file']['tmp_name'], $path)){
                   $image_ext_url = "office/images/events/" .$the_time.".".$file_ext;
                   $out['message'] = 'Image Uploaded Successfully';
                   $out['return_file'] = $image_ext_url;
                   $out['return_filename'] = $newFilename;
                 }
                 else {
                   $out['error'] = true;
                   $out['message'] = "Can't complete upload of webp image";
                 }

               }
               else {
                 $out['error'] = true;
                 $out['message'] = 'File format is not supported';
               }

            }

         }
         else{
             $out['error'] = true;
             $out['message'] = 'Upload Failed. File empty!';
         }
       }

       $conn->commit();
     } catch (PDOException $e) {
       $conn->rollback();
       throw $e;
     }

    echo json_encode($out);
?>
