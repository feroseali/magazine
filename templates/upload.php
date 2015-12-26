<?php
header("Access-Control-Allow-Origin: *"); 
header('Content-type: application/json');

$ds          = DIRECTORY_SEPARATOR;  //1
 
$storeFolder = '../uploads';   //2

$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
$newname = time();
$random = rand(100,999);
$name = $newname.$random.'.'.$ext;

if (!empty($_FILES)) {
     
    $tempFile = $_FILES['file']['tmp_name'];          //3             
      
    $targetPath = $storeFolder . $ds;  //4
     
    $targetFile =  $targetPath.$name;  //5

    move_uploaded_file($tempFile,$targetFile); //6
	chmod($targetFile, 0777);
    $output['filename'] = basename($targetFile, $ext).$ext;
    echo json_encode($output);
     
}


?>