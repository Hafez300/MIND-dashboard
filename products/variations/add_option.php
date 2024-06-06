<?php 




header('Content-Type: application/json');


	 include('../../config.php');


if( isset($_POST['product_id']) && isset($_POST['variation_id']) && isset($_POST['value_en']) && isset($_POST['value_ar']) ) {

$auth_key = mysqli_real_escape_string($conn , $_POST['auth_key']);




 $product_id = mysqli_real_escape_string($conn ,$_POST['product_id']);
$variation_id = mysqli_real_escape_string($conn ,$_POST['variation_id']);
 
 $value_en = mysqli_real_escape_string($conn ,$_POST['value_en']);
 
 
 $value_ar = mysqli_real_escape_string($conn , $_POST['value_ar']);



$sql = "SELECT auth_key FROM auth where auth_key='$auth_key'";
 $key = mysqli_query($conn,$sql);
 
 if (mysqli_num_rows($key) >= 1) {

 
 
    
    $rating_sql = "INSERT INTO `variation_options`
(
`main_product_id`,
`variation_id`,
`value_en`,
`value_ar`)
VALUES
(
'$product_id',
'$variation_id',
'$value_en',
'$value_ar') ";
    


$register = mysqli_query($conn,$rating_sql) or $error = mysqli_error($conn);


	
$last_id = mysqli_insert_id($conn);


$variation_type = mysqli_query($conn,"SELECT * from variations where id = '$variation_id' ");

$var_row = mysqli_fetch_assoc($variation_type);

$var_name = $var_row["name_en"];

	$query = mysqli_query($conn,"SELECT * from variation_options where id = '$last_id' ");

	





 if (mysqli_num_rows($query) >= 1) {

	



	while($row = mysqli_fetch_assoc($query)){
	    
	 
	    $temp_array[] = $row;
	    
	}
 }

if ($register) {
    
    


    
echo json_encode(array('status' => true,'msg' => 'success','variation_name' => $var_name,'variation_id' => $variation_id,"option"=>$temp_array));



mysqli_close($conn);
}

else 
{
 
 echo json_encode(array('status' => false,'msg' => 'Nothing changed',"option"=>$error));


mysqli_close($conn);   
    
}




}
else
{
    
     echo json_encode(array('status' => false,'msg' => 'Wrong key',"option"=>null));
}

}
else 
{
    
   
    echo json_encode(array('status' => false,'msg' => ' product_id , variation_id , value_ar and value_en  are required    ',"option"=>null));
}


?>