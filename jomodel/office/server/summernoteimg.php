<?php

$return_value = "";
if($_FILES['image']['name']) {
	if(!$_FILES['image']['error']) {
		$name = md5(rand(100, 200));
		$ext = explode('.', $_FILES['image']['name']);
		$filename = $name.'.'.$ext[1];
		$destination = '../images/posts/' . $filename;
		//$destination_1 = '../img/posts/' . $filename;
		$location = $_FILES["image"]["tmp_name"];
		move_uploaded_file($location, $destination);
		//move_uploaded_file($location, $destination_1);
		$return_value = 'office/images/posts/' . $filename;
	} else {
		$return_value = 'Oops! Your upload triggered the following error:' . $_FILES['image']['error'];
	}
}

echo $return_value;

?>
