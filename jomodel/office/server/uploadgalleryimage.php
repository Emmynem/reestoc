<?php

    header("Access-Control-Allow-Origin: *");

    ini_set('display_errors', 1);

    require '../../../ng/server/config/connect_data.php';
    include_once "../../../ng/server/objects/functions.php";
    // ini_set('post_max_size', "200M");
    // ini_set('upload_max_filesize', "200M");
    ini_set('max_execution_time', 3600);

    $out = array('error' => false);

    $functions = new Functions();

    try {
       $conn->beginTransaction();

       // $data = json_decode(file_get_contents("php://input"),true);

       $hybrid_unique_id = $functions->random_str(20);

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

       $data['user_unique_id'] = $_POST['user_unique_id'];
       $data['edit_user_unique_id'] = $_POST['edit_user_unique_id'];

       if ($connected) {
         $allowed_ext = array('jpg','JPG','jpeg','JPEG','png','PNG');
         if(!empty($_FILES['file']['name'])){
           $count = count($_FILES['file']['name']);
           $countErrors = 0;
           foreach ($_FILES['file']['size'] as $key => $filesize){
             if ($filesize > 30000000) {
               $countErrors += 1;
             }
           }

           if ($countErrors == 0) {
             $countErrorsExtentions = 0;
             foreach ($_FILES['file']['name'] as $key => $filename){
               $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
               if (!in_array($file_ext,$allowed_ext)) {
                 $countErrorsExtentions += 1;
               }
             }

             if ($countErrorsExtentions == 0) {
               foreach ($_FILES['file']['name'] as $key => $filename){
                   // $newFilename = time() . "_" . $filename;
                   $fileSize = $_FILES["file"]["size"][$key];
                   $fileType = $_FILES['file']['type'][$key];
                   $fileError = $_FILES["file"]["error"][$key];

                   $string = $filename;
                   $pattern = '/[ +#(),*!"<>~`;:?]/i';
                   $replacement = '_';

                   $newFilename = time() . "_" . preg_replace($pattern, $replacement, $string);

                   $path = '../../gallery/' . $newFilename;

                   $image_webp_name = $filename . ".webp";

                   $the_time = time();

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
                 					$image_webp_url = "../../gallery/" . $image_webp_name;

                           if((isset($newFilename)) && ($newFilename != "Image already exist")){
                         		$sourcePath = $_FILES["file"]["tmp_name"][$key]; // Storing source path of the file in a variable
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

                               $string = pathinfo($_FILES['file']['name'][$key], PATHINFO_FILENAME);
                               $pattern = '/[ +#(),*!"<>~`;:?]/i';
                               $replacement = '_';

                               $newFilenameWithoutExtension = time() . "_" . preg_replace($pattern, $replacement, $string);

                         			$output = '../../gallery/' .$the_time . '.webp';
                         			$image_webp_url = "gallery/" .$the_time . ".webp";
                         			imagewebp($content,$output);
                         			imagedestroy($content);

                               date_default_timezone_set("Africa/Lagos");
                               $date_added = date("Y-m-d H:i:s");

                               $active = 1;

                               // $data['image'] = $output;
                               $data['image'] = $image_webp_url;
                               $data['file_size'] = $fileSize;

                               $sql = "INSERT INTO gallery (user_unique_id, edit_user_unique_id, unique_id, image, file_size, added_date, last_modified, status)
                               VALUES (:user_unique_id, :edit_user_unique_id, :unique_id, :image, :file_size, :added_date, :last_modified, :status)";
                               $query = $conn->prepare($sql);
                               $query->bindParam(":user_unique_id", $data['user_unique_id']);
                               $query->bindParam(":edit_user_unique_id", $data['edit_user_unique_id']);
                               $query->bindParam(":unique_id", $hybrid_unique_id);
                               $query->bindParam(":image", $data['image']);
                               $query->bindParam(":file_size", $data['file_size']);
                               $query->bindParam(":added_date", $date_added);
                               $query->bindParam(":last_modified", $date_added);
                               $query->bindParam(":status", $active);
                               $query->execute();

                               if($query){
                                   if($count > 1){
                                       $out['message'] = 'Images Uploaded Successfully';
                                   }
                                   else{
                                       $out['message'] = 'Image Uploaded Successfully';
                                   }

                               }
                               else{
                                 if($count > 1){
                                   $out['error'] = true;
                                   $out['message'] = 'Image Uploaded but not Saved';
                                 }
                                 else{
                                     $out['error'] = true;
                                     $out['message'] = 'Images Uploaded but not Saved';
                                 }
                               }

                               $data_to_delete = $file;

                               $dir = $_SERVER['DOCUMENT_ROOT']."/cerotics_store/jomodel";
                               // unlink($dir."/".$data_to_delete);
                               unlink($data_to_delete);

                         		}
                         		else {
                               $counter_now = $key;
                               $out['error'] = true;
                               $out['message'] = $counter_now." index Compression failed";
                         		}
                         	}
                         	else {
                             $counter_now = $key;
                             $out['error'] = true;
                             $out['message'] = $counter_now." don't know what happened";
                         	}

                 				}
                 			}
                 			else {
                         $counter_now = $key;
                         $out['error'] = true;
                         $out['message'] = $counter_now." index, Oops! An error occured on our server!";
                 			}
                 		}
                 		else {
                       $counter_now = $key;
                       $out['error'] = true;
                       $out['message'] = $counter_now." index, Image file is too large";
                 		}
                 	}
                 	else {
                     $counter_now = $key;
                     $out['error'] = true;
                     $out['message'] = $counter_now." index, Invalid image format";
                 	}

                   // ----------------------------------------------

                   sleep(1);

               }
             }
             else {
               if ($countErrorsExtentions == 1) {
                 $out['error'] = true;
                 $out['message'] = $countErrorsExtentions.' image format is not supported';
               }
               else{
                 $out['error'] = true;
                 $out['message'] = $countErrorsExtentions.' images format is not supported';
               }
             }
           }
           else {
             if ($countErrors == 1) {
               $out['error'] = true;
               $out['message'] = $countErrors.' image have size over 30mb';
             }
             else{
               $out['error'] = true;
               $out['message'] = $countErrors.' images has sizes over 30mb';
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
