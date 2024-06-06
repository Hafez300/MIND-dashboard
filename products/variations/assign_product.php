<?php 




header('Content-Type: application/json');


	 include('../../config.php');


if( isset($_POST['product_id']) && isset($_POST['option_id'])  ) {

$auth_key = mysqli_real_escape_string($conn , $_POST['auth_key']);




 $product_id = mysqli_real_escape_string($conn ,$_POST['product_id']);
$option_id = mysqli_real_escape_string($conn ,$_POST['option_id']);
 




$sql = "SELECT auth_key FROM auth where auth_key='$auth_key'";
 $key = mysqli_query($conn,$sql);
 
 if (mysqli_num_rows($key) >= 1) {

 
 
    
    $rating_sql = "INSERT INTO `product_variation`
(
`product_id`,
`variation_option_id`)
VALUES
(
'$product_id',
'$option_id') ";
    


$register = mysqli_query($conn,$rating_sql) or $error = mysqli_error($conn);


	
$last_id = mysqli_insert_id($conn);



	






if ($register) {
    
    


    
echo json_encode(array('status' => true,'msg' => 'success',"option_product_id"=>$last_id));



mysqli_close($conn);
}

else 
{
 
 echo json_encode(array('status' => false,'msg' => 'Nothing changed',"option_product_id"=>$error));


mysqli_close($conn);   
    
}




}
else
{
    
     echo json_encode(array('status' => false,'msg' => 'Wrong key',"option_product_id"=>null));
}

}
else 
{
    
   
    echo json_encode(array('status' => false,'msg' => ' product_id , option_id are required ',"option_product_id"=>null));
}


?>