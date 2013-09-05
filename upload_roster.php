<?php 
$target_path = "uploads/";

$target_path = $target_path . basename( $_FILES['filename']['name']); 

if(move_uploaded_file($_FILES['filename']['tmp_name'], $target_path)) {
    echo "<script>alert('The file ".  basename( $_FILES['filename']['name']). 
    " has been uploaded');</script>";
} else {
    echo "<script>alert('There was an error uploading the file, please try again!');";
}
 ?>