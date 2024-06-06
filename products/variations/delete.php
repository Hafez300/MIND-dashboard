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

 
 
 $count_sql = "select count(*) as total from `product_variation` where  variation_option_id = '$option_id' limit 1 ";



$result = mysqli_query($conn,$count_sql) or $error = mysqli_error($conn);

$row = mysqli_fetch_assoc($result);

if ($row['total'] == 1)

    {
    $rating_sql = "delete from `product_variation` where product_id = '$product_id' and variation_option_id = '$option_id' limit 1 ";



$register = mysqli_query($conn,$rating_sql) or $error = mysqli_error($conn);


 $delete_option_sql = "delete from `variation_options` where id  = '$option_id' limit 1 ";



$excute = mysqli_query($conn,$delete_option_sql) or $error = mysqli_error($conn);   

}

else
{
 $rating_sql = "delete from `product_variation` where product_id = '$product_id' and variation_option_id = '$option_id' limit 1 ";



$register = mysqli_query($conn,$rating_sql) or $error = mysqli_error($conn);    
    
}
	

	






if (mysqli_affected_rows($conn) >= 1) {
    
    


    
echo json_encode(array('status' => true,'msg' => 'success',"data"=>''));



mysqli_close($conn);
}

else 
{
 
 echo json_encode(array('status' => false,'msg' => 'not found',"data"=>$error));


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
    
   
    echo json_encode(array('status' => false,'msg' => ' product_id , option_id   are required ',"option_product_id"=>null));
}


?>