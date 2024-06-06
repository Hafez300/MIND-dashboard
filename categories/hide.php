<?php 




header('Content-Type: application/json');



	 include('../config.php');


if( isset($_POST['category_id']) &&  isset($_POST['user_id'])) {

$auth_key = mysqli_real_escape_string($conn , $_POST['auth_key']);

 $category_id = mysqli_real_escape_string($conn , $_POST['category_id']);

 $user_id = mysqli_real_escape_string($conn , $_POST['user_id']);




$sql = "SELECT auth_key FROM auth where auth_key='$auth_key'";
 $key = mysqli_query($conn,$sql);
 
 if (mysqli_num_rows($key) >= 1) {


    
    $edit_sql = "update `products` set active = 0 where category_id = '$category_id' ";
    


$register = mysqli_query($conn,$edit_sql) or $error = mysqli_error($conn);


    $product_sql = "update categories set active = 0 where id = '$category_id' ";
    
    $excute = mysqli_query($conn,$product_sql) or $error = mysqli_error($conn);
    

	

if ($register) {
    
    


    
echo json_encode(array('status' => true,'msg' => 'success',"category_id"=>$category_id));



mysqli_close($conn);
}

else 
{
 
 echo json_encode(array('status' => false,'msg' => 'Nothing changed',"category_id"=>$error));


mysqli_close($conn);   
    
}




}
else
{
    
     echo json_encode(array('status' => false,'msg' => 'Wrong key',"category_id"=>null));
}

}
else 
{
    
    echo json_encode(array('status' => false,'msg' => ' category_id and user_id are required',"category_id"=>null));
}


?>