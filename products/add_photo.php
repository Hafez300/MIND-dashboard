<?php 




header('Content-Type: application/json');


	 include('../config.php');


if( isset($_POST['image_name']) && isset($_POST['product_id']) ) {

$auth_key = mysqli_real_escape_string($conn , $_POST['auth_key']);


 $product_id = mysqli_real_escape_string($conn ,$_POST['product_id']);

 $main_image = mysqli_real_escape_string($conn ,$_POST['image_name']);

$thumb_image  =$main_image ;




$sql = "SELECT auth_key FROM auth where auth_key='$auth_key'";
 $key = mysqli_query($conn,$sql);
 
 if (mysqli_num_rows($key) >= 1) {

 
  	
    
    $rating_sql = "INSERT INTO `images`
(
name,
thumb,
product_id)
VALUES
(
'$main_image',
'$thumb_image',
'$product_id') ";
    


$register = mysqli_query($conn,$rating_sql) or $error = mysqli_error($conn);


	
$last_id = mysqli_insert_id($conn);
	

if ($register) {
    
    


    
echo json_encode(array('status' => true,'msg' => 'success',"products_image_id"=>$last_id));



mysqli_close($conn);
}

else 
{
 
 echo json_encode(array('status' => false,'msg' => 'Nothing changed',"products_image_id"=>$error));


mysqli_close($conn);   
    
}




}
else
{
    
     echo json_encode(array('status' => false,'msg' => 'Wrong key',"products_image_id"=>null));
}

}
else 
{
    
   
    echo json_encode(array('status' => false,'msg' => ' image name and product_id are required    ',"products_image_id"=>null));
}


?>