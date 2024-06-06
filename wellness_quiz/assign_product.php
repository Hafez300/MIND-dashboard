<?php 




header('Content-Type: application/json');







	 include('../config.php');


if( isset($_POST['user_id']) &&  isset($_POST['q_id']) &&  isset($_POST['product_id']) ) {

$auth_key = mysqli_real_escape_string($conn , $_POST['auth_key']);


 $user_id = mysqli_real_escape_string($conn , $_POST['user_id']);

 $question_id = mysqli_real_escape_string($conn ,$_POST['q_id']);
$product_id = mysqli_real_escape_string($conn ,$_POST['product_id']);
 


$sql = "SELECT auth_key FROM auth where auth_key='$auth_key'";
 $key = mysqli_query($conn,$sql);
 
 if (mysqli_num_rows($key) >= 1) {


    
    $rating_sql = "insert into wellness_products (`question_id`,
  `product_id`
   ) values ('$question_id','$product_id') ";
    


$register = mysqli_query($conn,$rating_sql) or $error = mysqli_error($conn);


	

	

if ($register) {
    
    


    
echo json_encode(array('status' => true,'msg' => 'success',"q_id"=>$question_id));



mysqli_close($conn);
}

else 
{
 
 echo json_encode(array('status' => false,'msg' => 'Nothing changed',"q_id"=>$error));


mysqli_close($conn);   
    
}




}
else
{
    
     echo json_encode(array('status' => false,'msg' => 'Wrong key',"q_id"=>null));
}

}
else 
{
    
    echo json_encode(array('status' => false,'msg' => ' user_id is required',"q_id"=>null));
}


?>